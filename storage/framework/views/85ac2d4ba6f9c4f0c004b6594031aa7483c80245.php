
<div class="row">
              <div class="col-md-12">
                <div class="card overflow-hidden">
                  <div class="card-body">
                    <div class="card-header mb-4""><div class="card-title" style="margin-bottom: 10px; text-align: center;"><strong>Sales Invoice</strong></div></div>
                    

                    <div class="card-body pl-0 pr-0">
                      <div class="row">
                        
                       

                <table>
                <tr>
                <td><div class="col-sm-6">
                <span>Invoice No.</span><br>
                <strong><?php echo e($order->order_id); ?></strong>
                </div>
                </td>
                <td style=" text-align: right; ">
                <div class="col-sm-6 text-right">
                <span>Invoice Date</span><br>
                <strong><?php echo e(date('d M, Y',time())); ?></strong><br>
                <span>Sale Date</span><br>
                <strong><?php echo e(date('d M, Y',strtotime($order->created_at))); ?></strong>
                </div>
                </td>
                </tr>
                <tr>
                  <td>
                    <div class="col-lg-6 ">
                        <p class="h5 font-weight-bold">Bill From</p>
                        
                      </div>
                  </td>

                  <td style="text-align: right;">
                   <div class="col-lg-6 text-right">
                        <p class="h5 font-weight-bold">Bill To</p>
                        <address>
                        <span><?php echo e($info->first_name); ?> <?php echo e($info->last_name); ?></span><br>
                          <?php if(! is_null($customer_mst->user_address_sale($customer_mst->id,$order->id))): ?>
                            <?php $__currentLoopData = $customer_mst->user_address_sale($customer_mst->id,$order->id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $address): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                
                                <span><?php echo e($address['address_1']); ?></span>
                                <?php echo e($address['address_2']); ?><br>
                                <?php if(! is_null($address['city_data'])): ?>
                                <?php if(isset($address['city_data']['city'])): ?><?php echo e($address['city_data']['city']); ?>,<?php endif; ?>
                                <?php if(isset($address['city_data']['state'])): ?><?php echo e($address['city_data']['state']); ?><?php endif; ?><br>
                                <?php if(isset($address['city_data']['country'])): ?><?php echo e($address['city_data']['country']); ?><?php endif; ?><br>
                                <?php if($address['country_code']!='null'): ?> +<?php echo e($address['country_code']); ?> <?php endif; ?> <?php echo e($address['phone']); ?>

                                <?php endif; ?>
                                
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                              
                        </address>
                      </div> 
                  </td>
                </tr>
                </table>

                      </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <div class="row pt-4">
                      
                      
                    </div>
                    <div class="table-responsive push">

                      <table class="table table-bordered table-hover text-nowrap" style="width:100%;">
                        <tr class=" ">
                          <th class="text-center " ></th>
                          <th>Product</th>
                          <th class="text-center" >Qty</th>
                          <th class="text-right" >Unit Price</th>
                          <th class="text-right" >Amount</th>
                          <th class="text-right" >Discount Amount</th>
                        </tr>
                        <?php if($order_items && count($order_items) > 0): ?>
                        <?php $totals = $total_tax = $total_disc = $o= 0; ?>
                                          <?php $__currentLoopData = $order_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php $o++; $total= $row->price*$row->qty; ?>
                        <tr>
                          <td class="text-center"><?php echo e($o); ?></td>
                          <td>
                            <p class="font-weight-semibold mb-1"><?php echo e($row->prd_name); ?></p>
                            <!-- <div class="text-muted">Logo and business cards design</div> -->
                          </td>
                          <td class="text-center"><?php echo e($row->qty); ?></td>
                          <td class="text-center"><?php echo e($row->price); ?></td>
                          <td class="text-center"><?php echo e($total); ?></td>
                          <td class="text-right"><?php echo e(round($row->row_total)); ?></td>
                        </tr>
                        <?php $totals += $total;
                        $total_tax += $row->tax;
                        $total_disc += $row->discount;
                        
                         ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                          <?php endif; ?>
                          <?php $currency = getCurrency()->name; ?>
                        <tr>
                          <td colspan="5" style="text-align: right;" class="font-weight-semibold text-right">Subtotal</td>
                          <td class="text-right"> <?php echo e($currency); ?> <?php echo e(round($totals)); ?></td>
                        </tr>
                        <tr>
                          
                          <td colspan="5" style="text-align: right;" class="font-weight-semibold text-right">Tax</td>
                          <td class="text-right"><?php echo e($currency); ?> <?php echo e(round($order->tax)); ?></td>
                        </tr>
                        
                                                <?php if(isset($order->bid_charge) && ($order->bid_charge>0)): ?>
                                                <tr>
                          <td colspan="5" style="text-align: right;" class="font-weight-semibold text-right">Bid Charge </td>
                          <td class=" text-right "><?php echo e($currency); ?> <?php echo e(round($order->bid_charge)); ?></td>
                        </tr>
                         <?php endif; ?>
                          <?php if(isset($order->wallet_amount) && ($order->wallet_amount>0)): ?>
                        <tr>
                          <td colspan="5" style="text-align: right;" class="font-weight-semibold text-right">Wallet Amount </td>
                          <td class=" text-right"><?php echo e($currency); ?> <?php echo e(round($order->wallet_amount)); ?></td>
                        </tr>
                         <?php endif; ?>
                        <tr>
                          <td colspan="5" style="text-align: right;" class="font-weight-semibold text-right">Shipping Charge </td>
                          <td class="text-right"><?php echo e($currency); ?> <?php echo e(round($order->shiping_charge)); ?></td>
                        </tr>
                        <tr>
                          <td colspan="5" style="text-align: right;" class="font-weight-semibold text-right">Discount</td>
                          <td class="text-right"><?php echo e($currency); ?> <?php echo e(round($total_disc)); ?></td>
                        </tr>
                        <tr>
                          <td colspan="5" style="text-align: right;" class="font-weight-bold text-uppercase text-right h4 mb-0">Grand Total </td>
                          <td class="font-weight-bold text-right h4 mb-0"><?php echo e($currency); ?> <?php echo e(round($order->g_total)); ?></td>
                        </tr>
                        
                      </table>
                    </div>
                    

                    <p class="text-muted text-center"><center>Email:bigbasket@gmail.com</center></p>
                  </div>
                </div>
              </div>
            </div>


<style type="text/css">
p.addr_p {
margin-bottom: 0px;
}
.plabels {
  display: flex;
}
.plabels p {
  margin: 0;
}
</style>            
<style>
table {  font-family: arial, sans-serif;  border-collapse: collapse;  width: 100%;}

td, th {   text-align: left;  padding: 8px;}
.table { margin-top: 20px; }
.table td,.table th {border: 1px solid #dddddd; }

</style><?php /**PATH /home/qaushas/public_html/resources/views/pdf/order_invoice.blade.php ENDPATH**/ ?>