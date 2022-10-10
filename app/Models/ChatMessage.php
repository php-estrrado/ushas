<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class ChatMessage extends Model
{
    use HasFactory;
    protected $table = 'chat_messages';
    protected $fillable = ['chat_id', 'msg_type','message','other_msg','sender_id','sender_role_id','receiver_id','is_deleted','read_status','created_at','updated_at'];
    //public function Store($seller_id){ return DB::table('usr_stores')->where('seller_id', $seller_id)->first(); }
    
    public function getChatMsg()
    {
        $messages=[];
        $chatmsg = ChatMessage::whereIn('chat_id',function($query){$query->select('id')->from('chats')->where('is_deleted',0)->orderBy('updated_at','DESC');})->where('sender_role_id',5)->where('read_status',0)->where('is_deleted',0)->groupBy('chat_id')->get();
        foreach($chatmsg as $row)
        {
            $customer = CustomerInfo::where('user_id',$row->sender_id)->first();
            if($customer->profile_image)
                {
                $cust_img= config('app.storage_url').'/app/public/customer_profile/'.$customer->profile_image;
                }
                else
                {
                 $cust_img =url('/public/admin/assets/images/users/2.jpg');  
                }
                
            $data['name'] = $customer->first_name; 
            $data['img'] = $cust_img; 
             
            $latest = ChatMessage::where('chat_id',$row->chat_id)->orderBy('id','DESC')->first(); 
            $data['msg_type'] = $latest->msg_type;
            if($latest->msg_type=='text')
            {
                 
            $data['msg']  = substr_replace($latest->message, "...", 20);
            }
            else
            {
             $data['msg']  = '<i class="fa fa-image"></i>';   
            }
            $messages[] =$data;
        }
        return $messages;
    }
}
