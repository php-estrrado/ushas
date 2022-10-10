 @php    
    $row        =   0;

@endphp

<div class="row">
    <div class="col-lg-12 col-md-12">
        {{ Form::open(array('url' => "admin/seller/settlement/save", 'id' => 'adminForm', 'name' => 'adminForm', 'class' => '','files'=>'true')) }}
            <div class="modal-header">
                <h4 class="modal-titlee" id="exampleModalLongTitle">Seller Payment</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                    <div class="col-12 mb-2">
                            {{Form::hidden('seller_id',$seller->seller_id,['id'=>'seller_id'])}}  {{Form::hidden('store_id',$store->id,['id'=>'store_id'])}} 
                            {{Form::hidden('remain_amt',($earnings-$settled),['id'=>'remain_amt'])}} {{Form::hidden('page',$post->page,['id'=>'page'])}} 
                            <div class="text-muted">Seller</div>
                            <div class="font-weight-bold">{{$seller->fname}}</div>
                    </div>
                    <div class="col-12 mb-2">
                            <div class="text-muted">Store</div>
                            <div class="font-weight-bold">{{$store->store_name}}</div>
                    </div>
                    <div class="col-12 mb-2">
                            <div class="text-muted">Pending Settlement</div>
                            <div class="font-weight-bold">{{getCurrency()->name}} {{($earnings-$settled)}}</div>
                    </div>
                    <div class="col-12 mb-2">
                            {{Form::label('payment','Pay Amount',['class'=>'text-muted'])}}
                            {{Form::text('pay_amt','0',['id'=>'pay_amt', 'class'=>'form-control number','placeholder'=>'Enter Amount'])}}
                            <span id="pay_error" class="error"></span>
                    </div><div class="clr"></div>
            </div>
            <div class="card-footer text-right">
                {{Form::hidden('can_submit',0,['id'=>'can_submit'])}}
                <button id="cancel_btn" type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button id="save_btn" type="submit" class="btn btn-primary">Pay</button>
            </div>
       {{Form::close()}}
    </div>
 </div>