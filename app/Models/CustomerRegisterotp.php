<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerRegisterotp extends Model
{
    use HasFactory;
    protected $table = 'user_registeration_otp';
    protected $guarded=[];
    
    protected $fillable = ['country_code', 'phone_number','email', 'otp', 'status','created_at'];
}
