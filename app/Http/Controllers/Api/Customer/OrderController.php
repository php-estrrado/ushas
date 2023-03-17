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
use App\Models\SalesOrderItemOption;
use App\Models\Subcategory;
use App\Models\Store;
use App\Models\SellerReview;
use App\Models\SaleOrder;
use App\Models\SaleorderItems;
use App\Models\SalesOrderAddress;
use App\Models\SalesOrderPayment;
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
use App\Models\OrderStatus;
use App\Models\PrdOffer;
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
use App\Models\CustomerPoints;
use App\Models\SellerAddress;
use App\Models\SalesOrderShippingStatus;
use App\Models\SalesOrderStatusHistory;
use App\Models\InviteSave;

use App\Models\crm\{CrmAssortmentMaster, CrmChildProductsMaster, CrmCustomerType,CrmPartAssortmentDetails,
CrmPartAssortmentMaster,CrmProduct,CrmSalesPriceList,CrmSalesPriceType,CrmSize,CrmBranch,CrmCompany};

class OrderController extends Controller
{
    
function get_cart_ofr_products($prd_id,$cart_id,$qty,$lang,$cat_id,$sub_id){
        $data     =   [];
        
        $prod_data       =   Product::where('is_active',1)->where('is_deleted',0)->where('id',$prd_id)
        ->when($cat_id,function ($q,$cat_id) {
                            $q->where('category_id', $cat_id);
                        })
        ->when($sub_id,function ($q,$sub_id) {
                            $q->where('sub_category_id', $sub_id);
                        })
        
        ->first();
            if($prod_data)   { 
                    $prd_list['cart_id']=$cart_id;   
                    $prd_list['product_id']=$prod_data->id;
                    $prd_list['product_name']=$this->get_content($prod_data->name_cnt_id,$lang);
                    $prd_list['quantity']=$qty;
                    $prd_list['category_id']=$prod_data->category_id;
                    $prd_list['category_name']=$this->get_content($prod_data->category->cat_name_cid,$lang);
                    $prd_list['subcategory_id']=$prod_data->sub_category_id;
                    $prd_list['subcategory_name']=$this->get_content($prod_data->subCategory->sub_name_cid,$lang);
                    if($prod_data->brand_id)
                    {
                        $prd_list['brand_id']=$prod_data->brand_id;
                        $prd_list['brand_name']=$this->get_content($prod_data->brand->brand_name_cid,$lang);
                    }
                    if($prod_data->prdPrice){
                    $actual_price =$prod_data->prdPrice->price;
                    
                    $prd_list['unit_actual_price']=round($prod_data->prdPrice->price,2);
                    $tot_actual=$actual_price*$qty;
                    $prd_list['total_actual_price']=round($tot_actual,2);
                    $tax_amt=$prod_data->getTaxValue($prod_data->tax_id);
                    $total_tax_amount = $tot_actual * ($tax_amt/100);
                    $prd_list['total_tax_value']=$total_tax_amount;
                    }else{
                    
                    $prd_list['unit_actual_price']=false;
                    $prd_list['total_actual_price']=false;
                    $prd_list['total_tax_value']=false;

                         
                    }
                    
            $type= $prod_data->product_type;
            $login=1;           
            $spec_offr = $this->get_offer_price($prd_id,$type,$login,$qty);
            if($spec_offr){
                foreach ($spec_offr as $item) {
                            foreach ($item as $key => $value) {
                                $prd_list[$key] = $value;
                            } 
                        }
            }

            $prd_list['is_out_of_stock']=$prod_data->is_out_of_stock;
                    $data             =   $prd_list;
             }
            // else{ $data     =   []; } 
             return $data;
        
    }


  
    
public function apply_coupon($user_id,$id)
    {   
                     

        $query = Coupon::query();
        $avail = Coupon::where('id',$id)->first();
        $discount = 0;
        if($avail){
        $category= $avail->category_id;
       
        if($avail->category_id > 0)
        {
            $cat_id=$category;
        }
        else
        {
            $cat_id='';
        }
        $subcategory = $avail->subcategory_id;
        if($subcategory!=0)
        {
            $sub_id =$subcategory;
        }
        else
        {
            $sub_id = '';
        }
        //dd($avail);

        $cart = Product::join('usr_cart_item','usr_cart_item.product_id','=','prd_products.id')
                        ->join('usr_cart','usr_cart_item.cart_id','=','usr_cart.id')
                        ->where('prd_products.is_active',1)->where('prd_products.is_deleted',0)
                        ->when($cat_id,function ($q,$cat_id) {
                            $q->where('prd_products.category_id', $cat_id);
                        })
                        ->when($sub_id,function ($q,$sub_id) {
                            $q->where('prd_products.sub_category_id', $sub_id);
                        })
                        
                        ->where('usr_cart.user_id',$user_id)    
                        ->where('usr_cart.is_active',1)
                        ->where('usr_cart.is_deleted',0)
                        ->where('usr_cart_item.is_active',1)
                        ->where('usr_cart_item.is_deleted',0)
                        ->get();
           $no_prds = count($cart); 
          
           if($avail->validity_type == "range")
        { 
 
            $current_date=date('Y-m-d');
            $range = $query->whereDate('valid_from','<=',$current_date)->whereDate('valid_to','>=',$current_date)->where('id',$id)->first();
           if($no_prds>0){
                foreach($cart as $rows)
           
            {
               // $products[] = $this->get_cart_products($rows->product_id,$rows->cart_id,$rows->quantity,$lang);
                $products[] = $this->get_cart_ofr_products($rows->product_id,$rows->cart_id,$rows->quantity,$lang="",$cat_id,$sub_id);
               // dd($products);
            }   
            $filter = array_filter($products);
            $tot_tax =0;
            $total_cost =0;
            if(count($filter)>0)
            {
                foreach($filter as $value)
                {
                    $tot_tax += $value['total_tax_value'];
                    if($value['total_discount_price']!=0)
                    {
                     $total_cost +=(int)$value['total_discount_price'];    
                    }
                    else
                    {
                      $total_cost +=(int)$value['total_actual_price'];    
                    }
                }
            }
            $sale =SaleOrder::where('order_status','delivered')->where('cust_id',$user_id)->count();
            $sale_amt =SaleOrder::where('order_status','delivered')->where('cust_id',$user_id)->sum('total'); 

             //dd($sale_amt);  
            if($range->purchase_type == "number" && $range->ofr_min_amount <=$total_cost && $sale >= $range->purchase_number)
            {
               if($range->ofr_value_type=="percentage")
               {
                    $discount = $total_cost * ($range->ofr_value/100);
                    $grand = $total_cost - $discount;
                    $grand_tot = number_format($grand + $tot_tax,2);
                    
               }
               else
               {
                    $discount = $range->ofr_value;
                    $grand = $total_cost - $discount;
                    $grand_tot = number_format($grand + $tot_tax,2);
               }
               
                return $discount;
            }
            else if($range->purchase_type == "amount" && $range->ofr_min_amount <=$total_cost && $sale_amt >= $range->purchase_amount)
            {
            //  dd($total_cost);
                if($range->ofr_value_type=="percentage")
               {
                    $discount = $total_cost * ($range->ofr_value/100);
                   // dd($total_cost);
                    $grand = $total_cost - $discount;
                    $grand_tot = number_format($grand + $tot_tax,2);

               }
               else
               {
                    $discount = $range->ofr_value;
                    $grand = $total_cost - $discount;
                    $grand_tot = number_format($grand + $tot_tax,2);
               }
                return $discount;
            }
            else{ return 0;}

          }
          //if no prd in cart
          else
          {
             return 0;
          }
        }elseif($avail->validity_type == "days")
        {
            $created_date=$avail->created_at;
            $valid_date=$avail->created_at->addDays($avail->valid_days);
            $current_date=Carbon::now();
            //$diff_in_days = $current_date->diffInDays($valid_date);
            $validity =$valid_date->gte($current_date);
           

            $range = $query->where('ofr_code',$input['coupon_code'])->first();
            
           if($no_prds>0 && $validity==1){
                foreach($cart as $rows)
           
            {
                $products[] = $this->get_cart_ofr_products($rows->product_id,$rows->cart_id,$rows->quantity,$lang="",$cat_id,$sub_id,$seller);
            }   

            $filter = array_filter($products);
            $tot_tax =0;
            $total_cost =0;
            if(count($filter)>0)
            {
                foreach($filter as $value)
                {
                    $tot_tax += $value['total_tax_value'];
                    if($value['total_discount_price']==0)
                    {
                     $total_cost +=(int)$value['total_discount_price'];    
                    }
                    else
                    {
                      $total_cost +=(int)$value['total_actual_price'];    
                    }
                }
            }
            $sale =SaleOrder::where('order_status','delivered')->where('cust_id',$user_id)
             ->when($seller,function ($q,$seller) {
                            $q->where('seller_id', $seller);})
             ->count();
            $sale_amt =SaleOrder::where('order_status','delivered')->where('cust_id',$user_id)
             ->when($seller,function ($q,$seller) {
                            $q->where('seller_id', $seller);
                            })
             ->sum('total'); 

             // echo $sale_amt;
             // die;
            if($range->purchase_type == "number" && $range->ofr_min_amount >=$total_cost && $sale >= $range->purchase_number)
            {
               if($range->ofr_value_type=="percentage")
               {
                    $discount = $total_cost * ($range->ofr_value/100);
                    $grand = $total_cost - $discount;
                    $grand_tot = number_format($grand + $tot_tax,2);

               }
               else
               {
                    $discount = $range->ofr_value;
                    $grand = $total_cost - $discount;
                    $grand_tot = number_format($grand + $tot_tax,2);
               }
                //Coupon details
                    $coupon_details['coupon_id']=$range->id;
                    $coupon_details['title']=$this->get_content($range->cpn_title_cid,$lang);
                    $coupon_details['desc']=$this->get_content($range->cpn_desc_cid,$lang);
                    $coupon_details['offer']=$discount." ".$range->ofr_type;
                    return $discount;
                 
            }
            else if($range->purchase_type == "amount" && $range->ofr_min_amount >=$total_cost && $sale_amt >= $range->purchase_amount)
            {
                  return $discount;
            }
            

          } //if no prd in cart
          
        }

        }
       
         return $discount;                

     }
    
