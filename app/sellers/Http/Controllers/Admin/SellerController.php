<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\Category;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Telecom;
use App\Models\Seller;
use App\Models\SellerInfo;
use App\Models\SellerSecurity;
use App\Models\SellerTelecom;
use App\Models\StoreCategory;
use App\Models\Store;
use App\Models\SlotDelySlot;
use App\Models\SpotDelySlot;
use App\Models\AssignedSlot;

use App\Rules\Name;
use Validator;
use Session;

class SellerController extends Controller{
    public function __construct(){ $this->middleware('auth:admin'); }
    public function sellers(Request $request){ 
        $post                       =   (object)$request->post();
        if(isset($post->viewType))  {   $viewType = $post->viewType; }else{ $viewType = ''; }
        $data['title']              =   'Seller List';
        $data['menuGroup']          =   'userGroup';
        $data['menu']               =   'seller';
        $data['active']             =   '';
        $sellers                    =   SellerInfo::where('is_approved',1)->where('is_deleted',0);
        if(isset($post->active)     &&  $post->active != ''){ 
            $attributes             =   $sellers->where('is_active',$post->active); 
            $data['active']         =   $post->active;
        }
        $data['sellers']            =   $sellers->orderBy('id','desc')->get();
        if($viewType == 'ajax')     {   return view('admin.seller.list',$data); }else{ return view('admin.seller.page',$data); }
    }
    
    public function newSellers()
    { 
        $data['title']              =   'New Seller Request List';
        $data['menuGroup']          =   'userGroup';
        $data['menu']               =   'seller';
        $data['sellers']            =   SellerInfo::where('is_approved','!=',1)->where('is_deleted',0)->get();
        return view('admin.new_seller.list',$data);
    }
    
    function seller(Request $request, $id=0,$type=''){
        if($type == 'view')         {   $title = 'View Seller'; }else if($type == 'new'){ $title = 'View Seller'; }else if($id > 0){ $title = 'Edit Seller'; }else{ $title = 'Add Seller'; }
        $data['title']              =   'View Seller Detail'; 
        $data['menuGroup']          =   'userGroup';
        $data['menu']               =   'seller';
        $stateId = $countryId       =   0;
        $data['seller']             =   Seller::where('id',$id)->first();
        $data['info']               =   SellerInfo::where('id',$id)->first();
        $store                      =   Store::where('seller_id',$id)->where('is_deleted',0)->first();
        $data['store']              =   $store;
        if($store){ $countryId      =  (int)$store->country_id; $stateId = (int)$store->state_id; }
        $data['categories']         =   getDropdownData(Category::where('is_deleted',0)->get(),'category_id','cat_name');
        $data['countries']          =   getDropdownData(Country::where('is_deleted',0)->get(),'id','country_name');
        $data['states']             =   getDropdownData(State::where('country_id',$countryId)->where('is_deleted',0)->get(),'id','state_name');
        $data['cities']             =   getDropdownData(City::where('state_id',$stateId)->where('is_deleted',0)->get(),'id','city_name');
        $data['slots']              =   getDropdownData(SlotDelySlot::where('is_deleted',0)->get(),'id','slot_name');
        $data['spots']              =   getDropdownData(SpotDelySlot::where('is_deleted',0)->get(),'id','slot_name');
        $data['assSlots']           =   AssignedSlot::where('seller_id',$id)->where('slot_type','slot')->where('is_deleted',0)->get();
        $data['assSpots']           =   AssignedSlot::where('seller_id',$id)->where('slot_type','spot')->where('is_deleted',0)->get();
        $data['assCategories']      =   StoreCategory::where('seller_id',$id)->where('is_deleted',0)->get(['category_id']);
        $data['filters']            =   $request->post();
        if($type == 'new')          {   return view('admin.new_seller.details',$data); }else if($type == 'view'){ return view('admin.seller.view',$data); }
        return view('admin.seller.details',$data);
    }

