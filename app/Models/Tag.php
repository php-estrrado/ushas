<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;
class Tag extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'prod_tag';

    protected $fillable = ['org_id','name', 'tag_name_cid', 'tag_desc_cid','cat_id', 'subcat_id','is_active','is_deleted'];

        static function getTags(){ 
        $tags_list = Tag::where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->orderBy('id', 'DESC')->get();

            if($tags_list){ 
            $data               =   [];
            foreach($tags_list    as  $row){
            $data[$row->id]['id']        =   $row->id;
            $data[$row->id]['tag_name']         =   Tag::getTagsContent($row->tag_name_cid);
            $data[$row->id]['tag_desc']       =    Tag::getTagsContent($row->tag_desc_cid);
            $data[$row->id]['cat_name']       =    Tag::getTagsCat($row->cat_id);
            if($row->subcat_id) {
            $data[$row->id]['subcat_name']       =    Tag::getTagsSubCat($row->subcat_id);
            }else {
            $data[$row->id]['subcat_name']       =    "-";
            } 
            $data[$row->id]['is_active']       =   $row->is_active; 
            $data[$row->id]['is_deleted']       =   $row->is_deleted;
            $data[$row->id]['created_at']       =   $row->created_at; 
            }

            return $data;
            }else{ return false; }

        }
    
        static function getTagsContent($field_id){ 

        $language =DB::table('glo_lang_lk')->where('is_active', 1)->first();
        $content_table=DB::table('cms_content')->where('cnt_id', $field_id)->where('lang_id', $language->id)->first();
        if($content_table){ 
        $return_cont = $content_table->content;
        return $return_cont;
        }else{ return false; }
        }
         static function getTagsCat($field_id){ 


        $cat_table=DB::table('category')->where('category_id', $field_id)->where('is_active', 1)->first();
        if($cat_table){ 
        $return_cont = $cat_table->cat_name;
        return $return_cont;
        }else{ return false; }
        }
        static function getTagsSubCat($field_id){ 


        $subcat_table=DB::table('subcategory')->where('subcategory_id', $field_id)->where('is_active', 1)->first();
        if($subcat_table){ 
        $return_cont = $subcat_table->subcategory_name;
        return $return_cont;
        }else{ return false; }
        }
      

        static function getTag($tag_id){ 
        $tags_list = Tag::where("id",$tag_id)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();

            if($tags_list){ 
            $data               =   [];
            foreach($tags_list    as  $row){
            $data['id']        =   $row->id;
            $data['tag_name_cid']         =   $row->tag_name_cid;
            $data['tag_desc_cid']       =    $row->tag_desc_cid;
            $data['tag_name']         =   Tag::getTagsContent($row->tag_name_cid);
            $data['tag_desc']       =    Tag::getTagsContent($row->tag_desc_cid);
            $data['cat_name']       =    Tag::getTagsCat($row->cat_id);
            $data['cat_id']       =    $row->cat_id;
            if($row->subcat_id) {
            $data['subcat_name']       =    Tag::getTagsSubCat($row->subcat_id);
            $data['subcat_id']       =    $row->subcat_id;
            }else {
            $data['subcat_name']       =    "-";
            } 
            $data['is_active']       =   $row->is_active; 
            $data['is_deleted']       =   $row->is_deleted;
            $data['created_at']       =   $row->created_at; 
            }

            return $data;
            }else{ return false; }

        }
}
