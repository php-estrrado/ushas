<?php

namespace App\Models\customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerWallet_Model extends Model
{
    protected $table = 'usr_cust_wallet';
    protected $guarded=[];
    use HasFactory;
    public function customerInfo(){ return $this->hasOne(CustomerInfo ::class, 'user_id'); }
}
