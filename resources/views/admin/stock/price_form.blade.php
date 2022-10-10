<div class="col-12 mb-4">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Add Price</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>  
    {{ Form::open(array('url' => "admin/product-price/save", 'id' => 'priceForm', 'name' => 'priceForm', 'class' => '','files'=>'true')) }}
        <div class="col-lg-12 col-md-12">
            <div class="col-12 fl">
                <div class="form-group">
                    {{Form::label('name','Product Name',['class'=>''])}}
                    {{Form::text('attr[name]','',['id'=>'name','class'=>'form-control','disabled'=>true])}}
                    {{Form::hidden('price[prd_id]','',['id'=>'prd_id'])}} {{Form::hidden('price[seller_id]','',['id'=>'seller_id'])}} 
                    <span class="error"></span>
                </div>
            </div>
            
            <div id="" class="col-12 fl">
                <div class="form-group">
                    {{Form::label('price','Price ('.getCurrency()->name.')',['class'=>''])}}
                    {{Form::text('price[price]','',['id'=>'price', 'class'=>'form-control','placeholder'=>'Price'])}}
                    <span class="error"></span>
                </div>
            </div><div id="" class="col-12 fl">
                <div class="form-group">
                    {{Form::label('sale_price','Sale Price ('.getCurrency()->name.')',['class'=>''])}}
                    {{Form::number('price[sale_price]','',['id'=>'sale_price', 'class'=>'form-control number','placeholder'=>'Sale Price'])}}
                    <span id="qty_error" class="error"></span>
                </div>
            </div>
            <div class="col-12 fl">
                <div class="form-group">
                    {{Form::label('sale_start_date','Sale Starts On',['class'=>''])}} {{Form::hidden('stock[type]','add',['id'=>'add'])}}
                    {{Form::date('price[sale_start_date]',date('Y-m-d'),['id'=>'sale_start_date','min'=>date('Y-m-d'), 'class'=>'form-control'])}}
                    <span class="error"></span>
                </div>
            </div>
            <div id="" class="col-12 fl">
                <div class="form-group">
                    
                    {{Form::label('sale_end_date','Sale Ends On',['class'=>''])}} {{Form::hidden('stock[created_by]',auth()->user()->id,['id'=>'created_by'])}}
                    {{Form::date('price[sale_end_date]',date('Y-m-d'),['id'=>'sale_end_date','min'=>date('Y-m-d'), 'class'=>'form-control'])}}
                    <span class="error"></span>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            {{Form::hidden('cancelId','',['id'=>'cancelId'])}} 
            {{Form::button('Close',['id'=>'cancel_btn','class'=>'btn btn-secondary btn-sm fr','data-dismiss'=>'modal'])}}
            {{Form::submit('Add',['id'=>'ad_stk_btn','class'=>'btn btn-info btn-sm fr'])}}
        </div>
    {{Form::close()}}
</div>

