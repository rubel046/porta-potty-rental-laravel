<?php $__env->startSection('title', 'Terms of Service | Porta Potty Rental USA'); ?>
<?php $__env->startSection('meta_description', 'Terms of Service for Porta Potty Rental USA. Read our terms and conditions for using our services.'); ?>
<?php $__env->startSection('canonical', route('terms')); ?>

<?php $__env->startPush('schema'); ?>
<?php
$url = url('/');
$phone = phone_raw();

$organizationSchema = [
    "@context" => "https://schema.org",
    "@type" => "Organization",
    "@id" => $url . "#organization",
    "name" => "Potty Direct",
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
?>
<script type="application/ld+json"><?php echo json_encode($organizationSchema, JSON_UNESCAPED_SLASHES); ?></script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    
    <section class="relative py-16 md:py-20 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900"></div>
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 right-20 text-[180px]">📋</div>
        </div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-3xl"></div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-5">
                Terms of Service
            </h1>
            <p class="text-xl text-slate-300">The terms and conditions for using our services</p>
        </div>
    </section>

    
    <section class="py-12 md:py-16 px-4">
        <div class="max-w-3xl mx-auto">
            <p class="text-sm text-slate-500 mb-8">Last updated: <?php echo e(date('F d, Y')); ?></p>

            
            <div class="mb-10">
                <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                    <span class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600 text-sm">1</span>
                    Service Description
                </h2>
                <p class="text-slate-600 leading-relaxed">
                    We connect customers seeking portable toilet rental services with local rental providers. We act as a lead generation service and do not directly own or operate portable toilet inventory.
                </p>
            </div>

            
            <div class="mb-10">
                <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                    <span class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600 text-sm">2</span>
                    Pricing
                </h2>
                <p class="text-slate-600 leading-relaxed">
                    Prices displayed on our website are estimates and may vary based on location, availability, and specific requirements. Final pricing is determined by the local rental provider during your phone consultation.
                </p>
                <div class="mt-4 bg-amber-50 border border-amber-200 rounded-xl p-4">
                    <p class="text-amber-800 text-sm flex items-start gap-2">
                        <span class="text-amber-500">💡</span>
                        <span>Always confirm final pricing with your local provider before confirming your rental.</span>
                    </p>
                </div>
            </div>

            
            <div class="mb-10">
                <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                    <span class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600 text-sm">3</span>
                    Service Availability
                </h2>
                <p class="text-slate-600 leading-relaxed">
                    Service availability, including same-day delivery, is subject to the local provider's capacity and inventory. We make every effort to connect you with available providers in your area.
                </p>
            </div>

            
            <div class="mb-10">
                <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                    <span class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600 text-sm">4</span>
                    Rental Terms
                </h2>
                <ul class="space-y-3 text-slate-600">
                    <li class="flex items-start gap-3">
                        <span class="text-emerald-500 mt-1">✓</span>
                        <span>Rentals can be daily, weekly, or monthly</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-emerald-500 mt-1">✓</span>
                        <span>Delivery and pickup are included in the rental price</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-emerald-500 mt-1">✓</span>
                        <span>Weekly servicing is included for weekly and monthly rentals</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-emerald-500 mt-1">✓</span>
                        <span>No long-term contracts required</span>
                    </li>
                </ul>
            </div>

            
            <div class="mb-10">
                <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                    <span class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center text-red-600 text-sm">5</span>
                    Limitation of Liability
                </h2>
                <p class="text-slate-600 leading-relaxed mb-4">
                    We are a referral service and are not liable for the quality, timeliness, or performance of services provided by local rental companies.
                </p>
                <p class="text-slate-600 leading-relaxed">
                    Any disputes regarding rental services should be resolved directly with the rental provider.
                </p>
            </div>

            
            <div class="mb-10">
                <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                    <span class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600 text-sm">6</span>
                    User Responsibilities
                </h2>
                <ul class="space-y-2 text-slate-600">
                    <li class="flex items-start gap-2">
                        <span class="text-emerald-500 mt-1">•</span>
                        Ensure adequate access for delivery and pickup
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-emerald-500 mt-1">•</span>
                        Report any issues promptly to the rental provider
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-emerald-500 mt-1">•</span>
                        Use units only for their intended purpose
                    </li>
                </ul>
            </div>

            
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-8 md:p-10 text-white">
                <h2 class="text-2xl font-bold mb-3">Questions?</h2>
                <p class="text-slate-300 mb-6">
                    For questions about these terms, call us today.
                </p>
                <a href="tel:<?php echo e(phone_raw()); ?>"
                   class="inline-flex items-center gap-3 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500
                          text-white font-bold text-xl py-3 px-8 rounded-full
                          transition-all hover:scale-105 shadow-xl shadow-emerald-500/30">
                    📞 <?php echo e(phone_display()); ?>

                </a>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/hasanulrubel/Playground/PPC/laravel porta potty/porta-potty-app/resources/views/pages/terms.blade.php ENDPATH**/ ?>