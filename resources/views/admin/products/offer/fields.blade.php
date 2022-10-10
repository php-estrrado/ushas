<div class="tab-pane active " id="tab1">
    <div class="card-header mb-4""><div class="card-title">Offer Details</div></div>
    <div class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('discount_value','Discount Value',['class'=>''])}} <span class="text-red">*</span>
             {{Form::number('discount_value',$discount_value,['id'=>'discount_value','class'=>'form-control admin', 'placeholder'=>'Discount Value','max'=>9999])}}
             {{Form::hidden('id',$ofr_id,['id'=>'offer_id'])}}
             {{Form::hidden('prd_id',$prd_id,['id'=>'prd_id'])}}
            <span class="error"></span>
        </div>
    </div>
 

    <div  class="col-lg-6 fl ">
        <div class="form-group">
            {{Form::label('discount_type','Discount Type',['class'=>''])}} <span class="text-red">*</span>
            @php $disc_type = array('percentage'=>'Percentage','amount'=>'Amount'); @endphp
            {{Form::select('discount_type',$disc_type,$discount_type,['id'=>'discount_type','class'=>'form-control', 'placeholder'=>'Discount Type'])}}
            <span class="error"></span>
        </div>
    </div>
    <div class="clearfix"></div>
    <div  class="col-lg-6 fl">
        <div class="form-group">

            {{Form::label('quantity_limit','Product Quantity Limit',['class'=>''])}} <span class="text-red">*</span>
            {{Form::number('quantity_limit',$quantity_limit,['id'=>'quantity_limit','class'=>'form-control admin', 'placeholder'=>'Product Quantity Limit','max'=>9999])}}
            <span class="error"></span>
        </div>
    </div>
    <div class="col-lg-6 fl">
        <div class="form-group">
            <label class="form-label" for="valid_from" >Valid From <span class="text-red">*</span></label>
                <div id="valid_from"  class="datepicker input-group date"
                data-date-format="yyyy-mm-dd">
                <input class="form-control" name="valid_from" type="text" readonly  value="{{$valid_from}}"  onchange="date_check()" />
                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                </div>
            <span class="error"></span>
        </div>
    </div>
     <div class="clearfix"></div>
    <div class="col-lg-6 fl">
        <div class="form-group">

            <label class="form-label" for="valid_from" >Valid To <span class="text-red">*</span></label>
            <div id="valid_to" class="datepicker input-group date"
            data-date-format="yyyy-mm-dd">
            <input class="form-control"  name="valid_to" type="text" readonly value="{{$valid_to}}"  onchange="date_check()" />
            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
            </div>
            <span class="error"></span>
        </div>
    </div>
    <div class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('is_active','Status',['class'=>''])}} <span class="text-red">*</span>
            @php $status = array('1'=>'Active','0'=>'Inactive'); @endphp
            {{Form::select('is_active',$status,$is_active,['id'=>'is_active','class'=>'form-control', 'placeholder'=>'Status'])}}
            <span class="error"></span>
        </div>
    </div>
    
</div>
                        