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
		<link href="{{URL::asset('admin/assets/css/chosen.min.css')}}" rel="stylesheet"/>
		<link rel="stylesheet" type="text/css" href="{{URL::asset('admin/assets/css/bootstrap-datetimepicker.min.css')}}">
@endsection
@section('page-header')
						<!--Page header-->


						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0">{{ $title }}</h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Product Management</a></li>
									<li class="breadcrumb-item"><a href="{{ url('admin/shocking-sales')}}"><i class="fe fe-grid mr-2 fs-14"></i>Shocking Sale</a></li>
									
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
													{{ Form::open(array('url' => "/admin/shocking-sales/save", 'id' => 'shocksaleForm', 'name' => 'shocksaleForm', 'class' => '','files'=>'true')) }}
												{{Form::hidden('id',$shockingsale['id'],['id'=>'saleid'])}}
												{{Form::hidden('title_cid',$shockingsale['title_cid'],['id'=>'title_cid'])}}
												<div class="py-1">
													
														<div class="row">
															<div class="col">
																<div class="row">

																	<div class="col">
																		<div class="form-group">
																			<label class="form-label">Select Language <span class="text-red">*</span></label>
                                                                <select class="form-control custom-select select2" name="glo_lang_cid" required>

@php
$def_lang =DB::table('glo_lang_lk')->where('is_active', 1)->first();
$content_table=DB::table('cms_content')->where('cnt_id', $shockingsale['title_cid'])->where('lang_id', $def_lang->id)->first();
if($content_table){ 
$lang_id = $content_table->lang_id;
}
@endphp                                                                    

                                                                     @foreach ($language as $lang)
                                                                    <option value="{{ $lang->id }}" @php if($lang_id==$lang->id){ echo "selected";} @endphp >{{ $lang->glo_lang_name }}</option>
                                                                    @endforeach
                                                                </select>
																		</div>
																		
																	</div>
																	<div class="col">
																		<div class="form-group">
																			<label class="form-label">Caption <span class="text-red">*</span></label>
																			
																			{!! Form::text('caption', $shockingsale['title'], ['class' => 'form-control','rows' => 3,'id'=>'caption']) !!}
																		</div>
																		@error('caption')
																	<p style="color: red" class="error">{{ $message }}</p>
																	@enderror
																		
																	</div>
																</div>
															

																<div class="row">
														
																	<div class="col">
																		<div class="form-group">
																			<label class="form-label">Start Date <span class="text-red">*</span></label>
<div class="input-group date" id="sale_start" data-target-input="nearest">
<input type="text" class="form-control datetimepicker-input" name="sale_start" data-target="#sale_start" value="{{ $shockingsale['start_time'] }}"  />
<div class="input-group-append" data-target="#sale_start" data-toggle="datetimepicker">
<div class="input-group-text"><i class="fa fa-calendar"></i></div>
</div>
</div>
																		</div>
																		<p style="color: red" id="errNm3"></p>
																		@error('sale_start')
																	<p style="color: red" class="error">{{ $message }}</p>
																	@enderror
																		
																	</div>

																	<div class="col">
																		<div class="form-group">
																			<label class="form-label">End Date <span class="text-red">*</span></label>
																			
																			<div class="input-group date" id="sale_end" data-target-input="nearest">
