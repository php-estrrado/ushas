<div class="tab-pane" id="tab2">
    <div class="card-header mb-4"><div class="card-title">Store Address</div></div>
    <div class="col-lg-4 col-md-6 fl">
        <div class="form-group">
            <div class="text-muted">Address</div><div class="font-weight-bold">{{$store->address}}</div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 fl">
        <div class="form-group">
            <div class="text-muted">Country</div><div class="font-weight-bold">{{$store->country->country_name}}</div>
        </div>
    </div> 
    <div class="col-lg-4 col-md-6 fl">
        <div class="form-group">
            <div class="text-muted">State</div><div class="font-weight-bold">{{$store->state->state_name}}</div>
        </div>
    </div> 
    <div class="col-lg-4 col-md-6 fl">
        <div class="form-group">
            <div class="text-muted">City</div><div class="font-weight-bold">{{$store->city->city_name}}</div>
        </div>
    </div> 
    <div class="col-lg-4 col-md-6 fl">
        <div class="form-group">
            <div class="text-muted">Zip Code</div><div class="font-weight-bold">{{$store->zip_code}}</div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 fl">
        <div class="form-group">
            <div class="text-muted">Latitude</div><div class="font-weight-bold">{{$store->latitude}}</div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 fl">
        <div class="form-group">
            <div class="text-muted">Longitude</div><div class="font-weight-bold">{{$store->longitude}}</div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 fl">
        <div class="form-group">
            <div class="text-muted">Post Office</div><div class="font-weight-bold">{{$store->post_office}}</div>
        </div>
    </div>
</div>
                        