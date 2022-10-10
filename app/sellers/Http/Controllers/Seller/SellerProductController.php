<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use Intervention\Image\Facades\Image;

use App\Models\Store;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Brand;
use App\Models\Seller;
use App\Models\SellerInfo;
use App\Models\Product;
use App\Models\Tax;
use App\Models\AdminProduct;
use App\Models\ProductType;
use App\Models\PrdPrice;
use App\Models\PrdImage;
use App\Models\PrdStock;
use App\Models\Language;
use App\Models\CmsContent;
use App\Models\PrdAttribute;
use App\Models\PrdAttributeValue;
use App\Models\AssignedAttribute;
use App\Models\AssociatProduct;
use App\Models\PrdAssignedTag;
use App\Models\RelatedProduct;
use App\Models\PrdOffer;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

use Validator;
use Session;

class SellerProductController extends Controller{
     public function __construct()
    {
        $this->middleware('auth:seller');
    }
    public function products()
    { 

        $data['title']              =   'Seller Products';
        $data['menuGroup']          =   'sellerGroup';
        $data['menu']               =   'product';
        $data['products']           =   Product::where("seller_id",auth()->user()->id)->where('is_deleted',0)->orderBy('id','desc')->get();
        $data['sellerinfo']= SellerInfo::where('is_approved',1)->where("seller_id",auth()->user()->id)->where('is_deleted',0)->first();
        $data['sellers']            =   getDropdownData(SellerInfo::where('is_approved',1)->where('is_deleted',0)->get(),'seller_id','fname');
        $data['categories']         =   getDropdownData(Category::where('is_deleted',0)->get(),'category_id','cat_name');
        // dd($data);
        return view('seller.my_products.list',$data);
    }
    
    // public function newProducts()
    // { 
    //     $data['title']              =   'New ProductRequest List';
    //     $data['menuGroup']          =   'sellerGroup';
    //     $data['menu']               =   'new_product';
    //     $data['products']           =   Product::where('is_approved','!=',1)->where('is_deleted',0)->get();
    //     return view('admin.new_seller.list',$data);
    // }
    
    function product($id=0,$sellerId=0,$type=''){

        $data['title']              =   'Add Product'; $catId = 0; $data['seller'] = false;
        $data['menuGroup']          =   'sellerGroup';
        $data['menu']               =   'product';
        $product                    =   Product::where('id',$id)->first();
        if($id>0){ 
            
            if($product){ $catId    =   $product->category_id; }
        }
        if($type == 'view')     {   $title = 'View Product'; }else if($id > 0){ $title = 'Edit Product'; }else{ $title = 'Add Product'; }
        $data['title']          =   $title; 
        $data['product']            =   $product;
        if($type                    ==  'new'){ $data['title']   =   'View Product Detail'; }
        $data['categories']         =   getDropdownData(Category::where('is_deleted',0)->get(),'category_id','cat_name');
        $data['sub_cats']           =   getDropdownData(Subcategory::where('is_deleted',0)->get(),'subcategory_id','subcategory_name');
        $data['brands']             =   getDropdownCmsData(Brand::where('is_active',1)->where('is_deleted',0)->get(),'id','brand_name_cid');
        $data['taxes']              =   getDropdownCmsData(Tax::where('is_active',1)->where('is_deleted',0)->get(),'id','tax_name_cid');
        $data['languages']          =   getDropdownData(Language::where('is_active',1)->where('is_deleted',0)->get(),'id','glo_lang_name');
        if($sellerId                >   0){ $data['seller'] = SellerInfo::where('seller_id',$sellerId)->first(); }
        $data['adminProducts']      =   getDropdownData(AdminProduct::where('is_active',1)->where('is_deleted',0)->get(),'id','name');
        $data['attributes']         =   $this->getAttributes();

       
        if($type == 'new'){ return view('seller.my_products.details',$data); }
        if($type == 'view')     { 

              $data['prod_attributes']         =  $this->getAssignedAttributes($id); 
        $data['price']         =   PrdPrice::where('prd_id',$id)->first();
        $data['images']         =   PrdImage::where('prd_id',$id)->where('is_deleted',0)->get();
            // dd($data);
            return view('seller.my_products.view',$data); 
        }else{ return view('seller.my_products.details',$data); }
        return view('seller.my_products.details',$data);
    }
    
    function adminProduct($id){
        $admProduct                 =    AdminProduct::where('id',$id)->first();
        return $admProduct; 
    }
       function getAssignedAttributes($id){
        $qry                        =   AssignedAttribute::where('prd_id',$id)->where('is_deleted',0)->get();

        if($qry){ foreach($qry      as  $attr){
            $attrs[$attr->attr_id]['name']    =   $this->AttrName($attr->attr_id);
            if($attr->attr_value !="") {
                $attrs[$attr->attr_id]['value']    =   $attr->attr_value;
            }else {
               $attrs[$attr->attr_id]['value']    =   $this->getAssignedAttrValues($attr->attr_val_id); 
            }
            
        } 
         return $attrs; }else{         return false; }
    }
    function getAssignedAttrValues($attr_val_id){ return   PrdAttributeValue::where('id',$attr_val_id)->where('is_active',1)->where('is_deleted',0)->first()->name; }
    function AttrName($attr_id){ return   PrdAttribute::where('id',$attr_id)->where('is_active',1)->where('is_deleted',0)->first()->name; }

