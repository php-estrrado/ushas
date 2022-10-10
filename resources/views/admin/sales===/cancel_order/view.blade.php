@php $currency = getCurrency()->name; @endphp
<div class="row">
    <div class="col-12"> 
        <div class="card">
            <div class="card-body"> <?php // echo '<pre>'; print_r($res->order); echo '</pre>'; die; ?>
                <div class="row">
                    <div class="col-md-6">
                        <div><span class="text-muted mr-4">Requested On</span><span class="font-weight-bold">{{date('d M Y',strtotime($res->created_at))}}</span></div>
                        <div><span class="text-muted mr-4">Request Status</span><span class="font-weight-bold">{{ucfirst($res->status)}}</span></div>
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
    @if($res->notes && count($res->notes) > 0)
    @php $notes = $res->notes; @endphp
    <div class="col-md-12 col-lg-4">
        <div class="card">
            <div class="card-header"><div class="card-title">@if($notes[0]->role_id == 5)Customer Note @else if($notes[0]->role_id == 3) Seller Note @endif</div></div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="text-muted">Title</div><div class="font-weight-bold">{{$notes[0]->title}}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted">Phone</div><div class="font-weight-bold">{{$notes[0]->note}}</div>
                </div>
            </div>
        </div>
    </div>
    @if(count($notes) > 1)
    <div class="col-md-12 col-lg-4">
        <div class="card">
            <div class="card-header"><div class="card-title">Response</div></div>
            <div class="card-body">
                @foreach($notes as $note)
                @if($note->response != NULL)
                <div class="mb-3">
                    <div class="text-muted">@if($note->role_id == 5) Customer @else Seller @endif </div>
                    <div class="font-weight-bold">{{$note->response}}</div>
                </div>
                @endif
                @endforeach
                
            </div>
        </div>
    </div>
    @endif
    @endif
    <div class="col-lg-12">
        <div class="card">
            <div class="card-footer text-right">
                <button id="cancel_btn" type="button" class="btn btn-secondary">Back</button>
               @if($res->status == 'pending')
                <button id="accept_btn" type="button" class="btn btn-success" data-val="{{$res->id}}">Accept</button>
                <button id="reject_btn" type="button" class="btn btn-secondary" data-val="{{$res->id}}" data-toggle="modal" data-target=".bd-example-modal-sm">Reject</button>
               @endif
            </div>
        </div>
    </div>
</div>


    
