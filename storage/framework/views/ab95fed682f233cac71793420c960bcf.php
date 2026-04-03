<?php $__env->startSection('title', $servicePage->seo_title); ?>
<?php $__env->startSection('meta_description', $servicePage->seo_description); ?>
<?php $__env->startSection('canonical', url($servicePage->slug)); ?>
<?php $__env->startSection('phone_display', $servicePage->phone_display); ?>
<?php $__env->startSection('phone_raw', $servicePage->phone_raw); ?>

<?php $__env->startPush('schema'); ?>
<?php
$breadcrumbSchema = [
    "@context" => "https://schema.org",
    "@type" => "BreadcrumbList",
    "itemListElement" => [
        ["@type" => "ListItem", "position" => 1, "name" => "Home", "item" => route('home')],
        ["@type" => "ListItem", "position" => 2, "name" => $city->state->name, "item" => route('state.page', $city->state->slug)],
        ["@type" => "ListItem", "position" => 3, "name" => $city->name, "item" => url($servicePage->slug)]
    ]
];
?>
<script type="application/ld+json"><?php echo json_encode($schemaMarkup, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?></script>
<?php if(!empty($faqSchema)): ?>
<script type="application/ld+json"><?php echo json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?></script>
<?php endif; ?>
<script type="application/ld+json"><?php echo json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES); ?></script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    
    <?php
        $heroImages = [
            'hero-banner-images/11. 20260224_191225_782.webp',
            'hero-banner-images/11. 20260226_230456_870.webp',
            'hero-banner-images/14. 20260226_224730_961.webp',
            'hero-banner-images/16. 20260226_230059_253.webp',
        ];
        $randomHero = $heroImages[array_rand($heroImages)];
        $heroUrl = asset('storage/' . $randomHero);
    ?>

    <section class="relative min-h-[500px] md:min-h-[580px] flex items-center overflow-hidden">
        
        <div class="absolute inset-0">
            <img src="<?php echo e($heroUrl); ?>" alt="Porta potty rental in <?php echo e($city->name); ?>"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 via-slate-900/75 to-slate-900/60"></div>
        </div>

        
        <div class="absolute top-20 right-10 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-20 md:py-28 w-full">
            <div class="max-w-3xl">
                
                <nav class="flex items-center gap-2 text-sm text-slate-300 mb-6">
                    <a href="<?php echo e(route('home')); ?>" class="hover:text-white transition">Home</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <a href="<?php echo e(route('state.page', $city->state->slug)); ?>" class="hover:text-white transition">
                        <?php echo e($city->state->name); ?>

                    </a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-white"><?php echo e($city->name); ?></span>
                </nav>

                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-5 leading-tight">
                    <?php echo e($servicePage->h1_title); ?>

                </h1>

                <p class="text-xl text-slate-300 mb-8 max-w-2xl">
                    Clean, affordable portable toilets delivered to your
                    <?php echo e($city->name); ?> location. Same-day delivery available.
                </p>

                
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 mb-8">
                    <a href="tel:<?php echo e($servicePage->phone_raw); ?>"
                       class="w-full sm:w-auto bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700
                              text-white text-2xl md:text-3xl font-bold
                              py-4 px-10 rounded-full shadow-2xl shadow-emerald-500/30
                              transition-all hover:scale-105 flex items-center justify-center gap-3">
                        <span class="text-2xl">📞</span>
                        <?php echo e($servicePage->phone_display); ?>

                    </a>
                    <a href="<?php echo e(route('locations')); ?>"
                       class="text-slate-300 hover:text-white text-sm font-medium transition flex items-center gap-2">
                        ← View All Locations
                    </a>
                </div>

                
                <div class="flex flex-wrap items-center gap-5 text-sm text-slate-300">
                    <span class="flex items-center gap-1.5"><span class="text-yellow-400">⭐⭐⭐⭐⭐</span> 500+ Reviews</span>
                    <span class="text-slate-600">•</span>
                    <span class="flex items-center gap-1.5"><span class="text-emerald-400">✓</span> Licensed & Insured</span>
                    <span class="text-slate-600">•</span>
                    <span class="flex items-center gap-1.5"><span class="text-emerald-400">✓</span> Same-Day Delivery</span>
                    <span class="text-slate-600">•</span>
                    <span class="flex items-center gap-1.5"><span class="text-emerald-400">✓</span> No Hidden Fees</span>
                </div>
            </div>
        </div>

        
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
                <path d="M0,40 C360,80 720,0 1080,40 C1260,60 1380,50 1440,40 L1440,80 L0,80 Z" fill="white"/>
            </svg>
        </div>
    </section>

    
    <article class="py-12 md:py-16 px-4">
        <div class="max-w-4xl mx-auto">
            
            <div class="prose prose-lg max-w-none
                        prose-headings:text-slate-800 prose-headings:font-bold
                        prose-h2:text-2xl prose-h2:mt-12 prose-h2:mb-5 prose-h2:border-b prose-h2:border-slate-200 prose-h2:pb-3
                        prose-h3:text-xl prose-h3:mt-8 prose-h3:mb-3
                        prose-p:text-slate-600 prose-p:leading-relaxed prose-p:mb-5
                        prose-li:text-slate-600 prose-li:leading-relaxed
                        prose-li:mb-2
                        prose-a:text-emerald-600 prose-a:no-underline hover:prose-a:underline
                        prose-strong:text-slate-800 prose-strong:font-semibold
                        prose-blockquote:border-l-emerald-500 prose-blockquote:bg-emerald-50 prose-blockquote:rounded-r-xl
                        prose-table:text-sm
                        prose-th:bg-slate-100 prose-th:p-3 prose-th:font-semibold
                        prose-td:p-4 prose-td:border prose-td:border-slate-100
                        prose-img:rounded-xl prose-img:shadow-lg">
                <?php echo Str::markdown($servicePage->content); ?>

            </div>

            
            <div class="my-12 bg-gradient-to-r from-slate-800 to-slate-900 rounded-2xl p-8 md:p-10 text-center">
                <h3 class="text-2xl font-bold text-white mb-3">
                    Ready to Get a Quote?
                </h3>
                <p class="text-slate-400 mb-6">
                    Call now for instant pricing on porta potty rental in <?php echo e($city->name); ?>

                </p>
                <a href="tel:<?php echo e($servicePage->phone_raw); ?>"
                   class="inline-flex items-center gap-3 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500
                          text-white font-bold text-xl py-4 px-10 rounded-full
                          transition-all hover:scale-105 shadow-xl shadow-emerald-500/30">
                    📞 <?php echo e($servicePage->phone_display); ?>

                </a>
                <p class="text-sm text-slate-400 mt-4">
                    Mon-Sat 7AM-8PM • Same-Day Delivery Available
                </p>
            </div>
        </div>
    </article>

    
    <?php if($testimonials->isNotEmpty()): ?>
        <section class="py-12 md:py-16 px-4 bg-slate-50">
            <div class="max-w-5xl mx-auto">
                <h2 class="text-2xl md:text-3xl font-bold text-center mb-10">
                    What <?php echo e($city->name); ?> Customers Say
                </h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm hover:shadow-lg transition-all">
                            <div class="text-yellow-400 mb-4">
                                <?php for($i = 0; $i < $testimonial->rating; $i++): ?>⭐<?php endfor; ?>
                            </div>
                            <p class="text-slate-700 mb-4 italic leading-relaxed">
                                "<?php echo e($testimonial->content); ?>"
                            </p>
                            <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                                <div class="w-9 h-9 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm">
                                    <?php echo e(substr($testimonial->customer_name, 0, 1)); ?>

                                </div>
                                <div>
                                    <p class="font-bold text-sm text-slate-800"><?php echo e($testimonial->customer_name); ?></p>
                                    <?php if($testimonial->customer_title): ?>
                                        <p class="text-xs text-slate-500"><?php echo e($testimonial->customer_title); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    
    <?php if($faqs->isNotEmpty()): ?>
        <section class="py-12 md:py-16 px-4">
            <div class="max-w-3xl mx-auto">
                <h2 class="text-2xl md:text-3xl font-bold text-center mb-10">
                    Frequently Asked Questions — <?php echo e($city->name); ?>, <?php echo e($city->state->code); ?>

                </h2>
                <div class="space-y-3">
                    <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <details class="bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md transition-all group">
                            <summary class="flex justify-between items-center p-5 cursor-pointer
                                    font-semibold text-slate-800 hover:text-emerald-600 transition list-none">
                                <span><?php echo e($faq->question); ?></span>
                                <span class="text-2xl text-slate-400 group-open:rotate-45 group-open:text-emerald-500
                                     transition-all duration-300 ml-4 flex-shrink-0 bg-slate-100 group-hover:bg-emerald-100 w-8 h-8 rounded-full flex items-center justify-center">+</span>
                            </summary>
                            <div class="px-5 pb-5 text-slate-600 leading-relaxed">
                                <?php echo e($faq->answer); ?>

                            </div>
                        </details>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    
    <?php if($otherServices->isNotEmpty()): ?>
        <section class="py-12 md:py-16 px-4 bg-slate-50">
            <div class="max-w-5xl mx-auto">
                <h2 class="text-2xl md:text-3xl font-bold text-center mb-10">
                    Other Services in <?php echo e($city->name); ?>

                </h2>
                <div class="grid md:grid-cols-3 gap-4">
                    <?php $__currentLoopData = $otherServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(url($service->slug)); ?>"
                           class="bg-white p-6 rounded-xl shadow-sm hover:shadow-lg hover:border-emerald-300
                          transition-all text-center group border border-slate-200">
                            <h3 class="font-bold text-slate-800 group-hover:text-emerald-600 transition">
                                <?php echo e($service->service_type_label); ?>

                            </h3>
                            <p class="text-sm text-slate-400 mt-1">in <?php echo e($city->name); ?></p>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    
    <?php if($nearbyCityPages->isNotEmpty()): ?>
        <section class="py-12 md:py-16 px-4">
            <div class="max-w-5xl mx-auto">
                <h2 class="text-2xl md:text-3xl font-bold text-center mb-10">
                    Porta Potty Rental Near <?php echo e($city->name); ?>

                </h2>
                <div class="flex flex-wrap justify-center gap-3">
                    <?php $__currentLoopData = $nearbyCityPages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nearbyCity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $nearbyPage = $nearbyCity->getServicePage('general'); ?>
                        <?php if($nearbyPage): ?>
                            <a href="<?php echo e(url($nearbyPage->slug)); ?>"
                               class="bg-white hover:bg-emerald-50 border border-slate-200 hover:border-emerald-300
                              px-5 py-3 rounded-xl text-sm font-medium text-slate-700
                              hover:text-emerald-700 transition-all shadow-sm hover:shadow-md flex items-center gap-2">
                                📍 <?php echo e($nearbyCity->name); ?>, <?php echo e($nearbyCity->state->code); ?>

                            </a>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    
    <?php if($relatedPosts->isNotEmpty()): ?>
        <section class="py-12 md:py-16 px-4 bg-slate-50">
            <div class="max-w-5xl mx-auto">
                <h2 class="text-2xl md:text-3xl font-bold text-center mb-10">
                    Helpful Resources
                </h2>
                <div class="grid md:grid-cols-3 gap-6">
                    <?php $__currentLoopData = $relatedPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e($post->url); ?>"
                           class="bg-white rounded-xl shadow-sm hover:shadow-lg transition overflow-hidden group border border-slate-200">
                            <div class="h-32 bg-gradient-to-br from-blue-100 to-emerald-50 flex items-center justify-center text-4xl">
                                🚽
                            </div>
                            <div class="p-5">
                                <h3 class="font-bold text-slate-800 group-hover:text-emerald-600
                                   transition mb-2 line-clamp-2">
                                    <?php echo e($post->title); ?>

                                </h3>
                                <p class="text-sm text-slate-500 flex items-center gap-1.5">
                                    📖 <?php echo e($post->reading_time_text); ?>

                                </p>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    
    <section class="py-16 md:py-24 px-4 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white text-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-10 left-10 text-[200px]">🚽</div>
        </div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-3xl"></div>

        <div class="relative max-w-3xl mx-auto">
            <h2 class="text-3xl md:text-5xl font-extrabold mb-5">
                Get Your Porta Potty Delivered in <?php echo e($city->name); ?> Today
            </h2>
            <p class="text-xl text-slate-400 mb-10">
                Free quote • No hidden fees • Same-day delivery available
            </p>
            <a href="tel:<?php echo e($servicePage->phone_raw); ?>"
               class="inline-flex items-center gap-3 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500
                      text-white text-3xl md:text-4xl font-bold py-5 px-14
                      rounded-full shadow-2xl shadow-emerald-500/40
                      transition-all hover:scale-105 animate-pulse">
                📞 <?php echo e($servicePage->phone_display); ?>

            </a>
            <p class="mt-6 text-slate-400 text-sm">Mon-Sat 7AM-8PM • Operators Standing By</p>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/hasanulrubel/Playground/PPC/laravel porta potty/porta-potty-app/resources/views/pages/service.blade.php ENDPATH**/ ?>