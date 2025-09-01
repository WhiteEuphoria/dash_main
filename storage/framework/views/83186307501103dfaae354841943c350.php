<div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Your Balance</h3>
    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
        <?php echo e(auth()->user()->currency ?? 'EUR'); ?> <?php echo e(number_format($balance, 2)); ?>

    </p>
</div>
<?php /**PATH /Users/admin/Desktop/project/dash_3/resources/views/livewire/client/balance-display.blade.php ENDPATH**/ ?>