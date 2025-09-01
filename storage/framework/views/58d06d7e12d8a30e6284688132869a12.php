<?php
    // Support passing a Closure or nothing. If nothing provided, fetch by route record id.
    if (isset($path) && $path instanceof \Closure) {
        $path = $path();
    }
    $path = (string) ($path ?? '');
    if ($path === '') {
        $rid = request()->route('record');
        if ($rid) {
            $doc = \App\Models\Document::find($rid);
            $path = (string) ($doc->path ?? '');
        }
    }
    $rel = '/storage/' . ltrim($path, '/');
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    $imgExt = ['jpg','jpeg','png','gif','webp','bmp'];
?>

<?php if($path === ''): ?>
    <div class="text-gray-500">No file uploaded</div>
<?php elseif(in_array($ext, $imgExt, true)): ?>
    <img src="<?php echo e($rel); ?>" alt="Preview" class="rounded-md border border-gray-200 h-auto" style="max-width:50%;">
<?php elseif($ext === 'pdf'): ?>
    <div class="w-64 h-40 border border-gray-200 rounded-md overflow-hidden bg-gray-50">
        <embed src="<?php echo e($rel); ?>#toolbar=0&navpanes=0" type="application/pdf" class="w-full h-full" />
    </div>
<?php else: ?>
    <a href="<?php echo e($rel); ?>" target="_blank" class="text-primary-600">Open document</a>
<?php endif; ?>
<?php /**PATH /Users/admin/Desktop/project/dash_3/resources/views/filament/admin/document-preview.blade.php ENDPATH**/ ?>