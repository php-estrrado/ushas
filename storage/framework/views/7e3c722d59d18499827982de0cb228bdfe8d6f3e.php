<?php $__env->startSection('css'); ?>
		<!-- INTERNAl Data table css -->
		<link href="<?php echo e(URL::asset('admin/assets/plugins/datatable/css/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/plugins/datatable/css/buttons.bootstrap4.min.css')); ?>"  rel="stylesheet">
		<link href="<?php echo e(URL::asset('admin/assets/plugins/datatable/responsive.bootstrap4.min.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/sweetalert.css')); ?>" rel="stylesheet" />
		<style>
        #sortable-row { list-style: none; color: black; }
        #sortable-row li { margin-bottom:4px; padding:10px; background-color:#BBF4A8;cursor:move;}
        #sortable-row li.ui-state-highlight { height: 1.0em; background-color:#F0F0F0;border:#ccc 2px dotted;}
        
        .sortable-row { list-style: none; color: black; }
        .sortable-row li { margin-bottom:4px; padding:10px; background-color:#BBF4A8;cursor:move;}
        .sortable-row li.ui-state-highlight { height: 1.0em; background-color:#F0F0F0;border:#ccc 2px dotted;}
        .modal-open 
        {
        overflow: scroll;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-header'); ?>
						<!--Page header-->


						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0">Subcategory List</h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Masters</a></li>

									<li class="breadcrumb-item active" aria-current="page"><a href="#">Subcategory List</a></li>
								</ol>
							</div>
							<div class="page-rightheader" style="display:flex; flex-direction: row; justify-content: center; align-items: center">
							    <label class="form-label mr-2" for="filterSel">Filter </label>
							    						<select class="form-control mr-2" id="filterSel">
                                                        <option value="">All Status</option>
                                                        <option value="Active">Active</option>
                                                        <option value="Inactive">Inactive</option>
                                                        </select>
								<div class="btn btn-list">
									<!-- <a href="#" class="btn btn-info"><i class="fe fe-settings mr-1"></i> General Settings </a>
									<a href="#" class="btn btn-danger"><i class="fe fe-printer mr-1"></i> Print </a> -->
									<a href="<?php echo e(route('subcategory.new')); ?>"   class="btn btn-primary addmodule"><i class="fe fe-plus mr-1"></i> Add New</a>
									<button style="" data-backdrop="static" class="btn btn-warning" data-toggle="modal"  data-target="#sort-modal" data-container=""><i class="fa fa-sort mr-1"></i> Sort Order</button>
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
														<table id="subcategory" class="subcategory-table table table-striped table-bordered w-100 text-nowrap">
															<thead>
																<tr>
																	<th class="align-top border-bottom-0 wd-5 notexport">Select</th>
																	<th class="border-bottom-0 w-20">Category</th>
																	<th class="border-bottom-0 w-20">Sub-Category</th>
																
																	<th class="border-bottom-0 w-30">Description</th>
																	<th class="border-bottom-0 w-30">Status</th>
																	<th class="border-bottom-0 w-15">Created On</th>
																	<th class="border-bottom-0 w-10">Actions</th>
																</tr>
															</thead>

															<tbody>

																<?php if($category && count($category) > 0): ?> <?php $n =0 ; ?>
                    											<?php $__currentLoopData = $category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php $n++ ; ?>
                                                                <?php  $default_lang =DB::table('glo_lang_lk')->where('is_active', 1)->first();
                                                                       $subcategory_name=DB::table('cms_content')->where('cnt_id', $row->sub_name_cid)->where('lang_id', $default_lang->id)->first();
                                                                       $category_data =DB::table('category')->where('category_id', $row->category_id)->first();
                                                                       $category_name=DB::table('cms_content')->where('cnt_id', $category_data->cat_name_cid)->where('lang_id', $default_lang->id)->first();
                                                                       $category_desc=DB::table('cms_content')->where('cnt_id', $row->desc_cid)->where('lang_id', $default_lang->id)->first();
                                                                       $parent_name='';
                                                                       if($row->parent!=0 && $row->parent!='')
                                                                       {
                                                                       $parent_data =DB::table('subcategory')->where('subcategory_id', $row->parent)->first();
                                                                       $parent_name=DB::table('cms_content')->where('cnt_id', $parent_data->sub_name_cid)->where('lang_id', $default_lang->id)->first();
                                                                       } ?>
                                                                       
																<tr>
																		<td class="align-middle select-checkbox" id="moduleid" data-value="<?php echo e($row['id']); ?>">
																		<label class="custom-control custom-checkbox">
																			
																			<!--<?php echo e($loop->iteration); ?>-->
																		</label>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																				<h6 class=" font-weight-bold"><a href="<?php echo e(url('admin/subcategory/view/')); ?>/<?php echo e($row->subcategory_id); ?>"><?php echo e($category_name->content); ?></a></h6>
                                                                        </div>
																	</td>
																	
																	<td class="align-middle" >
																	    <?php $av_image=url('storage/app/public/subcategory/'.$row->image);
																	    ?>
																	    <div class="d-flex">
																			
																			<div class="ml-3 mt-1">
																			     <?php	$subcat_name = $row->subcategory_name; ?>
																				<h6 style="overflow: hidden;white-space: nowrap;text-overflow: ellipsis; max-width: 200px;" class="mb-0 font-weight-bold"><a href="<?php echo e(url('admin/subcategory/view/')); ?>/<?php echo e($row->subcategory_id); ?>"><?php echo e($subcat_name); ?></a></h6>
																			</div>
																		</div>
																	
																	<td class="text-nowrap align-middle">

																		<p style="overflow: hidden;white-space: nowrap;text-overflow: ellipsis; max-width: 100px;"><?php echo e($category_desc->content); ?></p>
																	</td>

																	<td class="text-nowrap align-middle" data-search="<?php if($row->is_active==1): ?><?php echo e("Active"); ?><?php else: ?><?php echo e("Inactive"); ?><?php endif; ?>">
																	    <div class="switch">
                                                                            <input class="switch-input status-btn ser_status" data-selid="<?php echo e($row->subcategory_id); ?>" id="status-<?php echo e($row->subcategory_id); ?>"  data-id="<?php echo e($row->subcategory_id); ?>" type="checkbox"  <?php if($row->is_active ==1): ?> <?php echo e("checked"); ?> <?php endif; ?>>
                                                                            <label class="switch-paddle" for="status-<?php echo e($row->subcategory_id); ?>">
                                                                                <span class="switch-active" aria-hidden="true">Active</span>
                                                                                <span class="switch-inactive" aria-hidden="true">Inactive</span>
                                                                            </label>
                                                                        </div>
																		
																	</td>
																	<td class="text-nowrap align-middle"><span><?php echo e(date('d M Y',strtotime($row->created_at))); ?></span></td>

																	<td class="align-middle">
																		<div class="btn-group align-top">
                                                                            <?php if(checkPermission('admin/subcategory','edit') == true): ?>
																			<a href="<?php echo e(url('admin/subcategory/edit/')); ?>/<?php echo e($row->subcategory_id); ?>"   class="btn btn-sm btn-info mr-2"><i class="fe fe-edit mr-1"></i> Edit</a>
																			<?php endif; ?>
																			<?php if(checkPermission('admin/subcategory','delete') == true): ?>
																			<button  class="btn btn-sm btn-secondary deletecategory" type="button" onclick="delete_cat(<?php echo e($row->subcategory_id); ?>)"><i class="fe fe-trash-2"></i>Delete</button>
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

        <!-- sort order modal -->              
			<div id="sort-modal" class="modal fade">
			<div class="modal-dialog modal-confirm">
			<div class="modal-content">
			<div class="modal-header">
			<h3 class="modal-title">Change Order</h3>  
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<form  action="<?php echo e(route('subcategory.sort-order')); ?>" method="POST" >
			<?php echo csrf_field(); ?>
			<div class="modal-body">

			<?php if($category_sort && count($category_sort) > 0): ?>
			<input type = "hidden" name="row_order" id="row_order" /> 
			
			<?php $__currentLoopData = $category_sort; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<ul id="sortable-row" class="sortable-row">
			
			<?php if(isset($cat->subcategory) && count($cat->subcategory) > 0): ?>
			<b><?php echo e($cat->cat_name); ?></b>
			<?php $__currentLoopData = $cat->subcategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<?php  $default_lang =DB::table('glo_lang_lk')->where('is_active', 1)->first();
			   $subcategory_name=DB::table('cms_content')->where('cnt_id', $row->sub_name_cid)->where('lang_id', $default_lang->id)->first();
			    ?>
			<li id=<?php echo e($row->subcategory_id); ?>><?php echo e($subcategory_name->content); ?></li>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			<br>
			<?php endif; ?>
			</ul>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			
			<?php endif; ?>
			</div>
			<div class="modal-footer">
			<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
			<button type="submit" id="" onClick="saveOrder();" class="btn btn-success"> Save </button>
			</div>
			</form>
			</div>
			</div>
			</div>
            <!-- Modal -->
			<div class="modal  fade" id="del_modal" tabindex="-1" role="dialog" aria-labelledby="smallmodal" aria-hidden="true">
				<div class="modal-dialog modal-sm" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="smallmodal1">Delete Subcategory</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">×</span>
							</button>
						</div>
						<div class="modal-body">
							<p>Do you want really want to delete?</p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary" id="ok_button">Yes</button>
						</div>
					</div>
				</div>
			</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
		<!-- INTERNAl Data tables -->
		<script src="<?php echo e(URL::asset('admin/assets/js/datatable/tables/subcategory-datatable.js')); ?>"></script>
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
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
    function saveOrder() 
    {
      var selectedLanguage = new Array();
      $('ul.sortable-row li').each(function() 
      {
          selectedLanguage.push($(this).attr("id"));
      });
      document.getElementById("row_order").value = selectedLanguage;
    }
    
	function delete_cat(cat_id){
       $('body').removeClass('timer-alert');
    swal({
        title: "Delete Confirmation",
        text: "Are you sure you want to delete this Sub Category?",
        // type: "input",
        showCancelButton: true,
        closeOnConfirm: true,
        confirmButtonText: 'Yes'
    },function(inputValue){
    if (inputValue == true) {
        $.ajax({
            type: "POST",
            url: '<?php echo e(url("/admin/delete-subcategory/")); ?>',
            data: { "_token": "<?php echo e(csrf_token()); ?>", cat_id: cat_id},
            success: function (data) {
                location.reload();

            }
        });
    }
});
}

    function status_update($cat_id){
       alert($cat_id);
    }

    $(function() {
        $( ".sortable-row" ).sortable(
      {
        placeholder: "ui-state-highlight"
      }); 
    $('.ser_status').change(function() {
        var status = $(this).prop('checked') == true ? 1 : 0;
        var cat_id = $(this).data('id');

        $.ajax({
            type: "POST",
            url: '<?php echo e(url("/admin/category/change-status-subcategory")); ?>',
            data: { "_token": "<?php echo e(csrf_token()); ?>", cat_id: cat_id,status: status},
            success: function (data) {
                console.log(data.success)

            }
        });
        if(status!=true)
        { toastr.success("Inactivated Successfully");
        jQuery('#status-'+cat_id).closest("td").attr("data-search","Inactive");
        var table = $.fn.dataTable.tables( { api: true } );
            table.rows().invalidate().draw();
        $(this).prop("");
        }else{ toastr.success("Activated Successfully");
        jQuery('#status-'+cat_id).closest("td").attr("data-search","Active");
              var table = $.fn.dataTable.tables( { api: true } );
            table.rows().invalidate().draw();
        }
    })
  })
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/qaushas/public_html/resources/views/admin/master/subcategory_list.blade.php ENDPATH**/ ?>