@extends('layouts.admin')
@section('css')
		<!-- INTERNAl Data table css -->
		<link href="{{URL::asset('admin/assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}"  rel="stylesheet">
		<link href="{{URL::asset('admin/assets/plugins/datatable/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />
		<style>
		.radiospan {
			font-size: 12px;
   			 padding-top: 8px;
		}
		.radiospan checkbox {
			width: 12px;
		}
		</style>
@endsection
@section('page-header')
						<!--Page header-->


						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0">{{ $title }}</h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>User Management</a></li>
									<li class="breadcrumb-item " aria-current="page"><a href="{{url('/admin/user-roles')}}">User Roles</a></li>
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
														
{{ Form::open(array('url' => "admin/user-roles/save", 'id' => 'userForm', 'name' => 'userForm', 'class' => '','files'=>'true')) }}

												<input type="hidden" name="id" value="{{ $userrole->id }}">

												{{Form::hidden('is_selected',0,['is_selected'=>'is_selected'])}}
												<input type="hidden" name="module_changed" id="module_changed" value="0">
														<div class="row">
															<div class="col">
																<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label>Role Name <span class="text-red">*</span></label>
																			<input type="text"  class="form-control" name="usr_role_name" value="{{ $userrole->usr_role_name }}">
																		
																		</div>
																		@error('usr_role_name')
																	<p style="color: red">{{ $message }}</p>
																	@enderror
																	</div>
																	
																</div>
																
																<div class="row">
																	<div class="col mb-3">
																		<div class="form-group">
																			<label>About <span class="text-red">*</span></label>
																			<textarea name="usr_role_desc" class="form-control" >{{ $userrole->usr_role_desc }}</textarea>
																			
																		</div>
																		@error('usr_role_desc')
																	<p style="color: red">{{ $message }}</p>
																	@enderror
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
		


															<div class="col" id="permissions">
																<div class="mb-2"><b>Permissions</b></div>
                    <div class="col-12 heading">
					<div class="row">
					<div class="col-6">Modules</div>
					<div class="col-2 citems">View <span class="d-block radiospan"><input type="checkbox" id="view-all" class="mr-1" >All</span></div>
					<div class="col-2 citems">Edit <span class="d-block radiospan"><input type="checkbox" id="edit-all" class="mr-1" >All</span></div>
					<div class="col-2 citems">Delete <span class="d-block radiospan"><input type="checkbox" id="delete-all" class="mr-1" >All</span></div>
					</div>
					</div>
															
						
 @if($modules && count($modules) > 0)
                    @foreach($modules as $row)
                        @php $pt = $row['parent'];  $child = $row['child']; @endphp

                        @php $trows =  DB::table('usr_role_action')->where('usr_role_id',$userrole->id)->where('module_id',$pt['id'])->where('is_deleted',0)->where('is_active',1)->first();  @endphp

                      
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-6 col-5 title">{{$pt['name']}}</div>
                                <div class="col-2 title citems">
									
									<div class="switch viewbox">
    <input class=' switch-input status-btn ser_status' name="modules[{{$pt['id']}}]['view']" data-selid="{{$pt['id']}}" id="view-{{$pt['id']}}" value="1"  type="checkbox" @if($trows) @if($trows->view ==1) {{ "checked" }} @endif @endif />
    <label class="switch-paddle" for="view-{{$pt['id']}}">
    <span class="switch-active" aria-hidden="true">Active</span>
    <span class="switch-inactive" aria-hidden="true">Inactive</span>
    </label>
    </div>

								</div>
								<div class="col-2 title citems">

		<div class="switch editbox">
		<input class='switch-input status-btn ser_status' name="modules[{{$pt['id']}}]['edit']" id="edit-{{$pt['id']}}" data-selid="{{$pt['id']}}" value="1"  type="checkbox" @if($trows) @if($trows->edit ==1) {{ "checked" }} @endif @endif />
		<label class="switch-paddle" for="edit-{{$pt['id']}}">
		<span class="switch-active" aria-hidden="true">Active</span>
		<span class="switch-inactive" aria-hidden="true">Inactive</span>
		</label>
		</div>

								</div>
								<div class="col-2 title citems">
									<div class="switch deletebox">
		<input class=' switch-input status-btn ser_status' name="modules[{{$pt['id']}}]['delete']" id="delete-{{$pt['id']}}" data-selid="{{$pt['id']}}" value="1"  type="checkbox" @if($trows) @if($trows->delete ==1) {{ "checked" }} @endif @endif />
		<label class="switch-paddle" for="delete-{{$pt['id']}}">
		<span class="switch-active" aria-hidden="true">Active</span>
		<span class="switch-inactive" aria-hidden="true">Inactive</span>
		</label>
		</div>

								</div>
                            </div>
                            @if($child && count($child) > 0) 
                                @php $nrow = 'odd'; @endphp
                                @foreach($child as $ch) 

                                @php $ctrows =  DB::table('usr_role_action')->where('usr_role_id',$userrole->id)->where('module_id',$ch['id'])->where('is_deleted',0)->where('is_active',1)->first();  @endphp
                                    <div class="row">
                                        <div class="col-md-6 col-5 sub-title module {{$nrow}}">{{$ch['name']}}</div>
                                       <div class="col-2 sub-title {{$nrow}} citems">
                                       						<div class="switch viewbox">
		<input class='switch-input status-btn ser_status' name="modules[{{$ch['id']}}]['view']" id="ch-view-{{$ch['id']}}" data-selid="{{$ch['id']}}" value="1"  type="checkbox" @if($ctrows) @if($ctrows->view ==1) {{ "checked" }} @endif @endif />
		<label class="switch-paddle" for="ch-view-{{$ch['id']}}">
		<span class="switch-active" aria-hidden="true">Active</span>
		<span class="switch-inactive" aria-hidden="true">Inactive</span>
		</label>
		</div>

								</div>
								<div class="col-2 sub-title {{$nrow}} citems">
									<div class="switch editbox">
		<input class='switch-input status-btn ser_status' name="modules[{{$ch['id']}}]['edit']" data-selid="{{$ch['id']}}" id="ch-edit-{{$ch['id']}}" value="1"  type="checkbox" @if($ctrows) @if($ctrows->edit ==1) {{ "checked" }} @endif @endif />
		<label class="switch-paddle" for="ch-edit-{{$ch['id']}}">
		<span class="switch-active" aria-hidden="true">Active</span>
		<span class="switch-inactive" aria-hidden="true">Inactive</span>
		</label>
		</div>

								</div>
								<div class="col-2 sub-title {{$nrow}} citems">
										<div class="switch deletebox">
		<input class='switch-input status-btn ser_status' name="modules[{{$ch['id']}}]['delete']" id="ch-delete-{{$ch['id']}}" data-selid="{{$ch['id']}}" value="1"  type="checkbox" @if($ctrows) @if($ctrows->delete ==1) {{ "checked" }} @endif @endif />
		<label class="switch-paddle" for="ch-delete-{{$ch['id']}}">
		<span class="switch-active" aria-hidden="true">Active</span>
		<span class="switch-inactive" aria-hidden="true">Inactive</span>
		</label>
		</div>
								</div>
                                    </div>
                                    @php if($nrow == 'odd'){ $nrow = 'even'; }else{ $nrow = 'odd'; } @endphp
                                @endforeach 
                            @else
                            <div class="row"><div class="col-12 br-line-wh"></div></div>
                            @endif
                        </div>
                    @endforeach
                @endif
																	
															</div>
														</div>
														<div class="row" style="margin-top: 20px;">
														<div class="col">
														<div class="form-group">
														<label class="form-label">Status <span class="text-red">*</span></label>

