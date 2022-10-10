<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderPayment extends Model
{
    use HasFactory;
    protected $fillable = ['sales_id','payment_method_id', 'payment_type', 'transaction_id','payment_data','amount', 'payment_status',];
    
    
}
