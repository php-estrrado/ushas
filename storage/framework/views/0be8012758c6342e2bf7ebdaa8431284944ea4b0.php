
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


		<!-- INTERNAl WYSIWYG Editor css -->
		<link href="<?php echo e(URL::asset('admin/assets/plugins/wysiwyag/richtext.css')); ?>" rel="stylesheet" />
        <style>.imageThumb {
            max-height: 75px;
            border: 2px solid;
            padding: 1px;
            cursor: pointer;
          }
          .pip {
            display: inline-block;
            margin: 10px 10px 0 0;
          }
          .remove {
            display: block;
            background: #444;
            border: 1px solid black;
            color: white;
            text-align: center;
            cursor: pointer;
          }
          .remove:hover {
            background: white;
            color: black;
          }</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-header'); ?>
						<!--Page header-->

<?php if($currency)
{
 $id = $currency->id;
}
else
{
$id = 0;
}
?>
						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0"><?php echo e($title); ?></h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i><?php echo e($menutype); ?></a></li>

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

								<?php if(Session::has('message')): ?>

								<div class="alert alert-<?php echo e(session('message')['type']); ?>" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><?php echo e(session('message')['text']); ?></div>
								<?php endif; ?>
								<!-- <?php if($errors->any()): ?>
								<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

								<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><?php echo e($error); ?></div>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								<?php endif; ?> -->
								<div class="row flex-lg-nowrap">
									<div class="col-12 mb-3">
											<div class="card">
                                                <div class="card-body">
                                                    <form action="<?php echo e(url('admin/insert-currency/'.$id)); ?>" method="POST" enctype="multipart/form-data">
													<?php echo csrf_field(); ?>
                                                    <div class="row">
                                                        
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Select Country <span class="text-red">*</span></label>
                                                                <select class="form-control select2-show-search <?php $__errorArgs = ['country'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="country" name="country">
                                                                	<option value="">Select country</option>
                                                                    <?php $__currentLoopData = $country; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($coun->id); ?>" <?php if($currency): ?><?php if($coun->id==$currency->country_id): ?> selected <?php endif; ?>;<?php endif; ?>;><?php echo e($coun->country_name); ?></option>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </select>
                                                                <?php $__errorArgs = ['country'];
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
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Currency Name <span class="text-red">*</span></label>
                                                                <input type="text" class="form-control <?php $__errorArgs = ['currency_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" <?php if($currency): ?>value="<?php echo e($currency->currency_name); ?>"  <?php else: ?> value="<?php echo e(old('currency_name')); ?>" <?php endif; ?>;
                                                                  placeholder="Currency Name" name="currency_name" >
                                                                <?php $__errorArgs = ['currency_name'];
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
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Currency Code <span class="text-red">*</span></label>
                                                                <input type="text" style="text-transform:uppercase;" class="form-control <?php $__errorArgs = ['currency_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" <?php if($currency): ?>value="<?php echo e($currency->currency_code); ?>"  <?php else: ?> value="<?php echo e(old('currency_code')); ?>" <?php endif; ?>;  placeholder="Currency Code" name="currency_code" >
                                                                <?php $__errorArgs = ['currency_code'];
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
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Set as default <span class="text-red">*</span></label>
                                                                <div class="col-12">
                                                                	<label class="custom-control custom-radio custom-control-md col-md-6 fl">
                                                                		<input id="option1" class="custom-control-input cus_radio" 
                                                                	<?php if($currency): ?>
                                                                	<?php if($currency->is_default==1): ?>
                                                                	checked="checked" 
                                                                	 <?php endif; ?>;
                                                                	 <?php endif; ?>; 
                                                                	 name="is_default" type="radio" value="1">
                                                                		<span class="custom-control-label custom-control-label-md">Yes</span>
                                                                	</label>
                                                                	<label class="custom-control custom-radio custom-control-md col-md-6 fl">
                                                                		<input id="option2" class="custom-control-input cus_radio" name="is_default" <?php if($currency): ?>
                                                                	<?php if($currency->is_default==0): ?>
                                                                	checked="checked" 
                                                                	 <?php endif; ?>;
                                                                	 <?php else: ?>
                                                                	 checked="checked" 
                                                                	 <?php endif; ?>;
                                                                	   name="prd_option" type="radio" value="0">
                                                                		<span class="custom-control-label custom-control-label-md">No</span>
                                                                	</label>
                                                                </div>
                                                                <?php $__errorArgs = ['is_default'];
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
                                                                <div class="clr"></div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-sm-6 col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Status <span class="text-red">*</span></label>
                                                                <select class="form-control select2" name="status">
                                                                    <option value="1" <?php if($currency): ?>
                                                                	<?php if($currency->is_active==1): ?><?php echo e("selected"); ?>

                                                                	<?php endif; ?>;<?php endif; ?>;>Active</option>
                                                                    <option value="0" <?php if($currency): ?>
                                                                	<?php if($currency->is_active==0): ?><?php echo e("selected"); ?>

                                                                	<?php endif; ?>;<?php endif; ?>;>Inactive</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                                             <div class="form-group">
                                                            <label class="form-label">Flag Image <span class="text-red">*</span></label>
                                                            <input type="file" name="flag" class="dropify <?php $__errorArgs = ['flag'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> form-control  is-invalid  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept=".jpg, .png, image/jpeg, image/png"/>
                                                             <?php $__errorArgs = ['flag'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                    <span style="color: red">
                                                                        <strong><?php echo e($message); ?></strong>
                                                                    </span>
                                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        </div>
                                                         </div>
                                                        <?php if($currency): ?>
                                                        <?php if($currency->image): ?>
                                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                                            <label class="form-label">Flag Image <span class="text-red">*</span></label>
                                                            <div class="d-flex">
                                                                <img src="<?php echo e(config('app.storage_url').$currency->image); ?>" alt="<?php echo e($currency->image); ?>"  style="height: 150px; max-height:150px; width:auto;">
                                                                <input type="hidden" value="<?php echo e($currency->image); ?>" name="image_file">
                                                            </div>
                                                        </div>
                                                        <?php endif; ?>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col d-flex justify-content-end">
                                                        <a class="btn btn-secondary mt-4 mb-0 mr-2" href="<?php echo e(url('admin/currency')); ?>">Cancel</a>
                                                        <button class="btn btn-primary mt-4 mb-0 " type="submit">Submit</button>
                                                        </div>
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



					</div>
				</div><!-- end app-content-->
            </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
         <!--INTERNAL Select2 js -->
		<script src="<?php echo e(URL::asset('admin/assets/plugins/select2/select2.full.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/js/select2.js')); ?>"></script>
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

        <!-- INTERNAL WYSIWYG Editor js -->
		<script src="<?php echo e(URL::asset('admin/assets/plugins/wysiwyag/jquery.richtext.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/js/form-editor.js')); ?>"></script>



<script type="text/javascript">

$.ajaxSetup({
headers: {
'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});
// 	$('#categoryList').on('change', function () {
//     $("#subcategoryList").attr('disabled', false); //enable subcategory select
//     $("#subcategoryList").val("");
//     $(".subcategory").attr('disabled', true); //disable all category option
//     $(".subcategory").hide(); //hide all subcategory option
//     $(".parent-" + $(this).val()).attr('disabled', false); //enable subcategory of selected category/parent
//     $(".parent-" + $(this).val()).show();
// });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/qaushas/public_html/resources/views/admin/currency/create.blade.php ENDPATH**/ ?>