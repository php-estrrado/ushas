<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Product extends Model{
    use HasFactory;
    protected $table = 'prd_products';
    protected $fillable = [
        'seller_id','product_type','category_id','sub_category_id','brand_id','tax_id','name',' name_cnt_id ','short_desc_cnt_id','desc_cnt_id','content_cnt_id',
        'is_out_of_stock','min_stock_alert','commission','commi_type','is_approved','visible','is_active','created_by'
    ];
    public function prdType(){ return $this->belongsTo(ProductType ::class, 'product_type'); }
    public function category(){ return $this->belongsTo(Category ::class, 'category_id'); }
    public function seller(){ return $this->belongsTo(SellerInfo ::class, 'seller_id'); }
    public function subCategory(){ return $this->belongsTo(Subcategory ::class, 'sub_category_id'); }
    public function prdPrice(){ return $this->hasOne(ProductPrice ::class, 'prd_id')->latest(); }    
    public function prdImage(){ return $this->hasMany(ProductImage ::class, 'prd_id'); }   
    public function brand(){ return $this->belongsTo(Brand ::class, 'brand_id'); }
    
    static function ValidateUnique($field,$value,$id) {
        $query                      =   Product::where($field,$value)->where('is_deleted',0)->first();
        if($query){ if($query->id   !=  $id){ return 'This '.$field.' already has been taken'; }else{ return false; } }else{ return false; }
    }
    
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
