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
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>User Management</a></li>
									
									<li class="breadcrumb-item active" aria-current="page"><a href="#">{{ $title }}</a></li>
								</ol>
							</div>
							<div class="page-rightheader">
								<!-- <div class="btn btn-list">
									<a href="#" class="btn btn-info"><i class="fe fe-settings mr-1"></i> General Settings </a>
									<a href="#" class="btn btn-danger"><i class="fe fe-printer mr-1"></i> Print </a>
									<a href="#"  data-target="#user-form-modal" data-toggle="modal" class="btn btn-danger addmodule"><i class="fe fe-shopping-cart mr-1"></i> Add New</a>
								</div> -->
							</div>
						</div>
                        <!--End Page header-->
@endsection
@section('content')
						<!-- Row -->
						<div class="row flex-lg-nowrap">
							<div class="col-12">
<!-- 
								@if(Session::has('message'))

								<div class="alert alert-{{session('message')['type']}}" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>{{session('message')['text']}}</div>
								@endif
								@if ($errors->any())
								@foreach ($errors->all() as $error)

								<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>{{$error}}</div>
								@endforeach
								@endif -->
								<div class="row flex-lg-nowrap">
									<div class="col-12 mb-3">
										<div class="e-panel card">
											<div class="card-body">
												<div class="e-table">
													<div class="table-responsiv table-lg mt-3">
														
