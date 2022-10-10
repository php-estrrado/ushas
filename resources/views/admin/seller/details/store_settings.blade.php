<div class="tab-pane" id="tab3">
    <div class="card-header mb-4"><div class="card-title">Store Settings</div></div>
    <div class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('categories','Store Categories',['class'=>''])}} <span class="text-red">*</span>
            {{Form::select('storeSet[categories][]',$categories,$catIds,['id'=>'categories','class'=>'form-control chosen-select','multiple'=>true])}}
            <span class="error"></span>
        </div>
    </div>
    <div class="col-lg-6 fl d-none">
        <div class="form-group">
            {{Form::label('slot_dly_id','Slot Delivery Slots',['class'=>''])}}
            {{Form::select('storeSet[slot_dly_id]',$slots,$assSlotIds,['id'=>'slot_dly_id','class'=>'form-control chosen-select','placeholder'=>'Slot Delivery Slots'])}}
            <span class="error"></span>
        </div>
    </div>
    <div class="col-lg-6 fl d-none">
        <div class="form-group">
            {{Form::label('spot_dly_id','Spot Delivery Slots',['class'=>''])}}
            {{Form::select('storeSet[spot_dly_id]',$spots,$assSpotIds,['id'=>'spot_dly_id','class'=>'form-control chosen-select','placeholder'=>'Spot Delivery Slots'])}}
            <span class="error"></span>
        </div>
    </div>
    <div class="col-lg-6 fl d-none">
        <div class="form-group">
            {{Form::label('pack_charge','Spot Delivery Slots',['class'=>''])}}
            {{Form::radio('storeSet[pack_charge]',1,$packChargeY,['id'=>'pack_charge_1','class'=>''])}}
            {{Form::label('pack_charge_1','Yes',['class'=>'form-label ml-2 fl'])}}
            {{Form::radio('storeSet[pack_charge]',1,$packChargeN,['id'=>'pack_charge_0','class'=>'ml-4'])}}
            {{Form::label('pack_charge_0','No',['class'=>'form-label ml-2 fl'])}}
            <span class="error"></span>
        </div>
    </div>
    <div class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('commission','Commission',['class'=>''])}} <span class="text-red">*</span>
            {{Form::number('storeSet[commission]',$comi,['id'=>'commission','class'=>'form-control','placeholder'=>'Commission','max'=>99])}}
            <span class="error"></span>
        </div>
    </div>
    <div class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('is_active','Status',['class'=>''])}}
            {{Form::select('storeSet[is_active]',[1=>'Active',0=>'Inactive'],$active,['id'=>'is_active','class'=>'form-control chosen-select'])}}
            <span class="error"></span>
        </div>
    </div>
</div>
                    