    public function placeorder(Request $request)
    {
        if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
        $user_id = $user['user_id'];
        $user_email = $user['email'];
        $lang=$request->lang_id;
        
        
        $validator=  Validator::make($request->all(),[
            'access_token'          => ['required'],
            'e_money_amt'           => ['required'],
            'is_coupon'             => ['nullable','boolean'],
            'coupon_id'             => ['nullable','numeric'],
            'discount_type'             => ['nullable','string',Rule::in(['discount', 'cashback'])],
            'total_amt'             => ['required','numeric'],
            'discount_amt'          => ['required','numeric'],
            'is_reward'             => ['nullable','numeric'],
            'reward_amt'            => ['nullable'],
            'reward_id'             => ['nullable','numeric'],
            'payment_type'          => ['required'],
            'address_id'            => ['required','numeric'],
            'use_points'            => ['required','in:0,1'],

        ]);
        $input = $request->all();

        if ($validator->fails()) 
        {    
          return ['httpcode'=>400,'status'=>'error','message'=>'Invalid parameters','data'=>['errors'=>$validator->messages()]];
        }else {
            $access_token = $request->access_token;
            $user_logins = DB::table('usr_logins')->where('access_token',$access_token)->first();

            $user_id = $user_logins->user_id;

            if($input['payment_type']==2)
            {
                $credits_log = CustomerCreditLogs::where('user_id',$user_id)->where('is_deleted',0)->first();
                $crd_updated = $credits_log->updated_at;

                $validity = Carbon::createFromFormat('Y-m-d H:i:s',$crd_updated);
                $crd_days = $credits_log->credit_days;

                $validity_date = $validity->addDays($crd_days);

                $current_date = Carbon::now();

                if($current_date > $validity_date)
                {
                    return ['httpcode'=>400,'status'=>'error','message'=>'Credit Vality Date Expired !'];
                }
            }

            if($input['payment_type']==2)
            {
                $credits_log = CustomerCreditLogs::where('user_id',$user_id)->where('is_deleted',0)->first();
                $purchase_limit = $credits_log->per_purchase;
                $total_purchase_amount = $input['total_amt'];

                if(($total_purchase_amount > $purchase_limit) && ($purchase_limit != 0))
                {
                    return ['httpcode'=>400,'status'=>'error','message'=>'Total Amount is greater than Purchase Limit !'];
                }
            }

            if($input['use_points'] == 1)
            {
                $bal_points = CustomerPoints::getbalancePoints($user_id);

                if($bal_points < $request->use_points)
                {
                    return ['httpcode'=>400,'status'=>'error','message'=>'Check points Balance !'];
                }
            }

        $carts = Product::join('usr_cart_item','prd_products.id','=','usr_cart_item.product_id')
                        ->join('usr_cart','usr_cart_item.cart_id','=','usr_cart.id')
                        ->where('usr_cart.user_id',$user_id)    
                        ->where('usr_cart.is_active',1)
                        ->where('usr_cart.is_deleted',0)
                        ->where('usr_cart_item.is_active',1)
                        ->where('usr_cart_item.is_deleted',0)
                        ->get();  
        // $cart = Product::join('usr_cart_item','prd_products.id','=','usr_cart_item.product_id')
        //                 ->join('usr_cart','usr_cart_item.cart_id','=','usr_cart.id')
        //                 ->where('usr_cart.user_id',$user_id)    
        //                 ->where('usr_cart.is_active',1)
        //                 ->where('usr_cart.is_deleted',0)
        //                 ->where('usr_cart_item.is_active',1)
        //                 ->where('usr_cart_item.is_deleted',0)
        //                 ->distinct()
        //                 ->get('seller_id');                           


        if(count($carts)>0){    
            //ADDRESS
            $addr_list =  CustomerAddress::where('id',$input['address_id'])->first();
            // foreach($carts as $rows){
            //     $products[] = $this->get_cart_products($rows->product_id,$rows->cart_id,$rows->quantity,$lang);
            // }  
            // $filter = array_filter($products);
            $tot_tax =0;
            $total_cost=0;
            $total_discount=0;
            //dd($filter);
           

            $cashback_amount=0;
            $coupon_discount_amount=0;
            
            
              if($input['is_platform_coupon']==true)
        {
            $pltform_coupon_id = $input['platform_coupon_id'];
            if($input['platform_discount_type']=='discount')
            {
                $plform_discount_type = $input['platform_discount_type'];
                $pltform_discount_amt = $input['discount_amt'];
                $parent_g_total = $input['total_amt'] - $pltform_discount_amt;
                
               
            }
            else
            {
                $pltform_coupon_id = '';
                $pltform_discount_amt = 0;
                $parent_g_total = '';
                $plform_discount_type = '';
                
            }
        }
        else
        {
                $pltform_coupon_id = '';
                $pltform_discount_amt = 0;
                $parent_g_total = '';
                $plform_discount_type = '';
        }
        
        
        if($input['invite_coupon_id'] !="")
        {
            // disable coupon
            
            InviteSave::where('id',$input['invite_coupon_id'])->update([
             'is_valid'=>0,'updated_at'=>date("Y-m-d H:i:s")]);
             
             $pltform_discount_amt = $input['discount_amt'];
        $parent_g_total = $input['total_amt'] - $pltform_discount_amt;
            
        }

            //WALlet balance
            if($input['e_money_amt']==false)
            {
                $wallet_amt = 0;
            }
            else
            {
                $wallet_amt = $input['e_money_amt'];
                
            }
        
            $insert_sale_parent = ParentSale::create(['org_id'            => 1,
                              'user_id'           => $user_id,
                              'tot_amount'        => $input['total_amt'],
                              'platform_coupon_id'=> $pltform_coupon_id,
                              'discount_type'     => $plform_discount_type,
                              'discount_amt'      => $pltform_discount_amt,
                              'wallet_amt'        => $wallet_amt,
                              'reward_id'         => $input['reward_id'],
                              'reward_amt'        => $input['reward_amt'],    
                              'grand_total'       => $input['total_amt']-$pltform_discount_amt - $wallet_amt,   
                              'created_at'        => date("Y-m-d H:i:s"),
                              'updated_at'        => date("Y-m-d H:i:s"),
                              'currency_code'     => $input['currency_code'],
                              'currency_amount'   => $input['currency_amount']
                             
                              ]);
            $parent_sale_id  = $insert_sale_parent->id; 

            // dd($parent_sale_id);
        
            // reward application
            $sale_before  =  SaleOrder::where('cust_id',$user_id)->count();
            if($sale_before<1)
            {
                $reward = Reward::where('is_active',1)->where('is_deleted',0)->where('ord_min_amount', '<=', $input['total_amt'])->first();
                if($reward){
                    if($reward->ord_type=='cashback'){
                    $cashback_reward = CustomerWallet_Model::create(['user_id'    =>  $user_id,
                                                              'source_id'  =>  $reward->id,
                                                              'source'     =>  'First Buy',
                                                              'credit'     =>  $reward->ord_amount,
                                                              'is_active'  =>  1,
                                                              'is_deleted' =>  0,
                                                              'created_at'    =>date("Y-m-d H:i:s"),
                                                              'updated_at'    =>date("Y-m-d H:i:s")]); 
                    }
                                                              
                    //If INVITED BY someone
                    $cust_master = Customer::where('id',$user_id)->first();
                    if($cust_master->invited_by!='' && $cust_master->invited_by!=0){
                        $rewards              =   Reward::getRewards();
                        $rewards = (object) $rewards;
                        if($rewards->reward == "cashback"){
                        if($rewards->rwd_type_referral == 6 || $rewards->rwd_type_referral == 5)
                        {
                        $cashback_purchase =  $rewards->referral_cashback_purchase;
                        $cashback_reward = CustomerWallet_Model::create(['user_id'    =>  $cust_master->invited_by,
                        'source_id'  =>  0,
                        'source'     =>  'Referral First Purchase Cashback',
                        'credit'     =>  $cashback_purchase,
                        'is_active'  =>  1,
                        'is_deleted' =>  0,
                        'created_at'    =>date("Y-m-d H:i:s"),
                        'updated_at'    =>date("Y-m-d H:i:s")]); 
                        }

                        }else{
                             if($rewards->rwd_type_referral == 6 || $rewards->rwd_type_referral == 5){

                                $cashback_purchase =  $rewards->referral_cashback_purchase; 

                                $cashback_reward = CustomerCoupon::create(['user_id'    =>  $cust_master->invited_by,
                                'salesman_id'  =>  0,
                                'coupon_id'     =>  $rewards->referral_coupon_purchase,
                                'is_active'  =>  1,
                                'is_deleted' =>  0,
                                'is_used' =>  0,
                                'created_at'    =>date("Y-m-d H:i:s"),
                                'updated_at'    =>date("Y-m-d H:i:s")]); 
                            }

                        }

                        // Referral rewards

                    }
                }
            }
            
       
        
            $latestorder_ids=1;
            $latestOrder = SaleOrder::orderBy('created_at','DESC')->first();
            
            if($latestOrder)
            {
                $latestorder_ids = $latestOrder->id;
                $saleorder_id = date('y').date('m').str_pad($latestorder_ids + 1, 6, "0", STR_PAD_LEFT);
            }
            else{
                $saleorder_id = date('y').date('m').str_pad($latestorder_ids, 6, "0", STR_PAD_LEFT);
            }
           
                $seller_arrays =$input['seller_array'];      
                
                foreach($seller_arrays as $rows)
                {  

                if($rows['discount_amt']!="")
                {
                    $discount_amt_sale = $rows['discount_amt'];
                }
                else
                {
                    $discount_amt_sale = 0;
                }
                if($rows['packing_charge']!="")
                {
                    $packing_chrg = $rows['packing_charge'];
                }
                else
                {
                    $packing_chrg = 0;
                }
                if($rows['shipping_charge']!="")
                {
                    $shipping_chrg = (float)$rows['shipping_charge'];
                }
                else
                {
                    $shipping_chrg = 0;
                }
                
               
                
                 $grnd_tot_sale = ($rows['total_cost']+$rows['total_tax']+$rows['shipping_charge']) - $discount_amt_sale - $wallet_amt;

                   $create_saleorder = SaleOrder::create(['org_id' => 1,
                'parent_sale_id'  =>$parent_sale_id,
                'order_id'        => $saleorder_id,
                'cust_id'         => $user_id,
                'seller_id'       => $rows['seller_id'],
                'total'           => $rows['total_cost'],
                'discount'        => $discount_amt_sale,
                'tax'             => $rows['total_tax'],
                'shiping_charge'  => $shipping_chrg,
                'packing_charge'  => $packing_chrg,
                'wallet_amount'   => $wallet_amt,
                'g_total'         => $grnd_tot_sale,
                'ecom_commission' => 0,
                'discount_type'   => $rows['discount_type'],  
                'coupon_id'       => $rows['coupon_id'],
                'invite_coupon_id'       => $input['invite_coupon_id'],
                'order_status'    => 'pending', //initiated
                'payment_status'  => 'pending',
                'shipping_status' => 'pending',
                'cancel_process'  => 0,
                'cust_message'    => $rows['message'],    
                'created_at'    =>date("Y-m-d H:i:s"),
                'updated_at'    =>date("Y-m-d H:i:s")]);
                $sale_id  = $create_saleorder->id;

                if($request->use_points == 1)
                {
                    $points['user_id'] = $user_id;
                    $points['sales_id'] = $sale_id;
                    $points['credit'] = 0;
                    $points['debit'] = $request->points_used;
                    $points['is_deleted'] = 0;
                    $points['created_at'] = date("Y-m-d H:i:s");
                    $points['updated_at'] = date("Y-m-d H:i:s");

                    $customer_points_id = CustomerPoints::create($points)->id;
                }
                if(!empty($request->insert_points))
                {
                    $points['user_id'] = $user_id;
                    $points['sales_id'] = $sale_id;
                    $points['credit'] = $request->insert_points;
                    $points['debit'] = 0;
                    $points['is_deleted'] = 0;
                    $points['created_at'] = date("Y-m-d H:i:s");
                    $points['updated_at'] = date("Y-m-d H:i:s");

                    $customer_points_id = CustomerPoints::create($points)->id;
                }

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
                if($rows['is_coupon']==true)
                {
                    $coupon_data= Coupon::where('id',$rows['coupon_id'])->first();
                    if($coupon_data)
                    {
                    $coupon_usage= CouponHist::create(['org_id'       =>  1,
                                                       'coupon_id'    =>  $coupon_data->id,
                                                       'order_id'     =>  $sale_id,
                                                       'ofr_value'    =>  $coupon_data->ofr_value,
                                                       'ofr_value_type'=> $coupon_data->ofr_value_type,
                                                       'ofr_type'     =>  $coupon_data->ofr_type,
                                                       'created_at'    =>date("Y-m-d H:i:s"),
                                                       'updated_at'    =>date("Y-m-d H:i:s")
                                                        ]);
                     }
                }
                
                $stHistory                  =   ['sales_id'=>$sale_id,'status'=>"Ordered",'created_by'=>$user_id,'role_id'=>5];
                $stHistory['description']   =   "Order Placed by Customer";    SalesOrderStatusHistory::create($stHistory);

                //Payment
                if($input['payment_type']==1){
                $payment_type="Stripe";
                $payment_status="pending";
                $payment_data="";
                $transaction_id="";
                }
                else if($input['payment_type']==2){ 
                $payment_type="CREDIT";
                $payment_status="pending";
                $payment_data="";
                $transaction_id="";

                // $access_token = $request->access_token;
                // $user_logins = DB::table('usr_logins')->where('access_token',$access_token)->first();

                // $user_id = $user_logins->user_id;
                
                // $credits_log = CustomerCreditLogs::where('user_id',$user_id)->where('is_deleted',0)->first();

                // $cust_credits['user_id'] = $user_id;
                // $cust_credits['ref_id'] = $sale_id;
                // $cust_credits['log_id'] = $credits_log->id;
                // $cust_credits['credit_limit'] = $credits_log->credit_limit;
                // $cust_credits['credit_days'] = $credits_log->credit_days;
                // $cust_credits['credit'] = 0;
                // // $cust_credits['debit'] = $request->total_cost;
                // $cust_credits['debit'] = $rows['total_cost'];
                // $cust_credits['allow_purchase'] = $credits_log->allow_purchase;
                // $cust_credits['per_purchase'] = $credits_log->per_purchase;
                // $cust_credits['payment_status'] = 'pending';
                // $cust_credits['is_active'] = 1;
                // $cust_credits['created_by'] = $user_id;
                // $cust_credits['modified_by'] = $user_id;
                // $cust_credits['created_at'] = date('Y-m-d H:i:s');
                // $cust_credits['updated_at'] = date('Y-m-d H:i:s');

                // $customer_credit_id = CustomerCredits::create($cust_credits)->id;
                
                }else{
                $payment_type="Offline Payment";
                $payment_status="pending";
                $payment_data="Offline payment";
                $transaction_id=$saleorder_id."BBCOD";
                }
                $saleorder_payment = SalesOrderPayment::create(['org_id' => 1,
                'sales_id'         => $sale_id,
                'payment_method_id'=> $input['payment_type'],
                'payment_type'     => $payment_type,
                'transaction_id'   => $transaction_id,
                'payment_data'     => $payment_data,
                'amount'           => $grnd_tot_sale,
                'payment_status'   => "pending"])->id;
                // $sale_items = $this->insert_products($sale_id,$user_id,$lang);
                $sale_items = $this->insert_seller_products($sale_id,$rows['seller_id'],$user_id,$lang);
                $saleorder_payments = SalesOrderShippingStatus::create([
                'sales_id'         => $sale_id,
                'status'=> "pending" ]);
                  if($rows['is_coupon']==true)
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
                
             if($input['payment_type']==3){ 
                 $order_id=$saleorder_id;
                 $prod_datas  = Cart::join('usr_cart_item','usr_cart.id','=','usr_cart_item.cart_id')->join('prd_products','usr_cart_item.product_id','=','prd_products.id')
                        ->where('usr_cart.user_id',$user_id)    
                        ->where('usr_cart.is_active',1)
                        ->where('usr_cart.is_deleted',0)
                        ->where('usr_cart_item.is_active',1)
                        ->where('usr_cart_item.is_deleted',0)
                        ->where('prd_products.is_deleted',0)
                        ->where('prd_products.seller_id',$rows['seller_id'])
                        ->groupBy('prd_products.id', 'usr_cart_item.assortment_id')
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
                        
                        $seller_name  = "Ushas";
                
                 $cust_info  = CustomerInfo::where('user_id',$user_id)->first();
                 $user_name  = $cust_info->first_name;
                 $user_email       = CustomerTelecom::where('user_id',$user_id)->where('usr_telecom_typ_id',1)->first(); 
                 $user_email = $user_email->usr_telecom_value;
                
              
                $data['data'] = array("content"=>"Test",'seller_name'=>$seller_name,'username'=>$user_name,'sale_id'=>$order_id);
                 $var = Mail::send('emails.customer_msg_email', $data, function($message) use($data,$user_email) {
                 $message->from(getadmin_mail(),'Ushas');    
                 $message->to($user_email);
                 $message->subject('Order Placed ');
                 });
        
               //  SaleOrder::where('order_id',$order_id)->update(['order_status'=>'pending',
               //  'payment_status'=>'pending','updated_at'=>date("Y-m-d H:i:s")]);
                
               //   $from       = $user_id; 
               //  $utype      = 3;
               //  $to         = $user_id; 
               //  $ntype      = 'order_placed';
               //  $title      = 'Order placed';
               //  $desc       = 'New order has been placed. Order ID:'.$order_id;
               //  $refId      = $saleorder_payment;
               //  $reflink    = 'customer/order/detail';
               //  $notify     = 'customer';
               //  addNotification($from,$utype,$to,$ntype,$title,$desc,$refId,$reflink,$notify);
             }
                
            }

            
            return ['httpcode'=>200,'status'=>'success','message'=>'Order placed','data'=>['order_id'=>$saleorder_id]];

        }

        else
        {
            return ['httpcode'=>404,'status'=>'error','message'=>'Cart is empty','data'=>['errors'=>'Cart is empty']];
        }
    }//validation true




    }
    
