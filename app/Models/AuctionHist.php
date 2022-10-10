<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;
use App\Models\customer\CustomerInfo;
use App\Models\SalesOrder;
class AuctionHist extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'auction_hist';
    protected $fillable = ['org_id','auction_id', 'user_id', 'sale_id','bid_price','is_active','is_deleted','created_by','updated_by','created_at','updated_at'];

     
  static function getLog($acn){ 
        $log_list = AuctionHist::where('auction_id',$acn)->orderBy('id', 'DESC')->get();
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
           if(SalesOrder::where("id",$row->sale_id)->first()){ 
               $data[$row->id]['sale_code']         =  SalesOrder::where("id",$row->sale_id)->first()->order_id;
            $data[$row->id]['sale_date']         =  SalesOrder::where("id",$row->sale_id)->first()->created_at;
           }else {
               $data[$row->id]['sale_code']         =  "";
            $data[$row->id]['sale_date']         =  "";
           } 
            $data[$row->id]['bid_price']         =   $row->bid_price;
            $data[$row->id]['is_active']       =   $row->is_active; 
            $data[$row->id]['is_deleted']       =   $row->is_deleted;
            $data[$row->id]['created_at']       =   $row->created_at; 
            }

            return $data;
            }else{ return false; }

        }
      
}
