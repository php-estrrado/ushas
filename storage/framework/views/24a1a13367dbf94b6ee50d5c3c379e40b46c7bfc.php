    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Ushas')); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="">
    <meta name="msapplication-tap-highlight" content="no">
    
    <!--Favicon -->
		<link rel="icon" href="<?php echo e(URL::asset('admin/assets/images/brand/favicon.ico')); ?>" type="image/x-icon"/>

		<!--Bootstrap css -->
		<link href="<?php echo e(URL::asset('admin/assets/plugins/bootstrap/css/bootstrap.min.css')); ?>" rel="stylesheet">

		<!-- Style css -->
		<link href="<?php echo e(URL::asset('admin/assets/css/style.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/css/dark.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/css/skin-modes.css')); ?>" rel="stylesheet" />

		<!-- Animate css -->
		<link href="<?php echo e(URL::asset('admin/assets/css/animated.css')); ?>" rel="stylesheet" />

		<!--Sidemenu css -->
       <link href="<?php echo e(URL::asset('admin/assets/css/sidemenu.css')); ?>" rel="stylesheet">

		<!-- P-scroll bar css-->
		<link href="<?php echo e(URL::asset('admin/assets/plugins/p-scrollbar/p-scrollbar.css')); ?>" rel="stylesheet" />

		<!---Icons css-->
		<link href="<?php echo e(URL::asset('admin/assets/css/icons.css')); ?>" rel="stylesheet" />
		<?php echo $__env->yieldContent('css'); ?>
	<?php echo $__env->make('includes.config-styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
		<!-- Simplebar css -->
		<link rel="stylesheet" href="<?php echo e(URL::asset('admin/assets/plugins/simplebar/css/simplebar.css')); ?>">

	    <!-- Color Skin css -->
		<link id="theme" href="<?php echo e(URL::asset('admin/assets/colors/color1.css')); ?>" rel="stylesheet" type="text/css"/>


                <link rel="stylesheet" href="<?php echo e(URL::asset('admin/assets/css/toastr.min.css')); ?>" />
                    
                
                <!--  Datatable -->
                <link href="<?php echo e(URL::asset('admin/assets/js/datatable/datatables.min.css')); ?>" rel="stylesheet" />
            <!-- Custom css -->
		<link id="theme" href="<?php echo e(URL::asset('admin/assets/css/custom.css')); ?>" rel="stylesheet" type="text/css"/>
		
		<!--Switch css-->
		<link href="<?php echo e(URL::asset('admin/assets/css/switch.css')); ?>" rel="stylesheet" type="text/css"/>
		<link href="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/sweetalert.css')); ?>" rel="stylesheet" />

	<?php /**PATH C:\wamp64\www\ushas-dev\resources\views/includes/head.blade.php ENDPATH**/ ?>