<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSecurity extends Model
{
    use HasFactory;
    protected $table = 'usr_security';
    protected $fillable = ['user_id','password_hash', 'fb_id', 'google_id','apple_id','is_active','created_by','updated_by'];

}
