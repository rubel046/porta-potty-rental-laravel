<?php $__env->startSection('title', 'Service Pages'); ?>
<?php $__env->startSection('page-title', 'Service Pages Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <div>
        <h2 class="text-lg font-semibold text-gray-800">All Service Pages</h2>
        <p class="text-sm text-gray-500">Service pages are auto-generated from cities. Create a city to add pages.</p>
    </div>
</div>


<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-medium text-gray-500 mb-1">Search Slug</label>
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search slug..." 
                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
            </div>
        </div>
        <div class="w-40">
            <label class="block text-xs font-medium text-gray-500 mb-1">City</label>
            <select name="city_id" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white hover:bg-gray-50">
                <option value="">All Cities</option>
                <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($city->id); ?>" <?php echo e(request('city_id') == $city->id ? 'selected' : ''); ?>><?php echo e($city->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="w-40">
            <label class="block text-xs font-medium text-gray-500 mb-1">Type</label>
            <select name="service_type" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white hover:bg-gray-50">
                <option value="">All Types</option>
                <option value="general" <?php echo e(request('service_type') == 'general' ? 'selected' : ''); ?>>General</option>
                <option value="construction" <?php echo e(request('service_type') == 'construction' ? 'selected' : ''); ?>>Construction</option>
                <option value="wedding" <?php echo e(request('service_type') == 'wedding' ? 'selected' : ''); ?>>Wedding</option>
                <option value="event" <?php echo e(request('service_type') == 'event' ? 'selected' : ''); ?>>Event</option>
            </select>
        </div>
        <div class="w-40" x-data="{ open: false, selected: '<?php echo e(request('published')); ?>' }">
            <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
            <div class="relative">
                <button type="button" @click="open = !open" @click.outside="open = false" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-left flex justify-between items-center bg-white hover:bg-gray-50">
                    <span x-text="selected === '1' ? 'Published' : (selected === '0' ? 'Draft' : 'All Status')"></span>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" x-transition.opacity style="display: none;" class="absolute z-20 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg">
                    <button type="button" @click="selected = ''; open = false" class="w-full px-3 py-2 text-left text-sm hover:bg-gray-50" :class="selected === '' ? 'bg-green-50 text-green-700 font-medium' : ''">All Status</button>
                    <button type="button" @click="selected = '1'; open = false" class="w-full px-3 py-2 text-left text-sm hover:bg-gray-50" :class="selected === '1' ? 'bg-green-50 text-green-700 font-medium' : ''">Published</button>
                    <button type="button" @click="selected = '0'; open = false" class="w-full px-3 py-2 text-left text-sm hover:bg-gray-50" :class="selected === '0' ? 'bg-green-50 text-green-700 font-medium' : ''">Draft</button>
                </div>
                <input type="hidden" name="published" :value="selected">
            </div>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                Filter
            </button>
            <?php if(request()->anyFilled(['search', 'city_id', 'service_type', 'published'])): ?>
                <a href="<?php echo e(route('admin.service-pages.index')); ?>" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-lg text-sm hover:bg-gray-50 transition">
                    Clear
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>


<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50/50 text-left text-xs text-gray-500 uppercase tracking-wider border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 font-medium">Slug</th>
                    <th class="px-6 py-4 font-medium">City</th>
                    <th class="px-6 py-4 font-medium">Type</th>
                    <th class="px-6 py-4 font-medium">SEO Score</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php $__empty_1 = true; $__currentLoopData = $servicePages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">
                            <div class="font-mono text-xs text-gray-900"><?php echo e(Str::limit($page->slug, 35)); ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-700"><?php echo e($page->city?->name ?? '—'); ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 capitalize"><?php echo e($page->service_type); ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($page->seo_score >= 80): ?>
                                <div class="flex items-center gap-2">
                                    <div class="w-16 h-2 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-green-500 rounded-full" style="width: <?php echo e($page->seo_score); ?>%"></div>
                                    </div>
                                    <span class="text-green-600 font-medium"><?php echo e($page->seo_score); ?></span>
                                </div>
                            <?php elseif($page->seo_score >= 50): ?>
                                <div class="flex items-center gap-2">
                                    <div class="w-16 h-2 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-yellow-500 rounded-full" style="width: <?php echo e($page->seo_score); ?>%"></div>
                                    </div>
                                    <span class="text-yellow-600 font-medium"><?php echo e($page->seo_score); ?></span>
                                </div>
                            <?php else: ?>
                                <div class="flex items-center gap-2">
                                    <div class="w-16 h-2 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-red-500 rounded-full" style="width: <?php echo e($page->seo_score); ?>%"></div>
                                    </div>
                                    <span class="text-red-600 font-medium"><?php echo e($page->seo_score); ?></span>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($page->is_published): ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                    Published
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                    <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                                    Draft
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="<?php echo e(route('admin.service-pages.show', $page)); ?>" class="p-1.5 text-gray-400 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition" title="SEO Details">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                </a>
                                <a href="<?php echo e(url($page->slug)); ?>" target="_blank" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="View">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <a href="<?php echo e(route('admin.service-pages.edit', $page)); ?>" class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p class="text-gray-500 font-medium">No service pages found</p>
                                <p class="text-sm text-gray-400 mt-1">Try adjusting your search or filters</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <?php if($servicePages->hasPages()): ?>
        <div class="px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="text-sm text-gray-500">
                <?php echo e($servicePages->firstItem()); ?> - <?php echo e($servicePages->lastItem()); ?> of <?php echo e($servicePages->total()); ?>

            </div>
            <nav class="flex items-center gap-1">
                <?php if($servicePages->currentPage() > 1): ?>
                    <a href="<?php echo e($servicePages->previousPageUrl()); ?>" class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">Previous</a>
                <?php endif; ?>
                
                <?php $__currentLoopData = $servicePages->getUrlRange(max(1, $servicePages->currentPage() - 2), min($servicePages->lastPage(), $servicePages->currentPage() + 2)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($page == $servicePages->currentPage()): ?>
                        <span class="px-3 py-1.5 text-sm rounded-lg bg-green-600 text-white font-medium"><?php echo e($page); ?></span>
                    <?php else: ?>
                        <a href="<?php echo e($url); ?>" class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition"><?php echo e($page); ?></a>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php if($servicePages->currentPage() < $servicePages->lastPage()): ?>
                    <a href="<?php echo e($servicePages->nextPageUrl()); ?>" class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">Next</a>
                <?php endif; ?>
            </nav>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/hasanulrubel/Playground/PPC/laravel porta potty/porta-potty-app/resources/views/admin/service-pages/index.blade.php ENDPATH**/ ?>