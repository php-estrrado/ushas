<div class="tab-pane" id="tab2">
    <div class="card-header mb-4"><div class="card-title">Store Address</div></div>
    <div class="col-12 fl">
        <div class="form-group">
            {{Form::label('address','Address',['class'=>''])}}
            {{Form::textarea('store[address]',$address,['id'=>'address', 'class'=>'form-control','placeholder'=>'Address','rows'=>1])}}
            <span class="error"></span>
        </div>
    </div>
    <div class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('country_id','Country',['class'=>''])}}
            {{Form::select('store[country_id]',$countries,$country,['id'=>'country_id','class'=>'form-control chosen-select','placeholder'=>'Country'])}}
            <span class="error"></span>
        </div>
    </div> 
    <div class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('state_id','State',['class'=>''])}}
            {{Form::select('store[state_id]',$states,$state,['id'=>'state_id','class'=>'form-control chosen-select','placeholder'=>'State'])}}
            <span class="error"></span>
        </div>
    </div> 
    <div class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('city_id','City',['class'=>''])}}
            {{Form::select('store[city_id]',$cities,$city,['id'=>'city_id','class'=>'form-control chosen-select','placeholder'=>'City'])}}
            <span class="error"></span>
        </div>
    </div> 
    <div class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('zip_code','Zip Code',['class'=>''])}}
            {{Form::text('store[zip_code]',$zip,['id'=>'zip_code','class'=>'form-control','placeholder'=>'Zip Code'])}}
            <span class="error"></span>
        </div>
    </div>
    <div class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('latitude','Latitude',['class'=>''])}}
            {{Form::text('store[latitude]',$zip,['id'=>'latitude','class'=>'form-control','placeholder'=>'Latitude'])}}
            <span class="error"></span>
        </div>
    </div>
    <div class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('longitude','Longitude',['class'=>''])}}
            {{Form::text('store[longitude]',$zip,['id'=>'longitude','class'=>'form-control','placeholder'=>'Longitude'])}}
            <span class="error"></span>
        </div>
    </div>
</div>
                        