<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssConfigAttribute extends Model{
    use HasFactory;
    protected $table = 'prd_assigned_config_attributes';
    protected $fillable = ['prd_id','attr_id',];
   
}
