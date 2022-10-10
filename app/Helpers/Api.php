<?php
use App\Models\CustomerLogin;
use App\Models\CustomerMaster;
use App\Models\SettingOther;
use App\Models\AssignedFields;
use App\Models\MetalRates;
use App\Models\Currency;


if (!function_exists('getadmin_mail')){
 function getadmin_mail()
    {
        return 'sujeesh.estrrado@gmail.com';
    }
}
if (!function_exists('validateToken')){
    function validateToken($token){
        $query                      =   CustomerLogin::where('access_token',$token)->where('is_login',1)->where('is_deleted',0);
        if($query->exists()){ 
            $user                   =   CustomerMaster::where('id',$query->first()->user_id)->first(); 
            if($user->info->profile_img != NULL){ $avatar = config('app.storage_url').$user->info->profile_img; }else{ $avatar = config('app.storage_url').'/app/public/no-avatar.png'; }
            $data['user_id']        =   $user->id;                              $data['first_name']         =   $user->info->first_name;
            $data['last_name']      =   $user->info->last_name;                 $data['email']              =   $user->custEmail($user->email);
            $data['phone']          =   $user->custPhone($user->phone);    
            $data['avatar']         =   $avatar;
            return $data;
        }else{ return false; }
    }
}
if (!function_exists('getTax')){
 function getTax()
    {
        $list=[];
        $cat= SettingOther::where('is_active',1)->where('is_deleted',0)->where('type','tax')->orderBy('id','DESC')->first();
        if($cat)
        {
            $list['value']=$cat->value;
                
        }
        else
        {
            $list['value']=0;
        }
        return (object)$list;
        
    }
}

if (!function_exists('getCustomerFee')){
 function getCustomerFee()
    {
        $list=[];
        $mjs_fee= SettingOther::where('is_active',1)->where('is_deleted',0)->first();
        if($mjs_fee)
        {
            $list['mjs_fee']=$mjs_fee->mjs_fee;
            $list['pg_fee']=$mjs_fee->pg_fee;
                
        }
        else
        {
            $list['mjs_fee']=0;
            $list['pg_fee']=0;
        }
        return (object)$list;
        
    }
}

if (!function_exists('extra_field_values')){
function extra_field_values($prd_id,$field_id,$fixed=0,$tax=0)
  {
    $extra=[];
    $extra_field=AssignedFields::where('prd_id',$prd_id)->where('is_deleted',0)->where('field_id',$field_id)->get();
    foreach($extra_field as $rows)
    {
        $list['assigned_id']  = $rows->id;
        $list['field_id']     = $rows->field_id;
        $list['field_name']   = $rows->PrdField->name;
        $list['field_val_id'] = $rows->field_val_id;
        $list['field_value']  = $rows->field_value;
        $subcategory_code = $rows->Product->subCategory->code;
        if($subcategory_code=='XAU'){
        if (stripos($rows->PrdField->name, 'carat') !== false || stripos($rows->PrdField->name, 'Karat') !== false)
            {
                $carat=$rows->fieldValue->name;
                $variable=get_variable_price_fn($subcategory_code,$carat);
                        if($rows->Product->weight>0)
                        {
                            $variable_price = $variable*$rows->Product->weight;
                        }
                        else
                        {
                            $variable_price = $variable;
                        }
            }
            else
            {
                $variable_price=false;
            }
        }
        else
        {
            $variable_price=false;
        }
        $list['variable_price']  = $variable_price; 
        
        if($tax>0){
            if($fixed>0){
            $total_p = $variable_price + $fixed;
            }else{
            $total_p = $variable_price;
            }
            $tax_price = ($tax/100)*$total_p;
            $list['product_tax']=$tax_price;
        }
       
        $extra[]  = $list;
    }
    return $extra;
  }
}

