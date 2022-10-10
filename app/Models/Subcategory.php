<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Subcategory extends Model
{
    use HasFactory;
    protected $table = 'subcategory';
    public $primaryKey = 'subcategory_id';
    protected $guarded=[];
    protected $fillable = ['category_id','odoo_id','platform','sub_name_cid','subcategory_name','slug','code','sabcatlist_id','local_name','desc_cid','parent','level','image','is_active','created_by'];
    
    static function get_content($field_id,$lang=''){ 
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
}
