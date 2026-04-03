<?php $__env->startSection('title', $city->name); ?>
<?php $__env->startSection('page-title', $city->name); ?>

<?php $__env->startSection('content'); ?>
<div class="grid lg:grid-cols-4 gap-6">
    <div class="lg:col-span-3 space-y-6">
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gradient-to-r from-indigo-50 to-white">
                <div>
                    <h2 class="font-bold text-gray-800 text-lg">Import JSON Content</h2>
                    <p class="text-sm text-gray-500">Paste JSON from external API to generate pages</p>
                </div>
                <button type="button" onclick="toggleJsonPanel()" class="text-indigo-600 hover:text-indigo-700 font-medium text-sm flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    <?php echo e(isset($showJsonPanel) && $showJsonPanel ? 'Hide' : 'Show'); ?> Import
                </button>
            </div>
            <div id="json-panel" class="<?php echo e(isset($showJsonPanel) && $showJsonPanel ? '' : 'hidden'); ?> p-6">
                <form method="POST" action="<?php echo e(route('admin.cities.import-json', $city)); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-4 flex gap-2">
                        <button type="button" onclick="loadSampleJson()" class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200 transition flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Load Sample Format
                        </button>
                        <button type="button" onclick="copySampleJson(event)" class="px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-lg text-sm hover:bg-emerald-100 transition flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            Copy JSON
                        </button>
                        <a href="<?php echo e(route('admin.cities.sample-json', $city)); ?>" target="_blank" class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-sm hover:bg-blue-100 transition flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                            View
                        </a>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Paste JSON Content</label>
                        <textarea name="json_content" id="json-content" rows="12" 
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm font-mono bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder='{"service_pages": [{"service_type": "general", "slug": "...", "h1_title": "...", ...}]}'></textarea>
                        <p class="text-xs text-gray-500 mt-2">Supports: service_pages (with h1_title, meta_title, meta_description, content), faqs (question, answer, service_type), testimonials (customer_name, content, rating, service_type)</p>
                    </div>
                    <div class="flex gap-3">
                        <button type="submit" class="px-4 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Import Content
                        </button>
                        <button type="button" onclick="clearJsonContent()" class="px-4 py-2.5 border border-gray-200 text-gray-600 rounded-lg text-sm hover:bg-gray-50 transition">
                            Clear
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-bold text-gray-800">Service Pages</h2>
                <div class="flex gap-2">
                    <?php if($city->servicePages->count() > 0): ?>
                        <button type="submit" form="bulk-delete-form" onclick="return confirm('Delete selected pages?')" class="bg-red-500 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-red-600 disabled:opacity-50 disabled:cursor-not-allowed" id="delete-selected-btn" disabled>
                            Delete Selected
                        </button>
                    <?php endif; ?>
                    <form method="POST" action="<?php echo e(route('admin.cities.generate-pages', $city)); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-purple-700">Generate Content</button>
                    </form>
                </div>
            </div>
            <form id="bulk-delete-form" method="POST" action="<?php echo e(route('admin.service-pages.bulk-destroy')); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="_method" value="DELETE">
                <table class="w-full text-sm">
                    <thead><tr class="text-left text-xs text-gray-500 uppercase"><th class="pb-2 w-8"><input type="checkbox" id="select-all"></th><th class="pb-2">Type</th><th class="pb-2">Slug</th><th class="pb-2">Views</th><th class="pb-2">Calls</th></tr></thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php $__empty_1 = true; $__currentLoopData = $city->servicePages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="py-2"><input type="checkbox" name="page_ids[]" value="<?php echo e($page->id); ?>" class="page-checkbox rounded"></td>
                                <td class="py-2">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700"><?php echo e($page->service_type); ?></span>
                                </td>
                                <td class="py-2 text-xs font-mono"><?php echo e(Str::limit($page->slug, 30)); ?></td>
                                <td class="py-2"><?php echo e(number_format($page->views)); ?></td>
                                <td class="py-2"><?php echo e(number_format($page->calls_generated)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="5" class="py-4 text-center text-gray-400">No pages generated yet</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <?php if($city->servicePages->count() > 0): ?>
                    <div class="mt-4 text-sm text-gray-500">
                        <?php echo e($city->servicePages->count()); ?> pages
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-800 mb-4">City Details</h2>
            <dl class="grid grid-cols-2 gap-3 text-sm">
                <div><dt class="text-gray-500">City</dt><dd class="font-medium"><?php echo e($city->name); ?></dd></div>
                <div><dt class="text-gray-500">State</dt><dd class="font-medium"><?php echo e($city->state?->name); ?></dd></div>
                <div><dt class="text-gray-500">Population</dt><dd><?php echo e($city->population ? number_format($city->population) : '—'); ?></dd></div>
                <div><dt class="text-gray-500">Area Codes</dt><dd><?php echo e($city->area_codes ?? '—'); ?></dd></div>
                <div><dt class="text-gray-500">Status</dt>
                    <dd><?php if($city->is_active): ?><span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</span><?php else: ?><span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Inactive</span><?php endif; ?></dd>
                </div>
            </dl>
            <div class="mt-4 flex gap-2">
                <a href="<?php echo e(route('admin.cities.edit', $city)); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Edit</a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-bold text-gray-800">Phone Numbers</h2>
            </div>
            <?php $__empty_1 = true; $__currentLoopData = $city->phoneNumbers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $number): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="p-3 bg-gray-50 rounded-lg mb-2">
                    <div class="font-mono text-sm"><?php echo e($number->number); ?></div>
                    <div class="text-xs text-gray-500"><?php echo e($number->total_calls); ?> calls</div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-gray-400 text-sm">No phone numbers assigned</p>
            <?php endif; ?>
            <a href="<?php echo e(route('admin.phone-numbers.index', ['city_id' => $city->id])); ?>" class="text-blue-600 text-sm hover:text-blue-700">Manage Numbers →</a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-800 mb-3">Quick Stats</h2>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Service Pages</span>
                    <span class="font-semibold text-gray-800"><?php echo e($city->servicePages->count()); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">FAQs</span>
                    <span class="font-semibold text-gray-800"><?php echo e($city->faqs->count()); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Testimonials</span>
                    <span class="font-semibold text-gray-800"><?php echo e($city->testimonials->count()); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Total Calls</span>
                    <span class="font-semibold text-gray-800"><?php echo e($city->callLogs->count()); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleJsonPanel() {
        const panel = document.getElementById('json-panel');
        panel.classList.toggle('hidden');
    }

    function clearJsonContent() {
        document.getElementById('json-content').value = '';
    }

    async function loadSampleJson() {
        try {
            const response = await fetch('<?php echo e(route('admin.cities.sample-json', $city)); ?>');
            const data = await response.json();
            document.getElementById('json-content').value = JSON.stringify(data, null, 2);
        } catch (error) {
            alert('Failed to load sample JSON');
        }
    }

    async function copySampleJson(event) {
        try {
            const response = await fetch('<?php echo e(route('admin.cities.sample-json', $city)); ?>');
            const data = await response.json();
            const jsonString = JSON.stringify(data, null, 2);
            
            // Try modern clipboard API first
            if (navigator.clipboard && navigator.clipboard.writeText) {
                await navigator.clipboard.writeText(jsonString);
                
                const btn = event.target.closest('button');
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Copied!';
                btn.classList.remove('bg-emerald-50', 'text-emerald-700', 'hover:bg-emerald-100');
                btn.classList.add('bg-green-600', 'text-white');
                
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.classList.add('bg-emerald-50', 'text-emerald-700', 'hover:bg-emerald-100');
                    btn.classList.remove('bg-green-600', 'text-white');
                }, 2000);
            } else {
                throw new Error('Clipboard API not available');
            }
        } catch (error) {
            console.error('Copy error:', error);
            
            // Fallback: Download as file
            try {
                const response = await fetch('<?php echo e(route('admin.cities.sample-json', $city)); ?>');
                const data = await response.json();
                const jsonString = JSON.stringify(data, null, 2);
                
                const blob = new Blob([jsonString], { type: 'application/json' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = '<?php echo e($city->slug); ?>-seo-data.json';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
                
                alert('JSON downloaded as file. You can now edit it and paste it back here to import.');
            } catch (downloadError) {
                alert('Failed to copy or download JSON. Please click "View" to open the JSON in a new tab, then copy manually (Ctrl+C / Cmd+C).');
            }
        }
    }

    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.page-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateDeleteButton();
    });

    document.querySelectorAll('.page-checkbox').forEach(cb => {
        cb.addEventListener('change', updateDeleteButton);
    });

    function updateDeleteButton() {
        const checked = document.querySelectorAll('.page-checkbox:checked');
        const btn = document.getElementById('delete-selected-btn');
        btn.disabled = checked.length === 0;
        btn.textContent = checked.length > 0 ? 'Delete Selected (' + checked.length + ')' : 'Delete Selected';
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/hasanulrubel/Playground/PPC/laravel porta potty/porta-potty-app/resources/views/admin/cities/show.blade.php ENDPATH**/ ?>