    function validateSeller(Request $request){
        $post                   =   (object)$request->post(); $error = false;
        $info                   =   $request->post('info'); $store = $request->post('store'); $storeSet = $request->post('storeSet');
        $rules                  =   [
                                        'email'                 =>  'required|string|email|max:100',
                                        'phone'                 =>  'required|numeric|digits_between:7,12',
                                        'business_name'         =>  'required|string','store_name'  =>  'required|string',
                                        'director_name'         =>  ['required', 'string','max:100', new Name],
                                    ];
        $validator              =   Validator::make($post->info,$rules);
        $validEmail             =   SellerTelecom::ValidateUnique('email',$info['email'],$post->id);
        $validPhone             =   SellerTelecom::ValidateUnique('phone',$info['phone'],$post->id);
        if ($validator->fails()){
            $error['error']     =   'info';
           foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; }
        }
        if($validEmail){ $error['email']    =   $validEmail; $error['error']     =   'info';}
        if($validPhone){ $error['phone']    =   $validPhone; $error['error']     =   'info';}
        if($error) { return $error; }
        $rules                  =   ['address'  => 'required|string|max:250','country_id' =>  'required','state_id' =>  'required', 'city_id' =>  'required',];   
        $validator              =   Validator::make($store,$rules);
        if ($validator->fails()){
            $error['error']     =   'store';
           foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; }
        } 
        $rules                  =   ['categories'  => 'required','commission' =>  'required|numeric|min:1'];   
        $validator              =   Validator::make($storeSet,$rules);
        if ($validator->fails()){
            $error['error']     =   'storeSet';
           foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; }
        } 
        if($error) { return $error; }else{ return 'success'; }
    }   

    function saveSeller(Request $request){
        $post                   =   (object)$request->post(); 
        $info                   =   (object)$post->info;
        $store                  =   $post->store; 
        $storeSet               =   (object)$post->storeSet; 
        $images                 =   $request->file('image'); $filter = (object)$post->filter;
        $selInfo                   =   ['fname'=>$info->director_name,'ic_number'=>$info->ic_number,'is_active'=>$storeSet->is_active];
     //   $store['pack_charge']   =   $storeSet->pack_charge;
        $store['business_name'] =   $info->business_name;       $store['store_name']    =   $info->store_name;      $store['licence']    =   $info->licence;
        $store['commission']    =   $storeSet->commission;      $store['is_active']     =   $storeSet->is_active;
        $emailTypeId            =   Telecom::where('name','email')->first()->id; $phoneTypeId   =   Telecom::where('name','phone')->first()->id;
        if($post->id            >   0){ 
            $seller             =   Seller::where('id',$post->id)->first();
            $sellerId           =   $post->id; $storeId = $post->storeId;
                                    SellerTelecom::where('id',$seller->email)->update(['value'=>$info->email]);
                                    SellerTelecom::where('id',$seller->phone)->update(['value'=>$info->phone]);
                                    SellerInfo::where('seller_id',$sellerId)->update($selInfo); Store::where('id',$storeId)->update($store);
            $msg                =   'Seller updated successfully!';
        }else{
            $selInfo['is_approved']=   1;  $selInfo['approved_at'] = date('Y-m-d H:i:s');
            $sellerId           =   Seller::create(['username'=>$info->email,'password'=>Hash::make('123456')])->id; $selInfo['seller_id'] = $store['seller_id'] = $sellerId; 
            $storeId            =   Store::create($store)->id; SellerInfo::create($selInfo);
            $teleEmail          =   ['seller_id'=>$sellerId,'type_id'=>$emailTypeId,'value'=>$info->email]; 
            $telePhone          =   ['seller_id'=>$sellerId,'type_id'=>$phoneTypeId,'value'=>$info->phone]; 
            $emailId            =   SellerTelecom::create($teleEmail)->id;
            $phoneId            =   SellerTelecom::create($telePhone)->id; Seller::where('id',$sellerId)->update(['email'=>$emailId,'phone'=>$phoneId]);
            $msg                =   'Seller added successfully!';
        }
        $this->assignStoreCategories($storeSet->categories,$storeId,$sellerId);
        if($images){ foreach($images as $k=>$image){
            $imgName            =   $k.'logo.'.$image->extension();
            $path               =   '/app/public/stores/'.$storeId;
            $destinationPath    =   storage_path($path);
            $image->move($destinationPath, $imgName);
            $imgData[$k]        =   $path.'/'.$imgName;
            Store::where('id',$storeId)->update($imgData);
        } }
        if($post->formType      ==  'newSeller'){ 
            SellerInfo::where('seller_id',$sellerId)->update(['is_approved'=>1,'approved_at'=>date('Y-m-d H:i:s')]); $msg =   'Seller approved successfully!';
        }
        $data['title']          =   'Seller List';
        $data['active']         =   $filter->active;
        $sellers                =   SellerInfo::where('is_approved',1)->where('is_deleted',0);
        if(isset($post->active) &&  $post->active != ''){ 
            $attributes         =   $sellers->where('is_active',$post->active); 
            $data['active']     =   $post->active;
        }
        $data['sellers']        =   $sellers->orderBy('id','desc')->get();
        return view('admin.seller.list',$data);
    }
    
    function assignStoreCategories($catIds,$storeId,$sellerId){
        StoreCategory::where('store_id',$storeId)->update(['is_deleted'=>1]);
        foreach($catIds as $cId){ 
            if(StoreCategory::where('store_id',$storeId)->where('category_id',$cId)->count() > 0){ 
                StoreCategory::where('store_id',$storeId)->where('category_id',$cId)->update(['is_deleted'=>0]);
            }else{ StoreCategory::create(['seller_id'=>$sellerId,'store_id'=>$storeId,'category_id'=>$cId]); }
        } return true;
    }
    
    function updateStatus(Request $request){
        $post               =   (object)$request->post(); 
        $result             =   SellerInfo::where('id',$post->id)->update([$post->field => $post->value]);
        if($post->page      !=  'new_seller'){  Store::where('seller_id',$post->id)->update([$post->field => $post->value]); }
        if($post->field     ==  'is_approved'){
            Session::flash('success', $post->msg);
        }else{
            if($result){ return ['type'=>'success','id'=>$post->id]; }else{  return ['type'=>'warning','id'=>$post->id]; } 
        }
    }
}
