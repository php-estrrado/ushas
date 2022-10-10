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
    $cert_end_date =   ($store)?   $store->certificate_end_date : NULL;
    $packOption =   ($store)?   $store->pack_option : 0;                        $first_name =   ($info)?    $info->fname : '';
    $last_name =   ($info)?     $info->lname : '';
    $catIds     =   $assSlotIds = $assSpotIds = []; $post_office        =   ($store)?   $store->post_office : '';
     $certificate     =   ($store)?   $store->certificate : NULL;
     $agreements     =   ($store)?   $store->agreement : NULL;
     $icPhoneISD    =   ($store)?   $store->incharge_isd_code : '';   $isd_code    =   ($seller)?   $seller->isd_code : ''; 
    if($storeId     >   0){ foreach($assCategories as $strCat){    $catIds[]       =   $strCat->category_id; } }
    if($assSlots    &&  count($assSlots) > 0){  foreach($assSlots as $row){ $assSlotIds[]   =   $row->slot_id;  }   }
    if($assSpots    &&  count($assSpots) > 0){  foreach($assSpots as $row){ $assSpotIds[]   =   $row->slot_id;  }   }
    if($packOption  >   0){ $packChargeY = true; $packChargeN = false; }else{ $packChargeY  =   false; $packChargeN = true; }   
       if($logo        !=  NULL && $logo   != ''){ $logoImg    = config('app.storage_url').$logo; }else{ $logoImg = url('storage/app/public/no-avatar.png'); }
    if($banner      !=  NULL && $banner != ''){ $bannerImg  = config('app.storage_url').$banner; }else{ $bannerImg = url('storage/app/public/no-banner.png'); }
       $bank_id       =   (isset($bank['id']))?    $bank['id'] : 0;  $ac_no       =   (isset($bank['ac_no']))?    $bank['ac_no'] : '';  
    $ac_holder       =   (isset($bank['ac_holder']))?    $bank['ac_holder'] : '';  $bank_name       =   (isset($bank['bank_name']))?    $bank['bank_name'] : '';  
    $acc_type       =   (isset($bank['acc_type']))?    $bank['acc_type'] : 0;  $ifsc       =   (isset($bank['ifsc']))?    $bank['ifsc'] : '';  
    $branch       =   (isset($bank['branch']))?    $bank['branch'] : '';  
     if($certificate      !=  NULL && $certificate != ''){ $cert_file  = config('app.storage_url').$certificate; }else{ $cert_file = ''; }
     if($agreements      !=  NULL && $agreements != ''){ $agreement  = config('app.storage_url').$agreements; }else{ $agreement = ''; }
     $agreement_samples  = config('app.storage_url').$agreement_sample;
@endphp  <?php // echo '<pre>'; print_r($catIds); echo '</pre>'; ?>
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0">@if($id > 0) Edit Seller @else Add Seller @endif</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Seller Management</a></li>
            <li class="breadcrumb-item"><a href="#" id="bc_list"><i class="fe fe-grid mr-2 fs-14"></i>Seller List</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="#">@if($id > 0) Edit Seller @else Add Seller @endif</a></li>
        </ol>
    </div>
     <div class="page-rightheader" style="display:flex; flex-direction: row; justify-content: center; align-items: center">
      
        <div class="btn btn-list">
         @if($id>0)   <a id="addBank" data-target="#bank-form-modal" data-toggle="modal"  class="btn btn-primary"><i class="fe fe-plus mr-1"></i> Bank Deatils</a> @endif
        </div>
    </div>
</div>
<div class="col-lg-12 col-md-12">
    <div class="card">
        <div class="card-body pb-2">
            {{ Form::open(array('url' => "admin/seller/save", 'id' => 'adminForm', 'name' => 'adminForm', 'class' => '','files'=>'true')) }}
                {{Form::hidden('storeId',$storeId,['id'=>'storeId'])}} {{Form::hidden('id',$id,['id'=>'id'])}} {{Form::hidden('formType','',['id'=>'formType'])}} 
                @if($filters && count($filters) > 0) @foreach($filters as $k=>$filter) {{Form::hidden('filter['.$k.']',$filter,['id'=>$k])}} @endforeach @endif
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
                        <button id="cancel_btn" type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button id="save_btn" type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
           {{Form::close()}}
        </div>
    </div>
</div>

<div class="modal fade" role="dialog" tabindex="-1" id="bank-form-modal">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title">Bank Details</h5>
<button type="button" class="close" data-dismiss="modal">
<span aria-hidden="true">×</span>
</button>
</div>
<div class="modal-body">
{{ Form::open(array('url' => "admin/seller/bank-data", 'id' => 'bankForm', 'name' => 'bankForm', 'class' => '','files'=>'true')) }}
{{Form::hidden('seller_id',$id,['id'=>'seller_id'])}}
{{Form::hidden('bank_id',$bank_id,['id'=>'bank_id'])}}
<div class="py-1">

<div class="row">
<div class="col">
<div class="row">
<div class="col">
<div class="form-group">
<label>Account Number <span class="text-red">*</span></label>

{!! Form::text('acc_number', $ac_no, ['class' => 'form-control','required','id'=>'acc_number']) !!}
</div>
</div>
<div class="col">
<div class="form-group">
<label>Bank Name <span class="text-red">*</span></label>

{!! Form::text('bank_name', $bank_name, ['class' => 'form-control','required','id'=>'bank_name']) !!}
</div>
</div>

</div>
<div class="row">
<div class="col">
<div class="form-group">
<label>Account Type <span class="text-red">*</span></label>
@php $acc_types = array("Current account"=>"Current account","Savings account"=>"Savings account","Salary account"=>"Salary account","Fixed deposit account"=>"Fixed deposit account","NRI account"=>"NRI account"); @endphp
{!! Form::select('acc_type', $acc_types, $acc_type,['class' => 'form-control','required','id'=>'acc_type']); !!}
</div>
</div>
<div class="col">
<div class="form-group">
<label>IFSC <span class="text-red">*</span></label>

{!! Form::text('ifsc', $ifsc, ['class' => 'form-control','required','id'=>'ifsc']) !!}
</div>
</div>

</div>
<div class="row">
<div class="col">
<div class="form-group">
<label>Branch Name <span class="text-red">*</span></label>

{!! Form::text('branch_name', $branch, ['class' => 'form-control','required','id'=>'branch_name']) !!}
</div>
</div>
<div class="col">
<div class="form-group">
<label>Account Holder <span class="text-red">*</span></label>

{!! Form::text('acc_holder', $ac_holder, ['class' => 'form-control','required','id'=>'acc_holder']) !!}
</div>
</div>

</div>


</div>
</div>

<div class="row">
<div class="col d-flex justify-content-end">
<input class="btn btn-primary" type="submit" value="Save Changes">
</div>
</div>

</div>
{{Form::close()}}
</div>
</div>
</div>
</div>