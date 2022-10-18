
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
								<h4 class="page-title mb-0">Edit Category</h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Master Settings</a></li>

									<li class="breadcrumb-item active" aria-current="page"><a href="<?php echo e(route('admin.category')); ?>">Category List</a></li>
									<li class="breadcrumb-item active" aria-current="page"><a href="#">Edit Category</a></li>
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
                                                    <form action="<?php echo e(url('admin/update-category/'.$category->category_id)); ?>" method="POST"  id="catForm" enctype="multipart/form-data">
													<?php echo csrf_field(); ?>
                                                    
                                                           <input type="hidden" value="<?php echo e($category->category_id); ?>" name="cat_id" id="cat_id">
                                                           <input type="hidden" value="<?php echo e($category->cat_name_cid); ?>" name="cat_content_id" id="cat_content_id">
                                                           <input type="hidden" value="<?php echo e($category->cat_desc_cid); ?>" name="desc_content_id" id="desc_content_id">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Select Language <span class="text-red">*</span></label>
                                                                <select class="form-control custom-select select2" name="language" id="language" required>
                                                                    <?php $__currentLoopData = $language; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($lang->id); ?>" <?php if($default_language->id==$lang->id){ echo "selected";}?>><?php echo e($lang->glo_lang_name); ?><?php if(1==$lang->is_default){ echo " (Default)";}?></option>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </select>
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
unset($__errorArgs, $__bag); ?>" placeholder="Name in Local Language" name="local_name" value="<?php echo e($category->local_name); ?>">
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
														 </div>
														 <div class="row" id="lang_content">
														<?php echo $__env->make('admin.master.includes.content', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>											
														</div>
														 <div class="row">
                                                        <div class="col-sm-12 col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Status <span class="text-red">*</span></label>
                                                                <select class="form-control select2" name="status">
                                                                    <option value="1" <?php if($category->is_active==1){ echo "selected";}?>>Active</option>
                                                                    <option value="0" <?php if($category->is_active==0){ echo "selected";}?>>Inactive</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                          <div class="col-sm-12 col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Gender <span class="text-red">*</span></label>
                                                                    <div class="row">
                                                                    <div class="col-md-2">
                                                                    <label class="custom-control custom-radio">
                                                                        <input type="radio" class="custom-control-input" name="gender" value="male" <?php if($category->gender=='male'){ echo "checked";}?>>
                                                                        <span class="custom-control-label">Male</span>
                                                                    </label>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                    <label class="custom-control custom-radio">
                                                                        <input type="radio" class="custom-control-input" name="gender" value="female" <?php if($category->gender=='female'){ echo "checked";}?>>
                                                                        <span class="custom-control-label">Female</span>
                                                                    </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                                            <label class="form-label">Category Image <span class="text-red">*</span></label>
                                                            <div class="d-flex">
                                                                <img src="<?php echo e(url('storage/app/public/category/'.$category->image)); ?>" alt="<?php echo e($category->image); ?>"  style="height: 150px; max-height:150px; width:auto;">
                                                                <input type="hidden" value="<?php echo e($category->image); ?>" name="image_file">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                                            <label class="form-label">Change Category Image <span class="text-red">*</span></label>
                                                            <p>(Image type .png,.jpeg)</p>
                                                            <input type="file" class="dropify" data-height="180"  accept="image/*" name="category_image"  data-allowed-file-extensions='["png", "jpg", "jpeg"]' />
                                                             <p style="color: red" id="errNm1"></p>
                                                        </div>
														
														<div class="col-sm-12 col-md-12">
                                                            <div class="form-group m-0">
													<div class="form-label">Rating and Review</div>
													<div class="custom-controls-stacked">
														<label class="custom-control custom-checkbox">
															<input type="checkbox" class="custom-control-input" name="is_rating" <?php echo e(($category->is_rating=="1")? "checked" : ""); ?> value="1" >
															<span class="custom-control-label">Product in this category will have rating and reviews</span>
														</label>
														
														
                                                        </div>
                                                        </div>
                                                        </div>
                                                    </div>
                                                    <div class="col d-flex justify-content-end">
                                                    <a href="<?php echo e(route('admin.category')); ?>" class="mr-2 mt-4 mb-0 btn btn-secondary" >Cancel</a>
                                                    <button type="submit"  id="frontval" class="btn btn-primary mt-4 mb-0" >Submit</button>
                                                    </div>
                                                </form>
                                                </div>
                                            </div>
                                                    <!---ttt-->


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
// category_image: {

// required: true
// }

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
   
	
	
	$('body').on('change','#language',function(){	
	var lang_id=$(this).val();
	var title_id=$("#cat_content_id").val();
	var desc_id=$("#desc_content_id").val();
	var cat_id=$("#cat_id").val();
	$.ajax({
                type: "POST",
                url: '<?php echo e(url("admin/category/content")); ?>',
                data: { lang_id:lang_id,title_id:title_id,cat_id:cat_id,desc_id:desc_id,'_token': '<?php echo e(csrf_token()); ?>'},
                success: function (data) {
                    $("#lang_content").empty().html(data);
					
					
				
			}
	});	
});
 });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\ushas-dev\resources\views/admin/master/edit_category.blade.php ENDPATH**/ ?>