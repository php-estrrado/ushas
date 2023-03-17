<?php $currency = getCurrency()->name; ?>
<div class="row flex-lg-nowrap">
    <div class="col-12">
        <div class="row flex-lg-nowrap">
            <div class="col-12 mb-3">
                <div class="e-panel card">
                    <div id="data-content" class="card-body">
                       
                        <div id="table_body" class="card-body table-card-body">
                            <div>
                                    <table id="currencies" class="currencies  table table-striped table-bordered w-100 text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="wd-15p">Select</th>
                                            <th class="wd-15p">Currency name</th>
                                            <th class="wd-15p">Code</th>
                                            <th class="wd-15p">Country</th>
                                            <th class="wd-15p">Created at</th>
                                            <th class="wd-15p">Status</th>
                                            <th class="wd-15p">Action</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                       <?php if($list && count($list) > 0): ?> <?php $n = 0; ?>
                                            <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php $n++; ?> 
                                    <tr>    
                                        <td class="align-middle select-checkbox">
										 <span class=""></span>
										</td>
                                        <?php $view = $row['id']; ?>
                                        <td><?php echo e($row['currency_name']); ?>

                                        <?php if($row['is_default']==1): ?>
                                        <span class="glyphicon glyphicon-flag text-success"></span>
                                        <?php endif; ?>
                                        </td>
                                        <td><?php echo e($row['currency_code']); ?></td>
                                        <td><?php echo e($row->country->country_name); ?></td>
                                        <td class="text-nowrap align-middle"><span><?php echo e(date('d M Y',strtotime($row->created_at))); ?></span></td>
                                        <td class="text-nowrap align-middle" data-search="<?php if($row->is_active==1): ?><?php echo e("Active"); ?><?php else: ?><?php echo e("Inactive"); ?><?php endif; ?>">
																	    <div class="switch">
                                                                            <input class="switch-input status-btn ser_status" data-selid="<?php echo e($row->id); ?>" id="status-<?php echo e($row->id); ?>"  data-id="<?php echo e($row->id); ?>" name="status" type="checkbox"  <?php if($row->is_active==1): ?> <?php echo e("checked"); ?> <?php endif; ?> >
                                                                            <label class="switch-paddle" for="status-<?php echo e($row->id); ?>">
                                                                                <span class="switch-active" aria-hidden="true">Active</span>
                                                                                <span class="switch-inactive" aria-hidden="true">Inactive</span>
                                                                            </label>
                                                                        </div>
																	</td>
                                        

                                        <td class="align-middle">
                                        <div class="btn-group align-top">

                                         <a href="<?php echo e(url('admin/currency/edit/')); ?>/<?php echo e($row->id); ?>"   class="btn btn-sm btn-info mr-2"><i class="fe fe-edit mr-2"></i> Edit</a>
                                         <button  class="btn btn-sm btn-secondary deletecurrency" type="button" onclick="delete_cat(<?php echo e($row->id); ?>)" ><i class="fe fe-trash-2"></i>Delete</button>
                                          </div>
                                         </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>

                                    </tbody>
                                </table>
                                <?php echo e(csrf_field()); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
            
        <!-- End Page -->

       


 

 <?php $__env->startSection('js'); ?>
 <script src="<?php echo e(URL::asset('admin/assets/js/datatable/tables/currencies-datatable.js')); ?>"></script>
<script>

 function delete_cat(id){
	    
	   $('body').removeClass('timer-alert');
        swal({
            title: "Delete Confirmation",
            text: "Are you sure you want to delete this Currency?",
            // type: "input",
            showCancelButton: true,
            closeOnConfirm: true,
            confirmButtonText: 'Yes'
        },function(inputValue){
    if (inputValue == true) {
        $.ajax({
            type: "POST",
            url: '<?php echo e(url("/admin/currency/delete/")); ?>',
            data: { "_token": "<?php echo e(csrf_token()); ?>", id: id},
            success: function (data) {
                location.reload();

            }
        });
        }
    });
    }
    
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
        url: '<?php echo e(url("/admin/currency/status")); ?>',
        data: { "_token": "<?php echo e(csrf_token()); ?>", id: selid,status:sestatus},
        success: function (data) {
        // alert(data);
        if(data ==1) {
        if(sestatus ==1) {
        jQuery('#status-'+selid).closest("td").attr("data-search","Active");
        toastr.success("Currency activated successfully.");   
        }else {
        jQuery('#status-'+selid).closest("td").attr("data-search","Inactive");
        toastr.success("Currency deactivated successfully.");  
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
 <?php /**PATH /home/qaushas/public_html/resources/views/admin/currency/list.blade.php ENDPATH**/ ?>