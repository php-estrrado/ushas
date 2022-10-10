<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderItemOption extends Model
{
    use HasFactory;
	protected $table = 'sales_order_item_options';
    protected $fillable = ['sales_id','sales_item_id','prd_id','attr_id','attr_value_id','attr_name','attr_value','created_at','updated_at','is_deleted'];
    
    
}
