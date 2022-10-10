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
		<link href="{{URL::asset('admin/assets/css/datepicker.css')}}" rel="stylesheet" />
@endsection
@section('page-header')
						<!--Page header-->


						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0">{{ $title }}</h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Ecom Benefits</a></li>
										<li class="breadcrumb-item"><a href="{{ url('admin/discounts')}}"><i class="fe fe-grid mr-2 fs-14"></i>Offers</a></li>
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
													{{ Form::open(array('url' => "/admin/discounts/save", 'id' => 'offerForm', 'name' => 'offerForm', 'class' => '','files'=>'true')) }}
												{{Form::hidden('id',0,['id'=>'id'])}} 
												<div class="py-1">
													
														<div class="row">
															<div class="col">
																
																
																	<div class="tab-pane active " id="tab1">
																	<div class="card-header mb-4"><div class="card-title">Discount Details</div></div>
																	
																	<!--seller-->
																	<!-- <div class="col-lg-6 fl">
																	<div class="form-group">
																	{{Form::label('sell_id','Seller',['class'=>''])}} <span class="text-red">*</span>
																	@if($sellers && count($sellers) > 0) 
																	{{Form::select('sell_id',$sellers,'',['id'=>'sell_id','class'=>'form-control', 'placeholder'=>'Select Seller','size'])}}
																	@endif
																	<span class="error"></span>
																	</div>
																	</div> -->
																	

																	
																	<div class="col-lg-12 fr">
																	<div class="form-group">
																	{{Form::label('prd_id','Product',['class'=>''])}} <span class="text-red">*</span>
																	@if($products && count($products) > 0) 
																	{{Form::select('prd_id',$products,'',['id'=>'prd_id','class'=>'form-control', 'placeholder'=>'Select Product','size'])}}
																	@endif
																	<span class="error"></span>
																	</div>
																	</div>
																	<div class="clearfix"></div>
																	<div class="col-lg-6 fl">
																	<div class="form-group">
																	{{Form::label('discount_value','Discount Value',['class'=>''])}} <span class="text-red">*</span>
																	{{Form::number('discount_value','',['id'=>'discount_value','class'=>'form-control admin', 'placeholder'=>'Discount Value','max'=>9999])}}
																
																	<span class="error"></span>
																	</div>
																	</div>


																	<div  class="col-lg-6  fl">
																	<div class="form-group">
																	{{Form::label('discount_type','Discount Type',['class'=>''])}} <span class="text-red">*</span>
																	@php $disc_type = array('percentage'=>'Percentage','amount'=>'Amount'); @endphp
																	{{Form::select('discount_type',$disc_type,'',['id'=>'discount_type','class'=>'form-control', 'placeholder'=>'Discount Type'])}}
																	<span class="error"></span>
																	</div>
																	</div>
																	<div class="clearfix"></div>
																	<div  class="col-lg-6 fl">
																	<div class="form-group">

																	{{Form::label('quantity_limit','Product Quantity Limit',['class'=>''])}} <span class="text-red">*</span>
																	{{Form::number('quantity_limit','',['id'=>'quantity_limit','class'=>'form-control admin', 'placeholder'=>'Product Quantity Limit','max'=>9999])}}
																	<span class="error"></span>
																	</div>
																	</div>
																	<div class="col-lg-6 fl">
																	<div class="form-group">
																	<label class="form-label" for="valid_from" >Valid From <span class="text-red">*</span></label>
																	<div id="valid_from"  class="datepicker input-group date"
																	data-date-format="yyyy-mm-dd">
																	<input class="form-control" name="valid_from" type="text" readonly  onchange="date_check()" />
																	<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
																	</div>
																	<span class="error"></span>
																	</div>
																	</div>
																	<div class="clearfix"></div>
																	<div class="col-lg-6 fl">
																	<div class="form-group">

																	<label class="form-label" for="valid_from" >Valid To <span class="text-red">*</span></label>
																	<div id="valid_to" class="datepicker input-group date"
																	data-date-format="yyyy-mm-dd">
																	<input class="form-control"  name="valid_to" type="text" readonly  onchange="date_check()" />
																	<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
																	</div>
																	<span class="error"></span>
																	</div>
																	</div>
																	<div class="col-lg-6 fl">
																	<div class="form-group">
																	{{Form::label('is_active','Status',['class'=>''])}} <span class="text-red">*</span>
																	@php $status = array('1'=>'Active','0'=>'Inactive'); @endphp
																	{{Form::select('is_active',$status,1,['id'=>'is_active','class'=>'form-control', 'placeholder'=>'Status'])}}
																	<span class="error"></span>
																	</div>
																	</div>

																	</div>
																
															</div>
														</div>
														
														<div class="row">
															<div class="col d-flex justify-content-end">
																<a href="{{url('/admin/discounts')}}"  class="mr-2 btn btn-secondary" >Cancel</a>
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
<style type="text/css">
	.radio-opts {
		margin-top: 10px;
	}
</style>

@endsection
@section('js')
		<!-- INTERNAl Data tables -->
<script src="{{URL::asset('admin/assets/js/jquery.validate.min.js')}}"></script>

		<script src="{{URL::asset('admin/assets/js/datatables.js')}}"></script>
	<!-- INTERNAL Popover js -->
		<script src="{{URL::asset('admin/assets/js/popover.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/bootstrap-datepicker.js')}}"></script>
		<!-- INTERNAL Sweet alert js -->
		<script src="{{URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/sweet-alert.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/sweet-alert.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/comboTreePlugin.js')}}"></script>
<script type="text/javascript">
	jQuery(document).ready(function(){




	 $(".datepicker").datepicker({ 
        autoclose: true, 
        todayHighlight: true,
       startDate: new Date()
  }).datepicker('update', new Date());




	

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



$("#offerForm").validate({
rules: {

sell_id : {
required: true
},
prd_id : {
required: true
},

discount_value : {
required: true,
number: true,
min: 1
},

discount_type: {
required: true,
},
quantity_limit: {
required: true,
number: true,
min: 1
},

valid_from: {
required: true,
},
valid_to: {
required: true,
},
is_active: {
required: true,
},

},

messages : {

sell_id: {
required: "Seller is required."
},

prd_id: {
required: "Product is required."
},


discount_value: {
required: "Discount Value is required.",
min: "Discount Value must be greater than 0"
},
discount_type: {
required: "Discount Type is required."
},
quantity_limit: {
required: "Product Quantity Limit is required.",
min: "Product Quantity Limit must be greater than 0"
},
valid_from: {
required: "Validity From Date is required."
},
valid_to: {
required: "Validity To Date is required."
},

is_active: {
required: "Status field is required."
},

}
});


$("#sell_id").change(function(){

// alert($(this).val());
var sell_id = $(this).val();

	 $.ajax({
            type: "POST",
            url: '{{url("/admin/discounts/products")}}',
            data: { "_token": "{{csrf_token()}}", sell_id: sell_id},
            success: function (data) {
            	console.log(data);
            	$("#prd_id").html(data);

            	// if(data ==1){
            	// 	location.reload();
            	// }
            
            }

});

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