<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\BannerType;

class Banner extends Model
{
    use HasFactory;
    protected $table = 'banner_sliders';
     protected $fillable = ['org_id','banner_id','banner_type','identifier','title','title_cnt_id','desc_cnt_id','banner_desc','upload_type','media_type',
'media','thumb','upload_type','alt_text','btn_label','btn_link','is_active','is_deleted','created_at' ];

     static function getBanners(){ 
        $banner_list = Banner::where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();

        if($banner_list){ 
        $data               =   [];
        foreach($banner_list    as  $row){
        $data[$row->id]['id']        =   $row->id;
        $data[$row->id]['banner_id']        =   $row->banner_id;
        $data[$row->id]['banner_type']        =   BannerType::bannerType($row->banner_id);
        $data[$row->id]['identifier']        =   $row->identifier;
        $data[$row->id]['title']        =   $row->title;
        $data[$row->id]['title_cnt_id']        =   $row->title_cnt_id;
        $data[$row->id]['desc_cnt_id']        =   $row->desc_cnt_id;
        $data[$row->id]['banner_desc']       =    Banner::get_content($row->desc_cnt_id);
        $data[$row->id]['upload_type']       =   $row->upload_type; 
        $data[$row->id]['media_type']       =   $row->media_type; 
        $data[$row->id]['media']       =   $row->media; 
        $data[$row->id]['thumb']       =   $row->thumb; 
        $data[$row->id]['upload_type']       =   $row->upload_type; 
        $data[$row->id]['alt_text']       =   Banner::get_content($row->alt_text);  
        $data[$row->id]['btn_label']       = Banner::get_content($row->btn_label); 
        $data[$row->id]['btn_link']       =   $row->btn_link; 
        $data[$row->id]['is_active']       =   $row->is_active; 
        $data[$row->id]['is_deleted']       =   $row->is_deleted;
        $data[$row->id]['created_at']       =   $row->created_at; 
        }

        return $data;
        }else{ return false; }

        }
    
    static function get_content($field_id){ 

        $language =DB::table('glo_lang_lk')->where('is_active', 1)->first();
        $content_table=DB::table('cms_content')->where('cnt_id', $field_id)->where('lang_id', $language->id)->first();
        if($content_table){ 
        $return_cont = $content_table->content;
        return $return_cont;
        }else{ return false; }
        }

        static function getBannerData($bn_id){ 
        $banner_list = Banner::where("id",$bn_id)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();

        if($banner_list){ 
        $data               =   [];
        foreach($banner_list    as  $row){
        $data['id']        =   $row->id;
        $data['banner_id']        =   $row->banner_id;
        $data['banner_type']        =   BannerType::bannerType($row->banner_id);
        $data['identifier']        =   $row->identifier;
        $data['title']        =   $row->title;
        $data['title_cnt_id']        =   $row->title_cnt_id;
        $data['desc_cnt_id']        =   $row->desc_cnt_id;
        $data['banner_desc']       =    Banner::get_content($row->desc_cnt_id);
        $data['upload_type']       =   $row->upload_type; 
        $data['media_type']       =   $row->media_type; 
        $data['media']       =   $row->media; 
        $data['thumb']       =   $row->thumb; 
        $data['upload_type']       =   $row->upload_type; 
        $data['alt_text']       =   Banner::get_content($row->alt_text);  
        $data['btn_label']       = Banner::get_content($row->btn_label); 
        $data['alt_text_cid']       =   $row->alt_text;  
        $data['btn_label_cid']       = $row->btn_label; 
        $data['btn_link']       =   $row->btn_link; 
        $data['is_active']       =   $row->is_active; 
        $data['is_deleted']       =   $row->is_deleted;
        $data['created_at']       =   $row->created_at; 
        }

        return $data;
        }else{ return false; }

        }
}
