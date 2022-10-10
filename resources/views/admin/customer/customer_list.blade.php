
@extends('layouts.admin')
@section('css')
		<!-- INTERNAl Data table css -->
		<link href="{{URL::asset('admin/assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}"  rel="stylesheet">
		<link href="{{URL::asset('admin/assets/plugins/datatable/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />
        <!--INTERNAL Select2 css -->
		<link href="{{URL::asset('admin/assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
		<style>
		    .field-icon {
    float: right;
    right: 18px !important;
    bottom: 108px;
    position: absolute;
    z-index: 2;
    color: black;}
		</style>
@endsection
@section('page-header')
						<!--Page header-->


						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0">Customer List</h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Customer</a></li>

									<li class="breadcrumb-item active" aria-current="page"><a href="#">Customer List</a></li>
								</ol>
							</div>
							<div class="page-rightheader">
								<div class="btn btn-list">
									<!-- <a href="#" class="btn btn-info"><i class="fe fe-settings mr-1"></i> General Settings </a>
									<a href="#" class="btn btn-danger"><i class="fe fe-printer mr-1"></i> Print </a> -->
									<a data-toggle="modal" data-target="#SignUp"   class="btn btn-primary addmodule"><i class="fe fe-plus mr-1"></i> Add New</a>
								</div>
							</div>
						</div>
                        <!--End Page header-->
