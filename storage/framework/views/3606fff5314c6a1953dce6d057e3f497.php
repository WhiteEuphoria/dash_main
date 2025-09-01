<div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow max-w-xl">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Select Currency</h3>
    <div class="mt-4">
        <label for="currency" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Currency</label>
        <select id="currency" wire:model="currency" class="mt-1 block w-full rounded-md dark:bg-gray-900 dark:text-gray-300">
            <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($key); ?>"><?php echo e($label); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <?php $__errorArgs = ['currency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-sm text-red-600 mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="mt-4">
        <button wire:click="save" class="px-4 py-2 font-bold text-white rounded-md btn-amber">Save</button>
    </div>
</div>
<?php /**PATH /Users/admin/Desktop/project/dash_3/resources/views/livewire/client/currency-settings.blade.php ENDPATH**/ ?>