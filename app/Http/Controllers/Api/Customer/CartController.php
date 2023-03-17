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
use App\Models\PrdImage;
use App\Models\Reward;
use App\Models\RewardType;
use App\Models\RelatedProduct;
use App\Models\AssignedAttribute;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use App\Models\LoyaltyPoints;
use App\Models\CustomerPoints;
use App\Models\CustomerMaster;
use App\Models\Delivery;
use App\Models\SettingOther;
use Carbon\Carbon;
use App\Rules\Name;
use Validator;
use App\Models\crm\{CrmAssortmentMaster, CrmChildProductsMaster, CrmCustomerType,CrmPartAssortmentDetails,
CrmPartAssortmentMaster,CrmProduct,CrmSalesPriceList,CrmSalesPriceType,CrmSize,CrmBranch,CrmCompany};

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
				$login=1;
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
                

      $cart = Cart::join('usr_cart_item','usr_cart.id','=','usr_cart_item.cart_id')->join('prd_products','usr_cart_item.product_id','=','prd_products.id')
                        ->where('usr_cart.user_id',$user_id)    
                        ->where('usr_cart.is_active',1)
                        ->where('usr_cart.is_deleted',0)
                        ->where('usr_cart_item.is_active',1)
                        ->where('usr_cart_item.is_deleted',0)
                        ->where('prd_products.is_deleted',0)
                        ->groupBy('prd_products.seller_id')
                        ->get();
        // 	dd($cart);
        if(count($cart)>0)
        {
            
            $wallet = $this->wallet_balance($user_id);

            $before_checkout_products=[];
    

        $payment_gateway_chrg=0;
            $shipping_chrg =0;
            //Seller cart products
     
            // dd($cart);
            //return $seller_products;die;
            $seller_product_list=[];
            $products=[];
            $rewards=[];
            $seller_products = $cart;
            foreach($seller_products as $s_row)
            {
                // dd($s_row);
                $products_seller_shipping = 0;
                $store_active = CrmBranch::where('DelStatus',0)->where('Branch_Id',$s_row->seller_id)->first(); //dd($s_row);
                    if($store_active)
                    {
                $products_seller['seller']                = array('seller_id'=>$s_row->seller_id,'seller'=>$store_active->Branch_Name);

                $products_seller['seller']['products']    = $this->get_cart_seller_products($s_row->seller_id,$user_id,$lang,$crv);

                $seller_product_list[]                    = $products_seller;
                
                    }
            }

            $products=[];
            $rewards=[];
             
            //   dd($seller_product_list);
           // if($user_id ==126){ dd($seller_product_list); }
            $tot_tax =0;
            $total_cost=0;
			$total_actual_cost=0;
			$total_discount_cost=0;
            $grand_tot=0;
            $before_checkout_product=[];
            $related=[];
            $c_count = 0;
            if(isset($seller_product_list))
            {
                foreach($seller_product_list as $sk=>$sv)
                {
                  if(isset($sv['seller']['products'])){ $seller_prd = $sv['seller']['products']; }else{ $seller_prd = []; } 
                  if(count($seller_prd)>0)
                  {
                    foreach($seller_prd as $spk=>$spv)
                    {   
                        $c_count++;
                        $tot_tax += $spv['total_tax_value'];
                        $total_actual_cost +=(float)$spv['total_actual_price'];  
                        $related_products=[];                   
                        $total_discount_cost +=(float)$spv['unit_discount_price']*$spv['quantity']; 
                        $prd_rel=RelatedProduct::where('prd_id',$spv['product_id'])->where('is_deleted',0)->get();

                        if(count($prd_rel)>0)
                        {
                        foreach($prd_rel as $key)
                        {
                        $related[] = $this->related_product($key->rel_prd_id,$lang,$user);
                        if (count($before_checkout_products) === 10) {
                        break;
                        }
                        }

                        }

                        $before_checkout_product= $related;

                    }
                  }
                }
                
                $total_cost=$total_actual_cost-$total_discount_cost;
                $grand_tot = $tot_tax+$total_cost;
            }

          // $cart_count = count($cart);
            $cart_count = $c_count;

            $cust_points_in             =   (int)  CustomerPoints ::where('user_id',$user_id)->where('is_deleted',0)->sum('credit'); 
            $cust_points_out            =   (int)  CustomerPoints ::where('user_id',$user_id)->where('is_deleted',0)->sum('debit'); 
            $points = ($cust_points_in-$cust_points_out);
            $points_value = SettingOther::where('is_deleted',0)->where('is_active',1)->first()->point_equivalent;

            $delivery_methods = Delivery::where('is_active',1)->where('is_deleted',0)->orderBy('id','desc')->get();
            $delivery_data = [];
            if($delivery_methods)
            {
            foreach($delivery_methods as $fk=>$fv)
            {
            $delivery_md['method'] = $fv->delivery_type_name;
            // $delivery_md['desc'] = $fv->delivery_description;
            $delivery_md['charge'] = $fv->delivery_charges;
            $delivery_data[] = $delivery_md;
            }

            }

            $sale_before  =  SaleOrder::where('cust_id',$user_id)->count();
            if($sale_before<1)
            {
                $reward = Reward::where('is_active',1)->where('is_deleted',0)->where('ord_min_amount', '<=', $total_cost)->where('ord_type','discount')->first();
                if($reward){
                $rewards= ['reward_id'=>$reward->id,'reward_amt'=>$reward->ord_amount];
                }
            }
            $before_checkout_product=array_values(array_filter(array_unique($before_checkout_product, SORT_REGULAR)));
            return ['httpcode'=>200,'status'=>'success','message'=>'Success','data'=>['cart_count'=>$cart_count,'product'=>$seller_product_list,'before_checkout_products'=>$before_checkout_product,'delivery_data'=>$delivery_data,'currency'=>getCurrency()->name,'wallet_balance'=>$wallet,'points'=>round($points),'points_value'=>$points_value,'reward'=>$rewards,'total_cost'=>number_format($total_actual_cost,2),'discount'=>number_format($total_discount_cost,2),'total_tax'=>number_format($tot_tax,2),'grand_total'=>number_format($grand_tot,2)]];       
         }
         else
         {
            return ['httpcode'=>404,'status'=>'error','message'=>'Cart is empty','data'=>['errors'=>'Cart is empty']];
         }  
    }

    }
	
    function related_product($prd_id,$lang,$user){
        $data     =   [];
        
        $prod_data       =   Product::where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->where('visible',1)->where('id',$prd_id)->first();
            if($prod_data)   {    
                     
                    //$prd_list['product_id']=$prd_id;
                    $prd_list['product_id']=$prod_data->id;;
                    $prd_list['product_name']=$prod_data->name;
                    $prd_list['category_id']=$prod_data->category_id;
                     if($prd_list['category_id']){
						$category=Category::where('category_id',$prod_data->category_id)->first();
						$prd_list['is_rating']=@$category->is_rating;
					}else{
						
						$prd_list['is_rating']=0;
					}
                    $prd_list['category_name']=$this->get_content(@$prod_data->category->cat_name_cid,$lang);
                    $prd_list['subcategory_id']=$prod_data->sub_category_id;
                    $prd_list['subcategory_name']=$this->get_content(@$prod_data->subCategory->sub_name_cid,$lang);
                    if($prod_data->brand_id)
                    {
                    $prd_list['brand_id']=$prod_data->brand_id;
                    $prd_list['brand_name']=$this->get_content(@$prod_data->brand->brand_name_cid,$lang);
                    }
                    else
                    {
                    $prd_list['brand_id']='';
                    $prd_list['brand_name']='';
                    }
                    $prd_list['short_description']=$this->get_content($prod_data->short_desc_cnt_id,$lang);
                    $prd_list['long_description']=$this->get_content($prod_data->desc_cnt_id,$lang);
                    $prd_list['content']=$this->get_content($prod_data->content_cnt_id,$lang);
                    if($prod_data->product_type==1)
                    {
                    $prd_list['product_type']='simple';    
					$prd_list['min_order_qty']=$prod_data->min_order;
					$prd_list['bulk_order_qty']=$prod_data->bulk_order;	
                        
                        $price=get_crm_price($prod_data,$type=1,$user);
                        foreach ($price as $key => $value) {
                        $prd_list[$key] = $value;
                        }

					    $prd_list['stock']=$prod_data->prdStock($prod_data->id);
                        if($prd_list['stock'] <= 0)
                        {
                            $prd_list['is_out_of_stock']=true;
                        }
                        else
                        {
                            $prd_list['is_out_of_stock']=false;
                        }
                        if($prod_data->out_of_stock_selling==0)
                        {
                            $prd_list['out_of_stock_selling']=false;
                        }
                        else
                        {
                            $prd_list['out_of_stock_selling']=true;
                        }	
					}
                    else
                    {
                     $prd_list['product_type']='config'; 
                     $prd_list['min_order_qty']="Null";
					 $prd_list['bulk_order_qty']="Null";
					 $price=get_crm_price($prod_data,$type=1,$user);
                        foreach ($price as $key => $value) {
                        $prd_list[$key] = $value;
                        }
						
						$prd_list['stock']=NULL;
                        $prd_list['is_out_of_stock']=NULL;
                        $prd_list['out_of_stock_selling']=NULL;
                        	
						
					} 
                     //$prd_list['is_out_of_stock']=$prod_data->is_out_of_stock;
                   // $prd_list['tag']=$this->get_product_tag($prod_data->id,$lang); 
                    $prd_list['rating']=$this->get_rates($prod_data->id);
                    $prd_list['total_reviews']=$this->get_rates_count($prod_data->id);
                    $prd_list['image']=$this->get_product_image($prod_data->id); 
                    
					
					$data             =   $prd_list;
                    
             }
            else{ $data     =   []; } return $data;
        
    }
    function get_rates($field_id){ 

        $rate =DB::table('prd_review')->select(DB::raw('AVG(rating) as rating'))->where('prd_id',$field_id)->where('is_active',1)->where('is_deleted',0)->first();
        if($rate){ 
        $return_val = round($rate->rating);
        return $return_val;
        }
        else
            { $return_val=0;
                return $return_val; }
        }
        function get_rates_count($field_id){ 

        $rate =DB::table('prd_review')->where('prd_id',$field_id)->where('is_active',1)->where('is_deleted',0)->count();
        if($rate>0){ 
        $return_val = $rate;
        return $return_val;
        }
        else
            { $return_val=0;
                return $return_val; }
        }
        
        function ass_related_product1($prd_id,$special_ofr_available,$lang,$login){
        $data     =   [];
       // dd($login);
        $prod_data       =   AssignedAttribute::where('is_deleted',0)->where('prd_id',$prd_id)->orderBy('attr_id','DESC')->groupBy('attr_id')->get();
       //dd( $prod_data  ) ;
		   if(count($prod_data)>0)   { 
                foreach($prod_data as $row)  {
					//dd($row);
                    //$attr_list['id']=$row->id;
                   $attr_list['attr_id']=$row->attr_id;
                   $attr_list['product_id']=$row->prd_id;
				   if($row->Product->min_order){
                   $attr_list['min_order']=$row->Product->min_order;
				   }else{
					$attr_list['min_order']=0;
				   }
				   if($row->Product->bulk_order){
                   $attr_list['bulk_order']=$row->Product->bulk_order;
				   }else{
					$attr_list['bulk_order']=0;
				   }
                   
                    //$attr_list['attr_id']=$row->attr_id;
                    $attr_list['attr_name']=$row->PrdAttr->name;  //$this->get_content($row->PrdAttr->name_cnt_id,$lang);
                   // $attr_list['attr_type']=$row->PrdAttr->type;
                   // $attr_list['attr_data_type']=$row->PrdAttr->data_type;
                   // $attr_list['attr_value_name']=$this->get_content($row->PrdAttr_value->name_cnt_id,$lang);
                    $attr_list['attr_value']=$row->attr_value;
					
					if(isset($row->attrValue->image)){
                    $attr_list['attribute_image']=config('app.storage_url').$row->attrValue->image;
                    }
                    $attr_list['image']=$this->get_product_image($row->prd_id); 
                    
                    //$attr_list['image']=config('app.storage_url').$row->attrValue->image;
                    $arrt_vals['list'][] =$this->inner_attribute($prd_id,$row->attr_id,$row->id,$special_ofr_available,$lang,$login);
                    

                    if(empty($arrt_vals['list'][0]))
                    {
                        
                    $attr_list['sub_attributes'] = [];
						
                    if($login==1){
					$actual_price = $this->get_actual_price($row->prd_id);
                    $attr_list['actual_price_quote']= $actual_price;
                    $attr_list['actual_price']= $actual_price;
                    $sale_price =$this->get_sale_price($row->prd_id);
                    $attr_list['sale_price']=$sale_price;
                    
					if($special_ofr_available>0)
                        {
                          $special_ofr_price = $actual_price - $special_ofr_available; 
                          $attr_list['special_ofr_price']=$special_ofr_price;
                        }
                        else
                        {
                          $attr_list['special_ofr_price']=false;  
                        }
					}else{
						$attr_list['actual_price_quote']= "NULL";
						$attr_list['actual_price']= "NULL";
						$attr_list['sale_price']= "NULL";
						$attr_list['special_ofr_price']= "NULL";
					}
                    $attr_list['stock']=$row->prdStock($row->prd_id);
                    $attr_list['sku']=$row->Product->sku;
                    
                    
                    if($row->Product->is_out_of_stock==0)
                        {
                            $attr_list['is_out_of_stock']=false;
                        }
                        else
                        {
                            $attr_list['is_out_of_stock']=true;
                        }
                        if($row->Product->out_of_stock_selling==0)
                        {
                            $attr_list['out_of_stock_selling']=false;
                        }
                        else
                        {
                            $attr_list['out_of_stock_selling']=true;
                        }
                    }
                    else
                    {
                        $attr_list['sub_attributes'] =$this->inner_attribute($prd_id,$row->attr_id,$row->id,$special_ofr_available,$lang,$login);
                    }
                    $data             =   $attr_list;
                }
             }
            else{ $data     =   []; } return $data;
        
    }
    
    function getProductimages($prd_id){
	$rowss = PrdImage::where('is_deleted',0)->where('prd_id',$prd_id)->get();
	$data=[];
	if($rowss){
		foreach($rowss as $row){
		$atr_inn['image'] =	config('app.storage_url').$row->image;
		$atr_inn['thumb'] =	config('app.storage_url').$row->thumb;
		
		  $data[]             =   $atr_inn;
		}
		return $data;
	}
	}
    function inner_attribute($prd_id,$attr_id,$rowId,$special_ofr_available,$lang,$login)
    {
       // dd($login);
		$data=[];
        $rowss = AssignedAttribute::where('is_deleted',0)->where('prd_id',$prd_id)->where('attr_id','!=',$attr_id)->where('id','!=',$rowId)->first();
        //$rows1 = AssignedAttribute::where('is_deleted',0)->where('attr_id',$attr_id)->whereNotIn('id',[$rowId])->get();
        //dd($rowId);
                     if($rowss && $rowss->id!=$rowId)
                    {
						
                        //$atr_inn['id']=$rowss->id;
                        $atr_inn['attr_id']=$rowss->attr_id;
                        $atr_inn['product_id']=$rowss->prd_id;
                        $atr_inn['attr_name']=$rowss->PrdAttr->name; //$this->get_content($rowss->PrdAttr->name_cnt_id,$lang);
                        $atr_inn['attr_value']= $rowss->attr_value;
						
						$atr_inn['image']=$this->getProductimages($prd_id);
						
					   if($login==1){
						//dd($rowId);	
						$actual_price = $this->get_actual_price($rowss->prd_id);
                        $atr_inn['actual_price_quote']= $actual_price;
                        $atr_inn['actual_price']= $rowss->Product->prdPrice->price;
                        if($special_ofr_available>0)
                        {
                          $special_ofr_price = $actual_price - $special_ofr_available; 
                          $atr_inn['special_ofr_price']=$special_ofr_price;
                        }
                        else
                        {
                          $atr_inn['special_ofr_price']=false;  
                        }
                        $sale_price =$this->get_sale_price($rowss->prd_id);
                        $atr_inn['sale_price']=$sale_price;
                        }else{
							 $atr_inn['actual_price_quote']="NULL";
							 $atr_inn['actual_price']="NULL";
							 $atr_inn['sale_price']="NULL";
							 $atr_inn['special_ofr_price']="NULL";
						}
						$atr_inn['stock']=$rowss->prdStock($rowss->prd_id);
                        $atr_inn['sku']=$rowss->Product->sku;
                        if($rowss->Product->is_out_of_stock==0)
                        {
                            $atr_inn['is_out_of_stock']=false;
                        }
                        else
                        {
                            $atr_inn['is_out_of_stock']=true;
                        }
                        if($rowss->Product->out_of_stock_selling==0)
                        {
                            $atr_inn['out_of_stock_selling']=false;
                        }
                        else
                        {
                            $atr_inn['out_of_stock_selling']=true;
                        }
                        //$atr_inn['subattr']=$this->inner_attribute_12($rowss->prd_id,$rowss->attr_id,$rowss->id,$lang);
                        $data[]             =   $atr_inn;
                        
                    
                    }return $data;
    }

	function min_price_product($prd_id)
        {
			//dd($prd_id);
            $val = 0;
            $prd_ass = AssociatProduct::where('prd_id',$prd_id)->where('is_deleted',0)->get(['ass_prd_id']);
            if($prd_ass){
            $join = Product::join('prd_prices', 'prd_products.id', '=', 'prd_prices.prd_id')
                    //->selectRaw("MIN(prd_prices.price) AS min_val,prd_prices.sale_price")
                    ->whereIn('prd_products.id',$prd_ass)->where('prd_prices.prd_id','!=',$prd_id)
                    ->orderBy('prd_prices.price','ASC')
                    ->first();
                    if($join)
                    {
                        $prd_id = $join->prd_id;
                        //$max = $join->max_val;
                        
                         $val = $prd_id;
                      
                    }
            }
            
            return $val;
                    
        }
		public function get_price($prdid,$type,$login){ 
		//$offer['offer_price']=false;
        $current_date=Carbon::now();
        $prod_data= Product::where('id',$prdid)->first();
             if($type==2){
				 $parent_product = AssociatProduct::where('ass_prd_id',$prdid)->where('is_deleted',0)->first();
				 if($parent_product){
				 $parent_prdid=$parent_product->prd_id;
				 }
				 else{
				 $parent_prdid=$parent_product=0;
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
				 $parent_prdid=$parent_product->prd_id;
				 $specialOffer = PrdOffer::where('is_deleted',0)->where('prd_id',$parent_prdid)->whereDate('valid_from','<=',$current_date)->whereDate('valid_to','>=',$current_date)->first();        
			}else{
			$specialOffer = PrdOffer::where('is_deleted',0)->where('prd_id',$prdid)->whereDate('valid_from','<=',$current_date)->whereDate('valid_to','>=',$current_date)->first();        
			}
			$SalesPrice = PrdPrice::where('is_deleted',0)->where('prd_id',$prdid)->whereDate('sale_end_date','>=',$current_date)->orderBy('id','DESC')->first();        
			$Price = PrdPrice::where('is_deleted',0)->where('prd_id',$prdid)->orderBy('id','DESC')->first();        

		if($shock){
            $offer['offer_name']= 'Shocking Sale';   
            $offer['discount_type']= $shock->discount_type;   
            //$offer['offer_id']=$shock->id;
			if($login==1){
				$offer['actual_price']=number_format($Price->price,2);
			}else{
				$offer['actual_price']=NULL;
			}
            //$offer['url']=url('api/customer/shock-sale');
           
                $actual_price=$prod_data->prdPrice->price;
				if($shock->discount_type=="amount"){
					$offer['offer']=getCurrency()->name." ".$shock->discount_value." Off";
					$discount_value = $shock->discount_value;
					$unit_price = $actual_price-$discount_value;
					if($login==1){
						$offer['offer_price']=number_format($unit_price,2);
					}else{
						$offer['offer_price']=NULL;
					}
					
				}
				else{
					$offer['offer']=$shock->discount_value."% Off";
					$per=$shock->discount_value/100;
					$per_value = (float)$actual_price*(float)$per;
			
					$discount=(float)$actual_price-(float)$per_value;
					$round= number_format($discount, 2);
					if($login==1){
						$offer['offer_price']=$round;
					}else{
						$offer['offer_price']=NULL;
					}
					
				}
					
        }else if($specialOffer){
			
			
			if($specialOffer->quantity_limit >1){
			$offer['offer_name']= false ;
			$offer['discount_type']= false; 
			$offer['offer']= false; 
            if($login==1){
				if($Price){
				$offer['actual_price']=number_format($Price->price,2);
                }else{
                	$offer['actual_price']=NULL;   
                }
			}else{
				$offer['actual_price']=NULL;
			}
            $offer['offer_price']=false;
				
			}else{
			$offer['offer_name']= 'Special Offer'; 
			$offer['discount_type']= $specialOffer->discount_type;   
			$discount_val = $specialOffer->discount_value;
			$discount_typ = $specialOffer->discount_type;
			if($login==1){
				if($Price){
				$offer['actual_price']=number_format($Price->price,2);
                }else{
                	$offer['actual_price']=NULL;   
                }
			}else{
				$offer['actual_price']=NULL;
			}
			$price=$Price->price;
			if($discount_typ=="percentage"){
			$offer['offer']=$specialOffer->discount_value."% Off";
            $dis = $price * ($discount_val/100);
            $offer['offer_price'] = $price-$dis;
			} else {
			$offer['offer']=getCurrency()->name." ".$specialOffer->discount_value." Off";
            $offer['offer_price'] = $price - $discount_val;
			}
			if($offer['offer_price']>0){
				if($login==1){
						$offer['offer_price']=number_format($offer['offer_price'],2);
					}else{
						$offer['offer_price']=NULL;
					}
			}
			else {
				if($login==1){
							$offer['offer_price']=number_format($Price->price,2);
						}else{
							$offer['offer_price']=NULL;
						}
				}
			}
        
        }else if($SalesPrice){
		$offer['offer_name']= 'Sale Offer'; 
		$offer['discount_type']= "amount"; 
		$discount_amount=$Price->price - $SalesPrice->sale_price ;
		$offer['offer']=getCurrency()->name." ".$discount_amount." Off";
		if($login==1){
				if($Price){
				$offer['actual_price']=number_format($Price->price,2);
                }else{
                	$offer['actual_price']=NULL;   
                }
			}else{
				$offer['actual_price']=NULL;
			}
		if($login==1){
						$offer['offer_price']=number_format($SalesPrice->sale_price,2);
					}else{
						$offer['offer_price']=NULL;
					}
        //$offer['offer_price']= $SalesPrice->sale_price;
		
       
        }else{
			$offer['offer_name']= false ;
			$offer['discount_type']= false; 
			$offer['offer']= false; 
            if($login==1){
				if($Price){
				$offer['actual_price']=number_format($Price->price,2);
                }else{
                	$offer['actual_price']=NULL;   
                }
			}else{
				$offer['actual_price']=NULL;
			}
            $offer['offer_price']=false;
		}
	    $offer_list[]=$offer;
					
                return $offer_list;
	
		
	
	   
	}
	public function get_special_ofr_value($price,$field_id){ 

       $return_val=0;
       $current_date=Carbon::now();
       $rows = PrdOffer::where('is_deleted',0)->where('prd_id',$field_id)->whereDate('valid_from','<=',$current_date)->whereDate('valid_to','>=',$current_date)->where('quantity_limit','>',0)->first();        
        if($rows){ 
        $discount_val = $rows->discount_value;
        $discount_typ = $rows->discount_type;
        if($discount_typ=="percentage")
        {
            $dis = $price * ($discount_val/100);
            $return_val = $dis;
        }
        else
        {
            $return_val = $discount_val;
        }
        if($return_val>0)
        {
            return $return_val;
        }
        else
        {
             return $return_val;
        }
        
        }
        else
            { //$return_val=false;
                return $return_val; }
        }
        
        //Product ACTUAL price
    
	function config_product_price($prd_id)
        {
            $val = 0;
            $prd_ass = AssociatProduct::where('prd_id',$prd_id)->where('is_deleted',0)->get(['ass_prd_id']);
            if($prd_ass){
            $join = Product::join('prd_prices', 'prd_products.id', '=', 'prd_prices.prd_id')
                    ->selectRaw("MAX(prd_prices.price) AS max_val, MIN(prd_prices.price) AS min_val")
                    ->whereIn('prd_products.id',$prd_ass)->first();
                    if($join)
                    {
                        $min = $join->min_val;
                        $max = $join->max_val;
                        
                         $val = $min;
                        // if($min > 0 && $max > 0 && $min!=$max){
                        // $val = $min."-".$max;
                        // }
                        // else if($min > 0 && $max ==0)
                        // {
                        //     $val = $min;
                        // }
                        // else if($min==$max)
                        // {
                        //   $val = $min; 
                        // }
                        // else
                        // {
                        //     $val = $max;
                        // }
                    }
            }
            
            return $val;
                    
        }
		function config_product_sale_price($prd_id)
        {
            $val = 0;
            $prd_ass = AssociatProduct::where('prd_id',$prd_id)->where('is_deleted',0)->get(['ass_prd_id']);
            if($prd_ass){
            $join = Product::join('prd_prices', 'prd_products.id', '=', 'prd_prices.prd_id')
                    //->selectRaw("MIN(prd_prices.price) AS min_val,prd_prices.sale_price")
                    ->whereIn('prd_products.id',$prd_ass)
                    ->orderBy('prd_prices.price','ASC')
                    ->first();
                    if($join)
                    {
                        $sale_price = $join->sale_price;
                        //$max = $join->max_val;
                        
                         $val = $sale_price;
                        // if($min > 0 && $max > 0 && $min!=$max){
                        // $val = $min."-".$max;
                        // }
                        // else if($min > 0 && $max ==0)
                        // {
                        //     $val = $min;
                        // }
                        // else if($min==$max)
                        // {
                        //   $val = $min; 
                        // }
                        // else
                        // {
                        //     $val = $max;
                        // }
                    }
            }
            
            return $val;
                    
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
            $tot_tax =0;
            $total_cost=0;
            $grand_tot=0;
            if(count($filter)>0)
            {
                foreach($filter as $value)
                {
                    $tot_tax += $value['total_tax_value'];
                    if($value['total_discount_price']==0)
                    {
                     $total_cost +=(int)$value['total_actual_price'];    
                    }
                    else
                    {
                      $total_cost +=(int)$value['total_discount_price'];    
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
            'cart_id' => ['required']
        ]);
        $input = $request->all();
        $user_id = $user['user_id'];

    if ($validator->fails()) 
    {    
      return ['httpcode'=>400,'status'=>'error','message'=>'Invalid parameters','data'=>['errors'=>$validator->messages()]];
    }
    else
    {
		 $cart_id= explode(",", $request->cart_id);
        foreach($cart_id as $cart)
        {
       
            $cart_product = CartItem::where('cart_id',$cart)->first();

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

    return ['httpcode'=>200,'status'=>'success','message'=>'Successfully Deleted','data'=>['response'=>'Successfully Deleted']];
    }

    }
    
     public function delete_cart_by_product_id(Request $request)
    {
        if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
        $validator=  Validator::make($request->all(),[
            'product_id' => ['required']
        ]);
        $input = $request->all();
        $user_id = $user['user_id'];

    if ($validator->fails()) 
    {    
      return ['httpcode'=>400,'status'=>'error','message'=>'Invalid parameters','data'=>['errors'=>$validator->messages()]];
    }
    else
    {
		$product_ids= explode(",", $request->product_id);
        foreach($product_ids as $product_id)
        {
            
             $cart_product = Cart::join('usr_cart_item','usr_cart.id','=','usr_cart_item.cart_id')
        ->where('usr_cart_item.is_active',1)->where('usr_cart_item.is_deleted',0)
        ->where('usr_cart.is_active',1)->where('usr_cart.is_deleted',0)
        ->where('usr_cart_item.product_id',$product_id)
        ->where('usr_cart.user_id',$user_id)
        ->first();
        
       if($cart_product){
           // $cart_product = CartItem::where('product_id',$product_id)->first();

            Cart::where('id',$cart_product->cart_id)->update([
            'is_active'=>0,
            'is_deleted'=>1,
            'updated_by'=>$user_id,
            'updated_at'=>date("Y-m-d H:i:s")]);

            CartItem::where('cart_id',$cart_product->cart_id)->update([
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
        {
       return ['httpcode'=>404,'status'=>'error','message'=>'Not found','data'=>['errors'=>'No product found in cart']];
        }
        
        }
        

    return ['httpcode'=>200,'status'=>'success','message'=>'Successfully Deleted','data'=>['response'=>'Successfully Deleted']];
    }

    }

    //Apply Coupon
    public function apply_coupon(Request $request)
    {   
        if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
        $user_id = $user['user_id'];

        $lang=$request->lang_id;

        $validator=  Validator::make($request->all(),[
            'coupon_code' =>['required','string','min:6','max:6']
        ]);
        $input = $request->all();

    if ($validator->fails()) 
    {    
      return ['httpcode'=>400,'status'=>'error','message'=>'Invalid parameters','data'=>['errors'=>$validator->messages()]];
    }

     else
     { 
                   

        $query = Coupon::query();
        $avail = Coupon::where('ofr_code',$input['coupon_code'])->first();
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
            $range = $query->whereDate('valid_from','<=',$current_date)->whereDate('valid_to','>=',$current_date)->where('ofr_code',$input['coupon_code'])->first();
           if($no_prds>0){
                foreach($cart as $rows)
           
            {
               // $products[] = $this->get_cart_products($rows->product_id,$rows->cart_id,$rows->quantity,$lang);
                $products[] = $this->get_cart_ofr_products($rows->product_id,$rows->cart_id,$rows->quantity,$lang,$cat_id,$sub_id);
            }   
//dd($product);
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
			//dd($products);
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
                //Coupon details
                    $coupon_details['coupon_id']=$range->id;
                    $coupon_details['title']=$this->get_content($range->cpn_title_cid,$lang);
                    $coupon_details['desc']=$this->get_content($range->cpn_desc_cid,$lang);
                    $coupon_details['offer']=$discount." ".$range->ofr_type;
                  if($range->ofr_type=="cashback")
                  {
                    
                    $tot_grand = number_format($total_cost+$tot_tax,2);
                    return ['httpcode'=>200,'status'=>'success','message'=>'Successfully applied','data'=>['currency'=>getCurrency()->name,'total_cost'=>$total_cost,'cashback'=>$discount,'tax_total'=>$tot_tax,'grand_total'=>$tot_grand,'coupon_details'=>$coupon_details]];
                  }
                  else if($range->ofr_type=="discount")
                  {
                    return ['httpcode'=>200,'status'=>'success','message'=>'Successfully applied','data'=>['currency'=>getCurrency()->name,'total_cost'=>$total_cost,'discount'=>$discount,'tax_total'=>$tot_tax,'grand_total'=>$grand_tot,'coupon_details'=>$coupon_details]];
                  }
                  else
                  {
                    return ['httpcode'=>200,'status'=>'success','message'=>'Successfully applied','data'=>['currency'=>getCurrency()->name,'total_cost'=>$total_cost,'discount'=>0,'tax_total'=>$tot_tax,'grand_total'=>$grand_tot,'coupon_details'=>$coupon_details]];
                  }
              
            }
            else if($range->purchase_type == "amount" && $range->ofr_min_amount <=$total_cost && $sale_amt >= $range->purchase_amount)
            {
				//dd($total_cost);
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
                //Coupon details
                    $coupon_details['coupon_id']=$range->id;
                    $coupon_details['title']=$this->get_content($range->cpn_title_cid,$lang);
                    $coupon_details['desc']=$this->get_content($range->cpn_desc_cid,$lang);
                    $coupon_details['offer']=$discount." ".$range->ofr_type;
                    if($range->ofr_type=="cashback")
                  {
                    $tot_grand = number_format($total_cost+$tot_tax,2);
                    return ['httpcode'=>200,'status'=>'success','message'=>'Successfully applied','data'=>['currency'=>getCurrency()->name,'total_cost'=>$total_cost,'cashback'=>$discount,'tax_total'=>$tot_tax,'grand_total'=>$tot_grand,'coupon_details'=>$coupon_details]];
                  }
                  else if($range->ofr_type=="discount")
                  {
                    return ['httpcode'=>200,'status'=>'success','message'=>'Successfully applied','data'=>['currency'=>getCurrency()->name,'total_cost'=>$total_cost,'discount'=>$discount,'tax_total'=>$tot_tax,'grand_total'=>$grand_tot,'coupon_details'=>$coupon_details]];
                  }
                  else
                  {
                    return ['httpcode'=>200,'status'=>'success','message'=>'Successfully applied','data'=>['currency'=>getCurrency()->name,'total_cost'=>$total_cost,'discount'=>0,'tax_total'=>$tot_tax,'grand_total'=>$grand_tot,'coupon_details'=>$coupon_details]];
                  }
            }
            else{ return ['httpcode'=>404,'status'=>'error','message'=>'Not applicable','data'=>['errors'=>'Coupon is not applicable']];}

          }
          //if no prd in cart
          else
          {
             return ['httpcode'=>404,'status'=>'error','message'=>'Not found','data'=>['errors'=>'Not found']];
          }
        }
        elseif($avail->validity_type == "days")
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
                $products[] = $this->get_cart_ofr_products($rows->product_id,$rows->cart_id,$rows->quantity,$lang,$cat_id,$sub_id,$seller);
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
                  if($range->ofr_type=="cashback")
                  {
                    
                    $tot_grand = number_format($total_cost+$tot_tax,2);
                    return ['httpcode'=>200,'status'=>'success','message'=>'Successfully applied','data'=>['currency'=>getCurrency()->name,'total_cost'=>$total_cost,'cashback'=>$discount,'tax_total'=>$tot_tax,'grand_total'=>$tot_grand,'coupon_details'=>$coupon_details]];
                  }
                  else if($range->ofr_type=="discount")
                  {
                    return ['httpcode'=>200,'status'=>'success','message'=>'Successfully applied','data'=>['currency'=>getCurrency()->name,'total_cost'=>$total_cost,'discount'=>$discount,'tax_total'=>$tot_tax,'grand_total'=>$grand_tot,'coupon_details'=>$coupon_details]];
                  }
                  else
                  {
                    return ['httpcode'=>200,'status'=>'success','message'=>'Successfully applied','data'=>['currency'=>getCurrency()->name,'total_cost'=>$total_cost,'tax_total'=>$tot_tax,'grand_total'=>$grand_tot,'coupon_details'=>$coupon_details]];
                  }
                 
            }
            else if($range->purchase_type == "amount" && $range->ofr_min_amount >=$total_cost && $sale_amt >= $range->purchase_amount)
            {
                    if($range->ofr_type=="cashback")
                  {
                    $tot_grand = number_format($total_cost+$tot_tax,2);
                    return ['httpcode'=>200,'status'=>'success','message'=>'Successfully applied','data'=>['currency'=>getCurrency()->name,'total_cost'=>$total_cost,'cashback'=>$discount,'tax_total'=>$tot_tax,'grand_total'=>$tot_grand,'coupon_details'=>$coupon_details]];
                  }
                  else if($range->ofr_type=="discount")
                  {
                    return ['httpcode'=>200,'status'=>'success','message'=>'Successfully applied','data'=>['currency'=>getCurrency()->name,'total_cost'=>$total_cost,'discount'=>$discount,'tax_total'=>$tot_tax,'grand_total'=>$grand_tot,'coupon_details'=>$coupon_details]];
                  }
                  else
                  {
                    return ['httpcode'=>200,'status'=>'success','message'=>'Successfully applied','data'=>['currency'=>getCurrency()->name,'total_cost'=>$total_cost,'discount'=>0,'tax_total'=>$tot_tax,'grand_total'=>$grand_tot,'coupon_details'=>$coupon_details]];
                  }
            }
            else{ return ['httpcode'=>404,'status'=>'error','message'=>'Not found','data'=>['errors'=>'Not found']];}

          }
          //if no prd in cart
          else
          {
             return ['httpcode'=>404,'status'=>'error','message'=>'Not applicable','data'=>['errors'=>'Coupon is not applicable']];
          }
        }

		}
                         
        else

        {
        return ['httpcode'=>404,'status'=>'error','message'=>'Not found','data'=>['errors'=>'Not found']];
        }
       
                        

     }
    }
    
    function coupon_list(Request $request)
    {
		$lang=$request->lang_id;
        $login=0;
        $user_id=null;
        $user = [];
        
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
		
        $cart =Cart::join('usr_cart_item','usr_cart.id','=','usr_cart_item.cart_id')->join('prd_products','usr_cart_item.product_id','=','prd_products.id')
                        ->where('usr_cart.user_id',$user_id)    
                        ->where('usr_cart.is_active',1)
                        ->where('usr_cart.is_deleted',0)
                        ->where('usr_cart_item.is_active',1)
                        ->where('usr_cart_item.is_deleted',0)
                        ->where('prd_products.is_active',1)
                        ->where('prd_products.is_deleted',0)
                        ->pluck('usr_cart_item.product_id');
                        
                        
                        
        $sale =SaleOrder::where('order_status','delivered')->where('cust_id',$user_id)->count();
        $sale_amt =SaleOrder::where('order_status','pending')->where('cust_id',$user_id)->sum('total');                 
        
        $current_date=date('Y-m-d');
		$coupons = DB::table('coupon')->where('is_active',1)->where('is_deleted',0)->get();
      
     
      
      
	 $i=0;
	$c_list=[];
	  foreach($coupons as $row)
      { 
	  $coupon_details=[];
	  
        $querys = Product::whereIn('id',$cart);
        if($row->category_id!=0)
        {
          $querys = $querys->whereIn('category_id', [$row->category_id]);
        }
        if($row->subcategory_id!=0)
        {
            $querys = $querys->whereIn('sub_category_id', [$row->subcategory_id]);
        }
        
        $product = $querys->get();
    
		//dd($product);
        if(count($product)>0){
        if($row->validity_type=="range")
        {

          $range_coupon = Coupon::where('id',$row->id)->whereDate('valid_from','<=',$current_date)->whereDate('valid_to','>=',$current_date)->first();
          
		  if($range_coupon){
              if($row->purchase_type=='number')
               {
                   if($sale>=$row->purchase_number)
                   {
                   
              if($row->ofr_value_type=="percentage")
               {
                    $discount = $row->ofr_value." "."%";

               }
               else
               {
                    $discount = $row->ofr_value." "."RM";
               }
                    $coupon_details['coupon_id']=$row->id;
                    $coupon_details['title']=$this->get_content($row->cpn_title_cid,$lang);
                    $coupon_details['desc']=$this->get_content($row->cpn_desc_cid,$lang);
                    $coupon_details['offer']=$discount." ".$row->ofr_type;
                    $coupon_details['coupon_code']=$row->ofr_code;
                    $coupon_details['minimum_purchase']=$row->ofr_min_amount;
                    $coupon_details['offer_type']=$row->ofr_type;
                    $coupon_details['offer_value']=$row->ofr_value;
                    $coupon_details['offer_value_in']=$row->ofr_value_type;

               
                $coupon_details['purchase_type']=$row->purchase_type;
                $coupon_details['previous_order_count']=$row->purchase_number;
                $coupon_details['previous_order_amount']="";
               } 
               }
               else 
               {
				  
                   if($sale_amt>=$row->purchase_amount)
                   {
                   
              if($row->ofr_value_type=="percentage")
               {
                    $discount = $row->ofr_value." "."%";

               }
               else
               {
                    $discount = $row->ofr_value." "."RM";
               }
                    $coupon_details['coupon_id']=$row->id;
                    $coupon_details['title']=$this->get_content($row->cpn_title_cid,$lang);
                    $coupon_details['desc']=$this->get_content($row->cpn_desc_cid,$lang);
                    $coupon_details['desc']=$this->get_content($row->cpn_desc_cid,$lang);
                    $coupon_details['offer']=$discount." ".$row->ofr_type;
                    $coupon_details['coupon_code']=$row->ofr_code;
                    $coupon_details['minimum_purchase']=$row->ofr_min_amount;
                    $coupon_details['offer_type']=$row->ofr_type;
                    $coupon_details['offer_value']=$row->ofr_value;
                    $coupon_details['offer_value_in']=$row->ofr_value_type;

               
                $coupon_details['purchase_type']=$row->purchase_type;
                $coupon_details['previous_order_count']=$row->purchase_number;
                $coupon_details['previous_order_amount']="";
               }
			   

			   
               }
                   
            }

          }elseif($row->validity_type=="days")
          {
            
            $created_date=$row->created_at;
            $valid_date=$row->created_at->addDays($row->valid_days);
            $current_dates=Carbon::now();
            //$diff_in_days = $current_date->diffInDays($valid_date);
            $validity =$valid_date->gte($current_dates);
          if($validity==1){  
            if($row->purchase_type=='amount')
               {
                   if($sale_amt>=$row->purchase_amount)
                   {  
           if($row->ofr_value_type=="percentage")
               {
                    $discount = $row->ofr_value." "."%";

               }
               else
               {
                    $discount = $row->ofr_value." "."RM";
               }
                    $coupon_details['coupon_id']=$row->id;
                    $coupon_details['title']=$this->get_content($row->cpn_title_cid,$lang);
                    $coupon_details['desc']=$this->get_content($row->cpn_desc_cid,$lang);
                    $coupon_details['offer']=$discount." ".$row->ofr_type;
                    $coupon_details['coupon_code']=$row->ofr_code;
                    $coupon_details['minimum_purchase']=$row->ofr_min_amount;
                    $coupon_details['offer_type']=$row->ofr_type;
                    $coupon_details['offer_value_in']=$row->ofr_value_type;
                    $coupon_details['offer_value']=$row->ofr_value;

               
                $coupon_details['purchase_type']=$row->purchase_type;
                $coupon_details['previous_order_count']=$row->purchase_number;
                $coupon_details['previous_order_amount']="";
               } 
                 
               } 
               
               else
               {
                   if($sale>=$row->purchase_number)
                   {  
           if($row->ofr_value_type=="percentage")
               {
                    $discount = $row->ofr_value." "."%";

               }
               else
               {
                    $discount = $row->ofr_value." "."RM";
               }
                    $coupon_details['coupon_id']=$row->id;
                    $coupon_details['title']=$this->get_content($row->cpn_title_cid,$lang);
                    $coupon_details['desc']=$this->get_content($row->cpn_desc_cid,$lang);
                    $coupon_details['offer']=$discount." ".$row->ofr_type;
                    $coupon_details['coupon_code']=$row->ofr_code;
                    $coupon_details['minimum_purchase']=$row->ofr_min_amount;
                    $coupon_details['offer_type']=$row->ofr_type;
                    $coupon_details['offer_value']=$row->ofr_value;
                    $coupon_details['offer_value_in']=$row->ofr_value_type;

               
                $coupon_details['purchase_type']=$row->purchase_type;
                $coupon_details['previous_order_count']=$row->purchase_number;
                $coupon_details['previous_order_amount']="";
               } 
               }
                    
                    }

          }
		  
                   
                   
          
      }
                   
$i++;
         $c_list[]  = $coupon_details;  
        }
		
        $couponlist=array_filter($c_list);
        if($couponlist){
        return ['httpcode'=>200,'status'=>'success','coupon_list'=>array_values($couponlist)];
        }
        else{
          return ['httpcode'=>404,'status'=>'error','message'=>'Not found','data'=>['errors'=>'Not found']];
        }
        
       
    }
	public function get_offer_price($prdid,$type,$login,$qty){ 
		$offer['offer_name']= false;
		//$offer['offer_price']=0;
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
		//dd("sdgsd");	
            $offer['discount_type']= $shock->discount_type;   
            //$offer['offer_id']=$shock->id;
			
                $actual_price=$prod_data->prdPrice->price;
				if($shock->discount_type=="amount"){
					$offer['offer_available'] = 1;
					$offer['offer_name']= 'Shocking Sale';		
					$discount_value = $shock->discount_value;
					$unit_price = $actual_price-$discount_value;
					$offer['unit_discount_price']=round($discount_value,2);
					$offer['total_discount_price']=round($unit_price*$qty,2);
				}else{
					$offer['offer_available'] = 1;
					$offer['offer_name']= 'Shocking Sale';		
					$per=$shock->discount_value/100;
					$per_value = (float)$actual_price*(float)$per;
					$discount=(float)$actual_price-(float)$per_value;
					$round= round($discount, 2);
					$offer['unit_discount_price']= round($per_value,2);
					$offer['total_discount_price']=round($discount*$qty,2);
				}
			//dd($offer);
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
		//dd($offer_list)	;		
        return $offer_list;
   
	}
	
   function get_cart_products($prd_id,$cart_id,$qty,$lang,$prd_assign_id,$assortment_id,$user){
        $data     =   [];
        $prod_data       =   Product::where('is_active',1)->where('is_deleted',0)->where('id',$prd_id)->first();
        $crm_product_id = $prod_data->crmProduct->id;
        $prd_assort = CrmPartAssortmentMaster::where('productID',$crm_product_id)->where('AssortmentID',$assortment_id)->where('is_deleted',0)->first();
        
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
                    if($prd_assort)
                    {
                        $prd_list['assortment'] = $prd_assort->Assortments->Assortment;
                        $prd_list['assortment_id'] = $prd_assort->AssortmentID;
                        $prd_list['assortment_qty']=$qty;
                        $prd_list['custom_quantity'] = 0;
                        $quantity = $this->custom_product_qty($prod_data->id,$assortment_id,$user->id);
                    }else{
                        $prd_list['assortment'] = "Custom";
                        $prd_list['custom_quantity'] = $this->custom_product_qty($prod_data->id,0,$user->id);
                        $prd_list['assortment_qty']=0;
                        $quantity = $this->custom_product_qty($prod_data->id,0,$user->id);
                    }
                    if($prod_data->crmProduct->Colour)
                    {
                        $prd_list['colour'] = $prod_data->crmProduct->Colour->ColourName;
                    }else{
                        $prd_list['colour'] = "";
                    }
                  
					$prd_list['quantity'] = $quantity;
                   if($prod_data->crmProduct->prdBranch)
                   {
                    $prd_list['store_id'] = $prod_data->crmProduct->prdBranch->BranchId;
                   }else{
                    $prd_list['store_id'] = "";
                   } 
                                          
                    $prd_list['category_id']=$prod_data->category_id;
                    $prd_list['category_name']=$this->get_content($prod_data->category->cat_name_cid,$lang);
                    $prd_list['subcategory_id']=$prod_data->sub_category_id;
                    $prd_list['subcategory_name']=$this->get_content($prod_data->subCategory->sub_name_cid,$lang);
                    
                    $prd_list['currency']=getCurrency()->name;
                    if($prod_data->brand_id)
                    {
                        $prd_list['brand_id']=$prod_data->brand_id;
                        $prd_list['brand_name']=$this->get_content(@$prod_data->brand->brand_name_cid,$lang);
                    }
                    
                                       
                       $actual_price =get_crm_price($prod_data,$type=1,$user);
                   //$prod_data->prdPrice->price; 
                       
                   // dd($actual_price);
                    $prd_list['unit_actual_price']=$actual_price['actual_price'];
                    $tot_actual=($actual_price['actual_price'])*$quantity;
                    $prd_list['total_actual_price']=$tot_actual;
                    $tax_amt=$prod_data->getTaxValue($prod_data->tax_id);
					// dd($tax_amt);
					$total_tax_amount = $tot_actual * ($tax_amt/100);
                    $prd_list['total_tax_value']=$total_tax_amount;
     //                dd($spec_offr);
					// foreach ($spec_offr as $item) {
					// 		foreach ($item as $key => $value) {
					// 			$prd_list[$key] = $value;
					// 		} 
					// 	}
                   
                    $prd_list['unit_discount_price'] = $actual_price['offer'];
                        $prd_list['stock']=$prod_data->prdStock($prod_data->id);
                        if($prd_list['stock'] <= 0)
                        {
                            $prd_list['is_out_of_stock']=true;
                        }
                        else
                        {
                            $prd_list['is_out_of_stock']=false;
                        }
                        if($prod_data->out_of_stock_selling==0)
                        {
                            $prd_list['out_of_stock_selling']=false;
                        }
                        else
                        {
                            $prd_list['out_of_stock_selling']=true;
                        }	
            $prd_list['image']=$this->get_product_image($prod_data->id);
                    $data             =   $prd_list;
            
             }
            // else{ $data     =   []; } 
             return $data;
        
    }
    function getAttributesOfAssociativeProducts($prd_id,$lang){
        $data     =   [];
        
        $prod_data       =   AssignedAttribute::where('is_deleted',0)->where('prd_id',$prd_id)->orderBy('attr_id','asc')->groupBy('attr_id')->get();
          //dd( $prod_data  ) ;
		   if(count($prod_data)>0)   {
				 $attr_list=[];
                foreach($prod_data as $row)  {
					
                    $attr_list[]=$row->attrValue->name." ".$row->PrdAttr->name;
   				
				}
				$data             =   $attr_list;
				
		   }
		return $data;
	}
    //cart product according to seller
    function get_cart_seller_products($seller_id,$user_id,$lang){
        $data     =   [];
        $user = CustomerMaster::where('id',$user_id)->first(); 
        
       $prod_data1 = Cart::join('usr_cart_item','usr_cart.id','=','usr_cart_item.cart_id')->join('prd_products','usr_cart_item.product_id','=','prd_products.id')
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
                        
                     
            if($prod_data1)   { 
                
                foreach($prod_data1 as $prod_data){
                    $store_active = CrmBranch::where('DelStatus',0)->where('Branch_Id',$prod_data->seller_id)->first();
                    if($store_active)
                    {
                    
                   
                    $single_prod_data       =   Product::where('is_active',1)->where('is_deleted',0)->where('id',$prod_data->product_id)->first();
                    $prices    = get_crm_price($single_prod_data,$type=1,$user);
                     
                    $crm_product_id = $single_prod_data->crmProduct->id;
                    $assortment_id = $prod_data->assortment_id;
                    $prd_assort = CrmPartAssortmentMaster::where('productID',$crm_product_id)->where('AssortmentID',$assortment_id)->where('is_deleted',0)->first();

                    $qty=$prod_data->assortment_qty;
                    // dd($prd_assort);
                    if($prd_assort)
                    {
                        $prd_list['assortment'] = $prd_assort->Assortments->Assortment;
                        $prd_list['assortment_id'] = $prd_assort->AssortmentID;
                        $prd_list['assortment_id'] = $assortment_id;
                        $prd_list['assortment_qty']=$qty;
                        $prd_list['custom_quantity'] = 0;
                        
                       
                            
                            $child_detail = $this->children_data($single_prod_data->id,$assortment_id,$user_id,$qty,$prd_assort);
                            $prd_list['child_detail']=$child_detail;
                           
                        
                        
                        $quantity = $this->custom_product_qty($single_prod_data->id,$assortment_id,$user_id);
                        //if($user_id ==126){ dd($quantity); }
                        $quantity = $quantity*$qty;
                        //$prd_list['children'] = $prd_assort;
                    }else{
                        $prd_list['assortment'] = "Custom";
                        $prd_list['custom_quantity'] = $this->custom_product_qty($single_prod_data->id,0,$user_id);
                        $prd_list['assortment_qty']=0;
                         $prd_list['assortment_id'] = 0;
                            $child_qty = $prod_data->quantity;
                            $child_detail = $this->children_data($single_prod_data->id,0,$user_id,$child_qty);
                            $prd_list['child_detail']=$child_detail;
                        
                        $quantity = $this->custom_product_qty($single_prod_data->id,0,$user_id);
                    }
                    // dd($quantity);
                    if($single_prod_data->crmProduct->Colour)
                    {
                        $prd_list['colour'] = $single_prod_data->crmProduct->Colour->ColourName;
                    }else{
                        $prd_list['colour'] = "";
                    }

                    $prd_list['cart_id']=$prod_data->cart_id;   
                    $prd_list['product_id']=$prod_data->product_id;
                    if($prod_data->product_type==1){
                     $prd_list['product_type'] ='simple';   
                    }
                    else
                    {
                        $prd_list['product_type'] ='config'; 
                    }
                   
                    $prd_list['product_name']=$this->get_content($prod_data->name_cnt_id,$lang);
                    $prd_list['image']=$this->get_product_image($prod_data->product_id);
                    
                   
                    $prd_list['quantity']=$quantity;
                    $prd_list['category_id']=$prod_data->category_id;
                    $prd_list['category_name']=$this->get_content(@$single_prod_data->category->cat_name_cid,$lang);
                    $prd_list['subcategory_id']=$prod_data->sub_category_id;
                    $prd_list['subcategory_name']=$this->get_content(@$single_prod_data->subCategory->sub_name_cid,$lang);
                    $prd_list['seller_id']=$prod_data->seller_id;
                   // $prd_list['brand_id']=$prod_data->brand_id;
                    $prd_list['currency']=getCurrency()->name;
                    if($prod_data->brand_id)
                    {
                        $prd_list['brand_id']=$prod_data->brand_id;
                        $prd_list['brand_name']=$this->get_content(@$single_prod_data->brand->brand_name_cid,$lang);
                    }
                    $actual_price = get_crm_price($single_prod_data,$type=1,$user); 
                    $prd_list['unit_actual_price']=$actual_price['actual_price'];
                    $tot_actual = $actual_price['actual_price']* $quantity;
                    $prd_list['total_actual_price']=$tot_actual;
                    $tax_amt=$single_prod_data->getTaxValue($single_prod_data->tax_id);
                    
                    $total_tax_amount = $tot_actual * ($tax_amt/100);
                    $prd_list['total_tax_value']=$total_tax_amount;
                   // $prd_list['cmm_type'] = $prod_data->commi_type;
                   if($prod_data->commission>0)
                   {
                       if($prod_data->commi_type=='%')
                       {
                           $cmm_price = $prices * ($prod_data->commission/100);
                           $cmm_tot   = $cmm_price*$qty;
                           $prd_list['commission'] = $cmm_tot;
                       }
                       else
                       {
                           $cmm_price =$prod_data->commission;
                           $cmm_tot   = $cmm_price*$qty;
                           $prd_list['commission'] = $cmm_tot;
                       }
                   }
                   else
                   {
                       $prd_list['commission'] = 0;
                   }
                     
                    
                    //Available offers for this product
            
                   if($actual_price['offer']>0){

                        $prd_list['offer_available']= 1;
                        $prd_list['offer_name']= 'Discount'; 
                        $sale_price =$this->get_sale_price($prod_data->product_id);
                       
                        $prd_list['unit_discount_price']=(float)( $actual_price['actual_price'] - $actual_price['offer_price']);
                        $tot= $actual_price['offer_price'] * $quantity;
                        $prd_list['total_discount_price']=(float) $tot;
                       

                   }else{
                        $prd_list['offer_available']= 0;
                        $prd_list['offer_name']= ''; 
                        $sale_price =$this->get_sale_price($prod_data->product_id);
                       
                        $prd_list['unit_discount_price']=false;
                        $prd_list['total_discount_price']=false;
                        
                   }

       
            $prd_list['product_point']=$prod_data->points;
            $prd_list['is_out_of_stock']=$prod_data->is_out_of_stock;
           
                    $data[]             =  $prd_list;
                    }//Active store
             }
             
        }//end if
            // else{ $data     =   []; } 
             return $data;
        
    }


