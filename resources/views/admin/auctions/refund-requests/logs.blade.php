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
@endsection
@section('page-header')
						<!--Page header-->


						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0">{{ $title }}</h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Auction Management</a></li>
									<li class="breadcrumb-item active" aria-current="page"><a href="{{ url('admin/auction/refund-request') }}">Auction Refund Requests</a></li>
									<li class="breadcrumb-item active" aria-current="page"><a href="#">{{ $title }}</a></li>
								</ol>
							</div>
											<div class="page-rightheader" style="display:flex; flex-direction: row; justify-content: center; align-items: center">
						
							
								<div class="btn btn-list">
									<!-- <a href="#" class="btn btn-info"><i class="fe fe-settings mr-1"></i> General Settings </a>
									<a href="#" class="btn btn-danger"><i class="fe fe-printer mr-1"></i> Print </a> -->
							<!-- 		<a href="{{ url('admin/coupons/create/') }}"  class="btn btn-primary addmodule"><i class="fe fe-plus mr-1"></i> Add New</a> -->
								</div>
							</div>
						</div>
                        <!--End Page header-->
@endsection
@section('content')
						<!-- Row -->


						<div class="main-proifle">
							<div class="row">
								<div class="col-lg-12">
									<div class="box-widget widget-user">
										<div class="widget-user-image1 d-sm-flex row mb-4">
											<div class="mt-1 col-lg-2">
										    @if($auctions['product_img']!='')
										    @php $prod_img=config('app.storage_url').$auctions['product_img'];
																	    @endphp
										    <img alt="Product Image" class="rounded-circle border p-0" style="width:120px;height:130px;" src="{{ $prod_img }}">
										    @else
											<img alt="Product Image" class="rounded-circle border p-0" src="{{ url('storage/app/public/product/default.jpg') }}">
											@endif
										</div>
											<div class="mt-1 col-lg-10">
												<div class="desc-sec">
													<h4 class="pro-user-username mb-3 font-weight-bold">{{ $auctions['product_name'] }}</h4>

												<div class="mb-0 pro-details">
                                                   
													<p><span class="h6 mt-3">{{ Str::limit($auctions['auct_desc'], 30) }} </span></p>
                                                    
												</div>
												</div>

												<div class="listing-sec">
												

												<ul class="mb-0 pro-details">
                                                   
													<li><span class="h6 mt-3">Start Date: {{date('Y-m-d H:i:s', strtotime($auctions['auct_start']))}}</span></li>
													<li><span class="h6 mt-3">End Date: {{date('Y-m-d H:i:s', strtotime($auctions['auct_end']))}}</span></li>
													<li><span class="h6 mt-3">Seller: {{$auctions['seller_name']}}</span></li>
													<li><span class="h6 mt-3">Status:  @if($auctions['is_active'] ==1) {{ "Active" }} @else {{ "Inactive" }} @endif</span></li>
													<li><span class="h6 mt-3">Min. Bid Price: {{$auctions['min_bid_price']}}</span></li>
													<li><span class="h6 mt-3">No. Of Bids: {{count($auctions['bids'])}}</span></li>
													<li><span class="h6 mt-3">Bid Allocated To: {{$auctions['bid_allocated_to_user']}}</span></li>

                                                    
												</ul>
											</div>
												
											</div>

											  
										</div>
									</div>
								</div>
								
							</div>
					
						</div>
						<div class="row ">
						<div class="col-12">
						<div class="btn btn-list fr mb-4">
						<a   class="btn btn-primary process_refund" style="display:none;"><i class="fe fe-plus mr-1"></i> Process Refund</a>
						</div>
						</div>
							</div>
						<div class="row flex-lg-nowrap">
							<div class="col-12">

								<div class="row flex-lg-nowrap">
									<div class="col-12 mb-3">
										<div class="e-panel card">
											<div class="card-body">
												<div class="e-table">
													<div class="table-responsive table-lg mt-3">
														{{ Form::open(array('url' => "admin/auction/process-refund", 'id' => 'refundForm', 'name' => 'refundForm', 'class' => '','files'=>'true')) }}

												<input type="hidden" name="auction_id" value="{{ $auctions['id'] }}">
												

														<table class="table table-bordered border-top text-nowrap auctions_refund" id="auctions_refund">
															<thead>
																<tr>
																	<th class="align-top border-bottom-0 wd-5 notexport">Select</th>
																	
																	
																	<th class="border-bottom-0 w-30">Customer</th>
																	<th class="border-bottom-0 w-20">Paid Amount</th>
																	<th class="border-bottom-0 w-30">Refund Amount</th>
																	<th class="border-bottom-0 w-30">Status</th>
																
																																		
																</tr>
															</thead>

															<tbody>

																@if($log && count($log) > 0)
                    											@foreach($log as $row)
                                                                        
                                                                <?php 
            											       
            											       $user_id=$row['user_id'];
            											       $user_info =DB::table('usr_mst')->where('id', $user_id)->first();
                                                               $cust_id = date('y',strtotime($user_info->created_at)).date('m',strtotime($user_info->created_at)).str_pad($user_id, 6, "0", STR_PAD_LEFT);
                                                             ?> 
                    										
																<tr>
																	<td class="align-middle process_selection" id="moduleid" data-value="{{$row['id']}}">
																		<label class="custom-control custom-checkbox">
																			
																			@if($row['status'] == "pending")
																			<input type="checkbox"  class="removeitem" name="to_process[]"  value="{{$row['id']}}" >
																			<style>
																			    a.process_refund { display:block !important;}
																			</style>
																			@else
																			<i class="fa fa-check"></i>
																			@endif

																		</label>
																	</td>
																	
																	<td class="align-middle" >
																		<div class="d-flex">
																		<p>{{$row['user_name']}} <br>(<small>{{$cust_id}}</small>)</p>
																	</div>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																		<p>{{$row['paid_amount']}}</p>
																	</div>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																		<p>{{ $row['refund_amount'] }}</p>
																	</div>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																		<p>@if($row['status'] =="completed") {{ "Refunded" }} @else {{  ucfirst($row['status']) }} @endif</p>
																	</div>
																	</td>
																	
																</tr>
																     @endforeach
                @endif
																
																
																
																
															</tbody>
														</table>
														{{Form::close()}}
													</div>
												</div>

											</div>
										</div>
									</div>
								</div>
                                <div class="row">
                                <div class="col d-flex justify-content-end">
                                <a href="{{url('/admin/auction/refund-request')}}"  class="mr-2 btn btn-secondary" >Back</a>
                                
                                </div>
                                </div>
							</div>
						</div>
						<!-- End Row -->


						<!-- Add Form Modal -->

							<div class="modal fade" role="dialog" tabindex="-1" id="add-form-modal">
									<div class="modal-dialog modal-lg" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title">Create Coupon</h5>
												<button type="button" class="close" data-dismiss="modal">
													<span aria-hidden="true">×</span>
												</button>
											</div>
											<div class="modal-body">
												
											</div>
										</div>
									</div>
								</div>
								

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
		<!-- INTERNAl Data tables -->

		<script src="{{URL::asset('admin/assets/js/datatable/tables/auctions_refund-datatable.js')}}"></script>
	<!-- INTERNAL Popover js -->
		<script src="{{URL::asset('admin/assets/js/popover.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/comboTreePlugin.js')}}"></script>

		<!-- INTERNAL Sweet alert js -->
		<script src="{{URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/sweet-alert.js')}}"></script>
