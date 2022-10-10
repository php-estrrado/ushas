<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssociatProduct extends Model{
    use HasFactory;
    protected $table = 'prd_associative_products';
    protected $fillable = ['prd_id','ass_prd_id','created_by','platform'];
    public function product(){ return $this->belongsTo(Product ::class, 'prd_id'); }
    
}