        //Product ACTUAL price
    public function get_actual_price($field_id){ 
       //$current_date=Carbon::now();
       $rows = PrdPrice::where('is_deleted',0)->where('prd_id',$field_id)->orderBy('id','DESC')->first();        
        if($rows){ 
        $return_val = $rows->price;
        return $return_val;
        }
        else
            { $return_val=0;
                return $return_val; }
        }
    
    
    //Track order
  public function track_order(Request $request)
    {    
        
        if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
        $user_id = $user['user_id'];
         $lang =  $request->lang_id;
         $orderId= $request->order_id;
                    
                    if($orderId)
                    {
                        $sales =  SaleOrder::where('cust_id',$user_id)->where('order_id',$orderId)->get();
                    }
                    else
                    {
                        $sales =  SaleOrder::where('cust_id',$user_id)->whereNotIn('shipping_status', ['delivered','cancelled'])->get();
                    }
                     
                    if($sales->count() > 0)
                    {
                        foreach($sales  as $row)
                        {
                            $all_items      =   SaleorderItems::where('sales_id',$row->id)->get(); 
                            // return [$all_items];
                            // die;
                            foreach($all_items  as $items)
                            {
                                $prdId      =   $items->prd_id;
                                
                                $data['sale_id']           =   $row->id;
                                $data['order_id']          =   $row->order_id;
                                $data['sale_items_id']     =   $items->id;
                                $data['product_id']        =   $prdId;
                                $data['product_name']      =   $this->get_content($items->product->name_cnt_id,$lang);
                                $data['price']             =   $items->row_total;
                                $data['currency']          =   getCurrency()->name;
                                //$data['image']     =   $this->get_product_image($items->prd_id);
                                $data['quantity']          =   $items->qty;
                                $data['order_date']        =   date('Y-m-d',strtotime($row->created_at));
                                $data['order_time']        =   date('g:i a',strtotime($row->created_at));
                                $data['delivery_status']   =   $row->shipping_status;
                                $data['delivery_date']       =   '';
                                $val[] = $data;
                             }
                        }
                    }
                    else
                    {
                       $val        =   []; 
                    }
                    return ['httpcode'=>'200','status'=>'success','message'=>'track order','data'=>['order'=>$val]];
    }
    
    
    //Order History
    //Order History
  public function order_history(Request $request)
    {    
        
        if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
       
        $user_id = $user['user_id'];
         $lang =  $request->lang_id;
         $orderId= $request->order_id;
                    $val        =   []; 
                    if($orderId)
                    {
                        $sales =  SaleOrder::where('cust_id',$user_id)->where('id',$orderId)->first();
                        
                  
                    // dd($sales);
                    if($sales->count() > 0)
                    {
                        
                            $all_status      =   SalesOrderStatusHistory::where('sales_id',$orderId)->where('is_deleted',0)->get(); 
                         //dd($all_status);
                            foreach($all_status  as $items)
                            {
                              
                                
                               // $data['identifier']           =   $items->statusVal->identifier;
                               // $data['title']          =   $items->statusVal->title;
                              //  $data['description']     =   $items->statusVal->description;
                               $data['title']          =   $items->status;
                               $data['description']     =   $items->description;
                                $data['date']        =  date('d-m-Y h:i A',strtotime($items->created_at)) ;
                                $data['timestamp']        =  strtotime($items->created_at);
                                // $data['updated_by']        =   $items->updated_by;
            
                                $val[] = $data;
                             }
                             
                             if($sales->order_status=="pending"){
                                $data['title']          =   "Pending";
                                $data['description']     =  "Order Pending";
                                $data['date']        =  date('d-m-Y h:i A',strtotime($sales->created_at)) ;
                                $data['timestamp']        =  strtotime($sales->created_at);
                                
                                 $val[] = $data;
                        }
                        
                    }
                    else{
                       $val        =   []; 
                    }
                       
                    } 
                    return ['httpcode'=>'200','status'=>'success','message'=>'track order','data'=>['order'=>$val]];
    }
    
