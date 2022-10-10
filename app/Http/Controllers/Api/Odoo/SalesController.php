<?php

namespace App\Http\Controllers\Api\Odoo;

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
use App\Models\Banner;
use App\Models\Brand;
use App\Models\UserRole;
use App\Models\Category;
use App\Models\CartItem;
use App\Models\Cart;
use App\Models\CartHistory;
use App\Models\Coupon;
use App\Models\CouponHist;
use App\Models\Customer;
use App\Models\CustomerCoupon;
use App\Models\CustomerAddress;
use App\Models\CustomerAddressType;
use App\Models\CustomerWallet_Model;
use App\Models\Subcategory;
use App\Models\Store;
use App\Models\SellerReview;
use App\Models\SaleOrder;
use App\Models\SaleorderItems;
use App\Models\SalesOrderAddress;
use App\Models\SalesOrderPayment;
use App\Models\SalesOrderCancel;
use App\Models\SalesOrderCancelNote;
use App\Models\SalesOrderReturn;
use App\Models\SalesOrderReturnStatus;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductDaily;
use App\Models\PrdAssignedTag;
use App\Models\PrdReview;
use App\Models\PrdShock_Sale;
use App\Models\PrdPrice;
use App\Models\PrdStock;
use App\Models\ParentSale;
use App\Models\RelatedProduct;
use App\Models\Reward;
use App\Models\RewardType;
use App\Models\AssignedAttribute;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use App\Models\MetalRates;
use App\Models\AssignedFields;
use App\Models\Seller;
use App\Models\SellerInfo;
use App\Models\SellerTelecom;
use App\Models\Email;
use App\Models\SalesOrderStatusHistory;
use App\Models\SalesOrderItemOption;
use App\Models\PrdOffer;
use App\Models\SalesOrder;
use App\Models\SalesOrderRefundPayment;
use Carbon\Carbon;
use App\Rules\Name;
use Validator;

use Mail;
use PDF;
use Redirect;
use Storage;
use App\Models\customer\CustomerMaster;
use App\Models\customer\CustomerInfo;
use App\Models\customer\CustomerSecurity;
use App\Models\customer\CustomerTelecom;
use App\Models\customer\CustomerCreditLogs;
use App\Models\customer\CustomerCredits;
use App\Models\customer\CustomerBranchEmployees;
use App\Models\SellerAddress;
use App\Models\SalesOrderShippingStatus;

use App\Models\InviteSave;

