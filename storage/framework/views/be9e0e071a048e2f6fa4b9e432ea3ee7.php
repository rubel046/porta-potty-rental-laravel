<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Admin'); ?> — Porta Potty Dashboard</title>
    <meta name="robots" content="noindex, nofollow">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="flex">
    
    <aside class="w-64 bg-gray-900 text-white min-h-screen fixed left-0 top-0
                      overflow-y-auto z-40 hidden lg:block">
        
        <div class="p-5 border-b border-gray-700">
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center gap-2">
                <span class="text-2xl">🚽</span>
                <span class="font-bold text-lg">PPR Admin</span>
            </a>
        </div>

        
        <nav class="p-4 space-y-1">
            <a href="<?php echo e(route('admin.dashboard')); ?>"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm
                          <?php echo e(request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white'); ?>

                          transition">
                <span>📊</span> Dashboard
            </a>

            <a href="<?php echo e(route('admin.calls.index')); ?>"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm
                          <?php echo e(request()->routeIs('admin.calls.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white'); ?>

                          transition">
                <span>📞</span> Call Logs
                <?php $todayCalls = \App\Models\CallLog::today()->count(); ?>
                <?php if($todayCalls > 0): ?>
                    <span class="ml-auto bg-green-500 text-white text-xs px-2 py-0.5 rounded-full">
                        <?php echo e($todayCalls); ?>

                    </span>
                <?php endif; ?>
            </a>

            <a href="<?php echo e(route('admin.cities.index')); ?>"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm
                          <?php echo e(request()->routeIs('admin.cities.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white'); ?>

                          transition">
                <span>🏙️</span> Cities
            </a>

            <a href="<?php echo e(route('admin.service-pages.index')); ?>"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm
                          <?php echo e(request()->routeIs('admin.service-pages.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white'); ?>

                          transition">
                <span>📄</span> Service Pages
            </a>

            <a href="<?php echo e(route('admin.blog-posts.index')); ?>"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm
                          <?php echo e(request()->routeIs('admin.blog-posts.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white'); ?>

                          transition">
                <span>✍️</span> Blog Posts
            </a>

            <a href="<?php echo e(route('admin.buyers.index')); ?>"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm
                          <?php echo e(request()->routeIs('admin.buyers.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white'); ?>

                          transition">
                <span>🤝</span> Buyers
            </a>

            <a href="<?php echo e(route('admin.phone-numbers.index')); ?>"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm
                          <?php echo e(request()->routeIs('admin.phone-numbers.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white'); ?>

                          transition">
                <span>📱</span> Phone Numbers
            </a>

            <a href="<?php echo e(route('admin.invoices.index')); ?>"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm
                          <?php echo e(request()->routeIs('admin.invoices.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white'); ?>

                          transition">
                <span>💵</span> Invoices
            </a>

            <a href="<?php echo e(route('admin.reports')); ?>"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm
                          <?php echo e(request()->routeIs('admin.reports*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white'); ?>

                          transition">
                <span>📈</span> Reports
            </a>

            <div class="border-t border-gray-700 my-4"></div>

            <a href="<?php echo e(route('home')); ?>" target="_blank"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm
                          text-gray-400 hover:bg-gray-800 hover:text-white transition">
                <span>🌐</span> View Site ↗
            </a>

            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm
                                   text-gray-400 hover:bg-red-600 hover:text-white transition">
                    <span>🚪</span> Logout
                </button>
            </form>
        </nav>
    </aside>

    
    <main class="flex-1 lg:ml-64">
        
        <header class="bg-white shadow-sm border-b border-gray-200 px-6 py-4
                          flex items-center justify-between sticky top-0 z-30">
            <div>
                <h1 class="text-xl font-bold text-gray-800">
                    <?php echo $__env->yieldContent('page_title', 'Dashboard'); ?>
                </h1>
                <?php if (! empty(trim($__env->yieldContent('page_subtitle')))): ?>
                    <p class="text-sm text-gray-500"><?php echo $__env->yieldContent('page_subtitle'); ?></p>
                <?php endif; ?>
            </div>

            <div class="flex items-center gap-4">
                
                <div class="hidden md:flex items-center gap-6 text-sm">
                    <?php
                        $quickCalls = \App\Models\CallLog::today()->billable()->count();
                        $quickRevenue = \App\Models\CallLog::today()->billable()->sum('payout');
                    ?>
                    <div class="text-center">
                        <div class="text-lg font-bold text-green-600"><?php echo e($quickCalls); ?></div>
                        <div class="text-xs text-gray-400">Today's Calls</div>
                    </div>
                    <div class="text-center">
                        <div class="text-lg font-bold text-green-600">$<?php echo e(number_format($quickRevenue, 2)); ?></div>
                        <div class="text-xs text-gray-400">Today's Revenue</div>
                    </div>
                </div>

                
                <div class="text-xs text-gray-400 hidden lg:block">
                    🇧🇩 <?php echo e(now()->setTimezone('Asia/Dhaka')->format('h:i A')); ?>

                </div>
            </div>
        </header>

        
        <?php if(session('success')): ?>
            <div class="mx-6 mt-4 bg-green-50 border border-green-200 text-green-700
                        px-4 py-3 rounded-lg flex items-center gap-2">
                ✅ <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="mx-6 mt-4 bg-red-50 border border-red-200 text-red-700
                        px-4 py-3 rounded-lg flex items-center gap-2">
                ❌ <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        
        <div class="p-6">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </main>
</div>

<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /Users/hasanulrubel/Playground/PPC/laravel porta potty/porta-potty-app/resources/views/layouts/admin.blade.php ENDPATH**/ ?>