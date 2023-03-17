
<?php $__env->startSection('title', 'Product List'); ?>
<?php $__env->startSection('css'); ?>
<link href="<?php echo e(URL::asset('admin/assets/plugins/wysiwyag/richtext.css')); ?>" rel="stylesheet" />
<!---combo tree-->
<link href="<?php echo e(URL::asset('admin/assets/css/combo-tree.css')); ?>" rel="stylesheet" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div id="pg_content">
    <?php echo $__env->make('admin.products.list', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>
<div id="loader" class="d-none"><div class="spinner1"><div class="double-bounce1"></div><div class="double-bounce2"></div></div></div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/qaushas/public_html/resources/views/admin/products/page.blade.php ENDPATH**/ ?>