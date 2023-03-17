
<?php $__env->startSection('title', 'Best Purchase Report'); ?>
<?php $__env->startSection('content'); ?>
<div id="pg_content">
    <?php echo $__env->make('admin.bestpurchase_report.filter', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>
<div id="loader" class="d-none"><div class="spinner1 content-spin"><div class="double-bounce1"></div><div class="double-bounce2"></div></div></div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/qaushas/public_html/resources/views/admin/bestpurchase_report/page.blade.php ENDPATH**/ ?>