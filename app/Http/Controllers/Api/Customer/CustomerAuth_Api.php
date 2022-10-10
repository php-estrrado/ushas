<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Session;
use DB;
use Mail;
use App\Models\Admin;
use App\Models\CustomerMaster;
use App\Models\CustomerCoupon;
use App\Models\CustomerInfo;
use App\Models\CustomerSecurity;
use App\Models\CustomerTelecom;
use App\Models\CustomerLogin;
use App\Models\CustomerAddress;
use App\Models\CustomerRegisterotp;
use App\Models\CustomerWallet_Model;
use App\Models\Reward;
use App\Models\RewardType;
use App\Models\Email;
use App\Models\RegisterationToken;
use App\Rules\Name;
use Validator;
use Illuminate\Support\Facades\Hash;
use Twilio\Rest\Client;

class CustomerAuth_Api extends Controller
{
    public function register(Request $request)
    {
        $ph = ['usr_telecom_value'=>[$request->phone_number]];
        $validator = Validator::make($request->all(), [
            'first_name' => ['required','max:255'],
            'last_name' => ['nullable','max:255'],
            'country_code' => ['required','max:20'],
            'email' => ['required','nullable','email','max:255','unique:usr_mst,username'],
            'phone_number'=>['required','nullable','min:7,12','unique:usr_telecom,usr_telecom_value'],
            'password' => ['required','min:8','required_with:password_confirmation','confirmed'],
        ]);


        $input = $request->all();

        if ($validator->passes()) {
            
            $invited_by='';
            if($request->ref_code)
            {
                $invite=CustomerMaster::where('ref_code',$request->ref_code)->first();
                if($invite)
                {
                $invited_by = $invite->id;
                // $reward = Reward::where('is_active',1)->where('ord_type','cashback')->where('is_deleted',0)->first();
                // if($reward->rwd_type==1 || $reward->rwd_type==1)
                //     {
                //         $typ_pts = $reward->rewardType_register()->points;
                //        if($typ_pts!='')
                //        {
                //            $credit_value = $typ_pts * $reward->point_val;
                //        }
                //        else
                //        {
                //            $credit_value =1 * $reward->point_val;
                //        }
                //        $cashback_reward_invite = CustomerWallet_Model::create(['user_id'    =>  $invited_by,
                //                                           'source_id'  =>  $reward->id,
                //                                           'source'     =>  'Reward',
                //                                           'credit'     =>  $credit_value,
                //                                           'is_active'  =>  1,
                //                                           'is_deleted' =>  0,
                //                                           'created_at'    =>date("Y-m-d H:i:s"),
                //                                           'updated_at'    =>date("Y-m-d H:i:s")]);
                //     }


                //   $referral =  CustomerMaster::where('ref_code',$request->ref_code)->first(); 
                // $rewards              =   Reward::getRewards();
                // $rewards = (object) $rewards;

                //         if($rewards->reward == "cashback")
                //         {
                //             if($rewards->rwd_type_referral == 6 || $rewards->rwd_type_referral == 4)
                //             {
                              
                //               $cashback_register =  $rewards->referral_cashback_register*$rewards->point_val;
                              
                //             $cashback_reward = CustomerWallet_Model::create(['user_id'    =>  $referral->id,
                //             'source_id'  =>  0,
                //             'source'     =>  'Referral Register Cashback',
                //             'credit'     =>  $cashback_register,
                //             'is_active'  =>  1,
                //             'is_deleted' =>  0,
                //             'created_at'    =>date("Y-m-d H:i:s"),
                //             'updated_at'    =>date("Y-m-d H:i:s")]); 
                //             }
                            
                //         }else{

                         

                //              if($rewards->rwd_type_referral == 6 || $rewards->rwd_type_referral == 4)
                //             {
                              
                //               $cashback_register =  $rewards->referral_cashback_register; 
                              
                //                 $cashback_reward = CustomerCoupon::create(['user_id'    =>  $referral->id,
                //                 'salesman_id'  =>  0,
                //                 'coupon_id'     =>  $rewards->referral_coupon_register,
                //                 'is_active'  =>  1,
                //                 'is_deleted' =>  0,
                //                 'is_used' =>  0,
                //                 'created_at'    =>date("Y-m-d H:i:s"),
                //                 'updated_at'    =>date("Y-m-d H:i:s")]); 
                //             }

                //         }
                }
            }
          $email = $request->email;
          //return $email;die;
          $random = Str::random(6);
          $master =  CustomerMaster::create(['org_id' => 1,
                'username' => $request->email,
                'ref_code' => $random,
                'invited_by'=>$invited_by,
                'is_approved'=>0,
                'is_active'=>0,
                'is_deleted'=>0,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")]);
          $masterId = $master->id;

          if($request->hasFile('profile_img'))
            {
            $file=$request->file('profile_img');
            $extention=$file->getClientOriginalExtension();
            $filename=time().'.'.$extention;
            $file->move(('storage/app/public/customer_profile/'),$filename);
            }
            else
            {
                $filename='';
            }

           $info = CustomerInfo::create(['org_id' => 1,
           'first_name' => $request->first_name,
           'last_name' =>$request->last_name,
           'user_id' => $masterId,
           'usr_role_id' => 5,
           'profile_image'=>$filename,
           'is_active'=>1,
           'is_deleted'=>0,
           'created_at'=>date("Y-m-d H:i:s"),
           'updated_at'=>date("Y-m-d H:i:s")]);

          $security = CustomerSecurity::create(['org_id' => 1,
          'password_hash' => Hash::make($request->password),
          'user_id' => $masterId,
          'is_active'=>1,
          'is_deleted'=>0,
          'created_at'=>date("Y-m-d H:i:s"),
          'updated_at'=>date("Y-m-d H:i:s")]);

           if($request->email)
           {
           $telecom_email = CustomerTelecom::create(['org_id' => 1,
           'user_id' => $masterId,
           'usr_telecom_typ_id'=>1,
           'country_code'=>'',
           'usr_telecom_value'=>$request->email,
           'is_active'=>1,
           'is_deleted'=>0,
           'created_at'=>date("Y-m-d H:i:s"),
           'updated_at'=>date("Y-m-d H:i:s")]);
           $email_tele=$telecom_email->id;

           CustomerMaster::where('id',$masterId)->update([
               'email'=>$email_tele
           ]);
          }
          if($request->phone_number)
           {
           $telecom_ph = CustomerTelecom::create(['org_id' => 1,
           'user_id' => $masterId,
           'usr_telecom_typ_id'=>2,
           'country_code'=>$request->country_code,
           'usr_telecom_value'=>$request->phone_number,
           'is_active'=>1,
           'is_deleted'=>0,
           'created_at'=>date("Y-m-d H:i:s"),
           'updated_at'=>date("Y-m-d H:i:s")]);
           $ph_tele=$telecom_ph->id;

           CustomerMaster::where('id',$masterId)->update([
               'phone'=>$ph_tele
           ]);

           }

           $otp = rand(1000, 9999);
           $otp=1234;
           $ph_no = '+'.$request->country_code.$request->phone_number;
                $msg = 'Your code for Registration - OTP: '.$otp.', Do not share this with anyone- '.config('app.name');
                $sendOTP_Twilio = twilio_send_otp($ph_no,$msg);
           CustomerRegisterotp::create(['user_id'=>$masterId,'country_code'=>$request->country_code,'phone_number'=>$request->phone_number,'otp'=>$otp,'created_at'=>date('Y-m-d H:i:s')]);

        //   $address= CustomerAddress::create(['org_id'=>1,
        //     'user_id'=>$masterId,
        //     'usr_addr_typ_id'=>1,
        //     'city_id'=>$request->city,
        //     'address_1'=>$request->address,
        //     'is_active'=>1,
        //     'is_default'=>1,
        //     'is_deleted'=>0,
        //     'created_at'=>date("Y-m-d H:i:s"),
        //     'updated_at'=>date("Y-m-d H:i:s")
        //     ]);

           // CustomerRegisterotp::where('country_code',$request->country_code)->where('phone_number',$request->phone_number)->where('is_active',1)->where('is_deleted',0)->update(['status'=>0]);
         
        //     $resetLink = base64_encode(rand(100000, 999999) . 'resetpassword' . time() . '1');
        //     $resetLink = urlencode($resetLink);
        //     $currTime = date('Y-m-d H:i:s');
        //     $data = array('active_link' => $resetLink, 'email_verified_at' => $currTime);
            
        //     $update = RegisterationToken::create(['user_id'=>$masterId,'user_type'=>'customer','email'=>$request->email,'token'=>$resetLink]);
        //      $link = url('/customer/account/verification/' . $resetLink);
        //     // $msg = Email::get_account_verification_message($link); 
            
        //  //  return $email;die;
        //     $data['data'] = array("content"=>"Test",'link'=>$link);
        //     $var = Mail::send('emails.verification_email', $data, function($message) use($data,$email) {
        //     $message->from(getadmin_mail(),'MJS');    
        //     $message->to($email);
        //     $message->subject('Email Verification');
        //     });
        
        
        
                $user_name  = $request->first_name." ".$request->last_name;
                
                $email = $request->email;
                $data['data'] = array("content"=>"Test",'user_name'=>$user_name);
                $var = Mail::send('emails.account_success_msg', $data, function($message) use($data,$email) {
                $message->from(getadmin_mail(),'BigBasket');    
                $message->to($email);
                $message->subject('Registration Success');
                });

                $datapass = array(
            'unique_id' => $masterId,
            'CustomerName' => $request->first_name,
            'EmailID' => $request->email,
            'MobileNo'=> $request->phone_number,
            'OrganisationId'=>1,
            'country_code'=>$request->country_code
        );     
        //notification
            $from   = 1; 
            $utype  = 3;
            $to     = 1;
            $ntype  = 'new_customer';
            $title  = 'New Customer';
            $desc   = 'New customer has been registered and waiting for approval';
            $refId  = $masterId;
            $reflink = 'admin/customer/request/list';
            $notify  = 'admin';
            addNotification($from,$utype,$to,$ntype,$title,$desc,$refId,$reflink,$notify);
        //endnotification
        
           $headers[] = 'Content-Type: application/json';
           $datapass = json_encode(array(
            'unique_id'=>$masterId,
            'Customer_Id'=>0,
            'CustomerName' => $request->first_name,
            'EmailID' => $request->email,
            'MobileNo'=> $request->phone_number,
            'CustomerStatus'=>1,
            'GSTNomber'=>'',
            'CustomerPOCName'=>$request->first_name,
            'DivisionId'=>49,
            'Street'=>'NULL',
            'City'=>'NULL',
            'Country'=>'NULL',
            'State'=>'NULL',
            'Customer_Type_Id'=>'',
            'CustomerCode'=>'',
            'IsNDPApplicable'=>'',
            'AuthorityApproval'=>'',
            'ActiveFlag'=>'',
            'BranchId'=>'',
            'UserId'=>0,
            'OrganisationId'=>50,
            'IndustryID'=>'',
            'SourceID'=>'',
            'HowToJoin'=>'',
            'HowToServeUrself'=>'',
            'Typology'=>'',
            'HowIsStoreFront'=>'',
            'StoreInterior'=>'',
            'Ambience'=>'',
            'MainNeeds'=>'',
            'Competitors'=>'',
            'BusinessType'=>'',
            'NoOfSeats'=>'',
            'TurnOver'=>'',
            'StoreFile'=>'',
            'CRFile'=>'',
            'VATFile'=>'',
            'MenuFile'=>'',
            'CRNo'=>'',
            'VATNo'=>'',
            'MobileNo1'=>'',
            'MobileNo2'=>'',
            'LandLine'=>'',
            'Need'=>'',
            'Quantity'=>''
        ));     
           $url_cust_reg = config('crm.customer_api');
           $handle = curl_init($url_cust_reg);
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $datapass);
            curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);    
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($handle);
            curl_close($handle);
            $return_response = json_decode($response,true);
            if(isset($return_response) && isset($return_response->data)){
            CustomerMaster::where('id',$masterId)->update(['crm_unique_id'=>$return_response->data->Customer_Id,'customer_code'=>$return_response->data->CustomerCode]);
           // print_r($msg); die();
            // if ($update) Email::sendEmail(geAdminEmail(), $post->email, 'Reset Password', $msg);
           }
           return response()->json(['httpcode'=>200,'success'=>'OTP sent to your number']);

        }
        return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
    }
    
