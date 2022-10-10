<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerInfo extends Model{
    use HasFactory;
    protected $table = 'usr_seller_info';
    protected $fillable = ['seller_id','fname', 'mname', 'lname','ic_number','avatar','is_active'];
    public function sellerMst(){ return $this->belongsTo(Seller ::class, 'seller_id'); }
    public function store($sellerId){ return Store::where('seller_id',$sellerId)->first(); }
}