  public function checkout_info_page(Request $request){
    
    if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
        $user_id = $user['user_id'];
        //$lang=$request->lang_id;
        $typ_data=[];
        $branches=[];
        $customer_master = CustomerMaster::where('id',$user_id)->where('is_active',1)->where('is_deleted',0)->first();
        if($customer_master->parent_id==0){

        //******TYpes of addresses
        $adr_typ = CustomerAddressType::where('is_active',1)->where('is_deleted',0)->get();
        foreach($adr_typ as $rows)
        {
            $typ['addr_type_id']      = $rows->id;
            $typ['addr_type_name']    = $rows->usr_addr_typ_name;
            $typ['addr_type_desc']    = $rows->usr_addr_typ_desc; 
            $typ_data[]               = $typ;   
        }



        //previous address
        $customer_address = CustomerAddress::where('is_active',1)->where('is_deleted',0)->where('user_id',$user_id)->get();
        $branches        =   CustomerBranchEmployees::getEmployeeBranches($user_id);    

        if(count($customer_address)>0)
        {
        foreach($customer_address as $row)
        {
            $cust_addr['addr_id']        = $row->id;
            $cust_addr['address_type']   = $row->type->usr_addr_typ_name;
            $cust_addr['address_1']      = $row->address_1;
            $cust_addr['address_2']      = $row->address_2;
            $cust_addr['pincode']        = $row->pincode;
            $cust_addr['city_id']        = $row->city_id;
            $cust_addr['city_name']      = $row->city->city_name;
            $cust_addr['state_id']       = $row->state_id;
            $cust_addr['state_name']     = $row->state->state_name;
            $cust_addr['country_id']     = $row->country_id;
            $cust_addr['country_name']   = $row->country->country_name;
            $cust_addr['latitude']       = $row->latitude;
            $cust_addr['longitude']      = $row->longitude;
            $cust_addr['is_default_addr'] = $row->is_default;
            
            $addr_data[]                  = $cust_addr;
        }
      }
      else
      {
        $addr_data = [];
      }
        }else{
        $addr_data = [];    
        $branches        =   CustomerBranchEmployees::getEmployeeBranches($user_id);    
            
        }


        //******Payment methods
        $pay_method = PaymentMethod::select('id','title','desc')->where('is_active',1)->where('is_deleted',0)->get();

        return ['httpcode'=>200,'status'=>'success','message'=>'Success','data'=>['address'=>$addr_data,'address_types'=>$typ_data,'branches'=>$branches,'payment_methods'=>$pay_method]];
  }
  
  //list of coupons
  
  public function coupon_list(Request $request)
  {
      $lang=$request->lang_id;
      $current_date=date('Y-m-d');
      $coupon = Coupon::whereDate('valid_from','<=',$current_date)->whereDate('valid_to','>=',$current_date)->where('is_active',1)->where('is_deleted',0)->get();
      $c_list =[];
      foreach($coupon as $row)
      {
           if($row->ofr_value_type=="percentage")
               {
                    $discount = $row->ofr_value." ".$row->ofr_value_type;

               }
               else
               {
                    $discount = $row->ofr_value." ".$row->ofr_value_type;
               }
                    $coupon_details['coupon_id']=$row->id;
                    $coupon_details['title']=$this->get_content($row->cpn_title_cid,$lang);
                    $coupon_details['desc']=$this->get_content($row->cpn_desc_cid,$lang);
                    $coupon_details['offer']=$discount." ".$row->ofr_type;
                    $coupon_details['coupon_code']=$row->ofr_code;
                    $coupon_details['minimum_purchase']=$row->ofr_min_amount;
                    $coupon_details['offer_type']=$row->ofr_type;
                    $c_list[]  = $coupon_details;
      }
      
      return ['httpcode'=>200,'status'=>'success','message'=>'Coupon list','data'=>['coupon'=>$c_list]];
  }
  

    function get_total_seller_product($seller_id)
    {
        $total = 0;
        return $total;
    }

