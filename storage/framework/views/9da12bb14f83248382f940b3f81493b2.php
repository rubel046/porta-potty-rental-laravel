<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Admin'); ?> — Porta Potty Rental</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50">

<div class="flex h-screen overflow-hidden">

    
    <aside class="w-64 bg-slate-900 text-white flex-shrink-0 flex flex-col hidden md:flex">
        <div class="p-4 border-b border-slate-700/50">
            <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center text-xl shadow-lg">🚽</div>
                <div>
                    <div class="font-bold text-sm leading-tight">Porta Potty</div>
                    <div class="text-xs text-slate-400">Admin Panel</div>
                </div>
            </a>
        </div>

        <nav class="flex-1 overflow-y-auto py-4 space-y-1">
            <div class="px-4 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Overview</div>
            <a href="<?php echo e(route('admin.dashboard')); ?>"
               class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.dashboard') ? 'bg-green-600/20 text-green-400 border-r-0' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span>Dashboard</span>
            </a>

            <div class="px-4 mt-6 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Management</div>

            <a href="<?php echo e(route('admin.cities.index')); ?>"
               class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.cities.*') ? 'bg-green-600/20 text-green-400 border-r-0' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                <span>Cities</span>
            </a>

            <a href="<?php echo e(route('admin.service-pages.index')); ?>"
               class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.service-pages.*') ? 'bg-green-600/20 text-green-400 border-r-0' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Service Pages</span>
            </a>

            <a href="<?php echo e(route('admin.buyers.index')); ?>"
               class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.buyers.*') ? 'bg-green-600/20 text-green-400 border-r-0' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span>Buyers</span>
            </a>

            <a href="<?php echo e(route('admin.phone-numbers.index')); ?>"
               class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.phone-numbers.*') ? 'bg-green-600/20 text-green-400 border-r-0' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                <span>Phone Numbers</span>
            </a>

            <a href="<?php echo e(route('admin.invoices.index')); ?>"
               class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.invoices.*') ? 'bg-green-600/20 text-green-400 border-r-0' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                <span>Invoices</span>
            </a>

            <a href="<?php echo e(route('admin.blog-posts.index')); ?>"
               class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.blog-posts.*') ? 'bg-green-600/20 text-green-400 border-r-0' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                <span>Blog Posts</span>
            </a>

            <div class="px-4 mt-6 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Analytics</div>

            <a href="<?php echo e(route('admin.reports')); ?>"
               class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.reports') ? 'bg-green-600/20 text-green-400 border-r-0' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                <span>Reports</span>
            </a>

            <a href="<?php echo e(route('admin.calls.index')); ?>"
               class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.calls.*') ? 'bg-green-600/20 text-green-400 border-r-0' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                <span>All Calls</span>
            </a>
        </nav>

        <div class="p-4 border-t border-slate-700/50">
            <a href="<?php echo e(route('home')); ?>"
               class="flex items-center gap-2 text-sm text-slate-400 hover:text-white transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                <span>Back to Site</span>
            </a>
        </div>
    </aside>

    
    <div class="flex-1 flex flex-col overflow-hidden">
        
        <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-30">
            <div class="px-6 py-4 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    
                    <button class="md:hidden p-2 hover:bg-gray-100 rounded-lg" onclick="document.querySelector('aside').classList.toggle('hidden')">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <h1 class="text-xl font-bold text-gray-800"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h1>
                </div>
                <div class="flex items-center gap-4">
                    
                    <div class="hidden lg:flex items-center gap-4 px-4 py-2 bg-gray-50 rounded-lg">
                        <div class="text-center">
                            <div class="text-lg font-bold text-green-600"><?php echo e($todayCalls ?? 0); ?></div>
                            <div class="text-xs text-gray-400">Today's Calls</div>
                        </div>
                        <div class="w-px h-8 bg-gray-200"></div>
                        <div class="text-center">
                            <div class="text-lg font-bold text-green-600">$<?php echo e(number_format($todayRevenue ?? 0, 2)); ?></div>
                            <div class="text-xs text-gray-400">Today's Revenue</div>
                        </div>
                    </div>

                    
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-3 p-1.5 hover:bg-gray-100 rounded-lg transition">
                            <div class="w-9 h-9 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                <?php echo e(strtoupper(substr(Auth::user()->name ?? 'A', 0, 1))); ?>

                            </div>
                            <div class="hidden md:block text-left">
                                <div class="text-sm font-medium text-gray-800"><?php echo e(Auth::user()->name ?? 'Admin'); ?></div>
                                <div class="text-xs text-gray-400">Admin</div>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50" style="display: none;">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <div class="text-sm font-medium text-gray-800"><?php echo e(Auth::user()->name); ?></div>
                                <div class="text-xs text-gray-500"><?php echo e(Auth::user()->email); ?></div>
                            </div>
                            <a href="<?php echo e(route('profile.edit')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Profile Settings
                            </a>
                            <form method="POST" action="<?php echo e(route('logout')); ?>" class="border-t border-gray-100 mt-2 pt-2">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        
        <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
            <?php if(session('success')): ?>
                <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
</div>

<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH /Users/hasanulrubel/Playground/PPC/laravel porta potty/porta-potty-app/resources/views/admin/layout.blade.php ENDPATH**/ ?>