{{ Form::open(array('url' => "admin/coupons/save", 'id' => 'couponForm', 'name' => 'userForm', 'class' => '','files'=>'true')) }}

												<input type="hidden" name="id" value="{{ $coupon['id'] }}">
												<input type="hidden" name="cpn_title_cid" value="{{ $coupon['cpn_title_cid'] }}">
												<input type="hidden" name="cpn_desc_cid" value="{{ $coupon['cpn_desc_cid'] }}">

												
														<div class="row">
															<div class="col">


																<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label class="form-label">Select Language <span class="text-red">*</span></label>
                                                                <select class="form-control custom-select select2" name="glo_lang_cid" required>
                                                                    @php
  $def_lang =DB::table('glo_lang_lk')->where('is_active', 1)->first();
        $content_table=DB::table('cms_content')->where('cnt_id', $coupon['cpn_title_cid'])->where('lang_id', $def_lang->id)->first();
        if($content_table){ 
        $lang_id = $content_table->lang_id;
    }
         @endphp
                                                                      @foreach ($language as $lang)
                                                                    <option value="{{ $lang->id }}" @php if($lang_id==$lang->id){ echo "selected";} @endphp >{{ $lang->glo_lang_name }}</option>
                                                                    @endforeach
                                                                </select>
																		</div>
																	@error('glo_lang_cid')
																	<p style="color: red">{{ $message }}</p>
																	@enderror
																	</div>
																	<div class="col">
																		<div class="form-group">
																			<label class="form-label">Coupon Title <span class="text-red">*</span></label>
																			
																			{!! Form::text('coupon_title', $coupon['cpn_title'], ['class' => 'form-control','required','id'=>'coupon_title']) !!}
																		</div>
																		@error('coupon_title')
																	<p style="color: red" class="error">{{ $message }}</p>
																	@enderror
																	</div>
																	
																</div>
																<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label class="form-label">Description <span class="text-red">*</span></label>
																			
																			{!! Form::textarea('coupon_desc', $coupon['cpn_desc'], ['class' => 'form-control','rows' => 3,'id'=>'coupon_desc']) !!}
																		</div>
																		
																	</div>
																	
																</div>
																<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label class="form-label">Category </label>
																		
																			<select class="form-control" name="category_id" id="category_id" required onchange="loadsubcat()">
																			<option value="0">Select Category</option>


																			@if($categories && count($categories) > 0)
																			@foreach($categories as $row)

																			
																			<option value="{{ $row['category_id'] }}" <?php if($coupon['category_id']==$row['category_id']){ echo "selected";}?>>{{ $row['cat_name'] }}</option>
																			@endforeach
																			@endif
																			</select>
																		</div>
																	</div>
																	<div class="col">
																		<div class="form-group">
																	
																			<label class="form-label">Subcategory </label>
																			<input type="text" id="sub-category-id" placeholder="Type to filter" name="subcat_id" autocomplete="off" hidden value="@if(isset($coupon['subcategory_id'])) {{ $coupon['subcategory_id'] }} @endif" />
																			 <input type="text" id="sub-category-drop" class="form-control " value="{{ $coupon['subcat_name'] }}" placeholder="Select Subcategory" readonly style="background-color: #fff !important;">
																		</div>
																	</div>
																	
																</div>
																<div class="row">
																	<div class="col">
																		<div class="form-group">
																		<label class="form-label">Shop </label>
																		
																			<select class="form-control" name="seller_id" id="seller_id" required >
																			<option value="0">Select Shop</option>


																			@if($sellers && count($sellers) > 0)
																			@foreach($sellers as $row)

																			
																			<option value="{{ $row['seller_id'] }}" <?php if($coupon['seller_id']==$row['seller_id']){ echo "selected";}?>>{{ $row['store_name'] }}</option>
																			@endforeach
																			@endif
																			</select>
																			
																			

																		
																		</div>
																	</div>
																	<div class="col">
																		
																	</div>
																	
																	
																</div>
																<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label class="form-label">Purchase Type </label> <div class="radio-opts">
														<?php 
														$number_check = $amount_check = "";
														$number_css = $amount_css = "";
														$number_val = $amount_val = "";
														 if($coupon['purchase_type']=="number") {
														 	$number_check = "checked";
														 	$number_val = $coupon['purchase_number'];
														 	$number_css = "display:block;";
														 	$amount_css = "display:none;";
														}else {
															$amount_check = "checked";
															$amount_val = $coupon['purchase_amount'];
															$amount_css = "display:block;";
															$number_css = "display:none;";
														} ?>				
                          <input class="purchase_type" id="purchase_type" type="radio" {{ $number_check }}  name="purchase_type" value="number"  /> Purchase Number &nbsp;&nbsp;
                          <input class="purchase_type"  type="radio" id="purchase_type" name="purchase_type" value="amount"  {{ $amount_check }}  /> Purchase Amount  &nbsp;&nbsp;
                        </div>
																			
																		</div>
																		
																	</div>

																	<div class="col">
																		<div class="form-group">
														<div  class="purchase_type_options purchase-number" 
														style='{{ $number_css }}'>
														<label class="form-label" for="purchase_number" >Purchase Number <span class="text-red">*</span></label>
														
														<input min="1" step="1" type="number" name="purchase_number" id="purchase_number" class="form-control" value="{{ $number_val }}" />

														</div>

														<div  class="purchase_type_options purchase-amount" 
														style='{{ $amount_css }}'>
														<label class="form-label" for="purchase_amount">Purchase Amount <span class="text-red">*</span></label>
													
														<input type="Number" min="0" name="purchase_amount" value="{{ $amount_val }}" id="purchase_amount" class="form-control"  />

														</div>
																			

																		</div>
																		@error('purchase_number','purchase_amount')
																	<p style="color: red" class="error">{{ $message }}</p>
																	@enderror
																	</div>
																	
																	
																	
																</div>

																<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label class="form-label">Min. Order Amount <span class="text-red">*</span></label>
																	
																			{!! Form::number('ofr_min_amount', $coupon['ofr_min_amount'], ['class' => 'form-control','required','id'=>'ofr_min_amount']) !!}
																		</div>
																		@error('ofr_min_amount')
																	<p style="color: red" class="error">{{ $message }}</p>
																	@enderror
																	</div>
																	<div class="col">
																		
															<div class="form-group">
															<label class="form-label">Offer Value <span class="text-red">*</span></label>

															{!! Form::number('ofr_value', $coupon['ofr_value'], ['class' => 'form-control','required','id'=>'ofr_value']) !!}
															</div>
															@error('ofr_value')
																	<p style="color: red" class="error">{{ $message }}</p>
																	@enderror
															</div>
																
																	
																</div>
																
															<div class="row">
															
															<?php 
														$ofr_value_per = $ofr_value_amt = "";
														$ofr_type_dis = $ofr_type_csb = "";
														
														 if($coupon['ofr_value_type']=="percentage") {
														 	$ofr_value_per = "selected";
														}else {
															$ofr_value_amt = "selected";
														}
														 if($coupon['ofr_type']=="cashback") {
														 	$ofr_type_csb = "selected";
														}else {
															$ofr_type_dis = "selected";
														}
														 ?>

															<div class="col">
															<div class="form-group">
															<label class="form-label">Offer Value Type </label>

															<select name="ofr_value_type" id="ofr_value_type" class="form-control">
																<option value="percentage" {{ $ofr_value_per }}>Percentage</option>
																<option value="amount" {{ $ofr_value_amt }}>Amount</option>
																
															</select>
															</div>
															</div>
															<div class="col">
															<div class="form-group">
															<label class="form-label">Offer Type </label>

															<select name="ofr_type" id="ofr_type" class="form-control">
																<option value="cashback" {{ $ofr_type_csb }}>Cashback</option>
																<option value="discount" {{ $ofr_type_dis }}>Discount</option>
																
															</select>
															</div>
															</div>

															</div>

															<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label class="form-label">Code (6 characters) <span class="text-red">*</span></label>
														<div class="input-group">
												<input type="text" class="form-control" maxlength="6" minlength='6' required name="ofr_code" id="ofr_code" placeholder="Click to generate..." value="{{ $coupon['ofr_code'] }}">
												<span class="input-group-append">
													<button class="btn btn-primary" onClick="couponCode(6);" type="button">Generate</button>
												</span>
											</div>
																			

																		</div>
																		<p style="color: red" id="errNm1"></p>
																		@error('ofr_code')
																	<p style="color: red" class="error" id="errNm1">{{ $message }}</p>
																	@enderror
																	</div>
																	
																	
																</div>

																<div class="row">
														<?php 
														// dd($coupon);
														$range_check = $days_check = "";
														$range_css = $days_css = "";
														$range_valid_from = $range_valid_to = $days_val = "";
														 if($coupon['validity_type']=="range") {
														 	$range_check = "checked";
														 	$range_valid_from = $coupon['valid_from'];
														 	$range_valid_to = $coupon['valid_to'];
														 	$range_css = "display:block;";
														 	$days_css = "display:none;";
														}else {
															$days_check = "checked";
															$days_val = $coupon['valid_days'];
															$days_css = "display:block;";
															$range_css = "display:none;";
														} ?>	

																	<div class="col">
																		<div class="form-group">
																			<label class="form-label">Validity Type </label> <div class="radio-opts">
																		
                          <input class="validity_type" id="validity_type" type="radio"  name="validity_type" value="range" {{ $range_check }} /> Date Range   &nbsp;&nbsp;
                          <input class="validity_type" id="validity_type" type="radio" name="validity_type" value="days" {{ $days_check }} />  Number of Days 
                        </div>
																		</div>
																	</div>

																	<div class="col">
																		<div class="form-group">
														<div  class="validity_type_options valid-range" style="{{ $range_css }}">
															<label class="form-label" for="valid_from" >Validity Range <span class="text-red">*</span></label>
															<div class="row">
															<div class="col">
																	<div id="valid_from"  class="datepicker input-group date"
															data-date-format="yyyy-mm-dd">
															<input class="form-control" name="valid_from" value="{{ $range_valid_from }}" type="text" readonly  onchange="date_check()" />
															<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
															</div>
															</div>
															<div class="col">
																	<div id="valid_to" class="datepicker input-group date"
															data-date-format="yyyy-mm-dd">
															<input class="form-control"  name="valid_to" value="{{ $range_valid_to }}" type="text" readonly  onchange="date_check()" />
															<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
															</div>
															</div>
														</div>
														
													

														</div>

														<div  class="validity_type_options valid-days" style="{{ $days_css }}">
														<label class="form-label" for="valid_days">Validity Days <span class="text-red">*</span></label>
													
														<input type="Number" min="0" name="valid_days" value="{{ $days_val }}" id="valid_days" class="form-control"  />

														</div>
																			

																		</div>
																		@error('valid_days','valid_from','valid_to')
																	<p style="color: red" class="error">{{ $message }}</p>
																	@enderror
																	</div>
																	
																	
																	
																</div>

																<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label class="form-label">Status <span class="text-red">*</span></label>
																	
																			{!! Form::select('is_active', array('1' => 'Active', '0' => 'Inactive'),$coupon['is_active'],['class' => 'form-control','required','id'=>'coupon_status']); !!}
																		</div>
																		@error('is_active')
																	<p style="color: red" class="error">{{ $message }}</p>
																	@enderror
																	</div>
																</div>

																
															</div>
														</div>
														
														<div class="row">
															<div class="col d-flex justify-content-end">
																<a href="{{url('/admin/coupons')}}"  class="mr-2 btn btn-secondary" >Cancel</a>
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

