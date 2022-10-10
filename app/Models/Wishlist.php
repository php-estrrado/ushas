<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;
    protected $table = 'usr_wishlist_lk';
    protected $fillable = ['org_id','user_id','title','description','is_active','is_deleted','created_at','updated_at'];
}
