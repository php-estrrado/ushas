<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderShipping extends Model
{
    use HasFactory;
    protected $fillable = ['sales_id','ship_operator_id', 'ship_operator', 'ship_method','rate_id','price', 'weight',];
    
    
}