    public function login(Request $request){  
        $post                       =   (object)$request->post(); $error = [];  $user = false;
        $rules                      =   ['input' => 'required|string|email|max:100','password'=>'required','deviceToken'=> 'required|string|max:200', 'os' => 'required|string|max:20',
                                            'deviceId' => 'required|string|max:200'];
        $validator              =   Validator::make($request->post(),$rules);
        if ($validator->fails()) {
           $rules                   =   ['input' => 'required|numeric|digits_between:7,12'];
            $validator              =   Validator::make($request->post(),$rules);
            if ($validator->fails()){   
                return array('httpcode'=>400,'status'=>'error','message'=>'Invalid Credential','data'=>array('errors' =>(object)['error_msg'=>'Invalid Email or Phone']));
            }else{ $input           =   'phone'; $inputId = 2; }
        }else{ $input               =   'email'; $inputId = 1; }
        $telecom                    =   CustomerTelecom::whereIn('usr_telecom_typ_id',[1,2])->where('usr_telecom_value',$post->input)->first();
        if($telecom){ $user         =   CustomerInfo::where('user_id',$telecom->user_id)->where('is_deleted',0)->first(); }
        if($user){

            if(config('settings.cust_approval') == "yes"){

            $approved = CustomerMaster::where('id',$user->user_id)->first()->is_approved;
            if($approved!=1)
            {
                    return array('httpcode'=>'400','status'=>'error','message'=>'Account is under verification','data'=>['message' =>'Account is under verification!']); 
                    die;
            }
           }
            // $otp                    =   $this->validateOtp($user,$post->otp);
            // if(!$otp){ return ['httpcode'=>400,'status'=>'error','message'=>'Invalid OTP','data'=>['errors' =>['error'=>'Invalid OTP']]]; }
        //**    $info                   =   CustomerInfo::where('user_id',$user->id)->first();
            $security               =   CustomerSecurity::where('user_id',$user->user_id)->first(); 
            // print_r($security);
            // die();
            if(!$security)
            {
                return ['httpcode'=>400,'status'=>'error','redirect'=>'login','message'=>'Invalid credential','data'=>['errors' =>(object)['error_msg'=>'Invalid credential']]];
            }
            if(Hash::check($post->password, $security->password_hash)){ 
                
            if($user->is_active == 0){ $error  =   'This account has been disabled. Please contact Admin'; }
            if($error){
                return ['httpcode'=>400,'status'=>'error','message'=>$error,'data'=>['errors' =>['error'=>$error]]];
            }else{ return $this->authenticateUser($user,$post); }
        }
        //pwd
        else
        {
            return array('httpcode'=>400,'status'=>'error','message'=>'Incorrect password ','data'=>array('errors' =>(object)['error_msg'=>'Incorrect password']));
        }
        }
        else{ return array('httpcode'=>400,'status'=>'error','message'=>'Invalid Account','data'=>array('errors' =>(object)['error_msg'=>'Invalid Account'])); }
        return ['httpcode'=>400,'status'=>'error','redirect'=>'login','message'=>'Invalid credential','data'=>['errors' =>(object)['error_msg'=>'Invalid credential']]];
        
    }
    
