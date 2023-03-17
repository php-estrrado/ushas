		<!-- Title -->
		<title><?php echo e(config('app.name', 'Big Basket')); ?></title>

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
		
		<!---Icons css-->
		<link href="<?php echo e(URL::asset('admin/assets/css/icons.css')); ?>" rel="stylesheet" />
		
		<?php echo $__env->yieldContent('css'); ?>
			<?php echo $__env->make('includes.config-styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
		<!-- Color Skin css -->
		<link id="theme" href="<?php echo e(URL::asset('admin/assets/colors/color1.css')); ?>" rel="stylesheet" type="text/css"/>
                
                <!-- Custom css -->
		<link id="theme" href="<?php echo e(URL::asset('admin/assets/css/custom.css')); ?>" rel="stylesheet" type="text/css"/>
		<?php /**PATH /home/qaushas/public_html/resources/views/layouts/custom-head.blade.php ENDPATH**/ ?>