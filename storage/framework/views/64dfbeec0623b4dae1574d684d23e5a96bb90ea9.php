
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
									
									<li class="breadcrumb-item active" aria-current="page"><a href="#"><?php echo e($title); ?></a></li>
								</ol>
							</div>
											<div class="page-rightheader" style="display:flex; flex-direction: row; justify-content: center; align-items: center">
								<label class="form-label" for="filterSel" style="margin-right: 8px;">Filter </label>
							    	<select class="form-control" id="filterSel" style="margin-right: 30px;">
									<option value="">All Status</option>
									<option value="Active">Active</option>
									<option value="Inactive">Inactive</option>
									</select>
								<div class="btn btn-list">
									<!-- <a href="#" class="btn btn-info"><i class="fe fe-settings mr-1"></i> General Settings </a>
									<a href="#" class="btn btn-danger"><i class="fe fe-printer mr-1"></i> Print </a> -->
									<a href="<?php echo e(url('/admin/tags/create')); ?>"   class="btn btn-primary addmodule"><i class="fe fe-plus mr-1"></i> Add New</a>
								</div>
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
										<div class="e-panel card">
											<div class="card-body">
												<div class="e-table">
													<div class="table-responsive table-lg mt-3">
														<table class="table table-bordered border-top text-nowrap tagslist" id="tagslist">
															<thead>
																<tr>
																	<th class="align-top border-bottom-0 wd-5 notexport">Select</th>
																	<th class="border-bottom-0 w-15">Tag Name</th>
																	
																	<th class="border-bottom-0 w-20">Description</th>
																	<th class="border-bottom-0 w-10">Category</th>
																	<th class="border-bottom-0 w-10">Sub Category</th>
																	
																	<th class="border-bottom-0 w-15">Created On</th>
																	<th class="border-bottom-0 w-15 notexport">Status</th>
																	<th class="border-bottom-0 w-30 hide_column" style"display:none;">Status</th>
																	<th class="border-bottom-0 w-10 notexport">Actions</th>
																</tr>
															</thead>

															<tbody>

																<?php if($tags && count($tags) > 0): ?>
                    											<?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																<tr>
																	<td class="align-middle select-checkbox" id="moduleid" data-value="<?php echo e($row['id']); ?>">
																		<label class="custom-control custom-checkbox">
																			
																			<!--<?php echo e($loop->iteration); ?>-->
																		</label>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																		<?php	$tag_name = Str::of($row['tag_name'])->limit(20); ?>
																	<h6 class=" font-weight-bold"><a href="<?php echo e(url('admin/tags/view/')); ?>/<?php echo e($row['id']); ?>" ><?php echo e($tag_name); ?> </a></h6>
																				
																			
																		</div>
																	</td>
																	<td class="text-nowrap align-middle">
																		
																			<?php	$tag_desc = Str::of($row['tag_desc'])->limit(20); ?>
																		<p><?php echo e($tag_desc); ?></p>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																			
																			
																				<p><?php echo e($row['cat_name']); ?></p>
																				
																			
																		</div>
																	</td>
																	<td class="text-nowrap align-middle">
																		<p><?php echo e($row['subcat_name']); ?></p>
																	</td>
																	<td class="text-nowrap align-middle"><span><?php echo e(date('d M Y',strtotime($row['created_at']))); ?></span></td>
																	<td class="text-nowrap align-middle"  data-search="<?php if($row['is_active'] ==1): ?><?php echo e("Active"); ?><?php else: ?><?php echo e("Inactive"); ?><?php endif; ?>">
																		<!--<label class="onswitch  ">-->
                  <!--                                                  <input class='ser_status' data-selid="<?php echo e($row['id']); ?>"  type="checkbox"  <?php if($row['is_active'] ==1): ?> <?php echo e("checked"); ?> <?php endif; ?> />-->
                  <!--                                                  <span class="slider round"></span>-->
                  <!--                                                  </label>-->
                                                                    <div class="switch">
                                                                    <input class="switch-input status-btn ser_status" data-selid="<?php echo e($row['id']); ?>"  id="status-<?php echo e($row['id']); ?>"  type="checkbox"  <?php if($row['is_active'] ==1): ?> <?php echo e("checked"); ?> <?php endif; ?> >
                                                                    <label class="switch-paddle" for="status-<?php echo e($row['id']); ?>">
                                                                    <span class="switch-active" aria-hidden="true">Active</span>
                                                                    <span class="switch-inactive" aria-hidden="true">Inactive</span>
                                                                    </label>
                                                                    </div>                    
                  
																	</td>
																	
																	<td style"display:none;"><?php if($row['is_active'] ==1): ?>
																	<?php echo e("Active"); ?>

																	<?php else: ?>
																	<?php echo e("Inactive"); ?>

																	<?php endif; ?>
																	</td>
																	
																	
																	<td class="align-middle">
																		<div class="btn-group align-top">
																			<?php if(checkPermission('/admin/tags','edit') == true): ?>
																			<a href="<?php echo e(url('admin/tags/edit/')); ?>/<?php echo e($row['id']); ?>"   class="mr-2 btn btn-info btn-sm editmodule"><i class="fe fe-edit mr-1"></i> Edit</a>
																			<?php endif; ?>
																			<?php if(checkPermission('/admin/tags','delete') == true): ?>
																			<button  class="btn btn-secondary btn-sm deletemodule" type="button"><i class="fe fe-trash-2  mr-1"></i>Delete</button>
																				<?php endif; ?>
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


						<!-- User Form Modal -->
								

					</div>
				</div><!-- end app-content-->
            </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
		<!-- INTERNAl Data tables -->
		<!--<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/js/jquery.dataTables.js')); ?>"></script>-->
		<!--<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/js/dataTables.bootstrap4.js')); ?>"></script>-->
		<!--<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/js/dataTables.buttons.min.js')); ?>"></script>-->
		<!--<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/js/buttons.bootstrap4.min.js')); ?>"></script>-->
		<!--<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/js/jszip.min.js')); ?>"></script>-->
		<!--<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/js/pdfmake.min.js')); ?>"></script>-->
		<!--<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/js/vfs_fonts.js')); ?>"></script>-->
		<!--<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/js/buttons.html5.min.js')); ?>"></script>-->
		<!--<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/js/buttons.print.min.js')); ?>"></script>-->
		<!--<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/js/buttons.colVis.min.js')); ?>"></script>-->
		<!--<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/dataTables.responsive.min.js')); ?>"></script>-->
		<!--<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/responsive.bootstrap4.min.js')); ?>"></script>-->
		<!--<script src="<?php echo e(URL::asset('admin/assets/js/datatables.js')); ?>"></script>-->
		<script src="<?php echo e(URL::asset('admin/assets/js/datatable/tables/tags-datatable.js')); ?>"></script>
	<!-- INTERNAL Popover js -->
		<script src="<?php echo e(URL::asset('admin/assets/js/popover.js')); ?>"></script>

		<!-- INTERNAL Sweet alert js -->
		<script src="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/sweetalert.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/js/sweet-alert.js')); ?>"></script>
