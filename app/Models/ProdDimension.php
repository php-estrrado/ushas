<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdDimension extends Model
{
    use HasFactory;
    protected $table = 'prd_dimensions';
    protected $fillable = ['prd_id','weight','length','width','height','is_deleted','created_by','created_at','updated_at','is_active'];
}
