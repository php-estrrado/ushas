@extends('layouts.admin')
@section('css')
		<!-- INTERNAl Data table css -->

		<link href="{{URL::asset('admin/assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />
		
		<link href="{{URL::asset('admin/assets/css/datepicker.css')}}" rel="stylesheet" />
@endsection
@section('page-header')
						<!--Page header-->


						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0">{{ $title }}</h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Master Settings</a></li>
									<li class="breadcrumb-item " aria-current="page"><a href="{{url('/admin/tax')}}">Tax</a></li>
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

							<!-- 	@if(Session::has('message'))

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
														
{{ Form::open(array('url' => "admin/tax/save", 'id' => 'taxForm', 'name' => 'taxForm', 'class' => '','files'=>'true')) }}
												{{Form::hidden('id',0,['id'=>'id'])}}
												
												
														<div class="row">
															<div class="col">


																<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label class="form-label">Select Language <span class="text-red">*</span></label>
                                                                <select class="form-control custom-select select2" name="glo_lang_cid" required>
                                                                    
                                                                    @foreach ($language as $lang)
                                                                    <option value="{{ $lang->id }}">{{ $lang->glo_lang_name }}</option>
                                                                    @endforeach
                                                                </select>
																		</div>
																	@error('glo_lang_cid')
																	<p style="color: red">{{ $message }}</p>
																	@enderror
																	</div>
																	
																</div>
																<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label class="form-label">Tax Name <span class="text-red">*</span></label>
																			
																			{!! Form::text('tax_name', null, ['class' => 'form-control','id'=>'tax_name']) !!}
																		</div>
																	@error('tax_name')
																	<p style="color: red">{{ $message }}</p>
																	@enderror
																	</div>
																	
																</div>
																
																<div class="row">
																	<div class="col mb-3">
																		<div class="form-group">
																			<label class="form-label">Tax Description <span class="text-red">*</span></label>
																			{!! Form::textarea('tax_desc', null, ['class' => 'form-control','id'=>'tax_desc', 'rows' => '3']) !!}
																		</div>
																	@error('tax_desc')
																	<p style="color: red">{{ $message }}</p>
																	@enderror
																	</div>
																</div>
															<div class="row">
															<div class="col">
															<div class="form-group">
															<label class="form-label">Select Country <span class="text-red">*</span></label>
															<select class="form-control custom-select select2" name="country" id="country" required onchange="loadCities();">
																<option value="">Select Country</option>
															@foreach ($countries as $country)
															<option value="{{ $country->id }}" @if($country->id == '132') {{ "selected" }} @endif>{{ $country->country_name }}</option>
															@endforeach
															</select>
															</div>
															@error('country')
															<p style="color: red">{{ $message }}</p>
															@enderror
															</div>
															<div class="col">
															<div class="form-group">
															<label class="form-label">Select State</label>
															<select class="form-control custom-select select2" name="state" id="state"  >
																<option value="">Select State</option>
															@foreach ($states as $state)
															<option value="{{ $state->id }}"  >{{ $state->state_name }}</option>
															@endforeach
															</select>
															</div>
															@error('state')
															<p style="color: red">{{ $message }}</p>
															@enderror
															</div>
															</div>

															
															


															<div class="row">
															<div class="col">
															<div class="form-group">
															<label class="form-label">Valid From<span class="text-red">*</span></label>
															<div id="valid_from"  class="datepicker input-group date"
															data-date-format="yyyy-mm-dd">
															<input class="form-control" name="valid_from" type="text" readonly onchange="date_check()" />
															<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
															</div>
															@error('valid_from')
															<p style="color: red">{{ $message }}</p>
															@enderror
															</div>

															</div>
															<div class="col">
															<div class="form-group">
															<label class="form-label">Valid To<span class="text-red">*</span></label>
															<div id="valid_to" class="datepicker input-group date"
															data-date-format="yyyy-mm-dd">
															<input class="form-control"  name="valid_to" type="text" readonly  onchange="date_check()" />
															<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
															</div>
															@error('valid_to')
															<p style="color: red">{{ $message }}</p>
															@enderror
															</div>

															</div>
														</div>
																	<div class="row">
																		<div class="col">
															<div class="form-group">
															<label class="form-label">Percentage<span class="text-red">*</span></label>
															
															<input type="number" step="0.01" min="0" max="100" name="percentage" class="form-control">
															@error('percentage')
															<p style="color: red">{{ $message }}</p>
															@enderror
															</div>

															</div>
																<div class="col">
																		<div class="form-group">
																			<label class="form-label">Status <span class="text-red">*</span></label>
																	
																			{!! Form::select('is_active', array('1' => 'Active', '0' => 'Inactive'), '1',['class' => 'form-control','required','id'=>'module_status']); !!}
																		</div>
																	</div>
																</div>
															</div>
														</div>
														
														<div class="row" style="margin-top: 30px;">
															<div class="col d-flex justify-content-end">
															    <a href="{{url('/admin/tax')}}"  class="mr-2 btn btn-secondary" >Cancel</a>  
															<button class="btn btn-primary" type="submit">Save</button>
															</div>
														</div>
													</form>

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
	<script src="{{URL::asset('admin/assets/js/bootstrap-datepicker.js')}}"></script>
	
	<!-- INTERNAL Popover js -->
		<script src="{{URL::asset('admin/assets/js/popover.js')}}"></script>

		<!-- INTERNAL Sweet alert js -->
		<script src="{{URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/sweet-alert.js')}}"></script>
<script type="text/javascript">
	jQuery(document).ready(function(){

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

loadCities();
	});

function loadCities(clear='')
    {
        var country_id=$("#country").val();
        
        if(clear!='1')
        {
            $("#state_id").val('');
        }
        
         $.ajax({
            type: "POST",
            url: '{{url("/admin/tax/states")}}',
            data: { "_token": "{{csrf_token()}}", country_id: country_id},
            success: function (data) {
            	var obj = JSON.parse(data);
            
            	console.log(obj);
            	 var obj = JSON.parse(data);
            
            if(obj.subdata.length >=1)
            {
               $('#state').attr("placeholder", "Select State"); 
               var html = '<option value="">Select State</option>';
for (var key in obj.subdata) {
html += '<option value="' + obj.subdata[key].id + '">' + obj.subdata[key].title + '</option>';
}

$('#state').html(html);
            }
            else
            {
            	$('#state').html('<option value="">Select State</option>');
                $('#state').val(""); 
            }

           
            
            }
        });
        
        
        
    }
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