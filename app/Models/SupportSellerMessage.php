<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportSellerMessage extends Model
{
    use HasFactory;
    protected $table = 'support_seller_messages';
    protected $fillable = ['support_id', 'msg_type','message','sender_id','sender_role_id','receiver_id','image','sender_type','show_role_id','is_deleted','read_status','created_at','updated_at'];
}
