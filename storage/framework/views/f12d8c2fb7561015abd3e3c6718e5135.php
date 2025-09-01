<?php if (isset($component)) { $__componentOriginalb525200bfa976483b4eaa0b7685c6e24 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb525200bfa976483b4eaa0b7685c6e24 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-widgets::components.widget','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-widgets::widget'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php if (isset($component)) { $__componentOriginalee08b1367eba38734199cf7829b1d1e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalee08b1367eba38734199cf7829b1d1e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.section.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
         <?php $__env->slot('heading', null, []); ?> 
            Brokerage Account
         <?php $__env->endSlot(); ?>

        <?php if($account): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-5 bg-white dark:bg-gray-900 rounded-2xl ring-1 ring-gray-200/80 dark:ring-gray-800">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Account Number</div>
                    <div class="text-xl font-semibold text-gray-900 dark:text-white"><?php echo e($account->number); ?></div>
                </div>
                <div class="p-5 bg-white dark:bg-gray-900 rounded-2xl ring-1 ring-gray-200/80 dark:ring-gray-800">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Organization</div>
                    <div class="text-xl font-semibold text-gray-900 dark:text-white"><?php echo e($account->organization); ?></div>
                </div>
                <div class="p-5 bg-white dark:bg-gray-900 rounded-2xl ring-1 ring-gray-200/80 dark:ring-gray-800">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Beneficiary</div>
                    <div class="text-xl font-semibold text-gray-900 dark:text-white"><?php echo e($account->beneficiary ?: '—'); ?></div>
                </div>
                <div class="p-5 bg-white dark:bg-gray-900 rounded-2xl ring-1 ring-gray-200/80 dark:ring-gray-800">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Investment Control</div>
                    <div class="text-xl font-semibold text-gray-900 dark:text-white"><?php echo e($account->investment_control ?: '—'); ?></div>
                </div>
                <div class="p-5 bg-white dark:bg-gray-900 rounded-2xl ring-1 ring-gray-200/80 dark:ring-gray-800">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Balance</div>
                    <div class="text-xl font-semibold text-gray-900 dark:text-white"><?php echo e($account->currency ?? 'EUR'); ?> <?php echo e(number_format($account->balance, 2)); ?></div>
                </div>
                <div class="p-5 bg-white dark:bg-gray-900 rounded-2xl ring-1 ring-gray-200/80 dark:ring-gray-800">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Type</div>
                    <div class="text-xl font-semibold text-gray-900 dark:text-white"><?php echo e(config('accounts.types')[$account->type] ?? $account->type); ?></div>
                </div>
                <div class="p-5 bg-white dark:bg-gray-900 rounded-2xl ring-1 ring-gray-200/80 dark:ring-gray-800">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Expiration</div>
                    <div class="text-xl font-semibold text-gray-900 dark:text-white"><?php echo e(optional($account->term)->format('d.m.Y')); ?></div>
                </div>
                <div class="p-5 bg-white dark:bg-gray-900 rounded-2xl ring-1 ring-gray-200/80 dark:ring-gray-800">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Status</div>
                    <div class="text-xl font-semibold text-gray-900 dark:text-white"><?php echo e($account->status); ?></div>
                </div>
            </div>
        <?php else: ?>
            <p class="text-gray-600 dark:text-gray-400">You have no brokerage accounts yet.</p>
        <?php endif; ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalee08b1367eba38734199cf7829b1d1e9)): ?>
<?php $attributes = $__attributesOriginalee08b1367eba38734199cf7829b1d1e9; ?>
<?php unset($__attributesOriginalee08b1367eba38734199cf7829b1d1e9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalee08b1367eba38734199cf7829b1d1e9)): ?>
<?php $component = $__componentOriginalee08b1367eba38734199cf7829b1d1e9; ?>
<?php unset($__componentOriginalee08b1367eba38734199cf7829b1d1e9); ?>
<?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb525200bfa976483b4eaa0b7685c6e24)): ?>
<?php $attributes = $__attributesOriginalb525200bfa976483b4eaa0b7685c6e24; ?>
<?php unset($__attributesOriginalb525200bfa976483b4eaa0b7685c6e24); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb525200bfa976483b4eaa0b7685c6e24)): ?>
<?php $component = $__componentOriginalb525200bfa976483b4eaa0b7685c6e24; ?>
<?php unset($__componentOriginalb525200bfa976483b4eaa0b7685c6e24); ?>
<?php endif; ?>
<?php /**PATH /Users/admin/Desktop/project/dash_3/resources/views/filament/client/widgets/brokerage-account-widget.blade.php ENDPATH**/ ?>