    /*function get_cart_products($prd_id,$cart_id,$qty,$lang){
        $data     =   [];
        
        $prod_data       =   Product::where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->where('id',$prd_id)->first();
            if($prod_data)   { 
                    $prd_list['cart_id']=$cart_id;   
                    $prd_list['product_id']=$prod_data->product_id;
                    $prd_list['product_name']=$this->get_content($prod_data->name_cnt_id,$lang);
                    $prd_list['quantity']=$qty;
                    //$prd_list['seller']=$prod_data->Store($prod_data->seller_id)->store_name;
                    //$prd_list['seller_id']=$prod_data->seller_id;
                    $prd_list['category_id']=$prod_data->category_id;
                    $prd_list['category_name']=$this->get_content($prod_data->category->cat_name_cid,$lang);
                    $prd_list['subcategory_id']=$prod_data->sub_category_id;
                    $prd_list['subcategory_name']=$this->get_content($prod_data->subCategory->sub_name_cid,$lang);
                    
                    $prd_list['currency']=getCurrency()->name;
                    if($prod_data->brand_id)
                    {
                        $prd_list['brand_id']=$prod_data->brand_id;
                        $prd_list['brand_name']=$this->get_content($prod_data->brand->brand_name_cid,$lang);
                    }
                    
                    $actual_price =$prod_data->prdPrice->price;
                    $prd_list['unit_actual_price']=(int)$actual_price;
                    $tot_actual=(int)$actual_price*(int)$qty;
                    $prd_list['total_actual_price']=(int)$tot_actual;
                    $tax_amt=$prod_data->getTaxValue($prod_data->tax_id);
                    $tax_amt_per =$tax_amt/100;
                    $total_tax_amount = (int)$tot_actual * (int)$tax_amt_per;
                    $prd_list['total_tax_value']=$total_tax_amount;
                     
                    
                    //Available offers for this product
            $current_date=Carbon::now();
           
           

            $shock = PrdShock_Sale::join('prd_shock_sale_products','prd_shock_sale.id','=','prd_shock_sale_products.shock_sale_id')
            ->whereRaw("find_in_set(".$prd_id.",prd_shock_sale_products.prd_id)")
            ->where('prd_shock_sale.is_active',1)->where('prd_shock_sale.is_deleted',0)->whereDate('prd_shock_sale.start_time','<=',$current_date)->whereDate('prd_shock_sale.end_time','>=',$current_date)
            ->where('prd_shock_sale_products.is_active',1)->where('prd_shock_sale_products.is_deleted',0)->first();

           
             if($shock)
            {
                 $prd_list['offer_available']= 1;
                 $prd_list['offer_name']= 'Shocking Sale'; 
                if($shock->discount_type=="amount")
                    {
                        $prd_list['offer']=getCurrency()->name." ".$shock->discount_value." Off";
                        $discount_value = $shock->discount_value;
                        $unit_price = (int)$actual_price-(int)$discount_value;
                        $prd_list['discount_values']= (int)$discount_value;
                        $prd_list['unit_discount_price']= $unit_price;
                        $prd_list['total_discount_price']=$unit_price * $qty;
                       

                    }
                    else
                    {
                        $prd_list['offer']=$shock->discount_value."% Off";
                        $shock_discount =$shock->discount_value/100;
                        $per=(int)$shock_discount*(int)$actual_price;
                        $discount=(float)$actual_price-(float)$per;
                        $round= $discount;
                        $prd_list['discount_values']= (int)$per;
                        $prd_list['unit_discount_price']=(int)$round;
                        $prd_list['total_discount_price']=$round * $qty;
                    }
            }
            
            else
            {
                $prd_list['offer_available']= 0;
                $prd_list['offer_name']= ''; 
                $sale_price =$this->get_sale_price($prd_id);
                if($sale_price!='')
                {
                $prd_list['unit_discount_price']=$sale_price;
                $tot= $sale_price * $qty;
                $prd_list['total_discount_price']=(int)$tot;
                $prd_list['discount_values']= (int)$actual_price-(int)$sale_price;
                }
                else
                {   $prd_list['discount_values']= 0;
                    $prd_list['unit_discount_price']=0;
                    $prd_list['total_discount_price']=0;
                }
            }

            $prd_list['is_out_of_stock']=$prod_data->is_out_of_stock;
           // $prd_list['image']=$this->get_product_image($prod_data->id);
                    $data             =   $prd_list;
             }
            // else{ $data     =   []; } 
             return $data;
        
    }*/
       function get_cart_products($prd_id,$cart_id,$qty,$lang){
        $data     =   [];
        $prod_data       =   Product::where('is_active',1)->where('is_deleted',0)->where('id',$prd_id)->first();
            if($prod_data)   { 
                    $type=$prod_data->product_type;
                    $login=1;
                    $prices    = $this->get_actual_price($prd_id);
                    $spec_offr = $this->get_offer_price($prd_id,$type,$login,$qty);
                    
                    $prd_list['cart_id']=$cart_id;   
                    $prd_list['product_id']=$prod_data->id;
                    /*if($prod_data->visible==0){
                    
                    $associative_prod_data       =   AssociatProduct::where('ass_prd_id',$prod_data->id)->where('is_deleted',0)->first();
                    $parent_prod_data       =   Product::where('is_active',1)->where('is_deleted',0)->where('id',$associative_prod_data->prd_id)->first();
                    $product_name=$parent_prod_data->name;//$this->get_content($parent_prod_data->name_cnt_id,$lang);
                    $getattributes=$this->getAttributesOfAssociativeProducts($prod_data->id,$lang);
                    $prd_list['product_name']=$product_name." - ".implode($getattributes, ' - ');
                    }
                    else{*/
                    $prd_list['product_name']=$prod_data->name;//($prod_data->name_cnt_id,$lang);
                   // }
                    $prd_list['type']=$prod_data->product_type;
                    $prd_list['quantity']=$qty;
                    $prd_list['category_id']=$prod_data->category_id;
                    $prd_list['category_name']=$this->get_content($prod_data->category->cat_name_cid,$lang);
                    $prd_list['subcategory_id']=$prod_data->sub_category_id;
                    $prd_list['subcategory_name']=$this->get_content($prod_data->subCategory->sub_name_cid,$lang);
                    
                    $prd_list['currency']=getCurrency()->name;
                    if($prod_data->brand_id)
                    {
                        $prd_list['brand_id']=$prod_data->brand_id;
                        $prd_list['brand_name']=$this->get_content($prod_data->brand->brand_name_cid,$lang);
                    }
                    
                                       
                       $actual_price =$this->get_actual_price($prd_id);
                   //$prod_data->prdPrice->price; 
                       
                   
                    $prd_list['unit_actual_price']=$actual_price;
                    $tot_actual=$actual_price*$qty;
                    $prd_list['total_actual_price']=$tot_actual;
                    $tax_amt=$prod_data->getTaxValue($prod_data->tax_id);
                    
                    $total_tax_amount = $tot_actual * ($tax_amt/100);
                    $prd_list['total_tax_value']=$total_tax_amount;
                    foreach ($spec_offr as $item) {
                            foreach ($item as $key => $value) {
                                $prd_list[$key] = $value;
                            } 
                        }
                   

            $prd_list['is_out_of_stock']=$prod_data->is_out_of_stock;
            //$prd_list['image']=$this->get_product_image($prod_data->id);
                    $data             =   $prd_list;
            
             }
            // else{ $data     =   []; } 
             return $data;
        
    }
    public function get_offer_price($prdid,$type,$login,$qty){ 
        $offer['offer_name']= false;
        $offer['offer_price']=0;
        $current_date=Carbon::now();
        $prod_data= Product::where('id',$prdid)->first();
          if($type==2){
                  $parent_product = AssociatProduct::where('ass_prd_id',$prdid)->where('is_deleted',0)->first();
                if($parent_product){
                $parent_prdid=$parent_product->prd_id;
                }else{
                    $parent_prdid=0;
                }
                  $shock = PrdShock_Sale::join('prd_shock_sale_products','prd_shock_sale.id','=','prd_shock_sale_products.shock_sale_id')
            ->where('prd_shock_sale.is_active',1)->where('prd_shock_sale.is_deleted',0)->whereDate('prd_shock_sale.start_time','<=',$current_date)->whereDate('prd_shock_sale.end_time','>=',$current_date)
            ->where('prd_shock_sale_products.is_active',1)->where('prd_shock_sale_products.is_deleted',0)->whereRaw("find_in_set($parent_prdid,prd_shock_sale_products.prd_id)")
            ->select('prd_shock_sale.*','prd_shock_sale_products.seller_id','prd_shock_sale_products.prd_id as shock_prd_id')->first(); 
            }else{
            $shock = PrdShock_Sale::join('prd_shock_sale_products','prd_shock_sale.id','=','prd_shock_sale_products.shock_sale_id')
            ->where('prd_shock_sale.is_active',1)->where('prd_shock_sale.is_deleted',0)->whereDate('prd_shock_sale.start_time','<=',$current_date)->whereDate('prd_shock_sale.end_time','>=',$current_date)
            ->where('prd_shock_sale_products.is_active',1)->where('prd_shock_sale_products.is_deleted',0)->whereRaw("find_in_set($prod_data->id,prd_shock_sale_products.prd_id)")
            ->select('prd_shock_sale.*','prd_shock_sale_products.seller_id','prd_shock_sale_products.prd_id as shock_prd_id')->first();
            }
        
            if($type==2){
                 $parent_product = AssociatProduct::where('ass_prd_id',$prdid)->where('is_deleted',0)->first();
                    if($parent_product){
                $parent_prdid=$parent_product->prd_id;
                }else{
                    $parent_prdid=0;
                }
                 $specialOffer = PrdOffer::where('is_deleted',0)->where('prd_id',$parent_prdid)->whereDate('valid_from','<=',$current_date)->whereDate('valid_to','>=',$current_date)->first();        
            }else{
            $specialOffer = PrdOffer::where('is_deleted',0)->where('prd_id',$prdid)->whereDate('valid_from','<=',$current_date)->whereDate('valid_to','>=',$current_date)->first();        
            }    
            $SalesPrice = PrdPrice::where('is_deleted',0)->where('prd_id',$prdid)->whereDate('sale_end_date','>=',$current_date)->orderBy('id','DESC')->first();        
            $Price = PrdPrice::where('is_deleted',0)->where('prd_id',$prdid)->orderBy('id','DESC')->first();        

        if($shock){ 
            
            $offer['discount_type']= $shock->discount_type;   
            //$offer['offer_id']=$shock->id;
            
                $actual_price=$prod_data->prdPrice->price;
                if($shock->discount_type=="amount"){
                    $offer['offer_available'] = 1;
                    $offer['offer_name']= 'Shocking Sale';      
                    $discount_value = $shock->discount_value;
                    $unit_price = $actual_price-$discount_value;
                    $offer['unit_discount_price']=$discount_value;
                    $offer['total_discount_price']=$unit_price*$qty;
                }else{
                    $offer['offer_available'] = 1;
                    $offer['offer_name']= 'Shocking Sale';      
                    $per=$shock->discount_value/100;
                    $per_value = (float)$actual_price*(float)$per;
                    $discount=(float)$actual_price-(float)$per_value;
                    $round= round($discount, 2);
                    $offer['unit_discount_price']= $per_value;
                    $offer['total_discount_price']=$discount*$qty;
                }
            
        }else if($specialOffer){
            
            $is_offer=1;
            if($specialOffer->quantity_limit >= $qty){
            $is_offer=1;    
            }else{
            $is_offer=0;
            }
            if($is_offer==1){

            $offer['offer_available'] = 1;
            $offer['offer_name']= 'Special offer';  
            $discount_val = $specialOffer->discount_value;
            $discount_typ = $specialOffer->discount_type;
            $price=$prod_data->prdPrice->price;
            if($discount_typ=="percentage"){
            $dis = $price * ($discount_val/100);
            $offer['unit_discount_price']=$dis;
            $offer['total_discount_price'] = ($price - $dis)*$qty;
            } else {
            $offer['offer_available'] = 1;
            $offer['offer_name']= 'Special offer';  
            $offer['unit_discount_price']=$discount_val;
            $offer['total_discount_price'] = ($price - $discount_val)*$qty;
            }
            }
            else{
            $offer['offer_available'] = 0;
            $offer['offer_name']= "";
            $offer['unit_discount_price']=0;    
            $offer['total_discount_price'] = 0;
            }
            
        }
        elseif($SalesPrice){
            $actual_price=$prod_data->prdPrice->price;
            $offer['offer_available'] = 1;
            $offer['offer_name'] = 'Sale Offer';
            $offer['unit_discount_price']=($actual_price - $SalesPrice->sale_price);            
            $offer['total_discount_price']= ($SalesPrice->sale_price)*$qty;
        }else{
            $offer['offer_available'] = 0;
            $offer['offer_name'] = "";
            $offer['unit_discount_price']= 0;
            $offer['total_discount_price']=0;
            
        }
        $offer_list[]=$offer;
                    
        return $offer_list;
   
    }
    function getAttributesOfAssociativeProducts($prd_id,$lang){
        $data     =   [];
        
        $prod_data       =   AssignedAttribute::where('is_deleted',0)->where('prd_id',$prd_id)->orderBy('attr_id','asc')->groupBy('attr_id')->get();
          dd($prod_data) ;
           if(count($prod_data)>0)   {
                 $attr_list=[];
                foreach($prod_data as $row)  {
                    
                    $attr_list['attr_id']=$row->attr_id;
                    $attr_list['attr_id']=$row->attr_id;
                    $attr_list['attr_id']=$row->PrdAttr->name;
                    $attr_list['attr_val_id']=$row->PrdAttr->name;
                    $attr_list['attr_value']=$row->attrValue->name;
                
                }
                $data             =   $attr_list;
                
           }
        return $data;
    }
    
function custom_product_qty($prd_id,$assort,$user_id=0)
  {
    $custom_qty=DB::table("usr_cart_item")->select(DB::raw("SUM(quantity) as quantity"))->where('product_id',$prd_id)->where('assortment_id',$assort)->where('is_active',1)->where('is_deleted',0)->whereIn('cart_id',function($query) use($user_id) {
   $query->select('id')->from('usr_cart')->where('is_deleted',0)->where('user_id',$user_id);})->first();    
    if($custom_qty->quantity > 0)
    {
        return $custom_qty->quantity;
    }
    else
    {
        return 0;
    }
  }

function product_assort_list($prd_id,$assort,$user_id=0)
  {
    $custom_assort=DB::table("usr_cart_item")->select('prd_assign_id','quantity','assortment_qty','assortment_id')->where('product_id',$prd_id)->where('assortment_id',$assort)->where('is_active',1)->where('is_deleted',0)->whereIn('cart_id',function($query) use($user_id) {
   $query->select('id')->from('usr_cart')->where('is_deleted',0)->where('user_id',$user_id);})->get();
    $prd_assorts = [];
    if($custom_assort)
    {
        $prd_assorts_data = [];
        foreach($custom_assort as $k=>$v)
        {
            $prd_assorts_data['child_product_id']= $v->prd_assign_id;
           //if($v->assortment_id>0){ $prd_assorts_data['child_product_qty']= ($v->quantity/$v->assortment_qty); }else{ $prd_assorts_data['child_product_qty']= $v->quantity; } 
           $prd_assorts_data['child_product_qty']= $v->quantity;
            $prd_assorts_data['child_assortment_qty']= $v->assortment_qty;
            $prd_assorts[] = $prd_assorts_data;
        }
    }
    return json_encode($prd_assorts);
  }

