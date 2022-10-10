<?php

namespace App\Jobs\StripeWebhooks;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\SaleOrder;
use App\Models\StripePayments;
use App\Models\customer\CustomerInfo;
use App\Models\SellerInfo;
use App\Models\SellerTelecom;
use Mail;
use App\Models\CustomerTelecom;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\CartHistory;
use App\Models\LoyaltyPoints;
use App\Models\LogLoyaltyPoints;
use App\Models\Customer\CustomerMaster;
use Spatie\WebhookClient\Models\WebhookCall;

use App\Models\customer\CustomerCredits;
use App\Models\customer\CustomerCreditLogs;

class Payment_intentSucceeded implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   /** @var \Spatie\WebhookClient\Models\WebhookCall */
    public $webhookCall;

    public function __construct(WebhookCall $webhookCall)
    {
        $this->webhookCall = $webhookCall;
    }

    public function handle()
    {
        // do your work here


        $customer = $this->webhookCall->payload['data']['object'];
                
        $stripe_p = [];
        $stripe_p['sale_id'] = 0;
        $stripe_p['job'] = "Payment_intentSucceeded";
        
        if($this->webhookCall->payload['data']['object']['metadata']){
        $order_id = $this->webhookCall->payload['data']['object']['metadata']['order_id'];
        $env_selected = $this->webhookCall->payload['data']['object']['metadata']['env'];
        $current_env = \Config::get('services.stripe.env');
        
        $req_type = $this->webhookCall->payload['data']['object']['metadata']['req_type'];
        $user_id = $this->webhookCall->payload['data']['object']['metadata']['user_id'];

        if($req_type =="credit"){


            $rules['credits_id']    = 'required';

            $credits_id = $order_id;

  
                $credit_table = (new CustomerCredits)->getTable();
                $exp_arr = explode(",", $credits_id); $insId = 0; 
                if(isset($exp_arr))
                {
                    foreach($exp_arr as $ek=>$ev)
                    {

                      $tr_info =  CustomerCredits::where('id',$ev)->whereNotIn('ref_id',function($query) use($credit_table,$user_id) {
                        $query->select('ref_id')->from("$credit_table")->where('credit','>',0)->where('user_id',$user_id);})->first();
                      $tr_latest_info =  CustomerCredits::where('user_id',$user_id)->orderBy("id","DESC")->first();

                      if($tr_info && $tr_latest_info){
                        $pay_arr = [];
                        $pay_arr['user_id'] = $user_id;
                        $pay_arr['ref_id'] = $tr_info->ref_id;
                        $pay_arr['log_id'] = $tr_info->log_id;
                        $pay_arr['credit_limit'] = $tr_latest_info->credit_limit;
                        $pay_arr['credit_days'] = $tr_latest_info->credit_days;
                        $pay_arr['credit'] = $tr_info->debit;
                        $pay_arr['per_purchase'] = $tr_latest_info->per_purchase;
                        $pay_arr['created_by'] = $user_id;
                        $pay_arr['modified_by'] = $user_id;
                        $pay_arr['payment_status'] = "paid";

                        $insId      =   CustomerCredits::create($pay_arr)->id;                        
                      }


                    }
                }
 


        }else{
       
       if($env_selected == "$current_env"){
        
        if($order_id){
            
            $find_order = SaleOrder::where('order_id',$order_id)->where('payment_status','pending')->first();
            $get_order = SaleOrder::where('order_id',$order_id)->where('payment_status','pending')->get();
            
			if($find_order)
            {
                
                foreach ($get_order as $key => $saldata) {
                    
                    //$seller_id =$saldata->seller_id; 
                    $user_id = $saldata->cust_id;
                        $product=[];
                        $prod_datas  = Product::join('usr_cart_item','prd_products.id','=','usr_cart_item.product_id')
                        ->join('usr_cart','usr_cart_item.cart_id','=','usr_cart.id')
                        ->where('usr_cart.user_id',$user_id)    
                        ->where('usr_cart.is_active',1)
                        ->where('usr_cart.is_deleted',0)
                        ->where('usr_cart_item.is_active',1)
                        ->where('usr_cart_item.is_deleted',0)
                        ->where('prd_products.is_active',1)->where('prd_products.is_deleted',0)
                       // ->where('prd_products.seller_id',$seller_id)
                        ->select('prd_products.*','usr_cart.*','usr_cart_item.*','usr_cart_item.product_id as cart_prd_id')
                        ->get();
                        
                        if(count($prod_datas)>0)   {    
                        foreach($prod_datas as $prod_data)
                        {    
                        
                        $cart_update= Cart::where('id',$prod_data->cart_id)->update([
                        'is_active'=>0,'updated_at'=>date("Y-m-d H:i:s")]);
                        
                        $cart_item_update=CartItem::where('cart_id',$prod_data->cart_id)->update([
                        'is_active'=>0,
                        'updated_at'=>date("Y-m-d H:i:s")]);  
                        
                        $insert_cart_hist =  CartHistory::create(['org_id' => 1,
                        'user_id' => $user_id,
                        'product_id' => $prod_data->product_id,
                        'quantity'  => $prod_data->quantity,
                        'action'=>'ordered',
                        'is_active'=>1,
                        'is_deleted'=>0,
                        'created_by'=>$user_id,
                        'updated_by'=>$user_id,
                        'created_at'=>date("Y-m-d H:i:s"),
                        'updated_at'=>date("Y-m-d H:i:s")]);                                 
                        
                        
                        }         
                        
                        }

               // $mail       = SellerTelecom::where('seller_id',$saldata->seller_id)->where('type_id',1)->first(); 
               // $seller_email = $mail->value;
                //$seller_info  = SellerInfo::where('seller_id',$saldata->seller_id)->first();
               $seller_name  = "Bigbasket";
                
                $cust_info  = CustomerInfo::where('user_id',$saldata->cust_id)->first();
                $user_name  = $cust_info->first_name;
                $user_email       = CustomerTelecom::where('user_id',$saldata->cust_id)->where('usr_telecom_typ_id',1)->first(); 
                $user_email = $user_email->usr_telecom_value;
                
               /*  $data['data'] = array("content"=>"Test",'seller_name'=>$seller_name,'username'=>$user_name,'sale_id'=>$order_id,'seller_id'=>$saldata->seller_id); 
                        $var = Mail::send('emails.seller_msg_email', $data, function($message) use($data,$seller_email) {
                        $message->from(getadmin_mail(),'MJS');    
                        $message->to($seller_email);
                        $message->subject('Order Placed');
                        });*/
                        
                }
                
                $data['data'] = array("content"=>"Test",'seller_name'=>$seller_name,'username'=>$user_name,'sale_id'=>$order_id);
                $var = Mail::send('emails.customer_msg_email', $data, function($message) use($data,$user_email) {
                $message->from(getadmin_mail(),'MJS');    
                $message->to($user_email);
                $message->subject('Order Placed ');
                });
        
                SaleOrder::where('order_id',$order_id)->update(['order_status'=>'pending',
             'payment_status'=>'paid','updated_at'=>date("Y-m-d H:i:s")]);
//Loyalty Points
						$loyaltypoints=LoyaltyPoints::where('is_active',1)->where('is_deleted',"0")->first();
						if($loyaltypoints){
							if($find_order->total >= $loyaltypoints->order_amount){
								$loyalty_data['credit']=($find_order->total*$loyaltypoints->point)/100;
								$customer_info=CustomerMaster::where('id',$user_id)->where('is_deleted',"0")->first();
								if($customer_info){
									if($customer_info->parent_id > 0 ){
										$loyalty_data['user_id']=$customer_info->parent_id;
									}else{
										$loyalty_data['user_id']=$user_id;
									}
								}
								$loyalty_data['sales_id']=$find_order->id;
								$insert_loyaltypoints =  LogLoyaltyPoints::create($loyalty_data);   
							}
						}             

                //$stripe_p['sale_id'] = $order_id; // sale id
                //$stripe_p['response'] = json_encode($customer);
                //$updates= StripePayments::create($stripe_p);
                
                //Notitfication
                $from       = $find_order->cust_id; 
                $utype      = 3;
                $to         = $find_order->cust_id; 
                $ntype      = 'order_placed';
                $title      = 'Order placed';
                $desc       = 'New order has been placed. Order ID:'.$order_id;
                $refId      = $find_order->id;
                $reflink    = 'customer/order/detail';
                $notify     = 'customer';
                addNotification($from,$utype,$to,$ntype,$title,$desc,$refId,$reflink,$notify);
                
                
                /*$from       = $find_order->cust_id; 
                $utype      = 3;
                //$to         = $find_order->seller_id; 
                $ntype      = 'order_placed';
                $title      = 'Order placed';
                $desc       = 'New order has been placed. Order ID:'.$order_id;
                $refId      = $find_order->id;
                $reflink    = 'customer/order/detail';
                $notify     = 'seller';
                addNotification($from,$utype,$to,$ntype,$title,$desc,$refId,$reflink,$notify);
                */
        
            }else {
                
                $stripe_p['exception'] = "Order id not found";
                $stripe_p['response'] = json_encode($customer);
                $updates= StripePayments::create($stripe_p);
                
            }
            
            
         }else {
             
                $stripe_p['response'] = json_encode($customer);
                $stripe_p['exception'] = "Order id mismatch";
                $updates= StripePayments::create($stripe_p);
                
         }
         
        }
        }
        
        }else {
            
                $stripe_p['response'] = json_encode($customer);
                $stripe_p['exception'] = "Meta data missing";
                $updates= StripePayments::create($stripe_p);
        }
     

        
        
        
                
        // you can access the payload of the webhook call with `$this->webhookCall->payload`
    }
}