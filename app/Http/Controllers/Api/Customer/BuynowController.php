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
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\PrdAdminImage;
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
use App\Models\AssignedFields;
use App\Models\MetalRates;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use Carbon\Carbon;
use App\Rules\Name;
use Validator;

class BuynowController extends Controller
{
    public function view(Request $request)
    {
    	if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
        $user_id = $user['user_id'];
        $lang=$request->lang_id;
        $validator=  Validator::make($request->all(),[
            'access_token' => ['required'],
            'product_id'=>['required','numeric'],
            'quantity'=>['required','numeric','min:1'],
            'prd_assign_id'=>['nullable'],
            'currency' => ['required'],
        ]);
        $input = $request->all();
        

    if ($validator->fails()) 
    {    
      return ['httpcode'=>400,'status'=>'error','message'=>'Invalid parameters','data'=>['errors'=>$validator->messages()]];
    }
    else
    {
    	$lang=1;
    	$total_cost=0;
        $grand_tot=0;
        $payment_gateway_chrg=0;
        $shipping_chrg =0;
        $rewards=[];
        $currency_code = $input['currency'];
        $currency_rate = get_currency_rate($currency_code);
        

        //Reward first purchase

        $sale_before  =  SaleOrder::where('cust_id',$user_id)->count();
            if($sale_before<1)
            {
                $reward = Reward::where('is_active',1)->where('is_deleted',0)->where('ord_min_amount', '<=', $total_cost)->where('ord_type','discount')->first();
                if($reward){
                $rewards= ['reward_id'=>$reward->id,'reward_amt'=>$reward->ord_amount];
                }
            }

    	$product = Product::where('is_active',1)->where('is_deleted',0)->where('id',$input['product_id'])->first();
    	if($product)
    	{
    		$store_active = Store::where('service_status',1)->where('is_active',1)->where('seller_id',$product->seller_id)->first();
             if($store_active)
            {
    		$products_seller['seller']                = array('seller_id'=>$product->seller_id,'seller'=>$product->Store($product->seller_id)->store_name);
            $products_seller['seller']['product']['product_id']   = $product->id;
            $products_seller['seller']['product']['product_name'] = $this->get_content($product->name_cnt_id,$lang);
            $products_seller['seller']['product']['image'] = $this->get_product_image($product->id);
            //////SUBCATEGORY PRICE
                        if($product->subCategory->code=='XAU')
                    {
                        $gold=true;
                        $subcategory_code='XAU';
                        $variable_price= $this->variable_price_attr($input['prd_assign_id']);
                        $price = ($variable_price*(float)$product->weight)+(float)$product->fixed_price;
                        $tax = getTax()->value;
                        $qty_price = $price*$input['quantity'];
                        
                         $mjs_fee=getCustomerFee()->mjs_fee;
                        $pg_fee=getCustomerFee()->pg_fee;
                        $mjs_fee_amt = ($mjs_fee/100)*$qty_price;
                        $pg_fee_amt =($pg_fee/100)*$qty_price;
                        $qty_price += $mjs_fee_amt + $pg_fee_amt;
                        
                        $tax_amount = ($tax/100)*$qty_price;
                        $tot_price = $price+$tax_amount;
                        $tot_amt_tax= $qty_price+$tax_amount; //$tot_price*$input['quantity'];
                    }
                    else
                    {
                        $gold= false;
                        $variable_price =0;
                        $price = (float)$product->fixed_price;
                        $qty_price = $price*$input['quantity'];
                        $tax = getTax()->value;
                        
                        $mjs_fee=getCustomerFee()->mjs_fee;
                        $pg_fee=getCustomerFee()->pg_fee;
                        $mjs_fee_amt = ($mjs_fee/100)*$qty_price;
                        $pg_fee_amt =($pg_fee/100)*$qty_price;
                        $qty_price += $mjs_fee_amt + $pg_fee_amt;
                        
                        $tax_amount = ($tax/100)*$qty_price;
                        $tot_price = $price+$tax_amount;
                        $tot_amt_tax=$qty_price+$tax_amount; //$tot_price*$input['quantity'];
                    }
                    //////SUBCATEGORY PRICE
                    
                    
                    $tot_price = $tot_price*$currency_rate;
                    $tot_amt_tax = $tot_amt_tax*$currency_rate;
                    
                     if($input['prd_assign_id']!='')
                     {
                        

                     $products_seller['seller']['product']['attr_list']=true;  
                        $i=1;
                     foreach(explode(',',$input['prd_assign_id']) as $keys)
                     {   
                        $ass_id = (int)$keys;
                     $extra_field=AssignedFields::where('is_deleted',0)->where('id',$ass_id)->first();
                    
                     $products_seller['seller']['product']['prd_assign_id'.$i] =$extra_field->id;
                     $products_seller['seller']['product']['prd_assign_name'.$i] =$extra_field->field_value;
                     $i++;
                     }
                     }
                     else
                     {
                        $products_seller['seller']['product']['attr_list']=false;
                     }

                    
                    $products_seller['seller']['product']['quantity']=$product->quantity;
                    $products_seller['seller']['product']['seller_id']=$product->seller_id;
                    $products_seller['seller']['product']['currency']=getCurrency()->name;
                   // $products_seller['seller']['product']['tax']=getTax()->value;
                    if($product->brand_id)
                    {
                        $products_seller['seller']['product']['brand_id']=$product->brand_id;
                        $products_seller['seller']['product']['brand_name']=$this->get_content($product->brand->brand_name_cid,$lang);
                    }


                   // $actual_price =$this->get_actual_price($prod_data->product_id);
                    $products_seller['seller']['product']['unit_actual_price']=$tot_price;//$actual_price;
                    //$tot_actual=(int) $actual_price * (int) $qty;
                   $products_seller['seller']['product']['total_actual_price']=$tot_amt_tax;//(int)$tot_actual;
                    $products_seller['seller']['product']['total_discount_price']=0;
                    //$tax_amt=$prod_data->getTaxValue($prod_data->tax_id);
                    //$total_tax_amount =$tot_actual * ($tax_amt/100);
                    //$prd_list['total_tax_value']=$tax_amount;
                     
                    
                    //Available offers for this product
            
           
                    $data             =  $products_seller;

                    $grand_tot = $tot_amt_tax+$payment_gateway_chrg;
                    $grand_tot = round($grand_tot);

                     return ['httpcode'=>200,'status'=>'success','message'=>'Success','data'=>['product'=>$data,'currency'=>getCurrency()->name,'reward'=>$rewards,'total_cost'=>$tot_amt_tax,'shipping_chrg'=>$shipping_chrg,'payment_gateway_chrg'=>$payment_gateway_chrg,'grand_total'=>$grand_tot]];
            }
            else
            {
            	return ['httpcode'=>404,'status'=>'error','message'=>'Store is inactive','data'=>['errors'=>'Store is inactive']];
            }

    	}
    	else
    	{
    		return ['httpcode'=>404,'status'=>'error','message'=>'No product found','data'=>['errors'=>'No product found']];
    	}
    }

    }
 
