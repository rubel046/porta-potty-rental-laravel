<?php

namespace App\Services;

use App\Models\Buyer;
use App\Models\CallLog;
use App\Models\City;
use App\Models\PhoneNumber;
use App\Models\ServicePage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SignalWireService
{
    protected string $projectId;

    protected string $apiToken;

    protected string $spaceUrl;

    protected string $baseUrl;

    public function __construct()
    {
        $this->projectId = config('services.signalwire.project_id');
        $this->apiToken = config('services.signalwire.api_token');
        $this->spaceUrl = config('services.signalwire.space_url');
        $this->baseUrl = "https://{$this->spaceUrl}/api/laml/2010-04-01/Accounts/{$this->projectId}";
    }

    /**
     * API Request পাঠান
     */
    protected function request(string $method, string $endpoint, array $data = []): ?array
    {
        try {
            $response = Http::withBasicAuth($this->projectId, $this->apiToken)
                ->$method("{$this->baseUrl}/{$endpoint}", $data);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error("SignalWire API Error: {$response->status()}", [
                'endpoint' => $endpoint,
                'response' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error("SignalWire API Exception: {$e->getMessage()}");

            return null;
        }
    }

    /**
     * Available নম্বর খুঁজুন
     */
    public function searchAvailableNumbers(string $areaCode, int $limit = 5): array
    {
        $result = $this->request('get', 'AvailablePhoneNumbers/US/Local.json', [
            'AreaCode' => $areaCode,
            'Limit' => $limit,
        ]);

        return $result['available_phone_numbers'] ?? [];
    }

    /**
     * নম্বর কিনুন
     */
    public function purchaseNumber(string $phoneNumber, ?int $cityId = null): ?PhoneNumber
    {
        $webhookBase = config('app.url');

        $result = $this->request('post', 'IncomingPhoneNumbers.json', [
            'PhoneNumber' => $phoneNumber,
            'VoiceUrl' => "{$webhookBase}/webhook/signalwire/incoming",
            'VoiceMethod' => 'POST',
            'StatusCallback' => "{$webhookBase}/webhook/signalwire/status",
            'StatusCallbackMethod' => 'POST',
        ]);

        if (! $result) {
            return null;
        }

        $areaCode = substr(preg_replace('/[^0-9]/', '', $phoneNumber), 1, 3);

        return PhoneNumber::create([
            'number' => $result['phone_number'],
            'friendly_name' => $result['friendly_name'] ?? $this->formatPhone($result['phone_number']),
            'area_code' => $areaCode,
            'city_id' => $cityId,
            'provider' => 'signalwire',
            'provider_sid' => $result['sid'],
            'status' => 'active',
            'is_active' => true,
        ]);
    }

    /**
     * ফোন নম্বর ফরম্যাট করুন
     */
    public function formatPhone(string $number): string
    {
        $digits = preg_replace('/[^0-9]/', '', $number);

        if (strlen($digits) === 11 && $digits[0] === '1') {
            $digits = substr($digits, 1);
        }

        if (strlen($digits) === 10) {
            return sprintf('(%s) %s-%s',
                substr($digits, 0, 3),
                substr($digits, 3, 3),
                substr($digits, 6, 4)
            );
        }

        return $number;
    }

    /**
     * সবচেয়ে ভালো বায়ার খুঁজুন
     */
    public function findBestBuyer(string $calledNumber): ?Buyer
    {
        // প্রথমে কোন শহরের নম্বরে কল এসেছে দেখুন
        $phoneNumber = PhoneNumber::where('number', $calledNumber)
            ->where('is_active', true)
            ->first();

        $cityId = $phoneNumber?->city_id;

        $query = Buyer::active()->orderBy('priority');

        // শহর-স্পেসিফিক বায়ার আগে
        if ($cityId) {
            $query->orderByRaw("
                CASE WHEN JSON_CONTAINS(serving_cities, ?, '$') THEN 0 ELSE 1 END
            ", [$cityId]);
        }

        $buyers = $query->get();

        foreach ($buyers as $buyer) {
            if ($buyer->isAvailable()) {
                return $buyer;
            }
        }

        return null;
    }

    /**
     * ডুপ্লিকেট কলার চেক
     */
    public function isDuplicate(string $callerNumber): bool
    {
        $hours = (int) config('services.signalwire.duplicate_hours', 72);

        return CallLog::isDuplicateCaller($callerNumber, $hours);
    }

    /**
     * LaML Response জেনারেট করুন — IVR সহ
     */
    public function generateIncomingResponse(
        string $callerNumber,
        string $calledNumber,
        string $callSid
    ): string {
        $isDuplicate = $this->isDuplicate($callerNumber);
        $buyer = $this->findBestBuyer($calledNumber);

        // Phone number থেকে city খুঁজুন
        $phoneNumber = PhoneNumber::where('number', $calledNumber)->first();
        $cityId = $phoneNumber?->city_id;

        // Call log তৈরি
        $callLog = CallLog::create([
            'call_sid' => $callSid,
            'caller_number' => $callerNumber,
            'called_number' => $calledNumber,
            'phone_number_id' => $phoneNumber?->id,
            'city_id' => $cityId,
            'status' => 'ringing',
            'is_duplicate' => $isDuplicate,
            'call_started_at' => now(),
        ]);

        if (! $buyer) {
            return $this->noAgentResponse();
        }

        // Phone number stats update
        if ($phoneNumber) {
            $phoneNumber->increment('total_calls');
        }

        $callLog->update([
            'buyer_id' => $buyer->id,
            'forwarded_to' => $buyer->phone,
        ]);

        return $this->ivrResponse($buyer, $callerNumber, $callLog->id);
    }

    /**
     * IVR Response
     */
    protected function ivrResponse(Buyer $buyer, string $callerNumber, int $callLogId): string
    {
        $gatherUrl = url("/webhook/signalwire/gather?call_log_id={$callLogId}");
        $statusUrl = url('/webhook/signalwire/status');
        $whisperUrl = url('/webhook/signalwire/whisper');

        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<Response>
    <Gather numDigits="1" timeout="5" action="{$gatherUrl}" method="POST">
        <Say voice="Polly.Joanna" language="en-US">
            Thank you for calling about a porta potty rental.
            For a new rental quote, press 1.
            For existing rental support, press 2.
        </Say>
    </Gather>
    <Say voice="Polly.Joanna" language="en-US">
        We didn't receive your selection. Let me connect you with a specialist.
    </Say>
    <Dial callerId="{$callerNumber}" timeout="{$buyer->ring_timeout}" record="record-from-answer" action="{$statusUrl}">
        <Number url="{$whisperUrl}">{$buyer->phone}</Number>
    </Dial>
    <Say voice="Polly.Joanna" language="en-US">
        We're sorry, all specialists are currently busy. Please try again shortly.
    </Say>
</Response>
XML;
    }

    /**
     * No Agent Available Response
     */
    protected function noAgentResponse(): string
    {
        return <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<Response>
    <Say voice="Polly.Joanna" language="en-US">
        Thank you for calling. We are currently experiencing high call volume.
        Please try again in a few minutes, or visit our website for more information.
        Thank you.
    </Say>
    <Hangup/>
</Response>
XML;
    }

    /**
     * Gather Response (IVR selection পরে)
     */
    public function generateGatherResponse(
        string $digits,
        int $callLogId,
        string $callerNumber
    ): string {
        $callLog = CallLog::find($callLogId);
        $statusUrl = url('/webhook/signalwire/status');
        $whisperUrl = url('/webhook/signalwire/whisper');

        if ($digits === '1' && $callLog) {
            $callLog->update(['ivr_passed' => true]);

            $buyer = Buyer::find($callLog->buyer_id);

            if ($buyer && $buyer->isAvailable()) {
                return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<Response>
    <Say voice="Polly.Joanna" language="en-US">
        Excellent! Connecting you with a rental specialist now.
    </Say>
    <Dial callerId="{$callerNumber}" timeout="{$buyer->ring_timeout}" record="record-from-answer" action="{$statusUrl}">
        <Number url="{$whisperUrl}">{$buyer->phone}</Number>
    </Dial>
    <Say voice="Polly.Joanna" language="en-US">
        We're sorry, the specialist is unavailable. Please try again shortly.
    </Say>
</Response>
XML;
            }
        }

        if ($digits === '2') {
            if ($callLog) {
                $callLog->update([
                    'ivr_passed' => false,
                    'disqualification_reason' => 'existing_customer',
                ]);
            }

            return <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<Response>
    <Say voice="Polly.Joanna" language="en-US">
        For existing rental support, please contact your rental provider directly.
        Thank you for calling.
    </Say>
    <Hangup/>
</Response>
XML;
        }

        return $this->noAgentResponse();
    }

    /**
     * Whisper Response (বায়ার শুনবে)
     */
    public function generateWhisperResponse(): string
    {
        return <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<Response>
    <Say voice="Polly.Joanna" language="en-US">
        New porta potty rental lead. Press any key to accept.
    </Say>
    <Pause length="3"/>
</Response>
XML;
    }

    /**
     * Call Status Process করুন
     */
    public function processCallStatus(array $data): void
    {
        $callSid = $data['CallSid'] ?? null;
        $status = $data['CallStatus'] ?? 'unknown';
        $duration = (int) ($data['CallDuration'] ?? $data['Duration'] ?? 0);
        $recordingUrl = $data['RecordingUrl'] ?? null;

        if (! $callSid) {
            return;
        }

        $callLog = CallLog::where('call_sid', $callSid)->first();
        if (! $callLog) {
            return;
        }

        $callLog->update([
            'status' => $status,
            'duration_seconds' => $duration,
            'recording_url' => $recordingUrl,
            'call_ended_at' => now(),
        ]);

        // SignalWire cost estimate
        $costPerMinute = 0.02; // $0.01 incoming + $0.01 outgoing
        $cost = ceil($duration / 60) * $costPerMinute;
        $callLog->update(['cost' => $cost]);

        // Qualify the call
        $callLog->qualify();

        // Buyer stats update
        if ($callLog->is_billable && $callLog->buyer_id) {
            $buyer = Buyer::find($callLog->buyer_id);
            if ($buyer) {
                $buyer->increment('total_calls');
                $buyer->increment('total_billed', $callLog->payout);
            }
        }

        // Service page stats update
        if ($callLog->is_qualified && $callLog->service_page_id) {
            ServicePage::find($callLog->service_page_id)?->incrementCalls();
        }
    }
}
