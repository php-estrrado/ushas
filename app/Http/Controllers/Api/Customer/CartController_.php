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
use App\Models\Reward;
use App\Models\RewardType;
use App\Models\RelatedProduct;
use App\Models\AssignedAttribute;
use App\Models\Wishlist;
use App\Models\WishlistItem;

use App\Models\PrdFields;
use App\Models\PrdFieldsValue;
use App\Models\CategoryFields;
use App\Models\AssignedFields;
use App\Models\PrdAttribute;
use App\Models\PrdAttributeValue;
use App\Models\AssConfigAttribute;
use App\Models\MetalRates;

use App\Models\CustomerAddress;
use App\Models\Country;

use Carbon\Carbon;
use App\Rules\Name;
use Validator;

class CartController extends Controller
{
    public function index(Request $request)
    {
        if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
        $user_id = $user['user_id'];
        $lang=$request->lang_id;
        $validator=  Validator::make($request->all(),[
            'access_token' => ['required']
        ]);
        $input = $request->all();

    if ($validator->fails()) 
    {    
      return ['httpcode'=>400,'status'=>'error','message'=>'Invalid parameters','data'=>['errors'=>$validator->messages()]];
    }
    else
    {
                //CURRENCY
                if($request->currency_code)
                {
                    $crv = get_currency_rate($request->currency_code);
                    $currency = $request->currency_code;
                }
                else
                {
                    $crv=1;
                    $currency = getCurrency()->name;
                }
                
       $cart = Cart::join('usr_cart_item','usr_cart.id','=','usr_cart_item.cart_id')
                        ->where('usr_cart.user_id',$user_id)    
                        ->where('usr_cart.is_active',1)
                        ->where('usr_cart.is_deleted',0)
                        ->where('usr_cart_item.is_active',1)
                        ->where('usr_cart_item.is_deleted',0)
                        ->get();
        if(count($cart)>0)
        {
            $cart_count = count($cart);
           // $wallet = $this->wallet_balance($user_id);
            
            $payment_gateway_chrg=0;
            $shipping_chrg =0;
            //Seller cart products
            /*$cart_bySeller = Cart::join('usr_cart_item','usr_cart.id','=','usr_cart_item.cart_id')
                        ->where('usr_cart.user_id',$user_id)    
                        ->where('usr_cart.is_active',1)
                        ->where('usr_cart.is_deleted',0)
                        ->where('usr_cart_item.is_active',1)
                        ->where('usr_cart_item.is_deleted',0)
                        ->pluck('product_id');*/
//$seller_products = Product::whereIn('id',$cart_bySeller)->where('is_active',1)->where('is_deleted',0)->groupBy('seller_id')->get();
            //return $seller_products;die;
           // $seller_product_list=[];
            $products=[];
            $rewards=[];
            $access_token = fedexAuth();
            
            
            foreach($cart as $rows)
            {
                $products[] = $this->get_cart_products($rows->product_id,$rows->cart_id,$rows->quantity,$lang,$crv);
            }   

            $filter = array_filter($products);
            $tot_tax= $tot_tax_cal =0;
            $total_cost=$total_cost_cal=0;
            $grand_tot=$grand_tot_cal=0;
            if(count($filter)>0)
            {
              
                foreach($filter as $value)
                {
                    $tot_tax += $value['total_tax_value'];
                    $tot_tax_cal += $value['total_tax_value_cal'];
                    if($value['total_discount_price']==0)
                    {
                     $total_cost +=(float)$value['total_actual_price'];
                     $total_cost_cal +=(float)$value['total_actual_price_cal'];
                   
                    }
                    else
                    {
                      $total_cost +=(float)$value['total_discount_price'];  
                      $total_cost_cal +=(float)$value['total_discount_price'];
                    }
                }

                //$grand_tot = $tot_tax+$total_cost;
                 //$grand_tot = $tot_tax+$total_cost;
                $grand_tot = $total_cost+$payment_gateway_chrg*$crv+$shipping_chrg*$crv;
                $grand_tot = round($grand_tot);
                
                //To calculate
                $grand_tot_cal = $total_cost_cal+$payment_gateway_chrg+$shipping_chrg;
                $grand_tot_cal = round($grand_tot_cal);
            }
            $sale_before  =  SaleOrder::where('cust_id',$user_id)->count();
            if($sale_before<1)
            {
                $reward = Reward::where('is_active',1)->where('is_deleted',0)->where('ord_min_amount', '<=', $total_cost)->where('ord_type','discount')->first();
                if($reward){
                $rewards= ['reward_id'=>$reward->id,'reward_amt'=>$reward->ord_amount];
                }
            }
            return ['httpcode'=>200,'status'=>'success','message'=>'Success','data'=>['cart_count'=>$cart_count,
            'product'=>$filter,'currency'=>$currency,'reward'=>$rewards,
            'total_cost'=>round($total_cost),'total_cost_cal'=>round($total_cost_cal),
            'total_tax'=>round($tot_tax),'total_tax_cal'=>round($tot_tax_cal),'shipping_chrg'=>round($shipping_chrg*$crv),
            'shipping_chrg_cal'=>round($shipping_chrg),'payment_gateway_chrg'=>$payment_gateway_chrg,
            'payment_gateway_chrg'=>$payment_gateway_chrg*$crv,'grand_total'=>$grand_tot,'grand_total_cal'=>$grand_tot_cal]];       
         }
         else
         {
            return ['httpcode'=>404,'status'=>'error','message'=>'Cart is empty','data'=>['errors'=>'Cart is empty']];
         }  
    }

    }
    
