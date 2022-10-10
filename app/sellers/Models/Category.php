<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Category extends Model
{
    use HasFactory;
    protected $table = 'category';
    public $primaryKey = 'category_id';
    protected $fillable = ['cat_name_cid','cat_name', 'slug', 'cat_desc_cid','image','sort_order','is_active','created_by',];
    protected $guarded=[];
    
     static function get_content($field_id){ 

        $language =DB::table('glo_lang_lk')->where('is_active', 1)->first();
        $content_table=DB::table('cms_content')->where('cnt_id', $field_id)->where('lang_id', $language->id)->first();
        if($content_table){ 
        $return_cont = $content_table->content;
        return $return_cont;
        }
        else
            { return false; }
        }
    static function get_count($table,$field,$value){ 

        $table_data=DB::table($table)->where($field, $value)->where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->get();
        if($table_data){ 
        $return_count = count($table_data);
        return $return_count;
        }
        else{ return false; }
        }
}
