<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;
class Coupon extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'coupon';

    protected $fillable = ['org_id', 'cpn_title_cid', 'cpn_desc_cid','category_id','subcategory_id','seller_id','purchase_type',
'purchase_number','purchase_amount','ofr_value_type','ofr_value','ofr_type','ofr_code','ofr_min_amount','validity_type',
'valid_from','valid_to','valid_days','is_active','is_deleted','created_by','updated_by','created_at','updated_at'];

        static function getCoupons($cpn=''){ 
            if($cpn) {
                $cpn_list = Coupon::where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->where('id',$cpn)->orderBy('id', 'DESC')->get();
            }else {
           $cpn_list = Coupon::where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->orderBy('id', 'DESC')->get();     
            }
        

            if($cpn_list){ 
            $data               =   [];
            foreach($cpn_list    as  $row){
            $data[$row->id]['id']        =   $row->id;
            $data[$row->id]['cpn_title']         =   Coupon::getCpnContent($row->cpn_title_cid);
            $data[$row->id]['cpn_desc']       =    Coupon::getCpnContent($row->cpn_desc_cid);
            $data[$row->id]['cat_name']       =    Coupon::getCpnCat($row->category_id);
            if($row->subcategory_id) {
            $data[$row->id]['subcat_name']       =    Coupon::getCpnSubCat($row->subcategory_id);
            }else {
            $data[$row->id]['subcat_name']       =    "-";
            } 
            $data[$row->id]['seller_id']       =   $row->seller_id;
            $data[$row->id]['purchase_type']       =   $row->purchase_type;
            $data[$row->id]['purchase_number']       =   $row->purchase_number;
            $data[$row->id]['purchase_amount']       =   $row->purchase_amount;
            $data[$row->id]['ofr_value_type']       =   $row->ofr_value_type;
            $data[$row->id]['ofr_value']       =   $row->ofr_value; 
            $data[$row->id]['ofr_type']       =   $row->ofr_type; 
            $data[$row->id]['ofr_code']       =   $row->ofr_code; 
            $data[$row->id]['ofr_min_amount']       =   $row->ofr_min_amount; 
            $data[$row->id]['validity_type']       =   $row->validity_type;
            $data[$row->id]['valid_from']       =   $row->valid_from;
            $data[$row->id]['valid_to']       =   $row->valid_to;
            $data[$row->id]['valid_days']       =   $row->valid_days; 
            $data[$row->id]['is_active']       =   $row->is_active;
            $data[$row->id]['is_deleted']       =   $row->is_deleted;
            $data[$row->id]['created_at']       =   $row->created_at; 
            }

            return $data;
            }else{ return false; }

        }
    
        static function getCpnContent($field_id){ 

        $language =DB::table('glo_lang_lk')->where('is_active', 1)->first();
        $content_table=DB::table('cms_content')->where('cnt_id', $field_id)->where('lang_id', $language->id)->first();
        if($content_table){ 
        $return_cont = $content_table->content;
        return $return_cont;
        }else{ return false; }
        }
         static function getCpnCat($field_id){ 


        $cat_table=DB::table('category')->where('category_id', $field_id)->where('is_active', 1)->first();
        if($cat_table){ 
        $return_cont = $cat_table->cat_name;
        return $return_cont;
        }else{ return false; }
        }
        static function getCpnSubCat($field_id){ 


        $subcat_table=DB::table('subcategory')->where('subcategory_id', $field_id)->where('is_active', 1)->first();
        if($subcat_table){ 
        $return_cont = $subcat_table->subcategory_name;
        return $return_cont;
        }else{ return false; }
        }
      

      
}
