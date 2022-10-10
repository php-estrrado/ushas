<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminProduct extends Model{
    use HasFactory;
    protected $table = 'prd_admin_products';
    protected $fillable = ['prd_id','image','thumb','created_by','is_active'];
    
}
