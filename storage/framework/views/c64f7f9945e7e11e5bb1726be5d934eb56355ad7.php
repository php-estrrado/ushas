
<?php $__env->startSection('css'); ?>
		<!-- INTERNAl Data table css -->
		<!--<link href="<?php echo e(URL::asset('admin/assets/plugins/datatable/css/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet" />-->
		<!--<link href="<?php echo e(URL::asset('admin/assets/plugins/datatable/css/buttons.bootstrap4.min.css')); ?>"  rel="stylesheet">-->
		<!--<link href="<?php echo e(URL::asset('admin/assets/plugins/datatable/responsive.bootstrap4.min.css')); ?>" rel="stylesheet" />-->
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
									<!--<a href="<?php echo e(url('/admin/brands/create')); ?>"   class="btn btn-primary addmodule"><i class="fe fe-plus mr-1"></i> Add New</a>-->
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
														<table class="table table-bordered border-top text-nowrap brandstable" id="brandstable">
															<thead>
																<tr>
																	<th class="align-top border-bottom-0 wd-5 notexport">Select</th>
																	<th class="border-bottom-0 w-20">Brand Name</th>
																	
																	<th class="border-bottom-0 w-20">Description</th>
																	<th class="border-bottom-0 w-15">Created On</th>
																	<th class="border-bottom-0 w-15 notexport">Status</th>
																	<th class="border-bottom-0 w-30 hide_column" style"display:none;">Status</th>														
																	<!--<th class="border-bottom-0 w-20 notexport">Actions</th>-->
																</tr>
															</thead>

															<tbody>

																<?php if($brands && count($brands) > 0): ?> <?php $n = 0; ?>
                    											<?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php $n++; ?>
																<tr>
																	<td class="align-middle select-checkbox" id="moduleid" data-value="<?php echo e($row['id']); ?>">
																		<span class="d-none"><?php echo e($n); ?></span>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																			<?php	$brand_name = Str::of($row['brand_name'])->limit(20); ?>
																	
																			
																			<h6 class=" font-weight-bold"> <a href="<?php echo e(url('admin/brands/view/')); ?>/<?php echo e($row['id']); ?>" ><?php echo e($brand_name); ?> </a></h6>
																				
																			
																		</div>
																	</td>
																	<td class="text-nowrap align-middle">
																		<!--<p><?php echo e($row['brand_desc']); ?></p>-->
																		<?php	$brand_desc = Str::of($row['brand_desc'])->limit(20); ?>
																		<p><?php echo e($brand_desc); ?></p>
																	</td>
																	<td class="text-nowrap align-middle"><span><?php echo e(date('d M Y',strtotime($row['created_at']))); ?></span></td>
																	<td class="text-nowrap align-middle" data-search="<?php if($row['is_active'] ==1): ?><?php echo e("Active"); ?><?php else: ?><?php echo e("Inactive"); ?><?php endif; ?>">
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
																	
																<?php /* ?>	<td class="align-middle">
																		<div class="btn-group align-top">
																			@if(checkPermission('/admin/brands','edit') == true)
																			<a href="{{ url('admin/brands/edit/') }}/{{$row['id']}}"   class="mr-2 btn btn-info btn-sm editmodule"><i class="fe fe-edit mr-1"></i> Edit</a>
																			@endif
																			@if(checkPermission('/admin/brands','delete') == true)
																			<button  class="btn btn-secondary btn-sm deletemodule" id="deletemodule-{{$row['id']}}" type="button"><i class="fe fe-trash-2 mr-1"></i>Delete</button>
																			@endif
																		</div>
																	</td> <?php */ ?>
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
	
	<!-- INTERNAL Popover js -->
		<script src="<?php echo e(URL::asset('admin/assets/js/popover.js')); ?>"></script>
     <script src="<?php echo e(URL::asset('admin/assets/js/datatable/tables/brands-datatable.js')); ?>"></script>
		<!-- INTERNAL Sweet alert js -->
		<script src="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/sweetalert.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/js/sweet-alert.js')); ?>"></script>
<script type="text/javascript">
	jQuery(document).ready(function(){


        $(".ser_status").on("click", function(e){
        
        var selid = jQuery(this).data("selid");
        
        var sestatus='0';
        if($(this).prop('checked') == true)
        {
        sestatus='1';
        }
        
        $.ajax({
        type: "POST",
        url: '<?php echo e(url("/admin/brands/status")); ?>',
        data: { "_token": "<?php echo e(csrf_token()); ?>", id: selid,status:sestatus},
        success: function (data) {
        // alert(data);
        if(data ==1) {
        if(sestatus ==1) {
        	jQuery('#status-'+selid).closest("td").attr("data-search","Active");
              toastr.success("Brand activated successfully.");   
            }else {
            	jQuery('#status-'+selid).closest("td").attr("data-search","Inactive");
               toastr.success("Brand deactivated successfully.");  
            }
            var table = $.fn.dataTable.tables( { api: true } );
            table.rows().invalidate().draw();
        }else {
        toastr.error("Failed to update status."); 	
        }
        
        
        }
        });
        });
        
    
        
	});
	
	$('body').on('click','.deletemodule',function(){  

		var id          =   this.id.replace('deletemodule-','');

		$('body').removeClass('timer-alert');
		swal({
			title: "Delete Confirmation",
			text: "Are you sure you want to delete this brand?",
			// type: "input",
			showCancelButton: true,
			closeOnConfirm: true,
			confirmButtonText: 'Yes'
		},function(inputValue){
			if (inputValue == true) {
			 $.ajax({
            type: "POST",
            url: '<?php echo e(url("/admin/brands/delete")); ?>',
            data: { "_token": "<?php echo e(csrf_token()); ?>", id: id},
            success: function (data) {
            	// alert(data);
            		toastr.success("Brand deleted successfully");
            		location.reload();
            	
            }
        });

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
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/qaushas/public_html/resources/views/admin/brands/list.blade.php ENDPATH**/ ?>