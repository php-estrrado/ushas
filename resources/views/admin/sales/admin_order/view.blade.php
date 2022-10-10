
@php $currency = getCurrency()->name; @endphp
 @php $weight=''; @endphp
<div class="row">
    <div class="col-12"> 
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                       <div><span class="text-muted mr-4">Order ID</span><span class="font-weight-bold">#{{$order->orders->order_id}}</span></div>
                        <div><span class="text-muted mr-4">Order Date</span><span class="font-weight-bold">{{date('d M Y',strtotime($order->created_at))}}</span></div> 
                    </div>
                    <div class="col-md-6 text-right">
                        
                        
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
                    <div class="text-muted">Order Total</div><div class="font-weight-bold">{{$currency}} {{$order->tot_amount}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Discount</div><div class="font-weight-bold">{{$currency}} {{$order->discount_amt}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Grand Total</div><div class="font-weight-bold">{{$currency}} {{$order->grand_total}}</div>
                </div>
                
            </div>
        </div>
    </div>
    
    <div class="col-md-12 col-lg-4">
        <div class="card">
            <div class="card-header"><div class="card-title">Billing Address</div></div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="text-muted">Name</div><div class="font-weight-bold">{{$order->orders->address->name}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Phone</div><div class="font-weight-bold">@if($order->orders->address){{ "+".$order->orders->address->country_code }}@endif{{$order->orders->address->phone}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Email</div><div class="font-weight-bold">{{$order->orders->address->email}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Address</div><div class="font-weight-bold">{{$order->orders->address->address1}}<br />{{$order->orders->address->address2}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">City</div><div class="font-weight-bold">@if($order->orders->address->bcity){{$order->orders->address->bcity->city_name }}@endif</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">State</div><div class="font-weight-bold">@if($order->orders->address->bstate){{ $order->orders->address->bstate->state_name }}@endif</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Country</div><div class="font-weight-bold">@if($order->orders->address->bcountry){{ $order->orders->address->bcountry->country_name }}@endif</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Zipcode</div><div class="font-weight-bold">@if($order->orders->address->zip_code!=0){{$order->orders->address->zip_code }}@endif</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-12 col-lg-4">
        <div class="card">
            <div class="card-header"><div class="card-title">Shipping Address</div></div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="text-muted">Name</div><div class="font-weight-bold">{{$order->orders->address->s_name}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Phone</div><div class="font-weight-bold">@if($order->orders->address){{ "+".$order->orders->address->s_country_code }}@endif{{$order->orders->address->s_phone}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Email</div><div class="font-weight-bold">{{$order->orders->address->s_email}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Address</div><div class="font-weight-bold">{{$order->orders->address->s_address1}}<br />{{$order->orders->address->address2}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">City</div><div class="font-weight-bold">@if($order->orders->address->scity){{ $order->orders->address->scity->city_name}}@endif</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">State</div><div class="font-weight-bold">@if($order->orders->address->sstate){{ $order->orders->address->sstate->state_name }}@endif</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Country</div><div class="font-weight-bold">@if($order->orders->address->scountry){{ $order->orders->address->scountry->country_name }} @endif</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Zipcode</div><div class="font-weight-bold">@if($order->orders->address->zip_code!=0){{ $order->orders->address->zip_code }}@endif</div>
                </div>
            </div>
        </div>
    </div>
    
    
    
    
    
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                    <h3 class="card-title">Seller Orders</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered card-table table-vcenter text-nowrap">
                        <thead>
                            <tr>
                                <th class="wd-15p">Sr.No.</th>
                                <th>Seller</th>
                                <th>Total ({{$currency}})</th>
                                <th>Tax.</th>
                                <th>Grand total ({{$currency}})</th>
                                <th>Order Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($order->seller_orders && count($order->seller_orders) > 0) @php $n = 0; @endphp
                            @foreach($order->seller_orders as $item) @php $n++; @endphp
                            <tr>
                                <th scope="row">{{$n}}</th>
                                <td>{{$item->store($item->seller_id)->store_name }}</td>
                                <td>{{$item->total}}</td>
                                <td>{{$item->tax}}</td>
                                <td>{{$item->g_total}}</td>
                                <td>{{ucfirst($item->order_status)}}</td>
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
    
