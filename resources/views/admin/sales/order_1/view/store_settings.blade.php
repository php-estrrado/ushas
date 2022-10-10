<div class="tab-pane" id="tab3">
    <div class="card-header mb-4"><div class="card-title">Store Settings</div></div>
    <div class="col-lg-4 col-md-6 fl">
        <div class="form-group">
            <div class="text-muted">Store Categories</div>
            <div class="font-weight-bold">
                @if($store->storeCategories && count($store->storeCategories) > 0) @foreach($store->storeCategories as $cat)
                <span id="{{$cat->id}}">{{$cat->category->cat_name}}</span>
                @endforeach @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 fl d-none">
        <div class="form-group">
        </div>
    </div>
    <div class="col-lg-4 col-md-6 fl d-none">
        <div class="form-group">
        </div>
    </div>
    <div class="col-lg-4 col-md-6 fl d-none">
        <div class="form-group">
        </div>
    </div>
    <div class="col-lg-4 col-md-6 fl">
        <div class="form-group">
            <div class="text-muted">Commission</div><div class="font-weight-bold">{{$store->commission}}</div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 fl">
        <div class="form-group">
            <div class="text-muted">Status</div><div class="font-weight-bold">{!!$active!!}</div>
        </div>
    </div>
</div>
                    