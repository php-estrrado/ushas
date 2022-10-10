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
														
{{ Form::open(array('url' => "admin/rewards/save", 'id' => 'rewardsForm', 'name' => 'rewardsForm', 'class' => '','files'=>'true','novalidate')) }}

@if(isset($rewards['id'])) 
<input type="hidden" name="id" value="{{ $rewards['id'] }}">	
@else
<input type="hidden" name="id" value="0">	
@endif
								
<div class="row mt-4">
	<div class="card">
		<div class="card-header">
			<h3 class="card-title">Reward Options</h3>
		</div>
		
	</div>
	<div class="col-lg-12">
		<div class="expanel expanel-default">
			<div class="expanel-heading">
				<h3 class="expanel-title">Referral Reward Options</h3>
			</div>
			<div class="row">
					<div class="col-lg-6">
					<div class="expanel-body">
						<label class="form-label" for="type" >Reward Type</label>
							<select class="form-control" id="reward_types" onchange="loaddata()" name="reward_types">
								<option value="coupon" @if($rewards["reward"]=="coupon") selected @endif>Coupon</option>
								<option value="cashback" @if($rewards["reward"]=="cashback") selected @endif>Cashback</option>
							</select>
					</div>
				</div>
			</div>
					<div class="row">
					<div class="col-lg-6">
					<div class="expanel-body">
						<label class="form-label">Referrer Reward</label>
					<div class="custom-controls-stacked">
						<?php //dd($rewards['all_types']); ?>
						@if($rewards && count($rewards['all_types']) > 0)
						@foreach($rewards['all_types'] as $row)
				@if($row['type']=='referrer')		
				<label class="custom-control custom-radio">
				<input type="radio" class="custom-control-input reward_type" onclick="loadOptions_referrer();" id="rwd_type_referrer" name="rwd_type_referrer" value="{{ $row['id'] }}" <?php if($rewards['rwd_type_referrer']==$row['id']){ echo "checked";}?>>
				<span class="custom-control-label">{{ $row['rwd_type_title'] }}</span>
				</label>
				@endif
						@endforeach
						@endif
				<span id="error_rwd_type_referrer" class="error"></span>		
					


					</div>
					</div>
					</div>

					<div class="col-lg-6" style="min-height: 175px;">
					<div class="expanel-body">
             
             <!--cashback-->
                    <div  class="referral_cashback_2" style="display: none;">
				<label class="form-label" for="cashback_2" >Referral First Purchase Cashback <span class="text-red">*</span></label>
				<input min="1" type = "number"
    maxlength = "6" step="1" type="number" name="first_purchase_cashback" id="cashback_2" class="form-control"  value="{{$rewards['referral_cashback_purchase']}}" />
    <span class="error" id="error_cashback_2"></span>
				</div>
				<div  class="referral_cashback_1" style="display: none;">
				<label class="form-label" for="cashback_1" >Referral When Register Cashback <span class="text-red">*</span></label>
				<input min="1" type = "number"
    maxlength = "6" step="1" type="number" name="register_cashback" id="cashback_1" class="form-control"  value="{{$rewards['referral_cashback_register']}}" />
    <span class="error" id="error_cashback_1"></span>
				</div>

				<!---coupon-->
				<div  class="referral_coupon_2" style="display: none;">
				<label class="form-label" for="coupon_2" >Referral First Purchase Coupon <span class="text-red">*</span></label>
				<select class="form-control" id="coupon_2" name="refral_coupon_puchase">
					@foreach($coupons as $cpn)
					<option value="{{$cpn['id']}}" @if($rewards['referral_coupon_purchase']==$cpn['id']) selected @endif>{{$cpn['cpn_title']}}</option>
					@endforeach
				</select>
				<span class="error" id="error_coupon_2"></span>
				</div>
				<div  class="referral_coupon_1" style="display: none;">
				<label class="form-label" for="coupon_1" >Referral When Register Coupon <span class="text-red">*</span></label>
				<select class="form-control" id="coupon_1" name="refral_coupon_register">
					@foreach($coupons as $cpn)
					<option value="{{$cpn['id']}}" @if($rewards['referral_coupon_register']==$cpn['id']) selected @endif>{{$cpn['cpn_title']}}</option>
					@endforeach
				</select>
				<span class="error" id="error_coupon_1"></span>
				</div>

	<!-- @if($rewards && count($rewards['all_types']) > 0)
						@foreach($rewards['all_types'] as $row)



				@if($row['type']=='referrer')		
				@if($row['id'] !=3)
				<div  class="reward_type_options_referrer_cashback points_cash_referrer{{ $row['id'] }}" >
				<label class="form-label" for="points_{{ $row['id'] }}" >{{ $row['rwd_type_title'] }} Cashback <span class="text-red">*</span></label>
				<input min="1" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
    type = "number"
    maxlength = "6" step="1" type="number" name="reward_points[{{ $row['id'] }}]" id="points_{{ $row['id'] }}" class="form-control"  @if($rewards['rwd_type_referrer'] == $row['id'] || $rewards['rwd_type_referrer'] == 3)  value="{{ $row['points'] }}" @endif />
				</div>
				@endif
				@endif

						@endforeach
						@endif
 -->

					

					

					</div>
					</div>
					</div>

					<!--referral-->
					<div class="row">
					<div class="col-lg-6">
					<div class="expanel-body">
						<label class="form-label">Referral Reward</label>
					<div class="custom-controls-stacked">
						<?php //dd($rewards['all_types']); ?>
						@if($rewards && count($rewards['all_types']) > 0)
						@foreach($rewards['all_types'] as $row)
				@if($row['type']=='referral')		
				<label class="custom-control custom-radio">
				<input type="radio" class="custom-control-input reward_type" onclick="loadOptions_referral();" id="rwd_type_referral" name="rwd_type_referral" value="{{ $row['id'] }}" <?php if($rewards['rwd_type_referral']==$row['id']){ echo "checked";}?>>
				<span class="custom-control-label">{{ $row['rwd_type_title'] }}</span>
				</label>
				@endif
						@endforeach
						@endif
				<span id="error_rwd_type_referral" class="error"></span>	
					


					</div>
					</div>
					</div>

					<div class="col-lg-6" style="min-height: 175px;">
					<div class="expanel-body">
                   
                   <!--referrer-->

                   <!--cashback-->
                    <div  class="referrer_cashback_5" style="display: none;">
				<label class="form-label" for="cashback_5" > First Purchase Cashback <span class="text-red">*</span></label>
				<input min="1" type = "number"
    maxlength = "6" step="1" type="number" name="referrer_first_purchase_cashback" id="cashback_5" class="form-control"  value="{{$rewards['referrer_cashback_purchase']}}" />
    <span class="error" id="error_cashback_5"></span>
				</div>
				<div  class="referrer_cashback_4" style="display: none;">
				<label class="form-label" for="cashback_4" > When Register Cashback <span class="text-red">*</span></label>
				<input min="1" maxlength = "6" step="1" type="number" name="referrer_register_cashback" id="cashback_4" class="form-control"  value="{{$rewards['referrer_cashback_register']}}" />
    <span class="error" id="error_cashback_4"></span>
				</div>

				<!---coupon-->
				<div  class="referrer_coupon_5" style="display: none;">
				<label class="form-label" for="referrer_first_purchase_coupon" > First Purchase Coupon <span class="text-red">*</span></label>
				<select class="form-control" id="referrer_first_purchase_coupon" name="refer_coupon_puchase">
					@foreach($coupons as $cpn)
					<option value="{{$cpn['id']}}" @if($rewards['referrer_coupon_purchase']==$cpn['id']) selected @endif>{{$cpn['cpn_title']}}</option>
					@endforeach
				</select>
				</div>
				<div  class="referrer_coupon_4" style="display: none;">
				<label class="form-label" for="referrer_register_coupon" > When Register Coupon <span class="text-red">*</span></label>
				<select class="form-control" id="referrer_register_coupon" name="refer_coupon_register">
					@foreach($coupons as $cpn)
					<option value="{{$cpn['id']}}" @if($rewards['referrer_coupon_register']==$cpn['id']) selected @endif>{{$cpn['cpn_title']}}</option>
					@endforeach
				</select>
				</div>

	<!-- @if($rewards && count($rewards['all_types']) > 0)
						@foreach($rewards['all_types'] as $row)




				@if($row['id'] !=6)
				<div  class="reward_type_options_cashback points_{{ $row['id'] }}" >
				<label class="form-label" for="points_{{ $row['id'] }}" >{{ $row['rwd_type_title'] }} Cashback <span class="text-red">*</span></label>
				<input min="1" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
    type = "number"
    maxlength = "6" step="1" type="number" name="reward_points[{{ $row['id'] }}]" id="points_{{ $row['id'] }}" class="form-control"  @if($rewards['rwd_type_referral'] == $row['id'] || $rewards['rwd_type_referral'] == 3)  value="{{ $row['points'] }}" @endif />
				</div>
				@endif


						@endforeach
						@endif -->


					

					

					</div>
					</div>
					</div>