   public function buynow(Request $request)
   {

   	if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
        $user_id = $user['user_id'];
        $user_email = $user['email'];
        $lang=$request->lang_id;
        // dd($request->all());
        // //print_r($request->all());
        // die;
        
        $validator=  Validator::make($request->all(),[
            'access_token'          => ['required'],
            'seller_array'          => ['required','array'],
            'is_platform_coupon'    => ['nullable','boolean'],
            'platform_coupon_id'    => ['nullable','numeric'],
            'platform_discount_type'=> ['nullable','string',Rule::in(['discount', 'cashback'])],
            'total_amt'             => ['required','numeric'],
            'discount_amt'          => ['required','numeric'],
            'is_reward'             => ['nullable','numeric'],
            'reward_amt'            => ['nullable'],
            'reward_id'             => ['nullable','numeric'],
            'payment_type'          => ['required'],
            'address_id'            => ['required'],
            

        ]);
        $input = $request->all();

    if ($validator->fails()) 
    {    
      return ['httpcode'=>400,'status'=>'error','message'=>'Invalid parameters','data'=>['errors'=>$validator->messages()]];
    }
    else
    {
   	//ADDRESS
    	$input['e_money_amt']=false;
          $addr_list =  CustomerAddress::where('id',$input['address_id'])->first();
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
                                                  'updated_at'        => date("Y-m-d H:i:s")
                                                  ]);
        $parent_sale_id  = $insert_sale_parent->id;                                          
          
