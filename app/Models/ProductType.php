<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model{
    use HasFactory;
    protected $table = 'prd_product_types';
    protected $fillable = ['type_name','desc'];
    
}
