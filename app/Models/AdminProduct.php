<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminProduct extends Model{
    use HasFactory;
    protected $table = 'prd_admin_products';
    protected $fillable = ['name','name_cid','product_type','category_id','sub_category_id','brand_id','tag_ids','short_desc','content','spec_cnt_id','desc','is_active','is_deleted','created_by','created_at','updated_by','updated_at'];
    
    public function category(){ return $this->belongsTo(Category ::class, 'category_id'); }
    public function subCategory(){ return $this->belongsTo(Subcategory ::class, 'sub_category_id'); }
    public function brand(){ return $this->belongsTo(Brand ::class, 'brand_id'); }
    
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
}