    function getShipping($seller_id,$user_id,$products,$access_token){

        $customer_address = CustomerAddress::where('user_id',$user_id)->where('is_default',1)->where('is_deleted',0)->first();
        $store_address = Store::where('service_status',1)->where('is_active',1)->where('seller_id',$seller_id)->first();
        if($customer_address) {

            $seller_addr = getCities($store_address->city_id);
            $seller_addr['address'] =$store_address->address;
            $seller_addr['address2'] =$store_address->address2;
            $seller_addr['postalCode'] =$store_address->zip_code;
            $seller_addr['countryCode'] =Country::where('id',$store_address->country_id)->first()->sortname;

            $customer_addr = getCities($customer_address->city_id);
            $customer_addr['address'] =$customer_address->address;
            $customer_addr['address2'] =$customer_address->address2;
            $customer_addr['postalCode'] =$customer_address->pincode;
             if($customer_address->country_id){ $customer_addr['countryCode'] = Country::where('id',$customer_address->country_id)->first()->sortname; }else{ $customer_addr['countryCode'] = ""; }
            
         
            if($store_address->country_id == 229) {
                 if($customer_address->country_id == $store_address->country_id)
            {
                // domestic all sellers united arab emirates

              return  domestic_rate_request($seller_addr,$customer_addr,$products,$access_token);
            //   return international_rate_request($seller_addr,$customer_addr,$products);

            }else {
                // international
             return  international_rate_request($seller_addr,$customer_addr,$products,$access_token);
            }
            
            }else {
                return 0;
               // return "Seller Address is outside UAE.";
            }
           
        }
    }
    
    //Total cart price
    public function cart_total(Request $request)
    {
        if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
        $user_id = $user['user_id'];
        $lang=$request->lang_id;
        $validator=  Validator::make($request->all(),[
            'access_token' => ['required']
        ]);
        $input = $request->all();

    if ($validator->fails()) 
    {    
      return ['httpcode'=>400,'status'=>'error','message'=>'Invalid parameters','data'=>['errors'=>$validator->messages()]];
    }
    else
    {
       $cart = Cart::join('usr_cart_item','usr_cart.id','=','usr_cart_item.cart_id')
                        ->where('usr_cart.user_id',$user_id)    
                        ->where('usr_cart.is_active',1)
                        ->where('usr_cart.is_deleted',0)
                        ->where('usr_cart_item.is_active',1)
                        ->where('usr_cart_item.is_deleted',0)
                        ->get();
        if(count($cart)>0)
        {                
            foreach($cart as $rows)
            {
                $products[] = $this->get_cart_products($rows->product_id,$rows->cart_id,$rows->quantity,$lang);
            }   

            $filter = array_filter($products);
            $tot_tax= $tot_tax_cal =0;
            $total_cost=$total_cost_cal=0;
            $grand_tot=0;
            if(count($filter)>0)
            {
                foreach($filter as $value)
                {
                    $tot_tax += $value['total_tax_value'];
                    $tot_tax_cal += $value['total_tax_value_cal'];
                    if($value['total_discount_price']==0)
                    {
                     $total_cost +=(int)$value['total_actual_price'];
                     $total_cost_cal +=(int)$value['total_actual_price_cal'];
                    }
                    else
                    {
                      $total_cost +=(int)$value['total_discount_price'];  
                      $total_cost_cal +=(int)$value['total_discount_price'];
                    }
                }

                $grand_tot = number_format($tot_tax+$total_cost,2);
            }
            return ['httpcode'=>200,'status'=>'success','message'=>'Success','data'=>['currency'=>getCurrency()->name,'total_tax'=>number_format($tot_tax,2),'total_cost'=>number_format($total_cost,2),'grand_total'=>$grand_tot]];       
         }
         else
         {
            return ['httpcode'=>404,'status'=>'error','message'=>'Cart is empty','data'=>['errors'=>'Cart is empty']];
         }  
    }

    }

