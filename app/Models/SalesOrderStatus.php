<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderStatus extends Model
{
    use HasFactory;
    protected $table = 'sales_order_status';
    protected $fillable = ['org_id','sale_id', 'status', 'created_by','updated_by','created_at', 'updated_at',];
    
    
}
