<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserRole extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'usr_role_lk';

    protected $fillable = ['org_id', 'usr_role_name', 'usr_role_desc','is_active','created_by','is_deleted'];
    
     static function getUserRoles(){ 
         $permanent = array(1,2,3,4,5,7);
            
           $userroles = UserRole::where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->whereNotIn('id', $permanent)->orderBy('id', 'DESC')->get();     
         

            if($userroles){ 
            $data               =   [];
            foreach($userroles    as  $row){
            $data[$row->id]['id']        =   $row->id;
            $data[$row->id]['usr_role_name']        =   $row->usr_role_name;
            $data[$row->id]['usr_role_desc']        =   $row->usr_role_desc;
            $data[$row->id]['assigned_users']        = UserRole::findUserCount($row->id);
           
            $data[$row->id]['is_active']       =   $row->is_active;
            $data[$row->id]['is_deleted']       =   $row->is_deleted;
            $data[$row->id]['created_at']       =   $row->created_at; 
            }

            return $data;
            }else{ return false; }

        }

         static function findUserCount($rid)
        { 

        $admins = Admin::where('role_id',$rid)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->orderBy('id', 'DESC')->get();   
        if(count($admins)>0)
        {
            return count($admins);
        }else 
        {
            return 0;
        }
     
        }
    
}