    //****old
    public function login1(Request $request){  
        $post                       =   (object)$request->post(); $error = [];  $user = false;
        $rules                      =   ['input' => 'required|string|email|max:100'];
        $validator              =   Validator::make($request->post(),$rules);
        if ($validator->fails()) {
           $rules                   =   ['input' => 'required|numeric|digits_between:7,12'];
            $validator              =   Validator::make($request->post(),$rules);
            if ($validator->fails()){   
                return array('httpcode'=>400,'status'=>'error','message'=>'Invalid Credential','data'=>array('errors' =>(object)['error_msg'=>'Invalid Email or Phone']));
            }else{ $input           =   'phone'; $inputId = 2; }
        }else{ $input               =   'email'; $inputId = 1; }
        $telecom                    =   CustomerTelecom::where('usr_telecom_typ_id',$inputId)->where('usr_telecom_value',$post->input)->first();
        if($telecom){ $user         =   CustomerMaster::where('id',$telecom->user_id)->first(); }
        if($user){ 
            $security               =   CustomerSecurity::where('user_id',$user->id)->first(); 
            if(Hash::check($post->password, $security->password_hash)){ 
                $otp                =   $this->generateOtp($user);
                return ['httpcode'=>200,'status'=>'success','message'=>'OTP hes been sent to phone and email','data'=>['otp' =>$otp,'input'=>$post->input]];
            }
        }
        return ['httpcode'=>400,'status'=>'error','redirect'=>'login','message'=>'Invalid credential','data'=>['errors' =>(object)['error_msg'=>'Invalid credential']]];
        
    }
    
    function generateOtp($user){
        $otp = rand(1000, 9999);    $otp = 1234;
        CustomerTelecom::where('id',$user->phone)->update(['otp'=>$otp,'otp_sent_at'=>date('Y-m-d H:i:s')]); 
        CustomerTelecom::where('id',$user->email)->update(['otp'=>$otp,'otp_sent_at'=>date('Y-m-d H:i:s')]); 
        return $otp;
    }
    
