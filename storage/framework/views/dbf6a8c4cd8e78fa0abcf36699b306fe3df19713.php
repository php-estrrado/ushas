
<?php $__env->startSection('css'); ?>
		<!-- INTERNAl Data table css -->
		<link href="<?php echo e(URL::asset('admin/assets/plugins/datatable/css/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/plugins/datatable/css/buttons.bootstrap4.min.css')); ?>"  rel="stylesheet">
		<link href="<?php echo e(URL::asset('admin/assets/plugins/datatable/responsive.bootstrap4.min.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/sweetalert.css')); ?>" rel="stylesheet" />
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
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-header'); ?>
						<!--Page header-->


						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0"><?php echo e($title); ?></h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>User Management</a></li>
									<li class="breadcrumb-item " aria-current="page"><a href="<?php echo e(url('/admin/admins-list')); ?>">User</a></li>
									<li class="breadcrumb-item active" aria-current="page"><a href="#"><?php echo e($title); ?></a></li>
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
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
						<!-- Row -->
						<div class="row flex-lg-nowrap">
							<div class="col-12">

							
								<div class="row flex-lg-nowrap">
									<div class="col-12 mb-3">
										<div class="e-panel card">
											<div class="card-body">
												<div class="e-table">
													<div class="table-responsiv table-lg mt-3">
														

														
 <?php echo e(Form::open(array('url' => "/admin/admins-list/save", 'id' => 'adminForm', 'name' => 'adminForm', 'class' => '','files'=>'true'))); ?>

        
        <div >
            <?php echo e(Form::hidden('id',0,['id'=>'id'])); ?>

          
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="fname">First name <span class="text-red">*</span></label>
                    <input type="text" class="form-control" name="user[fname]" id="fname" placeholder="First name" value="<?php if(old()): ?><?php echo e(old('user')['fname']); ?><?php endif; ?>" required>
                    <span class="error"></span>
                    <?php $__errorArgs = ['fname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p style="color: red"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="lname">Last name <span class="text-red">*</span></label>
                    <input type="text" class="form-control" name="user[lname]" id="lname" placeholder="Last name" value="<?php if(old()): ?><?php echo e(old('user')['lname']); ?><?php endif; ?>" required>
                    <span class="error"></span>
                    <?php $__errorArgs = ['lname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p style="color: red"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="email">Email <span class="text-red">*</span></label>
                    <input type="email" class="form-control" name="user[email]" id="email" placeholder="Email" value="<?php if(old()): ?><?php echo e(old('user')['email']); ?><?php endif; ?>" required>
                    <span class="error"></span>
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p style="color: red"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="phone">Phone <span class="text-red">*</span></label>
                    
                            <div class="row">
                            <div class="col-3 pr-0">
                            <select id="isd_code" name="user[isd_code]" class="form-control p-1" >
                            <?php if($c_code): ?> <?php $__currentLoopData = $c_code; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row=>$isd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                            <?php if($isd == 91){ $selected = 'selected'; }else{ $selected = ''; } ?>

                            <option value="<?php echo e($isd); ?>" <?php echo e($selected); ?>>+<?php echo e($isd); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php endif; ?>
                            </select>
                            </div>
                            <div class="col-9 pl-0">
                            <input type="text" class="form-control" name="user[phone]" id="phone" placeholder="Phone" value="<?php if(old()): ?><?php echo e(old('user')['phone']); ?><?php endif; ?>" required>
                            <span class="error"></span>
                            </div>
                        </div>
                         <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p style="color: red"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
            </div>
          

            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="password">Password <span class="text-red">*</span></label>
                    <input type="password" class="form-control" name="user[password]" id="password" data-strength placeholder="Password"  value="<?php if(old()): ?><?php echo e(old('user')['password']); ?><?php endif; ?>" >
                    <span toggle="#password" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                    <span class="error"></span>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p style="color: red"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="avatar">Avatar</label>
                    <?php echo e(Form::file('avatar',['id'=>'avatar','class'=>'form-control', 'accept'=>"image/*"])); ?>

                </div>
                <div class="col-md-6 mb-3">
                    <label for="avatar">Status <span class="text-red">*</span></label>
                    <?php echo e(Form::select('user[is_active]',['1'=>'Active','0'=>'Inactive'],1,['id'=>'is_active','class'=>'form-control'])); ?>

                </div>
                <div class="col-md-6 mb-3">
                    <img id="avatar_img" src="<?php echo e(url('storage/app/public/no-avatar.png')); ?>" alt="avatar" style="height: 120px;" />
                </div>
            </div>


<div class="form-row">
<div class="col-md-6 mb-3">
<label for="email">Role <span class="text-red">*</span></label>
<select class="form-control custom-select select2" name="user[role_id]" id="role_id" required  >
<option value="">Select Role</option>
<?php if($roles && count($roles) > 0): ?>
<?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<option value="<?php echo e($role->id); ?>" <?php if(old()){ if(old('user')['role_id']== $role->id){ echo 'selected'; } } ?>><?php echo e($role->usr_role_name); ?></option>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>
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
															<a href="<?php echo e(url('/admin/admins-list')); ?>"  class="mr-2 btn btn-secondary" >Cancel</a>           
            <input id="save_btn" type="submit" class="btn btn-primary" style="float:right;" value="Save">
															</div>
														</div>
            
        </div>
    <?php echo e(Form::close()); ?>

													

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
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
		<!-- INTERNAl Data tables -->
		<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/js/jquery.dataTables.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/js/dataTables.bootstrap4.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/js/dataTables.buttons.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/js/buttons.bootstrap4.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/js/jszip.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/js/pdfmake.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/js/vfs_fonts.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/js/buttons.html5.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/js/buttons.print.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/js/buttons.colVis.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/dataTables.responsive.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/responsive.bootstrap4.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/js/datatables.js')); ?>"></script>
	<!-- INTERNAL Popover js -->
		<script src="<?php echo e(URL::asset('admin/assets/js/popover.js')); ?>"></script>
<script src="<?php echo e(URL::asset('admin/assets/js/jquery.validate.min.js')); ?>"></script>
		<!-- INTERNAL Sweet alert js -->
		<script src="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/sweetalert.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/js/sweet-alert.js')); ?>"></script>
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

jQuery.validator.addMethod("phone", function (phone_number, element) {
        phone_number = phone_number.replace(/\s+/g, "");
        return this.optional(element) || phone_number.length >= 7 && phone_number.length < 13 &&
              phone_number.match(/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{1})/);
    }, "Phone Number should be 7-12 digit numbers.");

jQuery.validator.addMethod("lettersonly", function(value, element) 
{
return this.optional(element) || /^[a-z ]+$/i.test(value);
}, "Please enter valid name.");

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
"user[password]" : {
required: true,
maxlength: 15,
minlength: 6

},
"user[role_id]" : {
required: true
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
"user[password]": {
required: "Password is required."
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
    // $(document).ready(function(){
    //         <?php if(Session::has('message')): ?>
    //         <?php if(session('message')['type'] =="success"): ?>
            
    //         toastr.success("<?php echo e(session('message')['text']); ?>"); 
    //         <?php else: ?>
    //         toastr.error("<?php echo e(session('message')['text']); ?>"); 
    //         <?php endif; ?>
    //         <?php endif; ?>
            
    //         <?php if($errors->any()): ?>
    //         <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    //         toastr.error("<?php echo e($error); ?>"); 
            
    //         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    //         <?php endif; ?>
    // });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/qaushas/public_html/resources/views/admin/admins/create.blade.php ENDPATH**/ ?>