<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;
use App\Models\Admin;
use App\Models\Customer\CustomerMaster;
use App\Models\Customer\CustomerInfo;
use App\Models\Customer\CustomerSecurity;
use App\Models\Customer\CustomerTelecom;
use App\Models\Customer\CustomerAddress;
use App\Rules\Name;
use Validator;
use Illuminate\Support\Facades\Hash;

class CustomerAuth_Api extends Controller
{
    public function register(Request $request)
    {
        $ph = ['usr_telecom_value'=>[$request->phone_number]];
        $validator = Validator::make($request->all(), [
            'first_name' => ['required','max:255'],
            'last_name' => ['required','max:255'],
            'email' => ['required_without:phone_number','nullable','email','max:255','unique:usr_mst,username'],
            'phone_number'=>['required_without:email','nullable','min:10','unique:usr_telecom,usr_telecom_value'],
            'password' => ['required','min:8','required_with:password_confirmation','confirmed'],
            'address'=>['required','string','max:255'],
            'country'=>['required'],
            'city'=>['required']
            
        ]);


        $input = $request->all();

        if ($validator->passes()) {

          $master =  CustomerMaster::create(['org_id' => 1,
                'username' => $request->email,
                'ref_code' => $request->ref_code,
                'is_active'=>1,
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

           $address= CustomerAddress::create(['org_id'=>1,
            'user_id'=>$masterId,
            'usr_addr_typ_id'=>1,
            'city_id'=>$request->city,
            'address_1'=>$request->address,
            'is_active'=>1,
            'is_default'=>1,
            'is_deleted'=>0,
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
            ]);
           

           return response()->json(['httpcode'=>200,'success'=>'successfully registered']);

        }
        return response()->json(['error'=>$validator->errors()->all()]);
    }
}