    public function verifyOtp(Request $request){
        $post                       =   (object)$request->post(); $error = [];  $user = false;
        $rules                      =   [
                                            'input' => 'required|string|max:100', 'otp' => 'required|string|max:100',
                                            'deviceToken' => 'required|string|max:200', 'os' => 'required|string|max:20',
                                            'deviceId' => 'required|string|max:200'
                                        ];
        $validator                  =   Validator::make($request->post(), $rules);
        if(isset($post->deviceName)){   $deviceName = $post->deviceName; }else{ $deviceName = NULL; }
        if ($validator->fails()){
            foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0];}  
            return array('httpcode'=>400,'status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
        }
        $telecom                    =   CustomerTelecom::whereIn('usr_telecom_typ_id',[1,2])->where('usr_telecom_value',$post->input)->first();
        if($telecom){ $user         =   CustomerInfo::where('user_id',$telecom->user_id)->where('is_deleted',0)->first(); }
        if($user){
            $otp                    =   $this->validateOtp($user,$post->otp);
            if(!$otp){ return ['httpcode'=>400,'status'=>'error','message'=>'Invalid OTP','data'=>['errors' =>['error'=>'Invalid OTP']]]; }
        //**    $info                   =   CustomerInfo::where('user_id',$user->id)->first();
            
            if($user->is_active == 0){ $error  =   'This account has been disabled. Please contact Admin'; }
            if($error){
                return ['httpcode'=>400,'status'=>'error','message'=>$error,'data'=>['errors' =>['error'=>$error]]];
            }else{ return ['httpcode'=>200,'status'=>'success','message'=>'OTP verified','customer_id'];
            //return $this->authenticateUser($user,$post); }
        }
    }
        // else{ return array('httpcode'=>400,'status'=>'error','message'=>'Invalid OTP','data'=>array('errors' =>(object)['error_msg'=>'Invalid OTP'])); }
    }
    
    function validateOtp($user,$otp){
        if(CustomerTelecom::where('user_id',$user->user_id)->where('otp',$otp)->where('otp','!=',NULL)->count() > 0){
            CustomerTelecom::where('user_id',$user->user_id)->where('otp',$otp)->update(['otp'=>NULL,'otp_verified_at'=>date('Y-m-d H:i:s')]); return true;
        }else{ return false; }
    }
    
    
    function socialLogin(Request $request){
        $post                       =   (object)$request->post(); $error = false; $sField = ''; $user = false;
        if(!isset($post->email))    {   $post->email = ''; }if(!isset($post->phone)){ $post->phone = ''; }if(!isset($post->lname)){ $post->lname = ''; }
        $rules                      =   [
                                            'social_media'      => 'required|string|max:255', 'fname'   => 'required|string|max:255',
                                            'login_id'          => 'required|string|max:255', 'os' => 'required|string|max:55',
                                            'deviceId'         => 'required|string|max:255', 'deviceToken' => 'required|string|max:255',
                                        ];
        if($post->email != '')  {   $rules['email']         =   'string|email|max:255'; }
        $validator                  =   Validator::make($request->post(),$rules);
        if ($validator->fails())    {   foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; } }
        if($post->social_media  ==  'facebook'){ $sField =  'fb_id'; }else if($post->social_media == 'google'){ $sField =  'google_id'; }else if($post->social_media == 'apple'){ $sField =  'apple_id'; } 
        else{ $errorMag[]       =   $error['social_media'] = 'Invalid social media name'; }
        if($error)  {   return      ['httpcode' =>  '400','status'=>'error','message'=>$errorMag[0],'data'=>['errors' =>$error]]; }
        $str_result             =   '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        
        $security               =   CustomerSecurity::where($sField,$post->login_id)->first(); 
        if($security){ $user    =   $this->getUser($security->user_id); }
        if(!$user){
            if($post->email     !=  ''){
                $telecom        =   CustomerTelecom::where('usr_telecom_value',$post->email)->where('usr_telecom_typ_id',1)->first();
                if($telecom)    {   $user   =   $this->getUser($telecom->user_id); }
                if($user)       {   CustomerSecurity::where('user_id',$telecom->user_id)->update([$sField=>$post->login_id]); }
            }
        }
        if(!$user){
            if($post->phone     !=  ''){
                $telecom        =   CustomerTelecom::where('usr_telecom_value',$post->phone)->where('usr_telecom_typ_id',2)->first();
                if($telecom)    {   $user   =   $this->getUser($telecom->user_id); }
                if($user)       {   CustomerSecurity::where('user_id',$telecom->user_id)->update([$sField=>$post->login_id]); }
            }
        }
        
        if($user){ 
            if($user->is_active    ==  0){ $error  =   'This account has been disabled. Please contact Admin'; }
         //   else if($user->is_approved  ==  0){ $error  =   'This account did not approved. Please contact admin.'; }
            if($error){                 return ['httpcode'=>400,'status'=>'error','message'=>$error,'data'=>['errors' =>(object)['error_msg'=>$error]]]; }
            else{  return      $this->authenticateUser($user,$post); }
        }else{
            $userId             =   CustomerMaster::create(['username'=>$post->email])->id;
            if($post->email     !=  ''){ $email = $post->email; }else{ $email   =   'user'.$userId.'@kangtao.com'; }
            if($post->phone     !=  ''){ $phone = $post->phone; }else{ $phone   =   $userId; }
                                    CustomerInfo::create(['user_id'=>$userId,'first_name'=>$post->fname,'last_name'=>$post->lname]);
            $emailId            =   CustomerTelecom::create(['user_id'=>$userId,'usr_telecom_typ_id'=>1,'usr_telecom_value'=>$email])->id;
            $phoneId            =   CustomerTelecom::create(['user_id'=>$userId,'usr_telecom_typ_id'=>2,'usr_telecom_value'=>$phone]);
                                    CustomerSecurity::create(['user_id'=>$userId,'password_hash'=>Hash::make($str_result),$sField=>$post->login_id]);
                                    CustomerMaster::where('id',$userId)->update(['email'=>$emailId,'phone'=>$phoneId]);
            $user               =   $this->getUser($userId); return $this->authenticateUser($user,$post);
        }
    }
    
    function authenticateUser($user,$post){
        $tocken                 =   $user->user_id.substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),0,12);
        if(isset($post->deviceName)){   $deviceName = $post->deviceName; }else{ $deviceName = NULL; }
        if(!isset($post->os))   {   $post->os = 'web'; }
        
         
        $existing = CustomerLogin::where('user_id',$user->user_id)->where('device_id',"!=",$post->deviceId)->where('is_login',1)->where('is_deleted',0);
        if($existing->exists()){
        $existing->update(['is_login'=>0]);
        }
        
        //    $update                 =    User::where('id',$user->id)->update(['is_login'=>1,'otp'=>NULL,'access_token'=>$tocken,'deviceToken'=>$post->deviceToken,'os'=>$post->os]);
        $loginData              =   ['user_id'=>$user->user_id,'device_id'=>$post->deviceId,'device_name'=>$deviceName,'access_token'=>$tocken,'is_login'=>1,'device_token'=>$post->deviceToken,'os'=>$post->os,'login_at'=>date('Y-m-d H:i:s')];
         $loginUser             =   CustomerLogin::where('device_id',$post->deviceId)->where('is_deleted',0);
         
         if($loginUser->exists()){//  dd($loginUser->first());
         $loginData['last_login']    =   $loginUser->first()->login_at; 
             CustomerLogin::where('device_id',$post->deviceId)->where('is_deleted',0)->update($loginData);
             
         }else{ CustomerLogin::create($loginData); }
        if($user){
            $mst                =   CustomerMaster::where('id',$user->user_id)->first();
            $data['user_id']    =   $user->user_id;                 $data['fname']          =   $user->first_name;
            $data['lname']      =   $user->last_name;               $data['phone']          =   $mst->custphone($mst->phone);       
            $data['email']      =   $mst->custEmail($mst->email);
            return ['httpcode'=>200,'status'=>'success','message'=>'Login successfull!','data'=>array('access_token'=>$tocken,'user_details'=>$data)];
        }else{ return array('httpcode'=>400,'status'=>'error','message'=>'Somthing went wrong','data'=>['errors' =>(object)['error_msg'=>'Somthing went wrong']]); }
    }
    
    function getUser($userId){ 
        $query                  =   CustomerInfo::where('user_id',$userId)->where('is_deleted',0)->first();
        if($query->exists)  {       return $query; }else{ return false; }
    }
    
    
    //SEND EMAIL OTP
    public function regSendotpemail(Request $request)
    {
        $formData   =   $request->all(); 
        $rules      =   array();
        $rules['email']    = 'required|email';
        $validator  =   Validator::make($request->all(), $rules);
        if ($validator->fails()) 
            {
                foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
            }
        else
            { 
              $regexist = CustomerTelecom::where('usr_telecom_value',$request->email);
              $reg_uname_exist = CustomerMaster::where('username',$request->email);
              if($regexist->count() > 0)
              {
                return array('httpcode'=>'400','status'=>'error','message'=>'Email already exist','data'=>['message' =>'Email already exist!']);
              }
              else if($reg_uname_exist->count() > 0)
              {
                return array('httpcode'=>'400','status'=>'error','message'=>'Email already exist','data'=>['message' =>'Email already exist!']);
              }
              else
              {
                $otp = rand(1000, 9999); //$otp = 1234;
                
                 $currTime = date('Y-m-d H:i:s');
            
          //  $msg = '<h4>Hi</h4>';
            //$msg .= '<p>Your account has been approved by the Admin. Please use the below OTP for authentication, once authenticated you will be able to create your account password. OTP is valid for 5 minutes</p><h2 style="background: #00466a;margin: 0 auto;width: max-content;padding: 0 10px;color: #fff;border-radius: 4px;">'.$sellerotp.'</h2><p><a href="' . url('/sellers/otp-verification/') . '">Click here</a> to verify your OTP.</p><p style="font-size:0.9em;">Regards,<br />' . ucfirst(geSiteName()) . '</p>';
            //$msg .= '<p>Email Verification</p><h2 style="background: #00466a;margin: 0 auto;width: max-content;padding: 0 10px;color: #fff;border-radius: 4px;">OTP:'.$otp.'</h2><p><p style="font-size:0.9em;">Regards,<br />' . ucfirst(geSiteName()) . '</p>';
             
           // if ($update) 
                // $msg = Email::get_otp_message($otp);
                // $sndemail=Email::sendEmail(geAdminEmail(), $request->email, 'OTP Verification', $msg);
                $req_email = $request->email;
                        $data['data'] = array("content"=>"Test",'otp'=>$otp);
                        $var = Mail::send('emails.get_otp', $data, function($message) use($data,$req_email) {
                        $message->from(getadmin_mail(),'Bigbasket');    
                        $message->to($req_email);
                        $message->subject('OTP Verification');
                        });
            
                $exisit = CustomerRegisterotp::where('email',$request->email)->where('is_active',1)->where('is_deleted',0);
                if($exisit->count() > 0)
                {
                    CustomerRegisterotp::where('email',$request->email)->where('is_active',1)->where('is_deleted',0)->update(['otp'=>$otp]);
                }
                else
                {
                    CustomerRegisterotp::create(['email'=>$request->email,'otp'=>$otp,'created_at'=>date('Y-m-d H:i:s')]);
                }
               
                return ['httpcode'=>200,'status'=>'success','message'=>'OTP hes been sent to email','data'=>['otp' =>$otp,'email'=>$request->email]];
              }
              
            }
    }
    
    public function regVerifyotpemail(Request $request)
    {
        $formData   =   $request->all(); 
        $rules      =   array();
        $rules['email']           = 'required|email';
        $rules['otp']             = 'required|numeric';
        $validator  =   Validator::make($request->all(), $rules);
        if ($validator->fails()) 
            {
                foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
            }
        else
            { 
                $exisit = CustomerRegisterotp::where('email',$request->email)->where('is_active',1)->where('is_deleted',0);
                if($exisit->count() > 0)
                {
                     $exist = CustomerRegisterotp::where('email',$request->email)->where('otp',$request->otp)->where('is_active',1)->where('is_deleted',0);
                     if($exist->count() > 0)
                     {
                        CustomerRegisterotp::where('email',$request->email)->where('otp',$request->otp)->where('is_active',1)->where('is_deleted',0)->update(['status'=>1]);
                    return ['httpcode'=>200,'status'=>'success','message'=>'OTP verified','data'=>['redirect' =>'registeration','email'=>$request->email]];
                     }
                     else
                     {
                         return array('httpcode'=>'400','status'=>'error','message'=>'Invalid OTP','data'=>['message' =>'Entered OTP is Invalid!']);
                     }
                    
                }
                else
                {
                   return array('httpcode'=>'400','status'=>'error','message'=>'Phone number does not exist','data'=>['message' =>'Phone number does not exist!']);
                }
              
            }
    }
    
   
            
            
    public function regSendotp(Request $request)
    {
        $formData   =   $request->all(); 
        $rules      =   array();
        $rules['country_code']    = 'required|string';
        $rules['phone_number']    = 'required|numeric|min:7,12';
        $validator  =   Validator::make($request->all(), $rules);
        if ($validator->fails()) 
            {
                foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
            }
        else
            { 
              $regexist = CustomerTelecom::where('country_code',$request->country_code)->where('usr_telecom_value',$request->phone_number)->where('is_active',1)->where('is_deleted',0);
              if($regexist->count() > 0)
              {
                return array('httpcode'=>'400','status'=>'error','message'=>'Already Exist','data'=>['message' =>'Phone number already exist!']);
              }
              else
              {
                $otp = rand(1000, 9999); $otp = 1234;
                $exisit = CustomerRegisterotp::where('country_code',$request->country_code)->where('phone_number',$request->phone_number)->where('is_active',1)->where('is_deleted',0);
                if($exisit->count() > 0)
                {
                    CustomerRegisterotp::where('country_code',$request->country_code)->where('phone_number',$request->phone_number)->where('is_active',1)->where('is_deleted',0)->update(['otp'=>$otp]);
                }
                else
                {
                    CustomerRegisterotp::create(['country_code'=>$request->country_code,'phone_number'=>$request->phone_number,'otp'=>$otp,'created_at'=>date('Y-m-d H:i:s')]);
                }
               
                return ['httpcode'=>200,'status'=>'success','message'=>'OTP hes been sent to phone','data'=>['otp' =>$otp,'country_code'=>$request->country_code,'phone_number'=>$request->phone_number]];
              }
              
            }
    }
    
    public function regVerifyotp(Request $request)
    {
        $formData   =   $request->all(); 
        $rules      =   array();
        $rules['country_code']    = 'required|string';
        $rules['phone_number']    = 'required|numeric|min:7,12';
        $rules['otp']             = 'required|numeric';
        $validator  =   Validator::make($request->all(), $rules);
        if ($validator->fails()) 
            {
                foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
            }
        else
            { 
                $exisit = CustomerRegisterotp::where('country_code',$request->country_code)->where('phone_number',$request->phone_number)->where('is_active',1)->where('is_deleted',0);
                if($exisit->count() > 0)
                {
                     $exist = CustomerRegisterotp::where('country_code',$request->country_code)->where('phone_number',$request->phone_number)->where('otp',$request->otp)->where('is_active',1)->where('is_deleted',0);
                     if($exist->count() > 0)
                     {
                        CustomerRegisterotp::where('country_code',$request->country_code)->where('phone_number',$request->phone_number)->where('otp',$request->otp)->where('is_active',1)->where('is_deleted',0)->update(['status'=>1]);
                        CustomerMaster::where('id',$exist->first()->user_id)->update(['is_active'=>1]);
                    return ['httpcode'=>200,'status'=>'success','message'=>'OTP verified','data'=>['redirect' =>'registeration','country_code'=>$request->country_code,'phone_number'=>$request->phone_number]];
                     }
                     else
                     {
                         return array('httpcode'=>'400','status'=>'error','message'=>'Invalid OTP','data'=>['message' =>'Entered OTP is Invalid!']);
                     }
                    
                }
                else
                {
                   return array('httpcode'=>'400','status'=>'error','message'=>'Phone number does not exist','data'=>['message' =>'Phone number does not exist!']);
                }
              
            }
    }
    
     public function loginSendotp(Request $request)
    {
        $formData   =   $request->all(); 
        $rules      =   array();
        $rules['country_code']    = 'required|string';
        $rules['phone_number']    = 'required|numeric|min:7,12';
        $validator  =   Validator::make($request->all(), $rules);
        if ($validator->fails()) 
            {
                foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
            }
        else
            { 
              $regexist = CustomerTelecom::where('country_code',$request->country_code)->where('usr_telecom_value',$request->phone_number)->where('is_active',1)->where('is_deleted',0);
              if($regexist->count() > 0)
              {
                if(config('settings.cust_approval') == "yes"){
                $cust_master = CustomerMaster::where('id',$regexist->first()->user_id)->first()->is_approved;
                 }else{
                    $cust_master=1;
                 }
                if($cust_master==1){

                $otp = rand(1000, 9999); $otp = 1234;
                $ph_no = '+'.$request->country_code.$request->phone_number;
                $msg = 'Your code for Login - OTP: '.$otp.', Do not share this with anyone- '.config('app.name');
                $sendOTP_Twilio = twilio_send_otp($ph_no,$msg);
                CustomerTelecom::where('country_code',$request->country_code)->where('usr_telecom_value',$request->phone_number)->update(['otp'=>$otp,'otp_sent_at'=>date('Y-m-d H:i:s')]);
                return ['httpcode'=>200,'status'=>'success','message'=>'OTP has been sent to phone','data'=>['otp' =>$otp,'country_code'=>$request->country_code,'phone_number'=>$request->phone_number]];
                  }
                  else
                  {
                    return array('httpcode'=>'400','status'=>'error','message'=>'Account is under verification','data'=>['message' =>'Account is under verification!']);
                  }
              }
              else
              {
                return array('httpcode'=>'400','status'=>'error','message'=>'Phone number does not exist','data'=>['message' =>'Phone number does not exist!']);
              }
              
            }
    }

    public function loginVerifyotp(Request $request)
    {
        $formData   =   $request->all(); 
        $post       =   (object)$request->post();
        $rules      =   array();
        $rules['country_code']    = 'required|string';
        $rules['phone_number']    = 'required|numeric|min:7,12';
        $rules['otp']             = 'required|numeric';
        $rules['deviceToken']     = 'required|string|max:200';
        $rules['os']              = 'required|string|max:20';
        $rules['deviceId']        = 'required|string|max:200';
        $validator  =   Validator::make($request->all(), $rules);
        if ($validator->fails()) 
            {
                foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
            }
        else
            { 
                $exisit = CustomerTelecom::where('country_code',$request->country_code)->where('usr_telecom_value',$request->phone_number)->where('is_deleted',0)->first();
                if($exisit)
                {
                    if($exisit->is_active == 0)
                    {
                         return array('httpcode'=>'400','status'=>'error','message'=>'Account Disabled','data'=>['message' =>'This account has been disabled. Please contact Admin!']);
                    }
                    else
                    {
                        $ext = CustomerTelecom::where('country_code',$request->country_code)->where('usr_telecom_value',$request->phone_number)->where('otp',$request->otp)->where('is_deleted',0)->where('is_active',1);
                         if($ext->count() > 0)
                         {
                               
                              $user = $ext->first();
                              $user_details = CustomerInfo::where('user_id',$user->user_id)->where('is_deleted',0)->first();
                              return $this->authenticateUser($user_details,$post);
                         }
                         else
                         {
                             return array('httpcode'=>'400','status'=>'error','message'=>'Invalid OTP','data'=>['message' =>'Entered OTP is Invalid!']);
                         }
                    }
                  
                    
                }
                else
                {
                   return array('httpcode'=>'400','status'=>'error','message'=>'Phone number does not exist','data'=>['message' =>'Phone number does not exist!']);
                }
              
            }
    }
    
    
    ///LOGIN EMAIL OTP
    public function loginSendotpemail(Request $request)
    {
        $formData   =   $request->all(); 
        $rules      =   array();
        $rules['email']    = 'required|email';
        $validator  =   Validator::make($request->all(), $rules);
        if ($validator->fails()) 
            {
                foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
            }
        else
            { 
              $regexist = CustomerTelecom::where('usr_telecom_value',$request->email)->where('is_active',1)->where('is_deleted',0);
              if($regexist->count() > 0)
              {
                $otp = rand(1000, 9999); //$otp = 1234;
                CustomerTelecom::where('usr_telecom_value',$request->email)->update(['otp'=>$otp,'otp_sent_at'=>date('Y-m-d H:i:s')]);
                return ['httpcode'=>200,'status'=>'success','message'=>'OTP has been sent to phone','data'=>['otp' =>$otp,'email'=>$request->phone_number]];
              }
              else
              {
                return array('httpcode'=>'400','status'=>'error','message'=>'Email does not exist','data'=>['message' =>'Email does not exist!']);
              }
              
            }
    }
    
    public function loginVerifyotpemail(Request $request)
    {
        $formData   =   $request->all(); 
        $post       =   (object)$request->post();
        $rules      =   array();
        $rules['email']    = 'required|email';
        $rules['otp']             = 'required|numeric';
        $rules['deviceToken']     = 'required|string|max:200';
        $rules['os']              = 'required|string|max:20';
        $rules['deviceId']        = 'required|string|max:200';
        $validator  =   Validator::make($request->all(), $rules);
        if ($validator->fails()) 
            {
                foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
            }
        else
            { 
                $exisit = CustomerTelecom::where('usr_telecom_value',$request->email)->where('is_deleted',0)->first();
                if($exisit)
                {
                    if($exisit->is_active == 0)
                    {
                         return array('httpcode'=>'400','status'=>'error','message'=>'Account Disabled','data'=>['message' =>'This account has been disabled. Please contact Admin!']);
                    }
                    else
                    {
                        $ext = CustomerTelecom::where('usr_telecom_value',$request->email)->where('otp',$request->otp)->where('is_deleted',0)->where('is_active',1);
                         if($ext->count() > 0)
                         {
                              $user = $ext->first();
                              $user_details = CustomerInfo::where('user_id',$user->user_id)->where('is_deleted',0)->first();
                              return $this->authenticateUser($user_details,$post);
                         }
                         else
                         {
                             return array('httpcode'=>'400','status'=>'error','message'=>'Invalid OTP','data'=>['message' =>'Entered OTP is Invalid!']);
                         }
                    }
                  
                    
                }
                else
                {
                   return array('httpcode'=>'400','status'=>'error','message'=>'Email does not exist','data'=>['message' =>'Email does not exist!']);
                }
              
            }
    }


