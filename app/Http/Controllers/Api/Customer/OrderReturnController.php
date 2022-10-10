<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;
use App\Models\Wishlist;
use App\Models\UsrWishlist;
use App\Models\Product;
use App\Models\CmsContent;
use App\Models\PrdReview;
use App\Models\PrdPrice;
use App\Models\ProductImage;
use App\Models\PrdOffer;
use App\Models\PrdShock_Sale;
use App\Models\SaleOrder;
use App\Models\SalesOrder;
use App\Models\SaleorderItems;
use App\Models\SalesOrderCancel;
use App\Models\SalesOrderCancelNote;
use App\Models\CustomerMaster;
use App\Models\CustomerInfo;
use App\Models\CustomerAddress;
use App\Models\CustomerTelecom;
use App\Models\CustomerSecurity;
use App\Models\CustomerAddressType;
use App\Models\CustomerLogin;
use App\Models\SalesOrderReturn;
use App\Models\SalesOrderReturnStatus;
use App\Models\CouponHist;
use App\Models\Prd_Recent_View;
use App\Models\CustomerWallet_Model;
use App\Models\UserVisit;
use App\Models\SalesOrderAddress;
use App\Models\SellerInfo;
use App\Models\SalesOrderPayment;
use App\Models\SalesOrderShippingStatus;
use App\Models\UsrNotification;
use App\Models\SalesOrderRefundPayment;
use App\Models\SalesOrderReturnShipment;
use App\Models\SalesReturnReason;
use App\Models\Auction;
use App\Models\AuctionHist;
use App\Models\AssociatProduct;
use App\Models\SettingOther;
use App\Models\MetalRates;
use Carbon\Carbon;
use App\Rules\Name;
use Validator;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use App\Models\AssignedFields;

use App\Models\InviteSave;
use App\Models\InviteSaveLog;
use App\Models\ParentSale;
use App\Models\Chat;
use App\Models\Store;
use Mail;
use Illuminate\Support\Str;

class OrderReturnController extends Controller
{
    public function reasons(Request $request)
    {
        $reasons = SalesReturnReason::get();
        $rsn=[];
        foreach($reasons as $row)
        {
            $list['id'] =$row->id;
            $list['reason']=$row->reason;
            $rsn[]=$list;
        }
        return ['httpcode'=>200,'status'=>'success','message'=>'Return Reason List','data'=>$rsn]; 
    }
	
	
	
