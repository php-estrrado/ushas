<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;
use URL;
use App\Models\Admin;
use App\Models\CustomerInfo;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Modules;
use App\Models\Store;
use App\Models\SellerInfo;
use App\Models\UserRoles;
use Carbon\Carbon;
use App\Rules\Name;
use Validator;

class ChatsController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function list(Request $request)
    {
        $post                       =   (object)$request->post();
        if(isset($post->viewType))  {   $viewType = $post->viewType; }else{ $viewType = ''; }
            
        $data['title']              =   'Customer-seller chat';
        $data['menu']               =   'Customer-seller chat';
        $support = Chat::orderBy('id','DESC')->where('is_deleted',0)->get();
        if(count($support)>0)
        {
        foreach($support as $row)
        {
            $seller = Store::where('seller_id',$row->seller_id)->first();
            $seller_name = $seller->store_name;
           $customer = CustomerInfo::where('user_id',$row->created_by)->first();
            $cust_name = $customer->first_name."".$customer->middle_name." ".$customer->last_name; 
            
            $list['chat_id']      = $row->id;
            $list['seller']       = $seller_name;
            $list['customer']     = $cust_name;
            $support_list[]       = $list;

        }
       }
       else
       {
        $support_list=[];
       }
        $data['list'] = $support_list;
        if($viewType=='ajax')
        {
          return view('admin.chat.header',$data);
        }
        else
        {
            return view('admin.chat.page',$data);
        }
    }

    public function chat(Request $request,$id='',$type='')
    {
        if($type=='chat')
        {
            $support = Chat::where('id',$id)->first();
            $chat_id= $support->id;
            $date =  date('d-m-Y');
            $html ='';
            $customer = CustomerInfo::where('user_id',$support->created_by)->first();
               $customer_name_p = $customer->first_name."".$customer->middle_name." ".$customer->last_name; 
               if($customer->profile_image)
               {
                 $imagec=config('app.storage_url').'/app/public/customer_profile/'.$customer->profile_image;
                //$imagec=url('/uploads/storage/app/public/customer_profile/'.$customer->profile_image);
               } 
               else
               {
                $imagec=url('/public/admin/assets/images/users/2.jpg');
               } 
             $seller = SellerInfo::where('seller_id',$support->seller_id)->first(); 
             $seller12 = Store::where('seller_id',$support->seller_id)->first();
            $seller_name_p = $seller12->store_name;
                //$seller_name_p = $seller->fname."".$seller->mname." ".$seller->lname;
                if($seller->store($seller->seller_id)->logo == NULL)
               {
               $images=URL::asset(config('settings.logo'));//url('/public/admin/assets/images/users/2.jpg');
               } 
               else
               {
                 $images=URL::asset(config('settings.logo'));//config('app.storage_url').$seller->store($seller->seller_id)->logo;
               }
            //   if($seller->avatar)
            //   {
            //     $images=url('storage/'.$seller->avatar);
            //   } 
            //   else
            //   {
            //     $images=url('/public/admin/assets/images/users/2.jpg');
            //   }     
            $html.='<div class="action-header">
            <div class="float-left hidden-xs d-flex ml-2">
                                <div class="img_cont mr-3">
                                    <img src="'.$imagec.'" class="rounded-circle user_img avatar avatar-md" alt="img">
                                </div>
                                <div class="align-items-center mt-2 text-white">
                                    <h5 class="mb-0">'.$customer_name_p.'</h5>
                                </div>
                            </div>
                            <div class="float-right hidden-xs d-flex ml-2 mr-1">
                                <div class="img_cont mr-3">
                                    <img src="'.$images.'" class="rounded-circle user_img avatar avatar-md" alt="img">
                                </div>
                                <div class="align-items-center mt-2 text-white">
                                    <h5 class="mb-0">'.$seller_name_p.'</h5>
                                </div>
                            </div>
                            
                        </div>';
            $html.='<div class="card-body msg_card_body">';

             //card body statrted   
           $support_message = ChatMessage::where('is_deleted',0)->where('chat_id',$chat_id)->get();
           foreach($support_message as $row)
           {  
             $row_date  = date('d-m-Y',strtotime($row->created_at));
             $diff_date = Carbon::parse($row->created_at)->diffForHumans();  
             if($row->sender_role_id==5)
             {  
               $customer = CustomerInfo::where('user_id',$row->sender_id)->first();
               $customer_name = $customer->first_name."".$customer->middle_name." ".$customer->last_name; 
               if($customer->profile_image)
               {
                $image=config('app.storage_url').'/app/public/customer_profile/'.$customer->profile_image;
                //$image=url('/uploads/storage/app/public/customer_profile/'.$customer->profile_image);
               } 
               else
               {
                $image=url('/public/admin/assets/images/users/2.jpg');
               }     
               
               if($row->msg_type=="text")
               {
            $html.='<div class="d-flex justify-content-start">
                                <div class="img_cont_msg">
                                    <img src="'.$image.'" class="rounded-circle user_img_msg" alt="img">
                                </div>
                                <div class="msg_cotainer mb-2">'.$row->message.'<span class="msg_time"><br>'.$diff_date.'</span>
                                </div>
                            </div>'; 
               }
               else
               {
                   $msg_image= config('app.storage_url').$row->other_msg;
                   $html.='<div class="d-flex justify-content-start">
                                <div class="img_cont_msg">
                                    <img src="'.$image.'" class="rounded-circle user_img_msg" alt="img">
                                </div>
                                <div class="msg_cotainer mb-2">'.$row->message.'<div class="row mt-2"><div class="col-12"><img class="img-fluid rounded" src="'.$msg_image.'" alt="image"></div></div><span class="msg_time"><br>'.$diff_date.'</span>
                                </div>
                            </div>'; 
               }
              }
              else
              {
                $seller = SellerInfo::where('seller_id',$row->sender_id)->first(); 
                $seller_name = $seller->fname."".$seller->mname." ".$seller->lname;
                if($seller->store($seller->seller_id)->logo == NULL)
               {
               $image=URL::asset(config('settings.logo'));//url('/public/admin/assets/images/users/2.jpg');
               } 
               else
               {
                 $image= URL::asset(config('settings.logo'));//config('app.storage_url').$seller->store($seller->id)->logo;
               }
                if($row->msg_type=="text")
               {
             $html.='<div class="d-flex justify-content-end ">
                                <div class="msg_cotainer_send mb-2">'.$row->message.'<span class="msg_time_send">'.$diff_date.'</span>
                                </div>
                                <div class="img_cont_msg">
                                    <img src="'.$image.'" class="rounded-circle user_img_msg" alt="img">
                                </div>
                            </div>';  
               }
               else
               {
                   $msg_image= config('app.storage_url').$row->other_msg;
                   $html.='<div class="d-flex justify-content-end ">
                                <div class="msg_cotainer_send mb-2">'.$row->message.'<div class="row mt-2"><div class="col-12"><img class="img-fluid rounded" src="'.$msg_image.'" alt="image"></div></div><span class="msg_time_send">'.$diff_date.'</span>
                                </div>
                                <div class="img_cont_msg">
                                    <img src="'.$image.'" class="rounded-circle user_img_msg" alt="img">
                                </div>
                            </div>';
               }
              }

              
            } 
            $html.='</div></div>';
             return $html;   

        }

      
    }
    
    public function delete(Request $request)
    {
       //return $request->id;die;
       // $array=['is_deleted'=>1];
        $id=$request->id;
        $chat =  Chat::where('id',$id)->first();
        $chat->is_deleted=1;
        $chat->save();
        //$update = Currency::where('id',$id)->udpate($array);
        Session::flash('message', ['text'=>'Deleted successfully','type'=>'success']);
    }
}
