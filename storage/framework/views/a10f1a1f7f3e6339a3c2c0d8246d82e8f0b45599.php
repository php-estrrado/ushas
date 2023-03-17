<?php $currency = getCurrency()->name; ?>
<div class="row flex-lg-nowrap">
    <div class="col-12">
        <div class="row flex-lg-nowrap">
            <div class="col-12 mb-3">
                <div class="e-panel card">
                    <div id="data-content" class="card-body">
                       
                        <div id="table_body" class="card-body table-card-body">
                            <div>
                                    <table id="sales" class="sales table table-striped table-bordered w-100 text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="wd-15p notexport"></th>
                                            <th class="wd-15p">Order ID</th>
                                          
                                            <th class="wd-15p">Customer</th>
                                            <th class="wd-15p">Order Date</th>
                                            <th class="wd-25p notexport">Total (<?php echo e($currency); ?>)</th>
                                            <th class="wd-25p notexport">Tax (<?php echo e($currency); ?>)</th>
                                            <th class="wd-25p notexport">Shipping (<?php echo e($currency); ?>)</th>
                                            <th class="wd-15p">Delivery Status</th>
                                            <th class="wd-15p">Delivery Date</th>
                                            <th class="wd-15p">Payment Method</th>
                                            <th class="wd-15p">Payment Status</th>
                                            <th class="wd-15p">Order Status</th>
                                            <th class="wd-15p">Status</th>
                                            <th class="wd-25p text-center notexport action-btn">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($orders && count($orders) > 0): ?> <?php $n = 0; ?>
                                            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php $n++; ?> <?php // echo '<pre>'; print_r($row->address); echo '</pre>'; die; ?>
                                                <?php if($row->customer): ?>
                                                <?php 
                                                if($row->payment_status == 'pending'){ $pstat = 'primary'; }else if($row->payment_status == 'processing'){ $pstat = 'info'; }else if($row->payment_status == 'success'){ $pstat = 'success'; }else{ $pstat = 'default'; }
                                                if($row->order_status == 'pending'){ $ostat = 'primary'; }else if($row->order_status == 'processing'){ $ostat = 'info'; }else if($row->order_status == 'canceled'){ $ostat = 'error'; }
                                                else if($row->order_status == 'accepted'){ $ostat = 'success'; }else{ $ostat = 'default'; }
                                                $cust_id = date('y',strtotime($row->customer->info->created_at)).date('m',strtotime($row->customer->info->created_at)).str_pad($row->customer->info->id, 6, "0", STR_PAD_LEFT); 
                                                ?>
                                                <tr class="dtrow" id="dtrow-<?php echo e($row->id); ?>">
                                                    <td><span class="d-none"><?php echo e($n); ?></span></td>
                                                    <td><a id="dtlBtn-<?php echo e($row->id); ?>" class="font-weight-bold viewDtl"><?php echo e($row->order_id); ?></a></td> 
                                                   
                                                    <td> <?php echo e($row->customer->info->first_name); ?> <?php echo e($row->customer->info->last_name); ?> <br> (<?php echo e("#".$cust_id); ?>)</td>
                                                    <td><?php echo e(date('d M Y',strtotime($row->created_at))); ?></td>
                                                    <td><?php echo e($row->total); ?></td>
                                                    <td><?php echo e($row->tax); ?></td>
                                                    <td><?php echo e($row->shipping_charge); ?></td>
                                                    <td><?php if($row->delivery_status ==""): ?> <?php echo e("Pending"); ?> <?php else: ?> <?php echo e($row->delivery_status); ?> <?php endif; ?></td>
                                                    <td><?php echo e($row->delivery_date); ?></td>
                                                    <td>
                                                        <?php $__currentLoopData = $row->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pay): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="pay"><?php echo e($pay->payment_type); ?></div>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </td>
                                                    <td><span class="badge badge-<?php echo e($pstat); ?> mt-2"><?php echo e(ucwords(str_replace('_',' ',$row->payment_status))); ?></span></td>
                                                    <td><span class="badge badge-<?php echo e($ostat); ?> mt-2"><?php echo e(ucwords(str_replace('_',' ',$row->order_status))); ?></span></td>
                                                    <td> <?php if($row->calcel): ?> <?php echo e("Cancel: ".ucfirst($row->calcel->status)); ?> <?php endif; ?></td>
                                                    <td class="text-center">
                                                        <button id="editBtn-<?php echo e($row->id); ?>" class="mr-2 btn btn-success btn-sm editBtn"><i class="fa fa-file mr-1"></i>Invoice</button>
                                                    </td> 
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

 <script src="<?php echo e(asset('admin/assets/js/datatable/tables/order-datatable.js')); ?>"></script>
 <style type="text/css">

.plabels {
	display: flex;
}
.plabels p {
	margin: 0;
}
</style><?php /**PATH /home/qaushas/public_html/resources/views/admin/sales/order/list/content.blade.php ENDPATH**/ ?>