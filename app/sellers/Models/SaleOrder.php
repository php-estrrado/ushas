<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleOrder extends Model
{
    use HasFactory;
    protected $table = 'sales_orders';
    public function items() 
    {
        $this->hasMany(SaleorderItems::class, 'sales_id');
    }
    public function orderitem($sale_id){ return SaleorderItems::where('sales_id',$sale_id)->get(); }
}
