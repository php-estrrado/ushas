		<!-- Title -->
		<title>{{ config('app.name', 'Big Basket') }}</title>

		<!--Favicon -->
		<link rel="icon" href="{{URL::asset('admin/assets/images/brand/favicon.ico')}}" type="image/x-icon"/>

		<!--Bootstrap css -->
		<link href="{{URL::asset('admin/assets/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

		<!-- Style css -->
		<link href="{{URL::asset('admin/assets/css/style.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/css/dark.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/css/skin-modes.css')}}" rel="stylesheet" />

		<!-- Animate css -->
		<link href="{{URL::asset('admin/assets/css/animated.css')}}" rel="stylesheet" />
		
		<!---Icons css-->
		<link href="{{URL::asset('admin/assets/css/icons.css')}}" rel="stylesheet" />
		
		@yield('css')
			@include('includes.config-styles')
		<!-- Color Skin css -->
		<link id="theme" href="{{URL::asset('admin/assets/colors/color1.css')}}" rel="stylesheet" type="text/css"/>
                
                <!-- Custom css -->
		<link id="theme" href="{{URL::asset('admin/assets/css/custom.css')}}" rel="stylesheet" type="text/css"/>
		