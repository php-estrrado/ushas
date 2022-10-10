<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerSecurity extends Model{
    use HasFactory;
    protected $table = 'usr_seller_security';
    protected $fillable = ['seller_id','password_hash', 'fb_id', 'google_id','apple_id'];
    
}
