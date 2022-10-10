<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportSellerChat extends Model
{
    use HasFactory;
    protected $table = 'support_seller_chats';
    protected $fillable = ['subject', 'ticket_id','created_by','role_id','created_at','updated_at'];
}
