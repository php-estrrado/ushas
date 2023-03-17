
<?php $__env->startSection('css'); ?>
		<!-- INTERNAl Data table css -->
		<link href="<?php echo e(URL::asset('admin/assets/plugins/datatable/css/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/plugins/datatable/css/buttons.bootstrap4.min.css')); ?>"  rel="stylesheet">
		<link href="<?php echo e(URL::asset('admin/assets/plugins/datatable/responsive.bootstrap4.min.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/sweetalert.css')); ?>" rel="stylesheet" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-header'); ?>
						<!--Page header-->


						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0"><?php echo e($title); ?></h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Master Settings</a></li>
									<li class="breadcrumb-item " aria-current="page"><a href="<?php echo e(url('/admin/brands')); ?>">Brands</a></li>
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
<!-- 
								<?php if(Session::has('message')): ?>

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
														




												
														<div class="row">
															<div class="col">


																<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label class="form-label view">Language: </label>
<?php
  $def_lang =DB::table('glo_lang_lk')->where('is_active', 1)->first();
        $content_table=DB::table('cms_content')->where('cnt_id', $brand['brand_name_cid'])->where('lang_id', $def_lang->id)->first();
        if($content_table){ 
        $lang_id = $content_table->lang_id;
    }
         ?>

                                                              <p class="view_value"> <?php $__currentLoopData = $language; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                              	  <?php if($lang_id==$lang->id): ?> <?php echo e($lang->glo_lang_name); ?> <?php endif; ?> 
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </p>
																		</div>
																	
																	</div>
																	
																</div>
																<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label class="form-label view">Brand Name: </label>
																			<p class="view_value"><?php echo e($brand['brand_name']); ?></p>
																		
																		</div>
																		
																	</div>
																	
																</div>
																
																<div class="row">
																	<div class="col mb-3">
																		<div class="form-group">
																			<label class="form-label view">Brand Description: </label>
																			<p class="view_value"><?php echo e($brand['brand_desc']); ?></p>
																			
																		</div>
																		
																	</div>
																</div>
																<div class="row">
																<div class="col">
																		<div class="form-group">
																			<label class="form-label view">Status: </label>
																	<p class="view_value"><?php if($brand['is_active'] ==1): ?><?php echo e("Active"); ?><?php else: ?><?php echo e("Inactive"); ?><?php endif; ?></p>
																			
																		</div>
																	</div>
																</div>
															</div>
														</div>
														
														<div class="row" style="margin-top: 30px;">
															<div class="col d-flex justify-content-end">
															     <a href="<?php echo e(url('/admin/brands')); ?>"  class="mr-2 btn btn-secondary" >Back</a>  
														
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

$(".ser_status").change(function() {

	$("#module_changed").val(1);
    
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
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/qaushas/public_html/resources/views/admin/brands/view.blade.php ENDPATH**/ ?>