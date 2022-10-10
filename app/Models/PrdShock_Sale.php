<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class PrdShock_Sale extends Model
{
    use HasFactory;
    protected $table = 'prd_shock_sale';
    public function Productdetail($prd_id){ return Product::where('id',$prd_id)->first(); }
    public function Product(){ return $this->belongsTo(Product::class, 'prd_id'); }
    public function Store($seller_id){ return DB::table('usr_stores')->where('seller_id', $seller_id)->first(); }
    
    
    /*********API***********/
    public function product_data($prd_id){ return DB::table('prd_products')->where('id', $prd_id)->first(); }
    public function category_data($cat_id){ return Category::where('category_id', $cat_id)->first(); }
    public function subcategory_data($scat_id){ return Subcategory::where('subcategory_id', $scat_id)->first(); }
    public function brand_data($brand){ return Brand::where('id', $brand)->first(); }
    public function price_data($prd){ return PrdPrice::where('prd_id', $prd)->where('is_deleted',0)->first(); }


}
