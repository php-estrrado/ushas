<?php

namespace App\Http\Controllers\Api\Customer;

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
use App\Rules\Name;
use Validator;

class CreditsController extends Controller
{

   

    public function listing(Request $request)
    {
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];

            $formData   =   $request->all(); 
        

            $credit_table = (new CustomerCredits)->getTable();
            $credit_log_table = (new CustomerCreditLogs)->getTable();
            $user_credits = CustomerCredits::
            selectRaw("$credit_table.user_id,log.credit_limit,log.credit_days,SUM($credit_table.credit)-SUM($credit_table.debit) as balance,SUM($credit_table.debit)-SUM($credit_table.credit) as outstanding,MAX($credit_table.log_id) as log_id")
            ->join("$credit_log_table as log",'log.id','=',"$credit_table.log_id")->where("log.is_deleted",0)->where("$credit_table.user_id",$user_id)->orderBy("$credit_table.id","DESC")->groupBy("$credit_table.user_id")
            ->first();
            if(!$user_credits)
            {
             return ['httpcode'=>'400','status'=>'error','message'=>'Not found','data'=>['credits'=>[]]];   
            }
            $available_cr = 0;
            $cr_bal = $user_credits->credit_limit - $user_credits->outstanding; 
            if($user_credits->credit_limit > $cr_bal) { $available_cr = $cr_bal; }
            else
            { 
                // $available_cr = $user_credits->credit_limit;
                $available_cr = $cr_bal;
             }


            $credits_arr = [];
            $credits_arr['available_credits'] = $available_cr;
            if($user_credits->outstanding > 0){ $credits_arr['outstanding'] = $user_credits->outstanding; }else { $credits_arr['outstanding'] = 0; }  
            $credits_arr['credit_limit'] = $user_credits->credit_limit;
            $credits_arr['credit_balance'] = $user_credits->balance;

            // Transactions starts 
            $limits = 10;
            $offset = 0;

            $limits = $request->post('limit');
            $offset = $request->post('offset');

            $trans_arr = $trans_ret_arr = [];

            if($request->post('limit') && $request->post('offset') )
            {
                $all_transactions = CustomerCredits::where('user_id',$user_id)->orderBy("id","DESC")->skip($offset)->take($limits)->get();
            }else{
                $all_transactions = CustomerCredits::where('user_id',$user_id)->orderBy("id","DESC")->get();
            }

                if($all_transactions){
                    foreach($all_transactions as $k=>$v){
                        $trans_value = 0;
                        if($v->credit >0){
                            $trans_type = "Credit";
                            $trans_value = $v->credit;

                        }else{
                            $trans_type = "Debit";
                            $trans_value = $v->debit;
                        }
                        if($v->ref_id !=0){
                            $trans_arr['trans_id'] = $v->sales->order_id;
                        }else{
                            $trans_log_id = date('y').date('m').str_pad($v->id + 1, 6, "0", STR_PAD_LEFT);   
                             $trans_arr['trans_id'] = $trans_log_id; 
                 
                        }

                        $trans_arr['trans_date'] = date('d-m-Y',strtotime($v->created_at)); 
                        $trans_arr['trans_type'] = $trans_type; 
                        $trans_arr['trans_value'] = $trans_value;  
                        
                        $trans_ret_arr[] = $trans_arr;
                    }
                }


                $credits_arr['transactions'] = $trans_ret_arr;

                // Transactions ends

                // Pending Payments starts 

                $pending_trans_arr = $pending_trans_ret_arr = [];
                $credit_table = (new CustomerCredits)->getTable();
//                 $pending_transactions = CustomerCredits::where('user_id',$user_id)->where('ref_id','!=',0)->where('debit','>',0)->where("payment_status","pending")->whereNotIn('ref_id',function($query) use($credit_table,$user_id) {
//   $query->select('ref_id')->from("$credit_table")->where('credit','>',0)->where('user_id',$user_id);})->orderBy("id","DESC")->get();

