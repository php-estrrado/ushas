<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;
use App\Models\customer\CustomerInfo;
use App\Models\SalesOrder;
class AuctionRefundHist extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'auction_refund_hist';
    protected $fillable = ['org_id','auction_id', 'user_id', 'sale_id','paid_amount','refund_amount','refund_percentage','status','created_by','updated_by','created_at','updated_at'];

     
  static function getLog($acn,$status){ 
    if($status !="") {
        $log_list = AuctionRefundHist::where('auction_id',$acn)->where('status',$status)->orderBy('id', 'DESC')->get();
    }else {
       $log_list = AuctionRefundHist::where('auction_id',$acn)->orderBy('id', 'DESC')->get(); 
    }
        
            if($log_list){ 
            $data               =   [];
            foreach($log_list    as  $row){
            $data[$row->id]['id']        =   $row->id;
            $data[$row->id]['auction_id']         =   $row->auction_id;
            $data[$row->id]['user_id']         =   $row->user_id;
            $user_name = CustomerInfo::where('user_id', $row->user_id)->first();
            if($user_name){
                $user_name = $user_name->first_name." ". $user_name->last_name;
            }else{
              $user_name = "";  
            }
            $data[$row->id]['user_name']         =  $user_name;  
            $data[$row->id]['sale_id']         =   $row->sale_id;
           if(SalesOrder::where("id",$row->sale_id)->first()){ $data[$row->id]['sale_code']         =  SalesOrder::where("id",$row->sale_id)->first()->order_id;}
           if(SalesOrder::where("id",$row->sale_id)->first()){ $data[$row->id]['sale_date']         =  SalesOrder::where("id",$row->sale_id)->first()->created_at;}
            $data[$row->id]['paid_amount']         =   $row->paid_amount;
            $data[$row->id]['refund_amount']         =   $row->refund_amount;
            $data[$row->id]['refund_percentage']         =   $row->refund_percentage;
            $data[$row->id]['status']       =   $row->status; 
            $data[$row->id]['is_deleted']       =   $row->is_deleted;
            $data[$row->id]['updated_by']       =   $row->updated_by; 
            $data[$row->id]['created_at']       =   $row->created_at; 
            $data[$row->id]['updated_at']       =   $row->updated_at; 
            }

            return $data;
            }else{ return false; }

        }
      
}
