<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleOrder extends Model
{
    use HasFactory;
    protected $table = 'sales_orders';
    protected $guarded=[];
    public function items() 
    {
        $this->hasMany(SaleorderItems::class, 'sales_id');
    }
    public function orderitem($sale_id){ return SaleorderItems::where('sales_id',$sale_id)->get(); }
    public function seller(){ return $this->belongsTo(Seller ::class, 'seller_id'); }
    public function customer(){ return $this->belongsTo(Customer ::class, 'cust_id'); }
    public function store($sellerId){ return Store::where('seller_id',$sellerId)->first(); }
}
