		<!-- Back to top -->
		<a href="#top" id="back-to-top"><i class="fe fe-chevrons-up"></i></a>

		<!-- Jquery js-->
        @if(@$menu == "create-shocking-sales")
		<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
		@else
		<script src="{{URL::asset('admin/assets/js/jquery-3.5.1.min.js')}}"></script>
		@endif
	

		<!-- Bootstrap4 js-->
		<script src="{{URL::asset('admin/assets/plugins/bootstrap/popper.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>

		<!--Othercharts js-->
		<script src="{{URL::asset('admin/assets/plugins/othercharts/jquery.sparkline.min.js')}}"></script>

		<!-- Circle-progress js-->
		<script src="{{URL::asset('admin/assets/js/circle-progress.min.js')}}"></script>

		<!-- Jquery-rating js-->
		<script src="{{URL::asset('admin/assets/plugins/rating/jquery.rating-stars.js')}}"></script>

		<!--Sidemenu js-->
		<script src="{{URL::asset('admin/assets/plugins/sidemenu/sidemenu.js')}}"></script>
		
		<!-- P-scroll js-->
		<script src="{{URL::asset('admin/assets/plugins/p-scrollbar/p-scrollbar.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/p-scrollbar/p-scroll1.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/p-scrollbar/p-scroll.js')}}"></script>

		@yield('js')
		<!-- Simplebar JS -->
		<script src="{{URL::asset('admin/assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
		<!-- Custom js-->
		<script src="{{URL::asset('admin/assets/js/custom.js')}}"></script>	
                <script src="{{URL::asset('admin/assets/js/toastr.min.js')}}"></script>
                
                <!--  Datatable -->
                <script src="{{URL::asset('admin/assets/js/datatable/datatables.min.js')}}"></script>
	<!-- INTERNAL Popover js -->
		<script src="{{URL::asset('admin/assets/js/popover.js')}}"></script>

		<!-- INTERNAL Sweet alert js -->
		<script src="{{URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/sweet-alert.js')}}"></script>