          if($input['is_platform_coupon']==true)
        {
            $pltform_coupon_id = $input['platform_coupon_id'];
             $coupon_data= Coupon::where('id',$pltform_coupon_id)->first();
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
            if($input['platform_discount_type']=='cashback')
            {                                        
        //CASHBACK
                $cashback = CustomerWallet_Model::create(['user_id'    =>  $user_id,
                                                          'source_id'  =>  $parent_sale_id,
                                                          'source'     =>  'Platform coupon',
                                                          'credit'     =>  $input['discount_amt'],
                                                          'is_active'  =>  1,
                                                          'is_deleted' =>  0,
                                                          'created_at'    =>date("Y-m-d H:i:s"),
                                                          'updated_at'    =>date("Y-m-d H:i:s")]);  
            }
            
        }
        
        // reward application
        $sale_before  =  SaleOrder::where('cust_id',$user_id)->count();
        if($sale_before<1)
        {
            $reward = Reward::where('is_active',1)->where('is_deleted',0)->where('ord_min_amount', '<=', $input['total_amt'])->first();
            if($reward)
            {
                if($reward->ord_type=='cashback')
                {
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
            if($cust_master->invited_by!='' && $cust_master->invited_by!=0)
                {
                   if($reward->rwd_type==2 || $reward->rwd_type==3)
                   {
                       $typ_pts = $reward->rewardType_purchase()->points;
                       if($typ_pts!='')
                       {
                           $credit_value = $typ_pts * $reward->point_val;
                       }
                       else
                       {
                           $credit_value =1 * $reward->point_val;
                       }
                       $cashback_reward_invite = CustomerWallet_Model::create(['user_id'    =>  $cust_master->invited_by,
                                                          'source_id'  =>  $reward->id,
                                                          'source'     =>  'Reward',
                                                          'credit'     =>  $credit_value,
                                                          'is_active'  =>  1,
                                                          'is_deleted' =>  0,
                                                          'created_at'    =>date("Y-m-d H:i:s"),
                                                          'updated_at'    =>date("Y-m-d H:i:s")]); 
                   }
                }
            }
        }
        
        //Wallet used
        if($wallet_amt>0)
        {
            $wallet_usage = CustomerWallet_Model::create(['user_id'    =>  $user_id,
                                                          'source_id'  =>  $parent_sale_id,
                                                          'source'     =>  'Order',
                                                          'debit'      =>  $wallet_amt,
                                                          'is_active'  =>  1,
                                                          'is_deleted' =>  0,
                                                          'created_at'    =>date("Y-m-d H:i:s"),
                                                          'updated_at'    =>date("Y-m-d H:i:s")]); 
        }
        

            $latestOrder = SaleOrder::orderBy('id','DESC')->first();
            $saleorder_id = date('y').date('m').str_pad($latestOrder->id + 1, 6, "0", STR_PAD_LEFT);
            // echo $saleorder_id;
            // die;
            
            $seller_arrays =$input['seller_array'];
            
            foreach($seller_arrays as $rows)
            {   
                
               
                $discount_amt_sale = 0;
                
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
                
                $product = Product::where('id',$rows['product_id'])->first();

               if($product->subCategory->code=='XAU')
                    {
                        $gold=true;
                        $subcategory_code='XAU';
                        $variable_price= $this->variable_price_attr($rows['prd_assign_id']);
                        $price = ($variable_price*(float)$product->weight)+(float)$product->fixed_price;
                        $tax = getTax()->value;
                        $qty_price = $price*$rows['quantity'];
                        
                        $mjs_fee=getCustomerFee()->mjs_fee;
                        $pg_fee=getCustomerFee()->pg_fee;
                        $mjs_fee_amt = ($mjs_fee/100)*$qty_price;
                        $pg_fee_amt =($pg_fee/100)*$qty_price;
                        $qty_price += $mjs_fee_amt + $pg_fee_amt;
                        
                        
                        $tax_amount = ($tax/100)*$qty_price;
                        $tot_price = $price+$tax_amount;
                        $tot_amt_tax= $qty_price+$tax_amount; //$tot_price*$rows['quantity'];
                    }
                    else
                    {
                        $gold= false;
                        $variable_price =0;
                        $price = (float)$product->fixed_price;
                        $qty_price = $price*$rows['quantity'];
                        $tax = getTax()->value;
                        
                        $mjs_fee=getCustomerFee()->mjs_fee;
                        $pg_fee=getCustomerFee()->pg_fee;
                        $mjs_fee_amt = ($mjs_fee/100)*$qty_price;
                        $pg_fee_amt =($pg_fee/100)*$qty_price;
                        $qty_price += $mjs_fee_amt + $pg_fee_amt;
                        
                        $tax_amount = ($tax/100)*$qty_price;
                        $tot_price = $price+$tax_amount;
                        $tot_amt_tax= $qty_price+$tax_amount; //$tot_price*$rows['quantity'];
                    }
                
                $grnd_tot_sale = $tot_amt_tax - $discount_amt_sale;
                //$total = $this->get_total_seller_product($rows->seller_id); 
                $create_saleorder = SaleOrder::create(['org_id' => 1,
                'parent_sale_id'  =>$parent_sale_id,
                'order_id'        => $saleorder_id,
                'cust_id'         => $user_id,
                'seller_id'       => $rows['seller_id'],
                'total'           => $qty_price,
                'discount'        => $discount_amt_sale,
                'tax'             => $tax_amount,
                'shiping_charge'  => $shipping_chrg,
                'packing_charge'  => $packing_chrg,
                'wallet_amount'   => $wallet_amt,
                'g_total'         => $grnd_tot_sale,
                'ecom_commission' => 0,
                // 'discount_type'   => $rows['discount_type'],  
                // 'coupon_id'       => $rows['coupon_id'],
                'order_status'    => 'pending',
                'payment_status'  => 'pending',
                'shipping_status' => 'pending',
                'cancel_process'  => 0,   
                'created_at'    =>date("Y-m-d H:i:s"),
                'updated_at'    =>date("Y-m-d H:i:s")]);
                $sale_id  = $create_saleorder->id;
                
                

                //Payment
                $saleorder_payment = SalesOrderPayment::create(['org_id' => 1,
                'sales_id'         => $sale_id,
                'payment_method_id'=> $input['payment_type'],
                'payment_type'     => 'Cash on Delivery',
                'transaction_id'   => '',
                'payment_data'     => '',
                'amount'           => $grnd_tot_sale,
                'payment_status'  => 'pending']);
               
               


                $create_saleorder = SaleorderItems::create([
                'sales_id'        => $sale_id,
                'parent_id'       => $sale_id,
                'prd_id'          => $rows['product_id'],
                'attr_ids'		  => $rows['prd_assign_id'],
                'prd_type'        => $product->product_type,
                'prd_name'        => $this->get_content($product->name_cnt_id,$lang),
                'price'           => $price,
                'qty'             => $rows['quantity'],
                'total'           => $qty_price,
                'discount'        => 0,
                'tax'             => $tax_amount,
                'row_total'       => $qty_price + $tax_amount,
                'coupon_id'       => '', 
                'created_at'    =>date("Y-m-d H:i:s"),
                'updated_at'    =>date("Y-m-d H:i:s"),
                'is_deleted'    =>0]); 
                
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
                
                
                //Notitfication
                $from       = $user_id; 
                $utype      = 3;
                $to         = $user_id; 
                $ntype      = 'order_placed';
                $title      = 'New order has been placed';
                $desc       = 'New order has been placed. Order ID:'.$saleorder_id;
                $refId      = $sale_id;
                $reflink    = 'customer/order/detail';
                $notify     = 'customer';
                addNotification($from,$utype,$to,$ntype,$title,$desc,$refId,$reflink,$notify);
            }   

            
            
            
            
            return ['httpcode'=>200,'status'=>'success','message'=>'Order placed','data'=>['order_id'=>$saleorder_id]];
        }
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
                if($row->thumbnail)
                {
                $val['thumbnail']   =   config('app.storage_url').$row->thumbnail;
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



        //VARIABLE PRICE ATTR
  public function variable_price_attr($prd_ass_id)
    {
        $variable=null;
        if($prd_ass_id){
        foreach(explode(',',$prd_ass_id)as $ass_id)
        {

        $extra_field=AssignedFields::where('is_deleted',0)->where('id',$ass_id)->first();
        if(stripos($extra_field->PrdField->name, 'carat') !== false || stripos($extra_field->PrdField->name, 'Karat') !== false) 
        {
           $variable = $this->variable_price_fn('XAU',$extra_field->PrdField_value_name->name);
        }
        else
        {
            $variable = $this->variable_price_fn('XAU',$carat=null);
        }
        } 
       }
       else
       {
       	$variable = $this->variable_price_fn('XAU',$carat=null);
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
                 $carat = preg_replace("/[^0-9]/", "", $request->carat );
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



}
