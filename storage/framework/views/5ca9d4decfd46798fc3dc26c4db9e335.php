<div
    <?php echo e($attributes
            ->merge([
                'id' => $getId(),
            ], escape: false)
            ->merge($getExtraAttributes(), escape: false)); ?>

>
    <?php echo e($getChildComponentContainer()); ?>

</div>
<?php /**PATH /Users/admin/Desktop/project/dash_3/vendor/filament/infolists/resources/views/components/group.blade.php ENDPATH**/ ?>