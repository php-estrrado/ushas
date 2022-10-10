<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    use HasFactory;
    protected $table        =   'usr_seller_settilments';
    protected $fillable     =   ['seller_id','admin_id','amount','is_active'];
    public function seller(){ return $this->belongsTo(Seller ::class, 'seller_id'); }
    public function totEarnings($sellerId){ return SalesOrder::where('seller_id',$sellerId)->where('payment_status','success'); }
    public function paidSettlement($sellerId){ return Settlement::where('seller_id',$sellerId)->where('is_deleted',0)->sum('amount'); }
}