                $pending_transactions = CustomerCredits::where('user_id',$user_id)->where('ref_id','!=',0)->where('debit','>',0)->where("payment_status","pending")->orderBy("id","DESC")->get();
                if($pending_transactions){
                    foreach($pending_transactions as $k=>$v){
                        $trans_value = 0;
                        if($v->credit >0){
                            $trans_value = $v->credit;

                        }else{
                            $trans_value = $v->debit;
                        }
                        $pending_trans_arr['id'] = $v->id;
                        if($v->ref_id !=0){
                            $pending_trans_arr['trans_id'] = $v->sales->order_id;
                        }else{
                            $trans_log_id = date('y').date('m').str_pad($v->id + 1, 6, "0", STR_PAD_LEFT);   
                             $pending_trans_arr['trans_id'] = $trans_log_id; 
                 
                        }

                        $pending_trans_arr['trans_date'] = date('d-m-Y',strtotime($v->created_at)); 
                        $pending_trans_arr['trans_value'] = $trans_value;  
                        $pending_trans_ret_arr[] = $pending_trans_arr;
                    }
                }


                $credits_arr['pending_payments'] = $pending_trans_ret_arr;

                    return array('httpcode'=>'200','status'=>'success','message'=>'Credits info','data'=>['credits'=>$credits_arr]);
                
        }else{ return invalidToken(); }
    }
   
 
    public function payment(Request $request)
    {

         if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
            $formData   =   $request->all(); 
            $rules      =   array();
            $rules['credits_id']    = 'required';
            $rules['transaction_id']    = 'required';
            $rules['status']    = 'required';
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 

            $credits_id = $request->post('credits_id');
            $transaction_id = $request->post('access_token'); // need to handle once Payment Gateway confirmed
            $status = $request->post('status');

            if($status =="paid" || $status =="Paid")
            {
                $credit_table = (new CustomerCredits)->getTable();
                $exp_arr = explode(",", $credits_id); $insId = 0; 
                if(isset($exp_arr))
                {
                    foreach($exp_arr as $ek=>$ev)
                    {

                      $tr_info =  CustomerCredits::where('id',$ev)->whereNotIn('ref_id',function($query) use($credit_table,$user_id) {
                        $query->select('ref_id')->from("$credit_table")->where('credit','>',0)->where('user_id',$user_id);})->first();
                      $tr_latest_info =  CustomerCredits::where('user_id',$user_id)->orderBy("id","DESC")->first();

                      if($tr_info && $tr_latest_info){
                        $pay_arr = [];
                        $pay_arr['user_id'] = $user_id;
                        $pay_arr['ref_id'] = $tr_info->ref_id;
                        $pay_arr['log_id'] = $tr_info->log_id;
                        $pay_arr['credit_limit'] = $tr_latest_info->credit_limit;
                        $pay_arr['credit_days'] = $tr_latest_info->credit_days;
                        $pay_arr['credit'] = $tr_info->debit;
                        $pay_arr['per_purchase'] = $tr_latest_info->per_purchase;
                        $pay_arr['created_by'] = $user_id;
                        $pay_arr['modified_by'] = $user_id;
                        $pay_arr['payment_status'] = "paid";
                        $insId      =   CustomerCredits::create($pay_arr)->id;  
                        CustomerCredits::where('id',$ev)->update(["payment_status"=>"paid"]); 
                      }


                    }
                }
                if($insId>0)
                {
                    return array('httpcode'=>'200','status'=>'success','message'=>'Payment completed','data'=>['message' =>'Payment completed!']);
                }else{
                    return array('httpcode'=>'404','status'=>'error','message'=>'Payment failed');
                }
                
            }else{
                    return array('httpcode'=>'404','status'=>'error','message'=>'Payment failed');
                }



                    
                }
        }else{ return invalidToken(); }



    return array('httpcode'=>'404','status'=>'error','message'=>'Payment failed');

    }






}
