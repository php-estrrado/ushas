@php $currency = getCurrency()->name; @endphp
<div class="row">
    <div class="col-12"> 
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <?php if($order->source == 'return')
                         { ?>
                        <div><span class="text-muted mr-4">Order Status</span><span class="font-weight-bold">{{ucfirst($order->returninfo->status)}}</span></div>
                         <?php }
                        else
                        { ?>
                        <div><span class="text-muted mr-4">Order Status</span><span class="font-weight-bold">{{ucfirst($order->order->order_status)}}</span></div>
                        <?php } ?> 
                        <div><span class="text-muted mr-4">Payment Status</span><span class="font-weight-bold">{{ucfirst($order->order->payment_status)}}</span></div>
                    </div> 
                    <div class="col-md-6 text-right">
                        <div><span class="text-muted mr-4">Order ID</span><span class="font-weight-bold">#{{$order->order->order_id}}</span></div>
                        <div><span class="text-muted mr-4">Order Date</span><span class="font-weight-bold">{{date('d M Y',strtotime($order->order->created_at))}}</span></div>
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
                    <div class="text-muted">Order Total</div><div class="font-weight-bold">{{$currency}} {{$order->order->total}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Tax</div><div class="font-weight-bold">{{$currency}} {{$order->order->tax}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Discount</div><div class="font-weight-bold">{{$currency}} {{$order->order->discount}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Shipping</div><div class="font-weight-bold">{{$currency}} {{$order->order->shiping_charge}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Wallet Balance</div><div class="font-weight-bold">{{$currency}} {{$order->order->wallet_amount}}</div>
                </div>
               <?php  if($au_status == 'True')
                { ?>
                <div class="mb-3">
                    <div class="text-muted">Bid Chrge</div><div class="font-weight-bold">{{$currency}} {{$bidding_charge}}</div>
                </div>
                <?php } ?> 
                

                <div class="mb-3">
                    <div class="text-muted">Grand Total</div><div class="font-weight-bold">{{$currency}} {{$order->order->g_total}}</div>
                </div>
                
            </div>
        </div>
    </div>
   
    <div class="col-md-12 col-lg-4">
        <div class="card">
            <div class="card-header"><div class="card-title">Billing Address</div></div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="text-muted">Name</div><div class="font-weight-bold">{{$order->customerrr->name}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Phone</div><div class="font-weight-bold">{{$order->customerrr->phone}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Email</div><div class="font-weight-bold">{{$order->customerrr->email}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Address</div><div class="font-weight-bold">{{$order->customerrr->address1}}<br />{{$order->customerrr->address2}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">City</div><div class="font-weight-bold">{{$order->customerrr->city }}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">State</div><div class="font-weight-bold">{{$order->customerrr->state }}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Country</div><div class="font-weight-bold">{{$order->customerrr->bcountry->name }}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Pincode</div><div class="font-weight-bold">{{$order->customerrr->zip_code }}</div>
                </div>
            </div>
        </div>
    </div>
  
    <div class="col-md-12 col-lg-4">
        <div class="card">
            <div class="card-header"><div class="card-title">Shipping Address</div></div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="text-muted">Name</div><div class="font-weight-bold">{{$order->customerrr->s_name}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Phone</div><div class="font-weight-bold">{{$order->customerrr->s_phone}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Email</div><div class="font-weight-bold">{{$order->customerrr->s_email}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Address</div><div class="font-weight-bold">{{$order->customerrr->s_address1}}<br />{{$order->customerrr->address2}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">City</div><div class="font-weight-bold">{{$order->customerrr->s_city }}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">State</div><div class="font-weight-bold">{{$order->customerrr->s_state }}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Country</div><div class="font-weight-bold">{{$order->customerrr->s_country }}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Pincode</div><div class="font-weight-bold">{{$order->customerrr->s_zip_code }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-lg-4">
        <div class="card">
            <div class="card-header"><div class="card-title">Payment Detail</div></div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="text-muted">Payment Type</div><div class="font-weight-bold">{{$order->payment->payment_type}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Transaction ID</div><div class="font-weight-bold">{{$order->payment->transaction_id}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Amount</div><div class="font-weight-bold">{{$currency}} {{$order->payment->amount}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Payment Status</div><div class="font-weight-bold">{{$order->payment->payment_status}}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-lg-4">
        <div class="card">
            <div class="card-header"><div class="card-title">Refund Detail</div></div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="text-muted">Source Type</div><div class="font-weight-bold">{{ucfirst($order->source)}}</div>
                </div>
                <?php if($order->refund_mode == 2) { $mode = 'Bank';} else { $mode = 'Wallet'; } ?>
                <div class="mb-3">
                    <div class="text-muted">Refund Mode</div><div class="font-weight-bold">{{$mode}}</div>
                </div>
                 <div class="mb-3">
                    <div class="text-muted">Total</div><div class="font-weight-bold">{{$currency}} {{$order->total}}</div>
                </div>
                 <div class="mb-3">
                    <div class="text-muted">Refund Tax</div><div class="font-weight-bold">{{$currency}} {{$order->refund_tax}}</div>
                </div>
                 <div class="mb-3">
                    <div class="text-muted">Grand Total</div><div class="font-weight-bold">{{$currency}} {{$order->grand_total}}</div>
                </div>
                <?php
                if($order->refund_mode == 2)
                {
                ?>
                <div class="mb-3">
                    <div class="text-muted">Bank Name</div><div class="font-weight-bold">{{$order->bank_name}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Account Numnber</div><div class="font-weight-bold">{{$order->account_number}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Branch Name</div><div class="font-weight-bold">{{$order->branch_name}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">IFSC Code</div><div class="font-weight-bold">{{$order->ifsc_code}}</div>
                </div>
                <?php
                }
                ?>
                
            </div>
        </div>
    </div>
    
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                    <h3 class="card-title">Proucts</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered card-table table-vcenter text-nowrap">
                        <thead>
                            <tr>
                                <th class="wd-15p">Sr.No.</th>
                                <th>Name</th>
                                <th>Price ({{$currency}})</th>
                                <th>Qty.</th>
                                <th>total ({{$currency}})</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($order->items && count($order->items) > 0) @php $n = 0; @endphp
                            @foreach($order->items as $item) @php $n++; @endphp
                            <tr>
                                <th scope="row">{{$n}}</th>
                                <td>{{$item->prd_name}}</td>
                                <td>{{$item->price}}</td>
                                <td>{{$item->qty}}</td>
                                <td>{{$item->total}}</otaltd>
                            </tr>
                            @endforeach 
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card-footer text-right">
                     <button id="cancel_btn" type="button" class="btn btn-secondary">Back</button>
                 </div>
            </div>
    </div>
    </div>
</div>
    