    function getAttributes(){
        $qry                        =   PrdAttribute::where('is_active',1)->where('is_deleted',0)->get();
        if($qry){ foreach($qry      as  $k=>$row){
            $attrs[$k]              =   $row; 
            $attrs[$k]['values']    =   $this->getAttrValues($row->id);
        } return $attrs; }else{         return false; }
    }
    
    function getAttrValues($attrId){ return   PrdAttributeValue::where('attr_id',$attrId)->where('is_active',1)->where('is_deleted',0)->get(); }

    function validateProduct(Request $request){
        $post                   =   (object)$request->post(); $error = $validName = false;
        $prd                   =   $request->post('prd'); $price = $request->post('price'); $attr = $request->post('attr');
        if($post->prd_option    ==  'option2'){ $rules          =   ['short_desc' => 'required|string|max:250']; }
        else{ $rules            =   [
                                        'name'                  =>  'required|string|max:100','category_id'   =>  'required',
                                        'sub_category_id'       =>  'required', 'short_desc'    =>  'required|string|max:250'
                                    ];
        }
        $validator              =   Validator::make($post->prd,$rules);
        if($post->prd_option    ==  'option1'){ $validName      =   Product::ValidateUnique('name',$prd['name'],$post->id); }
        if ($validator->fails()){
            $error['error']     =   'prd';
           foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; }
        }
        if($validName){ $error['name']    =   $validName; $error['error']     =   'prd';}
        if($error) { return $error; }
        
