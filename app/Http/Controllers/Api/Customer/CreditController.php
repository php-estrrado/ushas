<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;
use App\Models\Modules;
use App\Models\UserRoles;
use App\Models\Admin;
use App\Models\Auction;
use App\Models\AuctionHist;
use App\Models\AssociatProduct;
use App\Models\UserRole;
use App\Models\Category;
use App\Models\CartItem;
use App\Models\Cart;
use App\Models\CartHistory;
use App\Models\Coupon;
use App\Models\CouponHist;
use App\Models\Subcategory;
use App\Models\Store;
use App\Models\SellerReview;
use App\Models\SaleOrder;
use App\Models\SaleorderItems;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\PrdAdminImage;
use App\Models\ProductDaily;
use App\Models\PrdAssignedTag;
use App\Models\PrdReview;
use App\Models\PrdShock_Sale;
use App\Models\PrdPrice;
use App\Models\PrdOffer;
use App\Models\Reward;
use App\Models\RewardType;
use App\Models\RelatedProduct;
use App\Models\AssignedAttribute;
use App\Models\Wishlist;
use App\Models\SalesOrderPayment;
use App\Models\CreditPayments;
use App\Models\LoyaltyPoints;
use App\Models\LogLoyaltyPoints;
use App\Models\customer\CustomerCredits;
use App\Models\customer\CustomerCreditLogs;
use App\Models\customer\CustomerInfo;
use App\Models\customer\CustomerTelecom;
use App\Models\customer\CustomerMaster;
use Carbon\Carbon;
use App\Rules\Name;
use Validator;
use Mail;
class CreditController extends Controller
{
    
	public function customer_credit(Request $request){
		$lang=$request->lang_id;
        $login=0;
        $user_id=null;
        $user = [];
        $data = [];
        $validator=  Validator::make($request->all(),[
            'device_id' => ['required'],
            'os_type'=> ['required','string','min:3','max:3'],
            'page_url'=>['required']
        ]);
        if($request->post('access_token')){
        if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
            $login=1;
			
			$user_id=$user['user_id'];
			//dd($user_id);
        }
		$creditData = CustomerCredits::where('is_active',1)->where('user_id',$user_id)->orderBY('id','Desc')->get();
		$credit=0;
		$debit=0;
		if($creditData){
			
			foreach($creditData as $row){
				
				$credit+= $row->credit;
				$debit+= $row->debit;
				$credits=$credit;
				$debits=$debit;
			}
			
		}
		$lastlog=CustomerCreditLogs::whereRaw('id = (select max(`id`) from usr_cust_credits_log)')->where('user_id',$user_id)->first();
		if($lastlog){
		$data['is_purchase_allow']=$lastlog->allow_purchase;
		$data['credit_using_per_purchase']=$lastlog->per_purchase;
		$data['total_limit']=$lastlog->credit_limit;
		$data['total_debit']=$debit;
		$data['total_credit']=$credit;
		
		$data['balance_credit']=($data['total_limit']-$debit)+$credit;
		$data['outstanding']=$debit-$credit;
	    if($data['outstanding']>0){
			$lastdebitlog=CustomerCredits::where('debit','!=','0')->where('user_id',$user_id)->orderBy('id','asc')->first();
			$creditData = $lastlog->credit_days;
			$created_at = $lastdebitlog->created_at; 
			$current_date=Carbon::now();
			$paymentReturnDate = date('Y-m-d H:i:s', strtotime($created_at. ' + '.$creditData.' days'));
		    if($current_date > $paymentReturnDate)
			{
				return ['httpcode'=>404,'status'=>'error','message'=>'Not found','data'=>['errors'=>'Payment Not Possible.Credit Payment return time expired']];
			}				
		}
		}else{
		 return ['httpcode'=>400,'status'=>'error','message'=>'Credits not yet added to you account','credit'=>'0'];   
		}
		$credit_details[]=$data;
		return ['httpcode'=>200,'status'=>'success','message'=>'Use Your Credits','data'=>['credit'=>$credit_details]];  
	}	
     
