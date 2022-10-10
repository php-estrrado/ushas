@extends('layouts.admin')
@section('css')
		<!-- INTERNAl Data table css -->
		<link href="{{URL::asset('admin/assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}"  rel="stylesheet">
		<link href="{{URL::asset('admin/assets/plugins/datatable/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />
				<link href="{{URL::asset('admin/assets/css/combo-tree.css')}}" rel="stylesheet" />
		<link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.0.45/css/materialdesignicons.min.css">
@endsection
@section('page-header')
						<!--Page header-->


						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0">{{ $title }}</h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Ecom Benefits</a></li>
									
									<li class="breadcrumb-item active" aria-current="page"><a href="#">{{ $title }}</a></li>
								</ol>
							</div>
							<div class="page-rightheader">
								
							</div>
						</div>
                        <!--End Page header-->
@endsection
@section('content')
						<!-- Row -->
						<div class="row flex-lg-nowrap">
							<div class="col-12">

								<div class="row flex-lg-nowrap">
									<div class="col-12 mb-3">
										<div class="e-panel card">
											<div class="card-body">
												<div class="e-table">
				
													<div class="table-responsiv table-lg mt-3">
														
{{ Form::open(array('url' => "admin/settings/save", 'id' => 'settingsForm', 'name' => 'settingsForm', 'class' => '','files'=>'true')) }}

@if(isset($settings['id'])) 
<input type="hidden" name="id" value="{{ $settings['id'] }}">	
@else
<input type="hidden" name="id" value="0">	
@endif
								
<div class="row mt-4">
	<div class="card">
		<div class="card-header">
			<h3 class="card-title">Settings</h3>
		</div>
		
	</div>
	<div class="col-lg-12">
		<div class="expanel expanel-default">
			<div class="expanel-heading">
				<h3 class="expanel-title">Refund Deduction Options</h3>
			</div>
				
<div class="row">
<div class="col-lg-6">
<div class="expanel-body">
<div  class="referral-points" >
<label class="form-label" for="refund_deduction" >Refund Deduction (%) <span class="text-red">*</span></label>
<input min="1" step="1" type="number" name="refund_deduction" id="refund_deduction" @if(isset($settings['refund_deduction'])) value="{{ $settings['refund_deduction'] }}" @endif placeholder="Refund Deduction(%)" class="form-control"  />
</div>

</div>
</div>

<div class="col-lg-6">
<div class="expanel-body">
<div  class="referral-points" >
<label class="form-label" for="return_period" >Return Time Period (in hours) <span class="text-red">*</span></label>
<input min="1" step="1" type="number" name="return_period" id="return_period" @if(isset($settings['return_period'])) value="{{ $settings['return_period'] }}" @endif placeholder="Return Time Period(After Delivery)" class="form-control"  />
</div>

</div>
</div>
</div>

	</div>


														<div class="row">
															<div class="col d-flex justify-content-end">
															
															<input class="btn btn-primary" type="submit" id="frontval" value="Save Changes">
															</div>
														</div>
													
												</div>
												{{Form::close()}}

													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- End Row -->


						
							

					</div>
				</div><!-- end app-content-->
            </div>
@endsection
@section('js')
		<!-- INTERNAl Data tables -->
<script src="{{URL::asset('admin/assets/js/jquery.validate.min.js')}}"></script>
<script src="{{URL::asset('admin/assets/js/bootstrap-datepicker.js')}}"></script>
	<!-- INTERNAL Popover js -->
		<script src="{{URL::asset('admin/assets/js/popover.js')}}"></script>

		<!-- INTERNAL Sweet alert js -->
		<script src="{{URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/sweet-alert.js')}}"></script>
<script src="{{URL::asset('admin/assets/js/comboTreePlugin.js')}}"></script>


<script type="text/javascript">
	jQuery(document).ready(function(){


$("#frontval").click(function(){

$("#settingsForm").validate({
rules: {


refund_deduction : {
required: true,
min: 0
},
return_period : {
required: true,
min: 0
},




},

messages : {

refund_deduction: {
required: "Refund Deduction is required.",
min: "Refund Deduction must be greater than 0"
},
return_period: {
required: "Return Time Period is required.",
min: "Return Time Period must be greater than 1hr"
},

 errorPlacement: function(error, element) {
 	 $("#errNm1").empty();
            if (element.attr("name") == "ofr_code" ) {
                $("#errNm1").text($(error).text());
                
            }else {
               error.insertAfter(element)
            }
        },

});
});


	});
</script>

<script type="text/javascript">
    $(document).ready(function(){
            @if(Session::has('message'))
            @if(session('message')['type'] =="success")
            
            toastr.success("{{session('message')['text']}}"); 
            @else
            toastr.error("{{session('message')['text']}}"); 
            @endif
            @endif
            
            @if ($errors->any())
            @foreach ($errors->all() as $error)
            toastr.error("{{$error}}"); 
            
            @endforeach
            @endif
    });
    </script>


<script type="text/javascript">
$(document).ready(function(){

loadOptions();



});

function loadOptions(){

var cu_val = $("[name='rwd_type']:checked").val();
$(".reward_type_options").hide('1000');  
if(cu_val == 3) {
$(".reward_type_options").show('1000'); 
}else {
$(".points_"+cu_val).show('1000');	
} 

}
</script>
@endsection