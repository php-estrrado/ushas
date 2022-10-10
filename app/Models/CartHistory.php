<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartHistory extends Model
{
    use HasFactory;
    protected $table = 'usr_cart_activity_hist';
    protected $fillable = [
        'org_id','user_id','product_id','quantity','action','is_active','is_deleted','created_by','updated_by','created_at','updated_at'];
}
