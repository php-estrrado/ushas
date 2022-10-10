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
									
									<li class="breadcrumb-item active" aria-current="page"><a href="#">{{ $title }}</a></li>
								</ol>
							</div>
											
						</div>
                        <!--End Page header-->
@endsection
@section('content')
						<!-- Row -->
						<div class="row flex-lg-nowrap">
							<div class="col-12">

							<div class="row" id="filtersec" >
								<div class="col-12 d-flex flex-row-reverse" >
									<div class="form-group col-md-1 " >
										<label></label>
										<a href="{{ url('admin/new-label') }}"  type="button" data-id="0" class="btn btn-primary mb-5 " style="margin-top:5px; margin-right:12px;">Add New</a>
									</div>
									<div class="form-group col-md-2 mt-5 " >
										<select class="form-control active_filters " id="status" >
											<option value="">All</option>
											<option value="1">active</option>
											<option value="0">In active</option>
										</select>
										<span class="error"></span>
									</div>
									<div class="form-group col-md-2" >	
										{{Form::label('Filter','Filter',['class'=>''])}} 
										<select class="form-control active_filters" id="filter_label" >
											<option value="">All</option>
											<option value="app">App</option>
											<option value="web">Web</option>
										</select>
										<span class="error"></span>
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
														<table class="table table-bordered border-top text-nowrap labellist" id="labellist" style="width: 100%">
															<thead>
																<tr>
																	<th class="align-top border-bottom-0 wd-5 notexport">Select</th>
																	<th class="border-bottom-0 w-15">Title</th>
																	<th class="border-bottom-0 w-15">Status</th>
																	<th class="border-bottom-0 w-15">Created On</th>
																	<th class="border-bottom-0 w-15">Actions</th>
																	
																</tr>
															</thead>

															<tbody>
															</tbody>

														</table>
														{{Form::hidden('listUrl',route('labels.list'),['id'=>'listUrl'])}}
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
		<script src="{{URL::asset('admin/assets/js/datatable/tables/labels-datatable.js')}}"></script>
	<!-- INTERNAL Popover js -->
		<script src="{{URL::asset('admin/assets/js/popover.js')}}"></script>

		<!-- INTERNAL Sweet alert js -->
		<script src="{{URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/sweet-alert.js')}}"></script>
<script type="text/javascript">
	
	jQuery(document).ready(function(){
	$(document).on('change', '#filtersec .active_filters', function(){  
		var status  =   $('#filtersec #status').val();
		var labelfor  =   $('#filtersec #filter_label').val();
		$(".labellist").DataTable().ajax.url("{{url('admin/labels')}}?labelfor="+labelfor+"&status="+status).load();
	});
	
	$('body').on('change', '.active_status', function() {
        var status = $(this).prop('checked') == true ? 1 : 0;
        var selid = this.id.replace('status-','');
        $.ajax({
            type: "POST",
            url: '{{url("admin/label/status")}}',
            data: { "_token": "{{csrf_token()}}", id: selid,status: status},
            success: function (data) {
            $(".labellist").DataTable().ajax.reload();
                console.log(data.success)
            }
        });
        if(status ==1) {
              toastr.success("Label activated successfully.");   
            }else {
               toastr.success("Label deactivated successfully.");  
            }
});
	
	});
var asset_path = "{{asset('/')}}";
var tablePath = asset_path+"{{'admin/assets/js/datatable/tables/org/'}}"

function deleteLabel(label_id) {
    swal({
        title: "Delete Confirmation",
        text: "Are you sure you want to delete this Label?",
        // type: "input",
        showCancelButton: true,
        closeOnConfirm: true,
        confirmButtonText: 'Yes'
        },function(inputValue){
		if (inputValue == true) { 
		$.ajax({
                type: "POST",
                url: '{{ url("admin/label/delete") }}',
                data: {label_id:label_id,'_token': '{{ csrf_token()}}'},
                success: function (data) {
                    if(data ==1){
						toastr.success("Label deleted successfully."); 
						}else{
						 toastr.error("Failed to deleted.");   
						}

							$(".labellist").DataTable().ajax.url("{{url('admin/labels')}}").load();
	
                }
            });
    
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