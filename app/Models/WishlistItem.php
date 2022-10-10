<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishlistItem extends Model
{
    use HasFactory;
    protected $table = 'usr_wishlist_prod';
    protected $fillable = ['org_id','user_id','usr_wishlist_id','product_id','is_active','is_deleted','created_by','updated_by','created_at','updated_at'];
}
