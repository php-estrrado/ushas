<?php $currency = getCurrency()->name; ?>
<div class="row flex-lg-nowrap">
    <div class="col-12">
        <div class="row flex-lg-nowrap">
            <div class="col-12 mb-3">
                <div class="e-panel card">
                    <div id="data-content" class="card-body">
                       
                        <div id="table_body" class="card-body table-card-body">
                            <div class="table-responsive">
                                    <table id="visit-table" class="visit-table table table-striped table-bordered w-100 text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="">#</th>
                                            <th class="">Date</th>
                                            <th class="">Product</th>
                                           
                                            <th class="">User views</th>
                                            <th class="">Visitors views</th>
                                            <th class="">Total views</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                       <?php if($visit && count($visit) > 0): ?> <?php $n = 0; ?>
                                            <?php $__currentLoopData = $visit; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php $n++; ?> 
                                            <?php if($row->product): ?>
                                        <tr>
                                        <td class="align-middle select-checkbox"></td>
                                        <td><?php echo e(date('d M Y',strtotime($row->created_at))); ?></td>
                                        <td><?php echo e($row->product->get_content($row->product->name_cnt_id)); ?></td>
                                       
                                        <td><?php echo e($row->users); ?></td>
                                        <td><?php echo e($row->total - $row->users); ?></td>
                                        <td><?php echo e($row->total); ?></td>
                                        </tr>
                                        <?php endif; ?>
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

 <script src="<?php echo e(asset('admin/assets/js/datatable/tables/visit-datatable.js')); ?>"></script>
 <?php /**PATH /home/qaushas/public_html/resources/views/admin/product_visit_report/list.blade.php ENDPATH**/ ?>