/***************get coupon applied products****/
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
            $prd_list['image']=$this->get_product_image($prod_data->id);
                    $data             =   $prd_list;
             }
            // else{ $data     =   []; } 
             return $data;
        
    }


  /*************GET VALUES **************/
    
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
  
   function custom_product_qty($prd_id,$assort,$user_id=0)
  {
    $custom_qty=DB::table("usr_cart_item")->select(DB::raw("SUM(quantity) as quantity"))->where('product_id',$prd_id)->where('assortment_id',$assort)->where('is_active',1)->where('is_deleted',0)->whereIn('cart_id',function($query) use($user_id) {
   $query->select('id')->from('usr_cart')->where('is_deleted',0)->where('is_active',1)->where('user_id',$user_id);})->first();
    
    if($custom_qty->quantity > 0)
    {
        return $custom_qty->quantity;
    }
    else
    {
        return 0;
    }
  }
  
  function children_data($prd_id,$assort,$user_id=0,$qty=0,$prd_assort="")
  {
      $child_datails = $child_detail =  [];
      if($assort ==0)
      {
         $custom_child=DB::table("usr_cart_item")->select("prd_assign_id","quantity")->where('product_id',$prd_id)->where('assortment_id',$assort)->where('is_active',1)->where('is_deleted',0)->whereIn('cart_id',function($query) use($user_id) {
   $query->select('id')->from('usr_cart')->where('is_deleted',0)->where('user_id',$user_id);})->get(); 
   
        if($custom_child)
        {
            foreach($custom_child as $cust_key=>$cust_val)
            {
                
                $child_id = $cust_val->prd_assign_id; $child_qty = $cust_val->quantity;
                $child_data = CrmChildProductsMaster::where('ChildProductID',$child_id)->first();
                if($child_data->SizeInfo){ $size_name = $child_data->SizeInfo->SizeName;}else{ $size_name = ""; }
                $child_detail['size'] = $size_name;
                $child_detail['quantity'] = $child_qty;
                $child_detail['child_product_id'] = $child_data->ChildProductID;
                $child_detail['available_quantity'] = $child_data->ChildPrdStock($child_data->ChildProductID,$child_data->prd_id);
                                        
                $child_datails[]=$child_detail;
                
            }
            
        }

       
      }else{
          
        if($prd_assort->Assortments)
            {
        
                if($prd_assort->AssortmentsDetail)
                {
        
                    foreach($prd_assort->AssortmentsDetail as $child_prod_k=>$child_val)
                    {
                        
                       if($child_val->ChildProduct){ $size_name = $child_val->ChildProduct->SizeInfo->SizeName; }else{
                          $size_name = "";
                           
                       } 
        
                            $child_detail['size'] = $size_name;
                            $child_detail['quantity'] = $child_val->ChildQuantity;
                             $child_detail['child_product_id'] = $child_val->ChildProductID;
                            if($child_val->ChildProduct){ $child_detail['available_quantity'] = $child_val->ChildProduct->ChildPrdStock($child_val->ChildProduct->ChildProductID,$child_val->ChildProduct->prd_id); }else{ $child_detail['available_quantity'] =0; }
                            $child_datails[]=$child_detail; 
        
                        
                    }
              
        
                }
                
            }
          
          
      }
   
   return $child_datails;
  }

    //Product sale price
    public function get_sale_price($field_id){ 

     
       $current_date=Carbon::now();
       $rows = PrdPrice::where('is_deleted',0)->where('prd_id',$field_id)->whereDate('sale_end_date','>=',$current_date)->orderBy('id','DESC')->first();        
        if($rows){ 
        $return_val = $rows->sale_price;
        return $return_val;
        }
        else
            { $return_val=0;
                return $return_val; }
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
        
        //Product special price
    public function get_special_ofr_price($field_id,$price){ 

       $return_val=0;
       $current_date=Carbon::now();
       $rows = PrdOffer::where('is_deleted',0)->where('prd_id',$field_id)->whereDate('valid_from','<=',$current_date)->whereDate('valid_to','>=',$current_date)->first();        
        if($rows){ 
        $discount_val = $rows->discount_value;
        $discount_typ = $rows->discount_type;
        if($discount_typ=="percentage")
        {
            $dis = $price * ($discount_val/100);
            $return_val = $price-$dis;
        }
        else
        {
            $return_val = $price - $discount_val;
        }
        if($return_val>0)
        {
            return $return_val;
        }
        else
        {
             return false;
        }
        
        }
        else
            { $return_val=false;
                return $return_val; }
        }
		
		
	public function cart_count(Request $request){
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
		if($cart){
		$data['cart_count']     =       count($cart);
		}else{
		$data['cart_count']     = 0;	
		}
	   
	         return ['httpcode'=>200,'status'=>'success','message'=>'success','data'=>$data];

	}
	}	
      
	}