@endsection
@section('content')
						<!-- Row -->
						<div class="row flex-lg-nowrap">
							<div class="col-12">

								@if(Session::has('message'))

								<div class="alert alert-{{session('message')['type']}}" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>{{session('message')['text']}}</div>
								@endif
								@if ($errors->any())
								@foreach ($errors->all() as $error)

								<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>{{$error}}</div>
								@endforeach
								@endif
								<div class="row flex-lg-nowrap">
									<div class="col-12 mb-3">
										<div class="e-panel card">
											<div class="card-body">
												<div class="e-table">
													<div class="table-responsive table-lg mt-3">
														<table id="customer-table" class="customer-table table table-striped table-bordered w-100 text-nowrap">
															<thead>
																<tr>
																	<th class="align-top border-bottom-0 wd-5"></th>
																	<th class="border-bottom-0 w-30">Customer ID</th>
																	<th class="border-bottom-0 w-30">Customer Name</th>
																	<th class="border-bottom-0 w-30">Contact number</th>
                                                                    <th class="border-bottom-0 w-30">Email</th>
																	<th class="border-bottom-0 w-20">Created On</th>
																	<th class="border-bottom-0 w-30 notexport">Actions</th>
																</tr>
															</thead>

															<tbody>

																@if($customer && count($customer) > 0)
                    											@foreach($customer as $row)
                                                               <?php   
                                                                        $user_id=$row->id;
                                                                       $cust_id = date('y',strtotime($row->created_at)).date('m',strtotime($row->created_at)).str_pad($user_id, 6, "0", STR_PAD_LEFT);
                                                                       $name=DB::table('usr_info')->where('user_id', $user_id)->first();
                                                                       ?>
																<tr>
																	<td class="align-middle select-checkbox" id="" data-value="{{$row->id}}">
																		<label class="custom-control custom-checkbox">
																		</label>
																	</td>
																	<td class="align-middle">
																		<div class="d-flex">
																		<h6 class=" font-weight-bold">{{$cust_id}}</h6>
                                                                        </div>
																	</td>
																	<td class="align-middle">
																		<div class="d-flex">
																		<h6 class=" font-weight-bold">{{$name->first_name." ".$name->middle_name." ".$name->last_name}}</h6>
                                                                        </div>
																	</td>
																	<td class="text-nowrap align-middle"><p style="overflow: hidden;white-space: nowrap;text-overflow: ellipsis; max-width: 100px;"> </p></td>
                                                                        <td class="text-nowrap align-middle"><p style="overflow: hidden;white-space: nowrap;text-overflow: ellipsis; max-width: 200px;"></p>
																	</td>
																	<!--<td class="text-nowrap align-middle">-->
																	<!--    <div class="switch">-->
                 <!--                                                           <input class="switch-input status-btn ser_status" data-selid="{{$row->id}}" id="status-{{$row->id}}"  data-id="{{ $row->id }}" type="checkbox"  @if($row->is_active ==1) {{ "checked" }} @endif>-->
                 <!--                                                           <label class="switch-paddle" for="status-{{$row->id}}">-->
                 <!--                                                               <span class="switch-active" aria-hidden="true">Active</span>-->
                 <!--                                                               <span class="switch-inactive" aria-hidden="true">Inactive</span>-->
                 <!--                                                           </label>-->
                 <!--                                                       </div>-->
																	<!--</td>-->
																	<td class="text-nowrap align-middle"><span>{{date('d M Y',strtotime($row->created_at))}}</span></td>
                                                                    <td class="align-middle">
																		<div class="btn-group align-top">
																			<a href="{{ url('admin/customer/view/') }}/{{$row->id}}"   class="btn btn-sm btn-primary mr-2"><i class="fe fe-eye mr-1"></i> view</a>
																			{{-- <button  class="btn btn-sm btn-secondary deletecategory" type="button" onclick="delete_cat({{ $row->id}})" data-toggle="modal" data-target="#del_modal"><i class="fe fe-trash-2"></i>Delete</button> --}}
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




					</div>
				</div><!-- end app-content-->
            </div>

            <!-- Modal regsiter -->
			<div id="SignUp" class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg">

                <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title text-center">Add New Customer</h3>
                            <button type="button" class="close" data-dismiss="modal">×</button>

                        </div>
                        <div class="modal-body" style="overflow: hidden;">
                            <div class="alert alert-danger" role="alert" style="display: none">
                            </div>
                            {{-- <div id="success-msg" class="" style="">
                                <div class="alert alert-info alert-dismissible fade in" role="alert">
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                  </button>
                                  <strong>Success!</strong> Check your mail for login confirmation!!
                                </div>
                            </div> --}}

                            <div class="col-md-offset-1 col-md-12">
                                <form method="POST" id="Register" enctype="multipart/form-data">
                                   @csrf
                                <div class="row">
                                    <div class="col-md-6 col-lg-6">
                                    <div class="form-group has-feedback">
                                        <label class= "form-label">Name<span class="text-red">*</span></label>
                                        <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control" placeholder="Name">

                                    </div>
                                    <!-- <div class="form-group has-feedback">
                                        <label class= "form-label">Last Name<span class="text-red">*</span></label>
                                        <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control" placeholder="Last name">

                                    </div> -->
                                    <div class="form-group has-feedback">
                                        <label class= "form-label">Contact Number<span class="text-red">*</span></label>
                                        <div class="input-group">
                                        <div class="input-group-append" style="width:20%"><select class="form-control select2-show-search" name="country_code" id="country_code">
                                            @foreach($countries as $key)
                                            <option value="{{$key->phonecode}}">+{{$key->phonecode}}</option>
                                            @endforeach
                                            </select></div>
                                        <input type="number" name="number" min="0" value="{{ old('number') }}" class="form-control" placeholder="Contact number">
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback">
                                        <label class= "form-label">Status<span class="text-red">*</span></label>
                                        <select class="form-control select2" name="status">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                     <input type="file" id="profile_img" class="form-control"  name="profile_img" hidden />
                                    <!--<div class="form-group has-feedback">-->
                                    <!--<label class="form-label">Profile Image <span class="text-red"></span></label>-->
                                   
                                    <!--</div>-->
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group has-feedback">
                                        <label class= "form-label">Role<span class="text-red">*</span></label>
                                        <select class="form-control select2" name="role" readonly>
                                            <option value="5">Customer</option>
                                           
                                        </select>
                                    </div>
                                    <div class="form-group has-feedback">
                                        <label class= "form-label">Email<span class="text-red">*</span></label>
                                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Email">

                                    </div>
                                    <div class="form-group has-feedback">
                                        <label class= "form-label">Password<span class="text-red">*</span></label>
                                        <input type="password" name="password" id="password" class="form-control" data-strength placeholder="Password">
                                        <span toggle="#password" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                                    </div>
                                    <div class="form-group has-feedback">
                                        <label class= "form-label">Reference Code<span class="text-red"></span></label>
                                        <input type="text" name="ref_code" id="ref_code" maxlength="10" class="form-control" placeholder="Reference Code">
                                    </div>
                                </div><!---col--->
                            </div><!---row-->

                                    <div class="row">
                                        <div class="col-xs-12 col-md-12 justify-content-end">
                                          <button type="submit" id="submitForm" class="btn btn-primary btn-prime white btn-flat fr">Save</button>
                                        </div>
                                    </div>




                        </form>
                        </div>
                    </div>

                </div>
            </div></div>
