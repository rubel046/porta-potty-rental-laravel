@extends(\App\Models\Domain::getLayoutPathStatic())
@section('title', 'Terms of Service | Plumbing Pro')
@section('meta_description', 'Terms of Service for Plumbing Pro. Read our terms and conditions for using our plumbing services.')
@section('canonical', route('terms'))

@push('schema')
@php
$url = url('/');
$phone = domain_phone_raw();

$organizationSchema = [
    "@context" => "https://schema.org",
    "@type" => "Organization",
    "@id" => $url . "#organization",
    "name" => ($domain?->business_name ?? 'Plumbing Pro'),
    "url" => $url,
    "telephone" => $phone,
    "contactPoint" => [
        "@type" => "ContactPoint",
        "telephone" => $phone,
        "contactType" => "customer service",
        "areaServed" => "US",
        "availableLanguage" => "English"
    ]
];
@endphp
<script type="application/ld+json">{!! json_encode($organizationSchema, JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')

    {{-- Hero --}}
    <section class="relative py-16 md:py-20 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900"></div>
        <div class="absolute inset-0 opacity-10">
            
        </div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-blue-500/10 rounded-full blur-3xl"></div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-5">
                Terms of Service
            </h1>
            <p class="text-xl text-slate-300">The terms and conditions for using our services</p>
        </div>
    </section>

    {{-- Content --}}
    <section class="py-12 md:py-16 px-4">
        <div class="max-w-3xl mx-auto">
            <p class="text-sm text-slate-500 mb-8">Last updated: {{ date('F d, Y') }}</p>

            {{-- Service Description --}}
            <div class="mb-10">
                <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                    <span class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 text-sm">1</span>
                    Service Description
                </h2>
                <p class="text-slate-600 leading-relaxed">
                    We connect customers seeking plumbing services with local plumbing providers. We act as a lead generation service and do not directly own or operate plumbing equipment.
                </p>
            </div>

            {{-- Pricing --}}
            <div class="mb-10">
                <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                    <span class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 text-sm">2</span>
                    Pricing
                </h2>
                <p class="text-slate-600 leading-relaxed">
                    Prices displayed on our website are estimates and may vary based on location, availability, and specific requirements. Final pricing is determined by the local plumbing provider during your phone consultation.
                </p>
                <div class="mt-4 bg-orange-50 border border-orange-200 rounded-xl p-4">
                    <p class="text-orange-800 text-sm flex items-start gap-2">
                        <svg class="w-4 h-4 text-orange-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span>Always confirm final pricing with your local provider before scheduling service.</span>
                    </p>
                </div>
            </div>

            {{-- Service Availability --}}
            <div class="mb-10">
                <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                    <span class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 text-sm">3</span>
                    Service Availability
                </h2>
                <p class="text-slate-600 leading-relaxed">
                    Service availability, including same-day service, is subject to the local provider's capacity and schedule. We make every effort to connect you with available providers in your area.
                </p>
            </div>

            {{-- Service Terms --}}
            <div class="mb-10">
                <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                    <span class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 text-sm">4</span>
                    Service Terms
                </h2>
                <ul class="space-y-3 text-slate-600">
                    <li class="flex items-start gap-3">
                        <span class="text-blue-500 mt-1">&#x2713;</span>
                        <span>Services can be scheduled as one-time or recurring</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-blue-500 mt-1">&#x2713;</span>
                        <span>Emergency services are available 24/7 in most areas</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-blue-500 mt-1">&#x2713;</span>
                        <span>All work is performed by licensed and insured professionals</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-blue-500 mt-1">&#x2713;</span>
                        <span>No long-term contracts required for standard service</span>
                    </li>
                </ul>
            </div>

            {{-- Limitation of Liability --}}
            <div class="mb-10">
                <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                    <span class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center text-red-600 text-sm">5</span>
                    Limitation of Liability
                </h2>
                <p class="text-slate-600 leading-relaxed mb-4">
                    We are a referral service and are not liable for the quality, timeliness, or performance of services provided by local plumbing companies.
                </p>
                <p class="text-slate-600 leading-relaxed">
                    Any disputes regarding services should be resolved directly with the service provider.
                </p>
            </div>

            {{-- User Responsibilities --}}
            <div class="mb-10">
                <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                    <span class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 text-sm">6</span>
                    User Responsibilities
                </h2>
                <ul class="space-y-2 text-slate-600">
                    <li class="flex items-start gap-2">
                        <span class="text-blue-500 mt-1">&bull;</span>
                        Ensure adequate access for service delivery
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-blue-500 mt-1">&bull;</span>
                        Report any issues promptly to the service provider
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-blue-500 mt-1">&bull;</span>
                        Provide accurate information about the issue requiring service
                    </li>
                </ul>
            </div>

            {{-- Contact --}}
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-8 md:p-10 text-white">
                <h2 class="text-2xl font-bold mb-3">Questions?</h2>
                <p class="text-slate-300 mb-6">
                    For questions about these terms, call us today.
                </p>
                <a href="tel:{{ domain_phone_raw() }}"
                   data-tracking-label="terms-cta"
                   class="inline-flex items-center gap-3 bg-orange-500 hover:bg-orange-400
                          text-white font-bold text-xl py-3 px-7 rounded-full
                          transition hover:scale-[1.02] shadow-xl shadow-orange-500/30 ring-4 ring-orange-400/30 min-h-[44px]">
                    <x-icon name="phone" class="w-5 h-5" />
                    {{ domain_phone_display() }}
                </a>
            </div>
        </div>
    </section>
@endsection
