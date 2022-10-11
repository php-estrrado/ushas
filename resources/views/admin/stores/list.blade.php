@extends('layouts.admin')
@section('css')
		<!-- INTERNAl Data table css -->
		<!--<link href="{{URL::asset('admin/assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />-->
		<!--<link href="{{URL::asset('admin/assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}"  rel="stylesheet">-->
		<!--<link href="{{URL::asset('admin/assets/plugins/datatable/responsive.bootstrap4.min.css')}}" rel="stylesheet" />-->
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
									
									<li class="breadcrumb-item active" aria-current="page"><a href="#">{{ $title }}</a></li>
								</ol>
							</div>
				<div class="page-rightheader" style="display:flex; flex-direction: row; justify-content: center; align-items: center">
								<!-- <label class="form-label" for="filterSel" style="margin-right: 8px;">Filter </label>
							    	<select class="form-control" id="filterSel" style="margin-right: 30px;">
									<option value="">All Status</option>
									<option value="Active">Active</option>
									<option value="Inactive">Inactive</option>
									</select> -->
								<div class="btn btn-list">
									<!-- <a href="#" class="btn btn-info"><i class="fe fe-settings mr-1"></i> General Settings </a>
									<a href="#" class="btn btn-danger"><i class="fe fe-printer mr-1"></i> Print </a> -->
									<!-- <a href="{{ url('/admin/brands/create') }}"   class="btn btn-primary addmodule"><i class="fe fe-plus mr-1"></i> Add New</a> -->
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
														<table class="table table-bordered border-top text-nowrap storetable" id="storetable">
															<thead>
																<tr>
																	<th class="align-top border-bottom-0 w-5 notexport">Sl No</th>
																	<th class="border-bottom-0 w-20">Store Name</th>
																	
																	<th class="border-bottom-0 w-20">Branch Code</th>
																	<th class="border-bottom-0 w-20">Store GST</th>
																	<th class="border-bottom-0 w-20">Phone Number</th>
																	<th class="border-bottom-0 w-15">Created On</th>
																	
																</tr>
															</thead>

															<tbody>

																@if($stores && count($stores) > 0) @php $n = 0; @endphp
                    											@foreach($stores as $row) @php $n++; @endphp
																<tr>
																	<td class="align-middle " id="moduleid" data-value="{{$row['id']}}">
																		<label class="custom-control custom-checkbox">
																			
																			{{ $loop->iteration }}
																		</label>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																			<h6 class=" font-weight-bold"> {{ $row->Branch_Name }} </h6>
																		</div>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																			<h6 class=" font-weight-bold"> {{$row->Branch_Code}} </h6>
																		</div>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																			<h6 class=" font-weight-bold"> {{$row->GSTIN}} </h6>
																		</div>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																			<h6 class=" font-weight-bold"> {{$row->PhoneNumber}} </h6>
																		</div>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																			<h6 class=" font-weight-bold"> {{date("d-m-Y h:i A",strtotime($row->CreatedDate))}} </h6>
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
@endsection
@section('js')
		<!-- INTERNAl Data tables -->
	
	<!-- INTERNAL Popover js -->
		<script src="{{URL::asset('admin/assets/js/popover.js')}}"></script>
     <script src="{{URL::asset('admin/assets/js/datatable/tables/stores-datatable.js')}}"></script>
		<!-- INTERNAL Sweet alert js -->
		<script src="{{URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/sweet-alert.js')}}"></script>
<script type="text/javascript">
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
        url: '{{url("/admin/brands/status")}}',
        data: { "_token": "{{csrf_token()}}", id: selid,status:sestatus},
        success: function (data) {
        // alert(data);
        if(data ==1) {
        if(sestatus ==1) {
        	jQuery('#status-'+selid).closest("td").attr("data-search","Active");
              toastr.success("Brand activated successfully.");   
            }else {
            	jQuery('#status-'+selid).closest("td").attr("data-search","Inactive");
               toastr.success("Brand deactivated successfully.");  
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
	
	$('body').on('click','.deletemodule',function(){  

		var id          =   this.id.replace('deletemodule-','');

		$('body').removeClass('timer-alert');
		swal({
			title: "Delete Confirmation",
			text: "Are you sure you want to delete this brand?",
			// type: "input",
			showCancelButton: true,
			closeOnConfirm: true,
			confirmButtonText: 'Yes'
		},function(inputValue){
			if (inputValue == true) {
			 $.ajax({
            type: "POST",
            url: '{{url("/admin/brands/delete")}}',
            data: { "_token": "{{csrf_token()}}", id: id},
            success: function (data) {
            	// alert(data);
            		toastr.success("Brand deleted successfully");
            		location.reload();
            	
            }
        });

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