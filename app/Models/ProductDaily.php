<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class ProductDaily extends Model
{
    use HasFactory;
    protected $table = 'prd_daily_deals';
    public function Product(){ return $this->belongsTo(Product::class, 'id'); }
    public function Productdetail($prd_id){ return Product::where('id',$prd_id)->first(); }
    public function ProductPrice($prd_id){ return DB::table('prd_prices')->where('prd_id', $prd_id)->where('is_deleted', 0)->first(); }
    public function Store($seller_id){ return DB::table('usr_stores')->where('seller_id', $seller_id)->first(); }

}