class SalesController extends Controller {

public function list(){ 
		$datas = SaleOrder::orderby('id','desc')->get();			
			if($datas){
				$sales=[];
				foreach($datas as $data){
				$cat['sales_id'] = $data->id;
				$cat['order_id'] = $data->order_id;
				$cat['total_amount'] = $data->total;
				$cat['discount'] = $data->discount;
				$cat['shiping_charge'] = $data->shiping_charge;
				$cat['tax'] = $data->tax;
				$cat['grand_total'] = $data->g_total;
				$cat['order_status'] = $data->order_status;
				$cat['payment_status'] = $data->payment_status;
				$cat['platform'] = $data->platform;
				$cat['delivery_status'] = $data->delivery_status;
				$cat['delivery_date'] = $data->delivery_date;
				
				$sales[]=$cat;
				}
				return array('httpcode'=>'200','status'=>'success','message'=>'Success','sales'=>$sales);

			}else{
				return array('httpcode'=>'400','status'=>'error','message'=>"Not Found");

			}
	}	
	
public function placeorder(Request $request)
    {
			$rules      =   array();
            $rules['odoo_unique_id']           = 'required|unique:sales_orders,odoo_id';
            $rules['order_id']           = 'required|string|unique:sales_orders,order_id';
            $rules['customer_id']           =  'required|numeric';
            $rules['total_amount']           = 'required|numeric';
            $rules['total_discount']           = 'required|numeric';
            $rules['tax']           = 'required|numeric';
            $rules['shipping_charge']           = 'required|numeric';
            $rules['payment_gateway_charge']           = 'required|numeric';
            $rules['wallet_amount']           = 'nullable';
            $rules['grand_total']           = 'required|numeric';
            $rules['cust_message']           = 'nullable';
            $rules['coupon_id']           = 'nullable|numeric';
            $rules['discount_type']           = 'nullable|in:cashback,discount';
            $rules['coupon_discount_amount']           = 'required|numeric';
            $rules['order_status']           = 'required';
            $rules['payment_status']           = 'required';
            $rules['shipping_status']           = 'required';
            $rules['sale_item_details']           = 'required|array';
            $rules['address_id']           = 'required|numeric';
            $rules['address_type_id']           = 'required|numeric';
            $rules['address_name']           = 'required';
            $rules['phone_countrycode']           = 'required|numeric';
            $rules['address_phone']           = 'required|numeric';
            $rules['address_email']           = 'required';
            $rules['address1']           = 'required';
            $rules['address2']           = 'required';
            $rules['city_id']           = 'required|numeric';
            $rules['state_id']           = 'required|numeric';
            $rules['country_id']           = 'required|numeric';
            $rules['s_address_id']           = 'required|numeric';
            $rules['s_address_name']           = 'required';
            $rules['s_phone_countrycode']           = 'required';
            $rules['s_address_phone']           = 'required';
            $rules['s_address_email']           = 'required';
            $rules['s_address1']           = 'required';
            $rules['s_address2']           = 'required';
            $rules['s_city_id']           = 'required|numeric';
            $rules['s_state_id']           = 'required|numeric';
            $rules['s_country_id']           = 'required|numeric';
            $rules['transaction_id']           = 'required';


        $input = $request->all();

		$validator  =   Validator::make($request->all(), $rules);
		if($validator->fails()){
			foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
			return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
		} else {
			$sale_item_details =$input['sale_item_details'];	
			if(count($sale_item_details)>0){    
				$tot_tax =$input['tax'];	
				$total_cost=$input['total_amount'];	
				$total_discount=$input['total_discount'];	
				$grand_total=$input['grand_total'];	
				$order_status=$input['order_status'];	
				$payment_status=$input['payment_status'];	
				$shipping_status=$input['shipping_status'];	
				$user_id=$input['customer_id'];	
				
				
				//Coupon
				if($input['is_coupon']==true){
					$coupon_id = $input['coupon_id'];
					$coupon_discount_amount = $input['coupon_discount_amount'];
					$discount_type = $input['discount_type'];
				}else{
					$coupon_id = '';
					$coupon_discount_amount = 0;
					$discount_type = '';
				}
			
			
			
				//WALlet balance
				if($input['wallet_amount']==false)
				{
					$wallet_amt = 0;
				}
				else
				{
					$wallet_amt = $input['wallet_amount'];
					
				}
        
				$saleorder_id=$input['order_id'];
			
				if($input['total_discount']!="")
                {
                    $discount_amt_sale = $input['total_discount'];
                }
                else
                {
                    $discount_amt_sale = 0;
                }
               
                if($input['shipping_charge']!="")
                {
                    $shipping_chrg = 0; 
                }
                else
                {
                    $shipping_chrg = 0;
                }
                
               
                
                $grnd_tot_sale = $grand_total;
                $create_saleorder = SaleOrder::create(['org_id' => 1,
                'parent_sale_id'  =>0,
                'order_id'        => $saleorder_id,
                'cust_id'         => $user_id,
                'odoo_id'         => $input['odoo_unique_id'],
                'platform'         => "odoo",
                'total'           =>  $total_cost,
                'discount'        => $discount_amt_sale,
                'tax'             => $tot_tax,
                'shiping_charge'  => $shipping_chrg,
                'packing_charge'  => 0,
                'wallet_amount'   => $wallet_amt,
                'g_total'         => $grnd_tot_sale,
                'discount_type'   => $input['discount_type'],  
                'coupon_id'       => $input['coupon_id'],
                'coupon_discount'       => $coupon_discount_amount,
                'order_status'    => $order_status,
                'payment_status'  => $payment_status,
                'shipping_status' => $shipping_status,
                'cancel_process'  => 0,
                'cust_message'    => $input['cust_message'],    
                'created_at'    =>date("Y-m-d H:i:s"),
                'updated_at'    =>date("Y-m-d H:i:s")]);
				
				
                $sale_id  = $create_saleorder->id;
				if($wallet_amt>0)
				{
					$wallet_usage = CustomerWallet_Model::create(['user_id'    =>  $user_id,
																  'source_id'  =>  $sale_id,
																  'source'     =>  'Order',
																  'debit'      =>  $wallet_amt,
																  'is_active'  =>  1,
																  'is_deleted' =>  0,
																  'created_at'    =>date("Y-m-d H:i:s"),
																  'updated_at'    =>date("Y-m-d H:i:s")]); 
				}
                //coupon usage history insertion
                if($input['is_coupon']==true)
                {
                    $coupon_data= Coupon::where('id',$input['coupon_id'])->first();
                    if($coupon_data)
                    {
                    $coupon_usage= CouponHist::create(['org_id'       =>  1,
                                                       'coupon_id'    =>  $coupon_id,
                                                       'platform'     =>"odoo",
                                                       'order_id'     =>  $sale_id,
                                                       'ofr_value'    =>  $coupon_data->ofr_value,
                                                       'ofr_value_type'=> $coupon_data->ofr_value_type,
                                                       'ofr_type'     =>  $coupon_data->ofr_type,
                                                       'created_at'    =>date("Y-m-d H:i:s"),
                                                       'updated_at'    =>date("Y-m-d H:i:s")
                                                        ]);
					 }
                }
                
                $stHistory                  =   ['sales_id'=>$sale_id,'status'=>"Ordered",'created_by'=>$user_id,'role_id'=>5,'platform'     =>"odoo"];
                $stHistory['description']   =   "Order Placed by Customer";    SalesOrderStatusHistory::create($stHistory);

                //Payment
				if($input['payment_type']==1){
				$payment_type="Stripe";
				$payment_status=$payment_status;
				$payment_data="";
				$transaction_id=$input['transaction_id'];
				}
				else if($input['payment_type']==2){ 
				$payment_type="CREDIT";
				$payment_status=$payment_status;
				$payment_data="";
				$transaction_id=$input['transaction_id'];
				
				}else{
				$payment_type="Offline Payment";
				$payment_status=$payment_status;
				$payment_data="Offline payment";
				$transaction_id=$input['transaction_id'];
				}
                $saleorder_payment = SalesOrderPayment::create(['org_id' => 1,
                'sales_id'         => $sale_id,
                'payment_method_id'=> $input['payment_type'],
                'payment_type'     => $payment_type,
                'transaction_id'   => $transaction_id,
                'payment_data'     => $payment_data,
                'amount'           => $grnd_tot_sale,
                'payment_status'   => "pending"])->id;
				
				$sale_item_details =$input['sale_item_details'];
				foreach($sale_item_details as $rows){ 
					$prod_data  = Product::where('id', $rows['prd_id'])->first();
					if($rows['prd_type']==1){
						$parent_id=$prod_data->product_id;
                    }
                    else
                    {
                     $associate= AssociatProduct::where('ass_prd_id',$prod_data->id)->first();
                     if($associate){
							$parent_id=$associate->prd_id;
                     }else{
							$parent_id=0;
                     }
                    }
					$create_saleorder = SaleorderItems::create([
						'sales_id'        => $sale_id,
						'parent_id'       => $parent_id,
						'prd_id'          => $rows['prd_id'],
						'prd_type'        => $rows['prd_type'],
						'prd_name'        => $rows['prd_name'],
						'price'           => $rows['price'],
						'qty'             => $rows['qty'],
						'total'           => $rows['total'],
						'discount'        => $rows['discount'],
						'tax'             => $rows['tax'],
						'row_total'       => $rows['row_total'],
						'coupon_id'       => '', 
						'created_at'    =>date("Y-m-d H:i:s"),
						'updated_at'    =>date("Y-m-d H:i:s"),
						'is_deleted'    =>0])->id;
					
					$prd_stock_update = PrdStock::create([
                                                 'type'       =>'destroy',
                                                 'prd_id'     => $rows['prd_id'],
                                                 'qty'        => $rows['qty'],
                                                 'rate'       => $rows['price'],
                                                 'created_by' => $user_id,
                                                 'sale_id'    => $sale_id,
                                                 'created_at' => date("Y-m-d H:i:s"),
                                                 'updated_at' => date("Y-m-d H:i:s")
                                                 ]);  

                if($rows['prd_type']==2){
													
									$attr_list['sales_id']=$sale_id;
									$attr_list['sales_item_id']=$create_saleorder;
									$attr_list['prd_id']= $rows['prd_id'];
									$attr_list['attr_id']=$rows['attribute_id'];
									$attr_list['attr_value_id']=$rows['attribute_value_id'];
									$attr_list['attr_name']=$rows['attribute_name'];
									$attr_list['attr_value']=$rows['attribute_value'];
									$attr_list['is_deleted']=0;
									SalesOrderItemOption::create($attr_list);
							
						
					}
				}
				$saleorder_payments = SalesOrderShippingStatus::create([
                'sales_id'         => $sale_id,
                'status'=> "pending" ]);
				  if($input['is_coupon']==true)
					{
						
						if($input['discount_type']=='cashback')
						{                                        
							$cashback = CustomerWallet_Model::create(['user_id'    =>  $user_id,
																	  'source_id'  =>  $sale_id,
																	  'source'     =>  'Coupon',
																	  'credit'     =>  $cashback_amount,
																	  'is_active'  =>  1,
																	  'is_deleted' =>  0,
																	  'created_at'    =>date("Y-m-d H:i:s"),
																	  'updated_at'    =>date("Y-m-d H:i:s")]);  
						}
						
					}
                             
				$insert_address = SalesOrderAddress::create(['sales_id' => $sale_id,
                'order_id'        => $saleorder_id,
                'cust_id'         => $user_id,
                'ref_addr_id'     => $input['address_id'],
                'addr_id'         => $input['address_type_id'],
                'name'            => $input['address_name'],
                'country_code'    => $input['phone_countrycode'],
                'phone'           => $input['address_phone'],
                'email'           => $input['address_email'],
                'address1'        => $input['address1'],
                'address2'        => $input['address2'],
                'zip_code'        => $input['zip_code'],
                'city'            => $input['city_id'],
                'state'           => $input['state_id'],
                'country'         => $input['country_id'],  
                'latitude'        => 0,
                'longitude'       => 0,
                's_addr_id'       => $input['s_address_id'],
                's_name'          => $input['s_address_name'],
                's_country_code'         => $input['s_phone_countrycode'],
                's_phone'         => $input['s_address_phone'],
                's_email'         => $input['s_address_email'],
                's_address1'      => $input['s_address1'],
                's_address2'      => $input['s_address2'],
                's_zip_code'      => $input['s_zip_code'],
                's_city'          => $input['s_city_id'],
                's_state'         => $input['s_state_id'],
                's_country'       => $input['s_country_id'],  
                's_latitude'      => 0,
                's_longitude'     => 0]);
                
             
                
            
            return ['httpcode'=>200,'status'=>'success','message'=>'Order placed','data'=>['order_id'=>$saleorder_id]];

        }

        else
        {
            return ['httpcode'=>404,'status'=>'error','message'=>'order items not found','data'=>['errors'=>'order items not found']];
        }
    }//validation true
	}
	function update(Request $request,$id){
		$rules      =   array();
        $rules['order_status']           = 'required|string';
        $input = $request->all();
		$validator  =   Validator::make($request->all(), $rules);
		if($validator->fails()){
			foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
			return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
		
		} else {
		$saleorder=SalesOrder::where('id',$id)->first();
		if($saleorder){
			$post                       =   (object)$request->post(); // echo '<pre>'; print_r($post); echo '</pre>'; die;
			$update                 =   SalesOrder::where('id',$id)->update(['order_status' =>  $input['order_status'],'updated_platform' => 'odoo']);
				if($input['order_status']=="delivered"){
				$update                 =   SalesOrderShippingStatus::where('sales_id',$id)->update(['status' => $input['order_status']]);
				SalesOrder::where('id',$id)->update(['shipping_status' =>  $input['order_status']]);
				}
				$salesId = $id;
				$stHistory                  =   ['sales_id'=>$id,'status'=>$input['order_status'],'created_by'=>1,'role_id'=>2,'platform' => 'odoo'];
				$stHistory['description']   =   "Order ".$input['order_status']." by admin";    SalesOrderStatusHistory::create($stHistory);
				//$this->orderStatusEmailSend($salesId);
				$saleorder = SalesOrder::where('id',$id)->first();
				$from   = 1; 
				$utype  = 1;
				$to_c     = $saleorder->cust_id;
				
				//dd($to_c);
				$to_a     = 1;
				
				$refId  = $id;
				$reflink = 'customer/order/detail';
				$reflink_a = 'admin/sales/orders';
				$notify_c  = 'customer';
				$notify_a  = 'admin';
				
				$ntype  = $input['order_status'];
				$title  = 'order_'.$input['order_status'];
				$desc   = 'Order #'.$saleorder->order_id.',order '.$input['order_status'].' by the admin';
				addNotification($from,$utype,$to_c,$ntype,$title,$desc,$refId,$reflink,$notify_c);
				addNotification($from,$utype,$to_a,$ntype,$title,$desc,$refId,$reflink_a,$notify_a);
		
				if($update>0){
					return array('httpcode'=>'200','status'=>'success','message'=>'Success','data'=>['coupon_id' =>$saleorder]);
				}else{
					return array('httpcode'=>'200','status'=>'success','message'=>'Success','data'=>['coupon_id' =>$saleorder]);
				}
			}else{
					return array('httpcode'=>'400','status'=>'error','message'=>'Not Found');
			}

		}
	}
	
	
	Public function cancelOrder(Request $request, $id){
		$rules      =   array();
        $rules['status']           = 'required|string';
        $input = $request->all();
		$validator  =   Validator::make($request->all(), $rules);
		if($validator->fails()){
			foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
			return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
		
		} else {
		$input = $request->all();
		$saleorder=SalesOrderCancel::where('sales_id',$id)->first();
		if($saleorder){
        $post                       =   (object)$request->post(); // echo '<pre>'; print_r($post); echo '</pre>'; die;
        
            $update                 =   SalesOrderCancel::where('sales_id',$id)->update(['status' => $input['status']]);
            $cancelRec              =   SalesOrderCancel::where('sales_id',$id)->first();
			$salesId = $cancelRec->sales_id;
            if($input['status']         ==  'accepted'){
             $cnl = ['order_status'=>'cancelled','cancel_process' => 2]; 

             //ORDER Notification accepted
             $saleval = SalesOrder::where('id',$cancelRec->sales_id)->first();
             // dd($saleval->payment);
            $from   = 1; 
            $utype  = 1;
            $to_c     = $saleval->cust_id;
            $to_a     = 1;
            $ntype  = 'cancel_order';
            $title  = 'Order has been cancelled and refund initiated';
            $desc   = 'Order #'.$saleval->order_id.' order has been cancelled by customer request and refund initiated';
            $refId  = $id;
            $reflink = 'customer/order/detail';
            $reflink_a = 'admin/sales/cancel/orders';
            $notify_c  = 'customer';
            $notify_a  = 'admin';
            addNotification($from,$utype,$to_c,$ntype,$title,$desc,$refId,$reflink,$notify_c);
            addNotification($from,$utype,$to_a,$ntype,$title,$desc,$refId,$reflink_a,$notify_a);

            //Refund
            // $sales_refund_tbl = SalesOrderRefundPayment::where('sales_id',$saleval->id)->first();
            // if(!$sales_refund_tbl){
            $create_sales_refund_tbl = SalesOrderRefundPayment::create(['ref_id'=>$cancelRec->id,'sales_id'=>$saleval->id,'source'=>'cancel_order','refund_mode'=>1,'total'=>$saleval->g_total,'refund_tax'=>0,'grand_total'=>$saleval->g_total,'updated_platform' => 'odoo'])->id;
            if($saleval->payment->payment_method_id==1){
            $this->refund_to_wallet($saleval,'cancel_order');
            }else{
            $this->refund_to_credit($saleval,'cancel_order');
            }


            //email
            $customer_email=$saleval->customer->custEmail($saleval->customer->email);
           $data['data'] = array("customer_name"=>$saleval->customer->info->first_name,'title'=>'Order Cancelled','message'=>'Your Order:'.$saleval->order_id.' has been cancelled and refund initiated','saleorder_id'=>$saleval->order_id);
                                    $var = Mail::send('emails.cancel_order', $data, function($message) use($data,$customer_email) {
                                    $message->from(getadmin_mail(),geSiteName());    
                                    $message->to($customer_email);
                                   // $message->cc(['aleenaantony1020@gmail.com']); //myjewelleryshopper@gmail.com
                                    $message->subject('Order Cancelled');
                                    });
            // }//if not refunded
           }
            else if($input['status']    ==  'rejected'){ $cnl['cancel_process'] = 3; $this->addCancelResponse($id,$input['comment']); 
            //ORDER Notification rejected
             $saleval = SalesOrder::where('id',$cancelRec->sales_id)->first();
            $from   = 1; 
            $utype  = 1;
            $to_c     = $saleval->cust_id;
            $to_a     = 1;
            $ntype  = 'cancel_order';
            $title  = 'Cancel request has been rejected';
            $desc   = 'Order #'.$saleval->order_id.' cancellation request has been rejected';
            $refId  = $id;
            $reflink = 'customer/order/detail';
            $reflink_a = 'admin/sales/cancel/orders';
            $notify_c  = 'customer';
            $notify_a  = 'admin';
            addNotification($from,$utype,$to_c,$ntype,$title,$desc,$refId,$reflink,$notify_c);
            addNotification($from,$utype,$to_a,$ntype,$title,$desc,$refId,$reflink_a,$notify_a);
        }
            $update                 =   SalesOrder::where('id',$cancelRec->sales_id)->update($cnl);
            $cInsId                 =   SalesOrderCancel::create(['sales_id'=>$id,'seller_id'=>0,'platform'=>"odoo",'created_by'=>1,'role_id'=>2])->id;
                                        $this->addCancelNote($cInsId,$title,$desc);
			//$orders                 =   SalesOrderCancel::where('is_deleted',0);
			

		
        $stHistory                  =   ['sales_id'=>$salesId,'status'=>$input['status'],'created_by'=>1,'role_id'=>2,'platform'     =>"odoo"];
        $stHistory['description']   =   "Order cancelled";    SalesOrderStatusHistory::create($stHistory);
       
        //$data['orders']             =   $orders->orderBy('id','desc')->get();
        if($update>0){
					return array('httpcode'=>'200','status'=>'success','message'=>'Success','data'=>['order' =>$saleorder]);
				}else{
					return array('httpcode'=>'200','status'=>'success','message'=>'Success','data'=>['order' =>$saleorder]);
				}
			}else{
					return array('httpcode'=>'400','status'=>'error','message'=>'Cancel Request Not Found');
			}

		}
	}	
	function refund_to_wallet($sale,$type){
        $refunddata = CustomerWallet_Model::where('source','Cancel Order')->where('user_id',$sale->cust_id)->where('source_id',$sale->id)->first();
        if(!$refunddata)
        {
            $user_id                    =   $sale->cust_id;
        
            
                CustomerWallet_Model::create(['user_id'=>$user_id,'source_id'=>$sale->id,'source'=>'Cancel Order','credit'=>$sale->g_total,'desc'=>$sale->order_id." order cancelled",'is_active'=>1]);
            
            SalesOrder::where('id',$sale->id)->update(['payment_status'=>'refunded']);
            $stHistory                  =   ['sales_id'=>$sale->id,'status'=>'refunded','created_by'=>auth()->user()->id,'role_id'=>auth()->user()->role_id];
             $stHistory['description']   =   $sale->order_id." order cancelled";    
             //SalesOrderStatusHistory::create($stHistory);
        
        }
    }
    function refund_to_credit($sale,$type) {
        $refunddata = CustomerWallet_Model::where('source','Cancel Order')->where('user_id',$sale->cust_id)->where('source_id',$sale->id)->first();
        if(!$refunddata) {
            $user_id                    =   $sale->cust_id;
            $amount  = $sale->g_total;
            $credit_table = (new CustomerCredits)->getTable();
            $credit_log_table = (new CustomerCreditLogs)->getTable();

            $user_data = CustomerCredits::selectRaw("$credit_table.user_id,log.credit_limit,log.credit_days,log.per_purchase,SUM($credit_table.credit)-SUM($credit_table.debit) as balance,SUM($credit_table.debit)-SUM($credit_table.credit) as outstanding,MAX($credit_table.log_id) as log_id")
   ->join("$credit_log_table as log",'log.id','=',"$credit_table.log_id")->where("log.is_deleted",0)->where("$credit_table.user_id",$user_id)->orderBy("$credit_table.id","DESC")->groupBy("$credit_table.user_id")
   ->first();

        $pay_arr = [];
        $pay_arr['user_id'] = $user_id;
        $pay_arr['ref_id'] = $sale->id;
        $pay_arr['log_id'] = $user_data->log_id;
        $pay_arr['credit_limit'] = $user_data->credit_limit;
        $pay_arr['credit_days'] = $user_data->credit_days;
        $pay_arr['credit'] = $amount;
        $pay_arr['per_purchase'] = $user_data->per_purchase;
        $pay_arr['created_by'] = 1;
        $pay_arr['modified_by'] =1;

        $insId      =   CustomerCredits::create($pay_arr)->id;
            SalesOrder::where('id',$sale->id)->update(['payment_status'=>'refunded']);
            $stHistory                  =   ['sales_id'=>$sale->id,'status'=>'refunded','created_by'=>1,'role_id'=>2];
             $stHistory['description']   =   $sale->order_id." order cancelled";    
        }
    }
	
