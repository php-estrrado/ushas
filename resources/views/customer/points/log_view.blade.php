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
								<h4 class="page-title mb-0">Customer Points Log</h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="{{ url('/admin/customer/points') }}"><i class="fe fe-grid mr-2 fs-14"></i>Points</a></li>
									<li class="breadcrumb-item active" aria-current="page"><a href="#">Customer Points Log</a></li>
								</ol>
							</div>
							<div class="page-rightheader">
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
															{{$customer->first_name}} {{$customer->middle_name}} {{$customer->last_name}}
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
														@if($points->balance > 0)	{{ ($points->balance) }}
														@else
															{{ 0 }}
														@endif
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
															{{$points->total}}
														</div>
													</div>
												</div>
										</div>
									</div>
								</div>
				<!-- Row-->
						<div class="row">
							<div class="col-md-12">
								<div class="card">
									<div class="card-header">
										<div class="card-title">Log</div>
									</div>
									<div class="card-body">
										<div class="e-table">
													<div class="table-responsive table-lg mt-3">
														<table id="wallet-table" class="wallet-table table table-striped table-bordered w-100 text-nowrap">
															<thead>
																<tr>
																	<th class="align-top border-bottom-0 wd-5"></th>
																	<th class="border-bottom-0 w-20">Date</th>
																	<th class="border-bottom-0 w-20">Sales ID</th>
																	<th class="border-bottom-0 w-20">Credit</th>
																	<th class="border-bottom-0 w-20">Debit</th>
																	
																</tr>
															</thead>

															<tbody>

																@if($transaction && count($transaction) > 0)
                    											@foreach($transaction as $row)


																<tr>
																	<td class="align-middle select-checkbox" data-value="{{$row->user_id}}">
																	<label class="custom-control custom-checkbox">
																		</label>
																	</td>
                                                                    <td class="align-middle" >
																		<h6 class=" font-weight-bold">
																			{{date('d M Y',strtotime($row->created_at))}}
																		</h6>
                                                                       
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																		<h6 class=" font-weight-bold">
																		 @if($row->sales_id){{$row->sales_id}} @else {{ "-" }} @endif
																		</h6>
                                                                        </div>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																		<h6 class=" font-weight-bold">
																			@if($row->credit>0)
																			{{$row->credit}}
																			@else
																			{{"-"}}
																			@endif
																		</h6>
                                                                        </div>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																		<h6 class=" font-weight-bold">
																			@if($row->debit>0)
																			{{$row->debit}}
																			@else
																			{{"-"}}
																			@endif
																		</h6>
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
									<div class="card-footer">
										<div class="row">
											<div class="col d-flex justify-content-end">
											<a href="{{route('customer.points')}}"  class="mr-2 btn btn-secondary" >Back</a>			
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- End row-->
				</div><!-- end app-content-->
            </div>
@endsection
@section('js')
<!-- INTERNAl Data tables -->
		<script src="{{URL::asset('admin/assets/js/datatable/tables/wallet_log-datatable.js')}}"></script>
	<script type="text/javascript">
    $(document).ready(function(){
        $('#customer_wallet').addClass("active");
          $('#a_c_w').addClass("active");
          $('#wallet').addClass("is-expanded");
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



	function delete_wallet(w_id){
	    
	   $('body').removeClass('timer-alert');
        swal({
            title: "Delete Confirmation",
            text: "Are you sure you want to delete this record?",
            // type: "input",
            showCancelButton: true,
            closeOnConfirm: true,
            confirmButtonText: 'Yes'
        },function(inputValue){
    if (inputValue == true) {
        $.ajax({
            type: "POST",
            url: '{{url("/admin/customer/wallet-delete/")}}',
            data: { "_token": "{{csrf_token()}}", w_id: w_id},
            success: function (data) {
                location.reload();

            }
        });
        }
    });
    }
</script>


@endsection