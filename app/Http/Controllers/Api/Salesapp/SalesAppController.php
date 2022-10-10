<?php

namespace App\Http\Controllers\Api\Salesapp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\PasswordReset;
use App\Models\Email;
use App\Models\CustomerMaster;
use App\Models\CustomerInfo;
use App\Models\CustomerSecurity;
use App\Models\CustomerTelecom;
use App\Models\CustomerAddress;
use App\Models\Coupon;
use App\Models\CustomerCoupon;
use DB;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\PrdAdminImage;
use App\Models\PrdOffer;
use App\Models\PrdPrice;
use App\Models\PrdShock_Sale;
use App\Models\AssociatProduct;
use App\Models\AssignedAttribute;

use App\Models\SalesOrder;
use Validator;
class SalesAppController extends Controller
{
    public function offerlist(Request $request)
    {
        $data       =   $request->all(); 
        $post       =   (object)$request->post();
        $search     =   $request->keyword;
        $getcoupons = Coupon::getActiveCoupons($cpn='',$search);
        $cp_list = [];
        if(count($getcoupons)>0)
        {
            foreach($getcoupons as $rows)
            {
                $list['coupon_id']      = $rows['id'];
                $list['cpn_title']      = $rows['cpn_title'];
                $list['cpn_desc']       = $rows['cpn_desc'];
                $list['cat_name']       = $rows['cat_name'];
                $list['subcat_name']    = $rows['subcat_name'];
                $list['purchase_type']  = $rows['purchase_type'];
                $list['purchase_number'] = $rows['purchase_number'];
                $list['purchase_amount'] = $rows['purchase_amount'];
                $list['ofr_value_type']  =   $rows['ofr_value_type'];
                $list['ofr_value']       =   $rows['ofr_value']; 
                $list['ofr_type']        =   $rows['ofr_type']; 
                $list['ofr_code']        =   $rows['ofr_code']; 
                $list['ofr_min_amount']  =   $rows['ofr_min_amount']; 
                $list['validity_type']   =   $rows['validity_type'];
                $list['valid_from']      =   $rows['valid_from'];
                $list['valid_to']        =   $rows['valid_to'];
                $list['valid_days']      =   $rows['valid_days']; 
                if($rows['image']){$list['image']=config('app.storage_url').$rows['image'];}
                else
                {$list['image']=false;}
                $cp_list[] = $list;
            }
        }
        return ['httpcode'=>200,'status'=>'success','message'=>'Offers List','data'=>$cp_list];
    }