<div class="row">
<div class="col-lg-6">
<div class="expanel-body">
<!-- <div  class="referral-points" >
<label class="form-label" for="point_val" >Referral Point Value <span class="text-red">*</span></label>
<input min="1" step="1" type="number" name="point_val" id="point_val" @if(isset($rewards['point_val'])) value="{{ $rewards['point_val'] }}" @endif placeholder="Point equivalent
 to amount" class="form-control"  />
</div> -->

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
			<span class="error" id="error_ord_amount"></span>
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
<span class="error" id="error_min_order"></span>
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


// $("#frontval").click(function(){

// $("#rewardsForm").validate({
// rules: {


// "reward_points[2]": {
// required: function(){
// return (''+$("#rwd_type[value='2']:checked").val() !="" || $("#rwd_type[value='3']:checked").val() !="") ;
// }, 
// number: true,
// min: 1
// },
// "reward_points[1]": {
// required: function(){
// return (''+$("#rwd_type[value='1']:checked").val() !="" || $("#rwd_type[value='3']:checked").val() !="") ;
// }, 
// number: true,
// min: 1
// },

// point_val : {
// required: true,
// min: 1
// },
// ord_amount : {
// required: true,
// min: 1
// },
// ord_min_amount : {
// required: true,
// min: 1
// },



