<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;
use App\Models\customer\CustomerInfo;
class CouponHist extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'coupon_usage_hist';

     
  static function getLog($cpn){ 
        $log_list = CouponHist::where('coupon_id',$cpn)->orderBy('id', 'DESC')->get();
            if($log_list){ 
            $data               =   [];
            foreach($log_list    as  $row){
            $data[$row->id]['id']        =   $row->id;
            $data[$row->id]['coupon_id']         =   $row->coupon_id;
            $data[$row->id]['user_id']         =   $row->user_id;
            $user_name = CustomerInfo::where('user_id', $row->user_id)->first();
            if($user_name){
                $user_name = $user_name->first_name." ". $user_name->last_name;
            }else{
              $user_name = "";  
            }
            $data[$row->id]['user_name']         =  $user_name;  
            $data[$row->id]['order_id']         =   $row->order_id;
            $data[$row->id]['order_date']         =   $row->created_at;
            $data[$row->id]['ofr_value']         =   $row->ofr_value;
            $data[$row->id]['order_value']         =   '1000';
            $data[$row->id]['ofr_type']         =   $row->ofr_type;
            $data[$row->id]['is_active']       =   $row->is_active; 
            $data[$row->id]['is_deleted']       =   $row->is_deleted;
            $data[$row->id]['created_at']       =   $row->created_at; 
            }

            return $data;
            }else{ return false; }

        }
      
}
