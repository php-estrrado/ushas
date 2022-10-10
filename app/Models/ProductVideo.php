<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVideo extends Model
{
    use HasFactory;
    protected $table = 'prd_videos';
    protected $fillable = ['prd_id','video','is_deleted','created_by','created_at','is_active'];
}