// },

// messages : {
// "reward_points[2]": {
// required: "First Purchase Points is required."
// },
// "reward_points[1]": {
// required: "Register Points is required."
// },
// point_val: {
// required: "Referral Point Value is required.",
// min: "Referral Point Value must be greater than 0"
// },
// ord_amount: {
// required: "Amount is required.",
// min: "Amount must be greater than 0"
// },
// ord_min_amount: {
// required: "Minimum Order Amount is required.",
// min: "Minimum Order Amount must be greater than 0"
// }
// },
//  errorPlacement: function(error, element) {
//  	 $("#errNm1").empty();
//             if (element.attr("name") == "ofr_code" ) {
//                 $("#errNm1").text($(error).text());
                
//             }else {
//                error.insertAfter(element)
//             }
//         },

// });
// });


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

//loadOptions();
loadOptions_referrer();
loadOptions_referral();



});

function loadOptions(){

var cu_val = $("[name='rwd_type']:checked").val();
var reward_types = $("reward_types").val();
$(".reward_type_options").hide('1000');  
if(cu_val == 3 && reward_types=="coupon") {
$(".reward_type_options_coupon").show('1000'); 
}else if(cu_val == 3 && reward_types=="cashback")
{
$(".reward_type_options").show('1000'); 
}
else {
$(".points_"+cu_val).show('1000');	
} 

}

function loaddata()
{
	loadOptions_referrer();
	loadOptions_referral();

}

function loadOptions_referrer(){

var cu_val = $("[name='rwd_type_referrer']:checked").val();

var reward_types = $("#reward_types").val();
//alert(cu_val);
$(".referral_cashback_1").hide('1000');  
$(".referral_cashback_2").hide('1000'); 
$(".referral_coupon_1").hide('1000'); 
$(".referral_coupon_2").hide('1000');
if(cu_val == 3 && reward_types=="cashback") {
$(".referral_cashback_1").show('1000'); 
$(".referral_cashback_2").show('1000');
}
else if(cu_val == 3 && reward_types=="coupon") {
$(".referral_coupon_1").show('1000'); 
$(".referral_coupon_2").show('1000');
}
else{
if(reward_types=="cashback")
{	
$(".referral_cashback_"+cu_val).show('1000');	
}
else
{
$(".referral_coupon_"+cu_val).show('1000');	
}
}

}


function loadOptions_referral(){

var cu_val = $("[name='rwd_type_referral']:checked").val();

var reward_types = $("#reward_types").val();
//alert(cu_val);
$(".referrer_cashback_4").hide('1000');  
$(".referrer_cashback_5").hide('1000'); 
$(".referrer_coupon_4").hide('1000'); 
$(".referrer_coupon_5").hide('1000');
if(cu_val == 6 && reward_types=="cashback") {
$(".referrer_cashback_4").show('1000'); 
$(".referrer_cashback_5").show('1000');
}
else if(cu_val == 6 && reward_types=="coupon") {
$(".referrer_coupon_4").show('1000'); 
$(".referrer_coupon_5").show('1000');
}
else{
if(reward_types=="cashback")
{	
$(".referrer_cashback_"+cu_val).show('1000');	
}
else
{
$(".referrer_coupon_"+cu_val).show('1000');	
}
}

}



