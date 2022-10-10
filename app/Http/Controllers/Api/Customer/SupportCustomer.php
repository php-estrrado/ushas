<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;
use App\Models\Modules;
use App\Models\UserRoles;
use App\Models\Admin;
use App\Models\CustomerInfo;
use App\Models\SupportChatMessage;
use App\Models\SupportChat;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use App\Rules\Name;
use Validator;

class SupportCustomer extends Controller
{
    public function create_ticket(Request $request)
    {
        if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
        $user_id = $user['user_id'];
        $validator=  Validator::make($request->all(),[
            'subject'      => ['required','string','max:100'],
            'message'      => ['required','string']

        ]);
        if ($validator->fails()) 
    {    
      return ['httpcode'=>400,'status'=>'error','message'=>'Invalid parameters','data'=>['errors'=>$validator->messages()]];
    }
    else
    {
        $input = $request->all();
       // $ticket_id = "TKT".date('YmdHis').rand(10,100);
        $ticket_id = "TKT".date('YmdHis');
        $data          =   ['subject'=>$input['subject'],
                            'ticket_id'=>$ticket_id,
                            'created_by'=>$user_id,
                            'created_at'=>date("Y-m-d H:i:s"),
                            'updated_at'=>date("Y-m-d H:i:s")];
        $create_ticket = SupportChat::create($data)->id; 
        if($create_ticket)
        {
            $message = ['support_id'=>$create_ticket,
                        'msg_type'=>'text',
                        'message'=>$input['message'],
                        'sender_id'=>$user_id,
                        'sender_role_id'=>5,
                        'created_at'=>date("Y-m-d H:i:s"),
                        'updated_at'=>date("Y-m-d H:i:s")];
            
            $create_msg = SupportChatMessage::create($message)->id;
            
            if($create_msg) 
            {
                $from       = $user_id; 
                $utype      = 2;
                $to         = 1; 
                $ntype      = 'support';
                $title      = 'New Support';
                $desc       = 'New support created by '.$user['first_name'].' '.$user['last_name'];
                $refId      = $create_ticket;
                $reflink    = 'customer/support';
                $notify     = 'admin';
               // addNotification($from,$utype,$to,$ntype,$title,$desc,$refId,$reflink,$notify);
            
                $latest_ticket = SupportChat::latest()->first()->ticket_id;
               return ['httpcode'=>200,'status'=>'success','message'=>'success','data'=>['ticket_id'=>$latest_ticket]];
            }           
        }                   
    }//end else

   }

   public function ticket_list(Request $request)
   {
        if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
        $user_id = $user['user_id'];
        $validator=  Validator::make($request->all(),[
            'access_token'      => ['required']

        ]);
        if ($validator->fails()) 
    {    
      return ['httpcode'=>400,'status'=>'error','message'=>'Invalid parameters','data'=>['errors'=>$validator->messages()]];
    }
    else
    {
        
        $support = SupportChat::where('created_by',$user_id)->orderBy('id','DESC')->get();
        
        if(!empty($support))
        {
         $support_list=[];
        foreach($support as $row)
        {
            $list['support_id'] = $row->id;
            $list['ticket_id']  = $row->ticket_id;
            $list['subject']    = $row->subject;
             if($row->is_closed==0)
             {
              $list['status']    = 'Open';   
             }
             else
             {
              $list['status']    = 'Solved';   
             }
            $list['message'] = SupportChat::last_chats($row->id);
            $list['created_at'] = date('d M Y',strtotime($row->created_at));
           
            $support_list[]     = $list;

        }
        return ['httpcode'=>200,'status'=>'success','message'=>'success','data'=>['list'=>$support_list]];
        }
        else
        {
            return ['httpcode'=>404,'status'=>'error','message'=>'Not found','data'=>['errors'=>'Not found']];
        }
    }
   }

