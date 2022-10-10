<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderStatusHistory extends Model
{
    use HasFactory;
    protected $fillable = ['sales_id','status','created_by','role_id','description',];
}
