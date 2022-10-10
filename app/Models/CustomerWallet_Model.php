<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerWallet_Model extends Model
{
    protected $table = 'usr_cust_wallet';
    protected $fillable = ['user_id', 'source_id', 'source','credit','debit','desc','is_active','is_deleted','created_at','updated_at'];
    use HasFactory;
    public function customerInfo(){ return $this->hasOne(CustomerInfo ::class, 'user_id'); }
}
