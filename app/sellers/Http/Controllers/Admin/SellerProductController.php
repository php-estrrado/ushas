<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

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

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

use Validator;
use Session;

class SellerProductController extends Controller{
    public function __construct(){ $this->middleware('auth:admin'); }
    public function products()
    { 
        $data['title']              =   'Seller Products';
        $data['menuGroup']          =   'sellerGroup';
        $data['menu']               =   'product';
        $data['products']           =   Product::where('is_approved',1)->where('is_deleted',0)->orderBy('id','desc')->get();
        $data['sellers']            =   getDropdownData(SellerInfo::where('is_approved',1)->where('is_deleted',0)->get(),'seller_id','fname');
        return view('admin.seller_product.list',$data);
    }
    
    public function newProducts()
    { 
        $data['title']              =   'New ProductRequest List';
        $data['menuGroup']          =   'sellerGroup';
        $data['menu']               =   'new_product';
        $data['products']           =   Product::where('is_approved','!=',1)->where('is_deleted',0)->get();
        return view('admin.new_seller.list',$data);
    }
    
    function product($id=0,$sellerId=0,$type=''){
        $data['title']              =   'Add Product'; $catId = 0; $data['seller'] = false;
        $data['menuGroup']          =   'sellerGroup';
        $data['menu']               =   'product';
        $product                    =   Product::where('id',$id)->first();
        if($id>0){ 
            $data['title']          =   'Edit Seller'; 
            if($product){ $catId    =   $product->category_id; }
        }
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
        if($type == 'new'){ return view('admin.new_product.details',$data); }
        return view('admin.seller_product.details',$data);
    }
    
    function adminProduct($id){
        $admProduct                 =    AdminProduct::where('id',$id)->first();
        return $admProduct; 
    }
    
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
        $prd['product_type']    =   1; $prd['is_approved'] =   1; $prd['seller_id']   =   $post->seller_id; 
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
        $result             =   SellerInfo::where('id',$post->id)->update([$post->field => $post->value]);
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
        return view('admin.stock.list',$data);
    }
    
    function stock(Request $request){
        $post                       =  (object) $request->post();
        $data['title']              =   'Addd Stock';
        $data['menuGroup']          =   'sellerGroup';
        $data['menu']               =   'stock';
        $data['product']            =   Product::where('id',$post->prdId)->first();
        $data['seller']             =   SellerInfo::where('seller_id',$post->sellerId)->first();
        $data['price']              =   PrdPrice::where('prd_id',$post->prdId)->where('is_deleted',0)->orderBy('id','desc')->first();
        return view('admin.stock.details',$data);
    }
    
    function saveStock(Request $request){
        $post                       =  (object) $request->post();
     //   echo '<pre>'; print_r($post); echo '</pre>';
        $insId                      =   PrdStock::create($post->stock)->id;
        if($insId){ $type           =   'success'; $msg = 'Stock added successfully!'; }
        else{ $type = 'error';          $msg = 'Somthinf went wrong. Please try after some time'; }
        return back()->with($type,$msg);
    }
}
