<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportChat extends Model
{
    use HasFactory;
    protected $table = 'support_chats';
    protected $guarded=[];
    
    public function chats(){ return $this->hasMany(SupportChatMessage ::class, 'support_id'); }

    
    static function last_chats($ticket_id){ 
	$chats =SupportChatMessage::where('support_id', $ticket_id)->where('msg_type', "text")->first();
	if($chats){ 
        $chats = $chats->message;
        return $chats;
        }else{ return false; }

    }
}
