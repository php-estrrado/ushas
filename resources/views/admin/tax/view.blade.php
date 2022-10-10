@extends('layouts.admin')
@section('css')
		<!-- INTERNAl Data table css -->

		<link href="{{URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
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
		
												
														<div class="row">
															<div class="col">


																<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label class="form-label view">Language: </label>
@php
  $def_lang =DB::table('glo_lang_lk')->where('is_active', 1)->first();
        $content_table=DB::table('cms_content')->where('cnt_id', $tax['tax_name_cid'])->where('lang_id', $def_lang->id)->first();
        if($content_table){ 
        $lang_id = $content_table->lang_id;
    }
         @endphp

                                                                    <p class="view_value">
                                                                    @foreach ($language as $lang)
                                                                    @php if($lang_id==$lang->id){ echo $lang->glo_lang_name;} @endphp
                                                                    @endforeach
                                                                </p>
                                                                
																		</div>
																
																	</div>
																	
																</div>
																<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label class="form-label view">Tax Name: </label>
																			<p class="view_value">{{ $tax['tax_name'] }}</p>
																		
																		</div>
																		
																	</div>
																	
																</div>
																
																<div class="row">
																	<div class="col mb-3">
																		<div class="form-group">
																			<label class="form-label view">Tax Description: </label>
																			<p class="view_value">{{ $tax['tax_desc'] }}</p>
																			
																		</div>
																		
																	</div>
																</div>
																<div class="row">
															<div class="col">
															<div class="form-group">
															<label class="form-label view">Country: </label>

															<p class="view_value">
															@foreach ($countries as $country)
															 @if(($country->id == $tax['country_id'])) {{ $country->country_name }} @endif
															@endforeach
															</p>
															</div>
															
															</div>
															<div class="col">
															<div class="form-group">
															<label class="form-label view"> State: </label>
															<p class="view_value">
															@foreach ($states as $state)
															 @if(isset($tax['state_id'])) @if(($state->id == $tax['state_id'])) {{ $state->state_name }} @endif  @endif 
															@endforeach
															</p>
															</div>
														
															</div>
															</div>

															<div class="row">
															<div class="col">
															<div class="form-group">
															<label class="form-label view">Valid From: </label>
															<p class="view_value"> {{ $tax['valid_from'] }} </p>
															
															</div>

															</div>
															<div class="col">
															<div class="form-group">
															<label class="form-label view"">Valid To: </label>
															<p class="view_value">{{ $tax['valid_to'] }}</p>
														
															</div>

															</div>
														</div>
																<div class="row">
																	<div class="col">
															<div class="form-group">
															<label class="form-label view">Percentage: </label>
															
															<p class="view_value">{{ $tax['percentage'] }}</p>
														
															</div>

															</div>
																<div class="col">
																		<div class="form-group">
																			<label class="form-label view">Status: </label>
																	<p class="view_value">@if($tax['is_active'] ==1){{ "Active" }}@else{{ "Inactive" }}@endif</p>
																		
																		</div>
																	</div>
																</div>
															</div>
														</div>
														
														<div class="row" style="margin-top: 30px;">
															<div class="col d-flex justify-content-end">
															    <a href="{{url('/admin/tax')}}"  class="mr-2 btn btn-secondary" >Back</a>  
														
															</div>
														</div>
												

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
  }).datepicker();


 @if(isset($tax['state_id'])) loadCities({{ $tax['state_id'] }}); @else loadCities();  @endif



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


function loadCities(state='')
    {
        var country_id=$("#country").val();
        
        // if(clear!='1')
        // {
        //     $("#state_id").val('');
        // }
        
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
	if(state !="") {
		var selected_state = "";
		if(obj.subdata[key].id == state) { selected_state = "selected"; }else { selected_state=""; }
html += '<option value="' + obj.subdata[key].id + '" '+selected_state+' >' + obj.subdata[key].title + '</option>';
	}else {
		html += '<option value="' + obj.subdata[key].id + '">' + obj.subdata[key].title + '</option>';
	}

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


@endsection