{!! Form::select('is_active', array('1' => 'Active', '0' => 'Inactive'), $userrole->is_active,['class' => 'form-control','required','id'=>'module_status']); !!}														</div>
														</div>
														</div>
														<div class="row" style="margin-top: 30px;">
															<div class="col d-flex justify-content-end">
															     <a href="{{url('/admin/user-roles')}}"  class="mr-2 btn btn-secondary" >Cancel</a>  
															<button class="btn btn-primary" type="submit">Save</button>
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
	jQuery(document).ready(function(){

jQuery(".editmodule").click(function(){

	jQuery("#user-form-modal .modal-title").text("Edit Module");

var moduleid = jQuery(this).parents("tr").find("#moduleid").data("value");
var module_name = jQuery(this).parents("tr").find("#module_name").data("value");
var module_link = jQuery(this).parents("tr").find("#module_link").data("value");
var module_class = jQuery(this).parents("tr").find("#module_class").data("value");
var module_status = jQuery(this).parents("tr").find("#module_status").data("value");
var module_order = jQuery(this).parents("tr").find("#module_order").data("value");

jQuery("#userForm #moduleid").val(moduleid);
jQuery("#userForm #module_name").val(module_name);
jQuery("#userForm #module_link").val(module_link);
jQuery("#userForm #module_class").val(module_class);
jQuery("#userForm #module_status").val(module_status);
jQuery("#userForm #module_order").val(module_order);


});

jQuery(".addmodule").click(function(){

jQuery("#user-form-modal .modal-title").text("Create Module");
jQuery("#userForm #moduleid").val(0);
$("#userForm").trigger("reset");

});

$(".ser_status").change(function() {

	$("#module_changed").val(1);
    
});

	// Prompt
	$(".deletemodule").on("click", function(e){

		var moduleid = jQuery(this).parents("tr").find("#moduleid").data("value");
		$('body').removeClass('timer-alert');
		swal({
			title: "Delete Confirmation",
			text: "Are you sure you want to delete this module?",
			// type: "input",
			showCancelButton: true,
			closeOnConfirm: true,
			confirmButtonText: 'Yes'
		},function(inputValue){



			if (inputValue == true) {
			 $.ajax({
            type: "POST",
            url: '{{url("/admin/modules/delete")}}',
            data: { "_token": "{{csrf_token()}}", id: moduleid},
            success: function (data) {
            	// alert(data);
            	if(data ==1){
            		location.reload();
            	}
            
            }
        });

			}
		});
	});
$("#view-all").click(function(){
	
		if ($(this).is(':checked')){ 
			
        	$('.viewbox .status-btn').prop('checked', true);
  		  }else {
  		  	
  		  	$('.viewbox .status-btn').prop('checked', false);
  		  }
	});
	$("#edit-all").click(function(){
	
		if ($(this).is(':checked')){ 
        	$('.editbox .status-btn').prop('checked', true);
  		  }else {
  		  	$('.editbox .status-btn').prop('checked', false);
  		  }
	});
	$("#delete-all").click(function(){
	
		if ($(this).is(':checked')){ 
        	$('.deletebox .status-btn').prop('checked', true);
  		  }else {
  		  	$('.deletebox .status-btn').prop('checked', false);
  		  }
	});
	
	});
</script>

<script type="text/javascript">
    // $(document).ready(function(){
    //         @if(Session::has('message'))
    //         @if(session('message')['type'] =="success")
            
    //         toastr.success("{{session('message')['text']}}"); 
    //         @else
    //         toastr.error("{{session('message')['text']}}"); 
    //         @endif
    //         @endif
            
    //         @if ($errors->any())
    //         @foreach ($errors->all() as $error)
    //         toastr.error("{{$error}}"); 
            
    //         @endforeach
    //         @endif
    // });
    </script>


@endsection