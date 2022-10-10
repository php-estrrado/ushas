<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrdAttributeValue extends Model
{
    use HasFactory;
    protected $fillable = ['attr_id','name','name_cnt_id','is_active','created_by'];
}

