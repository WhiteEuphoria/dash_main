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
         <?php $__env->slot('heading', null, []); ?> All Accounts <?php $__env->endSlot(); ?>

        <?php if($accounts && $accounts->isNotEmpty()): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-2">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Account Number</div>
                            <?php if($acc->is_default): ?>
                                <span class="px-2 py-0.5 text-xs rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200">Primary</span>
                            <?php endif; ?>
                        </div>
                        <div class="text-lg font-semibold text-gray-900 dark:text-white"><?php echo e($acc->number); ?></div>

                        <div class="mt-3 grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <div class="text-gray-500 dark:text-gray-400">Organization</div>
                                <div class="text-gray-900 dark:text-gray-100"><?php echo e($acc->organization ?: '—'); ?></div>
                            </div>
                            <div>
                                <div class="text-gray-500 dark:text-gray-400">Beneficiary</div>
                                <div class="text-gray-900 dark:text-gray-100"><?php echo e($acc->beneficiary ?: '—'); ?></div>
                            </div>
                            <div>
                                <div class="text-gray-500 dark:text-gray-400">Investment Control</div>
                                <div class="text-gray-900 dark:text-gray-100"><?php echo e($acc->investment_control ?: '—'); ?></div>
                            </div>
                            <div>
                                <div class="text-gray-500 dark:text-gray-400">Type</div>
                                <div class="text-gray-900 dark:text-gray-100"><?php echo e(config('accounts.types')[$acc->type] ?? $acc->type); ?></div>
                            </div>
                            <div>
                                <div class="text-gray-500 dark:text-gray-400">Balance</div>
                                <div class="text-gray-900 dark:text-gray-100"><?php echo e($acc->currency ?? 'EUR'); ?> <?php echo e(number_format($acc->balance, 2)); ?></div>
                            </div>
                            <div>
                                <div class="text-gray-500 dark:text-gray-400">Expiration</div>
                                <div class="text-gray-900 dark:text-gray-100"><?php echo e(optional($acc->term)->format('d.m.Y') ?: '—'); ?></div>
                            </div>
                            <div>
                                <div class="text-gray-500 dark:text-gray-400">Status</div>
                                <div class="text-gray-900 dark:text-gray-100"><?php echo e($acc->status); ?></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <p class="text-gray-600 dark:text-gray-400">No accounts yet.</p>
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

<?php /**PATH /Users/admin/Desktop/project/dash_3/resources/views/filament/client/widgets/all-accounts-widget.blade.php ENDPATH**/ ?>