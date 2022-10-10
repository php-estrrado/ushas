@extends('layouts.admin')
@section('css')
		<!-- INTERNAl alert css -->
		<link href="{{URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />

        <!--INTERNAL Select2 css -->
		<link href="{{URL::asset('admin/assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />

        <!-- INTERNAL File Uploads css -->
		<link href="{{URL::asset('admin/assets/plugins/fancyuploder/fancy_fileupload.css')}}" rel="stylesheet" />
        <!-- INTERNAL File Uploads css-->
        <link href="{{URL::asset('admin/assets/plugins/fileupload/css/fileupload.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('page-header')
						<!--Page header-->
						      <div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0">{{ $title }}</h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="{{ url('/admin/customer') }}"><i class="fe fe-grid mr-2 fs-14"></i>Customer</a></li>

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
						<!-- Row-->
						<div class="row">
							<div class="col-md-12">
								<div class="card overflow-hidden">
									<div class="card-body">
										<div class="card-header mb-4""><div class="card-title">Sale Invoice</div></div>
										

										<div class="card-body pl-0 pr-0">
											<div class="row">
												<div class="col-sm-6">
													<span>Invoice No.</span><br>
													<strong>INV0000{{$order->id}}</strong>
												</div>
												<div class="col-sm-6 text-right">
													<span>Invoice Date</span><br>
													<strong>{{date('d M, Y H:i A',time())}}</strong><br>
													<span>Sale Date</span><br>
													<strong>{{date('d M, Y H:i A',time())}}</strong>
												</div>
											</div>
										</div>
										<div class="dropdown-divider"></div>
										<div class="row pt-4">
											<div class="col-lg-6 ">
												<p class="h5 font-weight-bold">Bill From</p>
													<address>
												@if(isset($seller_address->address))	{{ $seller_address->address }}<br> @endif
												@if(isset($seller_address->address2))	{{ $seller_address->address2 }} @endif,@if(isset($seller_address_city['city'])) {{ $seller_address_city['city'] }}<br> @endif
												@if(isset($seller_address_city['state']))	{{ $seller_address_city['state'] }}<br> @endif
												@if(isset($seller_address_city['country']))	{{ $seller_address_city['country'] }} @endif
												</address>
											</div>
											<div class="col-lg-6 text-right">
												<p class="h5 font-weight-bold">Bill To</p>
												<address>

													@if(! is_null($customer_mst->user_address($customer_mst->id,1)))
														@foreach($customer_mst->user_address($customer_mst->id,1) as $address)


																<span>{{ $address['address_1'] }}</span>
																<p class="addr_p">{{ $address['address_2'] }}</p>
																@if(! is_null($address['city_data']))
																<p class="addr_p">@if(isset($address['city_data']['city'])){{ $address['city_data']['city'] }}@endif</p>
																<p class="addr_p">@if(isset($address['city_data']['state'])){{ $address['city_data']['state'] }}@endif</p>
																<p class="addr_p">@if(isset($address['city_data']['country'])){{ $address['city_data']['country'] }}@endif</p>
																@endif

														@endforeach
														@endif


													
												</address>
											</div>
										</div>
										<div class="table-responsive push">

											<table class="table table-bordered table-hover text-nowrap">
												<tr class=" ">
													<th class="text-center " style="width: 1%"></th>
													<th>Product</th>
													<th class="text-center" style="width: 1%">Qnty</th>
													<th class="text-right" style="width: 1%">Unit Price</th>
													<th class="text-right" style="width: 1%">Delivery Method</th>
													<th class="text-right" style="width: 1%">Delivery Charge</th>
													<th class="text-right" style="width: 1%">Packing Charge</th>
													<th class="text-right" style="width: 1%">Amount</th>
												</tr>
												@if($order_items && count($order_items) > 0)
												@php $totals = $total_tax = $total_disc = $o= 0; @endphp
                    											@foreach($order_items as $row)
                    												@php $o++; @endphp
												<tr>
													<td class="text-center">{{ $o }}</td>
													<td>
														<p class="font-weight-semibold mb-1">{{ $row->prd_name }}</p>
														<!-- <div class="text-muted">Logo and business cards design</div> -->
													</td>
													<td class="text-center">{{ $row->qty }}</td>
													<td class="text-center">{{ $row->price }}</td>
													<td class="text-center">FedEx</td>
													<td class="text-center">0</td>
													<td class="text-center">0</td>
													<td class="text-right">{{ $row->total }}</td>
												</tr>
												@php $totals += $row->total;
												$total_tax += $row->tax;
												$total_disc += $row->discount;
												
												 @endphp
												@endforeach
              													  @endif
													@php $currency = getCurrency()->name; @endphp
												<tr>
													<td colspan="7" class="font-weight-semibold text-right">Subtotal</td>
													<td class="text-right"> {{$currency}} {{ number_format($order->total, 2); }}</td>
												</tr>
												<tr>
													<td></td>
													<td class="plabels">Payment Method: <p class="font-weight-semibold ml-1">COD</p></td>
													<td colspan="5" class="font-weight-semibold text-right">Tax</td>
													<td class="text-right">{{$currency}} {{ number_format($order->tax, 2); }}</td>
												</tr>
												<tr>
													<td></td>
													<td class="plabels">Payment Status:<p class="font-weight-semibold ml-1"> Paid</p></td>
													<td colspan="5" class="font-weight-semibold text-right">Discount</td>
													<td class="text-right">{{$currency}} {{ number_format($order->discount, 2);  }}</td>
												</tr>
                                                @if(isset($order->bid_charge) && ($order->bid_charge>0))
                                                <tr>
													<td colspan="7" class="font-weight-semibold text-right">Bid Charge </td>
													<td class=" text-right ">{{$currency}} {{ number_format($order->bid_charge, 2);  }}</td>
												</tr>
												 @endif
												  @if(isset($order->wallet_amount) && ($order->wallet_amount>0))
												<tr>
													<td colspan="7" class="font-weight-semibold text-right">Wallet Amount </td>
													<td class=" text-right">{{$currency}} {{ number_format($order->wallet_amount, 2);  }}</td>
												</tr>
												 @endif
												<tr>
													<td colspan="7" class="font-weight-semibold text-right">Shipping Charge </td>
													<td class="text-right">{{$currency}} {{ number_format($order->shiping_charge, 2);  }}</td>
												</tr>
												<tr>
													<td colspan="7" class="font-weight-bold text-uppercase text-right h4 mb-0">Net Payable </td>
													<td class="font-weight-bold text-right h4 mb-0">{{$currency}} {{ number_format($order->g_total, 2);  }}</td>
												</tr>
												<tr>
													<td colspan="8" class="text-right">
														
														<div class="row">
															<div class="col d-flex justify-content-end">
																<a href="{{ url('admin/customer/view/') }}/{{$user_id}}"  class="mr-2 btn btn-secondary" >Back</a>
															
															</div>
														</div>
													</td>
												</tr>
											</table>
										</div>
										
										<p class="text-muted text-center">Thank you very much for doing business with us. We look forward to working with you again!</p>
									</div>
								</div>
							</div>
						</div>
						<!-- End row-->

					</div>
				</div><!-- end app-content-->
            </div>

<style type="text/css">
p.addr_p {
margin-bottom: 0px;
}
.plabels {
	display: flex;
}
.plabels p {
	margin: 0;
}
</style>

@endsection
@section('js')
<!--INTERNAL Select2 js -->
<script src="{{URL::asset('admin/assets/plugins/select2/select2.full.min.js')}}"></script>
<script src="{{URL::asset('admin/assets/js/select2.js')}}"></script>
<!-- INTERNAL Popover js -->
<script src="{{URL::asset('admin/admin/assets/js/popover.js')}}"></script>

<!-- INTERNAL Sweet alert js -->
<script src="{{URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
<script src="{{URL::asset('admin/assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{URL::asset('admin/assets/js/sweet-alert.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>

<!-- INTERNAl Data tables -->
		<script src="{{URL::asset('admin/assets/js/datatable/tables/order-datatable.js')}}"></script>
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
@endsection