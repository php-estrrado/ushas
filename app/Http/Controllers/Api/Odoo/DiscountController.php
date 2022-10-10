<?php

namespace App\Http\Controllers\Api\Odoo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use DB;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\PrdAdminImage;
use App\Models\PrdOffer;
use App\Models\PrdPrice;

use Validator;

class DiscountController extends Controller
{
    public function create_discount(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'unique_id'=>['required','numeric','unique:prd_special_offer,odoo_id,1,is_deleted'],
        'product_id'=>['required','numeric'],
        'discount_value'   =>  ['required'],
        'quantity_limit' => ['required','numeric'],
        'valid_from' => ['required', 'date_format:Y-m-d','after_or_equal:'.date('Y-m-d')],
        'valid_to' => ['required', 'date_format:Y-m-d','after_or_equal:'.date('Y-m-d')],
        'discount_type'=>['required','in:percentage,amount']
        ]);
        if ($validator->passes()) {
        $prd_id                    =   $request->product_id;

        $product = Product::where('id',$prd_id)->where('is_deleted',0)->first();
        if(!$product)
        {
         return ['httpcode'=>'400','status'=>'error','error'=>'Invalid product id'];   
        }
        $offr_arr = [];
        $offr_arr['org_id'] = 1;
        $offr_arr['prd_id'] = $prd_id;
        $offr_arr['odoo_id'] = $request->unique_id;
        $offr_arr['discount_value'] = $request->discount_value;
        $offr_arr['discount_type'] = $request->discount_type;
        $offr_arr['quantity_limit'] = $request->quantity_limit;
        $offr_arr['valid_from'] = $request->valid_from;
        $offr_arr['valid_to'] = $request->valid_to;
        $offr_arr['platform'] = 'odoo';
        $offr_arr['is_active'] = 1;
        $offr_arr['is_deleted'] = 0;
        
        $prd_offerid =PrdOffer::where('prd_id',$prd_id)->where('is_deleted',0)->first();
        if($prd_offerid){
        $offr_arr['updated_by'] = 1;
        $offr_arr['updated_at'] = date("Y-m-d H:i:s");
        $offrId =  PrdOffer::where('id',$prd_offerid->id)->update($offr_arr); 
        return ['httpcode'=>'200','status'=>'success','message'=>'Discount Updated Successfully'];
        
        }else {
        $offr_arr['created_by'] = 1;
        $offr_arr['created_at'] = date("Y-m-d H:i:s");
        $offrId                  =   PrdOffer::create($offr_arr)->id;
        
        return ['httpcode'=>'200','status'=>'success','message'=>'Discount Created Successfully','data'=>['unique_id'=>$offrId]];

        } 
    }
    else
        {
            return ['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()];
        }
 }

 public function view_discount(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'product_id'=>['required','numeric']
        ]);
        if ($validator->passes()) {
        $prd_id                    =   $request->product_id;
        $offr_arr=[];
        $prd_offer = PrdOffer::where('prd_id',$prd_id)->where('is_deleted',0)->first();
            if($prd_offer)
            {
                $offr_arr['offer_id'] = $prd_offer->id;
                $offr_arr['odoo_id'] = $prd_offer->odoo_id;
                $offr_arr['prd_id'] = $prd_offer->prd_id;
                $offr_arr['product_name'] = $this->get_content($prd_offer->product->name_cnt_id);
                $offr_arr['discount_value'] = $prd_offer->discount_value;
                $offr_arr['discount_type'] = $prd_offer->discount_type;
                $offr_arr['quantity_limit'] = $prd_offer->quantity_limit;
                $offr_arr['valid_from'] = $prd_offer->valid_from;
                $offr_arr['valid_to'] = $prd_offer->valid_to;
                if($prd_offer->is_active==1){ $active = true;}else{$active = false;}
                $offr_arr['is_active'] = $active;
                return ['httpcode'=>'200','status'=>'success','message'=>'Discount','data'=>['discount'=>$offr_arr]];
            }
            else
            {
               return ['httpcode'=>'404','status'=>'error','message'=>'No discount applied','error'=>'No discount applied']; 
            }
        }
        else
        {
            return ['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()];
        }

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
    public function view_list(Request $request)
    {
      
        $offr_arr_list=[];
        $prd_offer = PrdOffer::where('is_deleted',0)->get();
            if(count($prd_offer)>0)
            {
                foreach($prd_offer as $row){ if($row->product)
                {
                    
                
                $offr_arr['offer_id'] = $row->id; 
                $offr_arr['odoo_id'] = $row->odoo_id;   
                $offr_arr['prd_id'] = $row->prd_id;
                $offr_arr['product_name'] = $this->get_content($row->product->name_cnt_id,1);
                $offr_arr['discount_value'] = $row->discount_value;
                $offr_arr['discount_type'] = $row->discount_type;
                $offr_arr['quantity_limit'] = $row->quantity_limit;
                $offr_arr['valid_from'] = $row->valid_from;
                $offr_arr['valid_to'] = $row->valid_to;
                if($row->is_active==1){ $active = true;}else{$active = false;}
                $offr_arr['is_active'] = $active;
                $offr_arr_list[]=$offr_arr;
                }
                }
                return ['httpcode'=>'200','status'=>'success','message'=>'Discount','data'=>['discount'=>$offr_arr_list]];
            }
            else
            {
               return ['httpcode'=>'404','status'=>'error','message'=>'Empty','error'=>'Empty']; 
            }
        
    }

    public function delete_discount(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'offer_id'=>['required','numeric']
        ]);
        if ($validator->passes()) {
         $prd_offer = PrdOffer::where('id',$request->offer_id)->first(); 
         if($prd_offer)  
         {
            $prd_offer->is_deleted=1;
            $prd_offer->save();
            return ['httpcode'=>'200','status'=>'success','message'=>'Offer Successfully Deleted'];
         }
         else
         {
            return ['httpcode'=>'404','status'=>'error','message'=>'Not found'];
         }

        }
        else
        {
            return ['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()];
        }
    }
        
}
