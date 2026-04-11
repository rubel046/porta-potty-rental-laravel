@extends(\App\Providers\DomainViewHelper::resolve('layout'))
@section('title', 'Privacy Policy | Water Damage Restoration')
@section('meta_description', 'Privacy Policy. Learn how we collect, use, and protect your information.')
@section('canonical', route('privacy'))

@push('schema')
@php
$url = url('/');
$phone = domain_phone_raw();
$domain = \App\Models\Domain::current();

$organizationSchema = [
    "@context" => "https://schema.org",
    "@type" => "Organization",
    "@id" => $url . "#organization",
    "name" => $domain?->business_name ?? "Water Damage Pro",
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
            <div class="absolute top-10 right-20 text-[180px]">🔒</div>
        </div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-3xl"></div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-5">
                Privacy Policy
            </h1>
            <p class="text-xl text-slate-300">How we protect and use your information</p>
        </div>
    </section>

    {{-- Content --}}
    <section class="py-12 md:py-16 px-4">
        <div class="max-w-3xl mx-auto">
            <p class="text-sm text-slate-500 mb-8">Last updated: {{ date('F d, Y') }}</p>

            {{-- Information We Collect --}}
            <div class="mb-10">
                <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                    <span class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600 text-sm">1</span>
                    Information We Collect
                </h2>
                <p class="text-slate-600 leading-relaxed mb-4">
                    When you call us or visit our website, we may collect:
                </p>
                <ul class="space-y-2 text-slate-600">
                    <li class="flex items-start gap-2">
                        <span class="text-emerald-500 mt-1">✓</span>
                        Your phone number and call details
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-emerald-500 mt-1">✓</span>
                        Location information for service delivery
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-emerald-500 mt-1">✓</span>
                        Details about your rental inquiry
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-emerald-500 mt-1">✓</span>
                        Website usage data via cookies
                    </li>
                </ul>
            </div>

            {{-- How We Use Your Information --}}
            <div class="mb-10">
                <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                    <span class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600 text-sm">2</span>
                    How We Use Your Information
                </h2>
                <p class="text-slate-600 leading-relaxed mb-4">
                    We use your information solely to:
                </p>
                <ul class="space-y-2 text-slate-600">
                    <li class="flex items-start gap-2">
                        <span class="text-emerald-500 mt-1">✓</span>
                        Process your rental inquiry
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-emerald-500 mt-1">✓</span>
                        Connect you with a local rental provider
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-emerald-500 mt-1">✓</span>
                        Improve our services
                    </li>
                </ul>
                <div class="mt-4 bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <p class="text-blue-800 text-sm">
                        <strong>Note:</strong> We do not sell your personal information to third parties.
                    </p>
                </div>
            </div>

            {{-- Call Recording --}}
            <div class="mb-10">
                <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                    <span class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600 text-sm">3</span>
                    Call Recording
                </h2>
                <p class="text-slate-600 leading-relaxed">
                    Phone calls may be recorded for quality assurance and training purposes. By calling our number, you consent to call recording.
                </p>
            </div>

            {{-- Cookies --}}
            <div class="mb-10">
                <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                    <span class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600 text-sm">4</span>
                    Cookies
                </h2>
                <p class="text-slate-600 leading-relaxed">
                    Our website uses cookies to improve your browsing experience and analyze website traffic. You can disable cookies in your browser settings.
                </p>
            </div>

            {{-- Data Security --}}
            <div class="mb-10">
                <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                    <span class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600 text-sm">5</span>
                    Data Security
                </h2>
                <p class="text-slate-600 leading-relaxed">
                    We take reasonable measures to protect your personal information from unauthorized access, use, or disclosure.
                </p>
            </div>

            {{-- Contact --}}
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-8 md:p-10 text-white">
                <h2 class="text-2xl font-bold mb-3">Contact Us</h2>
                <p class="text-slate-300 mb-6">
                    If you have questions about this privacy policy, please call us.
                </p>
                <a href="tel:{{ domain_phone_raw() }}"
                   class="inline-flex items-center gap-3 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500
                          text-white font-bold text-xl py-3 px-8 rounded-full
                          transition-all hover:scale-105 shadow-xl shadow-emerald-500/30">
                    📞 {{ domain_phone_display() }}
                </a>
            </div>
        </div>
    </section>
@endsection
