<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerCoupon extends Model
{
    use HasFactory;
    protected $fillable = ['salesman_id','user_id','coupon_id','is_used','is_active','is_deleted','created_at','updated_at'];

    public function coupon(){ return $this->belongsTo(Coupon::class, 'coupon_id'); }
}
