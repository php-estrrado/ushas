<?php

namespace App\Http\Controllers\Api\CRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\PasswordReset;
use App\Models\Email;
use App\Models\CustomerMaster;
use App\Models\CustomerSecurity;
use App\Models\CustomerTelecom;
use App\Models\CustomerAddress;
use App\Models\CustomerInfo;
use App\Models\CustomerRegisterotp;
use App\Models\Coupon;
use App\Models\CustomerCoupon;
use Illuminate\Support\Str;
use Validator;
use Mail;

class CrmCustomerController extends Controller
{
    public function insert_customer(Request $request)
    {
        $ph = ['usr_telecom_value'=>[$request->phone_number]];
        $validator = Validator::make($request->all(), [
            'unique_id'=>['required','numeric'],
            'name' => ['required','max:255'],
            'country_code' => ['nullable','max:20'],
            'email' => ['required','nullable','email','max:255','unique:usr_mst,username'],
            'longitude'=>['nullable'],
            'latitude'=>['nullable'],
            'zipcode'=>['required'],
            'street'=>['nullable'],
            'type'=>['required','in:crm,ecom'],
            'phone_number'=>['required','nullable','min:7,12','unique:usr_telecom,usr_telecom_value']
        ]);

        $input = $request->all();

        if ($validator->passes()) {
            
            $invited_by='';
            $uiid_odoo= $uiid_crm='';
            if($request->type=="odoo")
            {
                $uiid_odoo= $request->unique_id;
            }
            else
            {
                $uiid_crm= $request->unique_id;
            }
            // if($request->ref_code)
            // {
            //     $invite=CustomerMaster::where('ref_code',$request->ref_code)->first();
            //     if($invite)
            //     {
            //     $invited_by = $invite->id;
            //     $reward = Reward::where('is_active',1)->where('ord_type','cashback')->where('is_deleted',0)->first();
            //     if($reward->rwd_type==1 || $reward->rwd_type==1)
            //         {
            //             $typ_pts = $reward->rewardType_register()->points;
            //            if($typ_pts!='')
            //            {
            //                $credit_value = $typ_pts * $reward->point_val;
            //            }
            //            else
            //            {
            //                $credit_value =1 * $reward->point_val;
            //            }
            //            $cashback_reward_invite = CustomerWallet_Model::create(['user_id'    =>  $invited_by,
            //                                               'source_id'  =>  $reward->id,
            //                                               'source'     =>  'Reward',
            //                                               'credit'     =>  $credit_value,
            //                                               'is_active'  =>  1,
            //                                               'is_deleted' =>  0,
            //                                               'created_at'    =>date("Y-m-d H:i:s"),
            //                                               'updated_at'    =>date("Y-m-d H:i:s")]);
            //         }
            //     }
            // }
            $email = $request->email;
            //return $email;die;
            $random = Str::random(6);
            $master =  CustomerMaster::create(['org_id' => 1,
                'username' => $request->email,
                'ref_code' => $random,
                'invited_by'=>$invited_by,
                'crm_unique_id'=>$uiid_crm,
                'usr_platform'=>$request->type,
                'crm_customer_type'=>$request->customer_type,
                'is_active'=>1,
                'is_deleted'=>0,
                'is_approved'=>1,
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
                'first_name' => $request->name,
                'user_id' => $masterId,
                'usr_role_id' => 5,
                'profile_image'=>$filename,
                'address'=>$request->building.$request->street,
                'pincode'=>$request->zipcode,
                'city_id'=>$request->city,
                'state_id'=>$request->state,
                'country_id'=>$request->country,
                'pan_number'=>$request->pan_number,
                'gst_number'=>$request->gst_number,
                'is_active'=>1,
                'is_deleted'=>0,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")]);

            $pwd_random = Str::random(8);

            $security = CustomerSecurity::create(['org_id' => 1,
                'password_hash' => Hash::make($pwd_random),
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
                    'usr_telecom_value'=>$request->email,
                    'country_code'=>'',
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
                    'country_code'=>'+91',
                    'usr_telecom_value'=>$request->phone_number,
                    'is_active'=>1,
                    'is_deleted'=>0,
                    'created_at'=>date("Y-m-d H:i:s"),
                    'updated_at'=>date("Y-m-d H:i:s")]);
                $ph_tele=$telecom_ph->id;

                CustomerMaster::where('id',$masterId)->update([
                    'phone'=>$ph_tele
                ]);
                $this->loginSendotp($request->phone_number);
            }

            $address= CustomerAddress::create(['org_id'=>1,
                'user_id'=>$masterId,
                'usr_addr_typ_id'=>1,
                'name'=>$request->name,
                'email'=>$request->email,
                'country_code'=>'+91',
                'address_1'=>$request->building.$request->street.$request->city,
                'street'=>$request->street,
                'pincode'=>$request->zipcode,
                // 'city_id'=>$request->city,
                'state_id'=>$request->state,
                'country_id'=>$request->country,
                'longitude'=>$request->longitude,
                'latitude'=>$request->latitude,
                'is_active'=>1,
                'is_default'=>1,
                'is_deleted'=>0,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")
            ]);
           
            CustomerRegisterotp::where('country_code','+91')->where('phone_number',$request->phone_number)->where('is_active',1)->where('is_deleted',0)->update(['status'=>0]);
            
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
            
            
            
            $user_name  = $request->name;
            
            $email = $request->email;
            $data['data'] = array("content"=>"Test",'user_name'=>$user_name,'password'=>$pwd_random);
            $var = Mail::send('emails.account_success_msg_pos', $data, function($message) use($data,$email) {
            $message->from(getadmin_mail(),'USHAS');    
            $message->to($email);
            $message->subject('Registration Success');
            });
            
            // print_r($msg); die();
            // if ($update) Email::sendEmail(geAdminEmail(), $post->email, 'Reset Password', $msg);
            
            //notification
            $from   = 1; 
            $utype  = 3;
            $to     = 1;
            $ntype  = 'new_customer';
            $title  = 'New Customer';
            $desc   = 'New customer has been registered';
            $refId  = $masterId;
            $reflink = 'admin/customer';
            $notify  = 'admin';
            addNotification($from,$utype,$to,$ntype,$title,$desc,$refId,$reflink,$notify);
            //endnotification
        
            $customer_email=$request->email;
            $data['data'] = array("customer_name"=>$request->name,'phone'=>$request->country_code.$request->phone_number,'title'=>'Account Activated','message'=>'Your account has been activated.You can access your account using your mobile number: +'.$request->country_code." ".$request->phone_number,'customer_id'=>$masterId);
            $var = Mail::send('emails.customer_activate', $data, function($message) use($data,$customer_email) {
                $message->from(getadmin_mail(),'Ushas');    
                $message->to($customer_email);
                // $message->cc(['aleenaantony1020@gmail.com']); //myjewelleryshopper@gmail.com
                $message->subject('Account Activated');
            });
            return response()->json(['httpcode'=>200,'success'=>'Successfully registered!','primary_key'=>$masterId]);

        }
        return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
    }

    public function offerlist(Request $request)
    {
        $data       =   $request->all(); 
        $post       =   (object)$request->post();
        $getcoupons = Coupon::getAllCoupons();
        $cp_list = [];
        if(count($getcoupons)>0)
        {
            foreach($getcoupons as $rows)
            {
                $list['coupon_id']      = $rows['id'];
                $list['cpn_title']      = $rows['cpn_title'];
                $list['cpn_desc']       = $rows['cpn_desc'];
                $list['cat_name']       = $rows['cat_name'];
                $list['subcat_name']    = $rows['subcat_name'];
                $list['purchase_type']  = $rows['purchase_type'];
                $list['purchase_number'] = $rows['purchase_number'];
                $list['purchase_amount'] = $rows['purchase_amount'];
                $list['ofr_value_type']  =   $rows['ofr_value_type'];
                $list['ofr_value']       =   $rows['ofr_value']; 
                $list['ofr_type']        =   $rows['ofr_type']; 
                $list['ofr_code']        =   $rows['ofr_code']; 
                $list['ofr_min_amount']  =   $rows['ofr_min_amount']; 
                $list['validity_type']   =   $rows['validity_type'];
                $list['valid_from']      =   $rows['valid_from'];
                $list['valid_to']        =   $rows['valid_to'];
                $list['valid_days']      =   $rows['valid_days']; 
                if($rows['image']){$list['image']=config('app.storage_url').$rows['image'];}
                else
                {$list['image']=false;}
                $list['is_active']      =   $rows['is_active']; 
                $list['is_deleted']     =   $rows['is_deleted']; 
                $cp_list[] = $list;
            }
        }
        return ['httpcode'=>200,'status'=>'success','message'=>'Offers List','data'=>$cp_list];
    }

    public function update_customer(Request $request)
    {
        $uniq_id = $request->customer_id;
        if($request->type=='odoo')
        {
            $users = CustomerMaster::where('id',$uniq_id)->where('usr_platform',$request->type)->first();
        }
        else
        {
            $users = CustomerMaster::where('id',$uniq_id)->where('usr_platform',$request->type)->first();
        }
        
        if(!$users)
        {
            return ['httpcode'=>404,'status'=>'error','message'=>'Not found'];
        }

        $ph = ['usr_telecom_value'=>[$request->phone_number]];
        $validator = Validator::make($request->all(), [
            'customer_id'=>['required','numeric'],
            'unique_id'=>['required','numeric'],
            'name' => ['required','max:255'],
            'country_code' => ['nullable','max:20'],
            'email' => ['required','nullable','email','max:255','unique:usr_mst,username,'.$users->id],
            'longitude'=>['nullable'],
            'latitude'=>['nullable'],
            'zipcode'=>['required'],
            'street'=>['nullable'],
            'type'=>['required','in:crm,ecom'],
            'phone_number'=>['nullable','min:7,12','unique:usr_telecom,usr_telecom_value,'.$users->id.',user_id']
        ]);

        $input = $request->all();

        if ($validator->passes()) {
            
            $invited_by='';
            $uiid_odoo= $uiid_crm='';
            if($request->type=="odoo")
            {
                $uiid_odoo= $request->unique_id;
            }
            else
            {
                $uiid_crm= $request->unique_id;
            }
            // if($request->ref_code)
            // {
            //     $invite=CustomerMaster::where('ref_code',$request->ref_code)->first();
            //     if($invite)
            //     {
            //     $invited_by = $invite->id;
            //     $reward = Reward::where('is_active',1)->where('ord_type','cashback')->where('is_deleted',0)->first();
            //     if($reward->rwd_type==1 || $reward->rwd_type==1)
            //         {
            //             $typ_pts = $reward->rewardType_register()->points;
            //            if($typ_pts!='')
            //            {
            //                $credit_value = $typ_pts * $reward->point_val;
            //            }
            //            else
            //            {
            //                $credit_value =1 * $reward->point_val;
            //            }
            //            $cashback_reward_invite = CustomerWallet_Model::create(['user_id'    =>  $invited_by,
            //                                               'source_id'  =>  $reward->id,
            //                                               'source'     =>  'Reward',
            //                                               'credit'     =>  $credit_value,
            //                                               'is_active'  =>  1,
            //                                               'is_deleted' =>  0,
            //                                               'created_at'    =>date("Y-m-d H:i:s"),
            //                                               'updated_at'    =>date("Y-m-d H:i:s")]);
            //         }
            //     }
            // }
            $email = $request->email;
            $telephone = $request->phone_number;
            //return $email;die;
            $random = Str::random(6);

            $masterId = $request->customer_id;

            $customer_email_check = CustomerMaster::where('username',$email)->where('id','!=',$masterId)->first();
            $customer_phone_check = CustomerTelecom::where('usr_telecom_typ_id','2')->where('usr_telecom_value',$telephone)->where('user_id','!=',$masterId)->first();
            // dd($customer_check);

            if($customer_email_check)
            {
                return ['httpcode'=>404,'status'=>'error','message'=>'Customer E-mail Already Exists'];
            }

            if($customer_phone_check)
            {
                return ['httpcode'=>404,'status'=>'error','message'=>'Customer Phone Already Exists'];
            }

            $master =  CustomerMaster::where('id',$masterId)->update(['org_id' => 1,
                'username' => $request->email,
                'ref_code' => $random,
                'invited_by'=>$invited_by,
                'crm_unique_id'=>$uiid_crm,
                'usr_platform'=>$request->type,
                'crm_customer_type'=>$request->customer_type,
                'is_active'=>1,
                'is_deleted'=>0,
                'is_approved'=>1,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")]);

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

            $info = CustomerInfo::where('user_id',$masterId)->update(['org_id' => 1,
                'first_name' => $request->name,
                'usr_role_id' => 5,
                'profile_image'=>$filename,
                'address'=>$request->building.$request->street,
                'pincode'=>$request->zipcode,
                'city_id'=>$request->city,
                'state_id'=>$request->state,
                'country_id'=>$request->country,
                'pan_number'=>$request->pan_number,
                'gst_number'=>$request->gst_number,
                'is_active'=>1,
                'is_deleted'=>0,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")]);

            $pwd_random = Str::random(8);

            $security = CustomerSecurity::where('user_id',$masterId)->update(['org_id' => 1,
                'password_hash' => Hash::make($pwd_random),
                'is_active'=>1,
                'is_deleted'=>0,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")]);

            if($request->email)
            {
                $telecom_email = CustomerTelecom::where('user_id',$masterId)->update(['org_id' => 1,
                    'usr_telecom_typ_id'=>1,
                    'usr_telecom_value'=>$request->email,
                    'country_code'=>'',
                    'is_active'=>1,
                    'is_deleted'=>0,
                    'created_at'=>date("Y-m-d H:i:s"),
                    'updated_at'=>date("Y-m-d H:i:s")]);
            }
            if($request->phone_number)
            {
                $telecom_ph = CustomerTelecom::where('user_id',$masterId)->update(['org_id' => 1,
                    'usr_telecom_typ_id'=>2,
                    'country_code'=>'+91',
                    'usr_telecom_value'=>$request->phone_number,
                    'is_active'=>1,
                    'is_deleted'=>0,
                    'created_at'=>date("Y-m-d H:i:s"),
                    'updated_at'=>date("Y-m-d H:i:s")]);

                    $this->loginSendotp($request->phone_number);
            }

            $address= CustomerAddress::where('user_id',$masterId)->update(['org_id'=>1,
                'usr_addr_typ_id'=>1,
                'name'=>$request->name,
                'email'=>$request->email,
                'country_code'=>'+91',
                'address_1'=>$request->building.$request->street.$request->city,
                'street'=>$request->street,
                'pincode'=>$request->zipcode,
                // 'city_id'=>$request->city,
                'state_id'=>$request->state,
                'country_id'=>$request->country,
                'longitude'=>$request->longitude,
                'latitude'=>$request->latitude,
                'is_active'=>1,
                'is_default'=>1,
                'is_deleted'=>0,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")
            ]);
           
            CustomerRegisterotp::where('country_code','+91')->where('phone_number',$request->phone_number)->where('is_active',1)->where('is_deleted',0)->update(['status'=>0]);
            
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
            
            
            
            $user_name  = $request->name;
            
            $email = $request->email;
            $data['data'] = array("content"=>"Test",'user_name'=>$user_name,'password'=>$pwd_random);
            $var = Mail::send('emails.account_success_msg_pos', $data, function($message) use($data,$email) {
            $message->from(getadmin_mail(),'USHAS');    
            $message->to($email);
            $message->subject('Registration Success');
            });
            
            // print_r($msg); die();
            // if ($update) Email::sendEmail(geAdminEmail(), $post->email, 'Reset Password', $msg);
            
            //notification
            $from   = 1; 
            $utype  = 3;
            $to     = 1;
            $ntype  = 'new_customer';
            $title  = 'New Customer';
            $desc   = 'New customer has been registered';
            $refId  = $masterId;
            $reflink = 'admin/customer';
            $notify  = 'admin';
            addNotification($from,$utype,$to,$ntype,$title,$desc,$refId,$reflink,$notify);
            //endnotification
        
            $customer_email=$request->email;
            $data['data'] = array("customer_name"=>$request->name,'phone'=>$request->country_code.$request->phone_number,'title'=>'Account Activated','message'=>'Your account has been activated.You can access your account using your mobile number: +'.$request->country_code." ".$request->phone_number,'customer_id'=>$masterId);
            $var = Mail::send('emails.customer_activate', $data, function($message) use($data,$customer_email) {
                $message->from(getadmin_mail(),'Ushas');    
                $message->to($customer_email);
                // $message->cc(['aleenaantony1020@gmail.com']); //myjewelleryshopper@gmail.com
                $message->subject('Account Activated');
            });
            return response()->json(['httpcode'=>200,'success'=>'Successfully registered!','primary_key'=>$masterId]);

        }
        return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
    }
    
    public function update_customer_2(Request $request)
    {
        $uniq_id = $request->customer_id;
        if($request->type=='odoo')
        {
            $users = CustomerMaster::where('id',$uniq_id)->where('usr_platform',$request->type)->first();
        }
        else
        {
            $users = CustomerMaster::where('id',$uniq_id)->where('usr_platform',$request->type)->first();
        }
        
        if(!$users)
        {
            return ['httpcode'=>404,'status'=>'error','message'=>'Not found'];  
        }
                
        $validator = Validator::make($request->all(), [
            'customer_id'=>['required','numeric'],
            'unique_id'=>['required','numeric'],
            'name' => ['required','max:255'],
            'country_code' => ['nullable','max:20'],
            'email' => ['required','nullable','email','max:255','unique:usr_mst,username,'.$users->id],
            'longitude'=>['nullable'],
            'latitude'=>['nullable'],
            'zipcode'=>['nullable'],
            'street'=>['nullable'],
            'type'=>['required','in:crm,ecom'],
            'phone_number'=>['nullable','min:7,12','unique:usr_telecom,usr_telecom_value,'.$users->id.',user_id']
        ]);


        $input = $request->all();

        if ($validator->passes()) {
            
            // $masterId = $users->id;

            $masterId = $uniq_id;
            
            $master =  CustomerMaster::where('id',$masterId)->update(['username' => $request->email,
                'updated_at'=>date("Y-m-d H:i:s")]);
         

            $info = CustomerInfo::where('user_id',$masterId)->update([
                'first_name' => $request->name,
                'updated_at'=>date("Y-m-d H:i:s")]);

          

            if($request->email)
            {
                $telecom_email = CustomerTelecom::where('user_id',$masterId)->update(['org_id' => 1,
                    'usr_telecom_typ_id'=>1,
                    'country_code'=>'',
                    'usr_telecom_value'=>$request->email,
                    'updated_at'=>date("Y-m-d H:i:s")]);
                    //  $email_tele=$telecom_email->id;
            }
            if($request->phone_number)
            {
                $telecom_ph = CustomerTelecom::where('user_id',$masterId)->update([
                    'usr_telecom_typ_id'=>2,
                    'country_code'=>$request->country_code,
                    'usr_telecom_value'=>$request->phone_number,
                    'updated_at'=>date("Y-m-d H:i:s")]);
                //$ph_tele=$telecom_ph->id;

                //   CustomerMaster::where('id',$masterId)->update([
                //       'phone'=>$ph_tele
                //   ]);
            }

            $address= CustomerAddress::where('user_id',$masterId)->update([
                'usr_addr_typ_id'=>1,
                'name'=>$request->name,
                'email'=>$request->email,
                'country_code'=>$request->country_code,
                'address_1'=>$request->street,
                'pincode'=>$request->zipcode,
                'longitude'=>$request->longitude,
                'latitude'=>$request->latitude,
                'updated_at'=>date("Y-m-d H:i:s")
            ]);
            
            return ['httpcode'=>200,'status'=>'success','message'=>'Customer updated successfully'];
        }
        else
        {
            return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
        }
    }
    
    public function delete_customer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unique_id'=>['required','numeric'],
            'type'=>['required','in:ecom']
        ]);

        $input = $request->all();
        if ($validator->passes())
        {
            $users = CustomerMaster::where('crm_unique_id',$request->unique_id)->where('usr_platform',$request->type)->first();
            if($users)
            {
                $delete_mst = CustomerMaster::where('id',$users->id)->update(['is_deleted'=>1,'updated_at'=>date("Y-m-d H:i:s")]);
                $info_delete = CustomerInfo::where('user_id',$users->id)->update(['is_deleted' =>1,'updated_at'=>date("Y-m-d H:i:s")]);
                return ['httpcode'=>200,'status'=>'success','message'=>'Customer deleted successfully'];
            }
            else
            {
                return ['httpcode'=>404,'status'=>'error','message'=>'Not found'];
            }
        }
        else
        {
            return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
        }
    }

    public function customer_list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type'=>['required','in:crm,ecom'],
        ]);

        if ($validator->passes())
        {
            // $users = CustomerMaster::where('is_deleted',NULL)->orWhere('is_deleted',0)->orWhere('usr_platform',$request->type)->orderBy('id','DESC')->get();

            $users = DB::table('usr_mst')->join('usr_info','usr_mst.id', '=', 'usr_info.user_id')->join('usr_telecom','usr_mst.id', '=', 'usr_telecom.user_id')->join('usr_address','usr_mst.id', '=', 'usr_address.user_id')->select('usr_mst.id AS customer_id', 'usr_mst.*', 'usr_info.*', 'usr_telecom.*', 'usr_address.*')->where('usr_telecom.usr_telecom_typ_id',2)->where('usr_mst.is_deleted',NULL)->orWhere('usr_mst.is_deleted',0)->orWhere('usr_mst.usr_platform',$request->type)->orderBy('usr_mst.id','DESC')->get();

            // echo $users;
            // exit;
            
            $user_list = [];

            if(count($users)>0)
            {
                foreach($users as $user_data)
                {
                    $list['customer_id'] = $user_data->customer_id;
                    $list['ushas_unique_id'] = $user_data->crm_unique_id;
                    $list['customer_name'] = $user_data->first_name;
                    $list['country_code'] = $user_data->country_code;
                    $list['username'] = $user_data->username;
                    $list['customer_telephone'] = $user_data->usr_telecom_value;
                    $list['longitude'] = $user_data->longitude;
                    $list['latitude'] = $user_data->latitude;
                    $list['zipcode'] = $user_data->pincode;
                    $list['street'] = $user_data->address_1;
                    $list['type'] = $user_data->usr_platform;
                    $list['organization_id'] = $user_data->org_id;
                    $list['created_at'] = $user_data->created_at;
                    $list['updated_at'] = $user_data->updated_at;
                    $user_list[] = $list;      
                }

                // print_r(json_decode($users));
                // exit;

                // foreach($users as $user_data)
                // {
                //     $list['customer_id'] = $user_data['id'];
                //     $list['ushas_unique_id'] = $user_data['crm_unique_id'];

                //     $customers_name = CustomerInfo::where('user_id',$user_data['id'])->get();                    
                //     if(isset($customers_name))
                //     {
                //         $customer_decode = json_decode($customers_name[0],true);
                //         $list['customer_name'] = $customer_decode['first_name'];
                //     }
                //     else
                //     {
                //         $list['customer_name'] = '';
                //     }

                //     $customer_country_code = CustomerTelecom::where('user_id',$user_data['id'])->where('usr_telecom_typ_id',2)->get('country_code')->first();
                //     if(isset($customer_country_code))
                //     {
                //         $customer_country_decode = json_decode($customer_country_code[0],true);
                //         $list['country_code'] = $customer_country_decode['country_code'];
                //     }
                //     else
                //     {
                //         $list['country_code'] = '';
                //     }

                //     $list['username'] = $user_data['username'];

                //     $customer_telephone = CustomerTelecom::where('id',$user_data['phone'])->get('usr_telecom_value');
                //     if(isset($customer_telephone))
                //     {
                //         $customer_telephone_decode = json_decode($customer_telephone[0],true);
                //         $list['customer_telephone'] = $customer_telephone_decode['usr_telecom_value'];
                //     }
                //     else
                //     {
                //         $list['customer_telephone'] = '';
                //     }

                //     $customer_longitude = CustomerAddress::where('user_id',$user_data['id'])->get('longitude');
                //     if(isset($customer_longitude))
                //     {
                //         $customer_longitude_decode = json_decode($customer_longitude[0],true);
                //         $list['longitude'] = $customer_longitude_decode['longitude'];
                //     }
                //     else
                //     {
                //         $list['longitude'] = '';
                //     }

                //     $customer_latitude = CustomerAddress::where('user_id',$user_data['id'])->get('latitude');
                //     if(isset($customer_latitude))
                //     {
                //         $customer_latitude_decode = json_decode($customer_latitude[0],true);
                //         $list['latitude'] = $customer_latitude_decode['latitude'];
                //     }
                //     else
                //     {
                //         $list['latitude'] = '';
                //     }

                //     $customer_pin = CustomerAddress::where('user_id',$user_data['id'])->get('pincode');
                //     if(isset($customer_pin))
                //     {
                //         $customer_pin_decode = json_decode($customer_pin[0],true);
                //         $list['zipcode'] = $customer_pin_decode['pincode'];
                //     }
                //     else
                //     {
                //         $list['zipcode'] = '';
                //     }                    

                //     $customer_street = CustomerAddress::where('user_id',$user_data['id'])->get('address_1');
                //     if(isset($customer_street))
                //     {
                //         $customer_street_decode = json_decode($customer_street[0],true);
                //         $list['street'] = $customer_street_decode['address_1'];
                //     }
                //     else
                //     {
                //         $list['street'] = '';
                //     }                    

                //     $list['type'] = $user_data['usr_platform'];
                //     $list['organization_id'] = $user_data['org_id'];
                    
                //     $user_list[] = $list;
                // }
            }
            return ['httpcode'=>200,'status'=>'success','message'=>'Customers List','data'=>$user_list];
        }
        else
        {
            return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
        }
    }

    function loginSendotp($ph)
    {
        $country_code = '+91';
        $phone_number = $ph;
        if(!empty($ph))
        {
            $regexist = CustomerTelecom::where('country_code',$country_code)->where('usr_telecom_value',$phone_number)->where('is_active',1)->where('is_deleted',0);
            if($regexist->count() > 0)
            {
                $otp = rand(1000, 9999); $otp = 1234;
                $ph_no = '+'.$country_code.$phone_number;
                $msg = 'Your code for Login - OTP: '.$otp.', Do not share this with anyone- '.config('app.name');
                $sendOTP_Twilio = twilio_send_otp($ph_no,$msg);
                CustomerTelecom::where('country_code',$country_code)->where('usr_telecom_value',$phone_number)->update(['otp'=>$otp,'otp_sent_at'=>date('Y-m-d H:i:s')]);
                return ['httpcode'=>200,'status'=>'success','message'=>'OTP has been sent to phone','data'=>['otp' =>$otp,'country_code'=>$country_code,'phone_number'=>$phone_number]];
            }
            else
            {
                return array('httpcode'=>'400','status'=>'error','message'=>'Phone number does not exist','data'=>['message' =>'Phone number does not exist!']);
            }
        }
    }
}
