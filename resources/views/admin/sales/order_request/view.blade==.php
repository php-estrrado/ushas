
@php $currency = getCurrency()->name; @endphp
 @php $weight=''; @endphp
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
            <div class="card-header"><div class="card-title">Invoice</div></div>
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
                    <div class="text-muted">Grand Total</div><div class="font-weight-bold">{{$currency}} {{ ($order->total+$order->tax+$order->shiping_charge)-$order->discount }}</div>
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
                    <div class="text-muted">Phone</div><div class="font-weight-bold">@if($order->address){{ "+".$order->address->country_code }}@endif{{$order->address->phone}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Email</div><div class="font-weight-bold">{{$order->address->email}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Address</div><div class="font-weight-bold">{{$order->address->address1}}<br />{{$order->address->address2}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">City</div><div class="font-weight-bold">@if($order->address->bcity){{$order->address->bcity->city_name }}@endif</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">State</div><div class="font-weight-bold">@if($order->address->bstate){{ $order->address->bstate->state_name }}@endif</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Country</div><div class="font-weight-bold">@if($order->address->bcountry){{ $order->address->bcountry->country_name }}@endif</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Zipcode</div><div class="font-weight-bold">@if($order->address->zip_code!=0){{$order->address->zip_code }}@endif</div>
                </div>
                <!--<div class="mb-3">-->
                <!--    <div class="text-muted">Country</div><div class="font-weight-bold">{{$order->address->zip_code }}</div>-->
                <!--</div>-->
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
                    <div class="text-muted">Phone</div><div class="font-weight-bold">@if($order->address){{ "+".$order->address->s_country_code }}@endif{{$order->address->s_phone}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Email</div><div class="font-weight-bold">{{$order->address->s_email}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Address</div><div class="font-weight-bold">{{$order->address->s_address1}}<br />{{$order->address->address2}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">City</div><div class="font-weight-bold">@if($order->address->scity){{ $order->address->scity->city_name}}@endif</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">State</div><div class="font-weight-bold">@if($order->address->sstate){{ $order->address->sstate->state_name }}@endif</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Country</div><div class="font-weight-bold">@if($order->address->scountry){{ $order->address->scountry->country_name }} @endif</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Zipcode</div><div class="font-weight-bold">@if($order->address->zip_code!=0){{ $order->address->zip_code }}@endif</div>
                </div>
                <!--<div class="mb-3">-->
                <!--    <div class="text-muted">Country</div><div class="font-weight-bold">{{$order->address->s_zip_code }}</div>-->
                <!--</div>-->
            </div>
        </div>
    </div>
    
    <div class="col-md-12 col-lg-4">
        <div class="card">
            <div class="card-header"><div class="card-title">Payment Detail</div></div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="text-muted">Payment Type</div><div class="font-weight-bold">{{ucfirst($order->payment->payment_type)}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Transaction ID</div><div class="font-weight-bold">{{$order->paymentstripe($order->order_id)}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Amount</div><div class="font-weight-bold">{{$currency}} {{$order->payment->amount}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Payment Status</div><div class="font-weight-bold">{{ucfirst($order->payment_status)}}</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-12 col-lg-4">
        <div class="card">
            <div class="card-header"><div class="card-title">Shipping Detail</div></div>
            <div class="card-body">
                <!--<div class="mb-3">-->
                <!--    <div class="text-muted">Seller</div><div class="font-weight-bold">@if($order->store($order->seller_id)) {{$order->store($order->seller_id)->store_name }} @endif</div>-->
                <!--</div>-->
                <div class="mb-3">
                    <div class="text-muted">Shipping Method</div><div class="font-weight-bold">Fedex</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Amount</div><div class="font-weight-bold">{{$order->shiping_charge}}</div>
                </div>
               
                @if($order->items && count($order->items) > 0) @php $n = 0;$weight=0; @endphp
                            @foreach($order->items as $item)
                            @php $n++;
                           if($item->product){ $weight +=($item->product->weight*$item->qty); }
                            @endphp
                           
                      @endforeach 
                        @endif      
                <div class="mb-3">
                    <div class="text-muted">Weight</div><div class="font-weight-bold">{{$weight}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Shipping Status</div><div class="font-weight-bold">{{ucfirst($order->shipping_status)}}</div>
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
                                <th class="wd-15p">Sl.No.</th>
                                <th>Item Name</th>
                                <th>Sellers Price ({{$currency}})</th>
                                <th>Qty.</th>
                                <th>Sellers Tax ({{$currency}})</th>
                                <th>MJS fees ({{$currency}})</th>
                                <th>MJS Tax ({{$currency}})</th>
                                <th>Total ({{$currency}})</th>
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
                                <td>{{$item->tax_seller}}</td>
                                <td>{{round($item->mjs_fee+$item->pg_fee,1)}}</td>
                                <td>{{round($item->tax-$item->tax_seller,1)}}</td>
                                <td>{{round($item->row_total,1)}}</td>
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
    