        $rules                  =   ['price'  => 'required|numeric|min:1',];   
        $validator              =   Validator::make($post->price,['price'  => 'required|numeric|min:1','tax'=>'required']);
        if ($validator->fails()){
            $error['error']     =   'price';
           foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; }
        } 
        if($error) { return $error; }else{ return 'success'; }
    }   

        function saveProduct(Request $request){
        $post                   =   (object)$request->post(); 
        $prd                    =   $post->prd;
        $price                  =   $post->price; 
        $attrs                  =   (object)$post->attr; 
        $images                 =   $request->file('image'); 
    //    echo '<pre>'; print_r($post); echo '</pre>'; 
    //    echo '<pre>'; print_r($request->file()); echo '</pre>'; die;
        if($post->prd_option    ==  'option2'){
            $adPrd              =   AdminProduct::where('id',$post->admin_prd_id)->first();
            $prd['name']        =   $adPrd->name; $prd['category_id'] = $adPrd->category_id; $prd['sub_category_id'] = $adPrd->sub_category_id; 
            $prd['brand_id']    =   $adPrd->brand_id; $prd['tax_id'] = $price['tax']; $prd['admin_prd_id'] = $post->admin_prd_id; $prd['created_by'] = auth()->user()->id;
        }
        $prd['product_type']    =   1; $prd['is_approved'] =   0; $prd['seller_id']   =   $post->seller_id; 
        $prdId                  =   Product::create($prd)->id; 
        $price['created_by']    =   auth()->user()->id;$price['prd_id'] =  $prdId; unset($price['tax']); PrdPrice::create($price);
          
        if($attrs){ foreach     (   $attrs as $k=>$attr){ 
            $prdAttr            =   ['prd_id'=>$prdId,'attr_id'=>$k,'created_by'=>auth()->user()->id];
            if(!isset($attr['valId'])){ $attr['valId'] = NULL; } if(!isset($attr['value']) || $attr['value'] == ''){ $attr['value'] = NULL; }
            $prdAttr['attr_val_id'] = $attr['valId']; $prdAttr['attr_value'] = $attr['value'];
            if($attr['valId']  !=  NULL    ||  $attr['value'] != NULL){  AssignedAttribute::create($prdAttr); }
        } }
        
        $cmsContent             =   ['name_cnt_id'=>$prd['name'],'short_desc_cnt_id'=>$prd['short_desc'],'desc_cnt_id'=>$prd['desc'],'content_cnt_id'=>$prd['content']];
        foreach($cmsContent     as  $k=>$content){ 
            $cntId = $this->addCmsContent(0,$post->lang_id,$content);
            Product::where('id',$prdId)->update([$k=>$cntId]);
        }
        
        if($images){ foreach($images as $k=>$image){
            $imgName            =   time().'.'.$image->extension();
            $path               =   '/app/public/products/'.$prdId;
            $destinationPath    =   storage_path($path.'/thumb');
            $img                =   Image::make($image->path());
            if(!file_exists($destinationPath)) { mkdir($destinationPath, 755, true);}
            $img->resize(250, 250, function($constraint){ $constraint->aspectRatio(); })->save($destinationPath.'/'.$imgName);
            $destinationPath    =   storage_path($path);
            $image->move($destinationPath, $imgName);
            PrdImage::create(['prd_id'=>$prdId,'image'=>$path.'/'.$imgName,'thumb'=>$path.'/thumb/'.$imgName,'created_by'=>auth()->user()->id]);
        } }
        $msg    =   'Product added successfully!';
        if($prdId){   return      back()->with('success',$msg); }else{ return back()->with('error','Somthing went wrong. Plese try again after some time.'); }
    }
    
    function addCmsContent($cntId,$l, $cnt){
        $qry                =   CmsContent::where('cnt_id',$cntId)->where('is_deleted',0)->first(); $insId = false;
        $query              =   CmsContent::where('cnt_id',$cntId)->where('is_deleted',0)->where('lang_id',$l)->first();
        if($query)          {   CmsContent::where('id',$query->id)->update(['content'=>$cnt,'updated_by'=>auth()->user()->id]); }
        else if($qry)       {   $insId   =   CmsContent::create(['cnt_id'=>$cntId,'lang_id'=>$l,'content'=>$cnt,'created_by'=>auth()->user()->id])->id; }
        else{
            $cms            =   CmsContent::orderBy('cnt_id','desc')->first(); if($cms){ $cntId = ($cms->cnt_id+1); }else{ $cntId = 1; }
            $insId          =   CmsContent::create(['cnt_id'=>$cntId,'lang_id'=>$l,'content'=>$cnt,'created_by'=>auth()->user()->id])->id;
        }
        return $cntId;
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
        $result             =   Product::where('id',$post->id)->update([$post->field => $post->value]);
        if($post->page      !=  'new_seller'){  Store::where('seller_id',$post->id)->update([$post->field => $post->value]); }
        if($post->field     ==  'is_deleted' || $post->field  == 'is_approved'){
//        $data['title']      =   'Seller List';
//        $data['sellers']    =   Seller::get();
//            return view('admin.seller.list.content',$data);
            Session::flash('success', $post->msg);
        }else{
            if($result){ return ['type'=>'success','id'=>$post->id]; }else{  return ['type'=>'warning','id'=>$post->id]; } 
        }
    }
    
    
    
     function specialOffer($prd_id){
        $data['title']              =   'Product Special Offer';
        $data['menuGroup']          =   'sellerGroup';
        $data['menu']               =   'specialoffer';
        $offer                     =   PrdOffer::where('prd_id',$prd_id)->where('is_deleted',0)->first();
        if(isset($offer)){ 
            $data['offer']             =   $offer;
         }
         $data['prd_id']             =   $prd_id;
        // dd($data);
        return view('seller.my_products.offer',$data);
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
         $msg    =   'Special Offer updated successfully!';
        }else {
        $offr_arr['created_by'] = auth()->user()->id;
        $offrId                  =   PrdOffer::create($offr_arr)->id;
         $msg    =   'Special Offer added successfully!';

        }
        
       
        if($offrId){   return      back()->with('success',$msg); }else{ return back()->with('error','Somthing went wrong. Plese try again after some time.'); }
    }
    
    function stocks(){
        $data['title']              =   'Product Stock List';
        $data['menuGroup']          =   'sellerGroup';
        $data['menu']               =   'stock';
        $stocks                     =   PrdStock::where('is_deleted',0)->groupBy('prd_id')->get();
        if($stocks){ foreach        (   $stocks as $row){
            $row->stock             =   PrdStock::where('prd_id',$row->prd_id)->where('is_deleted',0)->sum('qty');
            $data[]                 =   $row;
        } }
        $data['stocks']             =   $stocks;
        return view('seller.stock.list',$data);
    }
    
    function stock(Request $request){
        $post                       =  (object) $request->post();
        $data['title']              =   'Addd Stock';
        $data['menuGroup']          =   'sellerGroup';
        $data['menu']               =   'stock';
        $data['product']            =   Product::where('id',$post->prdId)->first();
        $data['seller']             =   SellerInfo::where('seller_id',$post->sellerId)->first();
        $data['price']              =   PrdPrice::where('prd_id',$post->prdId)->where('is_deleted',0)->orderBy('id','desc')->first();
        return view('seller.stock.details',$data);
    }
    
    function saveStock(Request $request){
        $post                       =  (object) $request->post();
     //   echo '<pre>'; print_r($post); echo '</pre>';
        $insId                      =   PrdStock::create($post->stock)->id;
        if($insId){ $type           =   'success'; $msg = 'Stock added successfully!'; }
        else{ $type = 'error';          $msg = 'Somthing went wrong. Please try after some time'; }
        return back()->with($type,$msg);
    }
}
