<div class="tab-pane" id="tab2">
    <div class="card-header mb-4"><div class="card-title">Price & Tax</div></div>
    <div class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('price','Price',['class'=>''])}}
            {{Form::text('price[price]','',['id'=>'price', 'class'=>'form-control','placeholder'=>'price'])}}
            <span class="error"></span>
        </div>
    </div>
    <div class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('sale_price','Sale Price',['class'=>''])}}
            {{Form::text('price[sale_price]','',['id'=>'sale_price','class'=>'form-control chosen-select','placeholder'=>'Sale Price'])}}
            <span class="error"></span>
        </div>
    </div> 
    <div class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('sale_start_date','Sale From Date',['class'=>''])}}
            {{Form::date('price[sale_start_date]','',['id'=>'sale_start_date','class'=>'form-control','placeholder'=>'Start Date'])}}
            <span class="error"></span>
        </div>
    </div> 
    <div class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('sale_end_date','Sale To Date',['class'=>''])}}
            {{Form::date('price[sale_end_date]','',['id'=>'sale_end_date','class'=>'form-control','placeholder'=>'End Date'])}}
            <span class="error"></span>
        </div>
    </div> 
    <div class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('tax','Tax',['class'=>''])}}
            {{Form::select('price[tax]',$taxes,'',['id'=>'tax','class'=>'form-control','placeholder'=>'Select Tax'])}}
            <span class="error"></span>
        </div>
    </div>
</div>
                        