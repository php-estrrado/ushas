<div class="tab-pane active " id="tab1">
    <div class="card-header mb-4""><div class="card-title">Basic Information</div></div>
    <div class="col-12 mb-4"><div class="row">
        <div class="col-md-4 fl">
            <div class="text-muted">Logo</div>
            <div class="font-weight-bold"><img src="{{$logoImg}}" alt="Logo" height="150" /></div>
        </div>
        <div class="col-md-8 fl">
            <div class="text-muted">Banner</div>
            <div class="font-weight-bold"><img src="{{$bannerImg}}" alt="Logo" height="150" /></div>
        </div>
    </div></div>
    <div class="col-lg-4 col-md-6 fl">
        <div class="form-group">
            <div class="text-muted">Business Name</div><div class="font-weight-bold">{{$store->business_name}}</div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 fl">
        <div class="form-group">
            <div class="text-muted">Store Name</div><div class="font-weight-bold">{{$store->store_name}}</div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 fl">
        <div class="form-group">
            <div class="text-muted">Email Id</div><div class="font-weight-bold">{{$seller->teleEmail->value}}</div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 fl">
        <div class="form-group">
            <div class="text-muted">Phone</div><div class="font-weight-bold">{{$seller->telePhone->value}}</div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 fl">
        <div class="form-group">
            <div class="text-muted">Licence</div><div class="font-weight-bold">{{$store->licence}}</div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 fl">
        <div class="form-group">
            <div class="text-muted">Director Name</div><div class="font-weight-bold">{{$info->fname}}</div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 fl">
        <div class="form-group">
            <div class="text-muted">Director IC Number</div><div class="font-weight-bold">{{$info->ic_number}}</div>
        </div>
    </div> 
    <div class="col-lg-4 col-md-6 fl">
        <div class="form-group">
            <div class="text-muted">Incharge Name</div><div class="font-weight-bold">{{$store->incharge_name}}</div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 fl">
        <div class="form-group">
            <div class="text-muted">Incharge Phone</div><div class="font-weight-bold">{{$store->incharge_phone}}</div>
        </div>
    </div> 
    <div class="col-lg-4 col-md-6 fl">
        <div class="form-group">
            <div class="text-muted">Certificate</div><div class="font-weight-bold"> @if($cert_file !="") <a href="{{$cert_file}}" download class=""> View Certificate </a>@endif </div>
        </div>
    </div> 
</div>
                        