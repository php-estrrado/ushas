@extends('layouts.admin')
@section('css')
		<!-- INTERNAl Data table css -->
		<link href="{{URL::asset('admin/assets/js/datatable/datatables.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}"  rel="stylesheet">
		<link href="{{URL::asset('admin/assets/plugins/datatable/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />
		<style>
        #sortable-row { list-style: none; color: black; }
        #sortable-row li { margin-bottom:4px; padding:10px; background-color:#BBF4A8;cursor:move;}
        #sortable-row li.ui-state-highlight { height: 1.0em; background-color:#F0F0F0;border:#ccc 2px dotted;}
        .modal-open 
        {
        overflow: scroll;
        }
    </style>
@endsection
@section('page-header')
						<!--Page header-->


						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0">Modules</h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Master Settings</a></li>
									
									<li class="breadcrumb-item active" aria-current="page"><a href="#">Modules</a></li>
								</ol>
							</div>
							<div class="page-rightheader" style="display:flex; flex-direction: row; justify-content: center; align-items: center">
								<!--<label class="form-label" for="filterSel" style="margin-right: 8px;">Filter </label>-->
							 <!--       <select class="form-control" id="filterSel" style="margin-right: 30px;">-->
        <!--                            <option value="">All Status</option>-->
        <!--                            <option value="Active">Active</option>-->
        <!--                            <option value="Inactive">Inactive</option>-->
        <!--                            </select>-->
								<div class="btn btn-list">
									<!-- <a href="#" class="btn btn-info"><i class="fe fe-settings mr-1"></i> General Settings </a>
									<a href="#" class="btn btn-danger"><i class="fe fe-printer mr-1"></i> Print </a> -->
									<a href="#"  data-target="#user-form-modal" data-toggle="modal" class="btn btn-primary addmodule"><i class="fe fe-plus mr-1"></i> Add New</a>
									<button style="" data-backdrop="static" class="btn btn-warning" data-toggle="modal"  data-target="#sort-modal" data-container=""><i class="fa fa-sort mr-1"></i> Sort Order</button>
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
													    
														<table class="table table-bordered border-top text-nowrap modulestable" id="modulestable">
															<thead>
																<tr>
																	<th class="align-top border-bottom-0 wd-5 notexport">Select</th>
																	<th class="border-bottom-0 w-20">Module</th>
																	<th class="border-bottom-0 w-20">Link</th>
																	<th class="border-bottom-0 w-15 d-none">Class</th>
																	<!-- <th class="border-bottom-0 w-30">Sort Order</th> -->
																	<th class="border-bottom-0 w-15 d-none">Icon</th>
																	<th class="border-bottom-0 w-15">Created On</th>
																	<th class="border-bottom-0 w-20">Status</th>
																	<th class="border-bottom-0 w-10 notexport">Actions</th>
																</tr>
															</thead>

															<tbody>

@if($modules && count($modules) > 0)
@foreach($modules as $row)

@php  $pt = $row['parent'];  $child = $row['child']; @endphp
<tr>
<td class="align-middle select-checkbox" id="moduleid" data-value="{{$pt['id']}}" data-parent="0">
<label class="custom-control custom-checkbox">

<!--{{ $loop->iteration }}-->
</label>
</td>
<td class="align-middle" id="module_name" data-value="{{$pt['module_name']}}">
<div class="d-flex">


<h6 class=" font-weight-bold"><a class="viewmodule" data-toggle="modal" data-target="#view-module" style="cursor: pointer;">{{$pt['module_name']}}</a></h6>


</div>
</td>
<td class="text-nowrap align-middle" id="module_link" data-value="{{$pt['link']}}">
<p>{{$pt['link']}}</p>
</td>
<td class="text-nowrap align-middle d-none" id="module_class" data-value="{{$pt['class']}}">
<p>{{$pt['class']}}</p>
</td>
<!-- <td class="text-nowrap align-middle" id="module_order" data-value="{{$pt['sort']}}">
<p>{{$pt['sort']}}</p>
</td> -->
<td class="text-nowrap align-middle d-none" id="menu_icon" data-value="{{$pt['menu_icon']}}">
<p>{{$pt['menu_icon']}}</p>
</td>
<td class="text-nowrap align-middle"><span>{{date('d M Y',strtotime($pt['created_at']))}}</span></td>
<td class="text-nowrap align-middle" id="module_status" data-value="{{$pt['is_active']}}" data-search="@if($pt['is_active'] ==1){{ "Active" }}@else{{ "Inactive" }}@endif">

