<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use Intervention\Image\Facades\Image;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use App\Models\Tax;
use App\Models\AdminProduct;
use App\Models\ProductType;
use App\Models\PrdPrice;
use App\Models\PrdImage;
use App\Models\Language;
use App\Models\SellerInfo;
use App\Models\PrdOffer;
use DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

use Validator;
use Session;

class OfferController extends Controller{
     public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function offers()
    { 

        $data['title']              =   'Discounts';
        $data['menu']               =   'offers';
        $data['products']           =   DB::table('prd_products as p')
->select('p.*','ps.discount_value','ps.valid_to','ps.discount_type','ps.is_active as active','ps.id as offer_id')
->join('prd_special_offer as ps','ps.prd_id','=','p.id')
->where('p.is_deleted',0)->where('p.visible',1)->orderBy('id','desc')->get();

        $data['sellers']            =  []; //getDropdownData(SellerInfo::where('is_approved',1)->where('is_deleted',0)->get(),'seller_id','fname');
        $data['categories']         =   getDropdownData(Category::where('is_deleted',0)->get(),'category_id','cat_name');
        // dd($data);
        return view('admin.benefits.discounts.list',$data);
    }
    
   

     function createOffer(){
        $data['title']              =   'Create discount';
        $data['menu']               =   'offers';
     $products           =   Product::where('is_deleted',0)->where('visible',1)->where('is_active',1)->whereNotIn('id',function($query) {
   $query->select('prd_id')->from('prd_special_offer');})->get();
     $prod_arr = [];
     if($products) {
        
        foreach ($products as $pk => $pv) {
           $prod_arr[$pv->id] = $pv->name;
        }
     }
     $data['products']           = $prod_arr;
      $data['sellers']            = [];  //getDropdownData(SellerInfo::where('is_approved',1)->where('is_deleted',0)->get(),'seller_id','fname');
        // dd($data);
        return view('admin.benefits.discounts.create',$data);
    }

        public function sellerProducts(Request $request)
        { 

        $input = $request->all();
        $prod_opts = "<option value=''>Select Product</option>";
        if($input['sell_id']>0) {


     $products           =   Product::where('is_deleted',0)->where('visible',1)->where('is_active',1)->whereNotIn('id',function($query) {
   $query->select('prd_id')->from('prd_special_offer');})->where('seller_id',$input['sell_id'])->get();
     $prod_arr = [];
     if($products) {
        
        foreach ($products as $pk => $pv) {
      
           $prod_opts .= "<option value='".$pv->id."'>".$pv->name."</option>"; 
        }
     }

        }
        return  $prod_opts;
        }

     function saveOffer(Request $request){
        $post                   =   (object)$request->post(); 
       $validator= $request->validate([
        'discount_value'   =>  ['required'],
        'quantity_limit' => ['required'],
        'valid_from' => ['required'],
        'valid_to' => ['required']

        ], [], 
        [
        'discount_value' => 'Discount Value',
        'quantity_limit' => 'Quantity Limit',
        'country' => 'Country',
        'valid_from' => 'Valid From',
        'valid_to' => 'Valid To'
        ]);

        $prd_id                    =   $post->prd_id;
        $offr_arr = [];
        $offr_arr['org_id'] = 1;
        $offr_arr['prd_id'] = $prd_id;
        $offr_arr['discount_value'] = $post->discount_value;
        $offr_arr['discount_type'] = $post->discount_type;
        $offr_arr['quantity_limit'] = $post->quantity_limit;
        $offr_arr['valid_from'] = $post->valid_from;
        $offr_arr['valid_to'] = $post->valid_to;
        $offr_arr['is_active'] = $post->is_active;
        $offr_arr['is_deleted'] = 0;
        
        if($post->id >0){
        $offr_arr['updated_by'] = auth()->user()->id;
        $offr_arr['updated_at'] = date("Y-m-d H:i:s");
        $offrId =  PrdOffer::where('id',$post->id)->update($offr_arr); 
         $msg    =   'Discount updated successfully!';
        }else {
        $offr_arr['created_by'] = auth()->user()->id;
        $offr_arr['created_at'] = date("Y-m-d H:i:s");
        $offrId                  =   PrdOffer::create($offr_arr)->id;
         $msg    =   'Discount added successfully!';

        }
        
       
        if($offrId){   
Session::flash('message', ['text'=>'Discount created successfully','type'=>'success']);  
             }else{
Session::flash('message', ['text'=>'Discount creation failed','type'=>'danger']);
           }

       
            // dd($data);
            return redirect(route('admin.offers'));
    }
    public function offerStatus(Request $request)
        {
        $input = $request->all();
        
        if($input['id']>0) {
        $deleted =  PrdOffer::where('id',$input['id'])->update(array('is_active'=>$input['status']));
        
        return '1';
        }else {
        
        return '0';
        }
        
        }

    
}