if (!function_exists('get_variable_price_fn')){
    function get_variable_price_fn($subcategory_code,$carat)
    {
     
      
            $metals = MetalRates::orderBy('id','DESC')->first();
            $list=null;
            if($metals){
            if($subcategory_code=="XAU" && $carat!=''){
            if($carat)
            {
                 $carat = preg_replace("/[^0-9]/", "", $carat);
           
            
            $json = json_decode($metals->carat_rates,TRUE);
            $json_rates=$json['rates'];
           // return $json_rates['Carat 24K'];
           
            // foreach($json_rates as $key=>$row)
            // {
            //     $res = preg_replace("/[^0-9]/", "", $key );
            //     if($carat==$res){
            //   // $list= $key.'->'.$row;
            //   $fig = (float)$row/0.2;
            //   $list = round($fig,2);
            //     }
               
            // }
            
             // 24k conversion as per client req
            $twentyfour = reset($json_rates);
            $twentyfour = (float)$twentyfour/0.2;
            // client variation
            $twentyfour = $twentyfour+ 6.5;
            
             if($carat == 24){
             $list = round($twentyfour);   
            }
            else{
                $sub_crt = ($carat/24)*100; 
                $sub_crt = round($sub_crt,1);
                $req_carat = ($twentyfour * $sub_crt)/100;
                if($carat == 22) { $req_carat = $req_carat+3.89; }else if($carat == 21) { $req_carat = $req_carat+3.58; } else if($carat == 18) { $req_carat = $req_carat+2.92; }
                $list = round($req_carat);
                 
            }
            }
            else {
                $list= 0; // only carat rate required
            }
           
            }//GOLD
            
            else
            {
                $json = json_decode($metals->metal_rates,TRUE);
                $json_rates=$json['rates'];
                foreach($json_rates as $key=>$row)
                    {
                        if($key==$subcategory_code)
                        {
                            $fig = (float)$row/28.35;
                            $list= round($fig);
                            //return $row;die;
                        }
                    }
                $list= 0; // only carat rate required
            }
            if($list)
            {
                return $list;
            }
            else
            {
                return 0;
            }
            }
   }
}

if (!function_exists('get_currency_rate')){
 function get_currency_rate($currency)
    {
        $value = 1;
        $metals  = MetalRates::orderBy('id','DESC')->first();
        $json    = json_decode($metals->metal_rates,TRUE);
        $json_rates=$json['rates'];
        foreach($json_rates as $key=>$row)
         {
           if($key==$currency)
             {
                 $value = (float)$row;
             }
                        
          }
     return $value;     
    }
}

