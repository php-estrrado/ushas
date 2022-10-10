<div class="col-12 mb-4">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Add Stock</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>  
    {{ Form::open(array('url' => "admin/product-stock/save", 'id' => 'adminForm', 'name' => 'adminForm', 'class' => '','files'=>'true')) }}
        <div class="col-lg-12 col-md-12">
            <div class="col-12 fl">
                <div class="form-group">
                    {{Form::label('name','Product Name',['class'=>''])}}
                    {{Form::text('attr[name]','',['id'=>'name','class'=>'form-control','disabled'=>true])}}
                    {{Form::hidden('stock[prd_id]','',['id'=>'prd_id'])}} {{Form::hidden('stock[seller_id]','',['id'=>'seller_id'])}} 
                    <span class="error"></span>
                </div>
            </div>
            
            <div id="" class="col-12 fl">
                <div class="form-group">
                    {{Form::label('rate','Price ('.getCurrency()->name.')',['class'=>''])}}
                    {{Form::text('stock[rate]','',['id'=>'rate', 'class'=>'form-control','readonly'=>true])}}
                    <span class="error"></span>
                </div>
            </div><div id="" class="col-12 fl">
                <div class="form-group">
                    {{Form::label('qty','Quantity',['class'=>''])}}
                    {{Form::number('stock[qty]','',['id'=>'qty', 'class'=>'form-control numberonly','data-val'=>1,'required'=>true,'placeholder'=>'Quantity'])}}
                    <span id="qty_error" class="error"></span>
                </div>
            </div>
            <div class="col-12 fl">
                <div class="form-group">
                    {{Form::label('amount','Amount',['class'=>''])}} {{Form::hidden('stock[type]','add',['id'=>'add'])}}
                    {{Form::text('amount',0,['id'=>'amount', 'class'=>'form-control','readonly'=>true])}}
                    <span class="error"></span>
                </div>
            </div>
            <div id="" class="col-12 fl">
                <div class="form-group">
                    {{Form::label('desc','Description',['class'=>''])}} {{Form::hidden('stock[created_by]',auth()->user()->id,['id'=>'created_by'])}}
                    {{Form::textarea('stock[desc]','',['id'=>'desc', 'class'=>'form-control','placeholder'=>'Description','rows'=>2])}}
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

