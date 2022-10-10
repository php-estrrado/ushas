<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderReturnStatus extends Model
{
    use HasFactory;
    protected $table = 'sales_order_return_status';
    protected $fillable     =   ['sales_id','return_id','status','updated_platform'];
    
}

