<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;
use App\Models\RewardType;
class SettingOther extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'settings_others';

    protected $fillable = ['org_id','type','name','value', 'refund_deduction', 'return_period','point_equivalent','bid_charge','mjs_fee','pg_fee','invite_save','invite_discount','valid_days','is_active','is_deleted','created_by','updated_by','created_at','updated_at'];

        static function getOtherSettings(){ 
            
           $settings_list = SettingOther::where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->where('is_active',1)->orderBy('id', 'DESC')->first();     
         

            if($settings_list){ 
            $data               =   [];
        
            $data['id']        =   $settings_list->id;
            $data['refund_deduction']       =   $settings_list->refund_deduction;
            $data['return_period']       =   $settings_list->return_period;
            $data['point_equivalent']       =   $settings_list->point_equivalent;
            $data['is_active']       =   $settings_list->is_active;
            $data['is_deleted']       =   $settings_list->is_deleted;
            $data['created_at']       =   $settings_list->created_at; 
            $data['name']       =   $settings_list->name;  
            $data['tax']       =   $settings_list->value; 
            $data['invite_save']       =   $settings_list->invite_save; 
            $data['invite_discount']       =   $settings_list->invite_discount; 
             $data['valid_days']       =   $settings_list->valid_days; 
           
            return $data;
            }else{ return false; }

        }

         
    
       
      
}
