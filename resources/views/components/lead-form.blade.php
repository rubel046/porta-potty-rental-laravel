@props([
    'source' => 'web-form',
    'serviceType' => null,
    'zipDefault' => null,
    'cityName' => 'your area',
    'headline' => "Call for a free quote — real person answers in 15s",
    'subheadline' => "We'll reply in under 10 minutes during business hours.",
])

@php
    $domain = \App\Models\Domain::current();
    $serviceTypes = $domain?->getServiceTypes() ?? [];
    $justSubmitted = session()->has('lead_id');
@endphp

<section id="lead-form" class="bg-gradient-to-br from-slate-50 to-emerald-50 py-12 sm:py-16">
    <div class="max-w-3xl mx-auto px-4 sm:px-6">
        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl border border-slate-100 p-6 sm:p-10">

            @if($justSubmitted)
                <div class="text-center py-6">
                    <div class="w-16 h-16 mx-auto mb-4 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 6L9 17l-5-5"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-2">Thanks — we got your details.</h3>
                    <p class="text-slate-600 mb-5">We'll text you a quote shortly. If you need it now, tap below.</p>
                    <a href="tel:{{ domain_phone_raw() }}"
                       data-tracking-label="lead-form-success"
                       class="inline-flex items-center gap-2 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-white font-bold text-lg py-3 px-6 rounded-full shadow-lg transition hover:scale-105 min-h-[44px]">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        Call Now — {{ domain_phone_display() }}
                    </a>
                </div>
            @else
                <div class="text-center mb-6">
                    <h2 class="text-2xl sm:text-3xl font-bold text-slate-800 mb-2">{{ $headline }}</h2>
                    <p class="text-slate-500">{{ $subheadline }}</p>
                </div>

                {{-- Availability counter --}}
                <div x-data="{
                    units: 0,
                    init() {
                        let base = 2 + Math.floor(Math.random() * 6);
                        let hour = new Date().getHours();
                        if (hour >= 6 && hour <= 14) base += 3;
                        if (hour >= 17 || hour <= 5) base = Math.max(1, base - 2);
                        this.units = base;
                    }
                }" class="flex items-center justify-center gap-2 mb-5 text-sm">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                    </span>
                    <span class="text-emerald-700 font-semibold">
                        <span x-text="units"></span> units available for delivery in <strong>{{ $cityName }}</strong>
                    </span>
                </div>

                <form method="POST" action="{{ route('lead.store') }}" x-data="{ submitting: false }" @submit="submitting = true" class="space-y-4">
                    @csrf
                    <input type="hidden" name="source" value="{{ $source }}">
                    @if($serviceType)
                        <input type="hidden" name="service_type" value="{{ $serviceType }}">
                    @endif

                    {{-- Honeypot: real users don't see this; bots fill it --}}
                    <div class="hidden" aria-hidden="true">
                        <label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="lead-name" class="block text-sm font-semibold text-slate-700 mb-1.5">Name <span class="text-rose-500">*</span></label>
                            <input id="lead-name" type="text" name="name" required maxlength="255" autocomplete="name"
                                   value="{{ old('name') }}"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none transition text-base min-h-[44px]">
                            @error('name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="lead-phone" class="block text-sm font-semibold text-slate-700 mb-1.5">Phone <span class="text-rose-500">*</span></label>
                            <input id="lead-phone" type="tel" name="phone" required maxlength="20" inputmode="tel" autocomplete="tel"
                                   value="{{ old('phone') }}"
                                   placeholder="(555) 123-4567"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none transition text-base min-h-[44px]">
                            @error('phone') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="lead-zip" class="block text-sm font-semibold text-slate-700 mb-1.5">ZIP code <span class="text-slate-400 font-normal">(optional)</span></label>
                            <input id="lead-zip" type="text" name="zip" maxlength="10" inputmode="numeric" autocomplete="postal-code"
                                   value="{{ old('zip', $zipDefault) }}"
                                   placeholder="77001"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none transition text-base min-h-[44px]">
                        </div>
                        @if(! $serviceType && ! empty($serviceTypes))
                            <div>
                                <label for="lead-service" class="block text-sm font-semibold text-slate-700 mb-1.5">What do you need? <span class="text-slate-400 font-normal">(optional)</span></label>
                                <select id="lead-service" name="service_type"
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 outline-none transition text-base min-h-[44px] bg-white">
                                    <option value="">Not sure yet</option>
                                    @foreach($serviceTypes as $key => $label)
                                        <option value="{{ $key }}" @selected(old('service_type') === $key)>{{ is_array($label) ? ($label['name'] ?? $key) : $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>

                    <button type="submit"
                            :disabled="submitting"
                            :class="submitting ? 'opacity-60 cursor-not-allowed' : ''"
                            class="w-full bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500 text-white font-bold text-lg py-4 rounded-xl shadow-lg shadow-emerald-500/30 transition hover:scale-[1.01] min-h-[44px] flex items-center justify-center gap-2">
                        <span x-show="!submitting">Get My Free Quote</span>
                        <span x-show="submitting" x-cloak class="flex items-center gap-2">
                            <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Sending…
                        </span>
                    </button>

                    <p class="text-xs text-slate-400 text-center">
                        By submitting you agree we can text/call you about your rental. No spam, ever.
                    </p>
                </form>
            @endif
        </div>
    </div>
</section>
