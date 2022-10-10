<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderItem extends Model
{
    use HasFactory;
	protected $table = 'sales_order_items';
    protected $fillable = ['sales_id','parent_id','prd_id','prd_type','prd_name','price','qty',' total','discount','tax','row_total','coupon_id',];
    
    
}
