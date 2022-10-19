<?php
use App\Models\CustomerLogin;
use App\Models\CustomerMaster;
use App\Models\SettingOther;
use App\Models\AssignedFields;
use App\Models\MetalRates;
use App\Models\Currency;
use App\Models\crm\{CrmAssortmentMaster, CrmChildProductsMaster, CrmCustomerType,CrmPartAssortmentDetails,
CrmPartAssortmentMaster,CrmProduct,CrmSalesPriceList,CrmSalesPriceType,CrmSize};

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
            if(isset($user->crm_customer_type)) { $data['crm_customer_type']         =   $user->crm_customer_type; }else{ $data['crm_customer_type']         =0; }
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



  if (!function_exists('get_content')){

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


if (!function_exists('get_crm_price')){
     function get_crm_price($prdid,$type,$user){ 
       
       if(isset($user['crm_customer_type']))
       {

            $crm_price = $prdid->crmProduct->prdPrice;
            $crm_price = CrmSalesPriceList::where('DelStatus',0)->where('Part_id',$prdid->crmProduct->id)->where('CustomerTypeId',$user['crm_customer_type'])->where('PriceTypeId',1)->whereDate('FromDate', '>=', date("Y-m-d"))->first();

            if($crm_price)
            {
                $price['actual_price'] = $crm_price->Amount;
                $price['offer'] = $crm_price->DiscountPercentage;
                if($crm_price->DiscountPercentage >0)
                {
                $dicountprice = $crm_price->Amount - ($crm_price->Amount*($crm_price->DiscountPercentage/100));
                $price['offer_price'] = $dicountprice;   
                }else{
                $price['offer_price'] = ""; 
                }
                
            }else{
                $price['actual_price'] = "";
                $price['offer'] = "";
                $price['offer_price'] = "";
            }
      

            return $price;
       }else{

            $price['actual_price'] = "";
            $price['offer'] = "";
            $price['offer_price'] = "";
        return $price;
       }

    }
}