if (!function_exists('invalidToken')){
    function invalidToken(){
        return array('httpcode'=>401,'status'=>'error','message'=>'Invalid Access Token','data'=>['message'=>'Invalid Access Token','redirect'=>'login']); 
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

if (!function_exists('fedexAuth')){
    
     function fedexAuth()
    {
        $api_url = \Config::get('services.fedex.url');
        $api_key = \Config::get('services.fedex.key');
        $api_secret = \Config::get('services.fedex.secret');
        $input_arr = array('grant_type'=>'client_credentials','client_id'=>$api_key,'client_secret'=>$api_secret);
        $input =  http_build_query($input_arr);

        $url = $api_url.'/oauth/token';
        $method = 'POST';
        $headers = array(
        "content-type: application/x-www-form-urlencoded"
        );

        $curl = curl_init();
        
        curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_URL => $url,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POSTFIELDS => $input
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        
        curl_close($curl);
        
        if ($err) {
        return false;
        } else {
             if(json_decode($response)){
                 if(isset(json_decode($response)->access_token)){
                    $access = json_decode($response)->access_token; 
                 }else {
                    $access = NULL;
                 }
                 
                return $access;
            }else {
               return NULL; 
            }
        }

    }
}

if (!function_exists('fedex_currency')){
    function fedex_currency(){
        $d_currency = Currency::where('is_default',1)->where('is_deleted',0)->first();
            if($d_currency){
             $base = $d_currency->currency_code;   
             if($base == 'AED') { $base = 'DHS';  }
            }else{
               $base = 'DHS'; 
            }
            return $base;  
    }
}


if (!function_exists('domestic_rate_request')){
    function domestic_rate_request($seller_addr,$customer_addr,$products,$access_token){
        
        // $access_token = fedexAuth();
        if($access_token){
        $api_key = \Config::get('services.fedex.key');
        $api_secret = \Config::get('services.fedex.secret');
        $api_account = \Config::get('services.fedex.account');
        $api_url = \Config::get('services.fedex.url');
        $input_arr = array('grant_type'=>'client_credentials','client_id'=>$api_key,'client_secret'=>$api_secret);
        // dd(($products));
        if(count($products) >0) {
        
        $request_arr = [];
        $request_arr['accountNumber']['value'] = $api_account;
        
        // if($seller_addr['address']) {
        //   if(strlen($seller_addr['address']) >20) {
        //     $street = explode(",",$seller_addr['address']);
        //     $tstr = "";
        //     foreach($street as $vals) {
                
        //         $cmb = $tstr.$vals;
                
        //         if(strlen($vals) < 20 && strlen($cmb) <20){
        //             $tstr .=$vals;
        //         }else {
        //             $streets[] =  $tstr; 
        //             $tstr = $vals;
                  
        //         }
        //     }
        //       $street = $streets;
      
        // }else {
        //     $street[] = $seller_addr['address'];
        // }  
        // }else {
        //     $street[] = $seller_addr['city'];
        // }
       
        
        // $request_arr['requestedShipment']['shipper']['address']['streetLines'] = $street;
        $request_arr['requestedShipment']['shipper']['address']['city'] = $seller_addr['city'] ;//$seller_addr['city']
        $request_arr['requestedShipment']['shipper']['address']['countryCode'] =$seller_addr['countryCode'];//$seller_addr['countryCode']
        $request_arr['requestedShipment']['shipper']['address']['postalCode'] = 0;//$seller_addr['postalCode']

        // $street = [];
        //  if($customer_addr['address']) {
        //   if(strlen($customer_addr['address']) >20) {
        //     $street = explode(",",$customer_addr['address']);
        //     $tstr = "";
        //     foreach($street as $vals) {
                
        //         $cmb = $tstr.$vals;
                
        //         if(strlen($vals) < 20 && strlen($cmb) <20){
        //             $tstr .=$vals;
        //         }else {
        //             $streets[] =  $tstr; 
        //             $tstr = $vals;
                  
        //         }
        //     }
        //       $street = $streets;
      
        // }else {
        //     $street[] = $customer_addr['address'];
        // }  
        // }else {
        //     $street[] = $customer_addr['city'];
        // }

        // $request_arr['requestedShipment']['recipient']['address']['streetLines'] = $street;
        $request_arr['requestedShipment']['recipient']['address']['city'] = $customer_addr['city'];
        $request_arr['requestedShipment']['recipient']['address']['countryCode'] = $customer_addr['countryCode'];
        $request_arr['requestedShipment']['recipient']['address']['postalCode'] = 0; //$customer_addr['postalCode']

         $request_arr['requestedShipment']['shipDatestamp'] = date("Y-m-d");
         $request_arr['requestedShipment']['serviceType'] = "STANDARD_OVERNIGHT";
         $request_arr['requestedShipment']['preferredCurrency'] = fedex_currency();
         $request_arr['requestedShipment']['packagingType'] = "YOUR_PACKAGING";
         $request_arr['requestedShipment']['pickupType'] = "USE_SCHEDULED_PICKUP";
         $request_arr['requestedShipment']['rateRequestType'] = array("LIST","ACCOUNT");
         $request_arr['requestedShipment']['customsClearanceDetail']['dutiesPayment']['paymentType'] ="SENDER";
        //  $request_arr['requestedShipment']['customsClearanceDetail']['dutiesPayment']['payor'] =array();
         
         $commodities = []; $packageItems = 0;
         foreach($products as $k=>$product) {
            $product = (object) $product;
            $commodities['description'] = $product->product_name;
            $commodities['quantity'] = $product->quantity;
            $commodities['quantityUnits'] = "PCS";
            $commodities['weight']['units'] = "KG";
            $commodities['weight']['value'] = $product->weight/1000;
            $commodities['customsValue']['amount'] = $product->total_actual_price + $product->total_tax_value;
            $commodities['customsValue']['currency'] = fedex_currency(); //$product->currency
            $packageItems +=$product->weight/1000;
            $commodities_arr[] = $commodities;
         }
        
        if($packageItems < 0.01) { $packageItems = 0.01; }
         $request_arr['requestedShipment']['customsClearanceDetail']['commodities'] = $commodities_arr;
         $request_arr['requestedShipment']['requestedPackageLineItems'][]['weight'] = array('units'=>"KG",'value'=>$packageItems);
         $request_qry =  json_encode($request_arr);

            
            // $url = 'https://apis-sandbox.fedex.com/rate/v1/rates/quotes'; //https://developer.fedex.com/api/en-us/catalog/rate/v1/rate/v1/rates/quotes
            // $method = 'POST';
            // $authorization = "Authorization: Bearer $access_token";
            // $headers = array(
            //     "X-locale :en_US",
            // "content-type: application/json",$authorization
            // );
            
            $url =$api_url.'/rate/v1/rates/quotes';
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_POSTFIELDS => $request_qry,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
            "accept: application/json",
            "accesstoken: $access_token",
            "authorization: Bearer $access_token",
            "content-type: application/json",
            
            
            ),
            ));


            $response = curl_exec($curl);
            $err = curl_error($curl);
            
            curl_close($curl);
            
           $valid_resp = json_decode($response,true); 
            // dd($valid_resp);
            if(isset($valid_resp['errors']) && count($valid_resp['errors']) >0) {
                return false;
            }else {
                // $exchange_rate = json_decode($response)->output->rateReplyDetails[0]->ratedShipmentDetails[0]->shipmentRateDetail->currencyExchangeRate->rate;
                // $aed_rate = (json_decode($response)->output->rateReplyDetails[0]->ratedShipmentDetails[0]->totalNetCharge)/$exchange_rate;
              if(json_decode($response)) {
                    $aed_rate =  (json_decode($response)->output->rateReplyDetails[0]->ratedShipmentDetails[0]->totalNetCharge);
                }else {
                    $aed_rate = 0;
                }
               return  round($aed_rate); 
            }
           
            
         }
        
        }
        else{
            return  0; 
        }
      
    }
}



