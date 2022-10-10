<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class UserVisit extends Model{
    use HasFactory;
     protected $table = 'usr_visits';
    protected $fillable = ['org_id','device_id','is_login','os','url','visited_on','created_at','updated_at'];
    static function getCount($time_st){ 
        $ret_cnt = 0;
        $cnt             =   UserVisit::where('visited_on',$time_st)->get(); 
        if(count($cnt) >0) {
         $ret_cnt = count($cnt);   
        }
        return ($ret_cnt);
    } 
    
}