	    public function replace_request(Request $request){
			
        if($user = validateToken($request->post('access_token')))
        {
           // dd($user['user_id']);
			$user_id    =   $user['user_id'];
            $formData   =   $request->all(); 
            $rules      =   array();
            $rules['sale_id']      = 'required|numeric';
			$rules['productid_and_quantity']    = 'required';
			$rules['all']    = 'required|numeric';
            $rules['reason_id']    = 'required|string';
            $rules['reason']       = 'required_if:reason_id,==,6';
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else{ 
                    $valid_reason = SalesReturnReason::where('id',$formData['reason_id'])->first();
                    if(!$valid_reason)
                    {
                      return array('httpcode'=>'400','status'=>'error','message'=>'Enter valid reason','data'=>array('errors' =>'Enter valid reason'));  
                    }
					$product_and_quantity=json_decode($formData['productid_and_quantity']);
                   // dd($product_and_quantity);
					$sales =  SalesOrder::where('cust_id',$user_id)->where('id',$formData['sale_id'])->where('order_status','delivered')->whereIn('payment_status',['success','paid'])->first(); 
                    if($sales)
                    {
                        $hourdiff = round((strtotime(date('Y-m-d H:i:s')) - strtotime($sales->created_at))/3600);
						$returntime = SettingOther::where('is_active',1)->where('is_deleted',0)->orderBy('id','DESC')->first()->return_period;
						if($hourdiff > $returntime)
                        {
                          return array('httpcode'=>'400','status'=>'error','message'=>'Return period expired','data'=>['message' =>'Return period expired!']);  
                        }
						if($formData['all']==1){
							$orderexist=SalesOrderReturn::where('sales_id',$formData['sale_id'])->exists();
							if($orderexist){
								return array('httpcode'=>'400','status'=>'error','message'=>'Not Found','data'=>['message' =>'Return & Replacement already intiated']);
							}else {
								
								$amt = $sales->g_total;
								$orderreturn = SalesOrderReturn::create(['sales_id' => $formData['sale_id'],'return_type' => 'replace','type'=>'order','user_id' => $user_id,'amount' =>  $amt,'reason_id'=>$formData['reason_id'],'reason' =>  $valid_reason->reason,'desc' =>  $formData['reason'],'status'=>"return_initiated"]);
                                SalesOrderReturnStatus::create(['sales_id' => $formData['sale_id'],
                                'return_id' => $orderreturn->id,
                                'status' => 'return_initiated']);
                                return array('httpcode'=>'200','status'=>'success','message'=>'Request sent','data'=>['message' =>'Return request intiated successfully','return_id' =>$orderreturn->id]);

								}
						}
                        else{
							$orderexist=SalesOrderReturn::where('sales_id',$formData['sale_id'])->exists();
							if($orderexist){
							return array('httpcode'=>'400','status'=>'error','message'=>'Not Found','data'=>['message' =>'Return & Replacement already intiated for this order']);
							}
							else{
							foreach ($product_and_quantity as $key => $value) {
							
							$exist=SalesOrderReturn::where('sales_id',$formData['sale_id'])->where('prd_id',$key)->exists();
								if($exist){
								return array('httpcode'=>'400','status'=>'error','message'=>'Not Found','data'=>['message' =>'Return & Replacement already intiated']);
								} else {
								$prds = 	SaleorderItems::where('sales_id',$formData['sale_id'])->where('prd_id',$key)->first();
								$amt = $prds->price * $prds->qty; 
								$orderreturn = SalesOrderReturn::create(['sales_id' => $formData['sale_id'],'return_type' => 'replace','type'=>"item",'user_id' => $user_id,'sales_item_id' => $prds->id,'prd_id' => $key,'qty' => $value,'amount' =>  $amt,'reason_id'=>$formData['reason_id'],'reason' =>  $valid_reason->reason,'desc' =>  $formData['reason'],'status'=>"return_initiated"]);
								 SalesOrderReturnStatus::create(['sales_id' => $formData['sale_id'],
										'return_id' => $orderreturn->id,
										'status' => 'return_initiated']);

								}
							}							
						}
						return array('httpcode'=>'200','status'=>'success','message'=>'Request sent','data'=>['message' =>'Return request intiated successfully']);
                      } 
					}else {
                        return array('httpcode'=>'400','status'=>'error','message'=>'Not Found','data'=>['message' =>'Order not found!']);
                }
        }
    }else{ return invalidToken(); }
		}
		
