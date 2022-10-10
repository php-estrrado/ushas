@extends('layouts.admin')
@section('css')
		<!-- INTERNAl Data table css -->
		<link href="{{URL::asset('admin/assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}"  rel="stylesheet">
		<link href="{{URL::asset('admin/assets/plugins/datatable/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />
@endsection
@section('page-header')
						<!--Page header-->


						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0">{{ $title }}</h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Master Settings</a></li>
									<li class="breadcrumb-item " aria-current="page"><a href="{{url('/admin/brands')}}">Brands</a></li>
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
														
											{{ Form::open(array('url' => "", 'id' => 'RewardForm', 'name' => 'RewardForm', 'class' => '','files'=>'true')) }}

												<input type="hidden" name="id" value="{{$reward->id}}">
												<input type="hidden" name="product" value="{{$reward->product_id}}">
												
												
														<div class="row">
															<div class="col">


																
																<div class="row">
																	<div class="col-md-12">
																		<div class="form-group">
																			<label>Product</label>
																			<input type="text"  class="form-control"  value="{{$reward->name}}" readonly>
																		
																		</div>
																		<span class="error"></span>
																	</div>
																	<div class="col-md-12">
																		<div class="form-group">
																			<label>Required Points</label>
																			<input type="text"  class="form-control" name="points_required" value="{{$reward->required_points}}">
																		</div>
																		<span class="error"></span>
																	</div>
																	<div class="col-md-12">
																		<div class="form-group">
																			<label>Quantity</label>
																			<input type="text"  class="form-control" name="quantity" value="{{$reward->quantity}}">
																		</div>
																		<span class="error"></span>
																	</div>
																	
																</div>
																
																
																
															</div>
														</div>
														
														<div class="row" style="margin-top: 30px;">
															<div class="col d-flex justify-content-end">
															     <a href="{{url('admin/loyalty-rewards')}}"  class="mr-2 btn btn-secondary" >Cancel</a>  
															<button class="btn btn-primary" type="submit" id="saveBtn">Save </button>
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
		<script src="{{URL::asset('admin/assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/datatable/js/jszip.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/datatable/responsive.bootstrap4.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/datatables.js')}}"></script>
	<!-- INTERNAL Popover js -->
		<script src="{{URL::asset('admin/assets/js/popover.js')}}"></script>

		<!-- INTERNAL Sweet alert js -->
		<script src="{{URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/sweet-alert.js')}}"></script>
<script type="text/javascript">
	$(document).ready(function(){
$('body').on('submit','#RewardForm',function(e){ 
	e.preventDefault(); 
	           
        var formData = new FormData(this);
        $('#RewardForm #saveBtn').attr('disabled',true);
        $('#RewardForm #saveBtn').text('Validating...'); 
        $request= $.ajax({
            type: "POST",
            url: '{{url("admin/loyalty-reward/validate")}}',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
            if(data == 'success'){ 
                $('#RewardForm #saveBtn').text('Saving...');
                $('#RewardForm #saveBtn').attr('disabled',true);
                submitRewardForm(formData,'RewardForm','saveBtn'); return false;
                return false;
            }else {
                var errKey = ''; var n = 0;
                $.each(data, function(key,value) { if(n == 0){ errKey = key; n++; }
					if(key == "tp_image") { $(".image.error").html(value); }else{
                        $('#RewardForm #'+key).parents('.form-group').find('.error').html(value);
                    }
				});
				$('#RewardForm #'+errKey).focus();
                $('#RewardForm #saveBtn').attr('disabled',false);
                $('#RewardForm #saveBtn').text('Save'); return false;
                
                    return false;
            }
			}
        });
	
		return false; 
    });
});
	
	function submitRewardForm(postValues,form,button){
		// alert();
        $.ajax({
            type: "POST",
            url: '{{url("admin/loyalty-reward/save")}}',
            data: postValues,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) { 
              $('#RewardForm #saveBtn').attr('disabled',false);
              $('#RewardForm #saveBtn').text('Save');
			 
				if(data == 1){
                var msg = 'Reward added successfully'; 
                toastr.success(msg);
				}else if(data == 2){
                var msg = 'Reward updated Successfully!'; 
                toastr.success(msg);
				}
				else{
                var msg = 'Something Went Wrong!'; 
                toastr.error(msg);
				}
				window.location.replace(base_path+"/admin/loyalty-rewards");
            } 
        });
    }
</script>



@endsection