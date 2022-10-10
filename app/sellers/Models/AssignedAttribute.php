<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignedAttribute extends Model{
    use HasFactory;
    protected $table = 'prd_assigned_attributes';
    protected $fillable = ['prd_id','attr_id','attr_val_id','attr_value','created_by',];
    
}