<!--<label class="onswitch  ">-->
<!--<input class='ser_status' data-selid="{{$pt['id']}}"  type="checkbox"  @if($pt['is_active'] ==1) {{ "checked" }} @endif />-->
<!--<span class="slider round"></span>-->
<!--</label>-->

    <div class="switch">
        
    <input class="switch-input status-btn ser_status" data-selid="{{$pt['id']}}" id="status-{{$pt['id']}}" type="checkbox" @if($pt['is_active'] ==1) {{ "checked" }} @endif >
    <label class="switch-paddle" for="status-{{$pt['id']}}">
    <span class="switch-active" aria-hidden="true">Active</span>
    <span class="switch-inactive" aria-hidden="true">Inactive</span>
    </label>
    </div>
</td>


<td class="align-middle">
<div class="btn-group align-top">
     @if(checkPermission('/admin/modules','edit') == true)
<button class=" btn btn-info btn-sm editmodule" type="button" data-toggle="modal" data-target="#user-form-modal" ><i class="fe fe-edit mr-1"></i>Edit</button>&nbsp;
@endif
 @if(checkPermission('/admin/modules','delete') == true)
<button  class="mr-2 btn btn-secondary btn-sm delBtn deletemodule" type="button"><i class="fe fe-trash-2  mr-1"></i>Delete</button>
@endif
</div>
</td>
</tr>

@if($child && count($child) > 0) 
                                @php $nrow = 'odd'; @endphp
                                @foreach($child as $ch) 

<tr>
<td class="align-middle select-checkbox" id="moduleid" data-value="{{$ch['id']}}" data-parent="{{$ch['parent']}}">
<label class="custom-control custom-checkbox">

 <!--{{$ch['parent'].".".$loop->iteration }} -->
</label>
</td>
<td class="align-middle" id="module_name" data-value="{{$ch['module_name']}}">
<div class="d-flex">


<h6 class=" font-weight-normal viewmodule" class="" data-toggle="modal" data-target="#view-module" style="cursor: pointer;" >{{ $loop->iteration.". " }}{{$ch['module_name']}}</h6>


</div>
</td>
<td class="text-nowrap align-middle" id="module_link" data-value="{{$ch['link']}}">
<p>{{$ch['link']}}</p>
</td>
<td class="text-nowrap align-middle d-none" id="module_class" data-value="{{$ch['class']}}">
<p>{{$ch['class']}}</p>
</td>
<!-- <td class="text-nowrap align-middle" id="module_order" data-value="{{$ch['sort']}}">
<p>{{$ch['sort']}}</p>
</td> -->
<td class="text-nowrap align-middle d-none" id="menu_icon" data-value="{{$ch['menu_icon']}}">
<p>{{$ch['menu_icon']}}</p>
</td>
<td class="text-nowrap align-middle"><span>{{date('d M Y',strtotime($ch['created_at']))}}</span></td>
<td class="text-nowrap align-middle" id="module_status" data-value="{{$ch['is_active']}}" data-search="@if($ch['is_active'] ==1){{ "Active" }}@else{{ "Inactive" }}@endif">

<!--<label class="onswitch  ">-->
<!--<input class='ser_status' data-selid="{{$ch['id']}}"  type="checkbox"  @if($ch['is_active'] ==1) {{ "checked" }} @endif />-->
<!--<span class="slider round"></span>-->
<!--</label>-->

<div class="switch">
    <input class="switch-input status-btn ser_status" data-selid="{{$ch['id']}}"  id="status-{{$ch['id']}}"  type="checkbox" @if($ch['is_active'] ==1) {{ "checked" }} @endif >
    <label class="switch-paddle" for="status-{{$ch['id']}}">
    <span class="switch-active" aria-hidden="true">Active</span>
    <span class="switch-inactive" aria-hidden="true">Inactive</span>
    </label>
    </div>
</td>


