<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;
use App\Models\Admin;
use App\Models\CustomerInfo;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Modules;
use App\Models\UserRoles;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use App\Rules\Name;
use Validator;

class ChatsControllerAdmin extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function chat_list(Request $request)
    {
        $data['title']              =   'Customer chat';
        $data['menu']               =   'customer-chat';
        $chat = Chat::where('is_deleted',0)->orderBy('id','DESC')->get();
       // $chat_latest = Chat::where('seller_id',auth()->user()->id)->latest()->first();
        $msg_list = [];
        foreach($chat as $row)
        {   $customer=CustomerInfo::where('is_active',1)->where('is_deleted',0)->where('user_id',$row->created_by)->first();
            $msg_count  =ChatMessage::where('chat_id',$row->id)->where('read_status',0)->where('sender_id','!=',auth()->user()->id)->count();
            if($customer)
            {
            $cust_name = $customer->first_name." ".$customer->middle_name." ".$customer->last_name.$customer->branch_name($customer->user_id);
            $latest = ChatMessage::where('chat_id',$row->id)->latest()->first();
            $list['chat_id']    = $row->id;
            $list['unread_msg'] = $msg_count;
            $list['cust_id']    = $row->created_by;
            $list['cust_name']  = $cust_name;
            if($customer->profile_image)
            {
            $list['profile_img']= config('app.storage_url').'/app/public/customer_profile/'.$customer->profile_image;
            }
            else
            {
             $list['profile_img'] =url('/public/admin/assets/images/users/2.jpg');   
            }
            $list['latest_msg']   =$latest->message;
            $diff_date = Carbon::parse($latest->created_at)->diffForHumans();
            $list['last_msg_on']  = $diff_date;
            $msg_list[]         = $list;
            }
        }
        $data['chat_id_latest']='';
        $data['list']  = $msg_list;
        

        return view('admin.chat.page',$data);
    }
    
    public function open_chat_messages(Request $request,$id='',$type='')
    {
        $data['title']              =   'Customer chat';
        $data['menu']               =   'customer-chat';
        $chat = Chat::orderBy('id','DESC')->get();
       // $chat_latest = Chat::where('seller_id',auth()->user()->id)->latest()->first();
        $msg_list = [];
        foreach($chat as $row)
        {   $customer=CustomerInfo::where('is_active',1)->where('is_deleted',0)->where('user_id',$row->created_by)->first();
            $msg_count  =ChatMessage::where('chat_id',$row->id)->where('read_status',0)->where('sender_id','!=',auth()->user()->id)->count();
            if($customer)
            {
            $cust_name = $customer->first_name." ".$customer->middle_name." ".$customer->last_name.$customer->branch_name($customer->user_id);
            $latest = ChatMessage::where('chat_id',$row->id)->latest()->first();
            $list['chat_id']    = $row->id;
            $list['unread_msg'] = $msg_count;
            $list['cust_id']    = $row->created_by;
            $list['cust_name']  = $cust_name;
            if($row->cust_info->profile_image)
            {
            $list['profile_img']= config('app.storage_url').'/app/public/customer_profile/'.$customer->profile_image;
            }
            else
            {
             $list['profile_img'] =url('/public/admin/assets/images/users/2.jpg');   
            }
            $list['latest_msg']   =$latest->message;
            $diff_date = Carbon::parse($latest->created_at)->diffForHumans();
            $list['last_msg_on']  = $diff_date;
            $msg_list[]         = $list;
            }
        }
        $data['chat_id_latest']=$id;
        $data['list']  = $msg_list;
        

        return view('admin.chat.page',$data);
    }

    public function chat_messages(Request $request,$id='',$type='')
    {
        $html ='';
        $post                       =   (object)$request->post();
        if($type=='view')
        {
            $chat = Chat::latest()->first();

        }
        
        else if($type=='ajax')
        {
            $chat = Chat::where('id',$id)->first();
            
            $Chatmsg_update= ChatMessage::where('chat_id',$id)->where('sender_role_id',5)->update([
            'read_status'=>1
            ]);
        }
        
        else if($type=='temporary' && $id=="10x")
        {
            //$html.="Select Chat";
            $chat='';
        }

        else if($type=='create')
        {$image = $request->file('file_data');
            
            if($image)
            {
                $msg_type='image';
                $imgName            =   time().'.'.$image->extension();
                $path               =   '/app/public/chat_customer/'.$post->chat_id;
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

            $Chatmsg_update= ChatMessage::where('chat_id',$post->chat_id)->where('sender_role_id',5)->update([
            'read_status'=>1
            ]);
            $message = ['chat_id'=>$post->chat_id,
                        'msg_type'=>$msg_type,
                        'message'=>$post->msg,
                        'other_msg'=>$image_chat,
                        'sender_id'=>auth()->user()->id,
                        'sender_role_id'=>1,
                        'receiver_id'=>$post->cust_id,
                        'created_at'=>date("Y-m-d H:i:s"),
                        'updated_at'=>date("Y-m-d H:i:s")];
                        $create_msg = ChatMessage::create($message)->id;
            $from   = auth()->user()->id; 
            $utype  = 2;
            $to     = $post->cust_id;
            $ntype  = 'new_chat_message';
            $title  = 'New Chat Message';
            $desc   = 'You have a new chat message';
            $refId  = $post->chat_id;
            $reflink = 'customer/chat/message';
            $notify  = 'customer';
            addNotification($from,$utype,$to,$ntype,$title,$desc,$refId,$reflink,$notify);
            
            $chat = Chat::where('sender_role_id',1)->where('id',$post->chat_id)->first();
            
            
        }    
            if($chat)
            {  
                $customer=CustomerInfo::where('is_active',1)->where('is_deleted',0)->where('user_id',$chat->created_by)->first();
                $cust_name = $customer->first_name."".$customer->middle_name." ".$customer->last_name.$customer->branch_name($customer->user_id);
                    if($customer->profile_image)
                {
                $cust_img= config('app.storage_url').'/app/public/customer_profile/'.$customer->profile_image;
                }
                else
                {
                 $cust_img =url('/public/admin/assets/images/users/2.jpg');  
                }
                
             

                $html.='<div class="main-content-body main-content-body-chat">
                        <input type="hidden" name="chat_category" class="chat_category" id="chat_category" value="'.$chat->id.'">
                                            <div class="main-chat-header">
                                                <div class="main-img-user"><img alt="" src="'.$cust_img.'" class="avatar avatar-md brround"></div>
                                                <div class="main-chat-msg-name">
                                                    <h6>'.$cust_name.'</h6>
                                                </div>
                                                <nav class="nav">
                                                </nav>
                                            </div>';
                $html.='<div class="main-chat-body ChatBody" style="overflow-y: auto;" id="ChatBody">
                            <div class="content-inner" id="innerchat">'; 
                $chat_msgs = ChatMessage::select('*', DB::raw('DATE(created_at) as date'))->where('chat_id',$chat->id)->groupBy('date')->orderBy('id','ASC')->get();
                // print_r($chat);
                // die;

                foreach($chat_msgs as $rows)
                {   
                    $row_date  = date('Y-m-d',strtotime($rows->date));
                    $label_date= date('d-m-Y',strtotime($row_date));
                    $dif_date = Carbon::parse($rows->created_at)->diffForHumans();
                    $html.='<label class="main-chat-time"><span>'.$label_date.','.$dif_date.'</span></label>';
                    $html.=$this->all_chats($rows->chat_id,$row_date);
                } 
                  $html.='<div id="theend" style="position: fixed"></div>';
                $html.='</div></div><div class="main-chat-footer">
                            <input type="hidden" value="'.$chat->id.'" name="chat_id" id="chat_id" class="chat_id">
                            <input type="hidden" value="'.$chat->created_by.'" name="cust_id" id="cust_id" class="cust_id">
                            <label class="mr-3 image-preview data-toggle="popover">
                                                <span class="nav-link image-preview-input">
                                                    <i class="fe fe-paperclip fs-20"></i> <input type="file" style="display: none;" accept="image/png, image/jpeg" class="img_up" id="img_up" onchange="imgchange(this)">
                                                </span>
                                            </label><span class="bellnumbers" data-toggle="modal" data-target="#myModalImge"></span>
                                                <input class="form-control msgchat" name="msgchat" id="msgchat" placeholder="Type your message here..." type="text"> <button class="main-msg-send sndbtn" id="sndbtn" onclick="submitThis('.$chat->id.')"><i class="fa fa-paper-plane-o" type="button"></i></button>
                                            </div>
                                        </div>';                                      
            }

            else
            {
                $html.='<div class="main-content-body main-content-body-chat"><div class="main-chat-body ChatBody" style="overflow-y: auto;" id="ChatBody">
                            <div class="content-inner" id="innerchat"><center>No chat messages</center></div></div></div>';
            }

            return $html;
        
    }

    function all_chats($chat_id,$date)
    {
        $html='';
        $chat_msgs = ChatMessage::where('chat_id',$chat_id)->whereDate('created_at',$date)->get();
        foreach($chat_msgs as $row)
        {
            $row_date  = date('H:i:s',strtotime($row->created_at));
            if($row->sender_role_id==5)
            
            {
                $customer = CustomerInfo::where('user_id',$row->sender_id)->first(); 
               if($customer->profile_image!='')
               {
                $image=config('app.storage_url').'/app/public/customer_profile/'.$customer->profile_image;
               } 
               else
               {
                $image=url('/public/admin/assets/images/users/2.jpg');
               }
               if($row->msg_type=="image")
                {
                    $msg_image= config('app.storage_url').$row->other_msg;
             $html.='<div class="media" >
                    <div class="main-img-user"><img alt="" src="'.$image.'" class="avatar avatar-md brround"></div>
                        <div class="media-body">
                         <div class="main-msg-wrapper">'.$row->message.'<div class="row mt-2"><div class="col-12"><img class="img-fluid rounded" data-toggle="modal" data-target="#myModal'.$row->id.'" src="'.$msg_image.'" alt="banner image"></div></div></div>
                                                            <div>
                                                                <span>'.$row_date.'</span> <a href=""><i class="icon ion-android-more-horizontal"></i></a>
                                                            </div>
                                                        </div>
                                                    </div><div id="myModal'.$row->id.'" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content"> <button type="button" class="close" data-dismiss="modal">&times;</button>
            <img src="'.$msg_image.'">
    </div>
  </div>
</div>';
                }
                else
                {
                    $html.='<div class="media" >
                    <div class="main-img-user"><img alt="" src="'.$image.'" class="avatar avatar-md brround"></div>
                        <div class="media-body">
                         <div class="main-msg-wrapper">'.$row->message.'</div>
                                                            <div>
                                                                <span>'.$row_date.'</span> <a href=""><i class="icon ion-android-more-horizontal"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>';
                }
            }
            else{
                //$seller = SellerInfo::where('seller_id',$row->sender_id)->first(); 
               //  if($seller->store($seller->id)->logo == NULL)
               // {
               // $image=url('/public/admin/assets/images/users/2.jpg');
               // } 
               // else
               // {
               //   $image= url('storage'.sellerData()->store(sellerData()->id)->logo);
               // }
                $image=url(config('settings.logo'));
               
               if($row->msg_type=="image")
                {
                    $msg_image= config('app.storage_url').$row->other_msg;
            $html.='<div class="media flex-row-reverse">
                    <div class="main-img-user"><img alt="" src="'.$image.'" class="avatar avatar-md brround"></div>
                    <div class="media-body">
                    <div class="main-msg-wrapper">'.$row->message.'<div class="row mt-2"><div class="col-12"><img data-toggle="modal" data-target="#myModal'.$row->id.'" class="img-fluid rounded" src="'.$msg_image.'" alt="banner image"></div></div></div>
                        <div>
                         <span>'.$row_date.'</span> <a href=""><i class="icon ion-android-more-horizontal"></i></a>
                                                            </div>
                                                        </div>
                                                    </div><div id="myModal'.$row->id.'" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content"><button type="button" class="close" data-dismiss="modal">&times;</button>
            <img src="'.$msg_image.'">
    </div>
  </div>
</div>';
                }
                else
                {
                    $html.='<div class="media flex-row-reverse">
                    <div class="main-img-user"><img alt="" src="'.$image.'" class="avatar avatar-md brround"></div>
                    <div class="media-body">
                    <div class="main-msg-wrapper">'.$row->message.'</div>
                        <div>
                         <span>'.$row_date.'</span> <a href=""><i class="icon ion-android-more-horizontal"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>';
                }
            }
        }
       
        return $html;
    }
}
