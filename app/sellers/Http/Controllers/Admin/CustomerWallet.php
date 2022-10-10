<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;
use App\Models\Modules;
use App\Models\UserRoles;
use App\Models\Admin;
use App\Models\UserRole;
use App\Models\customer\CustomerMaster;
use App\Models\customer\CustomerInfo;
use App\Models\customer\CustomerTelecom;
use App\Models\customer\CustomerWallet_Model;
use App\Rules\Name;
use Validator;

class CustomerWallet extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
   public function index()
   {
    $data['title']              =   'Customer Wallet';
    $data['menu']               =   'Customer Wallet List';
    $data['wallet']             = DB::table("usr_cust_wallet")->select(DB::raw("user_id,SUM(credit)-SUM(debit) as wallet"))->orderBy("id","DESC")->groupBy(DB::raw("user_id"))->get();
   return view('customer.wallet.list', $data);
   }
   
   public function wallet_log($cus_id)
   {
        $data['wallet']           = DB::table("usr_cust_wallet")->select(DB::raw("user_id,SUM(credit)-SUM(debit) as wallet"))->where('user_id',$cus_id)->where('is_deleted',0)->orderBy("id")->groupBy(DB::raw("user_id"))->first();
        $data['transaction']      = CustomerWallet_Model::where('user_id',$cus_id)->where('is_deleted',0)->orderBy('id','DESC')->get();
        $data['customer']         = CustomerInfo::where('user_id',$cus_id)->first();  
        return view('customer.wallet.log_view', $data);

   }
   public function delete_wallet(Request $request)
    {
        $wallet_id=$request->w_id;
        CustomerWallet_Model::where('id',$wallet_id)->update([
            'is_active'=>0,
            'is_deleted'=>1,
            'updated_at'=>date("Y-m-d H:i:s")

        ]);
            Session::flash('message', ['text'=>'Deleted successfully','type'=>'success']);
    }
}
