<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable{
    use Notifiable;
    protected $guard = 'admin';
    protected $fillable = ['fname','lname', 'email', 'phone', 'role_id','org_id','is_active', 'password','created_by',];
    protected $hidden = ['password', 'remember_token',];
    public function role(){ return $this->belongsTo(UserRole ::class, 'role_id'); }
    
    
    static function ValidateUnique($field,$post,$id) {
        $query                  =   Admin::where($field,$post->email)->first();
        if($query){
            if($query->id       !=  $id){ 
                if($query->status   ==  0){ return 'This '. $field .' account has been disabled'; }
                return 'This '.$field.' already has been taken'; 
            }
        }else{ return false; }
    }
    
    static function ValidatePhone($field,$post,$id){  
        $query                  =   Admin::where($field,$post->$field)->where('isd_code',$post->isd_code)->first();
        if($query){ 
            if($query->id       !=  $id){ 
                if($query->status   ==  0){ return 'This account has been disabled'; }
                return 'This phone already has been taken'; 
            }
        }else{ return false; }
    }
}