<td class="align-middle">
<div class="btn-group align-top">
    @if(checkPermission('/admin/modules','edit') == true)
<button class=" btn btn-info btn-sm editmodule" type="button" data-toggle="modal" data-target="#user-form-modal" ><i class="fe fe-edit mr-1"></i>Edit</button>&nbsp;
@endif
 @if(checkPermission('/admin/modules','delete') == true)
<button  class="mr-2 btn btn-secondary btn-sm delBtn deletemodule" type="button"><i class="fe fe-trash-2  mr-1"></i>Delete</button>
@endif
</div>
</td>
</tr>
                                 @php if($nrow == 'odd'){ $nrow = 'even'; }else{ $nrow = 'odd'; } @endphp
                                @endforeach 
                            @else
                            <div class="row"><div class="col-12 br-line-wh"></div></div>
                            @endif
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
								<div class="modal fade" role="dialog" tabindex="-1" id="user-form-modal">
									<div class="modal-dialog modal-lg" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title">Create Module</h5>
												<button type="button" class="close" data-dismiss="modal">
													<span aria-hidden="true">×</span>
												</button>
											</div>
											<div class="modal-body">
												{{ Form::open(array('url' => "admin/modules/save", 'id' => 'userForm', 'name' => 'userForm', 'class' => '','files'=>'true')) }}
												{{Form::hidden('id',0,['id'=>'moduleid'])}}
												{{Form::hidden('parent',0,['id'=>'parent'])}}
												<div class="py-1">
													
														<div class="row">
															<div class="col">
																<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label>Module Name <span class="text-red">*</span></label>
																			
																			{!! Form::text('module_name', null, ['class' => 'form-control','required','id'=>'module_name']) !!}
																		</div>
																	</div>
																	<div class="col">
																		<div class="form-group">
																			<label>Class <span class="text-red">*</span></label>
																			
																			{!! Form::text('class', null, ['class' => 'form-control','required','id'=>'module_class']) !!}
																		</div>
																	</div>
																	
																</div>
																<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label>Slug <span class="text-red">*</span></label>
																		
																			{!! Form::text('link', null, ['class' => 'form-control','required','id'=>'module_link']) !!}
																		</div>
																	</div>
																	<!-- <div class="col">
																		<div class="form-group">
																			<label>Sort Order <span class="text-red">*</span></label>
																			
																			{!! Form::text('sort', null, ['class' => 'form-control','required','id'=>'module_order']) !!}
																		</div>
																	</div> -->
																	<div class="col">
																		<div class="form-group">
																			<label>Menu Icon </label>
																			
																			{!! Form::text('menu_icon', null, ['class' => 'form-control','','id'=>'menu_icon']) !!}
																		</div>
																	</div>
																	
																</div>
																<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label>Parent </label>
																			<select class="form-control" name="parent" id="module_parent">
																			<option value="0">None</option>


																			@if($active_modules && count($active_modules) > 0)
																			@foreach($active_modules as $row)

																			@php  $pt = $row['parent'];   @endphp
																			<option value="{{ $pt['id'] }}">{{ $pt['name'] }}</option>
																			@endforeach
																			@endif
																			</select>
																			

																		
																		</div>
																	</div>
																	<div class="col">
																		<div class="form-group">
																			<label>Status <span class="text-red">*</span></label>
																	
																			{!! Form::select('is_active', array('1' => 'Active', '0' => 'Inactive'), '1',['class' => 'form-control','required','id'=>'module_status']); !!}
																		</div>
																	</div>
																	
																	
																</div>
																
																
															</div>
														</div>
														
														<div class="row">
															<div class="col d-flex justify-content-end">
															<input class="btn btn-primary" type="submit" id="frontval" value="Save Changes">
															</div>
														</div>
													
												</div>
												{{Form::close()}}
											</div>
										</div>
									</div>
								</div>


								<div class="modal fade" role="dialog" tabindex="-1" id="view-module">
									<div class="modal-dialog modal-lg" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title">View Module</h5>
												<button type="button" class="close" data-dismiss="modal">
													<span aria-hidden="true">×</span>
												</button>
											</div>
											<div class="modal-body">
												
												<div class="py-1">
													
														<div class="row">
															<div class="col">
																<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label class="form-label view">Module Name: </label>
																			
																			<p class="view_value" id="module_name_view"></p>
																		</div>
																	</div>
																	<div class="col">
																		<div class="form-group">
																			<label class="form-label view">Class: </label>
																			
																			<p class="view_value" id="module_class_view"></p>
																		</div>
																	</div>
																	
																</div>
																<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label class="form-label view">Slug: </label>
																		
																				<p class="view_value" id="module_link_view"></p>
																		</div>
																	</div>
																	<!-- <div class="col">
																		<div class="form-group">
																			<label class="form-label view">Sort Order: </label>
																		
																			<p class="view_value" id="module_order_view"></p>
																		</div>
																	</div> -->
																	<div class="col">
																		<div class="form-group">
																			<label>Menu Icon <span class="text-red">*</span></label>
																			<p class="view_value" id="menu_icon_view"></p>
																			
																		</div>
																	</div>
																	
																</div>
																<div class="row">
																	
																	<div class="col">
																		<div class="form-group">
																			<label class="form-label view">Status: </label>
																	<p class="view_value" id="module_status_view"></p>
																			
																		</div>
																	</div>
																	<div class="col">
																		<div class="form-group">
																			<!-- <label>Parent <span class="text-red">*</span></label>
																			<p class="view_value" id="module_parent_view"></p> -->
																		
																		</div>
																	</div>
																	
																</div>
																
																
															</div>
														</div>
														
														<div class="row">
															<div class="col d-flex justify-content-end">
															
															</div>
														</div>
													
												</div>
											
											</div>
										</div>
									</div>
								</div>

								<!-- sort order -->
								 <div id="sort-modal" class="modal fade">
                            <div class="modal-dialog modal-confirm">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title">Change Order</h3>  
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    </div>
                                  <form  action="{{route('modules.sort-order')}}" method="POST" >
                                  @csrf
                                    <div class="modal-body">
                                   



                                        @if($modules && count($modules) > 0)
                                      <input type = "hidden" name="new_order" id="new_order" /> 
                                      <ul id="sortable-row" style="    padding: 10px 30px 10px 20px;">
                    											@foreach($modules as $row)

                    											@php  $pt = $row['parent'];  $child = $row['child']; @endphp

                                                                
                                            <li id="{{$pt['id']}}" class="font-weight-bold">{{$pt['module_name']}}</li>
                                            @if($child && count($child) > 0) 
                                
                                @foreach($child as $ch) 
                                <li id="{{$ch['id']}}" class="pl-5" >{{ "- ".$ch['module_name']}}</li>
                                @endforeach
                                @endif

                                          @endforeach
                                      </ul>
                                      @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                        <button type="submit" id="" onClick="saveOrder();" class="btn btn-success"> Save </button>
                                    </div>
                                  </form>
                                </div>
                            </div>
                        </div>

					</div>
				</div><!-- end app-content-->
            </div>

