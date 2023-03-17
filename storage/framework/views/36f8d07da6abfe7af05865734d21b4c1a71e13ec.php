
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
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>User Management</a></li>
									
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
									<a href="<?php echo e(url('/admin/admins-list/create')); ?>"   class="btn btn-primary addmodule"><i class="fe fe-plus mr-1"></i> Add New</a>
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
														<table class="table table-bordered border-top text-nowrap adminlist" id="adminlist">
															<thead>
																<tr>
																	<th class="align-top border-bottom-0 wd-5 userroles">Select</th>
																	<th class="border-bottom-0 w-15">Name</th>
																	
																	<th class="border-bottom-0 w-15">Email</th>
																	<th class="border-bottom-0 w-15">Phone</th>
																	<th class="border-bottom-0 w-10">Role</th>
																	
																	<th class="border-bottom-0 w-10">Created On</th>	
																	<th class="border-bottom-0 w-15">Status</th>						
																	<th class="border-bottom-0 w-10 userroles">Actions</th>
																</tr>
															</thead>

															<tbody>

																<?php if($admins && count($admins) > 0): ?>
                    											<?php $__currentLoopData = $admins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																<tr>
																	<td class="align-middle select-checkbox" id="moduleid" data-value="<?php echo e($row->id); ?>">
																		<label class="custom-control custom-checkbox">
																			
																			<!--<?php echo e($loop->iteration); ?>-->
																		</label>
																	</td>
																	<td class="align-middle" >
																	    <?php $avatar=url('storage'.$row->avatar);
																	    ?>
																	    <div class="d-flex">
																			<?php if($row['avatar']): ?>
																	<span class="avatar brround avatar-md d-block" style="background-image: url(<?php echo $avatar; ?>)"></span>
																			<?php else: ?>
																	<span class="avatar brround avatar-md d-block" style="background-image: url(<?php echo url('storage/app/public/no-avatar.png'); ?>)"></span>
																			<?php endif; ?>
																			<div class="ml-3 mt-1">
																				<h6 class=" font-weight-bold"><a href="<?php echo e(url('admin/admins-list/view/')); ?>/<?php echo e($row->id); ?>" ><?php echo e($row->fname." ".$row->lname); ?> </a></h6>
																			</div>
																		</div>
																	
																	</td>
																	<td class="text-nowrap align-middle">
																		<p><?php echo e($row->email); ?></p>
																	</td>
																	<td class="text-nowrap align-middle">
																		<p><?php if($row->isd_code): ?> +<?php echo e($row->isd_code); ?> <?php endif; ?> <?php echo e($row->phone); ?></p>
																	</td>
																	<td class="text-nowrap align-middle">
																	<?php
																	$role_data =DB::table('usr_role_lk')->where('id', $row->role_id)->first();
																	if($role_data){ 
																	$role_name = $role_data->usr_role_name;
																	}else {
																	$role_name = '';
																	}
																	?>

																	<p><?php echo e($role_name); ?></p>
																	</td>
																	<td class="text-nowrap align-middle"><span><?php echo e(date('d M Y',strtotime($row->created_at))); ?></span></td>
																	<td class="text-nowrap align-middle" data-search="<?php if($row->is_active ==1): ?><?php echo e("Active"); ?><?php else: ?><?php echo e("Inactive"); ?><?php endif; ?>">
																		<!--<label class="onswitch  ">-->
                  <!--                                                  <input class='ser_status' data-selid="<?php echo e($row->id); ?>"  type="checkbox"  <?php if($row->is_active ==1): ?> <?php echo e("checked"); ?> <?php endif; ?> />-->
                  <!--                                                  <span class="slider round"></span>-->
                  <!--                                                  </label>-->
                                                                    
<div class="switch">
<input class="switch-input status-btn ser_status" data-selid="<?php echo e($row->id); ?>"  id="status-<?php echo e($row->id); ?>"  type="checkbox"  <?php if($row->is_active ==1): ?> <?php echo e("checked"); ?> <?php endif; ?> >
<label class="switch-paddle" for="status-<?php echo e($row->id); ?>">
<span class="switch-active" aria-hidden="true">Active</span>
<span class="switch-inactive" aria-hidden="true">Inactive</span>
</label>
</div>
                                                                    
																	</td>
																	
																	
																	<td class="align-middle">
																		<div class="btn-group align-top">
																			    	<?php if(checkPermission('/admin/admins-list','edit') == true): ?>
																			<a href="<?php echo e(url('admin/admins-list/edit/')); ?>/<?php echo e($row->id); ?>"   class="mr-2 btn btn-info btn-sm editmodule"><i class="fe fe-edit mr-1"></i> Edit</a>
																				<?php endif; ?>
																			<?php if( $row->role_id !=1 ): ?> 
																			<?php if(checkPermission('/admin/admins-list','delete') == true): ?>
																			<button  class="btn btn-secondary btn-sm deletemodule" onclick="deletecpn(<?php echo e($row['id']); ?>);" type="button"><i class="fe fe-trash-2  mr-1"></i>Delete</button> <?php endif; ?>
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
            
<style type="text/css">
table.dataTable tr.parent {
animation: none !important;
}
table.dataTable tr.selected p {
color: #fff;
}


</style>

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
			<script src="<?php echo e(URL::asset('admin/assets/js/datatable/tables/admins-datatable.js')); ?>"></script>
	<!-- INTERNAL Popover js -->
		<script src="<?php echo e(URL::asset('admin/assets/js/popover.js')); ?>"></script>

		<!-- INTERNAL Sweet alert js -->
		<script src="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/sweetalert.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/js/sweet-alert.js')); ?>"></script>
<script type="text/javascript">



function deletecpn(cpnid){
$('body').removeClass('timer-alert');
		swal({
			title: "Delete Confirmation",
			text: "Are you sure you want to delete this User?",
			// type: "input",
			showCancelButton: true,
			closeOnConfirm: true,
			confirmButtonText: 'Yes'
		},function(inputValue){



			if (inputValue == true) {
			 $.ajax({
            type: "POST",
            url: '<?php echo e(url("/admin/admins-list/delete")); ?>',
            data: { "_token": "<?php echo e(csrf_token()); ?>", id: cpnid},
            success: function (data) {
            	// alert(data);
            	if(data ==1){
            		location.reload();
            	}
            
            }
        });

			}
		});
}

	jQuery(document).ready(function(){



	// Prompt
	

 $(".ser_status").on("click", function(e){
        
        var selid = jQuery(this).data("selid");
        
        var sestatus='0';
        if($(this).prop('checked') == true)
        {
        sestatus='1';
        }
        
        $.ajax({
        type: "POST",
        url: '<?php echo e(url("/admin/admins-list/status")); ?>',
        data: { "_token": "<?php echo e(csrf_token()); ?>", id: selid,status:sestatus},
        success: function (data) {
        // alert(data);
        if(data ==1) {
            if(sestatus ==1) {
            	jQuery('#status-'+selid).closest("td").attr("data-search","Active");
              toastr.success("User activated successfully.");   
            }else {
            	jQuery('#status-'+selid).closest("td").attr("data-search","Inactive");
               toastr.success("User deactivated successfully.");  
            }
            var table = $.fn.dataTable.tables( { api: true } );
            table.rows().invalidate().draw();
        
        }else {
        toastr.error("Failed to update status."); 	
        }
        
        
        }
        });
        });

 $('#adminslist').DataTable({
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
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/qaushas/public_html/resources/views/admin/admins/list.blade.php ENDPATH**/ ?>