    function insert_seller_products($sale_id,$seller_id,$user_id,$lang){
        
        $user = CustomerMaster::where('id',$user_id)->first(); 
        
        $crm_cust_id = $user->crm_unique_id;
        $customer_name = CustomerInfo::where('user_id',$user_id)->first()->first_name;

        $product=[];
        $prod_datas  = Cart::join('usr_cart_item','usr_cart.id','=','usr_cart_item.cart_id')->join('prd_products','usr_cart_item.product_id','=','prd_products.id')
                        ->where('usr_cart.user_id',$user_id)    
                        ->where('usr_cart.is_active',1)
                        ->where('usr_cart.is_deleted',0)
                        ->where('usr_cart_item.is_active',1)
                        ->where('usr_cart_item.is_deleted',0)
                        ->where('prd_products.is_deleted',0)
                        ->where('prd_products.seller_id',$seller_id)
                        ->groupBy('prd_products.id', 'usr_cart_item.assortment_id')
                        ->select('prd_products.*','usr_cart.*','usr_cart_item.*','usr_cart_item.product_id as cart_prd_id')
                        ->get();
            
            if(count($prod_datas)>0)   {    
                foreach($prod_datas as $prod_data)
                {   

                    $single_prod_data       =   Product::where('is_active',1)->where('is_deleted',0)->where('id',$prod_data->product_id)->first();

                    $crm_product_id = $single_prod_data->crmProduct->id;
                    $crm_branch_id = $single_prod_data->crmProduct->BranchID;
                    $assortment_id = $prod_data->assortment_id;

                    $prd_assort = CrmPartAssortmentMaster::where('productID',$crm_product_id)->where('AssortmentID',$assortment_id)->where('is_deleted',0)->first();

                     if($prd_assort)
                    {
                        $is_custom = 0;
                        $quantity = $this->custom_product_qty($single_prod_data->id,$assortment_id,$user_id);
                        $qty=$prod_data->assortment_qty;
                        $quantity = $quantity*$qty;
                    }else{
                        $is_custom = 1;
                        $quantity = $this->custom_product_qty($single_prod_data->id,0,$user_id);
                    }

                    $qty=$quantity;


                    $prd_list['product_id']=$prod_data->product_id;
                    $prd_list['product_name']=$product_name=$this->get_content($prod_data->name_cnt_id,$lang);
                    
                        $actual_price = get_crm_price($single_prod_data,$type=1,$user);
                        $price = $actual_price['actual_price'];
                        $tax = getTax()->value;
                        $qty_price = $price*$qty;
                        
                        $seller_tax_amount = ($tax/100)*$qty_price;
                        
                        $tax_amount = ($tax/100)*$qty_price;
                        $tot_price = $price+$tax_amount;
                        $tot_amt_tax=$qty_price+$tax_amount; //$tot_price*$qty;
                  
                        if($actual_price['offer']>0){
                        $discount=(float)$actual_price['offer'];
                        }else{
                        $discount=0;
                      
                        }
                   
          
            $create_saleorder = SaleorderItems::create([
                'sales_id'        => $sale_id,
                'parent_id'       => $sale_id,
                'prd_id'          => $prod_data->product_id,
                'attr_ids'        => $prod_data->prd_assign_id,
                'prd_type'        => $prod_data->product_type,
                'prd_name'        => $product_name,
                'price'           => $price,
                'qty'             => $qty,
                'total'           => $qty_price,
                'discount'        => $discount,
                'tax'             => $tax_amount,
                'tax_seller'             => $seller_tax_amount,
                'row_total'       => $tot_amt_tax,
                'coupon_id'       => '', 
                'created_at'    =>date("Y-m-d H:i:s"),
                'updated_at'    =>date("Y-m-d H:i:s"),
                'assortments'       => $this->product_assort_list($single_prod_data->id,$assortment_id,$user_id),
                'is_custom'       => $is_custom,
                'assortments_id'       => $assortment_id,
                'is_deleted'    =>0]);
                
                 $prd_stock_update = PrdStock::create([
                    'type'       =>'destroy',
                    'prd_id'     => $prod_data->product_id,
                    'child_id'     => $prod_data->prd_assign_id,
                    'product_type'     => 'child',
                    'qty'        => $qty,
                    'rate'       => $price,
                    'created_by' => $user_id,
                    'sale_id'    => $sale_id,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ]);
                
                $headers[] = 'Content-Type: application/json';
   
                $datapass = json_encode(array(
                    "CategoryId"=> 0,
                    "POSSessionId"=> 0,
                    "POSInvoiceId"=> 0,
                    "BranchId"=> $crm_branch_id,
                    "DivisionId"=> 53,
                    "CustomerId"=> $crm_cust_id,
                    "OrganisationId"=> 54,
                    "User_Id"=> $user_id,
                    "POSInvoiceNumber"=> 0,
                    "SubTotal"=> $qty_price,
                    "TotalTax"=> $tax_amount,
                    "GrandTotal"=> $tot_amt_tax,
                    "TenderCash"=> 0,
                    "BalanceAmount"=> 0,
                    "Cash"=> 0,
                    "CustomerCredit"=> 0,
                    "DebitCard"=> null,
                    "DebitCardNo"=> null,
                    "DebitCardDate"=> null,
                    "MobilePayment"=> 0,
                    "MobilePaymentType"=> null,
                    "POS_Status"=> 'Created',
                    "DiscountType"=> null,
                    "DiscountValue"=> 0,
                    "CreditNoteNumber"=> null,
                    "CreditAmount"=> 0,
                    "CreditBalanceAmount"=> 0,
                    "CreditNoteId"=> 0,
                    "POSInvoiceParts"=> stripslashes(json_encode(
                        array(
                            array(
                                "Part_Id"=> $prod_data->unique_id,
                                "SubProductID"=> $prod_data->prd_assign_id,
                                "Discount"=> 0,
                                "DiscountPercentage"=> 0,
                                "AdditionalDiscount"=> 0,
                                "AdditionalDiscountPercentage"=> 0,
                                "IsPartDiscountAmount"=> false,
                                "GST_Percentage"=> 0,
                                "SellingPrice"=> $qty_price,
                                "Quantity"=> $qty,
                                "Total"=> $tot_amt_tax,
                                "TotalAmount"=> $tot_amt_tax
                            )
                        )
                    ))
                ));
   
               // dd($datapass);
   
            //   $url_cust_reg = "http://20.212.51.46:8081/api/POSUSHAS/SavePOSInvoice";
               $url_cust_reg = "http://20.204.113.67:5002/api/POSUSHAS/SavePOSInvoice";
               $handle = curl_init($url_cust_reg);
               curl_setopt($handle, CURLOPT_POST, true);
               curl_setopt($handle, CURLOPT_POSTFIELDS, $datapass);
               curl_setopt($handle, CURLOPT_HTTPHEADER, $headers); 
               curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
               $response = curl_exec($handle);
               curl_close($handle);
               $return_response = json_decode($response,true);
   
               // dd($return_response);
   
               if(isset($return_response) && isset($return_response['Data']))
               {
                   SaleOrder::where('id',$sale_id)->update(['crm_id'=>$return_response['Data']]);
               }
                
       
               }      //foreachend    

       
            
            
             }return $prod_datas;
           
        
    }

