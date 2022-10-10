<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrdAdminImage extends Model{
    use HasFactory;
    protected $fillable = ['prd_id','image','thumb','created_by','is_active'];
    
}
