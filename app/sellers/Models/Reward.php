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

    protected $fillable = ['org_id', 'rwd_type', 'ord_amount','ord_type','ord_min_amount','point_val','is_active','is_deleted','created_by','updated_by','created_at','updated_at'];

        static function getRewards(){ 
            
           $rwd_list = Reward::where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->orderBy('id', 'DESC')->get();     
         

            if($rwd_list){ 
            $data               =   [];
            foreach($rwd_list    as  $row){
            $data['id']        =   $row->id;
            $data['rwd_type']       =   $row->rwd_type;
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

         
    
       
      
}