<style type="text/css">
	
.view_value {
	font-size: 15px;
	color: #000;
}
label.view {
	font-size: 14px;
	color: #000;
	font-weight: 600;
}

        #sortable-row { list-style: none; color: black; }
        #sortable-row li { margin-bottom:4px; padding:10px; background-color:#BBF4A8;cursor:move;}
        #sortable-row li.ui-state-highlight { height: 1.0em; background-color:#F0F0F0;border:#ccc 2px dotted;}
        .modal-open 
        {
        overflow: scroll;
        }
    </style>
@endsection
@section('js')
 <script src="{{URL::asset('admin/assets/js/datatable/tables/modules-datatable.js')}}"></script> 
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{URL::asset('admin/assets/js/datatable/datatables.min.js')}}"></script> 
<script src="{{URL::asset('admin/assets/js/jquery.validate.min.js')}}"></script>
<script type="text/javascript">

 function saveOrder() 
    {
      var module_alt = new Array();
      $('ul#sortable-row li').each(function() 
      {
          module_alt.push($(this).attr("id"));
      });
      document.getElementById("new_order").value = module_alt;
    }

	jQuery(document).ready(function(){

 $( "#sortable-row" ).sortable(
      {
        placeholder: "ui-state-highlight"
      }); 


jQuery(".editmodule").click(function(){

	jQuery("#user-form-modal .modal-title").text("Edit Module");

var moduleid = jQuery(this).parents("tr").find("#moduleid").data("value");
var module_name = jQuery(this).parents("tr").find("#module_name").data("value");
var module_link = jQuery(this).parents("tr").find("#module_link").data("value");
var module_class = jQuery(this).parents("tr").find("#module_class").data("value");
var module_parent = jQuery(this).parents("tr").find("#moduleid").data("parent");
var module_status = jQuery(this).parents("tr").find("#module_status").data("value");
// var module_order = jQuery(this).parents("tr").find("#module_order").data("value");
var menu_icon = jQuery(this).parents("tr").find("#menu_icon").data("value");

jQuery("#userForm #moduleid").val(moduleid);
jQuery("#userForm #module_name").val(module_name);
jQuery("#userForm #module_link").val(module_link);
jQuery("#userForm #module_class").val(module_class);
jQuery("#userForm #module_parent").val(module_parent);
jQuery("#userForm #module_status").val(module_status);
// jQuery("#userForm #module_order").val(module_order);
jQuery("#userForm #menu_icon").val(menu_icon);


});

jQuery(".viewmodule").click(function(){


var moduleid = jQuery(this).parents("tr").find("#moduleid").data("value");

var module_name = jQuery(this).parents("tr").find("#module_name").data("value");

	jQuery("#view-module .modal-title").text(module_name);

var module_link = jQuery(this).parents("tr").find("#module_link").data("value");
var module_class = jQuery(this).parents("tr").find("#module_class").data("value");
var module_parent = jQuery(this).parents("tr").find("#moduleid").data("parent");
var module_status = jQuery(this).parents("tr").find("#module_status").data("value");
// var module_order = jQuery(this).parents("tr").find("#module_order").data("value");
var menu_icon = jQuery(this).parents("tr").find("#menu_icon").data("value");
if(module_status == 1) {
	module_status = "Active";
}else {
	module_status = "Inactive";
}


jQuery("#view-module #module_name_view").text(module_name);
jQuery("#view-module #module_link_view").text(module_link);
jQuery("#view-module #module_class_view").text(module_class);
// jQuery("#view-module #module_parent_view").text(module_parent);
jQuery("#view-module #module_status_view").text(module_status);
// jQuery("#view-module #module_order_view").text(module_order);
jQuery("#view-module #menu_icon_view").text(menu_icon);


});

jQuery(".addmodule").click(function(){

jQuery("#user-form-modal .modal-title").text("Create Module");
jQuery("#userForm #moduleid").val(0);
$("#userForm").trigger("reset");

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
        
        $(".ser_status").on("click", function(e){
        
        var selid = jQuery(this).data("selid");
        
        var sestatus='0';
        if($(this).prop('checked') == true)
        {
        sestatus='1';
        }
        
        $.ajax({
        type: "POST",
        url: '{{url("/admin/modules/status")}}',
        data: { "_token": "{{csrf_token()}}", id: selid,status:sestatus},
        success: function (data) {
        // alert(data);
        if(data ==1) {
        if(sestatus ==1) {
        	jQuery('#status-'+selid).closest("td").attr("data-search","Active");
              toastr.success("Module activated successfully.");   
            }else {
            	jQuery('#status-'+selid).closest("td").attr("data-search","Inactive");
               toastr.success("Module deactivated successfully.");  
            } 
             var table = $.fn.dataTable.tables( { api: true } );
            table.rows().invalidate().draw();
        }else {
        toastr.error("Failed to update status."); 	
        }
        
        
        }
        });
        
        
        });
        

$("#frontval").click(function(){

$("#userForm").validate({
rules: {

module_name : {
required: true
},

class: {
required: true
},
link: {
required: true
}

},

messages : {
module_name: {
required: "Module Name is required."
},
class: {
required: "Class is required."
},
link: {
required: "Slug is required."
}

},
 errorPlacement: function(error, element) {
 	 $("#errNm1").empty();
            if (element.attr("name") == "ofr_code" ) {
                $("#errNm1").text($(error).text());
                
            }else {
               error.insertAfter(element)
            }
        },

});
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