    public function payment(Request $request){
		if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
        $user_id = $user['user_id'];
		$validator=  Validator::make($request->all(),[
            'access_token'          => ['required'],
            'order_id'           => ['required'],
            'amount'           => ['required']
        ]);
        $input = $request->all();

		if ($validator->fails()){    
			return ['httpcode'=>400,'status'=>'error','message'=>'Invalid parameters','data'=>['errors'=>$validator->messages()]];
		}else{
			$order_id = $request->order_id;
            $find_order = SaleOrder::where('order_id',$order_id)->where('payment_status','pending')->first();
            $get_order = SaleOrder::where('order_id',$order_id)->where('payment_status','pending')->get();
            
			if($find_order)
            {
                $lastlog=CustomerCreditLogs::whereRaw('id = (select max(`id`) from usr_cust_credits_log)')->where('user_id',$user_id)->first();
                if($lastlog){
                    if($lastlog->per_purchase>= $request->amount){
                foreach ($get_order as $key => $saldata) {
                $sale_id=$saldata->id;    
				$lastlog=CustomerCreditLogs::whereRaw('id = (select max(`id`) from usr_cust_credits_log)')->where('user_id',$user_id)->first();
				//dd($user_id);
				$pay_arr = [];
				$pay_arr['user_id'] = $user_id;
				$pay_arr['ref_id'] =  $sale_id;
				$pay_arr['log_id'] =  $lastlog->id;
				$pay_arr['credit_limit'] = $lastlog->credit_limit;
				$pay_arr['credit_days'] = $lastlog->credit_days;
				$pay_arr['debit'] =  $request->amount;
				$pay_arr['per_purchase'] = $lastlog->per_purchase;
				$pay_arr['created_by'] = $user_id;
				$pay_arr['modified_by'] = $user_id;
				$insId      =   CustomerCredits::create($pay_arr)->id;
				$payment_status="Success";
				$last_transaction=SalesOrderPayment::where('payment_method_id',2)->where('payment_type',"CREDIT")->where('transaction_id','!=',"")->orderBy('id','desc')->first();
				$transaction_id="CREDITBB".$sale_id;
				
					
					
					
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
								$loyalty_data['sales_id']=$sale_id;
								$insert_loyaltypoints =  LogLoyaltyPoints::create($loyalty_data);   
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
               // dd
               $data['data'] = array("content"=>"Test",'seller_name'=>$seller_name,'username'=>$user_name,'sale_id'=>$order_id);
                $var = Mail::send('emails.customer_msg_email', $data, function($message) use($data,$user_email) {
                $message->from(getadmin_mail(),'Bigbasket');    
                $message->to($user_email);
                $message->subject('Order Placed ');
                });
        
                SaleOrder::where('order_id',$order_id)->update(['order_status'=>'pending',
                'payment_status'=>'paid','updated_at'=>date("Y-m-d H:i:s")]);
				
				$payment_type="CREDIT";
				$payment_data="Credit Transaction";
				$payment_status="Success";
				SalesOrderPayment::where('sales_id',$sale_id)->update(['payment_status'=>$payment_status,'updated_at'=>date("Y-m-d H:i:s")]);
             

                $credit_p['sale_id'] = $order_id; // sale id
                $credit_p['transaction_id'] = $transaction_id; // sale id
                $updates= CreditPayments::create($credit_p);
                
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
				return ['httpcode'=>200,'status'=>'success','message'=>'Payment Success','data'=>['transaction_id'=>$transaction_id]];
                    }else{
                    return ['httpcode'=>400,'status'=>'error','message'=>'Payment Failed! Maximum Amount per purchase using credit is '.$lastlog->per_purchase];     
                    }
                }else{
            	   return ['httpcode'=>400,'status'=>'error','message'=>'Payment Failed! Credits not yet added to you account']; 
            	}
            }else {
                
                $credit_p['exception'] = "Order id not found";
                $updates= CreditPayments::create($credit_p);
                return ['httpcode'=>400,'status'=>'error','message'=>'Payment Failed! Order id not found'];

            }
            
            
         }
	}		
}
