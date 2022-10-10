<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    use HasFactory;
    protected $fillable     =   [
                                    'org_id','order_id','cust_id','seller_id','total','discount','tax','packing_charge','wallet_amount','g_total',
                                    'discount_type','coupon_id','order_status'
                                ];
    public function seller(){ return $this->belongsTo(Seller ::class, 'seller_id'); }
    public function totEarnings($sellerId){ return SalesOrder::where('seller_id',$sellerId)->where('payment_status','success'); }
    public function paidSettlement($sellerId){ return Settlement::where('seller_id',$sellerId)->where('is_deleted',0)->sum('amount'); }
}