    public function delete_cart(Request $request)
    {
        if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
        $validator=  Validator::make($request->all(),[
            'cart_id' => ['required'],
            'access_token'=>['required']
        ]);
        $input = $request->all();
        $user_id = $user['user_id'];

    if ($validator->fails()) 
    {    
      return ['httpcode'=>400,'status'=>'error','message'=>'Invalid parameters','data'=>['errors'=>$validator->messages()]];
    }
    else
    {
        foreach(explode(',',$input['cart_id']) as $cart)
        {
            $cart_product = CartItem::where('cart_id',$cart)->first();
            if($cart_product){
            Cart::where('id',$cart)->update([
            'is_active'=>0,
            'is_deleted'=>1,
            'updated_by'=>$user_id,
            'updated_at'=>date("Y-m-d H:i:s")]);

            CartItem::where('cart_id',$cart)->update([
            'is_active'=>0,
            'is_deleted'=>1,
            'updated_by'=>$user_id,
            'updated_at'=>date("Y-m-d H:i:s")]);

            CartHistory::create(['org_id' => 1,
                'user_id' => $user_id,
                'product_id' => $cart_product->product_id,
                'quantity'  => 0,
                'action'=>'delete',
                'is_active'=>1,
                'is_deleted'=>0,
                'created_by'=>$user_id,
                'updated_by'=>$user_id,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")]);
           }
           else
           {
            return ['httpcode'=>400,'status'=>'error','message'=>'Invalid cart id','data'=>['errors'=>'Invalid cart id']];
           }
        }

    return ['httpcode'=>200,'status'=>'success','message'=>'Successfully Deleted','data'=>['response'=>'Successfully Deleted']];
    }

    }

    
    
    

   function get_cart_products($prd_id,$cart_id,$qty,$lang,$crv){
        $data     =   [];

     $prod_data       =Product::join('usr_cart_item','prd_products.id','=','usr_cart_item.product_id')
                            ->join('usr_cart','usr_cart_item.cart_id','=','usr_cart.id')
                            ->where('usr_cart.id',$cart_id)    
                            ->where('usr_cart.is_active',1)
                            ->where('usr_cart.is_deleted',0)
                            ->where('usr_cart_item.is_active',1)
                            ->where('usr_cart_item.is_deleted',0)
                            ->where('prd_products.is_active',1)->where('prd_products.is_deleted',0)->where('prd_products.is_approved',1)->where('prd_products.id',$prd_id)
                            ->first();
        
        // $prod_data       =   Product::where('is_active',1)->where('is_deleted',0)->where('id',$prd_id)->first();
            if($prod_data)   { 
                $store_active = Store::where('service_status',1)->where('is_active',1)->where('seller_id',$prod_data->seller_id)->first();
                    if($store_active)
                    {
                    if($prod_data->weight>0)
                    {
                        $weight=$prod_data->weight;
                    }
                    else
                    {
                        $weight=1;
                    }
                    //////SUBCATEGORY PRICE
                        if($prod_data->subCategory->code=='XAU')
                    {
                        $gold=true;
                        $subcategory_code='XAU';
                        $variable_price= $this->variable_price_attr($prod_data->prd_assign_id);
                        //return $variable_price;die;
                        $tax = getTax()->value;
                        $price = (($variable_price*(float)$weight)+(float)$prod_data->fixed_price)*$crv;
                       
                        $qty_price = $price*$prod_data->quantity;
                        
                        //To calculate
                        $price_cal     = ($variable_price*(float)$weight)+(float)$prod_data->fixed_price;
                        $qty_price_cal = $price_cal*$prod_data->quantity;
                        
                         $mjs_fee=getCustomerFee()->mjs_fee;
                        $pg_fee=getCustomerFee()->pg_fee;
                        $prd_list['mjs_fee']= ($mjs_fee/100)*$qty_price;
                        $prd_list['pg_fee']=($pg_fee/100)*$qty_price;
                        $qty_price += $prd_list['mjs_fee'] + $prd_list['pg_fee'];
                        
                        //To calculate
                        $prd_list['mjs_fee_cal']= ($mjs_fee/100)*$qty_price_cal;
                        $prd_list['pg_fee_cal']=($pg_fee/100)*$qty_price_cal;
                        $qty_price_cal += $prd_list['mjs_fee_cal'] + $prd_list['mjs_fee_cal'];
                        
                        
                        $tax_amount = ($tax/100)*$qty_price;
                        $tot_price = $price+$tax_amount;
                       $tot_qty_price = round($qty_price)+round($tax_amount); ; //$tot_price*$prod_data->quantity;
                       
                       //To calculate
                       $tax_amount_cal = ($tax/100)*$qty_price_cal;
                        $tot_price_cal = $price_cal+$tax_amount_cal;
                       $tot_qty_price_cal = ($qty_price_cal+$tax_amount_cal);
                    }
                    else
                    {
                        $gold= false;
                        $variable_price =0;
                        $tax = getTax()->value;
                        $price = (float)$prod_data->fixed_price*$crv;
                        $qty_price = $price*$prod_data->quantity;
                        
                        //to calculate
                        $price_cal = (float)$prod_data->fixed_price;
                        $qty_price_cal = $price_cal*$prod_data->quantity;
                        
                        $mjs_fee=getCustomerFee()->mjs_fee;
                        $pg_fee=getCustomerFee()->pg_fee;
                        $prd_list['mjs_fee']= ($mjs_fee/100)*$qty_price;
                        $prd_list['pg_fee']=($pg_fee/100)*$qty_price;
                        $qty_price += $prd_list['mjs_fee'] + $prd_list['pg_fee'];
                        
                        //to calculate
                        $prd_list['mjs_fee_cal']= ($mjs_fee/100)*$qty_price_cal;
                        $prd_list['pg_fee_cal']=($pg_fee/100)*$qty_price_cal;
                        $qty_price_cal += $prd_list['mjs_fee_cal'] + $prd_list['pg_fee_cal'];
                        
                        
                        $tax_amount = ($tax/100)*$qty_price;
                        $tot_price = $price+$tax_amount;
                        $tot_qty_price = round($qty_price)+round($tax_amount); //$tot_price*$prod_data->quantity;
                        
                        //to calculate
                        $tax_amount_cal = ($tax/100)*$qty_price_cal;
                        $tot_price_cal = $price_cal+$tax_amount_cal;
                        $tot_qty_price_cal = ($qty_price_cal+$tax_amount_cal);
                    }
                    //////SUBCATEGORY PRICE
                   
                    $prd_list['unit_actual_price']=round($tot_price);//number_format($actual_price,2);
                    $prd_list['total_actual_price']=round($tot_qty_price);
                    $prd_list['total_discount_price']=0;
                    //$tax_amt=0;//$prod_data->getTaxValue($prod_data->tax_id);
                    //$total_tax_amount = $tot_actual * ($tax_amt/100);
                    $prd_list['total_tax_value']=round($tax_amount);  
                    
                    //TO CALCULATE
                    $prd_list['unit_actual_price_cal']=round($tot_price_cal,2);
                    $prd_list['total_actual_price_cal']=round($tot_qty_price_cal);
                    $prd_list['total_tax_value_cal']=round($tax_amount_cal);
                    
                    $prd_list['image']=$this->get_product_image($prod_data->id);
                    $data             =   $prd_list;
            }//Active store
             }
            // else{ $data     =   []; } 
             return $data;
        
    }
    
    //cart product according to seller
    function get_cart_seller_products($seller_id,$user_id,$lang,$crv){
        $data     =   [];
        
        
        $prod_data1       =   Product::join('usr_cart_item','prd_products.id','=','usr_cart_item.product_id')
                            ->join('usr_cart','usr_cart_item.cart_id','=','usr_cart.id')
                            ->where('usr_cart.user_id',$user_id)    
                            ->where('usr_cart.is_active',1)
                            ->where('usr_cart.is_deleted',0)
                            ->where('usr_cart_item.is_active',1)
                            ->where('usr_cart_item.is_deleted',0)
                            ->where('prd_products.is_active',1)->where('prd_products.is_deleted',0)->where('prd_products.is_approved',1)
                            ->where('prd_products.seller_id',$seller_id)
                            ->get();
                            
            if($prod_data1)   { 
                
                foreach($prod_data1 as $prod_data){
                   
                    $store_active = Store::where('service_status',1)->where('is_active',1)->where('seller_id',$prod_data->seller_id)->first();
                    if($store_active)
                    {
                    $qty=$prod_data->quantity;
                    $prd_list['cart_id']=$prod_data->cart_id;   
                    $prd_list['product_id']=$prod_data->product_id;
                    $prd_list['product_type'] ='simple'; 
                    $prd_list['product_name']=$this->get_content($prod_data->name_cnt_id,$lang);
                    $prd_list['image']=$this->get_product_image($prod_data->product_id);

                    if($prod_data->weight>0)
                    {
                        $weight=$prod_data->weight;
                    }
                    else
                    {
                        $weight=1;
                    }
                    //////SUBCATEGORY PRICE
                        if($prod_data->subCategory->code=='XAU')
                    {
                        $gold=true;
                        $subcategory_code='XAU';
                        $variable_price= $this->variable_price_attr($prod_data->prd_assign_id);
                        //return $variable_price;die;
                        $price = (($variable_price*(float)$weight)+(float)$prod_data->fixed_price)*$crv;
                        $price_cal = ($variable_price*(float)$weight)+(float)$prod_data->fixed_price;


                        $tax = getTax()->value;
                        $qty_price = $price*$prod_data->quantity;
                        $qty_price_cal = $price_cal*$prod_data->quantity;
                        
                        $mjs_fee=getCustomerFee()->mjs_fee;
                        $pg_fee=getCustomerFee()->pg_fee;
                        $prd_list['mjs_fee']= ($mjs_fee/100)*$qty_price;
                        $prd_list['pg_fee']=($pg_fee/100)*$qty_price;
                        $qty_price += $prd_list['mjs_fee'] + $prd_list['pg_fee'];
                        
                        //To calculate
                        $prd_list['mjs_fee_cal']= ($mjs_fee/100)*$qty_price_cal;
                        $prd_list['pg_fee_cal']=($pg_fee/100)*$qty_price_cal;
                        $qty_price_cal += $prd_list['mjs_fee_cal'] + $prd_list['pg_fee_cal'];
                     
                        
                        $tax_amount = ($tax/100)*$qty_price;
                        $tot_price = $price+$tax_amount;
                        
                        //to calculate
                        
                        $tax_amount_cal = ($tax/100)*$qty_price_cal;
                        $tot_price_cal = $price_cal+$tax_amount_cal;
                        
                    }
                    else
                    {
                        $gold= false;
                        $variable_price =0;
                        $price = $prod_data->fixed_price*$crv;
                        $qty_price = $price*$prod_data->quantity;
                        
                        //to calculate
                        $price_cal = $prod_data->fixed_price;
                        $qty_price_cal = $price_cal*$prod_data->quantity;
                        
                         $mjs_fee=getCustomerFee()->mjs_fee;
                        $pg_fee=getCustomerFee()->pg_fee;
                        $prd_list['mjs_fee']= ($mjs_fee/100)*$qty_price;
                        $prd_list['pg_fee']=($pg_fee/100)*$qty_price;
                        $qty_price += $prd_list['mjs_fee'] + $prd_list['pg_fee'];
                        
                        //to calculate
                        $prd_list['mjs_fee_cal']= ($mjs_fee/100)*$qty_price_cal;
                        $prd_list['pg_fee_cal']=($pg_fee/100)*$qty_price_cal;
                        $qty_price_cal += $prd_list['mjs_fee_cal'] + $prd_list['pg_fee_cal'];
                        
                        $tax = getTax()->value;
                        $tax_amount = ($tax/100)*$qty_price;
                        $tot_price = $price+$tax_amount;
                        
                        //to calculate
                        $tax_amount_cal = ($tax/100)*$qty_price_cal;
                        $tot_price_cal = $price_cal+$tax_amount_cal;
                    }
                    //////SUBCATEGORY PRICE

                     if($prod_data->prd_assign_id)
                     {
                        

                     $prd_list['attr_list']=true;  
                        $i=1;
                     foreach(explode(',',$prod_data->prd_assign_id) as $keys)
                     {   
                        $ass_id = (int)$keys;
                     $extra_field=AssignedFields::where('is_deleted',0)->where('id',$ass_id)->first();
                     if($extra_field)
                     {
                     $prd_list['prd_assign_id'.$i] =$extra_field->id;
                     $prd_list['prd_assign_name'.$i] =$extra_field->field_value;
                     $i++;
                     }
                     }
                     }
                     else
                     {
                        $prd_list['attr_list']=false;
                     }

                    $prd_list['weight']=$prod_data->weight;
                    $prd_list['quantity']=$prod_data->quantity;
                    $prd_list['category_id']=$prod_data->category_id;
                    $prd_list['category_name']=$this->get_content($prod_data->category->cat_name_cid,$lang);
                    $prd_list['subcategory_id']=$prod_data->sub_category_id;
                    $prd_list['subcategory_name']=$prod_data->subCategory->subcategory_name;
                    $prd_list['seller_id']=$prod_data->seller_id;
                   // $prd_list['brand_id']=$prod_data->brand_id;
                    $prd_list['currency']=getCurrency()->name;
                    $prd_list['tax']=getTax()->value;
                    $prd_list['mjs_fee_percentage']=getCustomerFee()->mjs_fee;
                    $prd_list['pg_fee_percentage']=getCustomerFee()->pg_fee;
                 
                    if($prod_data->brand_id)
                    {
                        $prd_list['brand_id']=$prod_data->brand_id;
                        $prd_list['brand_name']=$this->get_content($prod_data->brand->brand_name_cid,$lang);
                    }


                    $prd_list['unit_actual_price']= round($qty_price/$prod_data->quantity);
                    $prd_list['total_actual_price']=round($qty_price);
                    $prd_list['total_discount_price']=0;
                    $prd_list['total_tax_value']=round($tax_amount);
                    
                    //TO CALCULATE
                    $prd_list['unit_actual_price_cal']=round($qty_price_cal/$prod_data->quantity);
                    $prd_list['total_actual_price_cal']=round($qty_price_cal);
                    $prd_list['total_tax_value_cal']=round($tax_amount_cal);
                     
                    
                    //Available offers for this product
            
           
                    $data[]             =  $prd_list;
                    }//Active store
             }
             
        } 
             return $data;
        
    }





  /*************GET VALUES **************/

  //VARIABLE PRICE ATTR
  public function variable_price_attr($prd_ass_id)
    {
        $variable=null;
        foreach(explode(',',$prd_ass_id)as $ass_id)
        {

        $extra_field=AssignedFields::where('is_deleted',0)->where('id',$ass_id)->first();
        //return $extra_field;die;
        if($extra_field){
        if(stripos($extra_field->PrdField_in($extra_field->field_id)->name, 'carat') !== false || stripos($extra_field->PrdField_in($extra_field->field_id)->name, 'Karat') !== false) 
        {
           $variable += get_variable_price_fn('XAU',$extra_field->PrdField_value_name->name);
        }
        else
        {
            $variable += get_variable_price_fn('XAU',$carat=null); 
        }
        } 
        else
        {
            $variable=0;   
        }
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
                 $carat = preg_replace("/[^0-9]/", "", $carat);
            }
            
            $json = json_decode($metals->carat_rates,TRUE);
            $json_rates=$json['rates'];
           // return $json_rates['Carat 24K'];
           
            foreach($json_rates as $key=>$row)
            {
                $res = preg_replace("/[^0-9]/", "", $key );
                if($carat==$res){
               // $list= $key.'->'.$row;
               $fig = (float)$res*(float)$row*0.2;
               $list = round($fig,2);
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
                            $list= round($fig,2);
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
        $return_val = $rows->sale_price;
        return $return_val;
        }
        else
            { $return_val='';
                return $return_val; }
        }
        
        //Product ACTUAL price
    public function get_actual_price($field_id){ 

     
       //$current_date=Carbon::now();
       $rows = PrdPrice::where('is_deleted',0)->where('prd_id',$field_id)->first();        
        if($rows){ 
        $return_val = $rows->price;
        return $return_val;
        }
        else
            { $return_val='';
                return $return_val; }
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
}
