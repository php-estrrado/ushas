
<?php $__env->startSection('css'); ?>
		<!-- INTERNAl alert css -->
		<link href="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/sweetalert.css')); ?>" rel="stylesheet" />

        <!--INTERNAL Select2 css -->
		<link href="<?php echo e(URL::asset('admin/assets/plugins/select2/select2.min.css')); ?>" rel="stylesheet" />

        <!-- INTERNAL File Uploads css -->
		<link href="<?php echo e(URL::asset('admin/assets/plugins/fancyuploder/fancy_fileupload.css')); ?>" rel="stylesheet" />
        <!-- INTERNAL File Uploads css-->
        <link href="<?php echo e(URL::asset('admin/assets/plugins/fileupload/css/fileupload.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-header'); ?>
						<!--Page header-->


						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0">Add Category</h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Master Settings</a></li>

									<li class="breadcrumb-item active" aria-current="page"><a href="<?php echo e(route('admin.category')); ?>">Category List</a></li>
									<li class="breadcrumb-item active" aria-current="page"><a href="#">Add Category</a></li>
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

								<!--<?php if(Session::has('message')): ?>-->

								<!--<div class="alert alert-<?php echo e(session('message')['type']); ?>" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><?php echo e(session('message')['text']); ?></div>-->
								<!--<?php endif; ?>-->
								<!--<?php if($errors->any()): ?>-->
								<!--<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>-->

								<!--<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><?php echo e($error); ?></div>-->
								<!--<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>-->
								<!--<?php endif; ?>-->
								<div class="row flex-lg-nowrap">
									<div class="col-12 mb-3">
											<div class="card">
                                                <div class="card-body">
                                                    <form action="<?php echo e(url('admin/insert-category')); ?>" method="POST" id="catForm" enctype="multipart/form-data">
													<?php echo csrf_field(); ?>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Select Language <span class="text-red">*</span></label>
                                                               <select class="form-control custom-select select2" name="language" id="language" required disabled>
																<?php if($language): ?>
																<?php $__currentLoopData = $language; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																	<option value="<?php echo e($lang->id); ?>" data-defaultlang="<?php echo e($lang->is_default); ?>" <?php if(1==$lang->is_default){ echo "selected";}?>><?php echo e($lang->glo_lang_name); ?> <?php if(1==$lang->is_default){ echo "(Default)";}?> </option>
																<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
																<?php endif; ?>
																</select>
																<input type="hidden" name="language" value="<?php echo e($default_language->id); ?>">
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Name in Local Language <span class="text-red"></span></label>
                                                                <input type="text" class="form-control <?php $__errorArgs = ['local_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Name in Local Language" value="<?php echo e(old('local_name')); ?>"  name="local_name">
                                                                <?php $__errorArgs = ['local_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong><?php echo e($message); ?></strong>
                                                                    </span>
                                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                            </div>
                                                        </div>
														<div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Category Name <span class="text-red">*</span></label>
																	<input type="text" class="form-control <?php $__errorArgs = ['category_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Category" value="<?php echo e(old('category_name')); ?>"  name="category_name">
                                                                <?php $__errorArgs = ['category_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong><?php echo e($message); ?></strong>
                                                                    </span>
                                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Category Description <span class="text-red">*</span></label>
                                                                <!--<input type="text" class="form-control <?php $__errorArgs = ['category_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Description" value="<?php echo e(old('category_description')); ?>" name="category_description">-->
                                                                <textarea class="form-control <?php $__errorArgs = ['category_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Description" value="<?php echo e(old('category_description')); ?>" name="category_description"><?php echo e(old('category_description')); ?></textarea>
                                                                <?php $__errorArgs = ['category_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong><?php echo e($message); ?></strong>
                                                                    </span>
                                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Status <span class="text-red">*</span></label>
                                                                <select class="form-control select2" name="status">
                                                                    <option value="1">Active</option>
                                                                    <option value="0">Inactive</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                       <!--   <div class="col-sm-12 col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Gender <span class="text-red">*</span></label>
                                                                    <div class="row">
                                                                    <div class="col-md-2">
                                                                    <label class="custom-control custom-radio">
                                                                        <input type="radio" class="custom-control-input" name="gender" value="male" checked>
                                                                        <span class="custom-control-label">Male</span>
                                                                    </label>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                    <label class="custom-control custom-radio">
                                                                        <input type="radio" class="custom-control-input" name="gender" value="female" >
                                                                        <span class="custom-control-label">Female</span>
                                                                    </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> --> 
                                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                                            <label class="form-label">Category Image <span class="text-red">*</span></label>
                                                            <p>(Image type .png,.jpeg)</p>
                                                            <input type="file" class="dropify <?php $__errorArgs = ['category_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-height="180"  accept="image/*" data-allowed-file-extensions='["png", "jpg", "jpeg"]' name="category_image" />
                                                            <p style="color: red" id="errNm1"></p>
                                                        </div>
                                                       
                                                             <?php $__errorArgs = ['category_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong><?php echo e($message); ?></strong>
                                                                    </span>
                                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
													<div class="col-sm-12 col-md-12">
                                                            <div class="form-group m-0">
													<div class="form-label">Rating and Review</div>
													<div class="custom-controls-stacked">
														<label class="custom-control custom-checkbox">
															<input type="checkbox" class="custom-control-input" name="is_rating" value="1" >
															<span class="custom-control-label">Product in this category will have rating and reviews</span>
														</label>
														
														
                                                        </div>
                                                        </div>
                                                        </div>
                                                    <div class="col d-flex justify-content-end">
                                                    <a href="<?php echo e(route('admin.category')); ?>" class="mr-2 mt-4 mb-0 btn btn-secondary" >Cancel</a>
                                                    <button type="submit" id="frontval" class="btn btn-primary mt-4 mb-0" >Submit</button>
                                                    </div>
                                                </form>
                                                </div>
                                            </div>
                                                    <!---hhj-->


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
												<?php echo e(Form::open(array('url' => "admin/modules/save", 'id' => 'userForm', 'name' => 'userForm', 'class' => '','files'=>'true'))); ?>

												<?php echo e(Form::hidden('id',0,['id'=>'moduleid'])); ?>

												<?php echo e(Form::hidden('is_selected',0,['is_selected'=>'is_selected'])); ?>

												<div class="py-1">

														<div class="row">
															<div class="col">
																<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label>Module Name</label>

																			<?php echo Form::text('module_name', null, ['class' => 'form-control','required','id'=>'module_name']); ?>

																		</div>
																	</div>
																	<div class="col">
																		<div class="form-group">
																			<label>Class</label>

																			<?php echo Form::text('class', null, ['class' => 'form-control','required','id'=>'module_class']); ?>

																		</div>
																	</div>

																</div>
																<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label>Slug</label>

																			<?php echo Form::text('link', null, ['class' => 'form-control','required','id'=>'module_link']); ?>

																		</div>
																	</div>
																	<div class="col">
																		<div class="form-group">
																			<label>Sort Order</label>

																			<?php echo Form::text('sort', null, ['class' => 'form-control','required','id'=>'module_order']); ?>

																		</div>
																	</div>

																</div>
																<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label>Status</label>

																			<?php echo Form::select('is_active', array('1' => 'Active', '0' => 'Inactive'), '1',['class' => 'form-control','required','id'=>'module_status']); ?>

																		</div>
																	</div>
																	<div class="col">
																		<!-- <div class="form-group">
																			<label>Sort Order</label>

																			<?php echo Form::text('sort', null, ['class' => 'form-control']); ?>

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
												<?php echo e(Form::close()); ?>

											</div>
										</div>
									</div>
								</div>

					</div>
				</div><!-- end app-content-->
            </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
         <!--INTERNAL Select2 js -->
		<script src="<?php echo e(URL::asset('admin/assets/plugins/select2/select2.full.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/js/select2.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/js/jquery.validate.min.js')); ?>"></script>
	<!-- INTERNAL Popover js -->
		<script src="<?php echo e(URL::asset('admin/admin/assets/js/popover.js')); ?>"></script>

		<!-- INTERNAL Sweet alert js -->
		<script src="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/sweetalert.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/js/sweet-alert.js')); ?>"></script>

        <!-- INTERNAL File-Uploads Js-->
		<script src="<?php echo e(URL::asset('admin/assets/plugins/fancyuploder/jquery.ui.widget.js')); ?>"></script>
        <script src="<?php echo e(URL::asset('admin/assets/plugins/fancyuploder/jquery.fileupload.js')); ?>"></script>
        <script src="<?php echo e(URL::asset('admin/assets/plugins/fancyuploder/jquery.iframe-transport.js')); ?>"></script>
        <script src="<?php echo e(URL::asset('admin/assets/plugins/fancyuploder/jquery.fancy-fileupload.js')); ?>"></script>
        <script src="<?php echo e(URL::asset('admin/assets/plugins/fancyuploder/fancy-uploader.js')); ?>"></script>

		<!-- INTERNAL File uploads js -->
        <script src="<?php echo e(URL::asset('admin/assets/plugins/fileupload/js/dropify.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/js/filupload.js')); ?>"></script>
<script type="text/javascript">
	$(document).ready(function () {
  $('#category_list').addClass("active");
  $('#a_cat').addClass("active");
  $('#master').addClass("is-expanded");
    });
    
    jQuery(document).ready(function(){


$("#frontval").click(function(){

$("#catForm").validate({
	ignore: [],
rules: {

category_name : {
required: true
},

category_description: {
required: true
},
category_image: {

required: true
}

},

messages : {
category_name: {
required: "Category Name is required."
},
category_description: {
required: "Category Description is required."
},
category_image: {
required: "Category Image is required."
}
},


 errorPlacement: function(error, element) {
 	 // $("#errNm1").empty();$("#errNm2").empty();
 	 console.log($(error).text());
            if (element.attr("name") == "category_image" ) {
            	
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
            <?php if(Session::has('message')): ?>
            <?php if(session('message')['type'] =="success"): ?>
            
            toastr.success("<?php echo e(session('message')['text']); ?>"); 
            <?php else: ?>
            toastr.error("<?php echo e(session('message')['text']); ?>"); 
            <?php endif; ?>
            <?php endif; ?>
            
            <?php if($errors->any()): ?>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            toastr.error("<?php echo e($error); ?>"); 
            
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
    });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/qaushas/public_html/resources/views/admin/master/create_category.blade.php ENDPATH**/ ?>