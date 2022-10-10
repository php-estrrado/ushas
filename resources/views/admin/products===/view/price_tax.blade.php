<div class="tab-pane" id="tab2">
    @php $currency = getCurrency()->name; @endphp
<div class="card-header mb-4"><div class="card-title">Product Info</div></div>
<div class="col-lg-6  col-lg-offset-6">
        <div class="form-group">
            {{Form::label('weight','Weight (g)',['class'=>''])}}
            {{Form::number('prd[weight]',$weight,['id'=>'weight', 'class'=>'form-control','placeholder'=>'Weight','max'=>9999999999,'step'=>0.01])}}
            <span class="error"></span>
        </div>
    </div>


<div class="row panel-body1 tabs-menu-body1 simple_prod" >     
<div class="tab-content col-12">
<div class="card-header mb-4"><div class="card-title">Price & Tax</div></div>       
<div class="clearfix"></div>   
<div class="col-lg-6 fl">
<div class="form-group">
{{Form::label('varprice',"Variable Price (as per today's rate) ($currency)",['class'=>''])}}
{{Form::number('variable_price',$price,['id'=>'varprice', 'class'=>'form-control','placeholder'=>'Variable Price','readonly'=>true,'max'=>9999999999,'step'=>0.01])}}
<span class="error"></span>
</div>
</div>
<div class="col-lg-6 fl">
<div class="form-group"> 
{{Form::label('fixed_price',"Fixed Price ($currency)",['class'=>''])}}
{{Form::number('prd[fixed_price]',$fixedprice,['id'=>'fixed_price','class'=>'form-control ','placeholder'=>'Fixed Price','max'=>9999999999,'step'=>0.01])}}
<span class="error"></span>
</div>
</div> 
<div class="clearfix"></div>

</div>
<div class="clearfix"></div>

</div>

<div class="row panel-body1 tabs-menu-body1 simple_prod" >     
<div class="tab-content col-12">
<div class="card-header mb-4"><div class="card-title">Vendor Selling Price</div></div>       
<div class="clearfix"></div>   

    @if($fields && count($fields) > 0)
    @foreach($fields as $field)

        @if($field->variable_rate ==1)
            <div class="col-lg-6 fieldgroup available_fields">
                <div class="form-group">
                    <h6>{{$field->name}}</h6> 
                    @if($field->values && count($field->values) > 0)
                        
                        
                        @foreach($field->values as $val)
                                <label for="attr_{{$val->id}}" class="custom-control custom-radio fieldslist show_{{$field->id}} show_val_{{$val->id}} mr-4 fl">
                                    <input type="radio" class="custom-control-input " name="display[{{$field->id}}][valId]" value="{{$val->name}}" id="attr_{{$val->id}}" />
                                    <span class="custom-control-label">{{$val->name}}</span>
                                </label>
                            @endforeach
                      
                        @endif
                    <span class="error"></span>
                    <div class="clr"></div>
                </div>
            </div>
         @endif  
    @endforeach
    @endif

<div class="col-lg-4 fr col-lg-offset-8">
<div class="form-group">
<ul class="summary">
    <li>Variable Price ({{$currency}})</li>
    <li><p id="var_price_disp">0.00</p></li>
</ul>
<ul class="summary">
    <li>Fixed Price ({{$currency}})</li>
    <li><p id="fixed_price_disp">0.00</p></li>
</ul>
<ul class="summary">
    <li>Tax ({{$currency}})</li>
    <li><input type="hidden" id="hidded_tax" value="{{ $tax->value}}"> <p id="total_tax_disp">0.00</p></li>
</ul>
<ul class="summary">
    <li>Total ({{$currency}})</li>
    <li><p id="total_price_disp">0.00</p></li>
</ul>
</div>
</div>

<div class="clearfix"></div>

</div>
<div class="clearfix"></div>

</div>
     
   


<style>
    .summary li {
         display: inline;

    }
    .summary li:nth-last-child(1) {
  float: right;
} 
</style>

</div>