if (!function_exists('international_rate_request')){
    function international_rate_request($seller_addr,$customer_addr,$products,$access_token){
        
        // $access_token = fedexAuth();
        if($access_token){
        $api_key = \Config::get('services.fedex.key');
        $api_secret = \Config::get('services.fedex.secret');
        $api_account = \Config::get('services.fedex.account');
        $api_url = \Config::get('services.fedex.url');
        $input_arr = array('grant_type'=>'client_credentials','client_id'=>$api_key,'client_secret'=>$api_secret);
        // dd(($products));
        if(count($products) >0) {
        
        $request_arr = [];
        $request_arr['accountNumber']['value'] = $api_account;
        // if($seller_addr['address']) {
        //   if(strlen($seller_addr['address']) >20) {
        //     $street = explode(",",$seller_addr['address']);
        //     $tstr = "";
        //     foreach($street as $vals) {
                
        //         $cmb = $tstr.$vals;
                
        //         if(strlen($vals) < 20 && strlen($cmb) <20){
        //             $tstr .=$vals;
        //         }else {
        //             $streets[] =  $tstr; 
        //             $tstr = $vals;
                  
        //         }
        //     }
        //       $street = $streets;
      
        // }else {
        //     $street[] = $seller_addr['address'];
        // }  
        // }else {
        //     $street[] = $seller_addr['city'];
        // }
       
        
        // $request_arr['requestedShipment']['shipper']['address']['streetLines'] = $street;
        $request_arr['requestedShipment']['shipper']['address']['city'] = 'DUBAI CITY' ;//$seller_addr['city']
        $request_arr['requestedShipment']['shipper']['address']['countryCode'] = 'AE';//$seller_addr['countryCode']
        $request_arr['requestedShipment']['shipper']['address']['postalCode'] = 0;//$seller_addr['postalCode']
        
        
        // $street = [];
        // if($customer_addr['address']) {
        //   if(strlen($customer_addr['address']) >20) {
        //     $street = explode(",",$customer_addr['address']);
        //     $tstr = "";
        //     foreach($street as $vals) {
                
        //         $cmb = $tstr.$vals;
                
        //         if(strlen($vals) < 20 && strlen($cmb) <20){
        //             $tstr .=$vals;
        //         }else {
        //             $streets[] =  $tstr; 
        //             $tstr = $vals;
                  
        //         }
        //     }
        //       $street = $streets;
      
        // }else {
        //     $street[] = $customer_addr['address'];
        // }  
        // }else {
        //     $street[] = $customer_addr['city'];
        // }

        // $request_arr['requestedShipment']['recipient']['address']['streetLines'] = $street;
        $request_arr['requestedShipment']['recipient']['address']['city'] = $customer_addr['city'];
        $request_arr['requestedShipment']['recipient']['address']['countryCode'] = $customer_addr['countryCode'];
        $request_arr['requestedShipment']['recipient']['address']['postalCode'] = $customer_addr['postalCode'];

         $request_arr['requestedShipment']['shipDatestamp'] = date("Y-m-d");
         $request_arr['requestedShipment']['serviceType'] = "INTERNATIONAL_PRIORITY";
        //  $request_arr['requestedShipment']['preferredCurrency'] = fedex_currency();
         $request_arr['requestedShipment']['packagingType'] = "YOUR_PACKAGING";
         $request_arr['requestedShipment']['pickupType'] = "DROPOFF_AT_FEDEX_LOCATION";
         $request_arr['requestedShipment']['rateRequestType'] = array("LIST","ACCOUNT");
         $request_arr['requestedShipment']['customsClearanceDetail']['dutiesPayment']['paymentType'] ="SENDER";
        //  $request_arr['requestedShipment']['customsClearanceDetail']['dutiesPayment']['payor'] =array();
         
         $commodities = []; $packageItems = 0;
         foreach($products as $k=>$product) {
            $product = (object) $product;
            $commodities['description'] = $product->product_name;
            $commodities['quantity'] = $product->quantity;
            $commodities['quantityUnits'] = "PCS";
            $commodities['weight']['units'] = "KG";
            $commodities['weight']['value'] = $product->weight/1000;
            $commodities['customsValue']['amount'] = $product->total_actual_price + $product->total_tax_value;
            $commodities['customsValue']['currency'] = fedex_currency(); //$product->currency
            $packageItems +=$product->weight/1000;
            $commodities_arr[] = $commodities;
         }
        if($packageItems < 0.01) { $packageItems = 0.01; }
         $request_arr['requestedShipment']['customsClearanceDetail']['commodities'] = $commodities_arr;
         $request_arr['requestedShipment']['requestedPackageLineItems'][]['weight'] = array('units'=>"KG",'value'=>$packageItems);
         $request_qry =  json_encode($request_arr);
            // dd($request_qry);
            
            // $url = 'https://apis-sandbox.fedex.com/rate/v1/rates/quotes'; //https://developer.fedex.com/api/en-us/catalog/rate/v1/rate/v1/rates/quotes
            // $method = 'POST';
            // $authorization = "Authorization: Bearer $access_token";
            // $headers = array(
            //     "X-locale :en_US",
            // "content-type: application/json",$authorization
            // );
            
            $url =$api_url.'/rate/v1/rates/quotes';
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_POSTFIELDS => $request_qry,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
            "accept: application/json",
            "accesstoken: $access_token",
            "authorization: Bearer $access_token",
            "content-type: application/json",
            
            
            ),
            ));


            $response = curl_exec($curl);
            $err = curl_error($curl);
            
            curl_close($curl);
            
            if ($err) {
            return false;
            } else {
                
            $valid_resp = json_decode($response,true); 
            // dd($valid_resp);
            if(isset($valid_resp['errors']) && count($valid_resp['errors']) >0) {
                return false;
            }else {
               if(json_decode($response)) {
                   $exchange_rate = json_decode($response)->output->rateReplyDetails[0]->ratedShipmentDetails[0]->shipmentRateDetail->currencyExchangeRate->rate;
                $aed_rate = (json_decode($response)->output->rateReplyDetails[0]->ratedShipmentDetails[0]->totalNetCharge)/$exchange_rate; 
                }else {
                  $aed_rate = 0;  
                }
                
               return  round($aed_rate); 
            }
           
             
     
            }
            
         }
        }
        else{
            return  0; 
        }
      
    }
    
    function get_content($field_id,$lang){ 
     
        if($lang=='')
        { 
	
        $language =DB::table('glo_lang_lk')->where('is_default', 1)->where('is_active', 1)->first();
        $language_id=$language->id;
		
        }
        else
        {
            $language_id=$lang;
        }
        $content_table=DB::table('cms_content')->where('cnt_id', $field_id)->where('lang_id', $language_id)->first();
        
		if(!empty($content_table)){ 
        $return_cont = $content_table->content;
        return $return_cont;
        }
        else
            { return false; }
        }
}


if (!function_exists('authenticateOdoo')){
    function authenticateOdoo(){

        $headers[] = 'Content-Type: application/json';
        $authenticate = json_encode(array(
        'jsonrpc'=>"2.0",
        'params'=>array(
        'login'=>"bigbasket",
        'password'=>"bigbasket1",
        'db'=>'pos_sep14_22',
        ),
        ));  

        $url_cust_auth = "http://3.109.84.120:7054/web/session/authenticate";
        $session_array = [];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_cust_auth);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $authenticate);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADERFUNCTION,
        function($curl, $header) use (&$session_array)
        {
        $len = strlen($header);
        $header = explode(':', $header, 2);
        if (count($header) < 2) // ignore invalid session_array
        return $len;

        $session_array[strtolower(trim($header[0]))][] = trim($header[1]);

        return $len;
        }
        );
        $response = curl_exec($ch);
        if($session_array)
        {
            $session_id = $session_array['set-cookie'];
            $exp_sess = explode('; Expires',$session_id[0]); 
            return $exp_sess[0]; 
        }else{
            return false;
        }
        


    }
}