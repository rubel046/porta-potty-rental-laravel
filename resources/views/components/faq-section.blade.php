@props([
    'faqs' => [],
    'title' => 'Frequently Asked Questions',
    'subtitle' => '',
    'id' => 'faq-section',
])

@if(count($faqs) > 0)
    @php
        $faqSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => array_map(fn($faq) => [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => strip_tags($faq['answer']),
                ],
            ], $faqs),
        ];
    @endphp

    @push('schema')
    <script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES) !!}</script>
    @endpush

    <section id="{{ $id }}" class="faq-section py-12 sm:py-16 px-4 {{ $attributes->get('class') }}">
        <div class="max-w-3xl mx-auto">
            @if($title)
                <div class="text-center mb-8 sm:mb-10">
                    <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-3">{{ $title }}</h2>
                    @if($subtitle)
                        <p class="text-slate-500">{{ $subtitle }}</p>
                    @endif
                </div>
            @endif

            <div class="space-y-3" x-data="{ openFaq: null }">
                @foreach($faqs as $index => $faq)
                    @php $faqId = 'faq-' . \Illuminate\Support\Str::slug($faq['question']) . '-' . $index; @endphp
                    <div class="bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md transition-all overflow-hidden">
                        <button type="button"
                                @click="openFaq = openFaq === {{ $index }} ? null : {{ $index }}"
                                :aria-expanded="openFaq === {{ $index }}"
                                aria-controls="{{ $faqId }}"
                                class="w-full flex justify-between items-center p-4 sm:p-5 cursor-pointer font-semibold text-slate-800 hover:text-blue-600 transition text-left min-h-[44px]">
                            <span class="text-sm sm:text-base pr-4">{{ $faq['question'] }}</span>
                            <span class="text-xl sm:text-2xl text-slate-400 flex-shrink-0 w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center transition-all duration-300"
                                  :class="openFaq === {{ $index }} ? 'rotate-45 bg-blue-100 text-blue-500' : 'bg-slate-100'"
                                  x-bind:class="openFaq === {{ $index }} ? 'rotate-45 bg-blue-100 text-blue-500' : 'bg-slate-100 group-hover:bg-blue-100'">+</span>
                        </button>
                        <div x-show="openFaq === {{ $index }}"
                             x-collapse
                             x-cloak
                             id="{{ $faqId }}"
                             role="region">
                            <div class="px-4 sm:px-5 pb-4 sm:pb-5 text-slate-600 leading-relaxed text-sm sm:text-base">
                                {!! $faq['answer'] !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