   public function add_message(Request $request)
   {
     if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
        $user_id = $user['user_id'];
        $validator=  Validator::make($request->all(),[
            'support_id'      => ['required','numeric'],
            'message'         => ['required_without:image'],
            'image'           => ['required_without:message','image','mimes:jpeg,png,jpg']

        ]);
        if ($validator->fails()) 
    {    
      return ['httpcode'=>400,'status'=>'error','message'=>'Invalid parameters','data'=>['errors'=>$validator->messages()]];
    }
    else
    {   
        $input = $request->all();
        $support = SupportChat::where('id',$input['support_id'])->first();
        if($support)
        {
            $image = $request->file('image');
            
            if($image)
            {
                $msg_type='image';
                $imgName            =   time().'.'.$image->extension();
                $path               =   '/app/public/support_seller/'.$input['support_id'];
                $destinationPath    =   storage_path($path.'/thumb');
                $img                =   Image::make($image->path());  //echo storage_path().'  '. $destinationPath; die;
                if(!file_exists($destinationPath)) { mkdir($destinationPath, 755, true);}
                $img->resize(250, 250, function($constraint){ $constraint->aspectRatio(); })->save($destinationPath.'/'.$imgName);
                $destinationPath    =   storage_path($path);
                $image->move($destinationPath, $imgName);
                $imgUpload          =   uploadFile('/'.$path,$imgName);

                $image_chat =$path.'/'.$imgName;
            //     $file=$request->file('file_data');
            // $extention=$file->getClientOriginalExtension();
            // $filename=time().'.'.$extention;
            // $file->move(('storage/app/public/support_seller/'),$filename);
            // $image_chat ='storage/app/public/support_seller/'.$filename;
            }
            else
            {
                $image_chat = '';
                $msg_type='text';
            }
            $data = ['support_id' => $support->id,
                     'msg_type'   => $msg_type,
                     'message'    => $input['message'],
                     'sender_id'  => $user_id,
                     'receiver_id'=>1,
                     'image'    => $image_chat, 
                     'sender_role_id'=>5,
                     'show_role_id'=>2,
                     'created_at'=>date("Y-m-d H:i:s"),
                     'updated_at'=>date("Y-m-d H:i:s")
                    ];
            $create = SupportChatMessage::create($data);  
            
            $from   = $user_id; 
            $utype   = 2;
            $to      = 1; 
            $ntype  = 'support_replay';
            $title  = 'Support Replay';
            $desc   = 'New support message from '.$user['first_name'].' '.$user['last_name'];
            $refId  = $support->id;
            $reflink = 'customer/support';
            $notify = 'admin';
           // addNotification($from,$utype,$to,$ntype,$title,$desc,$refId,$reflink,$notify);
            
            return ['httpcode'=>200,'status'=>'success','message'=>'success','data'=>['response'=>'Inserted']];      
        }

        else
        {
            return ['httpcode'=>400,'status'=>'error','message'=>'Invalid parameters','data'=>['errors'=>'Invalid support id']];
        }
    }
   }

   public function view_message(Request $request)
   {
     if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
        $user_id = $user['user_id'];
        $validator=  Validator::make($request->all(),[
            'support_id'      => ['required','numeric']

        ]);
        if ($validator->fails()) 
    {    
      return ['httpcode'=>400,'status'=>'error','message'=>'Invalid parameters','data'=>['errors'=>$validator->messages()]];
    }
    else
    {   
        $input = $request->all();
        $support = SupportChat::where('id',$input['support_id'])->first();
        if($support)
        {
            $list['support_id'] = $support->id;
            $list['ticket_id']  = $support->ticket_id;
            $list['subject']    = $support->subject;
            
            $list['created_at'] = date('d M Y',strtotime($support->created_at));
            $list['messages']   = $this->get_support_msgs($support->id,$user_id);
            // $support_msg = SupportChatMessage::where('support_id',$input['support_id']);
            // foreach($support_msg as $row)
            // {
            //     $msg['name']  = $user_id;//
            // }
            $support_list     = $list; 
            return ['httpcode'=>200,'status'=>'success','message'=>'Support','data'=>['support_messages'=>$support_list]];
        }
        else
        {
            return ['httpcode'=>400,'status'=>'error','message'=>'Invalid parameters','data'=>['errors'=>'Invalid support id']];
        }
   }
   }
   
   function get_support_msgs($chat_id,$user_id)
    {
        $all = [];
        $img_message='';
        $chat_msgs= SupportChatMessage::where('support_id',$chat_id)->get();
        foreach($chat_msgs as $row)
            {
                if($row->sender_id==$user_id)
                {
                    $from = "You"; $align ="right"; $me = 1;

                }
                else{
                    $from = "Admin"; $align ="left"; $me = 0;
                }
                $message = $row->message;
                if($row->msg_type       ==  'image')
                { $img_message = config('app.storage_url').$row->image; }
                else if($row->msg_type       ==  'emoji')
                { $img_message = url('storage'.$row->other_msg); }
                else if($row->msg_type       ==  'video')
                { $img_message = url('storage'.$row->other_msg); }
                else{ $img_message = ''; }

                   $msgs['from']       = $from;
                   $msgs['me']         = $me;
                   $msgs['align']      = $align;
                   $msgs['message']    = $message;
                   $msgs['image']      = $img_message;
                   $msgs['created_at'] = date('Y-m-d H:i:s', strtotime($row->created_at));
                   $msgs['read_status']= $row->read_status;
                   $all[]              = $msgs;
            }

            return $all;
    }
}
