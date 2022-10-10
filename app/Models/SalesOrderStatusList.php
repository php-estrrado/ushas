<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderStatusList extends Model
{
    use HasFactory;
    protected $fillable = ['identifier','title','description','short','is_active',];
}
