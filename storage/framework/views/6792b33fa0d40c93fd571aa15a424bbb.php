<div
    <?php echo e($attributes
            ->merge([
                'id' => $getId(),
            ], escape: false)
            ->merge($getExtraAttributes(), escape: false)); ?>

>
    <?php echo e($getChildComponentContainer()); ?>

</div>
<?php /**PATH /Users/admin/Desktop/project/dash_3/vendor/filament/forms/resources/views/components/grid.blade.php ENDPATH**/ ?>