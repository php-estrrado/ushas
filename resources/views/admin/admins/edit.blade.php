@extends('layouts.admin')
@section('css')
		<!-- INTERNAl Data table css -->
		<link href="{{URL::asset('admin/assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}"  rel="stylesheet">
		<link href="{{URL::asset('admin/assets/plugins/datatable/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />
		<style>
		#avatar {
		    padding:3px;
		}
		 .field-icon {
        float: right;
        right: 10px;
        top: 40px !important;
        margin-top: 0px !important;
        position: absolute;
        z-index: 2;
        color: black;
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
									<li class="breadcrumb-item " aria-current="page"><a href="{{url('/admin/admins-list')}}">User</a></li>
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

								
								<div class="row flex-lg-nowrap">
									<div class="col-12 mb-3">
										<div class="e-panel card">
											<div class="card-body">
												<div class="e-table">
													<div class="table-responsiv table-lg mt-3">
														

														
 {{ Form::open(array('url' => "/admin/admins-list/save", 'id' => 'adminForm', 'name' => 'adminForm', 'class' => '','files'=>'true')) }}
        
        <div >
            {{Form::hidden('id',$admin->id,['id'=>'id'])}}
            
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="fname">First name <span class="text-red">*</span></label>
                    <input type="text" class="form-control" name="user[fname]" id="fname" placeholder="First name" value="{{ $admin->fname }}" required>
                    <span class="error"></span>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="lname">Last name <span class="text-red">*</span></label>
                    <input type="text" class="form-control" name="user[lname]" id="lname" placeholder="Last name" value="{{ $admin->lname }}" required>
                    <span class="error"></span>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="email">Email <span class="text-red">*</span></label>
                    <input type="email" class="form-control" name="user[email]" id="email" placeholder="Email" value="{{ $admin->email }}" required>
                    <span class="error"></span>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="phone">Phone <span class="text-red">*</span></label>
                    
                            <div class="row">
                            <div class="col-3 pr-0">
                            <select id="isd_code" name="user[isd_code]" class="form-control p-1" >
                            @if($c_code) @foreach($c_code as $row=>$isd) 
                            @php if($isd == $admin->isd_code){ $selected = 'selected'; }else{ $selected = ''; } @endphp

                            <option value="{{ $isd }}" {{$selected}}>+{{$isd}}</option>
                            @endforeach @endif
                            </select>
                            </div>
                            <div class="col-9 pl-0">
                            <input type="text" class="form-control" name="user[phone]" id="phone" placeholder="Phone" value="{{ $admin->phone }}" required>
                            <span class="error"></span>
                            </div>
                        </div>
                         @error('phone')
                    <p style="color: red">{{ $message }}</p>
                    @enderror
                        </div>
            </div>
          

            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="password">Password </label>
                    <input type="password" class="form-control" name="user[password]" id="password" data-strength placeholder="Password" value="" >
                    <span toggle="#password" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                    <span class="error"></span>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="avatar">Avatar</label>
                    {{Form::file('avatar',['id'=>'avatar','class'=>'form-control', 'accept'=>"image/*"])}}
                </div>
                <div class="col-md-6 mb-3">
                    <label for="avatar">Status <span class="text-red">*</span></label>
                    {{Form::select('user[is_active]',['1'=>'Active','0'=>'Inactive'],$admin->is_active,['id'=>'is_active','class'=>'form-control'])}}
                </div>
                <div class="col-md-6 mb-3">
                	@if($admin->avatar !="" ) 
                	<img id="avatar_img" src="{{ config('app.url').'/storage'.auth()->user()->avatar }}" alt="avatar" style="height: 120px;" />
                	@else
                    <img id="avatar_img" src="{{url('storage/app/public/no-avatar.png')}}" alt="avatar" style="height: 120px;" />
                     @endif
                </div>
            </div>

            <div class="form-row">
<div class="col-md-6 mb-3">
<label for="email">Role <span class="text-red">*</span></label>
<select class="form-control custom-select select2" name="user[role_id]" id="role_id" required >
<option value="">Select Role</option>
@if($roles && count($roles) > 0)
@foreach ($roles as $role)
<option value="{{ $role->id }}" <?php if(isset($admin->role_id)) { if($admin->role_id == $role->id) { echo 'selected'; } } ?> >{{ $role->usr_role_name }}</option>
@endforeach
@endif
</select>
</div>
<div class="col-md-6 mb-3">
<!--     <label for="phone">Phone <span class="text-red">*</span></label>

<input type="text" class="form-control" name="user[phone]" id="phone" placeholder="Phone" value="" required>
<span class="error"></span> -->
</div>
</div>

        </div>
        <div >
                     <div class="row" style="margin-top: 30px;">
															<div class="col d-flex justify-content-end">
															<a href="{{url('/admin/admins-list')}}"  class="mr-2 btn btn-secondary" >Cancel</a>           
           <input id="save_btn" type="submit" class="btn btn-primary" style="float:right;" value="Save">
															</div>
														</div>     
          
        </div>
    {{Form::close()}}
													

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
												{{Form::hidden('is_selected',0,['is_selected'=>'is_selected'])}}
												<div class="py-1">
													
														<div class="row">
															<div class="col">
																<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label>Module Name</label>
																			
																			{!! Form::text('module_name', null, ['class' => 'form-control','required','id'=>'module_name']) !!}
																		</div>
																	</div>
																	<div class="col">
																		<div class="form-group">
																			<label>Class</label>
																			
																			{!! Form::text('class', null, ['class' => 'form-control','required','id'=>'module_class']) !!}
																		</div>
																	</div>
																	
																</div>
																<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label>Slug</label>
																		
																			{!! Form::text('link', null, ['class' => 'form-control','required','id'=>'module_link']) !!}
																		</div>
																	</div>
																	<div class="col">
																		<div class="form-group">
																			<label>Sort Order</label>
																			
																			{!! Form::text('sort', null, ['class' => 'form-control','required','id'=>'module_order']) !!}
																		</div>
																	</div>
																	
																</div>
																<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label>Status</label>
																	
																			{!! Form::select('is_active', array('1' => 'Active', '0' => 'Inactive'), '1',['class' => 'form-control','required','id'=>'module_status']); !!}
																		</div>
																	</div>
																	<div class="col">
																		<!-- <div class="form-group">
																			<label>Sort Order</label>
																			
																			{!! Form::text('sort', null, ['class' => 'form-control']) !!}
																		</div> -->
																	</div>
																	
																</div>
																
																
															</div>
														</div>
														
														<div class="row">
															<div class="col d-flex justify-content-end">
															<input class="btn btn-primary" type="submit" value="Save Changes">
															</div>
														</div>
													
												</div>
												{{Form::close()}}
											</div>
										</div>
									</div>
								</div>

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
<script src="{{URL::asset('admin/assets/js/jquery.validate.min.js')}}"></script>
		<!-- INTERNAL Sweet alert js -->
		<script src="{{URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/sweet-alert.js')}}"></script>
<script type="text/javascript">

if (window.File && window.FileList && window.FileReader) {
    $("#avatar").on("change", function(e) {
        $(".pip1").remove();
      var files = e.target.files,
        filesLength = files.length;
      for (var i = 0; i < filesLength; i++) {
        var f = files[i]
        var fileReader = new FileReader();
        fileReader.onload = (function(e) {
          var file = e.target;
          // $("<span class=\"pip1\">" +
          //   "<input type=\"file\" id=\"havefil\" hidden name=\"havefil[]\" value=\"" + e.target.result + "\"/>"+
          //   "<img class=\"imageThumb1\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
          //   "<br/>" +
          //   "</span>").insertAfter("#avatar");
          // $(".remove").click(function(){
          //   $(this).parent(".pip").remove();
          // });

          $("#avatar_img").attr("src",e.target.result);

          // <span class=\"remove\">Remove image</span>Old code here
          /*$("<img></img>", {
            class: "imageThumb",
            src: e.target.result,
            title: file.name + " | Click to remove"
          }).insertAfter("#avatar").click(function(){$(this).remove();});*/

        });
        fileReader.readAsDataURL(f);
      }
    });
  } else {
    alert("Your browser doesn't support to File API")
  }
  jQuery(document).ready(function(){


jQuery.validator.addMethod("lettersonly", function(value, element) 
{
return this.optional(element) || /^[a-z ]+$/i.test(value);
}, "Please enter valid name.");

jQuery.validator.addMethod("phone", function (phone_number, element) {
        phone_number = phone_number.replace(/\s+/g, "");
        return this.optional(element) || phone_number.length >= 7 && phone_number.length < 13 &&
              phone_number.match(/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{1})/);
    }, "Phone Number should be 7-12 digit numbers.");


$("#save_btn").click(function(){

$("#adminForm").validate({
	ignore: [],
rules: {

"user[fname]" : {
required: true,
lettersonly: true
},
"user[lname]" : {
required: true,
lettersonly: true
},

"user[email]": {
required: true,
email: true
},
"user[phone]": {

required: true,
phone:true,
number: true,
},

"user[role_id]" : {
required: true
},

"user[password]" : {

maxlength: 15,
minlength: 6

},
},

messages : {
"user[fname]": {
required: "First name is required."
},
"user[lname]": {
required: "Last name is required."
},

"user[email]": {
required: "Email is required."
},
"user[phone]": {
required: "Phone number is required."
},

"user[role_id]": {
required: "Role is required."
}

},


 errorPlacement: function(error, element) {
 	 // $("#errNm1").empty();$("#errNm2").empty();
 	 console.log($(error).text());
            if (element.attr("name") == "subcat_id" ) {
            	console.log("innnnnn");
                $("#errNm1").text($(error).text());
                
            }else if (element.attr("name") == "product_id" ) {
                $("#errNm2").text($(error).text());
                
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