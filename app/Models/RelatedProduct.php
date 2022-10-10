<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelatedProduct extends Model{
    use HasFactory;
    protected $table = 'prd_related_products';
    protected $fillable = ['prd_id','rel_prd_id','created_by',];
    
}
