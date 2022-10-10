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
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Master Settings</a></li>
									<li class="breadcrumb-item " aria-current="page"><a href="{{url('/admin/tags')}}">Tags</a></li>
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

							<!-- 	@if(Session::has('message'))

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
														
{{ Form::open(array('url' => "admin/tags/save", 'id' => 'tagsForm', 'name' => 'tagsForm', 'class' => '','files'=>'true')) }}
												{{Form::hidden('id',0,['id'=>'id'])}}
												
												
														<div class="row">
															<div class="col">


																<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label class="form-label">Select Language <span class="text-red">*</span></label>
                                                                <select class="form-control custom-select select2" name="glo_lang_cid" required>
                                                                    
                                                                    @foreach ($language as $lang)
                                                                    <option value="{{ $lang->id }}">{{ $lang->glo_lang_name }}</option>
                                                                    @endforeach
                                                                </select>
																		</div>
																	@error('glo_lang_cid')
																	<p style="color: red">{{ $message }}</p>
																	@enderror
																	</div>
																	
																</div>
																<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label class="form-label">Tag Name <span class="text-red">*</span></label>
																			
																			{!! Form::text('tag_name', null, ['class' => 'form-control','id'=>'tag_name']) !!}
																		</div>
																	@error('tag_name')
																	<p style="color: red">{{ $message }}</p>
																	@enderror
																	</div>
																	
																</div>
																
																<div class="row">
																	<div class="col mb-3">
																		<div class="form-group">
																			<label class="form-label">Tag Description <span class="text-red">*</span></label>
																			{!! Form::textarea('tag_desc', null, ['class' => 'form-control','id'=>'tag_desc', 'rows' => '3']) !!}
																		</div>
																	@error('tag_desc')
																	<p style="color: red">{{ $message }}</p>
																	@enderror
																	</div>
																</div>
																<div class="row">
																	<div class="col mb-3">
																		<div class="form-group">
																			<label class="form-label">Category <span class="text-red">*</span></label>
	<select class="form-control custom-select select2" name="cat_id" id="category_id"  onchange="loadsubcat()">
		<option value="">Select Category</option>
	@foreach ($categories as $cat)
	<option value="{{ $cat->category_id }}">{{ $cat->cat_name }}</option>
	@endforeach
	</select>
																		</div>
																	@error('cat_id')
																	<p style="color: red">{{ $message }}</p>
																	@enderror
																	</div>
																</div>
																<div class="row">
																	<div class="col mb-3">
																		<div class="form-group">
																			<label class="form-label">Subcategory </label>
																			<input type="text" id="sub-category-id" placeholder="Type to filter" name="subcat_id" autocomplete="off" hidden />
																			 <input type="text" id="sub-category-drop" class="form-control " placeholder="Select Subcategory" readonly style="background-color: #fff !important;">
                                                                    
																		</div>
																	@error('subcat_id')
																	<p style="color: red">{{ $message }}</p>
																	@enderror
																	</div>
																</div>
																	<div class="row">
																<div class="col">
																		<div class="form-group">
																			<label class="form-label">Status <span class="text-red">*</span></label>
																	
																			{!! Form::select('is_active', array('1' => 'Active', '0' => 'Inactive'), '1',['class' => 'form-control','required','id'=>'module_status']); !!}
																		</div>
																	</div>
																</div>
															</div>
														</div>
														
														<div class="row" style="margin-top: 30px;">
															<div class="col d-flex justify-content-end">
															     <a href="{{url('/admin/tags')}}"  class="mr-2 btn btn-secondary" >Cancel</a>  
															<button class="btn btn-primary" type="submit">Save </button>
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
		<script src="{{URL::asset('admin/assets/js/sweet-alert.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/comboTreePlugin.js')}}"></script>
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


// jQuery(".deletemodule").click(function(){

// 	jQuery("#user-form-modal .modal-title").text("Edit Module");

// var moduleid = jQuery(this).parents("tr").find("#moduleid").data("value");



//  if(confirm("Are you sure you want to delete this module?")){
//        alert(moduleid);
//     }
//     else{
//         return false;
//     }


// });

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

	});
</script>

<script type="text/javascript">
    $(document).ready(function(){
            // @if(Session::has('message'))
            // @if(session('message')['type'] =="success")
            
            // toastr.success("{{session('message')['text']}}"); 
            // @else
            // toastr.error("{{session('message')['text']}}"); 
            // @endif
            // @endif
            
            // @if ($errors->any())
            // @foreach ($errors->all() as $error)
            // toastr.error("{{$error}}"); 
            
            // @endforeach
            // @endif
    });
    </script>


<script type="text/javascript">


var instance = $('#sub-category-drop').comboTree({
collapse:true,
cascadeSelect:true,
isMultiple: false
});

 function loadsubcat(clear='')
    {
        var category_id=$("#category_id").val();
        
        if(clear!='1')
        {
            $("#sub-category-id").val('');
        }
        
         $.ajax({
            type: "POST",
            url: '{{url("/admin/tags/subcategory")}}',
            data: { "_token": "{{csrf_token()}}", category_id: category_id},
            success: function (data) {
            	var obj = JSON.parse(data);
            
            	console.log(obj);
            	 var obj = JSON.parse(data);
            if(obj.subdata.length >=1)
            {
               $('#sub-category-drop').attr("placeholder", "Select subcategory"); 
            }
            else
            {
                $('#sub-category-drop').attr("placeholder", "No subcategory to display"); 
            }
            instance.setSource(obj.subdata);
            if($("#sub-category-id").val())
            {
                var selectionIdList = new Array($("#sub-category-id").val());
                instance.setSelection(selectionIdList);

            }
            
            }
        });
        
        
        
    }
    $('#sub-category-drop').on('change',function()
        {
            if(instance.getSelectedIds())
            {
                $("#sub-category-id").val(instance.getSelectedIds()[0]);
            }
        });


</script>
@endsection