<script type="text/javascript">


	jQuery(document).ready(function(){

$('#filterCpn').on( 'change', function(){ 

var sel = $(this).val();
window.location = "{{ url('admin/coupons/log/') }}/"+sel;
 } );


// Prompt
	$(".process_refund").on("click", function(e){

 var checked = $(".process_selection input[type=checkbox]:checked").length;

      if(!checked) {
  
        swal('Unable to process!', 'Please select atleast 1 user to process refund.', 'error');
        return false;
      }else {
     
        // jQuery("#returnsform").submit();

        $('body').removeClass('timer-alert');
		swal({
			title: "Process Refund",
			text: "Are you sure you want to process refund to selected users?",
			// type: "input",
			showCancelButton: true,
			closeOnConfirm: true,
			confirmButtonText: 'Yes'
		},function(inputValue){

		if (inputValue == true) {
		jQuery("#refundForm").submit();

			}
		});

      }

	
		
	});



        $(".ser_status").on("click", function(e){
        
        var selid = jQuery(this).data("selid");
        
        var sestatus='0';
        if($(this).prop('checked') == true)
        {
        sestatus='1';
        }
        
        $.ajax({
        type: "POST",
        url: '{{url("/admin/coupons/status")}}',
        data: { "_token": "{{csrf_token()}}", id: selid,status:sestatus},
        success: function (data) {
        // alert(data);
        if(data ==1) {
        if(sestatus ==1) {
              toastr.success("Tag activated successfully.");   
            }else {
               toastr.success("Tag deactivated successfully.");  
            }
        }else {
        toastr.error("Failed to update status."); 	
        }
        
        
        }
        });
        });
        
        $('#userrole').DataTable({
		language: {
			searchPlaceholder: 'Search...',
			sSearch: '',
			lengthMenu: '_MENU_',
		}
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

@endsection