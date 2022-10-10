@php $currency = getCurrency()->name; 
if($res->status == 'return_initiated')
{ $stat = 'false'; $status = 'Return Initiated';}
else if($res->status == 'return_accepted')
{ $stat = 'false'; $status = 'Return Accepted';}
else if($res->status == 'return_rejected')
{ $stat = 'false'; $status = 'Return Rejected';}
else if($res->status == 'shipment_initiated')
{ $stat = 'true'; $status = 'Shipment Initiated';}
else if($res->status == 'shipment_rejected')
{ $stat = 'true'; $status = 'Shipment Rejected';}
else if($res->status == 'refund_initiated')
{ $stat = 'true'; $status = 'Refund Initiated';}
else if($res->status == 'refund_completed')
{ $stat = 'true'; $status = 'Refund Completed';}
else
{ $stat = 'default'; }
@endphp
<div class="row">
    <div class="col-12"> 
        <div class="card">
            <div class="card-body"> <?php // echo '<pre>'; print_r($res->order); echo '</pre>'; die; ?>
                <div class="row">
                    <div class="col-md-6">
                        <div><span class="text-muted mr-4">Requested On</span><span class="font-weight-bold">{{date('d M Y',strtotime($res->created_at))}}</span></div>
                        <div><span class="text-muted mr-4">Request Status</span><span class="font-weight-bold">{{ucfirst($status)}}</span></div>
                    </div>
                    <div class="col-md-6 text-right">
                        <div><span class="text-muted mr-4">Order ID</span><span class="font-weight-bold">#{{$res->order->order_id}}</span></div>
                        <div><span class="text-muted mr-4">Order Date</span><span class="font-weight-bold">{{date('d M Y',strtotime($res->order->created_at))}}</span></div>
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
                    <div class="text-muted">Order Total</div><div class="font-weight-bold">{{$currency}} {{$res->order->total}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Tax</div><div class="font-weight-bold">{{$currency}} {{$res->order->tax}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Discount</div><div class="font-weight-bold">{{$currency}} {{$res->order->discount}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Shipping</div><div class="font-weight-bold">{{$currency}} {{$res->order->shiping_charge}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Wallet Balance</div><div class="font-weight-bold">{{$currency}} {{$res->order->wallet_amount}}</div>
                </div>

                <div class="mb-3">
                    <div class="text-muted">Grand Total</div><div class="font-weight-bold">{{$currency}} {{$res->order->g_total}}</div>
                </div>
                
            </div>
        </div>
    </div>

    <div class="col-md-12 col-lg-4">
        <div class="card">
            <div class="card-header"><div class="card-title">Return Info</div></div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="text-muted">Return type</div><div class="font-weight-bold">@if($res->type=='qty')Quantity of a product return
                        @elseif($res->type=='item')Product return @else Order return 
                    @endif</div>
                </div>
                @if($res->type=='qty' || $res->type=='item')
                <div class="mb-3">
                    <div class="text-muted">Product</div><div class="font-weight-bold">{{$res->product->name}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Quantity</div><div class="font-weight-bold">{{$res->qty}}</div>
                </div>
                @endif
                <div class="mb-3">
                    <div class="text-muted">Reason</div><div class="font-weight-bold">{{$res->reason}} @if($res->reason_id == 6)- {{$res->desc}} @endif</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Issue Item</div><div class="font-weight-bold">{{$res->issue_item}}</div>
                </div>
                 <div class="mb-3">
                    <div class="text-muted">Description</div><div class="font-weight-bold">{{$res->desc}}</div>
                </div>
                 <div class="mb-3">
                    <div class="text-muted">Amount</div><div class="font-weight-bold">{{$currency}} {{$res->amount}}</div>
                </div>
            </div>
        </div>
    </div>
    @if($stat == 'true') 
    @if($res->shipment)
    <div class="col-md-12 col-lg-4">
        <div class="card">
            <div class="card-header"><div class="card-title">Shipment Info</div></div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="text-muted">Shipment Detail</div><div class="font-weight-bold">{{$res->shipment->description}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Document</div><div class="font-weight-bold"><img src="{{ config('app.storage_url').'/'.$res->shipment->document; }}" alt="Document" style="width: 50%;" ></div>
                </div>
            </div>

        </div>
    </div>
    @endif
    @endif

    @if($res->type=='order')
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
                                <th>Price ({{$currency}})</th>
                                <th>Qty.</th>
                                <th>total ({{$currency}})</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($res->order->items && count($res->order->items) > 0) @php $n = 0; @endphp
                            @foreach($res->order->items as $item) @php $n++; @endphp
                            <tr>
                                <th scope="row">{{$n}}</th>
                                <td>{{$item->prd_name}}</td>
                                <td>{{$item->price}}</td>
                                <td>{{$item->qty}}</td>
                                <td>{{$item->row_total}}</td>
                            </tr>
                            @endforeach 
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
    </div>
    </div>
    @endif
    <div class="col-lg-12">
        <div class="card">
            <div class="card-footer text-right">
                <button id="cancel_btn" type="button" class="btn btn-secondary">Back</button>
               @if($res->status == 'return_initiated')
                <button id="accept_btn" type="button" class="btn btn-success" data-val="{{$res->id}}">Accept</button>
                <button id="reject_btn" type="button" class="btn btn-secondary" data-val="{{$res->id}}" data-toggle="modal" data-target=".bd-example-modal-sm">Reject</button>
               @endif
               @if($res->status == 'shipment_initiated')
                <button id="ship_accept_btn" type="button" class="btn btn-success" data-val="{{$res->id}}">Accept</button>
                <button id="ship_reject_btn" type="button" class="btn btn-secondary" data-val="{{$res->id}}" data-toggle="modal" data-target=".bd-example-modal-sm">Reject</button>
               @endif
            </div>
        </div>
    </div>
</div>


    