<script type="text/javascript">
	jQuery(document).ready(function(){


// Prompt
	$(".deletemodule").on("click", function(e){

		var tagid = jQuery(this).parents("tr").find("#moduleid").data("value");
		$('body').removeClass('timer-alert');
		swal({
			title: "Delete Confirmation",
			text: "Are you sure you want to delete this tag?",
			// type: "input",
			showCancelButton: true,
			closeOnConfirm: true,
			confirmButtonText: 'Yes'
		},function(inputValue){



			if (inputValue == true) {
			 $.ajax({
            type: "POST",
            url: '<?php echo e(url("/admin/tags/delete")); ?>',
            data: { "_token": "<?php echo e(csrf_token()); ?>", id: tagid},
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
        url: '<?php echo e(url("/admin/tags/status")); ?>',
        data: { "_token": "<?php echo e(csrf_token()); ?>", id: selid,status:sestatus},
        success: function (data) {
        // alert(data);
        if(data ==1) {
        if(sestatus ==1) {
        	jQuery('#status-'+selid).closest("td").attr("data-search","Active");
              toastr.success("Tag activated successfully.");   
            }else {
            	jQuery('#status-'+selid).closest("td").attr("data-search","Inactive");
               toastr.success("Tag deactivated successfully.");  
            }
            var table = $.fn.dataTable.tables( { api: true } );
            table.rows().invalidate().draw();
        }else {
        toastr.error("Failed to update status."); 	
        }
        
        
        }
        });
        });
        
        $('#userrole').DataTable({
		language: {
			searchPlaceholder: 'Search...',
			sSearch: '',
			lengthMenu: '_MENU_',
		}
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
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/qaushas/public_html/resources/views/admin/tags/list.blade.php ENDPATH**/ ?>