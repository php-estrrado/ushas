@php $currency = getCurrency()->name; @endphp
<div class="row">
    <div class="col-12"> 
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div><span class="text-muted mr-4">Order Status</span><span class="font-weight-bold">{{ucfirst($order->order_status)}}</span></div>
                        <div><span class="text-muted mr-4">Payment Status</span><span class="font-weight-bold">{{ucfirst($order->payment_status)}}</span></div>
                    </div>
                    <div class="col-md-6 text-right">
                        <div><span class="text-muted mr-4">Order ID</span><span class="font-weight-bold">#{{$order->order_id}}</span></div>
                        <div><span class="text-muted mr-4">Order Date</span><span class="font-weight-bold">{{date('d M Y',strtotime($order->created_at))}}</span></div>
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
                    <div class="text-muted">Order Total</div><div class="font-weight-bold">{{$currency}} {{$order->total}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Tax</div><div class="font-weight-bold">{{$currency}} {{$order->tax}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Discount</div><div class="font-weight-bold">{{$currency}} {{$order->discount}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Shipping</div><div class="font-weight-bold">{{$currency}} {{$order->shiping_charge}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Wallet Balance</div><div class="font-weight-bold">{{$currency}} {{$order->wallet_amount}}</div>
                </div>

                <div class="mb-3">
                    <div class="text-muted">Grand Total</div><div class="font-weight-bold">{{$currency}} {{$order->g_total}}</div>
                </div>
                
            </div>
        </div>
    </div>
    
    <div class="col-md-12 col-lg-4">
        <div class="card">
            <div class="card-header"><div class="card-title">Billing Address</div></div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="text-muted">Name</div><div class="font-weight-bold">{{$order->address->name}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Phone</div><div class="font-weight-bold">{{$order->address->phone}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Email</div><div class="font-weight-bold">{{$order->address->email}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Address</div><div class="font-weight-bold">{{$order->address->address1}}<br />{{$order->address->address2}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">City</div><div class="font-weight-bold">{{$order->address->city }}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">State</div><div class="font-weight-bold">{{$order->address->state }}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">State</div><div class="font-weight-bold">{{$order->address->country }}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Country</div><div class="font-weight-bold">{{$order->address->zip_code }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-12 col-lg-4">
        <div class="card">
            <div class="card-header"><div class="card-title">Shipping Address</div></div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="text-muted">Name</div><div class="font-weight-bold">{{$order->address->s_name}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Phone</div><div class="font-weight-bold">{{$order->address->s_phone}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Email</div><div class="font-weight-bold">{{$order->address->s_email}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Address</div><div class="font-weight-bold">{{$order->address->s_address1}}<br />{{$order->address->address2}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">City</div><div class="font-weight-bold">{{$order->address->s_city }}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">State</div><div class="font-weight-bold">{{$order->address->s_state }}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">State</div><div class="font-weight-bold">{{$order->address->s_country }}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Country</div><div class="font-weight-bold">{{$order->address->s_zip_code }}</div>
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
            <div class="card-header"><div class="card-title">Shipping Detail</div></div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="text-muted">Shipping Method</div><div class="font-weight-bold">{{$order->shipping->ship_method}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Amount</div><div class="font-weight-bold">{{$currency}} {{$order->shipping->price}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Weight</div><div class="font-weight-bold">{{$order->shipping->weight}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Shipping Status</div><div class="font-weight-bold">{{$order->shipping->ship_status}}</div>
                </div>
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
    
