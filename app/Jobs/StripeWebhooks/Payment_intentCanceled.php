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
use Spatie\WebhookClient\Models\WebhookCall;

class Payment_intentCanceled implements ShouldQueue
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
        $stripe_p['job'] = "Payment_intentCanceled";
        
        if($this->webhookCall->payload['data']['object']['metadata']){
        $order_id = $this->webhookCall->payload['data']['object']['metadata']['order_id'];
        

        
        if($order_id){
            
            $find_order = SaleOrder::where('order_id',$order_id)->where('payment_status','pending')->first();
            if($find_order)
            {
               SaleOrder::where('order_id',$order_id)->update([
             'payment_status'=>'failed','updated_at'=>date("Y-m-d H:i:s")]);
             

               $stripe_p['sale_id'] = $order_id; // sale id
                $stripe_p['response'] = json_encode($customer);
                $updates= StripePayments::create($stripe_p);
                
                    //Notitfication
                $from       = $find_order->cust_id; 
                $utype      = 3;
                $to         = $find_order->cust_id; 
                $ntype      = 'order_placed';
                $title      = 'New order has been placed';
                $desc       = 'New order has been placed. Order ID:'.$order_id;
                $refId      = $find_order->id;
                $reflink    = 'customer/order/detail';
                $notify     = 'customer';
                addNotification($from,$utype,$to,$ntype,$title,$desc,$refId,$reflink,$notify);
                
                
                $from       = $find_order->cust_id; 
                $utype      = 3;
                $to         = $find_order->seller_id; 
                $ntype      = 'order_placed';
                $title      = 'New order has been placed';
                $desc       = 'New order has been placed. Order ID:'.$order_id;
                $refId      = $find_order->id;
                $reflink    = 'customer/order/detail';
                $notify     = 'seller';
                addNotification($from,$utype,$to,$ntype,$title,$desc,$refId,$reflink,$notify);
        
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
        
        }else {
            
                $stripe_p['response'] = json_encode($customer);
                $stripe_p['exception'] = "Meta data missing";
                $updates= StripePayments::create($stripe_p);
        }
                
        // you can access the payload of the webhook call with `$this->webhookCall->payload`
    }
}
