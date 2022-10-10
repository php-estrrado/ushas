<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;
class Brand extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'prd_brand';

    protected $fillable = ['org_id', 'brand_name_cid', 'brand_desc_cid','is_active','is_deleted'];

        static function getBrands(){ 
        $brands_list = Brand::where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->orderBy('id','desc')->get();

        if($brands_list){ 
        $data               =   [];
        foreach($brands_list    as  $row){
        $data[$row->id]['id']        =   $row->id;
        $data[$row->id]['brand_name']         =   Brand::getBrandsContent($row->brand_name_cid);
        $data[$row->id]['brand_desc']       =    Brand::getBrandsContent($row->brand_desc_cid);
        $data[$row->id]['is_active']       =   $row->is_active; 
        $data[$row->id]['is_deleted']       =   $row->is_deleted;
        $data[$row->id]['created_at']       =   $row->created_at; 
        }

        return $data;
        }else{ return false; }

        }
    
        static function getBrandsContent($field_id){ 

        $language =DB::table('glo_lang_lk')->where('is_active', 1)->first();
        $content_table=DB::table('cms_content')->where('cnt_id', $field_id)->where('lang_id', $language->id)->first();
        if($content_table){ 
        $return_cont = $content_table->content;
        return $return_cont;
        }else{ return false; }
        }


        static function getBrand($brand_id){ 
        $brands_list = Brand::where("id",$brand_id)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();

        if($brands_list){ 
        $data               =   [];
        foreach($brands_list    as  $row){
        $data['id']        =   $row->id;
        $data['brand_name_cid']         =   $row->brand_name_cid;
        $data['brand_desc_cid']       =    $row->brand_desc_cid;
        $data['brand_name']         =   Brand::getBrandsContent($row->brand_name_cid);
        $data['brand_desc']       =    Brand::getBrandsContent($row->brand_desc_cid);
        $data['is_active']       =   $row->is_active; 
        $data['is_deleted']       =   $row->is_deleted;
        $data['created_at']       =   $row->created_at; 
        $data['language']       =   $row->created_at; 
        }

        return $data;
        }else{ return false; }

        }
}
