

<?php $__env->startSection('css'); ?>
		<!-- INTERNAl Data table css -->
		<link href="<?php echo e(URL::asset('admin/assets/plugins/datatable/css/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/plugins/datatable/css/buttons.bootstrap4.min.css')); ?>"  rel="stylesheet">
		<link href="<?php echo e(URL::asset('admin/assets/plugins/datatable/responsive.bootstrap4.min.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/sweetalert.css')); ?>" rel="stylesheet" />
        <!--INTERNAL Select2 css -->
		<link href="<?php echo e(URL::asset('admin/assets/plugins/select2/select2.min.css')); ?>" rel="stylesheet" />
		<style>
		    .field-icon {
    float: right;
    right: 18px !important;
    bottom: 108px;
    position: absolute;
    z-index: 2;
    color: black;}
		</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-header'); ?>
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
									<!-- <a data-toggle="modal" data-target="#SignUp"   class="btn btn-primary addmodule"><i class="fe fe-plus mr-1"></i> Add New</a> -->
								</div>
							</div>
						</div>
                        <!--End Page header-->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
						<!-- Row -->
						<div class="row flex-lg-nowrap">
							<div class="col-12">

								<?php if(Session::has('message')): ?>

								<div class="alert alert-<?php echo e(session('message')['type']); ?>" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><?php echo e(session('message')['text']); ?></div>
								<?php endif; ?>
								<?php if($errors->any()): ?>
								<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

								<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><?php echo e($error); ?></div>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								<?php endif; ?>
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
                                                                    <th class="border-bottom-0 w-30">Customer Category</th>
																	<th class="border-bottom-0 w-20">Created On</th>
																	<th class="border-bottom-0 w-30 notexport">Actions</th>
																</tr>
															</thead>

															<tbody>

																<?php if($customer && count($customer) > 0): ?>
                    											<?php $__currentLoopData = $customer; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                               <?php   
                                                                        $user_id=$row->id;
                                                                       $cust_id = date('y',strtotime($row->created_at)).date('m',strtotime($row->created_at)).str_pad($user_id, 6, "0", STR_PAD_LEFT);
                                                                       $name=DB::table('usr_info')->where('user_id', $user_id)->first();
                                                                       ?>
																<tr>
																	<td class="align-middle select-checkbox" id="" data-value="<?php echo e($row->id); ?>">
																		<label class="custom-control custom-checkbox">
																		</label>
																	</td>
																	<td class="align-middle">
																		<div class="d-flex">
																		<h6 class=" font-weight-bold"><?php echo e($cust_id); ?></h6>
                                                                        </div>
																	</td>
																	<td class="align-middle">
																		<div class="d-flex">
                                                                        <h6 class=" font-weight-bold"><?php echo e($row->first_name." ".$row->middle_name." ".$row->last_name); ?></h6>
																		<!-- <h6 class=" font-weight-bold"><?php echo e(@$name->first_name." ".@$name->middle_name." ".@$name->last_name); ?></h6> -->
                                                                        </div>
																	</td>
																	<td class="text-nowrap align-middle"><p style="overflow: hidden;white-space: nowrap;text-overflow: ellipsis; max-width: 100px;"><?php echo e($row->usr_telecom_value); ?></p></td>
                                                                        <td class="text-nowrap align-middle"><p style="overflow: hidden;white-space: nowrap;text-overflow: ellipsis; max-width: 200px;"><?php echo e($row->username); ?></p>
																	</td>
																	<!--<td class="text-nowrap align-middle">-->
																	<!--    <div class="switch">-->
                 <!--                                                           <input class="switch-input status-btn ser_status" data-selid="<?php echo e($row->id); ?>" id="status-<?php echo e($row->id); ?>"  data-id="<?php echo e($row->id); ?>" type="checkbox"  <?php if($row->is_active ==1): ?> <?php echo e("checked"); ?> <?php endif; ?>>-->
                 <!--                                                           <label class="switch-paddle" for="status-<?php echo e($row->id); ?>">-->
                 <!--                                                               <span class="switch-active" aria-hidden="true">Active</span>-->
                 <!--                                                               <span class="switch-inactive" aria-hidden="true">Inactive</span>-->
                 <!--                                                           </label>-->
                 <!--                                                       </div>-->
																	<!--</td>-->
																	<td class="text-nowrap align-middle">
																		<?php if($row->crm_customer_type == 1): ?>
																		<?php else: ?>
																			SP
																		<?php endif; ?>
																	</td>
																	<td class="text-nowrap align-middle"><span><?php echo e(date('d M Y',strtotime($row->created_at))); ?></span></td>
																	<td>
																			<a href="<?php echo e(url('admin/customer/view/')); ?>/<?php echo e($row->id); ?>"   class="btn btn-sm btn-primary mr-2"><i class="fe fe-eye mr-1"></i> view</a>
																			
																		</div>
																	</td>
																</tr>
																     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                      <?php endif; ?>
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
                            

                            <div class="col-md-offset-1 col-md-12">
                                <form method="POST" id="Register" enctype="multipart/form-data">
                                   <?php echo csrf_field(); ?>
                                <div class="row">
                                    <div class="col-md-6 col-lg-6">
                                    <div class="form-group has-feedback">
                                        <label class= "form-label">Name<span class="text-red">*</span></label>
                                        <input type="text" name="first_name" value="<?php echo e(old('first_name')); ?>" class="form-control" placeholder="Name">

                                    </div>
                                    <!-- <div class="form-group has-feedback">
                                        <label class= "form-label">Last Name<span class="text-red">*</span></label>
                                        <input type="text" name="last_name" value="<?php echo e(old('last_name')); ?>" class="form-control" placeholder="Last name">

                                    </div> -->
                                    <div class="form-group has-feedback">
                                        <label class= "form-label">Contact Number<span class="text-red">*</span></label>
                                        <div class="input-group">
                                        <div class="input-group-append" style="width:20%"><select class="form-control select2-show-search" name="country_code" id="country_code">
                                            <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($key->phonecode); ?>">+<?php echo e($key->phonecode); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select></div>
                                        <input type="number" name="number" min="0" value="<?php echo e(old('number')); ?>" class="form-control" placeholder="Contact number">
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
                                        <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="form-control" placeholder="Email">

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
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
		<!-- INTERNAl Data tables -->
		<script src="<?php echo e(URL::asset('admin/assets/js/datatable/tables/customer-table.js')); ?>"></script>


        <!--INTERNAL Select2 js -->
		<script src="<?php echo e(URL::asset('admin/assets/plugins/select2/select2.full.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/js/select2.js')); ?>"></script>
<script src="<?php echo e(URL::asset('admin/assets/js/jquery.validate.min.js')); ?>"></script>
	<!-- INTERNAL Popover js -->
		<script src="<?php echo e(URL::asset('admin/assets/js/popover.js')); ?>"></script>

		<!-- INTERNAL Sweet alert js -->
		<script src="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/sweetalert.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/js/sweet-alert.js')); ?>"></script>
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
       $url='<?php echo e(route("customeregister")); ?>';
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
            url: '<?php echo e(url("/admin/delete-category/")); ?>',
            data: { "_token": "<?php echo e(csrf_token()); ?>", cat_id: cat_id},
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
            url: '<?php echo e(url("/admin/customer/change-status")); ?>',
            data: { "_token": "<?php echo e(csrf_token()); ?>", cus_id: cus_id,status: status},
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

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/qaushas/public_html/resources/views/admin/customer/customer_list.blade.php ENDPATH**/ ?>