	function addCancelNote($cId,$title,$note){
        SalesOrderCancelNote::create(['cancel_id'=>$cId,'title'=>$title,'note'=>$note,'created_by'=>1,'role_id'=>2]);
    }
	
	public function retunOrder(Request $request, $id){
		$rules      =   array();
        $rules['status']           = 'required|string';
        $rules['model']           = 'required|string';
        $input = $request->all();
		$validator  =   Validator::make($request->all(), $rules);
		if($validator->fails()){
			foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
			return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
		
		} else {
		$input = $request->all();
		$saleval=SalesOrderReturn::where('id',$id)->first();
		if($saleval){
				$post   =   (object)$request->post(); 
				$saleval = SalesOrderReturn::where('id',$id)->first();
				$from   = 1; 
				$utype  = 1;
				$to_c     = $saleval->user_id;
				$to_a     = 1;
				$refId  = $id;
				$reflink = 'customer/order/detail';
				$reflink_a = 'admin/sales/return/orders';
				$notify_c  = 'customer';
				$notify_a  = 'admin';
				//dd($input['model']);
				if($input['model']           ==  'order_return')
				{ 
					if($input['status']         ==  'accepted')
					{
						//dd("hi");
						$update                 =   SalesOrderReturn::where('sales_id',$id)->update(['status' => 'return_accepted','updated_platform' => 'odoo']);
						$salesorder             =  SalesOrderReturn::where('sales_id',$id)->first();
						SalesOrderReturnStatus::create(['sales_id'=>$salesorder->sales_id,'return_id'=>$salesorder->id,'status' => 'return_accepted','updated_platform' => 'odoo']); 
						$ntype  = 'return_accepted';
						$title  = 'Return request accepted';
						$desc   = 'Order #'.$saleval->order->order_id.',return request accepted by the admin';
					}
					else if($input['status']    ==  'rejected')
					{ 
						$update                 =   SalesOrderReturn::where('sales_id',$id)->update(['status' => 'return_rejected','updated_platform' => 'odoo']);
						$salesorder             =  SalesOrderReturn::where('sales_id',$id)->get();
						SalesOrderReturnStatus::create(['sales_id'=>$salesorder->sales_id,'return_id'=>$salesorder->id,'status' => 'return_rejected','updated_platform' => 'odoo']);
						$ntype  = 'return_rejected';
						$title  = 'Return request rejected';
						$desc   = 'Order #'.$saleval->order->order_id.',return request rejected by the admin';
					}
					$orders       =   SalesOrderReturn::where('is_deleted',0);
					addNotification($from,$utype,$to_c,$ntype,$title,$desc,$refId,$reflink,$notify_c);
					addNotification($from,$utype,$to_a,$ntype,$title,$desc,$refId,$reflink_a,$notify_a);
					$message="Return request ".$input['status'] ;
				}
				else if($post->model             ==  'order_shipment')
				{ 
					if($input['status']         ==  'accepted')
					{
						if($saleval->return_type=="replace"){
							
						$latestorder_ids=1;
					$latestOrder = SaleOrder::orderBy('created_at','DESC')->first();
					
					if($latestOrder)
					{
						$latestorder_ids = $latestOrder->id;
					}
				   
					$saleorder_id = date('y').date('m').str_pad($latestorder_ids + 1, 6, "0", STR_PAD_LEFT);
			
							
							$create_saleorder = SaleOrder::create(['org_id' => 1,
						'parent_sale_id'  =>$saleval->sales_id,
						'order_id'        => $saleorder_id,
						'cust_id'         => $saleval->user_id,
						'branch_id'         => $saleval->order->branch_id,
						//'seller_id'       => $rows['seller_id'],
						'total'           =>  $saleval->amount,
						'discount'        => 0,
						'tax'             => 0,
						'shiping_charge'  => 0,
						'packing_charge'  => 0,
						'wallet_amount'   => 0,
						'g_total'         => $saleval->amount,
						//'ecom_commission' => $rows['commission'],
						'discount_type'   => 0,  
						'coupon_id'       => 0,
						'order_status'    => 'accepted',
						'payment_status'  => 'paid',
						'shipping_status' => 'pending',
						'cancel_process'  => 0,
						'cust_message'    => "",    
						'created_at'    =>date("Y-m-d H:i:s"),
						'updated_at'    =>date("Y-m-d H:i:s")]);
						$sale_id  = $create_saleorder->id;
						
						
						
						$saleorder_payment = SalesOrderPayment::create(['org_id' => 1,
						'sales_id'         => $sale_id,
						'payment_method_id'=> $saleval->order->payment->payment_method_id,
						'payment_type'     => $saleval->order->payment->payment_type,
						'transaction_id'   => $saleval->order->payment->transaction_id,
						'payment_data'     => "",
						'amount'           => $saleval->amount,
						'payment_status'   => "success"]);
						
						$replaced_product=SaleorderItems::where('id',$saleval->sales_item_id)->where('is_deleted',0)->first();
						//if()
						$create_saleorder = SaleorderItems::create([
						'sales_id'        => $sale_id,
						'parent_id'       => $sale_id,
						'prd_id'          => $prod_data->product_id,
						'prd_type'        => $prod_data->product_type,
						'prd_name'        => $product_name,
						'price'           => $actual_price,
						'qty'             => $prod_data->quantity,
						'total'           => $tot_actual,
						'discount'        => 0,
						'tax'             => $total_tax_amount,
						'row_total'       => $tot_actual + $total_tax_amount,
						'coupon_id'       => '', 
						'created_at'    =>date("Y-m-d H:i:s"),
						'updated_at'    =>date("Y-m-d H:i:s"),
						'is_deleted'    =>0]);  
						 
				   $prd_stock_update = PrdStock::create([
														 'type'       =>'destroy',
														 'prd_id'     => $prod_data->product_id,
														 'qty'        => $prod_data->quantity,
														 'rate'       => $actual_price,
														 'created_by' => $user_id,
														 'sale_id'    => $sale_id,
														 'created_at' => date("Y-m-d H:i:s"),
														 'updated_at' => date("Y-m-d H:i:s")
														 ]); 
										
						$insert_address = SalesOrderAddress::create(['sales_id' => $sale_id,
						'order_id'        => $saleorder_id,
						'cust_id'         => $user_id,
						'ref_addr_id'     => $input['address_id'],
						'addr_id'         => $addr_list->usr_addr_typ_id,
						'name'            => $addr_list->name,
						'phone'           => $addr_list->phone,
						'email'           => $user_email,
						'address1'        => $addr_list->address_1,
						'address2'        => $addr_list->address_2,
						'zip_code'        => $addr_list->pincode,
						'city'            => $addr_list->city_id,
						'state'           => $addr_list->state_id,
						'country'         => $addr_list->country_id,  
						'latitude'        => $addr_list->latitude,
						'longitude'       => $addr_list->longitude,
						's_addr_id'       => $addr_list->usr_addr_typ_id,
						's_name'          => $addr_list->name,
						's_phone'         => $addr_list->phone,
						's_email'         => $user_email,
						's_address1'      => $addr_list->address_1,
						's_address2'      => $addr_list->address_2,
						's_zip_code'      => $addr_list->pincode,
						's_city'          => $addr_list->city_id,
						's_state'         => $addr_list->state_id,
						's_country'       => $addr_list->country_id,  
						's_latitude'      => $addr_list->latitude,
						's_longitude'     => $addr_list->longitude]);
					

						
						 $update                 =   SalesOrderReturn::where('sales_id',$post->id)->update(['status' => 'order_initiated','updated_platform' => 'odoo']);
						$salesorder             =  SalesOrderReturn::where('sales_id',$post->id)->first();
						SalesOrderReturnStatus::create(['sales_id'=>$salesorder->sales_id,'return_id'=>$post->id,'status' => 'refund_initiated','updated_platform' => 'odoo']); 
						$ntype  = 'order_initiated';
						$title  = 'order initiated for return & replacement';
						$desc   = 'Order #'.$saleorder_id.',order initiated for return & replacement  by the admin';

						
						}else{
						$update                 =   SalesOrderReturn::where('sales_id',$id)->update(['status' => 'refund_initiated']);
						$salesorder             =  SalesOrderReturn::where('sales_id',$id)->first();
						SalesOrderReturnStatus::create(['sales_id'=>$salesorder->sales_id,'return_id'=>$salesorder->id,'status' => 'refund_initiated','updated_platform' => 'odoo']); 
						$ntype  = 'refund_initiated';
						$title  = 'Return order refund initiated';
						$desc   = 'Order #'.$saleval->order->order_id.',return order refund initiated by the admin';
						}
					
					}
					else if($input['status']    ==  'rejected')
					{ 
						$update                 =   SalesOrderReturn::where('sales_id',$id)->update(['status' => 'shipment_rejected','updated_platform' => 'odoo']);
						$salesorder             =  SalesOrderReturn::where('sales_id',$id)->first();
						SalesOrderReturnStatus::create(['sales_id'=>$salesorder->sales_id,'return_id'=>$salesorder->id,'status' => 'shipment_rejected','updated_platform' => 'odoo']);
						$ntype  = 'shipment_rejected';
					$title  = 'Return order shipment rejected';
					$desc   = 'Order #'.$saleval->order->order_id.',return order shipment rejected by the admin';
					}
					$orders       =   SalesOrderReturn::where('is_deleted',0);

					addNotification($from,$utype,$to_c,$ntype,$title,$desc,$refId,$reflink,$notify_c);
					addNotification($from,$utype,$to_a,$ntype,$title,$desc,$refId,$reflink_a,$notify_a);
				    $message="Shipment ".$input['status'] ;
				}
				return array('httpcode'=>'200','status'=>'success','message'=>$message,'data'=>['order' =>$saleval]);
			}else{
					return array('httpcode'=>'400','status'=>'error','message'=>'Return Request Not Found');
			}
		}
		
	}
	public function retunOrderList(){ 
		$datas = SalesOrderReturn::get();			
			if($datas){
				$sales=[];
				foreach($datas as $data){
				$cat['sales_id'] = $data->id;
				$cat['return_type'] = $data->return_type;
				$cat['type'] = $data->type;
				$cat['amount'] = $data->amount;
				$cat['prd_id'] = $data->prd_id;
				$cat['qty'] = $data->qty;
				$cat['reason'] = $data->reason;
				$cat['status'] = $data->status;
				
				
				$sales[]=$cat;
				}
				return array('httpcode'=>'200','status'=>'success','message'=>'Success','sales'=>$sales);

			}else{
				return array('httpcode'=>'400','status'=>'error','message'=>"Not Found");

			}
	}
	
}