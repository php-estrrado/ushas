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
														
{{ Form::open(array('url' => "admin/rewards/save", 'id' => 'rewardsForm', 'name' => 'rewardsForm', 'class' => '','files'=>'true')) }}

@if(isset($rewards['id'])) 
<input type="hidden" name="id" value="{{ $rewards['id'] }}">	
@else
<input type="hidden" name="id" value="0">	
@endif
								
<div class="row mt-4">
	<div class="card">
		<div class="card-header">
			<h3 class="card-title">Invite & Earn Settings</h3>
		</div>
		
	</div>
	<div class="col-lg-12">
		<div class="expanel expanel-default">
			<div class="expanel-heading">
				<h3 class="expanel-title">Referral Cashback Options</h3>
			</div>
					<div class="row">
					<div class="col-lg-6">
					<div class="expanel-body">
					<div class="custom-controls-stacked">
						<?php //dd($rewards['all_types']); ?>
						@if($rewards && count($rewards['all_types']) > 0)
						@foreach($rewards['all_types'] as $row)

				<label class="custom-control custom-radio">
				<input type="radio" class="custom-control-input reward_type" onclick="loadOptions();" id="rwd_type" name="rwd_type" value="{{ $row['id'] }}" <?php if($rewards['rwd_type']==$row['id']){ echo "checked";}?>>
				<span class="custom-control-label">{{ $row['rwd_type_title'] }}</span>
				</label>
						@endforeach
						@endif
					
					


					</div>
					</div>
					</div>

					<div class="col-lg-6" style="min-height: 175px;">
					<div class="expanel-body">


	@if($rewards && count($rewards['all_types']) > 0)
						@foreach($rewards['all_types'] as $row)




				@if($row['id'] !=3)
				<div  class="reward_type_options points_{{ $row['id'] }}" >
				<label class="form-label" for="points_{{ $row['id'] }}" >{{ $row['rwd_type_title'] }} Points <span class="text-red">*</span></label>
				<input min="1" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
    type = "number"
    maxlength = "6" step="1" type="number" name="reward_points[{{ $row['id'] }}]" id="points_{{ $row['id'] }}" class="form-control"  @if($rewards['rwd_type'] == $row['id'] || $rewards['rwd_type'] == 3)  value="{{ $row['points'] }}" @endif />
				</div>
				@endif


						@endforeach
						@endif


					

					

					</div>
					</div>
					</div>

<div class="row">
<div class="col-lg-6">
<div class="expanel-body">
<div  class="referral-points" >
<label class="form-label" for="point_val" >Referral Point Value <span class="text-red">*</span></label>
<input min="1" step="1" type="number" name="point_val" id="point_val" @if(isset($rewards['point_val'])) value="{{ $rewards['point_val'] }}" @endif placeholder="Point equivalent
 to amount" class="form-control"  />
</div>

</div>
</div>

<div class="col-lg-6">
<!-- <div class="expanel-body">
<div  class="purchase_type_options purchase-number" >
<label class="form-label" for="purchase_number" >Purchase Number <span class="text-red">*</span></label>
<input min="1" step="1" type="number" name="purchase_number" id="purchase_number" class="form-control"  />
</div>

</div> -->
</div>


		</div>
	</div>

	<div class="col-lg-12">
		<div class="expanel expanel-default">
			<div class="expanel-heading">
				<h3 class="expanel-title">First Order Cash Back/Discount</h3>
			</div>
			<div class="row">
			<div class="col-lg-6">
			<div class="expanel-body">
			<div  class="first_order" >
			<label class="form-label" for="ord_amount" >Amount <span class="text-red">*</span></label>
			<input min="1" step="1" type="number" name="ord_amount" id="ord_amount" @if(isset($rewards['ord_amount'])) value="{{ $rewards['ord_amount'] }}" @endif  class="form-control"  />
			</div>
			</div>
			</div>

			<div class="col-lg-6">
			<div class="expanel-body">
			<label class="form-label" for="ord_type">Type </label>

<?php 

if(isset($rewards['ord_type'])) {

if($rewards['ord_type'] == "cashback")
{

$cashback = "selected";
$discount = ""; 
}
else
{

$discount = "selected"; 
$cashback = ""; 
}
} else {
$cashback = ""; 
$discount = "";
}
 ?> 
			<select class="form-control" name="ord_type" id="ord_type">
			<option value="cashback" {{ $cashback }} >Cashback</option>
			<option value="discount" {{ $discount }} >Discount</option>

			</select>

			</div>
			</div>
			</div>

<div class="row">
<div class="col-lg-6">
<div class="expanel-body">
<div  class="purchase_type_options purchase-number" >
<label class="form-label" for="ord_min_amount" >Minimum Order Amount <span class="text-red">*</span></label>
<input min="1" step="1" type="number" name="ord_min_amount" id="ord_min_amount" @if(isset($rewards['ord_min_amount'])) value="{{ $rewards['ord_min_amount'] }}" @endif   class="form-control"  />
</div>
</div>
</div>

<div class="col-lg-6">
<!-- <div class="expanel-body">
<label class="form-label">Type </label>

<select class="form-control" name="category_id" id="category_id" required onchange="loadsubcat()">
<option value="cashback">Cashback</option>
<option value="discount">Discount</option>

</select>

</div> -->
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

$("#rewardsForm").validate({
rules: {


"reward_points[2]": {
required: function(){
return (''+$("#rwd_type[value='2']:checked").val() !="" || $("#rwd_type[value='3']:checked").val() !="") ;
}, 
number: true,
min: 1
},
"reward_points[1]": {
required: function(){
return (''+$("#rwd_type[value='1']:checked").val() !="" || $("#rwd_type[value='3']:checked").val() !="") ;
}, 
number: true,
min: 1
},

point_val : {
required: true,
min: 1
},
ord_amount : {
required: true,
min: 1
},
ord_min_amount : {
required: true,
min: 1
},



},

messages : {
"reward_points[2]": {
required: "First Purchase Points is required."
},
"reward_points[1]": {
required: "Register Points is required."
},
point_val: {
required: "Referral Point Value is required.",
min: "Referral Point Value must be greater than 0"
},
ord_amount: {
required: "Amount is required.",
min: "Amount must be greater than 0"
},
ord_min_amount: {
required: "Minimum Order Amount is required.",
min: "Minimum Order Amount must be greater than 0"
}
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