		public function refund_request(Request $request){
			
        if($user = validateToken($request->post('access_token')))
        {
           // dd($user['user_id']);
			$user_id    =   $user['user_id'];
            $formData   =   $request->all(); 
            $rules      =   array();
            $rules['sale_id']      = 'required|numeric';
			$rules['productid_and_quantity']    = 'required';
			$rules['all']    = 'required|numeric';
            $rules['reason_id']    = 'required|string';
            $rules['reason']       = 'required_if:reason_id,==,6';
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else{ 
                    $valid_reason = SalesReturnReason::where('id',$formData['reason_id'])->first();
                    if(!$valid_reason)
                    {
                      return array('httpcode'=>'400','status'=>'error','message'=>'Enter valid reason','data'=>array('errors' =>'Enter valid reason'));  
                    }
					$product_and_quantity=json_decode($formData['productid_and_quantity']);
                   // dd($product_and_quantity);
					$sales =  SalesOrder::where('cust_id',$user_id)->where('id',$formData['sale_id'])->where('order_status','delivered')->whereIn('payment_status',['success','paid'])->first(); 
                    if($sales)
                    {
                        $hourdiff = round((strtotime(date('Y-m-d H:i:s')) - strtotime($sales->created_at))/3600);
						$returntime = SettingOther::where('is_active',1)->where('is_deleted',0)->orderBy('id','DESC')->first()->return_period;
						if($hourdiff > $returntime)
                        {
                          return array('httpcode'=>'400','status'=>'error','message'=>'Return period expired','data'=>['message' =>'Return period expired!']);  
                        }
						if($formData['all']==1){
							$orderexist=SalesOrderReturn::where('sales_id',$formData['sale_id'])->exists();
							if($orderexist){
								return array('httpcode'=>'400','status'=>'error','message'=>'Not Found','data'=>['message' =>'Return & Replacement already intiated']);
							}else {
								
								$amt = $sales->g_total;
								$orderreturn = SalesOrderReturn::create(['sales_id' => $formData['sale_id'],'return_type' => 'refund','type'=>'order','user_id' => $user_id,'amount' =>  $amt,'reason_id'=>$formData['reason_id'],'reason' =>  $valid_reason->reason,'desc' =>  $formData['reason'],'status'=>"return_initiated"]);
                                SalesOrderReturnStatus::create(['sales_id' => $formData['sale_id'],
                                'return_id' => $orderreturn->id,
                                'status' => 'return_initiated']);
                                return array('httpcode'=>'200','status'=>'success','message'=>'Request sent','data'=>['message' =>'Return request intiated successfully','return_id' =>$orderreturn->id]);

								}
						}
                        else{
							$orderexist=SalesOrderReturn::where('sales_id',$formData['sale_id'])->exists();
							if($orderexist){
							return array('httpcode'=>'400','status'=>'error','message'=>'Not Found','data'=>['message' =>'Return & Replacement already intiated for this order']);
							}
							else{
							foreach ($product_and_quantity as $key => $value) {
							
							$exist=SalesOrderReturn::where('sales_id',$formData['sale_id'])->where('prd_id',$key)->exists();
								if($exist){
								return array('httpcode'=>'400','status'=>'error','message'=>'Not Found','data'=>['message' =>'Return & Replacement already intiated']);
								} else {
								$prds = 	SaleorderItems::where('sales_id',$formData['sale_id'])->where('prd_id',$key)->first();
								$amt = $prds->price * $prds->qty; 
								$orderreturn = SalesOrderReturn::create(['sales_id' => $formData['sale_id'],'return_type' => 'refund','type'=>"item",'user_id' => $user_id,'sales_item_id' => $prds->id,'prd_id' => $key,'qty' => $value,'amount' =>  $amt,'reason_id'=>$formData['reason_id'],'reason' =>  $valid_reason->reason,'desc' =>  $formData['reason'],'status'=>"return_initiated"]);
								 SalesOrderReturnStatus::create(['sales_id' => $formData['sale_id'],
										'return_id' => $orderreturn->id,
										'status' => 'return_initiated']);

								}
							}							
						}
						return array('httpcode'=>'200','status'=>'success','message'=>'Request sent','data'=>['message' =>'Return request intiated successfully']);
                      } 
					}else {
                        return array('httpcode'=>'400','status'=>'error','message'=>'Not Found','data'=>['message' =>'Order not found!']);
                }
        }
    }else{ return invalidToken(); }
		}
		
	
	
