
<?php $__env->startSection('css'); ?>
		<!-- INTERNAl Data table css -->
		<link href="<?php echo e(URL::asset('admin/assets/plugins/datatable/css/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/plugins/datatable/css/buttons.bootstrap4.min.css')); ?>"  rel="stylesheet">
		<link href="<?php echo e(URL::asset('admin/assets/plugins/datatable/responsive.bootstrap4.min.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/sweetalert.css')); ?>" rel="stylesheet" />
<!-- INTERNAL File Uploads css -->
		<link href="<?php echo e(URL::asset('admin/assets/plugins/fancyuploder/fancy_fileupload.css')); ?>" rel="stylesheet" />
        <!-- INTERNAL File Uploads css-->
        <link href="<?php echo e(URL::asset('admin/assets/plugins/fileupload/css/fileupload.css')); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo e(URL::asset('admin/assets/css/combo-tree.css')); ?>" rel="stylesheet" />

<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-header'); ?>
						<!--Page header-->


						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0"><?php echo e($title); ?></h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Master Settings</a></li>
									<li class="breadcrumb-item " aria-current="page"><a href="<?php echo e(url('/admin/occasions')); ?>">Occasions</a></li>
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

							<!-- 	<?php if(Session::has('message')): ?>

								<div class="alert alert-<?php echo e(session('message')['type']); ?>" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><?php echo e(session('message')['text']); ?></div>
								<?php endif; ?>
								<?php if($errors->any()): ?>
								<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

								<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><?php echo e($error); ?></div>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								<?php endif; ?> -->
								<div class="row flex-lg-nowrap">
									<div class="col-12 mb-3">
										<div class="e-panel card">
											<div class="card-body">
												<div class="e-table">
													<div class="table-responsiv table-lg mt-3">
														
<?php echo e(Form::open(array('url' => "admin/occasions/save", 'id' => 'occasionsForm', 'name' => 'occasionsForm', 'class' => '','files'=>'true'))); ?>

												<?php echo e(Form::hidden('id',0,['id'=>'id'])); ?>

												
												
														<div class="row">
															<div class="col">


																<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label class="form-label">Select Language <span class="text-red">*</span></label>
                                                                <select class="form-control custom-select select2" name="glo_lang_cid" required>
                                                                    
                                                                    <?php $__currentLoopData = $language; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($lang->id); ?>"><?php echo e($lang->glo_lang_name); ?></option>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </select>
																		</div>
																	<?php $__errorArgs = ['glo_lang_cid'];
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
																<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label class="form-label">Occasion Name <span class="text-red">*</span></label>
																			
																			<?php echo Form::text('occasion', null, ['class' => 'form-control','id'=>'occasion']); ?>

																		</div>
																	<?php $__errorArgs = ['occasion'];
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
																
																
																<div class="row">
																	<div class="col-lg-4 col-md-4 col-sm-12">
                                                           <label class="form-label">Occasions Image <span class="text-red">*</span></label>
                                                           <p>(Image type .png,.jpeg)</p>
                                                           <input type="file" class="dropify <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-height="180"  accept="image/*" name="occasion_image" data-allowed-file-extensions='["png", "jpg", "jpeg"]' />
                                                           <?php if($errors->has('occasion_image')): ?>
																	<p style="color: red"><?php echo e("Occasions Image is required."); ?></p>
																	<?php endif; ?>
                                                        </div>
                                                    
																</div>
																	<div class="row">
																<div class="col">
																		<div class="form-group">
																			<label class="form-label">Status <span class="text-red">*</span></label>
																	
																			<?php echo Form::select('is_active', array('1' => 'Active', '0' => 'Inactive'), '1',['class' => 'form-control','required','id'=>'module_status']); ?>

																		</div>
																	</div>
																</div>
															</div>
														</div>
														
														<div class="row" style="margin-top: 30px;">
															<div class="col d-flex justify-content-end">
															    <a href="<?php echo e(url('/admin/occasions')); ?>"  class="mr-2 btn btn-secondary" >Cancel</a>  
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

		<!-- INTERNAL Sweet alert js -->
		<script src="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/sweetalert.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/js/sweet-alert.js')); ?>"></script>

<!-- INTERNAL File uploads js -->
        <script src="<?php echo e(URL::asset('admin/assets/plugins/fileupload/js/dropify.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/js/filupload.js')); ?>"></script>
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
            url: '<?php echo e(url("/admin/modules/delete")); ?>',
            data: { "_token": "<?php echo e(csrf_token()); ?>", id: moduleid},
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
            // <?php if(Session::has('message')): ?>
            // <?php if(session('message')['type'] =="success"): ?>
            
            // toastr.success("<?php echo e(session('message')['text']); ?>"); 
            // <?php else: ?>
            // toastr.error("<?php echo e(session('message')['text']); ?>"); 
            // <?php endif; ?>
            // <?php endif; ?>
            
            // <?php if($errors->any()): ?>
            // <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            // toastr.error("<?php echo e($error); ?>"); 
            
            // <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            // <?php endif; ?>
    });
    </script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/qaushas/public_html/resources/views/admin/occasions/create.blade.php ENDPATH**/ ?>