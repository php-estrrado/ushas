<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Product;
class PrdShockingSale extends Model{
    use HasFactory;
    protected $table = 'prd_shock_sale';
    protected $fillable = ['title_cid','start_time','end_time','discount_type','discount_value','is_active','is_deleted','created_by','updated_by','user_type'];
 

    static function getShockingSales(){ 
         
           $shk_list = PrdShockingSale::where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->orderBy('id', 'DESC')->get();     
           
        

            if($shk_list){ 
            $data               =   [];
            foreach($shk_list    as  $row){
            $data[$row->id]['id']        =   $row->id;
            $data[$row->id]['title_cid']         =   $row->title_cid;
            $data[$row->id]['title']       =    PrdShockingSale::getCpnContent($row->title_cid);
            $data[$row->id]['start_time']       =   $row->start_time;
            $data[$row->id]['end_time']       =   $row->end_time;
            $data[$row->id]['deletable']       =   PrdShockingSale::getDeleteStatus($row->end_time);
            $data[$row->id]['discount_type']       =   $row->discount_type;
            $data[$row->id]['discount_value']       =   $row->discount_value;
            $data[$row->id]['is_active']       =   $row->is_active;
            $data[$row->id]['created_by']       =   $row->created_by; 
            $data[$row->id]['created_at']       =   $row->created_at; 
              }

            return $data;
            }else{ return false; }

        }

         static function getShockingSale($id){ 
         
           $shk_list = PrdShockingSale::where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->where('id', $id)->get();     
           
        

            if($shk_list){ 
            $data               =   [];
            foreach($shk_list    as  $row){
            $data['id']        =   $row->id;
            $data['title_cid']         =   $row->title_cid;
            $data['title']       =    PrdShockingSale::getCpnContent($row->title_cid);
            $data['start_time']       =   $row->start_time;
            $data['end_time']       =   $row->end_time;
            $data['deletable']       =   PrdShockingSale::getDeleteStatus($row->end_time);
            $data['discount_type']       =   $row->discount_type;
            $data['discount_value']       =   $row->discount_value;
            $data['is_active']       =   $row->is_active;
            $data['created_by']       =   $row->created_by; 
            $data['created_at']       =   $row->created_at; 
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

    static function getProducts($field_id){ 

        $exp = explode(",", $field_id);
        $prd_arr = [];
        foreach($exp as $k=>$v){

          if(Product::where("id",$v)->first()){ $prd_arr[] = Product::where("id",$v)->first()->name; }  
        }
        if($prd_arr){ 
        $return_cont = implode(",", $prd_arr);
        return $return_cont;
        }else{ return false; }
        }
        static function getDeleteStatus($field_id){ 
        date_default_timezone_set("Asia/Calcutta");
        $deletable = 0;
        if(strtotime($field_id) < time()) {
        $deletable = 1;
        }
      
        return $deletable;
        
        }

}
