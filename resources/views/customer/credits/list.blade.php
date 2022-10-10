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
								<h4 class="page-title mb-0">Customer Credits</h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Credits</a></li>

									<li class="breadcrumb-item active" aria-current="page"><a href="#">Customer Credits</a></li>
								</ol>
							</div>
							<div class="page-rightheader">
								<div class="btn btn-list">
									<!-- <a href="#" class="btn btn-info"><i class="fe fe-settings mr-1"></i> General Settings </a> -->
									<a href="{{ url('/admin/customer/credits/add/') }}" class="btn btn-primary addmodule"><i class="fe fe-plus mr-1"></i> Add New </a>
									
								</div>
							</div>
						</div>
                        <!--End Page header-->
@endsection
@section('content')
						<!-- Row -->
						<div class="row flex-lg-nowrap">
							<div class="col-12">

								{{-- @if(Session::has('message'))

								<div class="alert alert-{{session('message')['type']}}" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>{{session('message')['text']}}</div>
								@endif
								@if ($errors->any())
								@foreach ($errors->all() as $error)

								<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>{{$error}}</div>
								@endforeach
								@endif --}}

								<button class="btn btn-warning d-none" data-toggle="modal" id="showpayment" data-target="#purchase-modal" data-container=""><i class="fa fa-sort mr-1"></i>Payment</button>

								<div class="row flex-lg-nowrap">
									<div class="col-12 mb-3">
										<div class="e-panel card">
											<div class="card-body">
												<div class="e-table">
													<div class="table-responsive table-lg mt-3">
														<table class="customer-table table table-bordered border-top text-nowrap" id="customer-table">
															<thead>
																<tr>
																	<th class="align-top border-bottom-0 wd-5"></th>
																	<th class="border-bottom-0 w-15">Customer</th>
																	<th class="border-bottom-0 w-15">Credit Limit</th>
																	<th class="border-bottom-0 w-10">Validity</th>
																	<th class="border-bottom-0 w-15">Balance</th>
																	<th class="border-bottom-0 w-10">Outstanding</th>
																	<th class="border-bottom-0 w-30 notexport">Actions</th>
																</tr>
															</thead>

															<tbody>

																@if($credits && count($credits) > 0)
                    											@foreach($credits as $row)


																<tr>
																	<td class="align-middle select-checkbox" id="moduleid" data-value="{{$row->user_id}}">
																		<label class="custom-control custom-checkbox">
																		</label>
																	</td>
																	<?php $name=DB::table('usr_info')->where('user_id', $row->user_id)->first(); ?>
                                                                    <td class="align-middle" >
																				<h6 class=" font-weight-bold">{{$name->first_name." ".$name->middle_name." ".$name->last_name}}</h6>
                                                                        </div>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																				<h6 class=" font-weight-bold">@if($row->credit_limit>0){{$row->credit_limit}}@else{{"0"}}@endif</h6>
                                                                        </div>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																				<h6 class=" font-weight-bold">@if($row->credit_days>0){{$row->credit_days}}@else{{"0"}}@endif</h6>
                                                                        </div>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																				<h6 class=" font-weight-bold">@if($row->balance>0){{$row->balance}}@else{{"0"}}@endif</h6>
                                                                        </div>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																				<h6 class=" font-weight-bold">@if($row->outstanding>0){{$row->outstanding}}@else{{"0"}}@endif</h6>
                                                                        </div>
																	</td>
																	<td class="align-middle">
																		<div class="btn-group align-top">

																			<a href="{{ url('admin/customer/credits-log/') }}/{{$row->user_id}}" class="btn btn-sm btn-primary mr-2"><i class="fe fe-list mr-1"></i> Log</a>&nbsp;

																			<a data-cid="{{$row->user_id}}" class="mr-2 btn hover btn-sm add_payment btn-primary"><i class="fe fe-list mr-1"></i> Payment</a>&nbsp;

																			<a href="{{ url('admin/customer/credits-manage/') }}/{{$row->user_id}}" class="btn btn-sm btn-primary "><i class="fe fe-list mr-1"></i> Manage</a>&nbsp;

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


<div id="purchase-modal" class="modal fade">
<div class="modal-dialog modal-confirm">
    <div class="modal-content">
        
      	<div id="purchaseform">
      			
      	</div>
    </div>
</div>
</div>

@endsection
@section('js')
		<!-- INTERNAl Data tables -->
		<script src="{{URL::asset('admin/assets/js/datatable/tables/customer-table.js')}}"></script>
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


	$('body').on('click','.add_payment',function(e){ 

	var usr_id = $(this).data('cid');

	$.ajax(
	{
	    url: '{{url("admin/customer/credits-payment-form")}}',
	    data: { usr_id:usr_id,'_token': '{{ csrf_token()}}'},
	    type: "post",
	    datatype: "html"
	}).done(function(data){
	    // console.log(data);
	    $("#purchaseform").empty().html(data);
	    $("#showpayment").click();

	    // location.hash = page;
	}).fail(function(jqXHR, ajaxOptions, thrownError){
	      // alert('No response from server');
	});

	});


	function delete_cat(cat_id){
       // alert(cat_id);
       $('#del_modal').show();
       $('#ok_button').click(function(){
        $.ajax({
            type: "POST",
            url: '{{url("/admin/delete-subcategory/")}}',
            data: { "_token": "{{csrf_token()}}", cat_id: cat_id},
            success: function (data) {
                location.reload();

            }
        });
    });
    }

    function status_update($cat_id){
       alert($cat_id);
    }

    $(function() {
    $('.ser_status').change(function() {
        var status = $(this).prop('checked') == true ? 1 : 0;
        var cat_id = $(this).data('id');

        $.ajax({
            type: "POST",
            url: '{{url("/admin/category/change-status-subcategory")}}',
            data: { "_token": "{{csrf_token()}}", cat_id: cat_id,status: status},
            success: function (data) {
                console.log(data.success)

            }
        });
    })
  })
</script>

@endsection