    public function offer_allocate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'=>['required','numeric'],
            'customer_id'=>['required','numeric'],
            'offer_id'=>['required','numeric']
        ]);


        $input = $request->all();

        if ($validator->passes()) {

            $custMst = CustomerMaster::where('crm_unique_id',$request->customer_id)->first();
            if($custMst)
            {
                $coupon = CustomerCoupon::where('user_id',$custMst->id)->where('coupon_id',$request->offer_id)->where('is_active',1)->where('is_deleted',0)->first();
                if($coupon)
                {
                    return ['httpcode'=>'400','status'=>'error','error'=>'Offer already assigned','message'=>'Offer already assigned'];
                }
                else
                {
                    $create = ['user_id'=>$custMst->id,'salesman_id'=>$request->user_id,'coupon_id'=>$request->offer_id];
                    CustomerCoupon::create($create);
                    return ['httpcode'=>200,'status'=>'success','message'=>'Offer Added'];
                }
            }
            else
            {
                return ['httpcode'=>'400','status'=>'error','error'=>'No customer found','message'=>'No customer found'];
            }
        }
        else
        {
            return ['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()];
        }
    }

     public function product_list(Request $request)
    {    
         $lang=$request->lang_id;
         
         //filter

        $prod_data= Product::where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->where('visible',1)->orderBy('id','desc');
        if($request->keyword)
        {
            $prod_data=$prod_data->where('name', 'like', "%{$request->keyword}%");
        }
        $prod_data=$prod_data->paginate(12);
       
        $products=[];
        
        
            if(!empty($prod_data))
            {
                
                foreach($prod_data as $row)
                {
                    
                    $prd_list['product_id']=$row->id;
                    $prd_list['odoo_id']=$row->odoo_id;
                    $prd_list['product_name']=$this->get_content($row->name_cnt_id,$lang);
                    $prd_list['category_name']=$this->get_content($row->category->cat_name_cid,$lang);
                    $prd_list['subcategory_name']=$this->get_content($row->subCategory->sub_name_cid,$lang);
                   
                    if($row->product_type==1){
                        $prd_list['actual_price']=(float)100;//number_format($row->prdPrice->price,2);
                        $ofrs = $this->getOfferPrice($row->id,$prd_list['actual_price']);
                        
                           $prd_list['offer_price'] = $ofrs['offer_price'];
                           $prd_list['offer'] = $ofrs['offer']; 
                        
                        
                        // $prd_list['sale_price']=$this->get_sale_price($row->id);
                        // $prd_list['special_ofr_price']=(float)99;//$this->get_special_ofr_price($row->id,$row->prdPrice->price);
                        $prd_list['product_type']='simple';
                        
                    }
                    else
                    {
                        $prd_list['actual_price']=$this->config_product_price($row->id);
                         $ofrs_config = $this->getOfferPrice($row->id,$prd_list['actual_price']);
                        
                           $prd_list['offer_price'] = $ofrs_config['offer_price'];
                           $prd_list['offer'] = $ofrs_config['offer']; 
                        
                        
                        // $prd_list['sale_price']=$this->get_sale_price($row->id);
                        // $c_price = $prd_list['actual_price'];
                        // $prd_list['special_ofr_price']=$this->get_special_ofr_price($row->id,$c_price);
                        $prd_list['product_type']='config';
                        
                    }
                    //$prd_list['shock_sale_price'] = $this->shock_sale_price($row->id);
                 //   $prd_list['short_description']=$this->get_content($row->short_desc_cnt_id,$lang);
                   // $prd_list['tag']=$this->get_product_tag($row->id,$lang);
                   // $prd_list['rating']=$this->get_rates($row->id);
                   // $prd_list['total_reviews']=$this->get_rates_count($row->id);
                    $prd_list['image']=$this->get_product_image($row->id); 
                    $associative_prdss=[];
                    if($row->product_type == 2)
                    {  
                        $prices = $this->config_product_price($row->id); 
                        $special_ofr_available=$this->get_special_ofr_value($prices,$row->id); 
                        $prd_ass = AssociatProduct::where('prd_id',$row->id)->where('is_deleted',0)->get();
                        if(count($prd_ass)>0)
                        {
                            foreach($prd_ass as $rows)
                            {
                            $product_visibility= Product::where('id',$rows->ass_prd_id)->where('is_active',1)->where('is_deleted',0)->first();
                            if($product_visibility){
                                $associative_prd=[];
                                $associative_prd[]=$this->ass_related_product1($product_visibility->id,$special_ofr_available,$lang,$login='');
                                if(count($associative_prd)>0)
                                {
                                    foreach($associative_prd as $ass)
                                    {
                                        if(!empty($ass['sub_attributes']))
                                        {
                                        foreach($ass['sub_attributes'] as $sub)
                                        {    
                                        $l_ass['product_id'] = $sub['product_id'];
                                        $l_ass['variation'] = $ass['attr_value'].'-'.$sub['attr_value'];
                                        $l_ass['actual_price'] =  $sub['actual_price'];
                                        $l_ass['offer_price']   = $sub['offer_price'];
                                        $l_ass['offer']= $sub['offer'];
                                        $associative_prdss[]=$l_ass;
                                        }
                                        }
                                        else
                                        {
                                            $l_ass['product_id'] = $ass['product_id'];
                                            $l_ass['variation'] = $ass['attr_value'];
                                            $l_ass['actual_price'] = $ass['actual_price'];
                                            $l_ass['offer_price'] = $ass['offer_price'];
                                            $l_ass['offer'] = $ass['offer'];

                                            $associative_prdss[]=$l_ass;
                                        }
                                    }
                                }
                            
                            
                             }
                            }
                        }
                        else
                        {
                            $associative_prdss=[];
                        }
                  }
                  else
                  {
                    $associative_prdss=[];
                  } 

            $prd_list['associative_products']=array_values(array_map("unserialize", array_unique(array_map("serialize", $associative_prdss))));
            //$prd_list['offers']=$this->available_offers($row->id,$lang); 
            $products[]=$prd_list;
                   
                }
                
                if(!empty($products))
                {
                    $total_products=$prod_data->total();
                }
                else
                {
                    $total_products=0;
                }
                return response()->json(['httpcode'=>200,'status'=>'success','data'=>['products'=>$products,'total_products'=>$total_products]]);

            }
            else
            {
               return response()->json(['httpcode'=>200,'status'=>'success','message'=>'Product not found']); 
            }
    }

    function config_product_price($prd_id)
        {
            $val = 0;
            $prd_ass = AssociatProduct::where('prd_id',$prd_id)->where('is_deleted',0)->get(['ass_prd_id']);
            if($prd_ass){
            $join = Product::join('prd_prices', 'prd_products.id', '=', 'prd_prices.prd_id')
                    ->selectRaw("MAX(prd_prices.price) AS max_val, MIN(prd_prices.price) AS min_val")
                    ->whereIn('prd_products.id',$prd_ass)->first();
                    if($join)
                    {
                        $min = $join->min_val;
                        $max = $join->max_val;
                        
                         $val = $min;
                        // if($min > 0 && $max > 0 && $min!=$max){
                        // $val = $min."-".$max;
                        // }
                        // else if($min > 0 && $max ==0)
                        // {
                        //     $val = $min;
                        // }
                        // else if($min==$max)
                        // {
                        //   $val = $min; 
                        // }
                        // else
                        // {
                        //     $val = $max;
                        // }
                    }
            }
            
            return $val;
                    
        }

        public function get_special_ofr_price($field_id,$price){ 

       $return_val=0;
       $current_date=Carbon::now();
       $rows = PrdOffer::where('is_deleted',0)->where('prd_id',$field_id)->whereDate('valid_from','<=',$current_date)->whereDate('valid_to','>=',$current_date)->first();        
        if($rows){ 
        $discount_val = $rows->discount_value;
        $discount_typ = $rows->discount_type;
        if($discount_typ=="percentage")
        {
            $dis = $price * ($discount_val/100);
            $return_val = $price-$dis;
        }
        else
        {
            $return_val = $price - $discount_val;
        }
        if($return_val>0)
        {
            return $return_val;
        }
        else
        {
             return false;
        }
        
        }
        else
            { $return_val=false;
                return $return_val; }
        }

    //Product sale price
    public function get_sale_price($field_id){ 

     
       $current_date=Carbon::now();
       $rows = PrdPrice::where('is_deleted',0)->where('prd_id',$field_id)->whereDate('sale_end_date','>=',$current_date)->orderBy('id','DESC')->first();        
        if($rows){ 
        $return_val = $rows->sale_price;
        if($return_val>0)
        {
            return $return_val;
        }
        else
        {
             return false;
        }
        
        }
        else
            { $return_val=false;
                return $return_val; }
        }

    function get_content($field_id,$lang){ 
     
        if($lang==''){
        $language =DB::table('glo_lang_lk')->where('is_active', 1)->where('is_default', 1)->first();
        $lang=$language->id;    
        }
        $content_table=DB::table('cms_content')->where('cnt_id', $field_id)->where('lang_id', $lang)->first();
        if(!empty($content_table)){ 
        $return_cont = $content_table->content;
        return $return_cont;
        }else {
            $language =DB::table('glo_lang_lk')->where('is_active', 1)->first();
            $language_id=$language->id;
            $content_table=DB::table('cms_content')->where('cnt_id', $field_id)->where('lang_id', $language_id)->first();
            if(!empty($content_table)){ 
            $return_cont = $content_table->content;
            return $return_cont;
            }else{
                return false;
            }
        }
     }

     function get_product_image($prd_id){
        $data     =   [];
        
        //$admin_pro=Product::where('id',$prd_id)->first();
        
        
        $product_seller       =   ProductImage::where('prd_id',$prd_id)->where('is_deleted',0)->get();
        $product_admin       =   PrdAdminImage::where('prd_id',$prd_id)->where('is_deleted',0)->get();
        if(!empty($product_seller))
        {
            foreach($product_seller as $k=>$row){ 
                if($row->image)
                {
                $val['image']       =   config('app.storage_url').$row->image;
                }
                if($row->thumbnail)
                {
                $val['thumbnail']   =   config('app.storage_url').$row->thumbnail;
                }
                $data[]             =   $val;
            }
        }
        else if(!empty($product_admin))
        {
            foreach($product_admin as $k=>$row){ 
                if($row->image)
                {
                $val['image']       =   config('app.storage_url').$row->image;
                }
                if($row->thumbnail)
                {
                $val['thumbnail']   =   config('app.storage_url').$row->thumbnail;
                }
                $data[]             =   $val;
            }
        } 
        
        else{ $data     =   []; } return $data;
        
    }

    function ass_related_product1($prd_id,$special_ofr_available,$lang,$login=''){
        $data     =   [];
       // dd($login);
        $prod_data       =   AssignedAttribute::where('is_deleted',0)->where('prd_id',$prd_id)->orderBy('attr_id','DESC')->groupBy('attr_id')->get();
         // dd( $prod_data  ) ;
           if(count($prod_data)>0)   { 
                foreach($prod_data as $row)  {
                    //$attr_list['id']=$row->id;
                    $attr_list['attr_id']=$row->attr_id;
                   $attr_list['product_id']=$row->prd_id;
                    $attr_list['attr_name']=$this->get_content($row->PrdAttr->name_cnt_id,$lang);
                    $attr_list['attr_value']=$row->attr_value;
                    $attr_list['image']=config('app.storage_url').$row->attrValue->image;
                    
                   
                    
                    //$attr_list['image']=config('app.storage_url').$row->attrValue->image;
                    $arrt_vals['list'][] =$this->inner_attribute($prd_id,$row->attr_id,$special_ofr_available,$row->id,$lang,$login='');
                    

                    if(empty($arrt_vals['list'][0]))
                    {
                        
                    $attr_list['sub_attributes'] = [];
                    $actual_price = $this->get_actual_price($row->prd_id);
                    // $attr_list['actual_price_quote']= $actual_price;
                    $attr_list['actual_price']= $actual_price;
                    $getofr = $this->getOfferPrice($row->prd_id,$actual_price);
                    $attr_list['offer_price'] = $getofr['offer_price'];
                    $attr_list['offer'] = $getofr['offer'];
                    // $sale_price =$this->get_sale_price($row->prd_id);
                    // $attr_list['sale_price']=$sale_price;
                    
                    // if($special_ofr_available>0)
                    //     {
                    //       $special_ofr_price = $actual_price - $special_ofr_available; 
                    //       $attr_list['special_ofr_price']=$special_ofr_price;
                    //     }
                    //     else
                    //     {
                    //       $attr_list['special_ofr_price']=false;  
                    //     }
                    
                    $attr_list['stock']=$row->prdStock($row->prd_id);
                    $attr_list['sku']=$row->Product->sku;
                    
                    
                    if($row->Product->is_out_of_stock==0)
                        {
                            $attr_list['is_out_of_stock']=false;
                        }
                        else
                        {
                            $attr_list['is_out_of_stock']=true;
                        }
                        if($row->Product->out_of_stock_selling==0)
                        {
                            $attr_list['out_of_stock_selling']=false;
                        }
                        else
                        {
                            $attr_list['out_of_stock_selling']=true;
                        }
                    }
                    else
                    {
                        $attr_list['sub_attributes'] =$this->inner_attribute($prd_id,$row->attr_id,$row->id,$special_ofr_available,$lang,$login='');
                    }
                    $data             =   $attr_list;
                }
             }
            else{ $data     =   []; } return $data;
        
    }

    public function get_special_ofr_value($price,$field_id){ 

       $return_val=0;
       $current_date=Carbon::now();
       $rows = PrdOffer::where('is_deleted',0)->where('prd_id',$field_id)->whereDate('valid_from','<=',$current_date)->whereDate('valid_to','>=',$current_date)->where('quantity_limit','>',0)->first();        
        if($rows){ 
        $discount_val = $rows->discount_value;
        $discount_typ = $rows->discount_type;
        if($discount_typ=="percentage")
        {
            $dis = $price * ($discount_val/100);
            $return_val = $dis;
        }
        else
        {
            $return_val = $discount_val;
        }
        if($return_val>0)
        {
            return $return_val;
        }
        else
        {
             return $return_val;
        }
        
        }
        else
            { //$return_val=false;
                return $return_val; }
        }


        function inner_attribute($prd_id,$attr_id,$rowId,$special_ofr_available,$lang,$login)
    {
        $data=[];
        $rowss = AssignedAttribute::where('is_deleted',0)->where('prd_id',$prd_id)->where('attr_id','!=',$attr_id)->where('id','!=',$rowId)->first();
        //$rows1 = AssignedAttribute::where('is_deleted',0)->where('attr_id',$attr_id)->whereNotIn('id',[$rowId])->get();
        
                     if($rowss && $rowss->id!=$rowId)
                    {
                        //$atr_inn['id']=$rowss->id;
                        $atr_inn['attr_id']=$rowss->attr_id;
                        $atr_inn['product_id']=$rowss->prd_id;
                        $atr_inn['attr_name']= $this->get_content($rowss->PrdAttr->name_cnt_id,$lang);
                        $atr_inn['attr_value']= $rowss->attr_value;
                        $atr_inn['image']=config('app.storage_url').$rowss->attrValue->image;
                        $actual_price = 100;//$this->get_actual_price($rowss->prd_id);
                        $getofr = $this->getOfferPrice($rowss->prd_id,$actual_price);
                        $atr_inn['offer_price'] = $getofr['offer_price'];
                        $atr_inn['offer'] = $getofr['offer'];
                        // $atr_inn['actual_price_quote']= $actual_price;
                        $atr_inn['actual_price']= $rowss->Product->prdPrice->price;
                        // if($special_ofr_available>0)
                        // {
                        //   $special_ofr_price = $actual_price - $special_ofr_available; 
                        //   $atr_inn['special_ofr_price']=$special_ofr_price;
                        // }
                        // else
                        // {
                        //   $atr_inn['special_ofr_price']=false;  
                        // }
                        // $sale_price =$this->get_sale_price($rowss->prd_id);
                        // $atr_inn['sale_price']=$sale_price;
                        
                        $atr_inn['stock']=$rowss->prdStock($rowss->prd_id);
                        $atr_inn['sku']=$rowss->Product->sku;
                        if($rowss->Product->is_out_of_stock==0)
                        {
                            $atr_inn['is_out_of_stock']=false;
                        }
                        else
                        {
                            $atr_inn['is_out_of_stock']=true;
                        }
                        if($rowss->Product->out_of_stock_selling==0)
                        {
                            $atr_inn['out_of_stock_selling']=false;
                        }
                        else
                        {
                            $atr_inn['out_of_stock_selling']=true;
                        }
                        //$atr_inn['subattr']=$this->inner_attribute_12($rowss->prd_id,$rowss->attr_id,$rowss->id,$lang);
                        $data[]             =   $atr_inn;
                        
                    
                    }return $data;
    }
    //Product ACTUAL price
    public function get_actual_price($field_id){ 

     
       //$current_date=Carbon::now();
       $rows = PrdPrice::where('is_deleted',0)->where('prd_id',$field_id)->orderBy('id','DESC')->first();        
        if($rows){ 
        $return_val = $rows->price;
        
            return $return_val;
        
        }
        else
            { $return_val=false;
                return $return_val; }
        }


        //OFFER/ SALE PRICE
        function getOfferPrice($prdid,$pricea)
        {
            $offer =[];
            $offer['offer']=false;
            $offer['offer_price']=false;
            $current_date=Carbon::now();
            
            $prod_data= Product::where('id',$prdid)->first();
            $shock = PrdShock_Sale::join('prd_shock_sale_products','prd_shock_sale.id','=','prd_shock_sale_products.shock_sale_id')
            ->where('prd_shock_sale.is_active',1)->where('prd_shock_sale.is_deleted',0)->whereDate('prd_shock_sale.start_time','<=',$current_date)->whereDate('prd_shock_sale.end_time','>=',$current_date)
            ->where('prd_shock_sale_products.is_active',1)->where('prd_shock_sale_products.is_deleted',0)->whereRaw("find_in_set($prod_data->id,prd_shock_sale_products.prd_id)")
            ->select('prd_shock_sale.*','prd_shock_sale_products.seller_id','prd_shock_sale_products.prd_id as shock_prd_id')->first();
           

       $return_val=$return_vals=0;
       $rows = PrdOffer::where('is_deleted',0)->where('prd_id',$prdid)->whereDate('valid_from','<=',$current_date)->whereDate('valid_to','>=',$current_date)->where('quantity_limit','>',0)->first();

       $rows_sale = PrdPrice::where('is_deleted',0)->where('prd_id',$prdid)->whereDate('sale_end_date','>=',$current_date)->orderBy('id','DESC')->first();        
        
            
            if($shock)
            {
                if($prod_data->product_type==1){
                $actual_price=$prod_data->prdPrice->price;
                if($shock->discount_type=="amount")
                    {
                        $offer['offer']=getCurrency()->name." ".$shock->discount_value." Off";
                        $discount_value = $shock->discount_value;
                        $unit_price = $actual_price-$discount_value;
                        $offer['offer_price']= $unit_price;
                       

                    }
                    else
                    {
                        $offer['offer']=$shock->discount_value."% Off";
                        $per=$shock->discount_value/100;
                        $per_value = (float)$actual_price*(float)$per;
                        $discount=(float)$actual_price-(float)$per_value;
                        $round= number_format($discount, 2);
                        $offer['offer_price']=$discount;
                    }
                }
                
                else
                {
                   $actual_price=$this->config_product_price($prod_data->id);
                if($shock->discount_type=="amount")
                    {
                        $offer['offer']=getCurrency()->name." ".$shock->discount_value." Off";
                        $discount_value = $shock->discount_value;
                        $unit_price = $actual_price-$discount_value;
                        $offer['offer_price']= $unit_price;
                       

                    }
                    else
                    {
                        $offer['offer']=$shock->discount_value."% Off";
                        $per=$shock->discount_value/100;
                        $per_value = (float)$actual_price*(float)$per;
                        $discount=(float)$actual_price-(float)$per_value;
                        $round= number_format($discount, 2);
                        $offer['offer_price']=$discount;
                    } 
                }
                //$offer_list[]=$offer;
                
                
            }
                    
        elseif($rows){ 
        $discount_val = $rows->discount_value;
        $discount_typ = $rows->discount_type;
        if($discount_typ=="percentage")
        {
            $offer['offer'] = $discount_val."% OFF";
            $dis = $pricea * ($discount_val/100);
            $return_val = $pricea-$dis;
        }
        else
        {
            $offer['offer'] = $discount_val." ".getCurrency()->name." OFF";
            $return_val = $pricea-$discount_val;
        }
        if($return_val>0)
        {
            $offer['offer_price'] = $return_val;
           
        }
        else
        {
             $offer['offer']=false;
            $offer['offer_price']=false;
        }
        
        }

        elseif($rows_sale){ 
        $return_vals = $rows_sale->sale_price;
        if($return_vals>0)
        {
            $offer['offer'] = false;
            $offer['offer_price']= $rows_sale->sale_price;
        }
        else
        {
             $offer['offer']=false;
             $offer['offer_price']=false;
        }
        
        }
        else
        {
            $offer['offer']=false;
            $offer['offer_price']=false;
        }
       
        return $offer;

        }

        
}
