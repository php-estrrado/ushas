<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderRefundPayment extends Model
{
    use HasFactory;
    protected $fillable = ['ref_id','sales_id','source','refund_mode','total','updated_platform','refund_tax','grand_total','bank_name','account_number','branch_name','ifsc_code'];
     public function customerrr(){ return $this->hasOne(SalesOrderAddress::class, 'sales_id','sales_id'); }
     public function order(){ return $this->belongsTo(SalesOrder ::class, 'sales_id'); }
     public function returninfo(){ return $this->belongsTo(SalesOrderReturn ::class, 'ref_id'); }
     public function payment(){ return $this->hasOne(SalesOrderPayment ::class, 'sales_id','sales_id'); }
     public function items(){ return $this->hasMany(SalesOrderItem ::class, 'sales_id','sales_id'); }
     public function shipping(){ return $this->hasOne(SalesOrderShipping ::class, 'sales_id'); }
}
