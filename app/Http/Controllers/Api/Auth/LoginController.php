<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\PasswordReset;
use App\Models\Email;
use App\Models\CustomerTelecom;
use App\Models\CustomerInfo;
use Validator;
use Mail;
class LoginController extends Controller
{ 
    function forgotPassword(Request $request){
        $post = (object)$request->post();
        $rules      =   array();
        $rules['email']    = 'required|email|max:255';
        $validator  =   Validator::make($request->all(), $rules);
        if ($validator->fails()) 
            {
                foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
            }
        else
            { 
                $user = CustomerTelecom::where('usr_telecom_value',$post->email)->where('usr_telecom_typ_id',1)->where('is_deleted',0)->where('is_active',1)->first();  
                if ($user) {
                    $userExist = CustomerInfo::where('user_id',$user->user_id)->first();
                    if ($userExist->is_active == 0 || $userExist->is_deleted == 1) {
                        return array('httpcode'=>'400','status'=>'error','message'=>'Not Found','data'=>['message' =>'This account not activated or disabled!']);
                    }
                    else
                    {
                        $resetLink = base64_encode(rand(100000, 999999) . 'resetpassword' . time() . '1');
                        $resetLink = urlencode($resetLink);
                        $currTime = date('Y-m-d H:i:s');
                        $data = array('active_link' => $resetLink, 'email_verified_at' => $currTime);
                        
                        $update = PasswordReset::create(['user_id'=>$user->user_id,'user_type'=>'customer','email'=>$post->email,'token'=>$resetLink]);
                        $link = url('customer/reset/password/' . $resetLink);
                        //$msg = Email::get_forgotpass_message($link); 
                       // print_r($msg); die();
                        //Email::sendEmail(geAdminEmail(), $post->email, 'Reset Password', $msg);
                        $email = $request->email;
                        $data['data'] = array("content"=>"Test",'link'=>$link);
                        $var = Mail::send('emails.forgot_pwd', $data, function($message) use($data,$email) {
                        $message->from(getadmin_mail(),'BigBasket');    
                        $message->to($email);
                        $message->subject('Reset password');
                        });
                        
                        return array('httpcode'=>'200','status'=>'success','message'=>'Password Reset','data'=>['message' =>'Reset password link sent to your registered email!']);
                    }
                }
                return array('httpcode'=>'400','status'=>'error','message'=>'Not Found','data'=>['message' =>'We cannot find a user with that e-mail address!','email'=>$post->email]);
        }
        
    }
    
}
