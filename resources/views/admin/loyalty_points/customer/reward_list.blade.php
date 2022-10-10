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
														<table class="table table-bordered border-top text-nowrap loyalty_reward_list" id="loyalty_reward_list" style="width: 100%">
															<thead>
																<tr>
																	<th class="border-bottom-0 w-15">Reward Redeemed</th>
																	<th class="border-bottom-0 w-15"> Points Required</th>
																	<th class="border-bottom-0 w-15">Quantity</th>
																	<th class="border-bottom-0 w-15">Redemption Date</th>
																	<th class="border-bottom-0 w-15">Status</th>
																	
																</tr>
																
															</thead>

															<tbody>
															@if($rewards)
																@foreach($rewards as $row)
																
																<tr>
																<td> {{ $row->reward->rewardInfo->name }}</td>
																<td> {{ $row->reward->required_points }}</td>
																<td> {{ $row->redeemed_quantity }}</td>
																<?php $newDate = date("d M Y h:i:s", strtotime($row->redemption_date)); ?>
																<td> {{ $newDate }}</td>
																<td> 
																<select class="form-control" id="rewardStatus" >
																<option value="pending" data-value="{{$row->id}}" <?php if($row->status=="pending"){ echo "selected"; } ?>>pending</option>
																<option value="delivered" data-value="{{$row->id}}" <?php if($row->status=="delivered"){ echo "selected"; } ?> >delivered</option>
																</select>
																</td>
																
																
																
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

		<script src="{{URL::asset('admin/assets/js/datatable/tables/loyalty_reward_list-datatable.js')}}"></script>
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
var asset_path = "{{asset('/')}}";
var tablePath = asset_path+"{{'admin/assets/js/datatable/tables/org/'}}"


$('body').on('change','#rewardStatus',function(){
	var status=this.value;
	var id=$(this).find(':selected').attr('data-value');
	//alert(id);
	$.ajax({
                type: "POST",
                url: '{{ url("admin/loyalty-reward/status") }}',
                data: {id:id,status:status,'_token': '{{ csrf_token()}}'},
                success: function (data) {
                    if(data ==1){
						toastr.success("Reward status changed successfully."); 
						}else{
						 toastr.error("Failed to updated.");   
						}

			//	$(".loyalty_reward_list").DataTable().ajax.url("{{url('admin/loyalty-rewards')}}").load();
	
                }
            });
	
	
});
});
</script>



@endsection