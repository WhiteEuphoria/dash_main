<div class="p-6 mt-4 bg-white dark:bg-gray-800 rounded-lg shadow">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Upload Document</h3>
    <form wire:submit.prevent="submit" class="mt-4 space-y-4">
        <?php if($successMessage): ?>
            <div class="p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                <?php echo e($successMessage); ?>

            </div>
        <?php endif; ?>

        <div>
            <label for="document_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Document Type</label>
            <select id="document_type" wire:model="document_type" class="block w-full mt-1 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                <option value="passport">Passport</option>
                <option value="utility_bill">Utility Bill</option>
                <option value="other">Other</option>
            </select>
            <?php $__errorArgs = ['document_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-sm text-red-600"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

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
        </div>

        <button type="submit" class="px-4 py-2 font-bold text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
            Upload
        </button>
    </form>
</div>
<?php /**PATH /Users/admin/Desktop/project/dash_3/resources/views/livewire/client/document-upload-form.blade.php ENDPATH**/ ?>