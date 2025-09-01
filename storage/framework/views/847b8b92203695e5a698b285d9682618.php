<div>
    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Upload Document</h3>
        <form wire:submit.prevent="save" class="mt-4 space-y-4">
            <?php if(session()->has('message')): ?>
                <div class="p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                    <?php echo e(session('message')); ?>

                </div>
            <?php endif; ?>

            <div>
                <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300">File</label>
                <input type="file" id="file" wire:model="file" class="block w-full mt-1 text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                <div wire:loading wire:target="file" class="mt-1 text-sm text-gray-500">Uploading...</div>
                <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-sm text-red-600"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <?php if($file): ?>
                    <div class="mt-3">
                        <?php
                            $tmpName = $file->getClientOriginalName();
                            $ext = strtolower(pathinfo($tmpName, PATHINFO_EXTENSION));
                            $isImg = in_array($ext, ['jpg','jpeg','png','gif','webp','bmp']);
                        ?>
                        <?php if($isImg): ?>
                            <img src="<?php echo e($file->temporaryUrl()); ?>" alt="Preview" class="object-cover w-40 h-28 rounded-md border border-gray-200">
                        <?php elseif($ext === 'pdf'): ?>
                            <div class="w-40 h-28 border border-gray-200 rounded-md overflow-hidden bg-gray-50">
                                <embed src="<?php echo e($file->temporaryUrl()); ?>#toolbar=0&navpanes=0" type="application/pdf" class="w-full h-full" />
                            </div>
                        <?php else: ?>
                            <div class="text-sm text-gray-500">Selected: <?php echo e($tmpName); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <button type="submit" class="px-4 py-2 font-bold text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                Upload
            </button>
        </form>
    </div>

    <div class="p-6 mt-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Your Documents</h3>
        <div class="mt-4 space-y-4">
            <?php $__empty_1 = true; $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $isImage = in_array(strtolower(pathinfo($document->path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                    // Use a relative URL to avoid APP_URL mismatches
                    $url = '/storage/' . ltrim($document->path, '/');
                ?>
                <div class="flex items-center justify-between p-3 border-b dark:border-gray-700">
                    <div>
                        <p class="font-medium dark:text-white"><?php echo e($document->original_name); ?></p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Status: <span class="font-semibold"><?php echo e($document->status); ?></span></p>
                    </div>
                    <div>
                        <?php if($isImage): ?>
                            <img src="<?php echo e($url); ?>" alt="Preview" class="object-cover w-40 h-28 rounded-md border border-gray-200">
                        <?php elseif(strtolower(pathinfo($document->path, PATHINFO_EXTENSION)) === 'pdf'): ?>
                            <div class="w-40 h-28 border border-gray-200 rounded-md overflow-hidden bg-gray-50">
                                <embed src="<?php echo e($url); ?>#toolbar=0&navpanes=0" type="application/pdf" class="w-full h-full" />
                            </div>
                        <?php else: ?>
                            <a href="<?php echo e($url); ?>" target="_blank" class="px-3 py-1 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">Download</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-gray-500 dark:text-gray-400">You have not uploaded any documents yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH /Users/admin/Desktop/project/dash_3/resources/views/livewire/client/upload-document.blade.php ENDPATH**/ ?>