<?php

namespace App\Models\customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerCreditLogs extends Model
{
    use HasFactory;
    protected $table = 'usr_cust_credits_log';
    protected $guarded = [];
}
