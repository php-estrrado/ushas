<?php

namespace App\Http\Controllers;
use Config;
use Artisan;
use Validator;
use DB;
use Hash;
use Illuminate\Http\Request;
use DateTime;
use App\Models\CreditPayments;
use App\Models\customer\CustomerCredits;
use App\Models\customer\CustomerCreditLogs;
use App\Models\customer\CustomerInfo;
use App\Models\customer\CustomerTelecom;
use App\Models\customer\CustomerMaster;

class CreditCronController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      //  $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function validateCredit()
    {
          $credit_table = (new CustomerCredits)->getTable();
    $credit_log_table = (new CustomerCreditLogs)->getTable();
      $data_percentage = CustomerCredits::
   selectRaw("$credit_table.user_id,log.credit_limit, ((SUM($credit_table.debit)-SUM($credit_table.credit))/log.credit_limit)*100 as percentage,MAX($credit_table.log_id) as log_id,MAX($credit_table.id) as ref_id")
   ->join("$credit_log_table as log",'log.id','=',"$credit_table.log_id")->havingRaw("percentage >=40")->where("log.is_deleted",0)->orderBy("$credit_table.id","DESC")->groupBy("$credit_table.user_id")
   ->get();

   if(isset($data_percentage) && count($data_percentage)>0)
   {
     
     foreach ($data_percentage as $pk => $percent_users) {
      
        $from   = 1; 
        $utype  = 2;
        $to     = $percent_users->user_id;
        $ntype  = 'user_credit';
        $title  = 'User Credit Limit Warning';
        $desc   = 'Used 80% of your credit limit. Please purchase credits immediately. ';
        $refId  = $percent_users->ref_id;
        $reflink = 'customer/credits';
        $notify  = 'customer';
        addNotification($from,$utype,$to,$ntype,$title,$desc,$refId,$reflink,$notify);
     }
   }


    }

  public function validateCreditDays()
    {
          $credit_table = (new CustomerCredits)->getTable();
    $credit_log_table = (new CustomerCreditLogs)->getTable();
    $pending_transactions = CustomerCredits::where('ref_id','=',NULL)->where('debit','>',0)->orderBy("id","DESC")->get();

   if(isset($pending_transactions) && count($pending_transactions)>0)
   {
     
     foreach ($pending_transactions as $pk => $trans_users) {
        
        $cr_date = $trans_users->created_at;
        $credit_days = $trans_users->credit_days;

        $datetime1 = new DateTime(now());
        $datetime2 = new DateTime($cr_date);
        $interval = $datetime1->diff($datetime2);
        $check_days = $interval->format('%a');

        if($check_days <=5){
            $from   = 1; 
            $utype  = 2;
            $to     = $trans_users->user_id;
            $ntype  = 'user_credit';
            $title  = 'User Credit Days Warning';
            $desc   = 'Your have only '.$check_days.'days to complete pending credit payments.';
            $refId  = $trans_users->id;
            $reflink = 'customer/credits';
            $notify  = 'customer';
            addNotification($from,$utype,$to,$ntype,$title,$desc,$refId,$reflink,$notify);
        }


     }
   }

 }
    

}