    function insert_products($sale_id,$user_id,$lang)
    {
        $product=[]; $odoo_arr=[];
        $prod_datas = Product::join('usr_cart_item','prd_products.id','=','usr_cart_item.product_id')
            ->join('usr_cart','usr_cart_item.cart_id','=','usr_cart.id')
            ->where('usr_cart.user_id',$user_id)    
            ->where('usr_cart.is_active',1)
            ->where('usr_cart.is_deleted',0)
            ->where('usr_cart_item.is_active',1)
            ->where('usr_cart_item.is_deleted',0)
            ->where('prd_products.is_active',1)->where('prd_products.is_deleted',0)
            ->select('prd_products.*','usr_cart.*','usr_cart_item.*','usr_cart_item.product_id as cart_prd_id')
            ->get();


        
        if(count($prod_datas)>0)
        {
            foreach($prod_datas as $prod_data)
            {
                $qty = $prod_data->quantity;
                $prd_list['product_id'] = $prod_data->product_id;
                
                if($prod_data->product_type==1)
                {
                    $prd_list['product_name'] = $product_name=$prod_data->name;
                    $parent_id = $prod_data->product_id;
                }
                else
                {
                    $associate = AssociatProduct::where('ass_prd_id',$prod_data->product_id)->first();
                    
                    if($associate)
                    {            
                        $parent_id = $associate->prd_id;
                        $prd_list['product_name'] = $product_name=$associate->product->name;   
                    }
                    else
                    {
                        $prd_list['product_name'] = $prod_data->name;
                    }
                }  
                
                $prd_list['currency']=getCurrency()->name;    
                $actual_price =$this->get_actual_price($prod_data->product_id);
                //$prod_data->prdPrice->price;   
                $prd_list['unit_actual_price']=$actual_price;
                $tot_actual=$actual_price*$qty;
                $prd_list['total_actual_price']=$tot_actual;
                $tax_amt=$prod_data->getTaxValue($prod_data->tax_id);
                $total_tax_amount = $tot_actual * ($tax_amt/100);
                //$prd_list['total_tax_value']=$total_tax_amount;
                $prd_list['total_tax_value']=number_format($total_tax_amount,2);
                $type=$prod_data->product_type;
                $login=1;
                $spec_offr = $this->get_offer_price($prod_data->product_id,$type,$login,$qty);
                // dd($spec_offr);
                
                foreach ($spec_offr as $item)
                {
                    foreach ($item as $key => $value)
                    {
                        if($key=="unit_discount_price")
                        {
                            $unit_discount_price=$value;
                        }
                        if($key=="total_discount_price")
                        {
                            $total_discount_price=$value;
                        }
                    } 
                }
                $prd_list['is_out_of_stock']=$prod_data->is_out_of_stock;
                // $discount_price =$prd_list['total_discount_price'];            
                $tot_actual=$actual_price*$qty;
                $discount=$unit_discount_price*$qty;
                $create_saleorder = SaleorderItems::create([
                    'sales_id'        => $sale_id,
                    'parent_id'       => $parent_id,
                    'prd_id'          => $prod_data->product_id,
                    'prd_type'        => $prod_data->product_type,
                    'prd_name'        => $product_name,
                    'price'           => $actual_price,
                    'qty'             => $prod_data->quantity,
                    'total'           => $tot_actual,
                    'discount'        => $discount,
                    'tax'             => $total_tax_amount,
                    'row_total'       => $tot_actual + $total_tax_amount-$discount,
                    'coupon_id'       => '', 
                    'created_at'    =>date("Y-m-d H:i:s"),
                    'updated_at'    =>date("Y-m-d H:i:s"),
                    'is_deleted'    =>0])->id;

                // $odoo_arr['unique_id'] = $prod_data->product_id;
                // $odoo_arr['product_type'] = $prod_data->product_type;
                // $odoo_arr['odoo_id'] = $prod_data->odoo_id;
                // $odoo_arr['description'] = "Test description";
                // $odoo_arr['qty'] = $prod_data->quantity;
                // $odoo_arr['price'] = $actual_price;

                $odoo_arr[] = array('unique_id'=>$prod_data->product_id,'product_type'=>$prod_data->product_type,'odoo_id'=>$prod_data->odoo_id,'description'=>"Test",'qty'=>$prod_data->quantity,'price'=>$actual_price);

                //dd($create_saleorder);
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

                if($prod_data->product_type==2)
                {
                    $associate= AssociatProduct::where('ass_prd_id',$prod_data->product_id)->first();
                    if($associate)
                    {
                        $prod_datas = AssignedAttribute::where('is_deleted',0)->where('prd_id',$prod_data->product_id)->orderBy('attr_id','asc')->groupBy('attr_id')->get();
                        
                        if(count($prod_datas)>0)
                        {
                            $attr_list=[];
                            
                            foreach($prod_datas as $row)
                            {       
                                $attr_list['sales_id']=$sale_id;
                                $attr_list['sales_item_id']=$create_saleorder;
                                $attr_list['prd_id']=$prod_data->product_id;
                                $attr_list['attr_id']=$row->attr_id;
                                $attr_list['attr_value_id']=$row->attr_val_id;
                                $attr_list['attr_name']=$row->PrdAttr->name;
                                $attr_list['attr_value']=$row->attrValue->name;
                                $attr_list['is_deleted']=0;
                                SalesOrderItemOption::create($attr_list);
                            }
                        }
                    }
                }               
                $cart_update= Cart::where('id',$prod_data->cart_id)->update(['is_active'=>0,'updated_at'=>date("Y-m-d H:i:s")]);

                $cart_item_update=CartItem::where('cart_id',$prod_data->cart_id)->update(['is_active'=>0,'updated_at'=>date("Y-m-d H:i:s")]);  

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

                $product[]=$prd_list;
            }      //foreachend    

       
            
            // Odoo integration

            $headers[] = 'Content-Type: application/json';
            if(authenticateOdoo())
            {
                $headers[] = 'Cookie: '.authenticateOdoo();  
            }
            
            // dd($base64_img);

            // $datapass = json_encode(array(
            //     'jsonrpc'=>"2.0",
            //     'method'=>"call",
            //     'params'=>array(
            //         'model'=>"sale.order",
            //         'method'=>"create_sale_order",
            //         'args'=>[[]],
            //         'kwargs'=>array(
            //             'vals'=>array(
            //                 'bb_partner_id'=>$user_id,
            //                 'ref_no'=>'#Test',
            //                 'big_basket_id'=> $sale_id,
            //                 'order_lines'=>$odoo_arr
            //             )
            //         ),
            //     ),
            // ));            
            
            // print_r($product);
            // echo "<br>";
            // print_r($product[0]['product_id']);
            // exit;

            $datapass = json_encode(array(
                'ModelPrevilege'=>'',
                'Privileges'=>'',
                'CategoryId'=>0,
                'Part_Id'=>$product[0]['product_id'],
                'SubPart_Id'=>0,
                'InvoiceDate'=>'',
                'Mode'=>'',
                'POSInvoiceId'=>0,
                'BranchId'=>0,
                'DivisionId'=>0,
                'CustomerId'=>0,
                'FromDate'=>'',
                'ToDate'=>'',
                'OrganisationId'=>0,
                'EmployeeId'=>0,
                'EmployeeName'=>'',
                'Template'=>'',
                'User_Id'=>$user_id,
                'POSInvoiceNumber'=>'',
                'SearchKeyWord'=>'',
                'SubTotal'=>'',
                'IGSTTotal'=>0,
                'CGSTTotal'=>0,
                'SGSTTotal'=>0,
                'TotalGST'=>0,
                'Total'=>0,
                'TotalTax'=>0,
                'GrandTotal'=>0,
                'Quantity'=>0,
                'PartNumber'=>'',
                'SellingPrice'=>0,
                'CustomerName'=>'',
                'PaymentStatus'=>'',
                'PaymentTerms'=>'',
                'Remarks'=>'',
                'Branch_Name'=>'',
                'Status'=>'',
                'PageStatus'=>'',
                'TenderCash'=>0,
                'BalanceAmount'=>0,
                'CurrentPage'=>0,
                'PageSize'=>0,
                'Count'=>0,
                'Cash'=>0,
                'CustomerCredit'=>0,
                'DebitCard'=>0,
                'DebitCardNo'=>0,
                'DebitCardDate'=>0,
                'MobilePayment'=>0,
                'MobilePaymentType'=>'',
                'POS_Status'=>'Created',
                'DiscountType'=>0,
                'DiscountValue'=>0.00,
                'CreditNoteNumber'=>'',
                'CreditAmount'=>0,
                'CreditBalanceAmount'=>0,
                'CustomerCode'=>'',
                'Customer_ID'=>'',
                'CustomerType'=>0,
                'Company'=>'',
                'Branch'=>'',
                'GroupName'=>'',
                'SubGroupName'=>'',
                'CustomerViewModel'=>'',
                'Owner'=>'',
                'OwnerContactNo'=>'',
                'OwnerSocialMediaNo'=>'',
                'OwnerEmail'=>'',
                'AccountNo'=>'',
                'PAN'=>'',
                'TIN'=>'',
                'GSTNomber'=>'',
                'POCDesignation'=>'',
                'POCContactNo'=>'',
                'BuildingName'=>'',
                'Street'=>'',
                'City'=>'',
                'StateName'=>'',
                'Route'=>'',
                'PINCode'=>'',
                'Country_Id'=>0,
                'StateId'=>0,
                'DistrictName'=>'',
                'CustomerPOCName'=>'',
                'POCSocialMediaNo'=>'',
                'EmailID'=>'',
                'MobileNo'=>'',
                'BestTimeToContact'=>'',
                'CustomerStatus'=>false,
                'CustomerStatus1'=>'',
                'Website'=>'',
                'CreditNoteId'=>0,
                'PortName'=>'',
                'CounterUser'=>'',
                'TableName'=>'',
                'Counter'=>'',
                'ItemsNo'=>0,
                'TableId'=>0,
                'MergeStatus'=>false,
                'MergeInvoiceNumber'=>'',
                'Table_Id'=>0,
                'TableIds'=>'',
                'Table_Ids'=>'',
                'CategoryName'=>'',
                'SundryDebtorsList'=>'',
                'CustomerGroupList'=>'',
                'CustomerSubGroupList'=>'',
                'RouteList'=>'',
                'StateList'=>'',
                'CountryList'=>'',
                'POSInvoiceList'=>'',
                'TableList'=>'',
                'TableList1'=>'',
                'CustomerList'=>'',
                'BranchList'=>'',
                'InvoiceList'=>'',
                'CustomerTypeList'=>'',
                'DivisionList'=>'',
                'PortList'=>'',
                'State'=>'',
                'CreditNoteList'=>'',
                'PromoterList'=>'',
                'PartCategoryList'=>'',
                'PartList'=>'',
                'POSInvoiceParts'=>array(
                    'ModelPrevilege'=> '',
                    'Privileges'=> '',
                    'POSInvoicePartMapId'=> 0,
                    'POSInvoiceId'=> 0,
                    'Part_Id'=> $product[0]['product_id'],
                    'SubProductID'=> 0,
                    'SubProductCode'=> '',
                    'SubProductName'=> '',
                    'Discount'=> 0,
                    'DiscountPercentage'=> 0,
                    'AdditionalDiscount'=> 0,
                    'TaxableAmount'=> 0,
                    'AdditionalDiscountPercentage'=> 0,
                    'IsPartDiscountAmount'=> false,
                    'GST_Percentage'=> 0,
                    'SellingPrice'=> 0,
                    'IGST'=> 0,
                    'IGSTAmount'=> 0,
                    'CGST'=> 0,
                    'CGSTAmount'=> 0,
                    'SGST'=> 0,
                    'SGSTAmount'=> 0,
                    'Quantity'=> 1,
                    'Colour'=> '',
                    'Size'=> '',
                    'UoM'=> '',
                    'MRP'=> 0,
                    'StockQty'=> 0,
                    'Status'=> '',
                    'Total'=> 0,
                    'TotalTax'=> 0,
                    'TotalAmount'=> 0,
                    'PartNumber'=> 0,
                    'FirstSizeQty'=> 0,
                    'SecondSizeQty'=> 0,
                    'ThirdSizeQty'=> 0,
                    'ForthSizeQty'=> 0,
                    'FifthSizeQty'=> 0,
                    'Category_Name'=> '',
                    'CategoryName'=> '',
                    'PaymentStatus'=> '',
                    'PartDescription'=> '',
                    'HSNCode'=> '',
                    'ArticleNo'=> '',
                    'SizeRange'=> ''
                ),
                'BranchData'=>array(
                    'BranchId'=> 0,
                    'DivisionId'=> 0,
                    'BranchName'=> '',
                    'BranchFlag'=> '',
                    'StateId'=> 0,
                    'StateName'=> '',
                    'DistrictId'=> 0,
                    'DistrictName'=> '',
                    'Street'=> '',
                    'City'=> '',
                    'Country'=> '',
                    'PINCode'=> '',
                    'BranchCode'=> '',
                    'PageSize'=> 0,
                    'CurrentPage'=> 0,
                    'SearchKey'=> '',
                    'Count'=> 0,
                    'DelStatus'=> false,
                    'GSTIN'=> '',
                    'BankName'=> '',
                    'IFSC_Code'=> '',
                    'Bank_Branch'=> '',
                    'AccountNo'=> '',
                    'PhoneNumber'=> '',
                    'EmailId'=> '',
                    'List'=> '',
                    'Flag'=> '',
                    'DivisionName'=> '',
                    'DivisionNameList'=> '',
                    'DivisionBranchMappingId'=> 0,
                    'Organisation'=> '',
                    'OrganisationId'=> 0
                ),
                'CustomerData'=>''
            ));

            // dd($datapass);

            $url_cust_reg = "http://20.212.51.46:8081/api/POSUSHAS/SavePOSInvoice";
            $handle = curl_init($url_cust_reg);
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $datapass);
            curl_setopt($handle, CURLOPT_HTTPHEADER, $headers); 
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($handle);
            curl_close($handle);
            $return_response = json_decode($response,true);

            // dd($return_response);

            if(isset($return_response) && isset($return_response['Data']))
            {
                SaleOrder::where('id',$sale_id)->update(['crm_id'=>$return_response['Data']]);
            }
        }
        return $prod_datas;
    }


    /********GET values********/
        
    
    
    //VARIABLE PRICE ATTR
  public function variable_price_attr($prd_ass_id)
    {
        $variable=null;
        if($prd_ass_id){
        foreach(explode(',',$prd_ass_id)as $ass_id)
        {

        $extra_field=AssignedFields::where('is_deleted',0)->where('id',$ass_id)->first();
        if($extra_field){
        if(stripos($extra_field->PrdField->name, 'carat') !== false || stripos($extra_field->PrdField->name, 'Karat') !== false) 
        {
           $variable = get_variable_price_fn('XAU',$extra_field->PrdField_value_name->name);
        }
        
        else
        {
            $variable = get_variable_price_fn('XAU',$carat=null);
        }
        }
        else
       {
        $variable = get_variable_price_fn('XAU',$carat=null);
       }
        } 
       }
       else
       {
        $variable = get_variable_price_fn('XAU',$carat=null);
       }

        return (float)$variable;
            
            
        
   }

   //VARIABLE PRICE
  public function variable_price_fn($subcategory_code,$carat)
    {
     
      
            $metals = MetalRates::orderBy('id','DESC')->first();
            $list=null;
            if($metals){
            if($subcategory_code=="XAU" && $carat!=''){
            if($carat)
            {
                 $carat = preg_replace("/[^0-9]/", "", $carat );
            }
            
            $json = json_decode($metals->carat_rates,TRUE);
            $json_rates=$json['rates'];
           // return $json_rates['Carat 24K'];
           
            foreach($json_rates as $key=>$row)
            {
                $res = preg_replace("/[^0-9]/", "", $key );
                if($carat==$res){
               // $list= $key.'->'.$row;
               $fig = (float)$row*0.2;
               $list = round($fig,3);
                }
               
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
                            $list= round($fig,3);
                            //return $row;die;
                        }
                    }
                
                
            }
            if($list)
            {
                return $list;
           // return ['httpcode'=>200,'status'=>'success','message'=>getCurrency()->name.' '.$list.' per gram','data'=>['variable_price_string'=>number_format($list,3),'variable_price'=>$list,'currency'=>getCurrency()->name]];
            }
            else
            {
                return 0;
                //return ['httpcode'=>404,'status'=>'error','message'=>'Check the inputs/Empty','data'=>['response'=>'Check the inputs/Empty']];
            }
            //return $list;
            }
            
        
   }
   
    //wallet balance
  function wallet_balance($user_id)
  {
    $wallet=DB::table("usr_cust_wallet")->select(DB::raw("SUM(credit)-SUM(debit) as wallet"))->where('user_id',$user_id)->where('is_deleted',0)->first();
    if($wallet->wallet > 0)
    {
        return $wallet->wallet;
    }
    else
    {
        return false;
    }
  }

    //Product sale price
    public function get_sale_price($field_id){ 

     
       $current_date=Carbon::now();
       $rows = PrdPrice::where('is_deleted',0)->where('prd_id',$field_id)->whereDate('sale_end_date','>=',$current_date)->first();        
        if($rows){ 
        $return_val = (int)$rows->sale_price;
        return $return_val;
        }
        else
            { $return_val=0;
                return $return_val; }
        }


        public function get_price($field_id){ 

       $current_date=Carbon::now();
       $rows = PrdPrice::where('is_deleted',0)->where('prd_id',$field_id)->first();        
        if($rows){ 
        $return_val = $rows->price;
        return $return_val;
        }
        else
            { $return_val=0;
                return $return_val; }
        }

        function get_content($field_id,$lang){ 
     
        if($lang=='')
        { 
        $language =DB::table('glo_lang_lk')->where('is_active', 1)->first();
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
                 function get_product_image($prd_id){
        $data     =   [];
        
        //$admin_pro=Product::where('id',$prd_id)->first();
        
        
        $product_seller       =   ProductImage::where('prd_id',$prd_id)->where('is_deleted',0)->get();
        $product_admin       =   PrdAdminImage::where('prd_id',$prd_id)->where('is_deleted',0)->get();
        if(!empty($product_seller))
        {
            foreach($product_seller as $k=>$row){ 
                if($row->image)
                {
                $val['image']       =   config('app.storage_url').$row->image;
                }
                if($row->thumb)
                {
                $val['thumbnail']   =   config('app.storage_url').$row->thumb;
                }
                $data[]             =   $val;
            }
        }
        else if(!empty($product_admin))
        {
            foreach($product_admin as $k=>$row){ 
                if($row->image)
                {
                $val['image']       =   config('app.storage_url').$row->image;
                }
                if($row->thumbnail)
                {
                $val['thumbnail']   =   config('app.storage_url').$row->thumbnail;
                }
                $data[]             =   $val;
            }
        } 
        
        else{ $data     =   []; } return $data;
        
    }
        
        /************country************/
        public function get_country(Request $request)
        {
            $country =DB::table('countries')->where('is_deleted', 0)->get();
            return ['httpcode'=>200,'status'=>'success','message'=>'success','data'=>['country'=>$country]];
        }
        
        public function get_state(Request $request)
        {
            $validator=  Validator::make($request->all(),[
            'country_id' => ['required','numeric']
        ]);
        $input = $request->all();

            if ($validator->fails()) 
            {    
              return ['httpcode'=>400,'status'=>'error','errors'=>$validator->messages()];
            }
            else
            {
                    $country =DB::table('states')->where('country_id', $input['country_id'])->where('is_deleted', 0)->get();
                    return ['httpcode'=>200,'status'=>'success','message'=>'success','data'=>['state'=>$country]];
            }
        }
        
        public function get_city(Request $request)
        {
            $validator=  Validator::make($request->all(),[
            'state_id' => ['required','numeric']
        ]);
        $input = $request->all();

            if ($validator->fails()) 
            {    
              return ['httpcode'=>400,'status'=>'error','errors'=>$validator->messages()];
            }
            else
            {
                    $country =DB::table('cities')->where('state_id', $input['state_id'])->where('is_deleted', 0)->get();
                    return ['httpcode'=>200,'status'=>'success','message'=>'success','data'=>['city'=>$country]];
            }
        }
        
        public function findCountryByName(Request $request)
        {
            $validator=  Validator::make($request->all(),[
            'country_code' => ['required']
            ]);

            $input = $request->all();
            $country_code = $input['country_code'];
        
            if ($validator->fails()) 
            {    
              return ['httpcode'=>400,'status'=>'error','errors'=>$validator->messages()];
            }
            else
            {
                $country =DB::table('countries')->where('sortname', "$country_code")->where('is_deleted', 0)->first();
                if($country) { 
                    return ['httpcode'=>200,'status'=>'success','message'=>'success','data'=>['country'=>$country]];
                }else {
                    return ['httpcode'=>400,'status'=>'error','errors'=>"Not Found"];
                }
            }
        }

            public function findStateByName(Request $request)
             {
                $validator=  Validator::make($request->all(),[
                'country_id' => ['required','numeric'],
                'name' => ['required']
                ]);
                $input = $request->all();
                $country_id = $input['country_id'];
                $name = $input['name'];
        
                if ($validator->fails()) 
                {    
                return ['httpcode'=>400,'status'=>'error','errors'=>$validator->messages()];
                }
                else
                {
                $state =DB::table('states')->where('country_id', $input['country_id'])->where('state_name',"$name")->where('is_deleted', 0)->first();
                if($state) {
                    return ['httpcode'=>200,'status'=>'success','message'=>'success','data'=>['state'=>$state]];
                }else {
                    return ['httpcode'=>400,'status'=>'error','errors'=>"Not Found"];
                }
                }
         }

        public function findCityByName(Request $request)
        {
            $validator=  Validator::make($request->all(),[
            'state_id' => ['required','numeric'],
            'name' => ['required']
            ]);
            $input = $request->all();
            $state_id = $input['state_id'];
            $name = $input['name'];
        
            if ($validator->fails()) 
            {    
            return ['httpcode'=>400,'status'=>'error','errors'=>$validator->messages()];
            }
            else
            {
            $cities =DB::table('cities')->where('state_id', $input['state_id'])->where('city_name', "$name")->where('is_deleted', 0)->first();
            if($cities){
                 return ['httpcode'=>200,'status'=>'success','message'=>'success','data'=>['city'=>$cities]];
            }else {
                return ['httpcode'=>400,'status'=>'error','errors'=>"Not Found"];
            }

            }
        }
        
        
        function order_invoice(Request $request)
  {
   if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }

        $validator=  Validator::make($request->all(),[
        'sale_id' => ['required','numeric']
        ]);
        if ($validator->fails()) 
        {    
        return ['httpcode'=>400,'status'=>'error','errors'=>$validator->messages()];
        }

        $user_id = $user['user_id'];
        $input = $request->all();
        $id = $input['sale_id'];
        

            $data['order']              =    SaleOrder::where('id',$id)->first();
            $user_id = $data['order']->cust_id; 
            $data['user_id']               =   $user_id;

            $data['customer_mst']       =    CustomerMaster::where('id',$user_id)->first();
            $data['telecom']            =    CustomerTelecom::where('id',$data['customer_mst']->email)->get();
            $data['info']               =    CustomerInfo::where('user_id',$user_id)->first();
            $data['wallet']             =    DB::table("usr_cust_wallet")->select(DB::raw("SUM(credit)-SUM(debit) as wallet"))->where("is_deleted",0)->where("user_id",$user_id)->first();
            
            //$seller_e                   =    Seller::where('id',$data['order']->seller_id)->first()->phone;
            //$data['seller_telecom']     =    SellerTelecom::where('id',$seller_e)->first();
            //$data['seller_address']  = Store::where('seller_id',$data['order']->seller_id)->first();
           // if($data['seller_address']) {
           // $data['seller_address_city']  = getCities($data['seller_address']->city_id);
           // }
            $data['order_items']             = SaleorderItems::where('sales_id',$id)->get();

            // dd($data);
          
            $usersdata = ['users'=>"Test"];
            $pdf = PDF::loadView('pdf.order_invoice', $data);
            $pdf->setOptions(['isPhpEnabled' => true,'isRemoteEnabled' => true]);
            $filename = "order_invoice.pdf";
            $insId = $id;
             $path               =   '/app/public/order/invoice/'.$insId."/pdf/";
             
            $destinationPath = storage_path($path);
            if (!file_exists($destinationPath)) { mkdir($destinationPath, 755, true);}
            // Save file to the directory
            $pdf->save($destinationPath.$filename);
            $imgUpload          =   uploadFile($path,$filename);
            $file_url = config('app.storage_url').$path.$filename;
            //Download Pdf
           

            return ['httpcode'=>200,'status'=>'success','message'=>'success','data'=>['file_url'=>$file_url]];
        
     }
}
