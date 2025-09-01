<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo e(config('app.name', 'App')); ?></title>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    </head>
    <body class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <div class="min-h-screen">
            <?php if(isset($header)): ?>
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="mx-auto max-w-7xl py-6 px-4 sm:px-6 lg:px-8">
                        <?php echo e($header); ?>

                    </div>
                </header>
            <?php endif; ?>

            <main class="py-6">
                <?php echo e($slot); ?>

            </main>
        </div>
    </body>
    </html>

<?php /**PATH /Users/admin/Desktop/project/dash_3/resources/views/components/app-layout.blade.php ENDPATH**/ ?>