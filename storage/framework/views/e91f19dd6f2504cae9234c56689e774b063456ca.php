<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head> <?php echo $__env->make('includes.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></head>
    <body class="app sidebar-mini">
        <!---Global-loader-->
        <div id="bgred"></div>
        <div id="global-loader"><img src="<?php echo e(URL::asset('admin/assets/images/svgs/loader.svg')); ?>" alt="loader"></div>
        <!--- End Global-loader-->
        <!-- Page -->
        <div class="page">
            <div class="page-main">
                <?php echo $__env->make('includes.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <!-- App-Content -->			
                <div class="app-content main-content">
                    <div class="side-app">
                        <?php echo $__env->make('includes.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo $__env->yieldContent('page-header'); ?>
                        <?php echo $__env->yieldContent('content'); ?>
                    </div>
                </div>
                <?php echo $__env->make('includes.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div><!-- End Page -->
        <?php echo $__env->make('includes.foot', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('includes.modals', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </body>
</html>
<?php /**PATH /home/qaushas/public_html/resources/views/layouts/admin.blade.php ENDPATH**/ ?>