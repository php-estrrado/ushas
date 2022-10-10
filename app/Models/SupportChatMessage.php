<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportChatMessage extends Model
{
    use HasFactory;
    protected $table = 'support_chat_messages';
    protected $guarded=[];
}