$("#couponForm").validate({
rules: {

coupon_desc : {
required: true
},

purchase_number: {
required: '#purchase_type[value="number"]:checked',
number: true,
min: 1
},
purchase_amount: {
required: '#purchase_type[value="amount"]:checked',
number: true,
min: 1
},
ofr_min_amount : {
required: true,
min: 1
},
ofr_value : {
required: true,
min: 1
},

valid_from: {
required: '#validity_type[value="range"]:checked',
},
valid_to: {
required: '#validity_type[value="range"]:checked',
},
valid_days: {
required: '#validity_type[value="days"]:checked',
min: 1
},

},

messages : {
coupon_title: {
required: "Coupon Title is required."
},
coupon_desc: {
required: "Coupon Description is required."
},
purchase_number: {
required: "Purchase Number is required.",
min: "Purchase Number must be greater than 0"
},
purchase_amount: {
required: "Purchase Amount is required.",
min: "Purchase Amount must be greater than 0"
},
ofr_min_amount: {
required: "Minimum Order Amount is required.",
min: "Minimum Order Amount must be greater than 0"
},
ofr_value: {
required: "Offer Value is required.",
min: "Offer Value must be greater than 0"
},
ofr_code: {
required: "Offer Code is required.",
maxlength:"Offer Code must be 6 characters",
minlength:"Offer Code must be 6 characters",
},
valid_from: {
required: "Validity From Date is required."
},
valid_to: {
required: "Validity To Date is required."
},

valid_days: {
required: "Validity Days is required.",
min: "Validity Days must be greater than 0"
},

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



	 $(".datepicker").datepicker({ 
        autoclose: true, 
        todayHighlight: true,
       startDate: new Date()
  }).datepicker();




	// Prompt
	$(".deletemodule").on("click", function(e){

		var moduleid = jQuery(this).parents("tr").find("#moduleid").data("value");
		$('body').removeClass('timer-alert');
		swal({
			title: "Delete Confirmation",
			text: "Are you sure you want to delete this module?",
			// type: "input",
			showCancelButton: true,
			closeOnConfirm: true,
			confirmButtonText: 'Yes'
		},function(inputValue){



			if (inputValue == true) {
			 $.ajax({
            type: "POST",
            url: '{{url("/admin/modules/delete")}}',
            data: { "_token": "{{csrf_token()}}", id: moduleid},
            success: function (data) {
            	// alert(data);
            	if(data ==1){
            		location.reload();
            	}
            
            }
        });

			}
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


var instance = $('#sub-category-drop').comboTree({
collapse:true,
cascadeSelect:true,
isMultiple: false
});
loadsubcat('1');
var selectionIdList = new Array($("#sub-category-id").val());
instance.setSelection(selectionIdList);
 function loadsubcat(clear='',selected='')
    {
        var category_id=$("#category_id").val();
        // alert(category_id);
        if(clear!='1')
        {
            $("#sub-category-id").val('');
        }
        
         $.ajax({
            type: "POST",
            url: '{{url("/admin/tags/subcategory")}}',
            data: { "_token": "{{csrf_token()}}", category_id: category_id},
            success: function (data) {
            	var obj = JSON.parse(data);
            
            	console.log(obj);
            	 var obj = JSON.parse(data);
            if(obj.subdata.length >=1)
            {
               $('#sub-category-drop').attr("placeholder", "Select subcategory"); 
            }
            else
            {
                $('#sub-category-drop').attr("placeholder", "No subcategory to display"); 
            }
            instance.setSource(obj.subdata);
            if($("#sub-category-id").val())
            {
                var selectionIdList = new Array($("#sub-category-id").val());
                instance.setSelection(selectionIdList);

            }
            
            }
        });
        
        
        
    }
    $('#sub-category-drop').on('change',function()
        {
            if(instance.getSelectedIds())
            {
                $("#sub-category-id").val(instance.getSelectedIds()[0]);
            }
        });

function couponCode(length) {
    var chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOP1234567890";
    var cpn = "";
    for (var x = 0; x < length; x++) {
        var i = Math.floor(Math.random() * chars.length);
        cpn += chars.charAt(i);
    }
    $("#ofr_code").val(cpn);
}

$(document).ready(function(){


$(".purchase_type").click(function(){
$(".purchase_type_options").hide('1000');    
$(".purchase-"+$(this).val()).show('1000');
});

$(".validity_type").click(function(){
$(".validity_type_options").hide('1000');    
$(".valid-"+$(this).val()).show('1000');
});


});

 function date_check() 
    {
      var sdate=$("[name='valid_from']").val();
      var tdate=$("[name='valid_to']").val();
      
      $('#valid_from').datepicker('setStartDate',new Date(sdate));
      if(sdate && tdate)
      {
        var d1 = Date.parse(sdate);
        var d2 = Date.parse(tdate);
        if (d1 > d2) 
        {
          $("[name='valid_to']").val(sdate);
          $('#valid_to').datepicker('setStartDate',new Date(sdate));
        }
      }
      
    }
</script>
@endsection