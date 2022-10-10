<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class VariableProdHist extends Model{
    use HasFactory;
    protected $table = 'prod_variation_hist';
    protected $fillable = [
        'seller_id','attr_data','price_data','stock_data','sku_data','weight','length','width','height','dynamic_ids','dynamic_prod_names','prd_id','assoc_prds','sale_price_data','sale_from_data','sale_to_data','min_order_data','bulk_order_data','image_data','created_by','created_at','is_active','is_deleted','updated_at'
    ];
   
}
