<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerReview extends Model
{
    use HasFactory;
    protected $table = 'usr_seller_ratings';
    protected $fillable = ['seller_id','user_id', 'rating','title', 'comment', 'image','is_active','is_deleted', 'created_at','updated_at',];
    public function customerinfo($user_id){ return customer\CustomerInfo::where('user_id',$user_id)->first(); }
}