@endsection
@section('js')
		<!-- INTERNAl Data tables -->
		<script src="{{URL::asset('admin/assets/js/datatable/tables/customer-table.js')}}"></script>


        <!--INTERNAL Select2 js -->
		<script src="{{URL::asset('admin/assets/plugins/select2/select2.full.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/select2.js')}}"></script>
<script src="{{URL::asset('admin/assets/js/jquery.validate.min.js')}}"></script>
	<!-- INTERNAL Popover js -->
		<script src="{{URL::asset('admin/assets/js/popover.js')}}"></script>

		<!-- INTERNAL Sweet alert js -->
		<script src="{{URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/sweet-alert.js')}}"></script>
        <script type="text/javascript">
           
        </script>
<script type="text/javascript">

	$(document).ready(function(){
jQuery.validator.addMethod("lettersonly", function(value, element) {
  return this.optional(element) || /^[a-z ]+$/i.test(value);
}, "Please enter valid name."); 

 $('body').on('click', '#submitForm', function(e){
     

$("#Register").validate({
    
rules: {

first_name : {
required: true,
lettersonly: true 
},

last_name: {
required: true,
lettersonly: true
},
number: {
required: true,
number: true,
minlength:7,
maxlength:15
},
email : {
required: true,
email: true,
},


password: {
required: true,
minlength:8,
maxlength:20
},
ref_code: {
minlength:5,
maxlength:10
},

},

messages : {
first_name: {
required: "First Name is required."
},
last_name: {
required: "Last Name is required."
},
number: {
required: "Contact Number is required.",
minlength: "Contact Number is invalid",
maxlength: "Contact Number is invalid"
},
email: {
required: "Email is required."
},
password: {
required: "Password is required.",
minlength: "Password must be greater than 8 digits.",
maxlength: "Password must be less than 20 digits."
},
ref_code: {
minlength: "Code must be greater than 5 characters.",
maxlength: "Code must be less than 10 characters."
},


},

submitHandler: function (form) {
       $url='{{ route("customeregister") }}';
                var registerForm = $("#Register");
                var formData = registerForm.serialize();
               // var files = $('#profile_img')[0].files[0];
                $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                  }
              });
              var Imagedata = new FormData();
                jQuery.each(jQuery('#profile_img')[0].files, function(i, file) {
                    Imagedata.append('file-'+i, file);
                });
                $.ajax({
                    url:$url,
                    type:'POST',
                    data:formData+Imagedata,
                    success:function(data) {
                        //$( '#name-error' ).html( data.errors.name);
                        console.log(data);
                        if(data.errors) {
                            jQuery('.alert-danger').html('');

                  		jQuery.each(data.errors, function(key, value){
                  			jQuery('.alert-danger').show();
                  			jQuery('.alert-danger').append('<li>'+value+'</li>');
                  		});

                        }
                        if(data['success']) {
                             toastr.success("New customer registered Successfully");
                            location.reload();
                        }
                    },
                });
             return false; 
    }

});
});

});

 $('body').on('click', '#submitForm', function(){
                
            });
            
	function delete_cat(cat_id){
       // alert(cat_id);
       $('#del_modal').show();
       $('#ok_button').click(function(){
        $.ajax({
            type: "POST",
            url: '{{url("/admin/delete-category/")}}',
            data: { "_token": "{{csrf_token()}}", cat_id: cat_id},
            success: function (data) {
                location.reload();

            }
        });
    });
    }

    $(function() {
    $('.ser_status').change(function() {
        var status = $(this).prop('checked') == true ? 1 : 0;
        var cus_id = $(this).data('id');

        $.ajax({
            type: "POST",
            url: '{{url("/admin/customer/change-status")}}',
            data: { "_token": "{{csrf_token()}}", cus_id: cus_id,status: status},
            success: function (data) {
                console.log(data.success)

            }
        });
        if(status!=true)
        { toastr.success("Inactivated Successfully");
        $(this).prop("");
        }else{ toastr.success("Activated Successfully"); }
    })
  })
</script>

@endsection
