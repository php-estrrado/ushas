<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table = 'usr_cart';
    protected $fillable = ['org_id','user_id','cart_name','cart_desc','is_active','is_deleted','created_by','updated_by','created_at','updated_at'];
}
