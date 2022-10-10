@php    
    $id         =   ($seller)?  $seller->id : 0;                                $email      =   ($seller)?  $seller->teleEmail->value : '';
    $phone      =   ($seller)?  $seller->telePhone->value : '';                 $refCode    =   ($seller)?  $seller->ref_code : '';
    $name       =   ($info)?    $info->fname : '';                              $icNo       =   ($info)?    $info->ic_number : '';
    $bName      =   ($store)?   $store->business_name : '';                     $sName      =   ($store)?   $store->store_name : '';
    $licence    =   ($store)?   $store->licence : '';                           $address    =   ($store)?   $store->address : '';
    $latitude   =   ($store)?   $store->latitude : '';                          $longitude  =   ($store)?   $store->longitude : '';
    $country    =   ($store)?   $store->country_id : '';                        $state      =   ($store)?   $store->state_id : '';
    $city       =   ($store)?   $store->city_id : '';                           $zip        =   ($store)?   $store->zip_code : '';
    $comi       =   ($store)?   $store->commission : '';                        $icName     =   ($store)?   $store->incharge_name : '';
    $icPhone    =   ($store)?   $store->incharge_phone : '';                    $shipMtd    =   ($store)?   $store->ship_method : '';
    $packOption =   ($store)?   $store->pack_option : '';                       $pickOption =   ($store)?   $store->pickup_option : '';
    $isPikChrg  =   ($store)?   $store->is_pickup_chrge : 0;                    $pikChrg    =   ($store)?   $store->pickup_chrge : 0;
    $discount   =   ($store)?   $store->discount : 0;                           $limitType  =   ($store)?   $store->limit_type : '';
    $purLimit   =   ($store)?   $store->purchase_limit : '';                    $trackLink  =   ($store)?   $store->tracking_link : '';
    $active     =   ($store)?   $store->is_active : 1;                          $storeId    =   ($store)?   $store->id : 0;
    $logo       =   ($store)?   $store->logo : NULL;                            $banner     =   ($store)?   $store->banner : NULL;
    $packOption =   ($store)?   $store->pack_option : 0; $post_office        =   ($store)?   $store->post_office : '';
    $first_name =   ($info)?    $info->fname : '';
    $last_name =   ($info)?     $info->lname : '';
    $cert_end_date =   ($store)?   $store->certificate_end_date : NULL;
    $catIds     =   $assSlotIds = $assSpotIds = []; $certificate     =   ($store)?   $store->certificate : NULL;
    $agreements     =   ($store)?   $store->agreement : NULL;
    $icPhoneISD    =   ($store)?   $store->incharge_isd_code : '';   $isd_code    =   ($seller)?   $seller->isd_code : ''; 
    if($storeId     >   0){ foreach($assCategories as $strCat){    $catIds[]       =   $strCat->category_id; } }
    if($assSlots    &&  count($assSlots) > 0){  foreach($assSlots as $row){ $assSlotIds[]   =   $row->slot_id;  }   }
    if($assSpots    &&  count($assSpots) > 0){  foreach($assSpots as $row){ $assSpotIds[]   =   $row->slot_id;  }   }
    if($packOption  >   0){ $packChargeY = true; $packChargeN = false; }else{ $packChargeY  =   false; $packChargeN = true; }   
    if($logo        !=  NULL && $logo   != ''){ $logoImg    = config('app.storage_url').$logo; }else{ $logoImg = url('storage/app/public/no-avatar.png'); }
    if($banner      !=  NULL && $banner != ''){ $bannerImg  = config('app.storage_url').$banner; }else{ $bannerImg = url('storage/app/public/no-banner.png'); }
    if($certificate      !=  NULL && $certificate != ''){ $cert_file  = config('app.storage_url').$certificate; }else{ $cert_file = ''; }
    if($agreements      !=  NULL && $agreements != ''){ $agreement  = config('app.storage_url').$agreements; }else{ $agreement = ''; }
     $agreement_samples  = config('app.storage_url').$agreement_sample;
@endphp  <?php // echo '<pre>'; print_r($catIds); echo '</pre>'; ?>
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0">View Seller Details</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Seller Management</a></li>
            <li class="breadcrumb-item"><a href="#" id="bc_list"><i class="fe fe-grid mr-2 fs-14"></i>New Seller Request List</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="#">View Seller Details</a></li>
        </ol>
    </div>
</div>
<div class="col-lg-12 col-md-12">
    <div class="card">
        <div class="card-body pb-2">
            {{ Form::open(array('url' => "admin/seller/save", 'id' => 'adminForm', 'name' => 'adminForm', 'class' => '','files'=>'true')) }}
                {{Form::hidden('storeId',$storeId,['id'=>'storeId'])}} {{Form::hidden('id',$id,['id'=>'id'])}} 
                {{Form::hidden('formType','newSeller',['id'=>'formType'])}}
                <div class="tabs-menu mb-4">
                    <ul class="nav panel-tabs">
                        <li><a href="#tab1" data-toggle="tab" id="nav_tab_1" class="active"><span>Basic Info.</span></a></li>
                        <li><a href="#tab2" data-toggle="tab" id="nav_tab_2"><span>Store Address.</span></a></li>
                        <!--<li><a href="#tab3" data-toggle="tab" id="nav_tab_3"><span>Store Settings</span></a></li>-->
<!--                        <li><a href="#tab4" data-toggle="tab" id="nav_tab_4"><span>Delivery Options</span></a></li>
                        <li><a href="#tab5" data-toggle="tab" id="nav_tab_5"><span>Bank Details</span></a></li>-->
                   </ul>
                </div>
                <div class="row panel-body tabs-menu-body">
                    <div class="tab-content col-12">
                        @include('admin.seller.details.basic_info')
                        @include('admin.seller.details.store_address')
                        @include('admin.seller.details.store_settings')
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card-footer text-right">
                        {{Form::hidden('can_submit',0,['id'=>'can_submit'])}}{{Form::hidden('page','admin',['id'=>'admin'])}}
                        <button id="cancel_btn" type="button" class="btn btn-secondary">Cancel</button>
                        @php if($info->is_approved == 0){ @endphp
                        <button id="deny_btn" data-id="{{$id}}" type="button" class="btn btn-secondary" >Deny</button>
                        <button id="save_new" type="button" class="btn btn-primary">Approve</button>
                        @php }elseif($info->is_approved == 1){ @endphp
                        <button type="button" class="btn btn-primary">Approved</button>
                        @php }else{ @endphp
                        <button type="button" class="btn btn-secondary" >Denied</button>
                        @php } @endphp
                        
                    </div>
                </div>
           {{Form::close()}}
        </div>
    </div>
</div>