<input type="text" class="form-control datetimepicker-input" name="sale_end" data-target="#sale_end" value="{{ $shockingsale['end_time'] }}"  />
<div class="input-group-append" data-target="#sale_end" data-toggle="datetimepicker">
<div class="input-group-text"><i class="fa fa-calendar"></i></div>
</div>
</div>
																		</div>
																		<p style="color: red" id="errNm2"></p>
																		@error('sale_end')
																	<p style="color: red" class="error">{{ $message }}</p>
																	@enderror
																		
																	</div>
																</div>
																<div class="row">
															<div class="col">
															<div class="form-group">
															<label class="form-label">Offer Value <span class="text-red">*</span></label>

															{!! Form::text('offer_value', $shockingsale['discount_value'] , ['class' => 'form-control','rows' => 3,'id'=>'offer_value']) !!}
															</div>
															</div>
															<div class="col">
															<div class="form-group">
															<label class="form-label">Offer Type </label>

															<select name="ofr_type" id="ofr_type" class="form-control">
																<option value="percentage" <?php if($shockingsale['discount_type']=="percentage"){ echo "selected";}?> >Percentage</option>
																<option value="amount" <?php if($shockingsale['discount_type']=="amount"){ echo "selected";}?>>Amount</option>
																
															</select>
															</div>
															</div>
															

															</div>
																
																<div class="row">
																	
																	
																	<div class="col-md-6 col-md-offset-6">
																		<div class="form-group">
																			<label class="form-label">Status <span class="text-red">*</span></label>
																	
																			{!! Form::select('is_active', array('1' => 'Active', '0' => 'Inactive'), $shockingsale['is_active'] ,['class' => 'form-control','required','id'=>'coupon_status']); !!}
																		</div>
																		@error('is_active')
																	<p style="color: red" class="error">{{ $message }}</p>
																	@enderror
																	</div>
																	
																	<div class="col-md-6">
			 <div class="form-group ">
            {{Form::label('training_levels','Training Level',['class'=>''])}} <span class="text-red">*</span>
			 {{Form::select('prd_id[]',$products,$shockingproducts,['id'=>'training_levels','class'=>'form-control chosen-select myselect ', 'multiple'=>'multiple'])}}
			
			

		   <span class="error"></span>
        </div>
																	
																	
																</div>
																
																

																
															</div>
														
														
														<div class="row">
															<div class="col d-flex justify-content-end">
																<a href="{{url('/admin/shocking-sales')}}"  class="mr-2 btn btn-secondary" >Cancel</a>
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

.chosen-container-multi .chosen-choices {
    padding: 4px 10px;
    border: 1px solid #e3e4e9;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    border-radius: 5px;
}

</style>

@endsection
@section('js')
		<!-- INTERNAl Data tables -->
<script src="{{URL::asset('admin/assets/js/jquery.validate.min.js')}}"></script>

<script src="{{URL::asset('admin/assets/js/chosen.jquery.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('admin/assets/js/moment.js')}}"></script>
<script src="{{URL::asset('admin/assets/js/bootstrap-datetimepicker.min.js')}}"></script>
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

  $(function () {      
      var sminDate = $('[name="sale_start"]').val();
      if(moment(sminDate) > moment()) {
          sminDate = moment();
      }
                $('#sale_start').datetimepicker({
    format: 'YYYY-MM-DD HH:mm',
  minDate: moment(sminDate),
useCurrent: false //Important! See issue #1075
});
                var eminDate = $('[name="sale_end"]').val();
     if(moment(sminDate) > moment()) {
          eminDate = moment();
      }
                $('#sale_end').datetimepicker({
                	format: 'YYYY-MM-DD HH:mm',
                	minDate: moment(eminDate),
                  useCurrent: false //Important! See issue #1075
                });
                
                $("#sale_start").on("dp.change", function (e) {
                	$("#sale_end").val('');
                  $('#sale_end').data("DateTimePicker").minDate(e.date);

                });      
                
                $("#sale_end").on("dp.change", function (e) {
                	$("#errNm2").empty();
                    // $('#sale_start').data("DateTimePicker").maxDate(e.date);
                });
            });


	jQuery(document).ready(function(){

jQuery.validator.addMethod("greaterStart", function (value, element, params) {

    return this.optional(element) || new Date(value) >= new Date($("["+params+"]").val());
},'End Date must be greater than start date.');

$("#frontval").click(function(){

$("#shocksaleForm").validate({
	ignore: [],
rules: {

caption : {
required: true
},
// "prd_id[]": {
// required: true
// },
sale_start : {
required: true
},

sale_end: {
required: true,
greaterStart: 'name="sale_start"'
},
offer_value : {
required: true,
min:1
},

is_active: {
required: true

},

},

messages : {
caption: {
required: "Caption is required."
},

// "prd_id[]": {
// required: "Product is required."
// },

sale_start: {
required: "Start Date is required."
},
sale_end: {
required: "End Date is required."

},
offer_value: {
required: "Offer Value is required."

},

is_active: {
required: "Status is required."
}

},


 errorPlacement: function(error, element) {
 	 $("#errNm1").empty();
 	 console.log($(error).text());
            if (element.attr("name") == "sale_end" ) {
                $("#errNm2").text($(error).text());
                
            }else if (element.attr("name") == "sale_start" ) {
                $("#errNm3").text($(error).text());
                
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
  }).datepicker('update', new Date());




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

  
  /*  $('#prd_id').on('change',function()
        {
               $("#errNm1").empty();
            
        });
*/
  


$(document).ready(function(){


$(".chosen-select").chosen({
  no_results_text: "Oops, nothing found!"
})


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