<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head> @include('includes.head')</head>
    <body class="app sidebar-mini">
        <!---Global-loader-->
        <div id="bgred"></div>
        <div id="global-loader"><img src="{{URL::asset('admin/assets/images/svgs/loader.svg')}}" alt="loader"></div>
        <!--- End Global-loader-->
        <!-- Page -->
        <div class="page">
            <div class="page-main">
                @include('includes.sidebar')
                <!-- App-Content -->			
                <div class="app-content main-content">
                    <div class="side-app">
                        @include('includes.header')
                        @yield('page-header')
                        @yield('content')
                    </div>
                </div>
                @include('includes.footer')
            </div>
        </div><!-- End Page -->
        @include('includes.foot')
        @include('includes.modals')
    </body>
</html>
