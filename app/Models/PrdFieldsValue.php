<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrdFieldsValue extends Model
{
    use HasFactory;
    protected $fillable = ['field_id','name','name_cnt_id','image','is_active','created_by','seller_id','prd_id'];
}

