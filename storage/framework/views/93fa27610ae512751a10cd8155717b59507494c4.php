<?php $currency = getCurrency()->name; ?>
<div class="row">
    <div class="col-12"> 
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div><span class="text-muted mr-4">Order Status</span><span class="font-weight-bold" id="orderStatustxt"><?php echo e(ucfirst($order->order_status)); ?></span></div>
                        <div><span class="text-muted mr-4">Payment Status</span><span class="font-weight-bold"><?php echo e(ucfirst($order->payment_status)); ?></span></div>
                    </div>
                    <div class="col-md-6 text-right">
                        <div><span class="text-muted mr-4">Order ID</span><span class="font-weight-bold">#<?php echo e($order->order_id); ?></span></div>
                        <div><span class="text-muted mr-4">Order Date</span><span class="font-weight-bold"><?php echo e(date('d M Y',strtotime($order->created_at))); ?></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-lg-4">
        <div class="card">
            <div class="card-header"><div class="card-title">Order Info</div></div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="text-muted">Order Total</div><div class="font-weight-bold"><?php echo e($currency); ?> <?php echo e($order->total); ?> </div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Tax</div><div class="font-weight-bold"><?php echo e($currency); ?> <?php echo e($order->tax); ?></div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Discount</div><div class="font-weight-bold"><?php echo e($currency); ?> <?php echo e($order->discount); ?></div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Shipping</div><div class="font-weight-bold"><?php echo e($currency); ?> <?php echo e($order->shiping_charge); ?></div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Wallet Balance</div><div class="font-weight-bold"><?php echo e($currency); ?> <?php echo e($order->wallet_amount); ?></div>
                </div>

                <div class="mb-3">
                    <div class="text-muted">Grand Total</div><div class="font-weight-bold"><?php echo e($currency); ?> <?php echo e($order->g_total); ?></div>
                </div>
                
            </div>
        </div>
    </div>
    
    <div class="col-md-12 col-lg-4">
        <div class="card">
            <div class="card-header"><div class="card-title">Billing Address</div></div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="text-muted">Name</div><div class="font-weight-bold"><?php echo e($order->address->name); ?></div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Phone</div><div class="font-weight-bold"><?php echo e($order->address->phone); ?></div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Email</div><div class="font-weight-bold"><?php echo e($order->address->email); ?></div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Address</div><div class="font-weight-bold"><?php echo e($order->address->address1); ?><br /><?php echo e($order->address->address2); ?></div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">City</div><div class="font-weight-bold"><?php echo e($order->address->city); ?></div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">State</div><div class="font-weight-bold"><?php echo e($order->address->state); ?></div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">State</div><div class="font-weight-bold"><?php echo e($order->address->country); ?></div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Country</div><div class="font-weight-bold"><?php echo e($order->address->zip_code); ?></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-12 col-lg-4">
        <div class="card">
            <div class="card-header"><div class="card-title">Shipping Address</div></div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="text-muted">Name</div><div class="font-weight-bold"><?php echo e($order->address->s_name); ?></div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Phone</div><div class="font-weight-bold"><?php echo e($order->address->s_phone); ?></div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Email</div><div class="font-weight-bold"><?php echo e($order->address->s_email); ?></div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Address</div><div class="font-weight-bold"><?php echo e($order->address->s_address1); ?><br /><?php echo e($order->address->address2); ?></div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">City</div><div class="font-weight-bold"><?php echo e($order->address->s_city); ?></div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">State</div><div class="font-weight-bold"><?php echo e($order->address->s_state); ?></div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">State</div><div class="font-weight-bold"><?php echo e($order->address->s_country); ?></div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Country</div><div class="font-weight-bold"><?php echo e($order->address->s_zip_code); ?></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-12 col-lg-4">
        <div class="card">
            <div class="card-header"><div class="card-title">Payment Detail</div></div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="text-muted">Payment Type</div><div class="font-weight-bold"><?php echo e($order->payment->payment_type); ?></div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Transaction ID</div><div class="font-weight-bold"><?php echo e($order->payment->transaction_id); ?></div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Amount</div><div class="font-weight-bold"><?php echo e($currency); ?> <?php echo e($order->payment->amount); ?></div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Payment Status</div><div class="font-weight-bold"><?php echo e($order->payment->payment_status); ?></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-12 col-lg-4">
        <div class="card">
            <div class="card-header"><div class="card-title">Shipping Detail</div></div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="text-muted">Shipping Method</div><div class="font-weight-bold"><?php if($order->shipping): ?> <?php echo e($order->shipping->ship_method); ?> <?php endif; ?></div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Amount</div><div class="font-weight-bold"><?php if($order->shipping): ?> <?php echo e($currency); ?> <?php echo e($order->shipping->price); ?> <?php endif; ?></div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Weight</div><div class="font-weight-bold"><?php if($order->shipping): ?> <?php echo e($order->shipping->weight); ?> <?php endif; ?></div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Shipping Status</div><div class="font-weight-bold"><?php if($order->shipping): ?> <?php echo e($order->shipping->ship_status); ?> <?php endif; ?></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                    <h3 class="card-title">Products</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered card-table table-vcenter text-nowrap">
                        <thead>
                            <tr>
                                <th class="wd-15p">Sr.No.</th>
                                <th>Name</th>
                                <th>Price (<?php echo e($currency); ?>)</th>
                                <th>Qty.</th>
                                <th>Discount</th>
                                <th>total (<?php echo e($currency); ?>)</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if($order->items && count($order->items) > 0): ?> <?php $n = 0; ?>
                            <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php $n++; ?>
                            <tr>
                                <th scope="row"><?php echo e($n); ?></th>
                                <td><?php echo e($item->prd_name); ?></td>
                                <td><?php echo e($item->price); ?></td>
                                <td><?php echo e($item->qty); ?></td>
                                 <td><?php echo e($item->discount); ?></td>
                                <td><?php echo e($item->row_total); ?></otaltd>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
			 </div>
			  <div class="card">
			<div class="card-header">
                    <h3 class="card-title">Update Order</h3>
            </div>
			<form  action="" method="POST" >

			<div class="card-body">
			<div class="col-6">
                     <?php echo e(Form::label('status','Status',['class'=>''])); ?>

					 <select class="form-control mr-2" id="order_status" name="order_status" >
                        <option value="">Select</option>             
                        <option value="accepted" <?php if($order->order_status=="accepted"){ echo "selected"; } ?>>Accept</option>             
                        <option value="rejected" <?php if($order->order_status=="rejected"){ echo "selected"; } ?>>Reject</option>             
                        <option value="ready to pickup" <?php if($order->order_status=="ready to pickup"){ echo "selected"; } ?>>Ready to pickup</option>  
                        <option value="order is delayed" <?php if($order->order_status=="order is delayed"){ echo "selected"; } ?>>Order is delayed</option>
                        <option value="delivered" <?php if($order->order_status=="delivered"){ echo "selected"; } ?>>Delivered</option> 								  <?php echo csrf_field(); ?>
								  
								  <input type="hidden" name="order_id" id="orderId" value="<?php echo e($order->id); ?>">
								  <input type="hidden" name="model" value="order">
								 
            </div>
            </div>
            <div class="col-lg-12">
                <div class="card-footer text-right">
				     <button type="button" id=""  class="btn btn-success OrderactBtn" data="Sucess"> Save </button>

                     <button id="cancel_btn" type="button" class="btn btn-secondary">Back</button>
                 </div>
            </div>
			 </form>
    </div>
    </div>
</div>
    
<?php /**PATH /home/qaushas/public_html/resources/views/admin/sales/order_request/view.blade.php ENDPATH**/ ?>