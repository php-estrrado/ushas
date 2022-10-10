<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;
class RewardType extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'rwd_type';

    protected $fillable = ['org_id', 'rwd_type_title', 'rwd_type_desc','points','is_active','is_deleted','created_by','updated_by','created_at','updated_at'];

        static function getRewardType(){ 
            
           $types_list = RewardType::where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->orderBy('id', 'DESC')->get();     
         

            if($types_list){ 
            $data               =   [];
            foreach($types_list    as  $row){
            $data[$row->id]['id']        =   $row->id;
            $data[$row->id]['rwd_type_title']       =   $row->rwd_type_title;
            $data[$row->id]['rwd_type_desc']       =   $row->rwd_type_desc;
            $data[$row->id]['points']       =   $row->points; 
            $data[$row->id]['is_active']       =   $row->is_active;
            $data[$row->id]['is_deleted']       =   $row->is_deleted;
            $data[$row->id]['created_at']       =   $row->created_at; 
            }

            return $data;
            }else{ return false; }

        }

        static function getRewardTypeData($id){ 
            
           $types_list = RewardType::where("id",$id)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();     
         

            if($types_list){ 
            $data               =   [];
            foreach($types_list    as  $row){
            $data[$row->id]['id']        =   $row->id;
            $data[$row->id]['rwd_type_title']       =   $row->rwd_type_title;
            $data[$row->id]['rwd_type_desc']       =   $row->rwd_type_desc;
            $data[$row->id]['points']       =   $row->points; 
            $data[$row->id]['is_active']       =   $row->is_active;
            $data[$row->id]['is_deleted']       =   $row->is_deleted;
            $data[$row->id]['created_at']       =   $row->created_at; 
            }

            return $data;
            }else{ return false; }

        }
    
       
      
}
