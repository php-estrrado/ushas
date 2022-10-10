<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerOTP extends Model{
    use HasFactory;
    protected $table = 'usr_seller_otp';
    protected $fillable = ['user_id', 'user_type','email','token','created_at','updated_at','is_deleted'];
}
