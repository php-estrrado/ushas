<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class PrdStock extends Model{
    use HasFactory;
    protected $fillable = ['seller_id','prd_id','type','qty','rate','created_by','sale_id'];
    public function product(){ return $this->belongsTo(Product ::class, 'prd_id'); }
    
    
    public function price($prdId){ return PrdPrice::where('prd_id',$prdId)->where('is_deleted',1)->first(); }
}
