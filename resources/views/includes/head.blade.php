    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Kangtao') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="">
    <meta name="msapplication-tap-highlight" content="no">
    
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

		<!--Sidemenu css -->
       <link href="{{URL::asset('admin/assets/css/sidemenu.css')}}" rel="stylesheet">

		<!-- P-scroll bar css-->
		<link href="{{URL::asset('admin/assets/plugins/p-scrollbar/p-scrollbar.css')}}" rel="stylesheet" />

		<!---Icons css-->
		<link href="{{URL::asset('admin/assets/css/icons.css')}}" rel="stylesheet" />
		@yield('css')
	@include('includes.config-styles')
		<!-- Simplebar css -->
		<link rel="stylesheet" href="{{URL::asset('admin/assets/plugins/simplebar/css/simplebar.css')}}">

	    <!-- Color Skin css -->
		<link id="theme" href="{{URL::asset('admin/assets/colors/color1.css')}}" rel="stylesheet" type="text/css"/>


                <link rel="stylesheet" href="{{URL::asset('admin/assets/css/toastr.min.css')}}" />
                    
                
                <!--  Datatable -->
                <link href="{{URL::asset('admin/assets/js/datatable/datatables.min.css')}}" rel="stylesheet" />
            <!-- Custom css -->
		<link id="theme" href="{{URL::asset('admin/assets/css/custom.css')}}" rel="stylesheet" type="text/css"/>
		
		<!--Switch css-->
		<link href="{{URL::asset('admin/assets/css/switch.css')}}" rel="stylesheet" type="text/css"/>
		<link href="{{URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />

	