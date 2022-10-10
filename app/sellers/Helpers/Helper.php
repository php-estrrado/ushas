<?php
use App\Models\CmsContent;
use App\Models\UserRole;
use App\Models\SellerInfo;
use App\Models\Setting;
if (!function_exists('geSiteName')) {

    function geSiteName() {
        $data = DB::table('settings')->where('type', 'site_name')->where('status', 1)->first();
        if ($data) {
            return $data->value;
        } else {
            return 'BS';
        }
    }

}if (!function_exists('geAdminName')) {

    function geAdminName() {
        $data = DB::table('settings')->where('type', 'admin_name')->where('status', 1)->first();
        if ($data) {
            return $data->value;
        } else {
            return 'Admin';
        }
    }

}if (!function_exists('geAdminEmail')) {

    function geAdminEmail() {
        $data = DB::table('settings')->where('type', 'admin_email')->where('status', 1)->first();
        if ($data) {
            return $data->value;
        } else {
            return 'admin@gameday.com';
        }
    }

}if (!function_exists('getCurrency')){
    function getCurrency(){
        $data['name'] = 'RM'; $data['symbol'] = 'RM'; 
        $query = Setting::where('type','currency_code')->where('is_active',1)->where('is_deleted',0)->first();
        if($query) $data['name'] = $query->value;
        $query = Setting::where('type','currency_symbol')->where('is_active',1)->where('is_deleted',0)->first();
        if($query) $data['symbol'] = $query->value;
         return (object)$data;
    }
}if (!function_exists('avatar')){
    function avatar($id){
        $avatar                 =   DB::table('users')->where('user_id',$id)->first()->avatar;
        if($avatar == NULL      ||  $avatar == ''){ $avatar = '/app/public/no-avatar.png'; }
        return $avatar;
    }
}if (!function_exists('notifyCount')){
    function notifyCount($id){ return DB::table('users')->where('id',$id)->first()->notify; }
}if (!function_exists('addNotification')){
    function addNotification($type,$refId=''){
        $admins                 =   DB::table('users')->where('active',1)->where('status',1)->whereIn('user_role',[1,2])->get(); $adminNotify = false;
        if($type                ==  'register'){
            $user               =   DB::table('users as U')->join('user_roles as R','U.user_role','=','R.id')->where('U.id',$refId)->first();
            if($user && $user->user_role  ==  3){ 
                $userData       =   DB::table('user_business_details')->select('shop_name','logo as icon_img')->where('user_id',$refId)->first();
                if($userData){      DB::table('users')->where('id',$refId)->update(['fname'=>$userData->shop_name]); }
            }else if($user && $user->user_role  ==  4){ 
                $userData       =   DB::table('user_details')->select('avthar as icon_img')->where('user_id',$refId)->first();
            }
            if($userData){
                if($userData->icon_img == NULL || $userData->icon_img == ''){ $icon = url("/storage/app/public/no-avatar.png"); }
                else{ $icon     =   "/storage".$userData->icon_img; }
                $msg            =   '<b>'.$user->fname.'</b> registered as '.$user->title.' at '.date('d M Y, g:i a', strtotime($user->created_at));
                $data           =   ['user_id'=>$refId,'notify_type'=>$type,'title'=>'New Registration','description'=>$msg,'icon'=>$icon,'ref_id'=>$refId,'created_at'=>date('Y-m-d H:i:s')];
                if(DB::table('notifications')->insert($data)){ $adminNotify = true; }
            }
        }else if($type          ==  'payment'){
            $pay                =   DB::table('user_payments as P')->join('users as U','P.user_id','=','U.id')->where('P.id',$refId)->first();
            if($pay){
                $icon           =   "/public/assets/img/money2.png"; 
                $msg            =   '<b>'.$pay->fname.'</b> done payment for '.$pay->pay_for.' at '.date('d M Y, g:i a', strtotime($pay->paid_on));
                $data           =   ['user_id'=>$pay->user_id,'notify_type'=>$type,'title'=>'Payment','description'=>$msg,'icon'=>$icon,'ref_id'=>$refId,'created_at'=>date('Y-m-d H:i:s')];
                if(DB::table('notifications')->insert($data)){ $adminNotify = true; }
            }
        }
        if($adminNotify && $admins){ foreach($admins as $row){ DB::table('users')->where('id',$row->id)->update(['notify'=>($row->notify+1)]); } }
    }
}if (!function_exists('getNotifications')){
    function getNotifications(){
        return DB::table('notifications as N')->select('N.*','U.user_role')->join('users as U','N.user_id','=','U.id')->where('N.status',1)->orderBy('N.id','desc')->limit(25)->get();
    }
}if (!function_exists('getDropdownValues')){
    function getDropdownValues($table,$field,$value,$label){
        return DB::table($table)->where($field,$value)->where('is_deleted',0)->orderBy($label,'asc')->get();
    }
}if (!function_exists('getDropdownData')){
    function getDropdownData($data,$value,$label,$label2='') { $res =   [];
        if($data){ foreach($data as $row){ if($label2 != ''){ $res[$row->$value] = $row->$label.' '.$row->$label2; }else{ $res[$row->$value] = $row->$label; } } } return $res; 
    }
}if (!function_exists('getDropdownCmsData')){
    function getDropdownCmsData($data,$value,$label,$label2='') { $res =   [];
        $query          =   Setting::where('type','default_lang')->where('is_active',1)->first();
        if($query){        $langId = $query->value;}else{ $langId = 1; }
        if($data){ foreach($data as $row){ if($label2 != ''){ $res[$row->$value] = getContent($row->$label,$langId).' '.getContent($row->$label2,$langId); }else{ $res[$row->$value] = getContent($row->$label,$langId); } } } return $res; 
    }
}if (!function_exists('defaultLangId')){
    function defaultLangId() { 
        $query          =   Setting::where('type','default_lang')->where('is_active',1)->first();
        if($query){ return $query->value;}else{ return 1; }
    }
}if (!function_exists('roleData')) {
    function roleData() { return UserRole::where('id', auth()->user()->role_id)->first(); }
}
if (!function_exists('sellerData')) {
    function sellerData() { return SellerInfo::where('seller_id', auth()->user()->id)->first(); }
}
if (!function_exists('getContent')) {
    function getContent($cntId=0, $langId=1){ 
        $content            =   CmsContent::where('cnt_id', $cntId)->where('lang_id', $langId)->where('is_deleted', 0)->first(); 
        if($content){            return $content->content; }
        $content            =   CmsContent::where('cnt_id', $cntId)->where('lang_id', defaultLangId())->where('is_deleted', 0)->first(); 
        if($content){            return $content->content; } return '';
    }
}