//FORGOT PASSWORD
    public function forgotPassword(Request $request)
    {
        $formData   =   $request->all(); 
        $rules      =   array();
        $rules['country_code']    = 'required_without:email|string';
        $rules['phone_number']    = 'required_without:email|numeric|min:7,12';
        $rules['email']           = 'required_without:phone_number|email';
        $validator  =   Validator::make($request->all(), $rules);
        if ($validator->fails()) 
            {
                foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
            }
        else
            { 
              if(isset($request->phone_number)){
              $regexist = CustomerTelecom::where('country_code',$request->country_code)->where('usr_telecom_value',$request->phone_number)->where('is_active',1)->where('is_deleted',0);
              if($regexist->count() > 0)
              {
                

                if(config('settings.cust_approval') == "yes"){
                $cust_master = CustomerMaster::where('id',$regexist->first()->user_id)->first()->is_approved;
                 }else{
                    $cust_master=1;
                 }

                if($cust_master==1){

                $otp = rand(1000, 9999); $otp = 1234;
                $ph_no = '+'.$request->country_code.$request->phone_number;
                $msg = 'Your code for Change Password - OTP: '.$otp.', Do not share this with anyone- BIGBASKET';
                $sendOTP_Twilio = twilio_send_otp($ph_no,$msg);
                CustomerTelecom::where('country_code',$request->country_code)->where('usr_telecom_value',$request->phone_number)->update(['otp'=>$otp,'otp_sent_at'=>date('Y-m-d H:i:s')]);
                return ['httpcode'=>200,'status'=>'success','message'=>'OTP hes been sent to phone','data'=>['otp' =>$otp,'country_code'=>$request->country_code,'phone_number'=>$request->phone_number]];
                  }
                  else
                  {
                    return array('httpcode'=>'400','status'=>'error','message'=>'Account is under verification','data'=>['message' =>'Account is under verification!']);
                  }
              }
              else
              {
                return array('httpcode'=>'400','status'=>'error','message'=>'Phone number does not exist','data'=>['message' =>'Phone number does not exist!']);
              }

          }//phone

          elseif(isset($request->email))
          {
            $regexist_email = CustomerTelecom::where('usr_telecom_value',$request->email)->where('is_active',1)->where('is_deleted',0);
              if($regexist_email->count() > 0)
              {
                
                 if(config('settings.cust_approval') == "yes"){
                $cust_master = CustomerMaster::where('id',$regexist_email->first()->user_id)->first();
                $cust_master_approved = $cust_master->is_approved;
                 }else{
                    $cust_master_approved=1;
                 }
                if($cust_master_approved==1){

                $otp = rand(1000, 9999); $otp = 1234;
                $msg = 'Your code for Change Password - OTP: '.$otp.', Do not share this with anyone- '.config('app.name');

                CustomerTelecom::where('usr_telecom_value',$request->email)->update(['otp'=>$otp,'otp_sent_at'=>date('Y-m-d H:i:s')]);

                $customer_email = $request->email;
                $data['data'] = array("customer_name"=>$cust_master->info->first_name,'title'=>'OTP for Change Password','message'=>$msg);
                $var = Mail::send('emails.customer_otp', $data, function($message) use($data,$customer_email) {
                                    $message->from(getadmin_mail(),geSiteName());    
                                    $message->to($customer_email);
                                    $message->cc(['aleenaantony1020@gmail.com']); //myjewelleryshopper@gmail.com
                                    $message->subject('Change Password -OTP');
                                    });
                return ['httpcode'=>200,'status'=>'success','message'=>'OTP hes been sent to phone','data'=>['otp' =>$otp,'email'=>$request->email]];
                  }
                  else
                  {
                    return array('httpcode'=>'400','status'=>'error','message'=>'Account is under verification','data'=>['message' =>'Account is under verification!']);
                  }
              
            }
            }//email
            else
              {
                return array('httpcode'=>'400','status'=>'error','message'=>'Invalid Data','data'=>['message' =>'Invalid Data!']);
              }
            
          }
    }

    public function password_verifyotp(Request $request)
    {
        $formData   =   $request->all(); 
        $post       =   (object)$request->post();
        $rules      =   array();
        $rules['country_code']    = 'required_without:email|string';
        $rules['phone_number']    = 'required_without:email|numeric|min:7,12';
        $rules['email']           = 'required_without:phone_number|email';
        $rules['otp']             = 'required|numeric';
        $validator  =   Validator::make($request->all(), $rules);
        if ($validator->fails()) 
            {
                foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
            }
        else
            { 
                if(isset($request->phone_number))
                {
                $exisit = CustomerTelecom::where('country_code',$request->country_code)->where('usr_telecom_value',$request->phone_number)->where('is_deleted',0)->first();
                if($exisit)
                {
                    if($exisit->is_active == 0)
                    {
                         return array('httpcode'=>'400','status'=>'error','message'=>'Account Disabled','data'=>['message' =>'This account has been disabled. Please contact Admin!']);
                    }
                    else
                    {
                        $ext = CustomerTelecom::where('country_code',$request->country_code)->where('usr_telecom_value',$request->phone_number)->where('otp',$request->otp)->where('is_deleted',0)->where('is_active',1);
                         if($ext->count() > 0)
                         {
                               
                              $user = $ext->first();
                              $user_details = CustomerInfo::where('user_id',$user->user_id)->where('is_deleted',0)->first();
                              return array('httpcode'=>'200','status'=>'success','message'=>'Valid User','customer_id'=>$user_details->user_id,'data'=>['message' =>'Valid User!']);
                              //$this->authenticateUser($user_details,$post);
                         }
                         else
                         {
                             return array('httpcode'=>'400','status'=>'error','message'=>'Invalid OTP','data'=>['message' =>'Entered OTP is Invalid!']);
                         }
                    }
                  
                    
                }
                else
                {
                   return array('httpcode'=>'400','status'=>'error','message'=>'Phone number does not exist','data'=>['message' =>'Phone number does not exist!']);
                }

            }//If phone number
            elseif (isset($request->email)) {
                $exisit_email = CustomerTelecom::where('usr_telecom_value',$request->email)->where('is_deleted',0)->first();
                if($exisit_email)
                {
                    if($exisit_email->is_active == 0)
                    {
                         return array('httpcode'=>'400','status'=>'error','message'=>'Account Disabled','data'=>['message' =>'This account has been disabled. Please contact Admin!']);
                    }
                    else
                    {
                        $ext = CustomerTelecom::where('usr_telecom_value',$request->email)->where('otp',$request->otp)->where('is_deleted',0)->where('is_active',1);
                         if($ext->count() > 0)
                         {
                               
                              $user = $ext->first();
                              $user_details = CustomerInfo::where('user_id',$user->user_id)->where('is_deleted',0)->first();
                              return array('httpcode'=>'200','status'=>'success','message'=>'Valid User','customer_id'=>$user_details->user_id,'data'=>['message' =>'Valid User!']);
                              //$this->authenticateUser($user_details,$post);
                         }
                         else
                         {
                             return array('httpcode'=>'400','status'=>'error','message'=>'Invalid OTP','data'=>['message' =>'Entered OTP is Invalid!']);
                         }
                    }
                  
                    
                }
                else
                {
                   return array('httpcode'=>'400','status'=>'error','message'=>'Email does not exist','data'=>['message' =>'Email does not exist!']);
                }
            }
            else
            {
              return array('httpcode'=>'400','status'=>'error','message'=>'Enter valid data','data'=>['message' =>'Enter valid data!']);
            }
            }
    }

    public function change_password(Request $request)
    {
            $formData   =   $request->all(); 
            $rules      =   array();
            $rules['customer_id']='required|numeric';
            $rules['password']='min:8|required_with:password_confirmation|confirmed';
              
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                {   
                    $master   =  CustomerMaster::where('id',$request->customer_id)->where('is_approved',1)->where('is_active',1)->where('is_deleted',0)->first();
                    if($master)
                    {
                    $security               =   CustomerSecurity::where('user_id',$request->customer_id)->first();
                    if($security)
                    {
                            CustomerSecurity::where('user_id',$request->customer_id)->update(['password_hash'=>Hash::make($request->password)]);
                            return array('httpcode'=>'200','status'=>'success','message'=>'Password has been changed','data'=>['message' =>'Your Password has been changed successfully']);
                            
                        
                    }
                    else
                    {
                            $security = CustomerSecurity::create(['org_id' => 1,
                              'password_hash' => Hash::make($request->password),
                              'user_id' => $request->customer_id,
                              'is_active'=>1,
                              'is_deleted'=>0,
                              'created_at'=>date("Y-m-d H:i:s"),
                              'updated_at'=>date("Y-m-d H:i:s")]);
                            return array('httpcode'=>'200','status'=>'success','message'=>'Password has been changed','data'=>['message' =>'Your Password has been changed successfully']);
                    }
                    }
                    else
                    {
                        return array('httpcode'=>400,'status'=>'error','message'=>'Invalid User');
                    }
                }
       
    }//end

    public function referralCode(Request $request)
    {
        $rules      =   array();
            $rules['customer_id']='required|numeric';
            $rules['ref_code']='required|string';
              
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                {
                $customer=CustomerMaster::where('ref_code',$request->ref_code)->first();
                if($customer)
                {
                    $rwd=CustomerMaster::where('id',$request->customer_id)->update(['invited_by'=>$customer->id]);
                $rewards    =   Reward::where('is_active',1)->where('is_deleted',0)->orderBy('id','DESC')->first();
                if($rewards)
                {
                    if($rewards->reward=='cashback')
                    {
                        if($rewards->rwd_type_referral==6 || $rewards->rwd_type_referral==4)
                        {
                            $cashback_reward = CustomerWallet_Model::create(['user_id'    =>  $request->customer_id,
                            'source_id'  =>  0,
                            'source'     =>  'Referral Register Cashback',
                            'credit'     =>  $rewards->referral_cashback_register,
                            'is_active'  =>  1,
                            'is_deleted' =>  0,
                            'created_at'    =>date("Y-m-d H:i:s"),
                            'updated_at'    =>date("Y-m-d H:i:s")])->id; 

            $from   = 1; 
            $utype  = 2;
            $to     = $request->customer_id;
            $ntype  = 'reward_cashback';
            $title  = 'Cashback Reward';
            $desc   = 'Cashback have rewarded on your wallet';
            $refId  = $cashback_reward;
            $reflink = 'customer/profile';
            $notify  = 'customer';
            addNotification($from,$utype,$to,$ntype,$title,$desc,$refId,$reflink,$notify);

              if($rewards->rwd_type_referrer==3 || $rewards->rwd_type_referrer==1)
                        {
                            $cashback_reward_referrer = CustomerWallet_Model::create(['user_id'    =>  $customer->id,
                            'source_id'  =>  0,
                            'source'     =>  'Referrer Invite Cashback',
                            'credit'     =>  $rewards->referrer_cashback_register,
                            'is_active'  =>  1,
                            'is_deleted' =>  0,
                            'created_at'    =>date("Y-m-d H:i:s"),
                            'updated_at'    =>date("Y-m-d H:i:s")])->id; 

            $from   = 1; 
            $utype  = 2;
            $to     = $customer->id;
            $ntype  = 'reward_cashback';
            $title  = 'Cashback Reward';
            $desc   = 'Cashback have rewarded on your wallet';
            $refId  = $cashback_reward_referrer;
            $reflink = 'customer/profile';
            $notify  = 'customer';
            addNotification($from,$utype,$to,$ntype,$title,$desc,$refId,$reflink,$notify);
                            return ['httpcode'=>200,'status'=>'success','message'=>'Success'];
                          }
                        }
                    }
                    elseif($rewards->reward=='coupon')
                    {
                        if($rewards->rwd_type_referral==6 || $rewards->rwd_type_referral==4)
                        {
                            if($rewards->referrer_coupon_register>0){
                        $coupon_referrer = CustomerCoupon::where('user_id',$customer->id)->where('coupon_id',$rewards->referrer_coupon_register)->where('is_active',1)->where('is_deleted',0)->first();
                        if(!$coupon_referrer)
                            {
                                if($rewards->rwd_type_referrer==3 || $rewards->rwd_type_referrer==1)
                                { 
                                $create = ['user_id'=>$customer->id,'salesman_id'=>0,'coupon_id'=>$rewards->referrer_coupon_register];
                                $cpn = CustomerCoupon::create($create)->id;
            $from   = 1; 
            $utype  = 2;
            $to     = $customer->id;
            $ntype  = 'reward_coupon';
            $title  = 'Reward Coupon added';
            $desc   = 'You got a coupon by referring';
            $refId  = $cpn;
            $reflink = 'customer/my-coupon/list';
            $notify  = 'customer';
            addNotification($from,$utype,$to,$ntype,$title,$desc,$refId,$reflink,$notify);
                               }
                            }
                        }    
                        $coupon = CustomerCoupon::where('user_id',$request->customer_id)->where('coupon_id',$rewards->referral_coupon_register)->where('is_active',1)->where('is_deleted',0)->first();
                        if($coupon)
                        {
                          return ['httpcode'=>'400','status'=>'error','message'=>'Coupon already allocated'];
                        }
                        else
                        {
                            if($rewards->referral_coupon_register>0){
                        $create = ['user_id'=>$request->customer_id,'salesman_id'=>0,'coupon_id'=>$rewards->referral_coupon_register];
                    $cpn_id =CustomerCoupon::create($create)->id;

            $from   = 1; 
            $utype  = 2;
            $to     = $request->customer_id;
            $ntype  = 'reward_coupon';
            $title  = 'Reward Coupon added';
            $desc   = 'you have got a coupon reward';
            $refId  = $cpn_id;
            $reflink = 'customer/my-coupon/list';
            $notify  = 'customer';
            addNotification($from,$utype,$to,$ntype,$title,$desc,$refId,$reflink,$notify);
                            }
                    return ['httpcode'=>200,'status'=>'success','message'=>'Success'];
                           }
                        }

                        }
                 else{}

                    }
                else
                {
                  return array('httpcode'=>400,'status'=>'error','message'=>'Invalid Referral Code');  
                }
                
                    
                    
                }

                }
           
    }
}
