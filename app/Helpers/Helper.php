<?php
use App\Models\CmsContent;
use App\Models\UserRole;
use App\Models\Setting;
use App\Models\Modules;
use App\Models\Admin;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use App\Models\AppVersion;
use App\Models\Subcategory;
use App\Models\SubcategoryList;
use App\Models\MetalRates;
use App\Models\Currency;

use App\Models\AdminNotification;

use App\Models\Product;
use Twilio\Rest\Client;



if (!function_exists('geSiteName')) {

    function geSiteName() {
        $data = DB::table('settings')->where('type', 'site_name')->where('is_deleted', 0)->first();
        if ($data) {
            return $data->value;
        } else {
            return 'BigBasket';
        }
    }

}if (!function_exists('geAdminName')) {

    function geAdminName() {
        $data = DB::table('settings')->where('type', 'admin_name')->where('is_deleted', 0)->first();
        if ($data) {
            return $data->value;
        } else {
            return 'Admin';
        }
    }

}if (!function_exists('geAdminEmail')) {

    function geAdminEmail() {
        $data = DB::table('settings')->where('type', 'admin_email')->where('is_deleted', 0)->first();
        if ($data) {
            return $data->value;
        } else {
            return 'admin@dev-mjs.estrradoweb.com';
        }
    }

}if (!function_exists('getCurrency')){
    function getCurrency(){
         
        $query = Currency::where('is_default',1)->where('is_active',1)->where('is_deleted',0)->first();
        if($query)
        {
            $data['name'] = $query->currency_code; $data['symbol'] = $query->currency_code;
        }
        else
        {
            $data['name'] = 'SAR'; $data['symbol'] = 'SAR';
        }
        // $query = Setting::where('type','currency_code')->where('is_active',1)->where('is_deleted',0)->first();
        // if($query) $data['name'] = $query->value;
        // $query = Setting::where('type','currency_symbol')->where('is_active',1)->where('is_deleted',0)->first();
        // if($query) $data['symbol'] = $query->value;
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
     function addNotification($from,$utype,$to,$ntype,$title,$desc,$refId,$reflink,$notify){
      

	   if($notify                ==  'admin'){
             DB::table('admin_notifications')->insert(['notify_from'=>$from,'user_type'=>$utype,'notify_to'=>$to,'notify_type'=>$ntype,'title'=>$title,'description'=>$desc,'ref_id'=>$refId,'ref_link'=>$reflink,'created_at'=>date('Y-m-d H:i:s')]);
        }
        else if($notify                ==  'seller'){
            
            $seller_token = DB::table('usr_seller_logins')->where('seller_id',$to)->where('is_login',1)->get();
            $deviceTokens = [];
            foreach($seller_token as $sk=>$tokens)
            {
                $deviceTokens[] = $tokens->device_token;
            }
            $pushData['title'] = $title;
            $pushData['message'] = $desc;
            $pushData['data'] = array('ref_id'=>$refId,'time'=>date('H:i:s'));
            sendPush($deviceTokens,$pushData);
             DB::table('seller_notifications')->insert(['notify_from'=>$from,'user_type'=>$utype,'notify_to'=>$to,'notify_type'=>$ntype,'title'=>$title,'description'=>$desc,'ref_id'=>$refId,'ref_link'=>$reflink,'created_at'=>date('Y-m-d H:i:s')]);
        }
        else if($notify                ==  'customer'){
            
              $users_token = DB::table('usr_logins')->where('user_id',$to)->where('is_login',1)->get();
            $deviceTokens = [];
            foreach($users_token as $sk=>$tokens)
            {
                $deviceTokens[] = $tokens->device_token;
            }
            $pushData['title'] = $title;
            $pushData['message'] = $desc;
            $pushData['data'] = array('ref_id'=>$refId,'time'=>date('H:i:s'));
            sendPush($deviceTokens,$pushData);
             
            
             DB::table('usr_notifications')->insert(['notify_from'=>$from,'user_type'=>$utype,'notify_to'=>$to,'notify_type'=>$ntype,'title'=>$title,'description'=>$desc,'ref_id'=>$refId,'ref_link'=>$reflink,'created_at'=>date('Y-m-d H:i:s')]);
        }
    }
}
if (!function_exists('sendPush')){
function sendPush($deviceTokens,$pushData=[]){
    $fb                     =   Setting::where('is_active',1)->where('type','firebase')->first();
    if($fb){ $accessKey    =   $fb->value; }else{ $accessKey = ''; } 
    $msg          =   array( 'title' => $pushData['title'],'body'=>$pushData['message'],'data'=>$pushData['data']);
    $fields       =   array( 'registration_ids' => $deviceTokens, 'notification' => $msg );
    $headers      =   array( 'Authorization: key='.$accessKey, 'Content-Type: application/json' );
    $ch = curl_init();
    curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
    curl_setopt( $ch,CURLOPT_POST, true );
    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields));
    $result = curl_exec($ch );
    curl_close( $ch );

    return json_encode($result);
}
}
if (!function_exists('getNotifications')){
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
if (!function_exists('getContent')) {
    function getContent($cntId=0, $langId=1){ 
        $content            =   CmsContent::where('cnt_id', $cntId)->where('lang_id', $langId)->where('is_deleted', 0)->first(); 
        if($content){            return $content->content; }
        $content            =   CmsContent::where('cnt_id', $cntId)->where('lang_id', defaultLangId())->where('is_deleted', 0)->first(); 
        if($content){            return $content->content; } return '';
    }
}if (!function_exists('uploadFile')) {
    function uploadFile($path,$fileName){ 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, config('app.upload_url').'/file/upload');
        $postData = array(
            'file'  =>  base64_encode(file_get_contents(url('storage'.$path.'/'.$fileName))),
            'path'  =>  $path,   'fileName'  =>  $fileName,
        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $response = curl_exec($ch);
        return $response;
    }
}
if (!function_exists('sidebarMenu')) {
    function sidebarMenu(){ 

        // dd(Modules::visibleModules(auth()->user()->role_id));
        $menu_list = Modules::visibleModules(auth()->user()->role_id);
        
        if($menu_list){
        if(count($menu_list) >0){
            return $menu_list;
        }else {
           return array(); 
        }    
        }else {
            
           return array(); 
        } 
        
        
        
    }
}
if (!function_exists('checkPermission')) {
    function checkPermission($slug,$act){ 
    if(auth()->user()->role_id ==1) {
        return true;
    }
    $check_perm = Modules::checkPermission($slug,auth()->user()->role_id,$act);
        // dd($check_perm);
    return $check_perm;        
    }
}
if (!function_exists('getCities')) {
    function getCities($city_id){ 
        $city            =   City::where('id', $city_id)->first(); 
        $city_data = [];
        if($city){           
            $city_data['city'] = $city->city_name;
            $state            =   State::where('id', $city->state_id)->first(); 
            if($state){           
            $city_data['state'] = $state->state_name;

            $country            =   Country::where('id', $state->country_id)->first(); 
            if($country){
            
            $city_data['country'] = $country->country_name;
            }else {
            $city_data['country'] = '';
            }
            }else{
            $city_data['state'] = '';
            }
         }else {
            $city_data['city'] = '';
         }
        
        if($city_data){            return $city_data; } return '';
    }
}
if (!function_exists('appVersion')) {
    function appVersion($type){ 
    if($type =="admin") {
        return AppVersion::where('id', 1)->first()->admin_web; 
    }
    else{
        return AppVersion::where('id', 1)->first()->seller_web; 
    }
        
    }
}

if (!function_exists('prdPrice')) {

function prdPrice($subcat,$carat_id=0){

    $rate=0;

       if(Subcategory::where("subcategory_id",$subcat)->first())
       {
        $metal_code = Subcategory::where("subcategory_id",$subcat)->first()->code;
       }else {
         $metal_code = '';
       }
       
       $metals = MetalRates::orderBy('id','DESC')->first();
        if($metal_code=="XAU" && $carat_id){

            if($carat_id)
            {
            $carat = preg_replace("/[^0-9]/", "", $carat_id );
            

            $json = json_decode($metals->carat_rates,TRUE);
            $json_rates=$json['rates'];
            // return $json_rates['Carat 24K'];

            // foreach($json_rates as $key=>$row)
            // {
            // $res = preg_replace("/[^0-9]/", "", $key );
            // if($carat==$res){
         
            // $prc = (float)$row/0.2;
            // $rate= number_format($prc,2);
            // }

            // }
            
             // 24k conversion as per client req
            $twentyfour = reset($json_rates);
            $twentyfour = (float)$twentyfour/0.2;
            // client variation
            $twentyfour = $twentyfour+ 6.5;
            
             if($carat == 24){
             $rate = round($twentyfour);   
            }
            else{
                $sub_crt = ($carat/24)*100; 
                $sub_crt = round($sub_crt,1);
                $req_carat = ($twentyfour * $sub_crt)/100;
                if($carat == 22) { $req_carat = $req_carat+3.89; }else if($carat == 21) { $req_carat = $req_carat+3.58; } else if($carat == 18) { $req_carat = $req_carat+2.92; }
                $rate = round($req_carat);
                
            }
        }else{
             $rate= 0; // only carat rate required
        }
            
        }else
        {
            $jdcode = json_decode($metals->metal_rates,TRUE);
            $api_rates=$jdcode['rates'];
            foreach($api_rates as $key=>$val)
            {
            if($key==$metal_code)
            {
            $prc = $val/28.3495;
            $rate=  number_format($prc);
            }
            }
            $rate= 0; // only carat rate required
        }
    
      return $rate;
    }
        
}
    if (!function_exists('AdminNotification')) {
    function AdminNotification($page=0) { return AdminNotification::orderBy('id', 'DESC')->paginate($page); }
	}

    


if (!function_exists('getProduct')) {

    function getProduct($id) {
        return Product::where('id', $id)->first(); 
    }

}

if (!function_exists('twilio_send_otp')) {

    function twilio_send_otp($ph,$msg) {
    //   $sid = 'AC709155bb1b2e2d7479446ac918a4a3df';
    //   $token = '5b1844c8e9bb9691b090d20690d21167';
    //     try {
    //         $client = new Client($sid, $token);
    //         $message = $client->messages->create(
    //           $ph, // Text this number
    //           [
    //             'from' => '+17632972465', // From a valid Twilio number
    //             'body' => $msg
    //           ]
    //         );
            
            
    //         }
    //          catch (\Exception $e) {
    //         // will return user to previous screen with twilio error
    //         return ['httpcode'=>'400','status'=>'error',"message" => "Invalid Phone number/Country code."];
    //           }
    
    return true;
        
    }
    }



     function putConfig($name,$key,$value,$implodeCharacter = '.' )
    {
        $path = base_path('config/'.$name.'.php');
        $config = config($name);

        $data = findAndReplace($key, $config, $value, $implodeCharacter);
      
        file_put_contents($path,'<?php
        return '.var_export($data,true).';');

        Artisan::call('config:cache');
    }

     function findAndReplace($key, $config,$value,$implodeCharacter) {
        // dd($config); 
        $parts = explode($implodeCharacter, $key);
        for($i=0;$i < count($parts);$i++) {
            if($i == count($parts)-1){
                $config[$parts[$i]] = $value;
            }else{
                $config= $config[$parts[$i]];
            }
        }
        return $config;
    }

    function setConfig($config,$key,$value,$path='')
    {
        $path = base_path('config/'.$config.'.php');
        file_put_contents(
            $path,
            str_replace("'$key'". ' => ' ."'" . config($config)."'",
            "'$key'". ' => ' ."'" . $value ."'",
            file_get_contents($path))
        );
        // clear config cache
        Artisan::call('cache:clear');
    }