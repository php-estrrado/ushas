<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;
use App\Models\RewardType;
class Reward extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'reward';

    protected $fillable = ['org_id', 'reward','rwd_type_referrer','referrer_cashback_purchase','referrer_cashback_register','referrer_coupon_register','referrer_coupon_purchase','rwd_type_referral','referral_cashback_purchase','referral_cashback_register','referral_coupon_register','referral_coupon_purchase','ord_amount','ord_type','ord_min_amount','point_val','is_active','is_deleted','created_by','updated_by','created_at','updated_at'];

        static function getRewards(){ 
            
           $rwd_list = Reward::where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->orderBy('id', 'DESC')->get();     
         

            if($rwd_list){ 
            $data               =   [];
            foreach($rwd_list    as  $row){
            $data['id']        =   $row->id;
            $data['reward']        =   $row->reward;
            $data['rwd_type_referrer']       =   $row->rwd_type_referrer;
            $data['referrer_cashback_purchase'] =   $row->referrer_cashback_purchase;
            $data['referrer_cashback_register'] =   $row->referrer_cashback_register;
            $data['referrer_coupon_register'] =   $row->referrer_coupon_register;
            $data['referrer_coupon_purchase'] =   $row->referrer_coupon_purchase;

            $data['referral_cashback_purchase'] =   $row->referral_cashback_purchase;
            $data['referral_cashback_register'] =   $row->referral_cashback_register;
            $data['referral_coupon_register'] =   $row->referral_coupon_register;
            $data['referral_coupon_purchase'] =   $row->referral_coupon_purchase;

            $data['rwd_type_referral']       =   $row->rwd_type_referral;
            $data['type_data']       =   RewardType::getRewardTypeData($row->rwd_type);
            $data['all_types']       =   RewardType::getRewardType();
            $data['ord_amount']       =   $row->ord_amount;
            $data['ord_type']       =   $row->ord_type; 
            $data['ord_min_amount']       =   $row->ord_min_amount; 
            $data['point_val']       =   $row->point_val; 
            $data['is_active']       =   $row->is_active;
            $data['is_deleted']       =   $row->is_deleted;
            $data['created_at']       =   $row->created_at; 
            }

            return $data;
            }else{ return false; }

        }

    public function rewardType_purchase(){ 
        $reward_type = RewardType::where('is_active',1)->where('is_deleted',0)->where('id',2)->first();
        return $reward_type;
    }
    
    public function rewardType_register(){ 
        $reward_type = RewardType::where('is_active',1)->where('is_deleted',0)->where('id',1)->first();
        return $reward_type;
    }
    
       
      
}
