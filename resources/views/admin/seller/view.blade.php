 @php  
    if($store->is_active    ==  1){ $active     = '<span class="badge badge-success">Active</span>'; }else{ $active = '<span class="badge badge-error">Inactive</span>'; }
    if($store->logo         !=  NULL && $store->logo   != ''){ $logoImg    = config('app.storage_url').$store->logo; }else{ $logoImg = url('storage/app/public/no-avatar.png'); }
    if($store->banner       !=  NULL && $store->banner != ''){ $bannerImg  = config('app.storage_url').$store->banner; }else{ $bannerImg = url('storage/app/public/no-banner.png'); }
    $certificate     =   ($store)?   $store->certificate : NULL;
    if($certificate      !=  NULL && $certificate != ''){ $cert_file  = config('app.storage_url').$certificate; }else{ $cert_file = ''; }
@endphp
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0">{{$title}}</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Seller Management</a></li>
            <li class="breadcrumb-item" aria-current="page"><a id="bc_list" href="">Seller List</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="#">{{$title}}</a></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body pb-2">
                    <div class="tabs-menu mb-4">
                        <ul class="nav panel-tabs">
                        <li><a href="#tab1" data-toggle="tab" id="nav_tab_1" class="active"><span>Basic Info.</span></a></li>
                        <li><a href="#tab2" data-toggle="tab" id="nav_tab_2"><span>Store Address.</span></a></li>
                        <!--<li><a href="#tab3" data-toggle="tab" id="nav_tab_3"><span>Store Settings</span></a></li>-->
<!--                        <li><a href="#tab4" data-toggle="tab" id="nav_tab_4"><span>Delivery Options</span></a></li>
                        <li><a href="#tab5" data-toggle="tab" id="nav_tab_5"><span>Bank Details</span></a></li>-->
                   </ul>
                    </div><?php // echo '<pre>'; print_r($attr); echo '</pre>'; die; ?>
                    <div class="row panel-body tabs-menu-body">
                        <div class="tab-content col-12">
                            @include('admin.seller.view.basic_info')
                            @include('admin.seller.view.store_address')
                            @include('admin.seller.view.store_settings')
                        </div>
                    </div>
                    <div class="col-lg-12">
                    <div class="card-footer text-right">
                        <button id="cancel_btn" type="button" class="btn btn-secondary" data-dismiss="modal">Back</button>
                    </div>
                    </div>
            </div>
        </div>
    </div>
</div>
