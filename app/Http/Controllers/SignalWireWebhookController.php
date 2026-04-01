<?php

namespace App\Http\Controllers;

use App\Services\SignalWireService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class SignalWireWebhookController extends Controller
{
    public function __construct(
        protected SignalWireService $signalWire
    ) {}

    /**
     * Incoming Call
     */
    public function incoming(Request $request): Response
    {
        Log::channel('calls')->info('Incoming call', $request->all());

        $xml = $this->signalWire->generateIncomingResponse(
            callerNumber: $request->input('From', ''),
            calledNumber: $request->input('To', ''),
            callSid: $request->input('CallSid', ''),
        );

        return response($xml, 200)
            ->header('Content-Type', 'text/xml');
    }

    /**
     * IVR Gather Response
     */
    public function gather(Request $request): Response
    {
        Log::channel('calls')->info('Gather response', $request->all());

        $xml = $this->signalWire->generateGatherResponse(
            digits: $request->input('Digits', ''),
            callLogId: (int) $request->input('call_log_id', 0),
            callerNumber: $request->input('From', ''),
        );

        return response($xml, 200)
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Call Whisper (Buyer hears this)
     */
    public function whisper(Request $request): Response
    {
        $xml = $this->signalWire->generateWhisperResponse();

        return response($xml, 200)
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Call Status Update
     */
    public function status(Request $request): Response
    {
        Log::channel('calls')->info('Call status', $request->all());

        $this->signalWire->processCallStatus($request->all());

        return response('OK', 200);
    }
}
