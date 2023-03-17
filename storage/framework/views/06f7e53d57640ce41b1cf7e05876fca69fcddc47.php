<?php $currency = getCurrency()->name; ?>
<div class="row flex-lg-nowrap">
    <div class="col-12">
        <div class="row flex-lg-nowrap">
            <div class="col-12 mb-3">
                <div class="e-panel card">
                    <div id="data-content" class="card-body">
                       
                        <div id="table_body" class="card-body table-card-body">
                            <div class="table-responsive">
                                    <table id="bestpurchases" class="bestpurchases table table-striped table-bordered w-100 text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="wd-15p">#</th>
                                            <th class="wd-15p">Product Name</th>
                                            <th class="wd-15p">Items sold</th>
                                            <!--<th class="wd-15p">Repeated purchase</th>-->
                                            <th class="wd-15p">Total reviews</th>
                                            <th class="wd-15p">Tot.avg rating</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                       <?php if($data && count($data) > 0): ?> <?php $n = 0; ?>
                                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php $n++; ?> 
                                    <tr>    
                                        <td class="align-middle select-checkbox"></td>
                                        <td><?php echo e($row['product_name']); ?></td>
                                        <td><?php echo e($row['sold']); ?></td>
                                        <!--<td><?php echo e($row['cust_repeat']); ?></td>-->
                                        <td><?php echo e($row['tot_review']); ?></td>
                                        <td><?php echo e($row['tot_rating']); ?></td>
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

 <script src="<?php echo e(asset('admin/assets/js/datatable/tables/bestpurchase.js')); ?>"></script>
 <?php /**PATH /home/qaushas/public_html/resources/views/admin/bestpurchase_report/list.blade.php ENDPATH**/ ?>