$(function() {
    //hang on event of form with id=myform
    $("#rewardsForm").submit(function(e) {
    
        //prevent Default functionality
        e.preventDefault();
        var cu_val_er = $("[name='rwd_type_referrer']:checked").val();
        var cu_val_al = $("[name='rwd_type_referral']:checked").val();
        var reward_types = $("#reward_types").val();

        var foundA=false;
        var foundB=false;
        var foundC=false;
        var foundD=false;
        var foundE=false;

	if($('input[name="rwd_type_referral"]:checked').length == 0)
	{
		$("#error_rwd_type_referral").text("This field is required");
	}
	else if($('input[name="rwd_type_referrer"]:checked').length == 0)
	{
		$("#error_rwd_type_referrer").text("This field is required");
	}
	else
	{
		$("#error_rwd_type_referral").text("");
		$("#error_rwd_type_referrer").text("");
	}
	if(reward_types=='coupon')
	{
		var foundA=true;
        var foundB=true;
        var foundC=true;
        var foundD=true;
	}
   if(cu_val_er==3 && reward_types=='coupon'){
		if($("#coupon_1").val()=='')
		{
			$("#error_coupon_1").text("This field is required");
		}
		else if($("#coupon_2").val()=='')
		{
			$("#error_coupon_2").text("This field is required");
		}
		else
		{   
			$("#error_coupon_1").text("");
			$("#error_coupon_2").text("");
		}
	}
	else if(cu_val_er!=3 && reward_types=='coupon')
	{
		if($("#coupon_"+cu_val_er).val()=='')
		{
			$("#error_coupon_"+cu_val_er).text("This field is required");
		}
		else
		{
			
			$("#error_coupon_"+cu_val_er).text("");
		}
	}
	else if(cu_val_er==3 && reward_types=='cashback' && foundA===false){
		foundB=true;
		if($("#cashback_1").val()=='')
		{
			$("#error_cashback_1").text("This field is required");
			foundA=false;
		}
		else if($("#cashback_2").val()=='')
		{
			$("#error_cashback_2").text("This field is required");
			foundA=false;
		}
		else
		{   
			foundA=true;
			$("#error_cashback_2").text("");
			$("#error_cashback_1").text("");
		}
		//foundA=true;
	}
	else if(cu_val_er!=3 && reward_types=='cashback' && foundB===false)
	{   
		foundA=true;
		if($("#cashback_"+cu_val_er).val()=='')
		{
			foundB=false;
			$("#error_cashback_"+cu_val_er).text("This field is required");
		}
		else
		{    
			$("#error_cashback_2").text("");
			$("#error_cashback_1").text("");
			foundB=true;
					}
		//foundB=true;
	}
	if(cu_val_al==6 && reward_types=='cashback' && foundC===false){
		//alert('still4');
		foundD=true;
		if($("#cashback_4").val()=='')
		{
			$("#error_cashback_4").text("This field is required");
			foundC=false;
		}
		else if($("#cashback_5").val()=='')
		{
			$("#error_cashback_5").text("This field is required");
			foundC=false;
		}
		else
		{
			$("#error_cashback_4").text("");
			$("#error_cashback_5").text("");
			foundC=true;
		}
		//foundC=true;
	}
	else if(cu_val_al!=6 && reward_types=='cashback' && foundD===false)
	{ 
	foundC=true;
		if($("#cashback_"+cu_val_al).val()=='')
		{
			$("#error_cashback_"+cu_val_al).text("This field is required");
			foundD=false;
		}
		else{
		foundD=true;
	 }
	}
	else
	{
		//$('form#rewardsForm').submit();
		
	}

    $("#error_min_order").text("");
			$("#error_ord_amount").text("");
	if(foundE===false)
	{
		if($("#ord_min_amount").val()=='')
		{
			$("#error_min_order").text("This field is required");
		}
		else if($("#ord_amount").val()=='')
		{
			$("#error_ord_amount").text("This field is required");
		}
		else
		{
			foundE=true;
			$("#error_min_order").text("");
			$("#error_ord_amount").text("");
		}
	}

	if(foundA===true && foundB===true && foundC===true && foundD===true && foundE===true)
	{
		$("#error_rwd_type_referral").text("");
		$("#error_rwd_type_referrer").text("");
		//alert('fffsub');
		$('#rewardsForm')[0].submit();
		
	}
});
    });
</script>
@endsection