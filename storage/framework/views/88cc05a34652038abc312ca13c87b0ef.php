<?php $__env->startSection('page_title', "Edit: {$page->city->name} — {$page->service_type_label}"); ?>

<?php $__env->startSection('content'); ?>

    <form method="POST" action="<?php echo e(route('admin.service-pages.update', $page)); ?>"
          class="max-w-5xl">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="grid lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-2 space-y-6">
                <div class="card p-6">
                    <div class="mb-4 bg-blue-50 rounded-lg p-3 text-sm">
                        <span class="font-bold">City:</span> <?php echo e($page->city->name); ?>, <?php echo e($page->city->state->code); ?>

                        <span class="mx-2">|</span>
                        <span class="font-bold">Type:</span> <?php echo e($page->service_type_label); ?>

                        <span class="mx-2">|</span>
                        <span class="font-bold">URL:</span>
                        <a href="<?php echo e(url($page->slug)); ?>" target="_blank" class="text-blue-600">
                            /<?php echo e($page->slug); ?> ↗
                        </a>
                    </div>

                    <div class="mb-6">
                        <label class="form-label">H1 Title *</label>
                        <input type="text" name="h1_title" class="form-input"
                               value="<?php echo e(old('h1_title', $page->h1_title)); ?>" required>
                        <?php $__errorArgs = ['h1_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-6">
                        <label class="form-label">Content * (Markdown)</label>
                        <textarea name="content" id="content-editor" rows="30"
                                  class="form-input font-mono text-sm leading-relaxed"
                                  required><?php echo e(old('content', $page->content)); ?></textarea>
                        <div class="flex items-center justify-between mt-1">
                            <p class="text-xs text-gray-400">
                                Target: 1,500+ words
                            </p>
                            <p class="text-xs font-medium" id="word-count-display">
                                <span id="word-count"><?php echo e(number_format($page->word_count)); ?></span> words
                            </p>
                        </div>
                        <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <?php $__env->startPush('scripts'); ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const textarea = document.getElementById('content-editor');
                            const wordCountEl = document.getElementById('word-count');
                            const wordCountDisplay = document.getElementById('word-count-display');

                            function countWords(str) {
                                if (!str || str.trim() === '') return 0;
                                return str.trim().split(/\s+/).length;
                            }

                            function updateWordCount() {
                                const count = countWords(textarea.value);
                                wordCountEl.textContent = count.toLocaleString();
                                wordCountDisplay.className = count >= 1500 
                                    ? 'text-xs font-medium text-green-600' 
                                    : (count >= 1000 
                                        ? 'text-xs font-medium text-yellow-600' 
                                        : 'text-xs font-medium text-red-500');
                            }

                            textarea.addEventListener('input', updateWordCount);
                            updateWordCount();
                        });
                    </script>
                    <?php $__env->stopPush(); ?>
                </div>

                
                <div class="card p-6">
                    <h3 class="font-bold text-gray-700 mb-4">🔍 SEO Settings</h3>

                    <div class="mb-4">
                        <label class="form-label">Meta Title * (max 60 chars)</label>
                        <input type="text" name="meta_title" class="form-input" maxlength="60"
                               value="<?php echo e(old('meta_title', $page->meta_title)); ?>" required>
                        <p class="text-xs text-gray-400 mt-1">
                            <?php echo e(strlen($page->meta_title)); ?>/60 characters
                        </p>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Meta Description * (max 160 chars)</label>
                        <textarea name="meta_description" rows="2" class="form-input" maxlength="160"
                                  required><?php echo e(old('meta_description', $page->meta_description)); ?></textarea>
                        <p class="text-xs text-gray-400 mt-1">
                            <?php echo e(strlen($page->meta_description)); ?>/160 characters
                        </p>
                    </div>
                </div>
            </div>

            
            <div class="space-y-6">
                
                <div class="card p-6">
                    <h3 class="font-bold text-gray-700 mb-4">📋 Status</h3>

                    <label class="flex items-center gap-2 mb-4">
                        <input type="hidden" name="is_published" value="0">
                        <input type="checkbox" name="is_published" value="1"
                               <?php echo e(old('is_published', $page->is_published) ? 'checked' : ''); ?>

                               class="rounded border-gray-300">
                        <span class="text-sm">Published</span>
                    </label>

                    <button type="submit" class="btn-primary w-full">
                        💾 Save Changes
                    </button>

                    <div class="mt-3 text-center">
                        <a href="<?php echo e(route('admin.service-pages.index')); ?>"
                           class="text-sm text-gray-500">Cancel</a>
                    </div>
                </div>

                
                <div class="card p-6">
                    <h3 class="font-bold text-gray-700 mb-4">📊 SEO Score</h3>
                    <div class="text-center">
                        <div class="text-4xl font-bold <?php echo e($page->seo_score >= 70 ? 'text-green-600' : ($page->seo_score >= 40 ? 'text-yellow-600' : 'text-red-600')); ?>">
                            <?php echo e($page->seo_score); ?>%
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3 mt-3">
                            <div class="h-3 rounded-full transition-all <?php echo e($page->seo_score >= 70 ? 'bg-green-500' : ($page->seo_score >= 40 ? 'bg-yellow-500' : 'bg-red-500')); ?>"
                                 style="width: <?php echo e($page->seo_score); ?>%"></div>
                        </div>
                    </div>

                    <div class="mt-4 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Word Count</span>
                            <span class="font-medium <?php echo e($page->word_count >= 1500 ? 'text-green-600' : 'text-orange-500'); ?>">
                                <?php echo e(number_format($page->word_count)); ?>

                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Views</span>
                            <span class="font-medium"><?php echo e(number_format($page->views)); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Calls Generated</span>
                            <span class="font-medium"><?php echo e($page->calls_generated); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Published</span>
                            <span class="font-medium"><?php echo e($page->published_at?->format('M d, Y') ?? 'Not yet'); ?></span>
                        </div>
                    </div>
                </div>

                
                <div class="card p-6">
                    <h3 class="font-bold text-gray-700 mb-4">📞 Phone</h3>
                    <p class="text-lg font-mono font-bold text-blue-600">
                        <?php echo e($page->phone_display); ?>

                    </p>
                    <p class="text-xs text-gray-400 mt-1">
                        Assigned from city's phone number
                    </p>
                </div>
            </div>
        </div>
    </form>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/hasanulrubel/Playground/PPC/laravel porta potty/porta-potty-app/resources/views/admin/service-pages/edit.blade.php ENDPATH**/ ?>