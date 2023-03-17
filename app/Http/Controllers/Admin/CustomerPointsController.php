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
use App\Models\customer\CustomerCredits;
use App\Models\customer\CustomerCreditLogs;
use App\Models\CustomerPoints;
use App\Rules\Name;
use Validator;

class CustomerPointsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index()
    {
        $data['title']              =   'Customer Points';
        $data['menu']               =   'Customer Points List';

        $credit_table = (new CustomerCredits)->getTable();
        $credit_log_table = (new CustomerCreditLogs)->getTable();
    
        $data['points'] = CustomerPoints::selectRaw("user_id,SUM(credit)-SUM(debit) as balance,SUM(credit) as total")->where("is_deleted",0)->orderBy("id","DESC")->groupBy("user_id")->get();

        return view('customer.points.list', $data);
    }
   
   public function points_log($cus_id)
   {

        $credit_table = (new CustomerCredits)->getTable();
        $credit_log_table = (new CustomerCreditLogs)->getTable();

        $data['points'] = CustomerPoints::selectRaw("user_id,SUM(credit)-SUM(debit) as balance,SUM(credit) as total")->where("is_deleted",0)->where("user_id",$cus_id)->orderBy("id","DESC")->groupBy("user_id")->first();

        $data['transaction']      = CustomerPoints::where('user_id',$cus_id)->orderBy('id','DESC')->get();
        $data['customer']         = CustomerInfo::where('user_id',$cus_id)->first();  
        
        // dd($data);
        
        return view('customer.points.log_view', $data);

   }
 public function manage_credits($cus_id)
   {

        $credit_table = (new CustomerCredits)->getTable();
        $credit_log_table = (new CustomerCreditLogs)->getTable();
        $data['credits'] = CustomerCredits::
        selectRaw("$credit_table.user_id,log.credit_limit,log.credit_days,log.allow_purchase,log.per_purchase,SUM($credit_table.credit)-SUM($credit_table.debit) as balance,SUM($credit_table.debit)-SUM($credit_table.credit) as outstanding,MAX($credit_table.log_id) as log_id")
        ->join("$credit_log_table as log",'log.id','=',"$credit_table.log_id")->where("log.is_deleted",0)->where("$credit_table.user_id",$cus_id)->orderBy("$credit_table.id","DESC")->groupBy("$credit_table.user_id")
        ->first();

        $data['customer']         = CustomerInfo::where('user_id',$cus_id)->first();  
        // dd($data);
        return view('customer.points.manage_credits', $data);

   }

    public function add_credits($cus_id=0)
   {

        $credit_table = (new CustomerCredits)->getTable();
        $credit_log_table = (new CustomerCreditLogs)->getTable();
        $data['credits'] = [];
        
        $data['customers']         = CustomerInfo::where('is_active',1)->where('is_deleted',0)->whereNotIn('user_id',function($query) use($credit_log_table) {
   $query->select('user_id')->from("$credit_log_table");})->get();
        
        return view('customer.points.add_credits', $data);

   }

    public function payment_form(Request $request)
    {
        $user_id  = $request->post('usr_id');
        $data['user_data']           = CustomerCredits::select(DB::raw("user_id,SUM(debit)-SUM(credit) as outstanding"))->where('user_id',$user_id)->orderBy("id")->groupBy(DB::raw("user_id"))->first();

        return view('customer.points.payment_form', $data);
    }

    public function payment(Request $request)
    {
        $post                       =   (object)$request->post();
        $user_id  = $post->user_id;
        $amount  = $post->amount;

        $credit_table = (new CustomerCredits)->getTable();
        $credit_log_table = (new CustomerCreditLogs)->getTable();

        $user_data = CustomerCredits::
   selectRaw("$credit_table.user_id,log.credit_limit,log.credit_days,log.per_purchase,SUM($credit_table.credit)-SUM($credit_table.debit) as balance,SUM($credit_table.debit)-SUM($credit_table.credit) as outstanding,MAX($credit_table.log_id) as log_id")
   ->join("$credit_log_table as log",'log.id','=',"$credit_table.log_id")->where("log.is_deleted",0)->where("$credit_table.user_id",$user_id)->orderBy("$credit_table.id","DESC")->groupBy("$credit_table.user_id")
   ->first();

        $pay_arr = [];
        $pay_arr['user_id'] = $user_id;
        $pay_arr['ref_id'] = 0;
        $pay_arr['log_id'] = $user_data->log_id;
        $pay_arr['credit_limit'] = $user_data->credit_limit;
        $pay_arr['credit_days'] = $user_data->credit_days;
        $pay_arr['credit'] = $amount;
        $pay_arr['per_purchase'] = $user_data->per_purchase;
        $pay_arr['created_by'] = auth()->user()->id;
        $pay_arr['modified_by'] = auth()->user()->id;

        $insId      =   CustomerCredits::create($pay_arr)->id;
        $msg        =   'Payment added successfully!';
       Session::flash('message', ['text'=>$msg,'type'=>'success']);
        return redirect(route('customer.credits'));
    }

     function validate_credits(Request $request){

        $post               =  (object)$request->post(); 
         $existName         =  $error = false;

        
       
                $rules          =   [
                'credit_limit'                 =>  ['required','max:100','min:1'],
                'credit_days'                 =>   ['required','max:100','min:1'],
                'per_purchase'                 =>  ['required'],
                // 'org_image'  =>['mimes:jpeg,jpg,png,gif','required','max:10000']
                ];

                $validator              =   Validator::make($post->manage ,$rules);
                if ($validator->fails()) {
                foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; }
                $error['tab'] = 'tab-1';
                } 

                if ($validator->fails()) {
                foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; }
                if(! isset($error['tab'])){ $error['tab'] = 'tab-2'; }
                } 
                
              
                if($error) { return $error; }else{ return 'success'; } return 'success'; 
  

    }



      function credits_save(Request $request){

        $post               =  (object)$request->post(); 
        // $images                 =   $request->file('org_image');

        // dd($post);
        $credit_table = (new CustomerCredits)->getTable();
        $credit_log_table = (new CustomerCreditLogs)->getTable();
        if(! isset($post->manage['allow_purchase']) ) { $post->manage['allow_purchase'] = 0; }
        $user_id= $post->manage['user_id'];
        $add_new= $post->manage['add_new'];

        if($add_new ==1){

            if($user_id)
            {
                $c_log_arr = [];
                $c_log_arr['user_id'] =$user_id;
                $c_log_arr['credit_limit'] = $post->manage['credit_limit'];
                $c_log_arr['credit_days'] = $post->manage['credit_days'];
                $c_log_arr['allow_purchase'] = $post->manage['allow_purchase'];
                $c_log_arr['per_purchase'] = $post->manage['per_purchase'];
                $c_log_arr['created_by'] = auth()->user()->id;
                $c_log_arr['modified_by'] = auth()->user()->id;

                CustomerCreditLogs::where('user_id',$user_id)->update(array('is_deleted'=>1));

                $insId      =   CustomerCreditLogs::create($c_log_arr)->id;

                $pay_arr = [];
                $pay_arr['user_id'] = $user_id;
                $pay_arr['ref_id'] = 0;
                $pay_arr['log_id'] = $insId;
                $pay_arr['credit_limit'] = $post->manage['credit_limit'];
                $pay_arr['credit_days'] = $post->manage['credit_days'];
                $pay_arr['credit'] = 0;
                $pay_arr['per_purchase'] = $post->manage['per_purchase'];
                $pay_arr['created_by'] = auth()->user()->id;
                $pay_arr['modified_by'] = auth()->user()->id;

                $insId      =   CustomerCredits::create($pay_arr)->id;
            }

            Session::flash('message', ['text'=>'User credits saved successfully.','type'=>'success']);
            return redirect(route('customer.credits'));

        }else {

              if($user_id)
        {

            $user_data = CustomerCredits::
            selectRaw("$credit_table.user_id,log.credit_limit,log.credit_days,log.per_purchase,log.allow_purchase,SUM($credit_table.credit)-SUM($credit_table.debit) as balance,SUM($credit_table.debit)-SUM($credit_table.credit) as outstanding,MAX($credit_table.log_id) as log_id")
            ->join("$credit_log_table as log",'log.id','=',"$credit_table.log_id")->where("log.is_deleted",0)->where("$credit_table.user_id",$user_id)->orderBy("$credit_table.id","DESC")->groupBy("$credit_table.user_id")
            ->first();

            $credid_limit = $user_data->credit_limit;
            $credit_days = $user_data->credit_days;
            $per_purchase = $user_data->per_purchase;
            $allow_purchase = $user_data->allow_purchase;

            $post_credid_limit = $post->manage['credit_limit'];
            $post_credit_days = $post->manage['credit_days'];
            $post_per_purchase = $post->manage['per_purchase'];
            $post_allow_purchase = $post->manage['allow_purchase'];

            if( ($credid_limit != $post_credid_limit) || ($post_credit_days != $credit_days) || ($post_per_purchase != $per_purchase) ||
             ($post_allow_purchase != $allow_purchase)  )
            {
                $c_log_arr = [];
                $c_log_arr['user_id'] =$user_id;
                $c_log_arr['credit_limit'] = $post->manage['credit_limit'];
                $c_log_arr['credit_days'] = $post->manage['credit_days'];
                $c_log_arr['allow_purchase'] = $post->manage['allow_purchase'];
                $c_log_arr['per_purchase'] = $post->manage['per_purchase'];
                $c_log_arr['created_by'] = auth()->user()->id;
                $c_log_arr['modified_by'] = auth()->user()->id;

                CustomerCreditLogs::where('user_id',$user_id)->update(array('is_deleted'=>1));

                $insId      =   CustomerCreditLogs::create($c_log_arr)->id;

                $c_update_arr = [];
                $c_update_arr['credit_limit'] = $post->manage['credit_limit'];
                $c_update_arr['credit_days'] = $post->manage['credit_days'];
                $c_update_arr['allow_purchase'] = $post->manage['allow_purchase'];
                $c_update_arr['per_purchase'] = $post->manage['per_purchase'];
                $c_update_arr['log_id'] = $insId;
                $c_update_arr['modified_by'] = auth()->user()->id;

                $user_credit_row =CustomerCredits::where('user_id',$user_id)->orderBy('id', 'desc')->first();
                $user_credit_row->update($c_update_arr);

            }



            return 'success'; 

        }
        else {
            return 'error'; 
        }

        }

        
      
    }




}
