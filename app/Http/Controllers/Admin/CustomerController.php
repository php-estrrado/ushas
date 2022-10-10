<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Session;
use DB;
use Mail;
use App\Models\Country;
use App\Models\Modules;
use App\Models\UserRoles;
use App\Models\Admin;
use App\Models\UserRole;
use App\Models\SaleOrder;
use App\Models\SaleorderItems;
use App\Models\SellerTelecom;
use App\Models\SellerAddress;
use App\Models\CustomerMaster;
use App\Models\CustomerInfo;
use App\Models\CustomerSecurity;
use App\Models\CustomerTelecom;
use App\Models\CustomerAddress;
use App\Rules\Name;
use App\Models\Reward;
use App\Models\CustomerWallet_Model;
use Validator;
use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Client;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }


    public function index($type=NULL)
    {
        $data['title']              =   'Customer';
        $data['menu']               =   'Customer List';
        $data['role']               =    UserRole::where('is_deleted',NULL)->orWhere('is_deleted',0)->where('usr_role_name','Customer')->where('is_active',1)->get();
        if(isset($type) && $type=='today')
        {
        $data['customer']           =    CustomerMaster::where('is_deleted',NULL)->orWhere('is_deleted',0)->whereDate('created_at', '=', date('Y-m-d'))->orderBy('id','DESC')->get();    
        }
        else
        {
        $data['customer']           =    CustomerMaster::where('is_deleted',NULL)->orWhere('is_deleted',0)->orderBy('id','DESC')->get();    
        }
        
        $data['countries']          =    Country::all();
        return view('admin.customer.customer_list', $data);
    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:usr_mst,username',
            'password' => 'required|min:8',
            'status'=>'required',
            'country_code'=>'required',
            'number'=>'required|min:10|unique:usr_telecom,usr_telecom_value',
            'ref_code'=>'nullable'
        ]);

        $input = $request->all();
        if ($validator->passes()) {
             //   $masterId = 106;
          $master =  CustomerMaster::create(['org_id' => 1,
                'username' => $request->email,
                'ref_code' => Str::random(6),
                'is_active'=>$request->status,
                'is_deleted'=>0,
                'created_by'=>auth()->user()->id,
                'updated_by'=>auth()->user()->id,
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
           'user_id' => $masterId,
           'usr_role_id' => $request->role,
           'profile_image'=>$filename,
           'is_active'=>$request->status,
           'is_deleted'=>0,
           'created_by'=>auth()->user()->id,
           'updated_by'=>auth()->user()->id,
           'created_at'=>date("Y-m-d H:i:s"),
           'updated_at'=>date("Y-m-d H:i:s")]);

           $security = CustomerSecurity::create(['org_id' => 1,
           'password_hash' => Hash::make($request->password),
           'user_id' => $masterId,
           'is_active'=>$request->status,
           'is_deleted'=>0,
           'created_by'=>auth()->user()->id,
           'updated_by'=>auth()->user()->id,
           'created_at'=>date("Y-m-d H:i:s"),
           'updated_at'=>date("Y-m-d H:i:s")]);

           $telecom_email = CustomerTelecom::create(['org_id' => 1,
           'user_id' => $masterId,
           'usr_telecom_typ_id'=>1,
           'usr_telecom_value'=>$request->email,
           'is_active'=>$request->status,
           'is_deleted'=>0,
           'created_by'=>auth()->user()->id,
           'updated_by'=>auth()->user()->id,
           'created_at'=>date("Y-m-d H:i:s"),
           'updated_at'=>date("Y-m-d H:i:s")]);
           $email_tele=$telecom_email->id;

           $telecom_ph = CustomerTelecom::create(['org_id' => 1,
           'user_id' => $masterId,
           'usr_telecom_typ_id'=>2,
           'usr_telecom_value'=>$request->number,
           'country_code'=>$request->country_code,
           'is_active'=>$request->status,
           'is_deleted'=>0,
           'created_by'=>auth()->user()->id,
           'updated_by'=>auth()->user()->id,
           'created_at'=>date("Y-m-d H:i:s"),
           'updated_at'=>date("Y-m-d H:i:s")]);
           $ph_tele=$telecom_ph->id;

           CustomerMaster::where('id',$masterId)->update([
               'email'=>$email_tele,
               'phone'=>$ph_tele
           ]);

        
           $headers[] = 'Content-Type: application/json';
           $datapass = json_encode(array(
            //'unique_id'=>$masterId,
            'Customer_Id'=>0,
            'CustomerName' => $request->first_name,
            'emailid' => $request->email,
           // 'MobileNo'=> $request->phone_number,
            'CustomerStatus'=>true,
            'GSTNomber'=>'',
            'CustomerPOCName'=>$request->first_name,
            'DivisionId'=>config('crm.divId'),
            'Street'=>'NULL',
            'City'=>'NULL',
            'Country'=>'NULL',
            'State'=>'NULL',
            'Customer_Type_Id'=>'',
            'CustomerCode'=>'',
            'IsNDPApplicable'=>true,
            'AuthorityApproval'=>false,
            'ActiveFlag'=>'',
            'BranchId'=>0,
            'UserId'=>config('crm.userID'),
            'OrganisationId'=>config('crm.orgId'),
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
            'crno'=>'',
            'vatno'=>'',
            'mobileno1'=>$request->number,
            'mobileno2'=>'',
            'landline'=>'',
            'Need'=>'',
            //'Quantity'=>'',
            'PinCode'=>'',
            'DistrictName'=>'',
            'Latitude'=>'',
            'Longitude'=>'',
        ));
           $url_cust_reg = config('crm.customer_api');
           $handle = curl_init($url_cust_reg);
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $datapass);
            curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);    
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($handle);
            curl_close($handle);
            $return_response = json_decode($response);
            CustomerMaster::where('id',$masterId)->update(['crm_unique_id'=>$return_response->data->Customer_Id,'customer_code'=>$return_response->data->CustomerCode]);
        //   return $return_response->data->Customer_Id;die;

            
           $customer_email=$request->email;
           $data['data'] = array("customer_name"=>$request->first_name,'phone'=>$request->country_code.$request->number,'title'=>'Account Activated','message'=>'Your account has been activated.You can access your account using your mobile number: +'.$request->country_code." ".$request->number,'customer_id'=>$masterId);
                                    $var = Mail::send('emails.customer_activate', $data, function($message) use($data,$customer_email) {
                                    $message->from(getadmin_mail(),'Bigbasket');    
                                    $message->to($customer_email);
                                   // $message->cc(['aleenaantony1020@gmail.com']); //myjewelleryshopper@gmail.com
                                    $message->subject('Account Activated');
                                    });




                                    //refcode

                                    if($input['ref_code'])
{       
$ref_code = trim($input['ref_code'], "[object FormData]");                         
$customer=CustomerMaster::where('ref_code',$ref_code)->first();
                if($customer)
                {
                $rwd=CustomerMaster::where('id',$masterId)->update(['invited_by'=>$customer->id]);
                $rewards    =   Reward::where('is_active',1)->where('is_deleted',0)->orderBy('id','DESC')->first();
                if($rewards)
                {
                    if($rewards->reward=='cashback')
                    {
                        if($rewards->rwd_type_referral==6 || $rewards->rwd_type_referral==4)
                        {
                            $cashback_reward = CustomerWallet_Model::create(['user_id'    =>  $masterId,
                            'source_id'  =>  0,
                            'source'     =>  'Referral Register Cashback',
                            'credit'     =>  $rewards->referral_cashback_register,
                            'is_active'  =>  1,
                            'is_deleted' =>  0,
                            'created_at'    =>date("Y-m-d H:i:s"),
                            'updated_at'    =>date("Y-m-d H:i:s")])->id; 

            $from   = 1; 
            $utype  = 2;
            $to     = $masterId;
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
                            //return ['httpcode'=>200,'status'=>'success','message'=>'Success'];
                             }
                        }
                    }
                    elseif($rewards->reward=='coupon')
                    {
                        if($rewards->rwd_type_referral==6 || $rewards->rwd_type_referral==4)
                        {
                            if($rewards->referrer_coupon_register>0){
                        if($rewards->rwd_type_referrer==3 || $rewards->rwd_type_referrer==1)
                        {        
                        $coupon_referrer = CustomerCoupon::where('user_id',$customer->id)->where('coupon_id',$rewards->referrer_coupon_register)->where('is_active',1)->where('is_deleted',0)->first();
                        if(!$coupon_referrer)
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
                        $coupon = CustomerCoupon::where('user_id',$masterId)->where('coupon_id',$rewards->referral_coupon_register)->where('is_active',1)->where('is_deleted',0)->first();
                        if($coupon)
                        {
                         // return ['httpcode'=>'400','status'=>'error','message'=>'Coupon already allocated'];
                        }
                        else
                        {
                            if($rewards->referral_coupon_register>0){
                        $create = ['user_id'=>$masterId,'salesman_id'=>0,'coupon_id'=>$rewards->referral_coupon_register];
                    $cpn_id =CustomerCoupon::create($create)->id;

            $from   = 1; 
            $utype  = 2;
            $to     = $masterId;
            $ntype  = 'reward_coupon';
            $title  = 'Reward Coupon added';
            $desc   = 'you have got a coupon reward';
            $refId  = $cpn_id;
            $reflink = 'customer/my-coupon/list';
            $notify  = 'customer';
            addNotification($from,$utype,$to,$ntype,$title,$desc,$refId,$reflink,$notify);
                            }
                   // return ['httpcode'=>200,'status'=>'success','message'=>'Success'];
                           }
                        }

                        }
                 else{}

                    }
                    
                }
            }
           
                                    //refcode end



//            $client = new \GuzzleHttp\Client();
//            $response =  $client->post($url_cust_reg,
//     array('form_params' => array(
//             'unique_id' => $masterId,
//             'name' => $request->first_name,
//             'email' => $request->email,
//             'mobile'=> $request->number,
//             'country_code'=>$request->country_code
//         )
//     )
// );

//            echo $response->getBody();
//            die;

            return response()->json(['success'=>'success']);

        }
        return response()->json(['errors'=>$validator->errors()->all()]);
    }

    public function view_customer($user_id)
    {
        $data['title']              =   'Customer Info';
        $data['menu']               =   'Customer Details';
        $data['role']               =    UserRole::where('is_deleted',NULL)->orWhere('is_deleted',0)->where('usr_role_name','Customer')->where('is_active',1)->get();
        $data['customer_mst']       =    CustomerMaster::where('is_deleted',0)->where('id',$user_id)->first();
        $data['telecom']            =    CustomerTelecom::where('user_id',$user_id)->where('is_deleted',0)->get();
        $data['customer_addr']       =    CustomerAddress::where('is_deleted',0)->where('user_id',$user_id)->get();
        $data['info']               =    CustomerInfo::where('user_id',$user_id)->where('is_deleted',0)->first();
        $data['wallet']             =    DB::table("usr_cust_wallet")->select(DB::raw("SUM(credit)-SUM(debit) as wallet"))->where("is_deleted",0)->where("user_id",$user_id)->first();
        $data['order']              =    SaleOrder::whereNotIn('order_status',['initiated'])->where('cust_id',$user_id)->get();
        $data['tot_order']          =    SaleOrder::whereNotIn('order_status',['initiated'])->where('cust_id',$user_id)->count();
        $data['sale_amt']           =    SaleOrder::whereNotIn('order_status',['initiated'])->where('cust_id',$user_id)->sum('g_total'); 
        $data['order_cancel']       =    SaleOrder::where('cust_id',$user_id)->where('order_status','cancelled')->count();
        $data['order_refund']       =    SaleOrder::where('cust_id',$user_id)->where('order_status','refund')->count();
        // dd($data);
        return view('admin.customer.view_customer', $data);
    }

    public function update_profile(Request $request,$user_id)
    {  
        
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required',
            'number'=>'required',
            'status'=>'required',
            'number'=>'required|min:10'
        ]);

        if ($validator->passes()) {
            
            if($request->hasFile('profile_img'))
            {
            $file=$request->file('profile_img');
            $extention=$file->getClientOriginalExtension();
            $filename=time().'.'.$extention;
            $file->move(('storage/app/public/customer_profile/'),$filename);
            
            CustomerInfo::where('user_id',$user_id)->where('is_active',1)->update([
                'profile_image'=>$filename,
                'updated_by'=>auth()->user()->id,
                'updated_at'=>date("Y-m-d H:i:s")]);
           // dd($filename);
            }

            CustomerMaster::where('id',$user_id)->update([
                'is_active' => $request->status,
                'updated_by'=>auth()->user()->id,
                'updated_at'=>date("Y-m-d H:i:s")]);

            CustomerInfo::where('user_id',$user_id)->update([
                'first_name' => $request->first_name,
                'last_name' =>$request->last_name,
                'is_active'=>$request->status,
                'updated_by'=>auth()->user()->id,
                'updated_at'=>date("Y-m-d H:i:s")]);

            CustomerTelecom::where('user_id',$user_id)->where('is_active',1)->where('usr_telecom_typ_id',1)->update([
                    'usr_telecom_value' => $request->email,
                    'updated_by'=>auth()->user()->id,
                    'updated_at'=>date("Y-m-d H:i:s")]);  
             
            $teledata=CustomerTelecom::where('user_id',$user_id)->where('is_active',1)->where('usr_telecom_typ_id',2)->first();
            if(is_null($teledata)){
                CustomerTelecom::create(['org_id' => 1,
           'user_id' => $user_id,
           'usr_telecom_typ_id'=>2,
           'usr_telecom_value'=>$request->number,
           'is_active'=>$request->status,
           'is_deleted'=>0,
           'created_by'=>auth()->user()->id,
           'updated_by'=>auth()->user()->id,
           'created_at'=>date("Y-m-d H:i:s"),
           'updated_at'=>date("Y-m-d H:i:s")]);  
            
            }
            else
            {
             CustomerTelecom::where('user_id',$user_id)->where('is_active',1)->where('usr_telecom_typ_id',2)->update([
                        'usr_telecom_typ_id'=>2,
                        'usr_telecom_value' => $request->number,
                        'is_active'=>1,
                        'updated_by'=>auth()->user()->id,
                        'updated_at'=>date("Y-m-d H:i:s")]); 
            }
            
            //CRM UPDATE
            $crmMaster = CustomerMaster::where('id',$user_id)->first()->crm_unique_id;
            if($crmMaster>0)
            {
                $crmMasterID=$crmMaster;
            }
            else
            {
                $crmMasterID=0;
            }
            $headers[] = 'Content-Type: application/json';
           $datapass = json_encode(array(
            //'unique_id'=>$masterId,
            'Customer_Id'=>$crmMasterID,
            'CustomerName' => $request->first_name.' '.$request->last_name,
            'emailid' => $request->email,
           // 'MobileNo'=> $request->phone_number,
            'CustomerStatus'=>true,
            'GSTNomber'=>'',
            'CustomerPOCName'=>$request->first_name,
            'DivisionId'=>config('crm.divId'),
            'Street'=>'NULL',
            'City'=>'NULL',
            'Country'=>'NULL',
            'State'=>'NULL',
            'Customer_Type_Id'=>'',
            'CustomerCode'=>'',
            'IsNDPApplicable'=>true,
            'AuthorityApproval'=>false,
            'ActiveFlag'=>'',
            'BranchId'=>0,
            'UserId'=>config('crm.userID'),
            'OrganisationId'=>config('crm.orgId'),
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
            'crno'=>'',
            'vatno'=>'',
            'mobileno1'=>$request->number,
            'mobileno2'=>'',
            'landline'=>'',
            'Need'=>'',
            //'Quantity'=>'',
            'PinCode'=>'',
            'DistrictName'=>'',
            'Latitude'=>'',
            'Longitude'=>'',
        ));
           $url_cust_reg = config('crm.customer_api');
           $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$url_cust_reg);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $datapass);           
           // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $response = curl_exec($ch);
            $err = curl_error($ch);
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            curl_close($ch);
            return $err;die;
            $return_response = json_decode($response);
            
            
            CustomerMaster::where('id',$user_id)->update(['crm_unique_id'=>$return_response->data->Customer_Id,'customer_code'=>$return_response->data->CustomerCode]);
            //**end CRM
            
             Session::flash('message', ['text'=>'Customer updated successfully.','type'=>'success']);
                        
                        return redirect(url('admin/customer/view/'.$user_id));           
        }

    }
    
     public function invoice($id)
    {
        $data['title']              =   'Invoice';
        $data['menu']               =   'Invoice';
        $data['order']              =    SaleOrder::where('id',$id)->first();
        $user_id = $data['order']->cust_id; 
        $data['user_id']               =   $user_id;
        
        $data['customer_mst']       =    CustomerMaster::where('is_deleted',0)->where('id',$user_id)->first();
        $data['telecom']            =    CustomerTelecom::where('user_id',$user_id)->where('is_active',1)->where('is_deleted',0)->get();
        $data['info']               =    CustomerInfo::where('user_id',$user_id)->where('is_active',1)->where('is_deleted',0)->first();
        $data['wallet']             =    DB::table("usr_cust_wallet")->select(DB::raw("SUM(credit)-SUM(debit) as wallet"))->where("is_deleted",0)->where("user_id",$user_id)->first();
        
       $data['seller_address']  = SellerAddress::where('seller_id',auth()->user()->id)->where('is_deleted',0)->first();
       if($data['seller_address']) {
        $data['seller_address_city']  = getCities($data['seller_address']->city_id);
       }
        $data['order_items']             = SaleorderItems::where('sales_id',$id)->get();
       
        // dd($data);
        return view('admin.customer.invoice', $data);
    }

    //CUSTOMER REQUEST

    public function request_index()
    {
        $data['title']              =   'New Customers Request List';
        $data['menu']               =   'customer-request';
        // $data['role']               =    UserRole::where('is_deleted',NULL)->orWhere('is_deleted',0)->where('usr_role_name','Customer')->where('is_active',1)->get();
        $data['customer']           =    CustomerMaster::whereIn('is_approved',[0,2])->where('is_deleted',0)->orderBy('id','DESC')->get();
        return view('admin.customer.request.page', $data);
    }

    public function request_cust_view(Request $request,$user_id)
    {
        $data['title']              =   'New Customers Request List';
        $data['menu']               =   'customer-request';
        // $data['role']               =    UserRole::where('is_deleted',NULL)->orWhere('is_deleted',0)->where('usr_role_name','Customer')->where('is_active',1)->get();
        $data['customer']           =    CustomerMaster::where('id',$user_id)->first();
        return view('admin.customer.request.view', $data);
    }

    
    function base64_encode_html_image($img_file, $alt = null, $cache = false, $ext = null)
    {
      if (!is_file($img_file)) {
        return false;
      }
    
      $b64_file = "{$img_file}.b64";
      if ($cache && is_file($b64_file)) {
        $b64 = file_get_contents($b64_file);
      } else {
        $bin = file_get_contents($img_file);
        $b64 = base64_encode($bin);
    
        if ($cache) {
          file_put_contents($b64_file, $b64);
        }
      }
    
      if (!$ext) {
        $ext = pathinfo($img_file, PATHINFO_EXTENSION);
      }
    
      return "{$b64}";
    }

     public function updateStatus(Request $request)
    { 
        $post = (object)$request->post();
        //return $post;
        if($post->field=='is_approved'){

        $update = CustomerMaster::where('id',$post->id)->update(['is_approved'=>$post->value]);
        if($post->value==1)
        {


           $headers[] = 'Content-Type: application/json';
            if(authenticateOdoo()){
              $headers[] = 'Cookie: '.authenticateOdoo();  
            }
            
            $cust_mst =CustomerMaster::where('id',$post->id)->first();
            $customer_email=$cust_mst->custEmail($cust_mst->email);

            if($cust_mst->info->profile_image !="")
            {
                $prof_img = storage_path('/app/public/customer_profile/'.$cust_mst->info->profile_image);
                $base64_img = $this->base64_encode_html_image($prof_img, '1x1');

            }else{
                $base64_img = "";
            }

            // dd($base64_img);

            $datapass = json_encode(array(
            'jsonrpc'=>"2.0",
            'method'=>"call",
            'params'=>array(
            'model'=>"res.partner",
            'method'=>"create_customer",
            'args'=>[[]],
            'kwargs'=>array(
                'vals'=>array(
                    'first_name'=>$cust_mst->info->first_name,
                    'last_name'=>$cust_mst->info->last_name,
                    'email'=>$customer_email,
                    'phone'=>$cust_mst->custPhonecode($cust_mst->phone)." ".$cust_mst->custPhone($cust_mst->phone),
                    'ref_no'=>'#Test',
                    'contact_type'=>'customer',
                    'bb_partner_id'=>$post->id,
                    'image'=>$base64_img,
                )
            ),
            ),
            )); 


           $url_cust_reg = "http://3.109.84.120:7054/web/dataset/call_kw";
           $handle = curl_init($url_cust_reg);
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $datapass);
            curl_setopt($handle, CURLOPT_HTTPHEADER, $headers); 
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($handle);
            
            if (curl_errno($handle)) {
            $error_msg = curl_error($handle);
            // dd($error_msg);
            }
            curl_close($handle);
            $return_response = json_decode($response,true);
            // dd($return_response);
            if(isset($return_response) && isset($return_response['result'])){
            CustomerMaster::where('id',$post->id)->update(['odoo_id'=>$return_response['result']['partner_id']]);
           // print_r($msg); die();
            // if ($update) Email::sendEmail(geAdminEmail(), $post->email, 'Reset Password', $msg);
           }


            $cust_mst =CustomerMaster::where('id',$post->id)->first();
            $customer_email=$cust_mst->custEmail($cust_mst->email);
           $data['data'] = array("customer_name"=>$cust_mst->info->first_name,'phone'=>$cust_mst->custPhonecode($cust_mst->phone).$cust_mst->custPhone($cust_mst->phone),'title'=>'Account Activated','message'=>'Your account has been activated.You can access your account using your mobile number: +'.$cust_mst->custPhonecode($cust_mst->phone)." ".$cust_mst->custPhone($cust_mst->phone),'customer_id'=>$cust_mst->id);
                                    $var = Mail::send('emails.customer_activate', $data, function($message) use($data,$customer_email) {
                                    $message->from(getadmin_mail(),'Bigbasket');    
                                    $message->to($customer_email);
                                   // $message->cc(['aleenaantony1020@gmail.com']); //myjewelleryshopper@gmail.com
                                    $message->subject('Account Activated');
                                    });
        }
        }
        // return 'success';
        
    }

}
