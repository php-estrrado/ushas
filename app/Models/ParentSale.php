<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentSale extends Model
{
    use HasFactory;
    protected $table = 'sale_order_parent';
    protected $fillable = ['org_id','user_id', 'tot_amount', 'platform_coupon_id','discount_type','discount_amt','wallet_amt','grand_total','created_at','updated_at','currency_code','currency_amount','currency_rate'];

    public function customer($id){ return CustomerInfo::where('user_id',$id)->first(); }
    public function order($id){ return SalesOrder::where('parent_sale_id',$id)->first(); }
    
    public function orders(){ return $this->hasOne(SalesOrder::class, 'parent_sale_id'); }
    public function seller_orders(){ return $this->hasMany(SalesOrder ::class, 'parent_sale_id'); }
}

