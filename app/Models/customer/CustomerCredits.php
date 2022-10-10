<?php

namespace App\Models\customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerCredits extends Model
{
    use HasFactory;
    protected $table = 'usr_cust_credits';
    protected $guarded = [];

    public function sales(){ return $this->belongsTo(\App\Models\SalesOrder ::class, 'ref_id'); }
    public function user(){ return $this->belongsTo(\App\Models\CustomerInfo ::class, 'user_id', 'user_id'); }
}
