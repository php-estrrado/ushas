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
<style>
.btn-primary {
    color: #fff !important;
    background-color: #065e95 !important;
    border-color: #006fb4 !important;
}
.error {
    margin-top: 0.25rem;
    color: #fff;
}
</style>
						<!--Page header-->


						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0">{{ $title }}</h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Master Settings</a></li>
									
									<li class="breadcrumb-item active" aria-current="page"><a href="#">{{ $title }}</a></li>
								</ol>
							</div>
											
						</div>
                        <!--End Page header-->
@endsection
@section('content')
						<div class="card custom-card">
									<div class="card-body">
										<div class="main-profile-contact-list d-lg-flex">
											<div class="media mr-4">
													<div class="media-icon bg-primary text-white  mr-3 mt-1">
														<i class="fa fa-user"></i>
													</div>
													<div class="media-body">
														<small class="text-muted">Customer Name</small>
														<div class="font-weight-normal1">
															{{$customer->first_name}} 
														</div>
													</div>
												</div>
												<div class="media mr-4">
													<div class="media-icon bg-primary text-white  mr-3 mt-1">
														<i class="las la-hand-holding-usd fs-18"></i>
													</div>
													<div class="media-body">
														<small class="text-muted">Points Balance</small>

														<div class="font-weight-normal1">
														{{ $balance }}
														</div>
													</div>
												</div>
												<div class="media mr-4">
													<div class="media-icon bg-primary text-white  mr-3 mt-1">
														<i class="las la-hand-holding-usd fs-18"></i>
													</div>
													<div class="media-body">
														<small class="text-muted">Total Points</small>
														<div class="font-weight-normal1">
															{{$total}}
														</div>
													</div>
												</div>
											
										</div>
									</div>
								</div>
						
								<div class="row flex-lg-nowrap">
									<div class="col-12 mb-3">
										<div class="e-panel card">
											<div class="card-body">
												<div class="e-table">
													<div class="table-responsive table-lg mt-3">
														<table class="table table-bordered border-top text-nowrap loyalty_log_list" id="loyalty_log_list" style="width: 100%">
															<thead>
																<tr>
																	<th class="border-bottom-0 w-15">DATE</th>
																	<th class="border-bottom-0 w-15"> ORDER ID</th>
																	<th class="border-bottom-0 w-15">CREDIT</th>
																	<th class="border-bottom-0 w-15">DEBIT</th>
																	
																</tr>
																
															</thead>

															<tbody>
															@if($log)
																@foreach($log as $row)
																
																<tr>
																<?php $newDate = date("d M Y h:i:s", strtotime($row->created_at)); ?>
																	<td> {{ $newDate }}</td>
																	<td> <?php if($row->sales_id) { echo $row->saleorder->order_id; } else { echo "---"; } ?></td>
																	<td> <?php if($row->credit) { echo $row->credit ; } else { echo "---"; } ?></td>
																	<td> <?php if($row->debit) { echo $row->debit ; } else { echo "---"; } ?></td>
																
																
																</tr>
																@endforeach
																@endif
															</tbody>

														</table>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- End Row -->


						<!-- User Form Modal -->
								

					</div>
				</div><!-- end app-content-->
            </div>
@endsection
@section('js')
		<!-- INTERNAl Data tables -->
		<!--<script src="{{URL::asset('admin/assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>-->
		<!--<script src="{{URL::asset('admin/assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>-->
		<!--<script src="{{URL::asset('admin/assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>-->
		<!--<script src="{{URL::asset('admin/assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>-->
		<!--<script src="{{URL::asset('admin/assets/plugins/datatable/js/jszip.min.js')}}"></script>-->
		<!--<script src="{{URL::asset('admin/assets/plugins/datatable/js/pdfmake.min.js')}}"></script>-->
		<!--<script src="{{URL::asset('admin/assets/plugins/datatable/js/vfs_fonts.js')}}"></script>-->
		<!--<script src="{{URL::asset('admin/assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>-->
		<!--<script src="{{URL::asset('admin/assets/plugins/datatable/js/buttons.print.min.js')}}"></script>-->
		<!--<script src="{{URL::asset('admin/assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>-->
		<!--<script src="{{URL::asset('admin/assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>-->
		<!--<script src="{{URL::asset('admin/assets/plugins/datatable/responsive.bootstrap4.min.js')}}"></script>-->
		<!--<script src="{{URL::asset('admin/assets/js/datatables.js')}}"></script>-->
		<script src="{{URL::asset('admin/assets/js/datatable/tables/loyalty_rewards-datatable.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/datatable/tables/loyalty_log_list-datatable.js')}}"></script>
	<!-- INTERNAL Popover js -->
		<script src="{{URL::asset('admin/assets/js/popover.js')}}"></script>

		<!-- INTERNAL Sweet alert js -->
		<script src="{{URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/sweet-alert.js')}}"></script>

<script type="text/javascript">
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } }); 

</script>
<script type="text/javascript">
	
var base_path = "{{url('/')}}";
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
                var msg = 'Label updated Successfully!'; 
                toastr.success(msg);
				}
				else{
                var msg = 'Reward add failed!'; 
                toastr.error(msg);
				}
				window.location.replace(base_path+"/admin/loyalty-rewards");
            } 
        });
    }
var asset_path = "{{asset('/')}}";
var tablePath = asset_path+"{{'admin/assets/js/datatable/tables/org/'}}"

function deleteReward(id) {
    swal({
        title: "Delete Confirmation",
        text: "Are you sure you want to delete this Reward?",
        // type: "input",
        showCancelButton: true,
        closeOnConfirm: true,
        confirmButtonText: 'Yes'
        },function(inputValue){
		if (inputValue == true) { 
		$.ajax({
                type: "POST",
                url: '{{ url("admin/loyalty-reward/delete") }}',
                data: {id:id,'_token': '{{ csrf_token()}}'},
                success: function (data) {
                    if(data ==1){
						toastr.success("Reward deleted successfully."); 
						}else{
						 toastr.error("Failed to deleted.");   
						}

				$(".loyalty_reward_list").DataTable().ajax.url("{{url('admin/loyalty-rewards')}}").load();
	
                }
            });
    
		}
    });
}



</script>



@endsection