    public function return_request(Request $request)
    {
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
            $formData   =   $request->all(); 
            $rules      =   array();
            $rules['sale_id']      = 'required|numeric';
            $rules['quantity']     = 'nullable|numeric|min:1';
            $rules['product_id']   = 'nullable|numeric';
            $rules['reason_id']    = 'required|string';
            $rules['reason']       = 'required_if:reason_id,==,6';
            // $rules['message']      = 'required|string';
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 
                    $valid_reason = SalesReturnReason::where('id',$formData['reason_id'])->first();
                    if(!$valid_reason)
                    {
                      return array('httpcode'=>'400','status'=>'error','message'=>'Enter valid reason','data'=>array('errors' =>'Enter valid reason'));  
                    }

                    if($formData['quantity'] && $formData['product_id'])
                    {
                        $type = 'qty';
                    }
                    elseif(!$formData['quantity'] && $formData['product_id'])
                    {
                        $type='item';
                    }
                    else
                    {
                        $type='order';
                    }
                    $sales =  SalesOrder::where('cust_id',$user_id)->where('id',$formData['sale_id'])->where('order_status','delivered')->whereIn('payment_status',['success','paid'])->first(); 
                    if($sales)
                    {
                        $hourdiff = round((strtotime(date('Y-m-d H:i:s')) - strtotime($sales->created_at))/3600);
                        $refundtime = SettingOther::where('is_active',1)->where('is_deleted',0)->orderBy('id','DESC')->first()->return_period;
                        if($hourdiff > $refundtime)
                        {
                          return array('httpcode'=>'400','status'=>'error','message'=>'Return period expired','data'=>['message' =>'Return period expired!']);  
                        }
                        if($formData['product_id']){
                        $prds =  SaleorderItems::where('sales_id',$formData['sale_id'])->where('prd_id',$formData['product_id'])->first();
                        if($prds)
                        {
                            if($formData['quantity'] > $prds->qty)
                            {
                                return array('httpcode'=>'400','status'=>'error','message'=>'Quantity exceeds','data'=>['message' =>'Quantity exceeds!']);
                            }
                            else
                            {
                                $amt = $prds->price * $prds->qty; 
                                $orderreturn = SalesOrderReturn::create(['sales_id' => $formData['sale_id'],'type'=>$type,'user_id' => $user_id,'sales_item_id' => $prds->id,'prd_id' => $formData['product_id'],'qty' => $formData['quantity'],'amount' =>  $amt,'reason_id'=>$formData['reason_id'],'reason' =>  $valid_reason->reason,'desc' =>  $formData['reason'],'issue_item'=>$formData['issue_item'],'status'=>"return_initiated"]);
                                SalesOrderReturnStatus::create(['sales_id' => $formData['sale_id'],
                                'return_id' => $orderreturn->id,
                                'status' => 'return_initiated']);
                                return array('httpcode'=>'200','status'=>'success','message'=>'Request sent','data'=>['message' =>'Return request initated successfully','return_id' =>$orderreturn->id]);
                            }
                       
                        }
                        else
                        {
                             return array('httpcode'=>'400','status'=>'error','message'=>'Not Found','data'=>['message' =>'Product not found!']);
                        }
                      }
                      else
                      {
                        $amt = $sales->g_total;
                        $orderreturn = SalesOrderReturn::create(['sales_id' => $formData['sale_id'],'type'=>$type,'user_id' => $user_id,'amount' =>  $amt,'reason_id'=>$formData['reason_id'],'reason' =>  $valid_reason->reason,'desc' =>  $formData['reason'],'status'=>"return_initiated"]);
                                SalesOrderReturnStatus::create(['sales_id' => $formData['sale_id'],
                                'return_id' => $orderreturn->id,
                                'status' => 'return_initiated']);
                                return array('httpcode'=>'200','status'=>'success','message'=>'Request sent','data'=>['message' =>'Return request initated successfully','return_id' =>$orderreturn->id]);
                      }
                    }
                       
                    else
                    {
                        return array('httpcode'=>'400','status'=>'error','message'=>'Not Found','data'=>['message' =>'Order not found!']);
                    }
                }
        }else{ return invalidToken(); }
    }

    public function return_shipment(Request $request)
    {
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
            $formData   =   $request->all(); 
            $rules      =   array();
            $rules['return_id']            = 'required|numeric';
            $rules['shipment_detail']      = 'required';
            $rules['shipment_bill']        = 'required';
            //$rules['refund_mode']          = 'required|numeric';
            // if($request->refund_mode == 2)
            // {
            //     $rules['bank_name']        = 'required|string';
            //     $rules['account_number']   = 'required|string';
            //     $rules['branch_name']      = 'required|string';
            //     $rules['ifsc_code']        = 'required|string';
            // }
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 
                    $return = SalesOrderReturn::where('id',$formData['return_id'])->where('is_deleted',0)->first();
                    if($return)
                    {
                        if($request->hasFile('shipment_bill'))
                        {
                        $file=$request->file('shipment_bill');
                        $extention=$file->getClientOriginalExtension();
                        $filename='bill_'.$formData['return_id'].'.'.$extention;
                        $file->move(('uploads/storage/app/public/shipment_bills/'),$filename);
                        }
                        else
                        {
                            $filename='';
                        }
                        $refundcharge = SettingOther::where('is_active',1)->where('is_deleted',0)->orderBy('id','DESC')->first();
                        SalesOrderReturn::where('id',$formData['return_id'])->update(['status'=>'shipment_initiated']);
                        SalesOrderReturnShipment::create(['return_id' => $formData['return_id'],'description' => $formData['shipment_detail'],'document' => 'app/public/shipment_bills/'.$filename]);
                        $tot = $return->amount;
                        $per = $return->amount*($refundcharge->refund_deduction/100);
                        $gtot = $tot - $per;
                        SalesOrderRefundPayment::create([
                         'ref_id' => $formData['return_id'],'sales_id' => $return->sales_id,'source' =>'return','refund_mode' => 1,'total' => $tot,'refund_tax' => $refundcharge->refund_deduction,'grand_total' => $gtot]);

                         SalesOrderReturnStatus::create(['sales_id' => $return->sales_id,
                                'return_id' => $formData['return_id'],
                                'status' => 'shipment_initiated']);

                        return array('httpcode'=>'200','status'=>'success','message'=>'Shipment submitted','data'=>['message' =>'Your shipment details sent successfully!']);
                    }
                    else
                    {
                        return array('httpcode'=>'400','status'=>'error','message'=>'Not Found','data'=>['message' =>'Return request not found!']);
                    }
                }
        }else{ return invalidToken(); }
    }
}
