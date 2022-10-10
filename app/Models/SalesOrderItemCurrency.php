<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderItemCurrency extends Model
{
    use HasFactory;
    protected $fillable = ['sales_item_id','price','total','discount','tax','row_total'];
}
