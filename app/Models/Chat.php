<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Chat extends Model
{
    use HasFactory;
    protected $table = 'chats';
    protected $fillable = ['created_by','prd_id','seller_id','sender_role_id','sale_id','created_at','updated_at'];
   // public function seller_info(){ return $this->belongsTo(SellerInfo ::class, 'seller_id'); }
    public function cust_info(){ return $this->belongsTo(CustomerInfo ::class, 'created_by'); }
   // public function Store($seller_id){ return DB::table('usr_stores')->where('seller_id', $seller_id)->first(); }
}
