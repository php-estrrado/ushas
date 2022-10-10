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
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Blog Management</a></li>
									
									<li class="breadcrumb-item active" aria-current="page"><a href="#">{{ $title }}</a></li>
								</ol>
							</div>
							<div class="page-rightheader" style="display:flex; flex-direction: row; justify-content: center; align-items: center">
								<div class="btn btn-list">
									<a href="{{ url('/admin/blog/newtag') }}"   class="btn btn-primary addmodule"><i class="fe fe-plus mr-1"></i> Add New</a>
								</div>
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
													<div class="table-responsive table-lg mt-3">
														<table class="table table-bordered border-top text-nowrap blogtaglist" id="blogtaglist">
															<thead>
																<tr>
																	<th class="align-top border-bottom-0 wd-5 userroles">Select</th>
																	<th class="border-bottom-0 w-20">Name</th>
																	<th class="border-bottom-0 w-30">Status</th>
																	<th class="border-bottom-0 w-15">Created At</th>						
																	<th class="border-bottom-0 w-10 userroles">Actions</th>
																</tr>
															</thead>

															<tbody>
																@if($blog_tags && count($blog_tags) > 0) @php $n= 0; @endphp
																	@foreach($blog_tags as $row) @php $n++; @endphp
																		<!-- @php if($row->is_active == 1){ $active = "Active"; $checked = 'checked'; }else if ($row->is_active == 0){ $active = "Inactive"; $checked = ""; } @endphp -->
																		<tr>
																			<td class="align-middle select-checkbox">
																				<span class=""></span>
																			</td>
																			<td>
																				<h6 class="mb-0 font-weight-bold">{{ucfirst($row->bt_name)}}</h6>
																			</td>
																			<td class="text-nowrap align-middle" data-search="@if($row->is_active==1){{ 'Published' }}@else{{ 'Draft' }}@endif">
																				<div class="switch">
																					<input class="switch-input status-btn ser_status" data-selid="{{$row->bt_id}}" id="status-{{$row->bt_id}}"  data-id="{{ $row->bt_id }}" name="status" type="checkbox"  @if($row->is_active==1) {{ "checked" }} @endif >
																					<label class="switch-paddle" for="status-{{$row->bt_id}}">
																						<span class="switch-active" aria-hidden="true">Published</span>
																						<span class="switch-inactive" aria-hidden="true">Draft</span>
																					</label>
																				</div>
																			</td>
																			<td class="text-nowrap align-middle">
																				<span>{{date('d M Y',strtotime($row->created_at))}}</span>
																			</td>
																			<td class="align-middle">
																				<div class="btn-group align-top">
																					<a href="{{ url('admin/blog/edittag/'.$row->bt_id) }}"   class="btn btn-sm btn-info mr-2"><i class="fe fe-edit mr-1"></i> Edit</a>
																					<button  class="btn btn-sm btn-secondary deletetag" type="button" onclick="delete_tag(<?php echo$row->bt_id;?>)"><i class="fe fe-trash-2"></i>Delete</button>
																				</div>
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
            
<style type="text/css">
table.dataTable tr.parent {
animation: none !important;
}
table.dataTable tr.selected p {
color: #fff;
}


</style>

@endsection
@section('js')
		
			<script src="{{URL::asset('admin/assets/js/datatable/tables/blogtag-datatable.js')}}"></script>
	<!-- INTERNAL Popover js -->
		<script src="{{URL::asset('admin/assets/js/popover.js')}}"></script>

		<!-- INTERNAL Sweet alert js -->
		<script src="{{URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/sweet-alert.js')}}"></script>
		<script type="text/javascript">


			$(document).ready(function(){
					@if(Session::has('message'))
						@if(session('message')['type'] =="success")
					
							toastr.success("{{session('message')['text']}}"); 
						@else
							toastr.error("{{session('message')['text']}}"); 
						@endif
					@endif
					
			});
   

			function delete_tag(cpnid){
				$('body').removeClass('timer-alert');
					swal({
						title: "Delete Confirmation",
						text: "Are you sure you want to delete this Tag?",
						showCancelButton: true,
						closeOnConfirm: true,
						confirmButtonText: 'Yes'
					},function(inputValue){
						if (inputValue == true) {
								$.ajax({
								type: "POST",
								url: '{{url("/admin/blog/tagdelete")}}',
								data: { "_token": "{{csrf_token()}}", btag_id: cpnid},
								success: function (data) {
										location.reload();
								}
							});
						}
					});
			}

			jQuery(document).ready(function(){
				$(".ser_status").on("click", function(e){
						var selid = jQuery(this).data("selid");
						var sestatus='0';
						if($(this).prop('checked') == true)
						{
						sestatus='1';
						}
						
						$.ajax({
							type: "POST",
							url: '{{url("/admin/blog/tagstatus")}}',
							data: { "_token": "{{csrf_token()}}", btag_id: selid,status:sestatus},
							success: function (data) {
								// alert(data);
								if(data) {
									if(sestatus ==1) {
										jQuery('#status-'+selid).closest("td").attr("data-search","Active");
									toastr.success("Blog Tag Published Successfully.");   
									}else {
										jQuery('#status-'+selid).closest("td").attr("data-search","Inactive");
									toastr.success("Blog Tag Status updated successfully.");  
									}
									var table = $.fn.dataTable.tables( { api: true } );
									table.rows().invalidate().draw();
								
								}else {
								toastr.error("Failed to update status."); 	
								}
							}
						});
				});
			});
		</script>


@endsection