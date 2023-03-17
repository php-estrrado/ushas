<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;
    protected $table = 'usr_cart_item';
    protected $fillable = ['org_id','cart_id','product_id','quantity','is_active','prd_assign_id','is_deleted','created_by','updated_by','created_at','updated_at','assortment_id','crm_product_id','custom','assortment_qty'];
}
