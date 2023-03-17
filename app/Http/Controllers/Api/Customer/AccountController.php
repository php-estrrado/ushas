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
use App\Models\Category;
use App\Models\UsrNotification;
use App\Models\SalesOrderRefundPayment;
use App\Models\SalesOrderReturnShipment;
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
use App\Models\customer\CustomerBranches;
use App\Models\customer\CustomerBranchEmployees;
use App\Models\customer\CustomerBusinessDetails;
use App\Models\LogLoyaltyPoints;
use App\Models\Reward;
use App\Models\RewardType;
use App\Models\Coupon;
use App\Models\AssignedAttribute;
use App\Models\PrdImage;
use App\Models\SalesOrderItemOption;
use App\Models\InviteSave;
use App\Models\InviteSaveLog;
use App\Models\ParentSale;
use App\Models\Chat;
use App\Models\Store;
use App\Models\customer\CustomerCredits;
use App\Models\customer\CustomerCreditLogs;
use App\Models\CustomerRegisterotp;
use Mail;
use Illuminate\Support\Str;
use Twilio\Rest\Client;
use App\Models\CustomerPoints;

use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\crm\{CrmAssortmentMaster, CrmChildProductsMaster, CrmCustomerType,CrmPartAssortmentDetails,
CrmPartAssortmentMaster,CrmProduct,CrmSalesPriceList,CrmSalesPriceType,CrmSize,CrmBranch,CrmCompany};

class AccountController extends Controller
{


    public function addWishlist(Request $request)
    {
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
            $formData   =   $request->all(); 
            $rules      =   array();
            $rules['product_id']    = 'required|numeric';
            $rules['type']          = 'required|string|in:WEB,APP';
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 
                    $checklist = Wishlist::where('user_id',$user_id)->where('is_active',1)->where('is_deleted',0)->first();
                    $checkprd =  UsrWishlist::where('user_id',$user_id)->where('prd_id',$formData['product_id'])->where('is_deleted',0)->first();
                    if($checkprd)
                    {
                        return array('httpcode'=>'400','status'=>'error','message'=>'Already exist','data'=>['message' =>'Product is already in the wishlist!']);
                    }
                    
                    if($checklist == '')
                    {
                        $createwishlist =  Wishlist::create(['user_id' => $user_id,
                        'title' => $formData['type'],
                        'created_at'=>date("Y-m-d H:i:s"),
                        'updated_at'=>date("Y-m-d H:i:s")]);
                    }
                    $wishlist = UsrWishlist::create(['user_id' => $user_id,
                    'prd_id' => $formData['product_id'],
                    'created_at'=>date("Y-m-d H:i:s"),
                    'updated_at'=>date("Y-m-d H:i:s")]);
                    return array('httpcode'=>'200','status'=>'success','message'=>'product added','data'=>['message' =>'Your product successfully added in wishlist!']);
                }
        }else{ return invalidToken(); }
    }
    public function wishlist(Request $request)
    {
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
            $val       =   [];
            $formData   =   $request->all(); 
            $rules      =   array();
            $rules['lang_id']    = 'nullable|numeric';
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 
                    $lang =  $request->lang_id;
                   
                    $list =  UsrWishlist::where('user_id',$user_id)->where('is_deleted',0)->get();
                   //return $list;die;
                    foreach($list  as $row)
                    {
                         $prdId                     =   $row->prd_id;
                         $avaliable = Product::where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->where('visible',1)->where('id',$prdId)->first();
                        
                          $products = Product::where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->where('visible',1)->where('id',$prdId)->first();
                          if($products){

                            $prd_list['product_id']=$products->id;
                    $prd_list['product_name']=$this->get_content($products->name_cnt_id,$lang);
                    $prd_list['category_id']=$products->category_id;
                    $prd_list['category_name']=$this->get_content($products->category->cat_name_cid,$lang);
                    $prd_list['subcategory_id']=$products->sub_category_id;
                    $prd_list['subcategory_name']=$this->get_content($products->subCategory->sub_name_cid,$lang);
                    if($products->brand_id)
                    {
                    $prd_list['brand_id']=$products->brand_id;
                    $prd_list['brand_name']=$this->get_content($products->brand->brand_name_cid,$lang);
                    }
                    else
                    {
                    $prd_list['brand_id']='';
                    $prd_list['brand_name']='';  
                    }
                    if($products->product_type==1)
                    {
                    $prd_list['product_type']='simple';    
						
						
					
                        $wlist_prd_price=get_crm_price($products,$type=1,$user);
                        foreach ($wlist_prd_price as $key => $value) {
                        $prd_list[$key] = $value;
                        } 
                        
					}
                    else
                    {
                     $prd_list['product_type']='config'; 
                     //print_r($products->id);
					 $wlist_prd_price=get_crm_price($products,$type=1,$user);
                        foreach ($wlist_prd_price as $key => $value) {
                        $prd_list[$key] = $value;
                        } 	
						
					}
                    $prd_list['shock_sale_price'] = $this->shock_sale_price($products->id);
                    $prd_list['image']=$this->get_product_image($products->id); 
                  

                   
            $val[]=$prd_list;

                    }
                    }
                   
                    return array('httpcode'=>'200','status'=>'success','message'=>'wishlist','data'=>['wishlist'=>$val,'count_wish'=>count($val)]);
                }
        }else{ return invalidToken(); }
    }
	
		public function get_price($prdid,$type,$login){ 
		//$offer['offer_price']=false;
        $current_date=Carbon::now();
        $prod_data= Product::where('id',$prdid)->first();
	//	print_r($prdid);
            $shock = PrdShock_Sale::join('prd_shock_sale_products','prd_shock_sale.id','=','prd_shock_sale_products.shock_sale_id')
            ->where('prd_shock_sale.is_active',1)->where('prd_shock_sale.is_deleted',0)->whereDate('prd_shock_sale.start_time','<=',$current_date)->whereDate('prd_shock_sale.end_time','>=',$current_date)
            ->where('prd_shock_sale_products.is_active',1)->where('prd_shock_sale_products.is_deleted',0)->whereRaw("find_in_set($prod_data->id,prd_shock_sale_products.prd_id)")
            ->select('prd_shock_sale.*','prd_shock_sale_products.seller_id','prd_shock_sale_products.prd_id as shock_prd_id')->first();
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
				$offer['actual_price']=$Price->price;
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
						$offer['offer_price']=$unit_price;
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
						$offer['offer_price']=$discount;
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
				$offer['actual_price']=$Price->price;
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
				$offer['actual_price']=$Price->price;
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
						$offer['offer_price']=$offer['offer_price'];
					}else{
						$offer['offer_price']=NULL;
					}
			}
			else {
				if($login==1){
							$offer['offer_price']=$Price->price;
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
				$offer['actual_price']=$Price->price;
                }else{
                	$offer['actual_price']=NULL;   
                }
			}else{
				$offer['actual_price']=NULL;
			}
		if($login==1){
						$offer['offer_price']=$SalesPrice->sale_price;
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
				$offer['actual_price']=$Price->price;
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
            //dd($val);
            return $val;
                    
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
	public function getProductimages($prd_id){
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
	        //Product ACTUAL price
    public function get_actual_price($field_id){ 

     
       //$current_date=Carbon::now();
       $rows = PrdPrice::where('is_deleted',0)->where('prd_id',$field_id)->orderBy('id','DESC')->first();        
        if($rows){ 
        $return_val = $rows->price;
        
            return $return_val;
        
        }
        else
            { $return_val=false;
                return $return_val; }
        }
    public function removeWishlist(Request $request)
    {
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
            $formData   =   $request->all(); 
            $rules      =   array();
            $rules['product_id']    = 'required|numeric';
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 
                    $checkprd =  UsrWishlist::where('user_id',$user_id)->where('prd_id',$formData['product_id'])->where('is_deleted',0)->first();
                    if($checkprd)
                    {
                         UsrWishlist::where('user_id',$user_id)->where('prd_id',$formData['product_id'])->update(['is_deleted'=>1]);
                    }
                    return array('httpcode'=>'200','status'=>'success','message'=>'product removed','data'=>['message' =>'Your product successfully removed from wishlist!']);
                }
        }else{ return invalidToken(); }
    }
    //Year filter
    public function year_filter(Request $request)
    {
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
            $formData   =   $request->all(); 
            $rules      =   array();
            $rules['access_token']    = 'required';
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 
                    $sales =  SalesOrder::where('order_status', '!=', "initiated")->where('cust_id',$user_id)->pluck('created_at')->unique();
                    if($sales)
                    {
                        foreach($sales as $row){
                            $year[]=$row->year;
                        }
                        $year= array_unique($year);
                        return array('httpcode'=>'200','status'=>'success','message'=>'Years','data'=>['year' =>$year]);
                    }
                    else
                    {
                        return array('httpcode'=>'404','status'=>'error','message'=>'Not found');
                    }
                    
                }
        }else{ return invalidToken(); }
    }
    
    function get_content($field_id,$lang)
    { 
        if($lang=='')
        { 
        $language =DB::table('glo_lang_lk')->where('is_active', 1)->first();
        $language_id=$language->id;
        }
        else
        {
            $language_id=$lang;
        }
        $content_table  =   CmsContent::where('cnt_id', $field_id)->where('lang_id', $language_id)->first();
        if(!empty($content_table))
        { 
            $return_cont = $content_table->content;   
        }
        else
        {
            $return_cont = ''; 
        }
        return $return_cont;
    }

    function get_rates($field_id)
    { 
        $rate = PrdReview::select(DB::raw('AVG(rating) as rating'))->where('prd_id',$field_id)->where('is_active',1)->where('is_deleted',0)->first();
        if($rate)
        { 
            $return_val = round($rate->rating);
        }
        else
        { 
            $return_val =   0;
        }
        return $return_val;
    }

    public function get_sale_price($field_id)
    { 
       $current_date    =   Carbon::now();
       $rows            =   PrdPrice::where('is_deleted',0)->where('prd_id',$field_id)->whereDate('sale_end_date','>=',$current_date)->first();        
        if($rows)
        { 
            $return_val = $rows->sale_price;
        }
        else
        { 
             $return_val=false;
        } return $return_val;
    }

    function get_product_image($prd_id)
    {
        $data     =   [];
        $product       =   ProductImage::where('prd_id',$prd_id)->where('is_deleted',0)->get(); 
        if($product->count() > 0)   
            {   
                foreach($product as $k=>$row)
                { 
                    $val['image']       =   config('app.storage_url').$row->image;
                    $val['thumbnail']   =   config('app.storage_url').$row->thumb;
                    $data[]             =   $val;
                } 
            }
        else
            { 
                $val['image']       =   config('app.storage_url').'/app/public/no-image.png';
                $val['thumbnail']   =   config('app.storage_url').'/app/public/no-image-thumbnail.jpg';
                $data[]     =   $val; 
            } 
        return $data;
    }

   
   public function my_purchase_filter(Request $request)
    {
		if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
        $user_id = $user['user_id'];
		$validator=  Validator::make($request->all(),[
            'access_token'          => ['required']
        ]);
		
        $input = $request->all();

		if ($validator->fails()){    
			return ['httpcode'=>400,'status'=>'error','message'=>'Invalid parameters','data'=>['errors'=>$validator->messages()]];
		}else{
			$branches=[];
            $master =  CustomerMaster::where('is_active',1)->where('is_deleted',0)->where('id',$user_id)->first(); 
			if(@$master->parent_id==0){
				$branch= CustomerBranches::where('is_active',1)->where('is_deleted',0)->where('user_id',$user_id)->get(); 
				if($branch){
				foreach($branch as $k=>$row){ 
						$val['id']       	=   $row->id;
						$val['branch_name'] =   $row->branch_name;
						//$val['address']     =   CustomerBranches::getBranchAddress($row->address_id);
						$branches[]         =   $val;
					} 
				}
            }
        $order_status = ['Pending'=>'pending','Cancelled'=>'cancelled','Delivered'=>'delivered','Returned'=>'rejected'];
        $years = SaleOrder::select(DB::raw('YEAR(created_at) as year'))->distinct()->pluck('year');
        $branches=$branches;
		//return $years; 
        $order_time = ['Last 30 days','Last 6 months',$years];
        return array('httpcode'=>'200','status'=>'success','message'=>'My purchase filter','data'=>['order_status'=>$order_status,'order_time'=>$order_time,'branches'=>$branches]);
        }
    }

    public function purchase(Request $request)
    {
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
            $val        =   [];  $cdata   =   []; 
            $formData   =   $request->all(); 
            $rules      =   array();
            
            if($request->order_status!='returned'){
            $ord_status =   $request->order_status;
                $returned='';
            }
            else
            {   $ord_status ='';
                $returned = $request->order_status;
            }
            
            //search
            $orderIds=$prd_name='';
            if($request->search){
            if(is_numeric($request->search))
            {
              $orderIds=  $request->search;
            }
            else
            {
                $prd_name=  $request->search;
            }
            }

            //ORDER TIME FILTER
            $last_days='';
            $year='';
            if($request->order_time=='Last 30 days')
            {
                $last_days =Carbon::now()->subDays(30);
            }
            elseif($request->order_time=='Last 6 months')
            {
               $last_days =Carbon::now()->subMonths(6);
            }
            else
            {
                $year       =   $request->order_time;
            }
			if($request->branch_id){
				$branch_id=$request->branch_id;
			}else{
				$branch_id=0;
			}

            //////

            $rules['lang_id']    = 'required|numeric';
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 
                    $lang =  $request->lang_id;
					$currency = getCurrency()->name;
                     
                    if($returned)
                    {
                        $sale_return = SalesOrderReturn::where('user_id',$user_id)->pluck('sales_id');
                    }
                    else
                    {
                        $sale_return='';
                    }
					DB::enableQueryLog();
                    $sales =  SalesOrder::where('order_status', '!=', "initiated")->where('cust_id',$user_id)->orderBy('id','desc')->when($year, function ($q,$year) {
                        return $q->whereYear('created_at', $year);
                        })->when($ord_status, function ($q,$ord_status) {
                        return $q->where('order_status', $ord_status);
                        })->when($sale_return, function ($q,$sale_return) {
                        return $q->whereIn('id', $sale_return);
                        })->when($orderIds, function ($q,$orderIds) {
                        return $q->where('order_id', $orderIds);
                        })->when($last_days, function ($q,$last_days) {
                        return $q->where('created_at','>=', $last_days);
                        })->when($branch_id, function ($q,$branch_id) {
                        return $q->where('branch_id', $branch_id);
                        })->get();
						
//dd(\DB::getQueryLog()); 

                    if($sales->count() >0)
                    {
                        
                        foreach($sales  as $row)
                        {
                             $items_count=0;
							$produc=[];
							$rtrn=[];
                            $sal_id = $row->id;
                            $all_items  =   SaleorderItems::where('sales_id',$row->id)->when($prd_name, function ($q,$prd_name) {
                                return $q->where('prd_name','Like', '%' . $prd_name . '%');})->get();
                               // $all  =   SaleorderItems::where('sales_id',$row->id)->first();
                               // dd($all->qty);
                                $ship       =   SalesOrderShippingStatus::where('sales_id',$row->id)->orderBy('created_at', 'desc')->first(); 
                                $ord        =   SalesOrderCancel::where('sales_id',$row->id)->orderBy('created_at', 'desc')->first();
                                $adddr      =   SalesOrderAddress::where('sales_id',$row->id)->first();
                              //  $seller     =   SellerInfo::where('seller_id',$row->seller_id)->first();
                                $payment    =   SalesOrderPayment::where('sales_id',$row->id)->first();
                               // $storeinfo     =   Store::where('seller_id',$row->seller_id)->first();
								$data['order_id']          =   $row->order_id;
								$data['sale_id']           =   $row->id;
								$data['order_date']        =   date('d-m-Y',strtotime($row->created_at));
                                $data['order_time']        =   date('g:i a',strtotime($row->created_at));
                                $data['g_total']        =   $row->g_total;
								$data['currency']          =   $currency;
							
								$data['payment_mode']      =  $payment->payment_type;
                                    $data['payment_status']    =  $row->payment_status;
                
                                    $data['delivered_date']    =   '';
                                    $data['return_date']       =   '';
                                    if($ship)
                                    {
                                         $data['delivery_status']   =   $ship->status;
                                         if($ship->status == 'delivered')
                                         {
                                            $data['delivered_date']    =   date('F d Y',strtotime($ship->updated_at));
                                            $data['return_date']       =    date('F d Y',strtotime($ship->updated_at. ' + 2 days'));
                                         }
                                    }
                                    else
                                    {
                                     if($row->delivery_status){    $data['delivery_status']   =   $row->delivery_status; }else {  $data['delivery_status']   = 'Pending'; }
                                    }
                                  
                                   // $data['sold_by']        =   $storeinfo->store_name ;
                                    $data['track_order']    =   '';
                                    $data['order_status']   =   $row->order_status;
                                    if($data['order_status']=="rejected"){
									    $data['rejected_date']=date('F d Y',strtotime($row->updated_at));
									}else{
									   $data['rejected_date']="";  
									}
									if($data['order_status']=="pending" || $data['order_status']=="accepted"  || $data['order_status']=="ready to pick up" )
									{
										$data['estimated_delivery_date']= date('F d Y',strtotime($row->created_at. ' + 10 days'));
									}else{
										$data['estimated_delivery_date']="";
									}
									
                                    $data['cancel_order_detail'] = array();
                                    if($row->order_status == 'cancel_initiated' || $row->order_status == 'cancelled')
                                    {
                                         if($ord)
                                            {
                                                $ordnote = SalesOrderCancelNote::where('cancel_id',$ord->id)->first();
                                                $cdata['cancel_id']      =   $ord->id;
                                                $cdata['cancel_title']   =   $ordnote->title;
                                                $cdata['cancel_notes']   =   $ordnote->note;
                                                $cdata['cancelled_date']   =   date('F d Y',strtotime($ordnote->created_at));
                                                 $data['cancel_order_detail'] =  $cdata;
                                            }
                                            else
                                            {
                                                 $data['cancel_order_detail'] =  [];
                                            }
                                           
                                    }
                                   
                                   //  $data['auction_status']=   'False';
                                     $data['bid_charge']    =   0;
                                    if($adddr)
                                    {
                                     $saddr['address_type'] = $adddr->stype->usr_addr_typ_name;
                                     $saddr['name']         = $adddr->s_name; 
                                     $saddr['phone']        = $adddr->s_phone; 
                                     $saddr['email']        = $adddr->s_email; 
                                     $saddr['address1']     = $adddr->s_address1; 
                                     $saddr['address2']     = $adddr->s_address2; 
                                     $saddr['zip_code']     = $adddr->s_zip_code;      
                                     $saddr['country']      = $adddr->scountry->country_name; 
                                     $saddr['state']        = $adddr->sstate->state_name; 
                                    if($adddr->scity){ $saddr['city']         = $adddr->scity->city_name; }else { $saddr['city'] = ''; }
                                     $saddr['latitude']     = $adddr->s_latitude; 
                                     $saddr['longitude']    = $adddr->s_longitude;
                                    }
                                    else{
                                    $saddr['address_type']  = '';
                                     $saddr['name']         = '';
                                     $saddr['phone']        = '';
                                     $saddr['email']        = '';
                                     $saddr['address1']     = '';
                                     $saddr['address2']     = '';
                                     $saddr['zip_code']     = '';
                                     $saddr['country']      = '';
                                     $saddr['state']        = '';
                                     $saddr['city']         = '';
                                     $saddr['latitude']     = '';
                                     $saddr['longitude']    = '';
                                    }
                                     $data['shipping_address'] =  $saddr;
									 $return_orders= SalesOrderReturn::where('sales_id',$row->id)->where('is_deleted',0)->first();
									//dd($return_orders);
									if($return_orders){
                                        $rtrn['status']      =   $return_orders->status;
                                        $rtrn['id']          =   $return_orders->id;
                                        $rtrn['return_type']     =   $return_orders->return_type;
                                        $rtrn['payment_status']     =   $return_orders->payment_status;
                                             
                                    }
                                    else
                                    {
                                        $rtrn =       [];
                                    }
                                    $data['return_order']=$rtrn;

                                foreach($all_items  as $items)
                                {
                                   
								   $prdId      =   $items->prd_id;
                                    $products   =   Product::where('id',$prdId)->first();
									$datas['product_id']        =   $prdId;
                                    $datas['product_name']      =   $items->prd_name;
                                    $datas['quantity']      =   $items->qty;
                                   	$items_count+=   $items->qty;
									
                         
								$produc[]	=   $datas; 					
								}
								$data['items_count']      =$items_count;
							$data['products'] = 	$produc;	
                             $val[] = $data;    
                        }
                        
                    }
                    else
                    {
                       $val        =   []; 
                    }
                    return array('httpcode'=>'200','status'=>'success','message'=>'my purchase','data'=>['purchase'=>$val]);
                }
        }else{ return invalidToken(); }
    }
    public function order_detail(Request $request)
    {
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
            $val        =   [];
            $formData   =   $request->all(); 
            $rules      =   array();
            $rules['sale_id']   = 'required|numeric';
            $rules['lang_id']    = 'required|numeric';
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 
                    $lang =  $request->lang_id;
                    // if($request->currency_code)
                    // {
                    //     $crv = get_currency_rate($request->currency_code);
                    //     $currency = $request->currency_code;
                    // }
                    // else
                    // {
                    //     $crv=1;
                    //     $currency = getCurrency()->name;
                    // } 
                    $sales =  SalesOrder::where('cust_id',$user_id)->where('id',$formData['sale_id'])->first(); //dd($sales);
                    if($sales)
                    {
                        $pay      =   SalesOrderPayment::where('sales_id',$request->sale_id)->first();
                       // $seller   =   SellerInfo::where('seller_id',$sales->seller_id)->first();
                         $sale_parent   =   ParentSale::where('id',$sales->parent_sale_id)->first();
                        if(isset($sale_parent->currency_rate)) { $crv =$sale_parent->currency_rate;  $currency = $sale_parent->currency_code; } else {
                        $crv=1;
                        $currency = getCurrency()->name;
                        }    
                        $ship     =   SalesOrderShippingStatus::where('sales_id',$request->sale_id)->first();
                       
                        $ord      =   SalesOrderCancel::where('sales_id',$sales->id)->orderBy('created_at', 'desc')->first();
                        $data['cust_id']           =   $user_id;
                        $data['seller_id']           =   $sales->seller_id;
                        $chat= Chat::where('seller_id',$sales->seller_id)->where('created_by',$user_id)->where('is_deleted',0)->first();  
                        if($chat)
                        {   
                            $data['chat_id']           =   $chat->id;
                        }
                        $data['sale_id']           =   $sales->id;
                        $data['order_id']          =   $sales->order_id;
                        $data['order_date']        =   date('d-m-Y',strtotime($sales->created_at));
                        $data['order_time']        =   date('g:i a',strtotime($sales->created_at));
                        $data['pay_method']        =   $pay->payment_type;
                        $data['shipping']          =   0;
                        $data['discount']         =   round($sales->discount);
                        $data['payment_status']    =  $sales->payment_status;
                        
                        
                        
                        
                       
                        
                        $data['delivered_date']    =   '';
                        $data['return_date']       =   '';
                        if($ship)
                        {
                             $data['delivery_status']   =   $ship->status;
                             if($ship->status == 'delivered')
                             {
                                $data['delivered_date']    =   date('F d Y',strtotime($ship->updated_at));;
                                $data['return_date']   =    date('F d Y',strtotime($ship->updated_at. ' + 2 days'));
                             }
                        }
                        else
                        {
                            $data['delivery_status']   =   $sales->delivery_status;;
                        }
                        
                       // $data['sold_by']            =   $seller->fname;
                        $data['order_status']       =   $sales->order_status;
                        if($data['order_status']=="rejected"){
									    $data['rejected_date']=date('F d Y',strtotime($sales->updated_at));
									}else{
									   $data['rejected_date']="";  
									}
                        $data['cancel_order_detail'] = array();
                        if($sales->order_status == 'cancel_initiated')
                        {
                             if($ord)
                                {
                                    $ordnote = SalesOrderCancelNote::where('cancel_id',$ord->id)->first();
                                    $cdata['cancel_id']      =   $ord->id;
                                    $cdata['cancel_title']   =   $ordnote->title;
                                    $cdata['cancel_notes']   =   $ordnote->note;
                                    $cdata['cancelled_date']   =   date('F d Y',strtotime($ordnote->created_at));
                                    $data['cancel_order_detail'] =  $cdata;
                                }
                                else
                                {
                                     $data['cancel_order_detail'] =  [];
                                }
                               
                        }
                        $all_items      =   SaleorderItems::where('sales_id',$sales->id)->get(); 
                        $data['tax']               =  0;
                        foreach($all_items  as $items)
                        {
							
							$prdId      =   $items->prd_id;
                            $products   =   Product::where('id',$prdId)->first();
                            
                            if($products->crmProduct->Colour)
                            {
                            $prd['colour'] = $products->crmProduct->Colour->ColourName;
                            }else{
                            $prd['colour'] = "";
                            }
                            $prd['sale_items_id']     =   $items->id;
                            $prd['product_id']        =   $prdId;
							if($products->category_id){
								$category=Category::where('category_id',$products->category_id)->first();
								$prd['is_rating']=@$category->is_rating;
							}else{
								
								$prd['is_rating']=0;
							}
							
                            if($products->product_type==1){
                            $prd['product_type']      = "simple";
                            $prd['product_name']      =   $this->get_content($products->name_cnt_id,$lang);
                            $prd['product_image']     =   $this->get_product_image($products->id);
                            $prd['visible_product_id']=$products->id;
							}
                            else
                            {
                                $prd['product_type']      = "config";
                                
								$prd_assoc = Product::where('id',$items->prd_id)->first();
                                $prd['product_name']      =   $this->get_content($prd_assoc->name_cnt_id,$lang);
                                $prd['product_image']     =   $this->get_product_image($prd_assoc->id);
								
								
								   $prd['is_custom']      = $items->is_custom;
								  
								$item_count = 0;
								$child_data = json_decode($items->assortments,true);
								if(count($child_data) >0){
                                    foreach($child_data as $cust_key=>$cust_val)
                                    {
                                    
                                    $child_id = $cust_val['child_product_id']; $child_qty = $cust_val['child_product_qty'];  $child_assortment_qty =$cust_val['child_assortment_qty'];
                                    $child_data = CrmChildProductsMaster::where('ChildProductID',$child_id)->first();
                                    if(isset($child_data->SizeInfo)){ $size_name = $child_data->SizeInfo->SizeName;}else{ $size_name = ""; }
                                    $child_detail['size'] = $size_name;
                                    $child_detail['quantity'] = $child_qty;
                                    $item_count+= $child_qty;
                                    $child_datails[]=$child_detail;
                                    
                                    }
                                   if($child_assortment_qty>0){ $item_count = $item_count*$child_assortment_qty; }
                                    $prd['child_datails']     =   $child_datails;
								}else{
								    $prd['child_datails']     =   [];
								    $child_assortment_qty =0;
								}
								
								$prd['child_assortment_qty'] = $child_assortment_qty;
								$crm_product_id = $prd_assoc->crmProduct->id;
								$prd_assort = CrmPartAssortmentMaster::where('productID',$crm_product_id)->where('AssortmentID',$items->assortments_id)->where('is_deleted',0)->first();
								
								if($prd_assort)
                                {
                                    $prd['assortment'] = $prd_assort->Assortments->Assortment;
                                }else{
                                    $prd['assortment'] = "Custom";
                                }
								// $options=SalesOrderItemOption::where('sales_item_id',$items->id)->get();
								
								// if(count($options)>0){
								// 	//dd(count($options));
								// 	if(isset($options[0]) && isset($options[1])){
								// 		$prd['variant_name']=$options[0]->attr_value." ".$options[0]->attr_name. " - ".$options[1]->attr_value." ".$options[1]->attr_name;
								// 		}else{
								// 		//dd($options)	;
								// 		$prd['variant_name']=$options[0]->attr_value." ".$options[0]->attr_name;    
								// 		}
								// }else{
								//   $prd['variant_name']="";  
								// }
							}
                            
                            $tot_price = $items->row_total;
                        
                           
                        
                            $tax =  $items->tax;
                           // $mjsfee=$items->mjs_fee;
                            $pgfee=$items->pg_fee;
                     
                            //$prd['mjs_fee']= $mjsfee;
                           // $prd['pg_fee']= $pgfee;;
                            
                            $tot_price = round($tot_price);
                            $tax_price = $tax;
                            $prd['product_tax']=round($tax_price );
                            $prd['actual_price']=round($tot_price );
                            $prd['sale_price']        =   round($tot_price);
                            $prd['tot_sale_price']    =   round($tot_price);
                            $prd['currency']          =   $currency;
                            $prd['quantity']          =   $items->qty;
                            
                            $return_order = SalesOrderReturn::where('sales_id',$sales->id)->where('is_deleted',0)->first();
                            // $data['return_detail']       =       array();
                            if($return_order)
                            {
								if($return_order->type=="order"){
								$rtrn['status']      =   $return_order->status;
                                $rtrn['id']          =   $return_order->id;
                                $rtrn['payment_status']     =   $return_order->payment_status;
                                $prd['return_detail']       =       $rtrn;
								}else{
								$return_order_item = SalesOrderReturn::where('sales_item_id',$items->id)->where('is_deleted',0)->first();
								if($return_order_item){
                                $rtrn['status']      =   $return_order_item->status;
                                $rtrn['id']          =   $return_order_item->id;
                                $rtrn['payment_status']     =   $return_order_item->payment_status;
                                $prd['return_detail']       =       $rtrn;
								}
								else
								{
									$prd['return_detail']       =       [];
								}
								}
                            }
                            else
                            {
                                $prd['return_detail']       =       [];
                            }
                            $data['products'][]       =       $prd;
                            $data['tax']               +=   round($tax);
                         }
                           $data['total']         =   number_format($sales->total); 

							$data['taxes']               =   number_format($sales->tax);
                         
                           $data['shiping_charge']               =  number_format($sales->shiping_charge,2); 
                           $data['payment_gateway_charge']               =  number_format($sales->payment_gateway_charge,2); 
                           
						   $data['coupon_discount']         =   number_format($sales->coupon_discount,2);

						   $data['discounts']               =  number_format(($sales->discount -  $data['coupon_discount']),2 ); 
							
                           $data['grand_total']       =   number_format($sales->g_total,2);
                        
                         $data['item_count']= $item_count;//  count($data['products']);
                         //$data['auction_status']=   $au_status;
                         //$data['bid_charge']    =   $charge;
                         $adddr   =   SalesOrderAddress::where('sales_id',$request->sale_id)->first();
                         $baddr['address_type'] = $adddr->type->usr_addr_typ_name;
                         $baddr['name']         = $adddr->name; 
                         $baddr['phone']        = $adddr->phone;
                         $baddr['country_code']        = $adddr->country_code;
                         $baddr['email']        = $adddr->email; 
                         $baddr['address1']     = $adddr->address1; 
                         $baddr['address2']     = $adddr->address2; 
                         $baddr['zip_code']     = $adddr->zip_code;      
                         $baddr['country']      = $adddr->bcountry->country_name; 
                         $baddr['state']        = $adddr->bstate->state_name; 
                        if($adddr->bcity){ $baddr['city']         = $adddr->bcity->city_name; }else { $baddr['city']         = "";}
                         $baddr['latitude']     = $adddr->latitude; 
                         $baddr['longitude']    = $adddr->longitude;
                         $data['billing_address'] =  $baddr;

                         $saddr['address_type'] = $adddr->stype->usr_addr_typ_name;
                         $saddr['name']         = $adddr->s_name; 
                         $saddr['phone']        = $adddr->s_phone; 
                         $saddr['s_country_code']        = $adddr->s_country_code; 
                         $saddr['email']        = $adddr->s_email; 
                         $saddr['address1']     = $adddr->s_address1; 
                         $saddr['address2']     = $adddr->s_address2; 
                         $saddr['zip_code']     = $adddr->s_zip_code;      
                         $saddr['country']      = $adddr->scountry->country_name; 
                         $saddr['state']        = $adddr->sstate->state_name; 
                       if($adddr->scity){  $saddr['city']         = $adddr->scity->city_name; }else{ $saddr['city'] = ''; }
                         $saddr['latitude']     = $adddr->s_latitude; 
                         $saddr['longitude']    = $adddr->s_longitude;
                         $data['shipping_address'] =  $saddr;

                         return array('httpcode'=>'200','status'=>'success','message'=>'Order Detail','data'=>['order_detail'=>$data]);
                    }
                    else
                    {
                       return array('httpcode'=>'400','status'=>'error','message'=>'Not Found','data'=>['message' =>'Sale not found!']);
                    }
                    
                }
        }
        else{ return invalidToken(); }
    }
    public function invoice(Request $request)
    {
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
            $val        =   [];
            $formData   =   $request->all(); 
            $rules      =   array();
            $rules['sale_id']    = 'required|numeric';
            $rules['lang_id']    = 'required|numeric';
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 
                    $lang =  $request->lang_id;
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
                    $sales =  SalesOrder::where('cust_id',$user_id)->where('id',$formData['sale_id'])->first(); 
                    if($sales)
                    {
                        $pay      =   SalesOrderPayment::where('sales_id',$request->sale_id)->first();
                        $seller   =   SellerInfo::where('seller_id',$sales->seller_id)->first();
                        $ship     =   SalesOrderShippingStatus::where('sales_id',$request->sale_id)->first();
                        $histories =  AuctionHist::where('user_id',$user_id)->where('sale_id',$request->sale_id)->where('is_deleted',0)->where('is_active',1)->orderBy('created_at', 'desc');
                        $auctionwin = Auction::where('bid_allocated_to',$user_id)->where('sale_id',$request->sale_id)->where('status','closed')->where('is_deleted',0)->where('is_active',1);
                        if($histories->count() > 0)
                        {
                            if($auctionwin->count() > 0)
                            {
                                $au_status=   'True';
                                $charge    =   $sales->bid_charge;
                            }
                            else
                            {
                                $au_status=   'False';
                                $charge    =   0;
                            }
                        }
                        else
                        {
                            $au_status =   'False';
                            $charge    =   0;
                        }
                        $data['order_id']          =   $sales->order_id;
                        $data['order_date']        =   date('d-m-Y',strtotime($sales->created_at));
                        $data['order_time']        =   date('g:i a',strtotime($sales->created_at));
                        $data['pay_method']        =   $pay->payment_type;
                        $data['sub_total']         =   round($sales->total);
                        $data['shipping']          =   round($sales->shiping_charge * $crv);
                        $tot                       =   $data['sub_total'] + $data['shipping'];
                        $data['total']             =   $tot;
                        $data['promotion']         =   round($sales->discount * $crv);
                        $data['tax']               =   round($sales->tax );
                        $data['grand_total']       =   round($sales->g_total);
                        $data['delivered_date']    =   '';
                        $data['return_date']       =   '';
                        if($ship)
                        {
                             $data['delivery_status']   =   $ship->status;
                             if($ship->status == 'delivered')
                             {
                                $data['delivered_date']    =   date('d-m-Y',strtotime($ship->updated_at));;
                                $data['return_date']   =    date('d-m-Y',strtotime($ship->updated_at. ' + 2 days'));
                             }
                        }
                        else
                        {
                            $data['delivery_status']   =   'Pending';
                        }
                        
                        $data['sold_by']            =   $seller->fname;
                        $all_items      =   SaleorderItems::where('sales_id',$sales->id)->get(); 
                        foreach($all_items  as $items)
                        {
                            $prdId      =   $items->prd_id;
                            $products   =   Product::where('id',$prdId)->first();
                            
                            $prd['sale_items_id']=   $items->id;
                            $prd['product_id']   =   $prdId;
                            $prd['product_name'] =   $this->get_content($products->name_cnt_id,$lang);
                            $prd['short_desc']   =   $this->get_content($products->short_desc_cnt_id,$lang);
                            $prd['desc']         =   $this->get_content($products->desc_cnt_id,$lang);
                            $prd['sale_price']   =   round($items->price * $crv);
                            $prd['currency']     =   $currency;
                            $prd['quantity']     =   $items->qty;
                            $data['products'][]  =       $prd;
                         }
                         $data['auction_status']=   $au_status;
                         $data['bid_charge']    =   round($charge * $crv);
                         $adddr   =   SalesOrderAddress::where('sales_id',$request->sale_id)->first();
                         $baddr['address_type'] = $adddr->type->usr_addr_typ_name;
                         $baddr['name']         = $adddr->name; 
                         $baddr['phone']        = $adddr->phone;
                         $baddr['country_code']        = $adddr->country_code;
                         $baddr['email']        = $adddr->email; 
                         $baddr['address1']     = $adddr->address1; 
                         $baddr['address2']     = $adddr->address2; 
                         $baddr['zip_code']     = $adddr->zip_code;      
                         $baddr['country']      = $adddr->bcountry->country_name; 
                         $baddr['state']        = $adddr->bstate->state_name; 
                        if($adddr->bcity){ $baddr['city']         = $adddr->bcity->city_name; }else{ $baddr['city'] =''; }
                         $baddr['latitude']     = $adddr->latitude; 
                         $baddr['longitude']    = $adddr->longitude;
                         $data['billing_address'] =  $baddr;

                         $saddr['address_type'] = $adddr->stype->usr_addr_typ_name;
                         $saddr['name']         = $adddr->s_name; 
                         $saddr['phone']        = $adddr->s_phone; 
                          $saddr['s_country_code']        = $adddr->s_country_code; 
                         $saddr['email']        = $adddr->s_email; 
                         $saddr['address1']     = $adddr->s_address1; 
                         $saddr['address2']     = $adddr->s_address2; 
                         $saddr['zip_code']     = $adddr->s_zip_code;      
                         $saddr['country']      = $adddr->scountry->country_name; 
                         $saddr['state']        = $adddr->sstate->state_name; 
                        if($adddr->scity){ $saddr['city']         = $adddr->scity->city_name; }else { $saddr['city'] =''; }
                         $saddr['latitude']     = $adddr->s_latitude; 
                         $saddr['longitude']    = $adddr->s_longitude;
                         $data['shipping_address'] =  $saddr;

                         return array('httpcode'=>'200','status'=>'success','message'=>'invoice','data'=>['order_detail'=>$data]);
                    }
                    else
                    {
                       return array('httpcode'=>'400','status'=>'error','message'=>'Not Found','data'=>['message' =>'Sale not found!']);
                    }
                    
                }
        }
        else{ return invalidToken(); }
    }
    public function cancel_request(Request $request)
    {
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
            $formData   =   $request->all(); 
            $rules      =   array();
            $rules['sale_id']     = 'required|numeric';
            $rules['reason']      = 'required|string';
            $rules['notes']       = 'required|string';
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 
                    $sales =  SalesOrder::where('cust_id',$user_id)->where('id',$formData['sale_id'])->first(); 
                    if($sales)
                    {
						if($sales->order_status=='pending'){
                        SalesOrder::where('id',$formData['sale_id'])->update(['cancel_process'=>1,'order_status' => 'cancel_initiated']);
                        $ordercancel = SalesOrderCancel::create(['sales_id' => $sales->id,
                        'seller_id' => $sales->seller_id,
                        'created_by' => $user_id,
                        'customer_id' => $sales->cust_id,
                        'role_id' => 5,
                        'status' => 'pending']);
                        SalesOrderCancelNote::create(['cancel_id' => $ordercancel->id,
                        'created_by' => $user_id,
                        'role_id' => 5,
                        'title' => $formData['reason'],
                        'note' => $formData['notes']]);
                        return array('httpcode'=>'200','status'=>'success','message'=>'Request sent','data'=>['message' =>'Your cancel request sent successfully!']);
						}else{
                        return array('httpcode'=>'400','status'=>'error','message'=>'Not Sent','data'=>['message' =>'you cannot send Cancel request for this order']);
						}
					}
                    else
                    {
                        return array('httpcode'=>'400','status'=>'error','message'=>'Not Found','data'=>['message' =>'Order not found!']);
                    }
                }
        }else{ return invalidToken(); }
    }

    public function seller_req_list(Request $request)
    {
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
            $val        =   [];
            $formData   =   $request->all(); 
            $rules      =   array();
            $rules['lang_id']    = 'required|numeric';
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 
                    $lang =  $request->lang_id;
                    $sales =  SalesOrder::where('cust_id',$user_id)->where('cancel_process',1)->get(); 
                    if($sales->count() > 0)
                    {
                        foreach($sales  as $row)
                        {
                            $cancelorders  = SalesOrderCancel::where('sales_id',$row->id)->where('role_id',3)->orderBy('id', 'DESC')->first();
                            if($cancelorders)
                            {
                                $all_items      =   SaleorderItems::where('sales_id',$row->id)->get(); 
                                $calcelnotes    =   SalesOrderCancelNote::where('cancel_id',$cancelorders->id)->first();
                                foreach($all_items  as $items)
                                {
                                    $prdId      =   $items->prd_id;
                                    $products   =   Product::where('id',$prdId)->first();
                                    $data['cancel_id']         =   $cancelorders->id;
                                    $data['order_id']          =   $row->order_id;
                                    $data['seller_id']         =   $row->seller_id;
                                    $data['sale_items_id']     =   $items->id;
                                    $data['product_id']        =   $prdId;
                                    if($products->product_type==1){
                                    $data['product_name']      =   $this->get_content($products->name_cnt_id,$lang);
                                    $data['product_image']     =   $this->get_product_image($products->id);
                                    }
                                    else
                                    {
                                    $associate= AssociatProduct::where('ass_prd_id',$products->id)->first();
                                    $prd_assoc = Product::where('id',$associate->prd_id)->first();
                                    $data['product_name']      =   $this->get_content($prd_assoc->name_cnt_id,$lang);
                                    $data['product_image']     =   $this->get_product_image($prd_assoc->id);    
                                    }
                                    $data['price']             =   $items->row_total;
                                    $data['currency']          =   getCurrency()->name;
                                    
                                    $data['quantity']          =   $items->qty;
                                    $data['order_date']        =   date('d-m-Y',strtotime($row->created_at));
                                    $data['order_time']        =   date('g:i a',strtotime($row->created_at));
                                    $data['delivery_status']   =   $row->shipping_status;
                                    $data['cancel_notes']       =  $calcelnotes->note;
                                    $val[] = $data;
                                }
                            }
                          
                        }
                    }
                    else
                    {
                       $val        =   []; 
                    }
                    return array('httpcode'=>'200','status'=>'success','message'=>'Seller request to customer','data'=>['request_list'=>$val]);
                }
        }
        else{ return invalidToken(); }
    }

    public function seller_past_list(Request $request)
    {
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
            $val        =   [];
            $formData   =   $request->all(); 
            $rules      =   array();
            $rules['lang_id']    = 'required|numeric';
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 
                    $lang =  $request->lang_id;
                    $sales =  SalesOrder::where('cust_id',$user_id)->whereIn('cancel_process',[2,3])->get(); 
                    if($sales->count() > 0)
                    {
                        foreach($sales  as $row)
                        {
                            $cancelorders  = SalesOrderCancel::where('sales_id',$row->id)->where('role_id',3)->orderBy('id', 'DESC')->first();
                            if($cancelorders)
                            {
                                $all_items      =   SaleorderItems::where('sales_id',$row->id)->get(); 
                                $calcelnotes    =   SalesOrderCancelNote::where('cancel_id',$cancelorders->id)->first();
                                foreach($all_items  as $items)
                                {
                                    $prdId      =   $items->prd_id;
                                    $products   =   Product::where('id',$prdId)->first();
                                    $data['cancel_id']         =   $cancelorders->id;
                                    $data['order_id']          =   $row->order_id;
                                    $data['sale_items_id']     =   $items->id;
                                    $data['product_id']        =   $prdId;
                                    if($products->product_type==1){
                                    $data['product_name']      =   $this->get_content($products->name_cnt_id,$lang);
                                    $data['product_image']     =   $this->get_product_image($products->id);
                                    }
                                    else
                                    {
                                    $associate= AssociatProduct::where('ass_prd_id',$products->id)->first();  
                                    $prd_assoc = Product::where('id',$associate->prd_id)->first();
                                    $data['product_name']      =   $this->get_content($prd_assoc->name_cnt_id,$lang);
                                    $data['product_image']     =   $this->get_product_image($prd_assoc->id);
                                    }
                                    $data['price']             =   $items->row_total;
                                    $data['currency']          =   getCurrency()->name;
                                    
                                    $data['quantity']          =   $items->qty;
                                    $data['order_date']        =   date('d-m-Y',strtotime($row->created_at));
                                    $data['order_time']        =   date('g:i a',strtotime($row->created_at));
                                    $data['delivery_status']   =   $row->shipping_status;
                                    $data['cancel_notes']      =  $calcelnotes->note;
                                    $data['cancel_response']   =  $calcelnotes->response;
                                    $val[] = $data;
                                }
                            }
                          
                        }
                    }
                    else
                    {
                       $val        =   []; 
                    }
                    return array('httpcode'=>'200','status'=>'success','message'=>'Seller past request to customer','data'=>['past_request_list'=>$val]);
                }
        }
        else{ return invalidToken(); }
    }

    public function cust_req_list(Request $request)
    {
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
            $val        =   [];
            $formData   =   $request->all(); 
            $rules      =   array();
            $rules['lang_id']    = 'required|numeric';
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 
                    $lang =  $request->lang_id;
                    $sales =  SalesOrder::where('cust_id',$user_id)->where('cancel_process',1)->get(); 
                    if($sales->count() > 0)
                    {
                        foreach($sales  as $row)
                        {
                            $cancelorders  = SalesOrderCancel::where('sales_id',$row->id)->where('role_id',5)->orderBy('id', 'DESC')->first();
                            if($cancelorders)
                            {
                                $all_items      =   SaleorderItems::where('sales_id',$row->id)->get(); 
                                $calcelnotes    =   SalesOrderCancelNote::where('cancel_id',$cancelorders->id)->first();
                                foreach($all_items  as $items)
                                {
                                    $prdId      =   $items->prd_id;
                                    $products   =   Product::where('id',$prdId)->first();
                                    $data['cancel_id']         =   $cancelorders->id;
                                    $data['order_id']          =   $row->order_id;
                                    $data['seller_id']         =   $row->seller_id;
                                    $data['sale_items_id']     =   $items->id;
                                    $data['product_id']        =   $prdId;
                                    if($products->product_type==1){
                                    $data['product_name']      =   $this->get_content($products->name_cnt_id,$lang);
                                    $data['product_image']     =   $this->get_product_image($products->id);
                                    }
                                    else
                                    {
                                    $associate= AssociatProduct::where('ass_prd_id',$products->id)->first();    
                                    $prd_assoc = Product::where('id',$associate->prd_id)->first();
                                    $data['product_name']      =   $this->get_content($prd_assoc->name_cnt_id,$lang);
                                    $data['product_image']     =   $this->get_product_image($prd_assoc->id);
                                    }
                                    $data['price']             =   $items->row_total;
                                    $data['currency']          =   getCurrency()->name;
                                    
                                    $data['quantity']          =   $items->qty;
                                    $data['order_date']        =   date('d-m-Y',strtotime($row->created_at));
                                    $data['order_time']        =   date('g:i a',strtotime($row->created_at));
                                    $data['delivery_status']   =   $row->shipping_status;
                                    $data['cancel_notes']       =  $calcelnotes->note;
                                    $val[] = $data;
                                }
                            }
                          
                        }
                    }
                    else
                    {
                       $val        =   []; 
                    }
                    return array('httpcode'=>'200','status'=>'success','message'=>'Customer requests','data'=>['request_list'=>$val]);
                }
        }
        else{ return invalidToken(); }
    }

    public function cust_past_list(Request $request)
    {
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
            $val        =   [];
            $formData   =   $request->all(); 
            $rules      =   array();
            $rules['lang_id']    = 'required|numeric';
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 
                    $lang =  $request->lang_id;
                    $sales =  SalesOrder::where('cust_id',$user_id)->whereIn('cancel_process',[2,3])->get(); 
                    if($sales->count() > 0)
                    {
                        foreach($sales  as $row)
                        {
                            $cancelorders  = SalesOrderCancel::where('sales_id',$row->id)->where('role_id',5)->orderBy('id', 'DESC')->first();
                            if($cancelorders)
                            {
                                $all_items      =   SaleorderItems::where('sales_id',$row->id)->get(); 
                                $calcelnotes    =   SalesOrderCancelNote::where('cancel_id',$cancelorders->id)->first();
                                foreach($all_items  as $items)
                                {
                                    $prdId      =   $items->prd_id;
                                    $products   =   Product::where('id',$prdId)->first();
                                    $data['cancel_id']         =   $cancelorders->id;
                                    $data['order_id']          =   $row->order_id;
                                    $data['sale_items_id']     =   $items->id;
                                    $data['product_id']        =   $prdId;
                                    if($products->product_type==1){
                                    $data['product_name']      =   $this->get_content($products->name_cnt_id,$lang);
                                    $data['product_image']     =   $this->get_product_image($products->id);
                                    }
                                    else
                                    {
                                    $associate= AssociatProduct::where('ass_prd_id',$products->id)->first();   
                                    $prd_assoc = Product::where('id',$associate->prd_id)->first();
                                    $data['product_name']      =   $this->get_content($prd_assoc->name_cnt_id,$lang);
                                    $data['product_image']     =   $this->get_product_image($prd_assoc->id);
                                    }
                                    $data['price']             =   $items->row_total;
                                    $data['currency']          =   getCurrency()->name;
                                    
                                    $data['quantity']          =   $items->qty;
                                    $data['order_date']        =   date('d-m-Y',strtotime($row->created_at));
                                    $data['order_time']        =   date('g:i a',strtotime($row->created_at));
                                    $data['delivery_status']   =   $row->shipping_status;
                                    $data['cancel_notes']       =  $calcelnotes->note;
                                    $data['cancel_response']   =  $calcelnotes->response;
                                    $val[] = $data;
                                }
                            }
                          
                        }
                    }
                    else
                    {
                       $val        =   []; 
                    }
                    return array('httpcode'=>'200','status'=>'success','message'=>'Customer past requests','data'=>['past_request_list'=>$val]);
                }
        }
        else{ return invalidToken(); }
    }

    public function response_request(Request $request)
    {
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
            $formData   =   $request->all(); 
            $rules      =   array();
            $rules['cancel_id']     = 'required|numeric';
            $rules['status']        = 'required|numeric';
            if($request->status == 1)
            {
                // $rules['refund_mode']        = 'required|numeric|in:1,2';
                // if($request->refund_mode == 2)
                // {
                //     $rules['bank_name']        = 'required|string';
                //     $rules['account_number']   = 'required|string';
                //     $rules['branch_name']      = 'required|string';
                //     $rules['ifsc_code']        = 'required|string';
                // }
            }
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 
                    $cancels = SalesOrderCancel::where('id',$formData['cancel_id'])->where('role_id',3)->where('is_deleted',0)->first();
                    if($cancels)
                    {
                        if($formData['status'] == 1)
                        {
                            $status = 'accepted';
                            $ord_status = 'cancelled';
                        }
                        else
                        {
                            $status = 'rejected';
                            $ord_status = 'pending';
                        }
                        $salOrd = SalesOrder::where('id',$cancels->sales_id)->update(['order_status'=>$ord_status,'cancel_process'=>$formData['status']]);
                        $sales = SalesOrder::where('id',$cancels->sales_id)->first();
                        SalesOrderCancel::where('id',$formData['cancel_id'])->update([
                        'status' => $status]);
                        if($formData['status'] == 1)
                        {
                          $refundcharge = SettingOther::where('is_active',1)->where('is_deleted',0)->orderBy('id','DESC')->first();
                          $tot = $sales->g_total;
                          $gtot = $tot - $refundcharge->refund_deduction;
                          SalesOrderRefundPayment::create([
                         'ref_id' => $formData['cancel_id'],'sales_id' => $cancels->sales_id,'source' =>'cancel','refund_mode' => 1,'total' => $tot,'refund_tax' => $refundcharge->refund_deduction,'grand_total' => $gtot,'bank_name' => $formData['bank_name'],'account_number' => $formData['account_number'],'branch_name' => $formData['branch_name'],'ifsc_code' => $formData['ifsc_code']]);
                        }
                        return array('httpcode'=>'200','status'=>'success','message'=>'Response sent','data'=>['message' =>'Your cancel response sent successfully!']);
                    }
                    else
                    {
                        return array('httpcode'=>'400','status'=>'error','message'=>'Not Found','data'=>['message' =>'Cancel request not found!']);
                    }
                }
        }else{ return invalidToken(); }
    }
    
     public function get_profile(Request $request)
    {
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
            $val        =   [];
            $formData   =   $request->all(); 
            $rules      =   array();
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 
                    $customer =  CustomerMaster::where('id',$user_id)->first(); 
                    $invite_save= SettingOther::where('is_active',1)->where('is_deleted',0)->first();
                    if($customer)
                    {
                        $cust_info = CustomerInfo::where('user_id',$user_id)->first(); 
                        $cust_tele = CustomerTelecom::where('user_id',$user_id)->first();
                        if(!empty($cust_info->country_id)){ $country = $cust_info->country->country_name;} else { $country = '';}
                        if(!empty($cust_info->state_id)){ $state = $cust_info->state->state_name;} else { $state = '';}
                        if(!empty($cust_info->city_id)){ $city = $cust_info->city->city_name;} else { $city = '';}
                        if(!empty($cust_info->profile_image)){ $avatar = config('app.storage_url').'/app/public/customer_profile/'.$cust_info->profile_image;} else { $avatar = config('app.storage_url').'/app/public/no-avatar.png';}
                        if(!empty($cust_info->address)){ $address = $cust_info->address;} else { $address = '';}
                        $cust_country_code = CustomerTelecom::where('user_id',$user_id)->where('usr_telecom_typ_id',2)->first();
                        $data['user_id']        =   $user_id;
                        $data['username']       =   $customer->username;
                        $data['first_name']     =   $user['first_name'];
                        $data['last_name']      =   $user['last_name'];
                        $data['country_code']   =   $cust_country_code->country_code;
                        $data['phone']          =   $user['phone'];
                        $data['email']          =   $user['email'];
                        $data['address1']       =   $address;
                     //   $data['pincode']        =   $cust_info->pincode;
                        $data['country']        =   $country;
                        $data['state']          =   $state;
                        $data['city']           =   $city;
                        $data['country_id']     =   $cust_info->country_id;
                        $data['state_id']       =   $cust_info->state_id;
                        $data['city_id']        =   $cust_info->city_id;
                        $data['birthday']       =   $cust_info->birthday;
                        $data['gender']         =   $cust_info->gender;
                        $data['profile_image']  =   $avatar;
                        $data['joined_date']    =   date('d-m-Y', strtotime($customer->created_at));
                        $data['duration']       =   Carbon::parse($customer->created_at)->diffForHumans();
                        
                        $existing = InviteSaveLog::where('user_id',$user_id)->sum('count'); 
                        $i_count = 0;
                        if($existing){
                        $i_count =$existing; 
                        }
                        $data['invite_save']       = $invite_save->invite_save;
                        $data['invite_count']       = $i_count;
						$data['wallet']       = $this->wallet_balance($user_id);
						$data['credits']       = $this->credit_balance($user_id);
						$data['customer_points']       = $this->customer_points($user_id);
						$data['is_kyc_completed']       = $this->kyc_status($user_id);
                        $val[] = $data;
                    }
                    else
                    {
                       $val        =   []; 
                    }
                    return array('httpcode'=>'200','status'=>'success','message'=>'profile','data'=>['profile'=>$val]);
                }
        }else{ return invalidToken(); }
    }
	public function wallet_balance($user_id){
		$wallet=DB::table("usr_cust_wallet")->select(DB::raw("SUM(credit)-SUM(debit) as wallet"))->where('user_id',$user_id)->where('is_deleted',0)->first();
		if($wallet->wallet > 0)
		{
			return $wallet->wallet;
		}
		else
		{
			return 0;
		}
	}

	public function kyc_status($user_id){
		$info = CustomerInfo::where('user_id',$user_id)->where('is_deleted',0)->first();
		if($info)
		{
			
			if($info->pan_number !="" && $info->pan_file !="" && $info->gst_number !="" && $info->gst_file !="" )
			{
				return 1;
			}else{
				return 0;
			}
		}
		else
		{
			return 0;
		}
	}
	
	public function credit_balance($user_id){
		$creditData = CustomerCredits::where('is_active',1)->where('user_id',$user_id)->orderBY('id','Desc')->get();
		$credit=0;
		$debit=0;
		$balance=0;

		if($creditData)
        {			
			foreach($creditData as $row)
            {				
				$credit+= $row->credit;
				$debit+= $row->debit;
			}

		    $lastlog=CustomerCreditLogs::where('user_id',$user_id)->orderBy("id","DESC")->first();
            
		    if($lastlog)
            {
		        $data['total_limit']=$lastlog->credit_limit;
		        $balance=($data['total_limit']-$debit)+$credit;
		    }
		}
		
		return $balance;
	}

    public function customer_points($user_id){
		$data['log'] = CustomerPoints::where('user_id',$user_id)->where('is_deleted',0)->get();
		$credit=0;
		$debit=0;
		$balance=0;
		if($data['log']){
			foreach($data['log'] as $log){
				$credit+= $log->credit;
				$debit+= $log->debit;
			}
		}
		$balance=$credit-$debit;
		
		return $balance;
	}
	
	public function loyalty_points($user_id){
		$data['log']=LogLoyaltyPoints::where('user_id',$user_id)->where('is_deleted',0)->get();
		$credit=0;
		$debit=0;
		$balance=0;
		if($data['log']){
			foreach($data['log'] as $log){
				$credit+= $log->credit;
				$debit+= $log->debit;
			}
		}
		$balance=$credit-$debit;
		
		return $balance;
	}
        public function edit_profile(Request $request)
    {
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
            $formData   =   $request->all(); 
            $rules      =   array();
            $rules['username']   = 'required|string|unique:usr_mst,username,'.$user_id;
            $rules['first_name'] = 'required|string';
            $rules['last_name']  = 'nullable|string';
            $rules['email']      = 'required_without:phone|nullable|email|max:255|unique:usr_telecom,usr_telecom_value,'.$user_id.',user_id';
            $rules['phone']      = 'required_without:email|nullable|numeric|digits_between:7,12|unique:usr_telecom,usr_telecom_value,'.$user_id.',user_id';
            $rules['state']      = 'nullable|numeric';
            $rules['country']    = 'nullable|numeric';
            $rules['city']       = 'nullable|numeric';
            $rules['country_code']='required';
            $rules['gender']     =  'nullable|string';
            $rules['birthday']   =  'nullable|date_format:Y-m-d';
            $rules['device_id']  = 'required|string';
            $rules['os_type']    = 'required|string';
            if (array_key_exists("password",$formData))
            {
                if($formData['password']!='')
                {
                    $rules['password']='min:8|required_with:password_confirmation|confirmed';
                }
            }
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 
                    $usr_visit = UserVisit::create([
                    'org_id' =>1,
                    'device_id'=>$request->device_id,
                    'is_login'=>1,
                    'os'=>$request->os_type,
                    'url'=>'Edit profile',
                    'visited_on'=>date("Y-m-d H:i:s"),
                    'created_at'=>date("Y-m-d H:i:s"),
                    'updated_at'=>date("Y-m-d H:i:s")]);
                    
                    CustomerMaster::where('id',$user_id)->where('is_deleted',0)->where('is_active',1)->update(['username' => $formData['username']]);
                    if(isset($formData['birthday'])){
                        $birthday=formData['birthday'];
                    }else{
                       $birthday=""; 
                    }
                    if(isset($formData['gender'])){
                        $gender=formData['gender'];
                    }else{
                       $gender=""; 
                    }
                    if(isset($formData['last_name'])){
                        $last_name=formData['last_name'];
                    }else{
                       $last_name=""; 
                    }

                    if($request->hasFile('profile_img'))
                    {
                    $file=$request->file('profile_img');
                    $extention=$file->getClientOriginalExtension();
                    $filename=time().'.'.$extention;
                    $file->move(('uploads/storage/app/public/customer_profile/'),$filename);
                    $info = CustomerInfo::where('user_id',$user_id)->where('is_deleted',0)->where('is_active',1)->update([
                    'first_name' => $formData['first_name'],
                    'last_name' =>$last_name,
                    'country_id' => $formData['country'],
                    'state_id' =>$formData['state'],
                    'city_id'=>$formData['city'],
                    'birthday'      =>   $birthday,
                    'gender'       =>   $gender,
                    'profile_image'=>$filename,
                    ]);
                    }
                    else
                    {
                    $info = CustomerInfo::where('user_id',$user_id)->where('is_deleted',0)->where('is_active',1)->update([
                    'first_name' => $formData['first_name'],
                    'last_name' =>$last_name,
                    'country_id' => $formData['country'],
                    'state_id' =>$formData['state'],
                    'city_id'=>$formData['city'],
                    'birthday'      =>   $birthday,
                    'gender'       =>   $gender,
                    ]);
                    }

             
                    if (array_key_exists("phone",$formData))
                    {
                        if($formData['phone']!='')
                        {
                            $exist = CustomerTelecom::where('user_id',$user_id)->where('usr_telecom_typ_id',2)->where('is_deleted',0)->where('is_active',1)->first();
                            if($exist)
                            {
                                CustomerTelecom::where('user_id',$user_id)->where('usr_telecom_typ_id',2)->where('is_deleted',0)->where('is_active',1)->update(['usr_telecom_value' => $formData['phone'],'country_code'=>$formData['country_code']]);
                            }
                            else
                            {
                                $telecom_ph = CustomerTelecom::create(['org_id' => 1,
                               'user_id' => $user_id,
                               'usr_telecom_typ_id'=>2,
                               'usr_telecom_value'=>$formData['phone'],
                               'country_code'=>$formData['country_code'],
                               'is_active'=>1,
                               'is_deleted'=>0,
                               'created_at'=>date("Y-m-d H:i:s"),
                               'updated_at'=>date("Y-m-d H:i:s")]);
                               $ph_tele=$telecom_ph->id;

                               CustomerMaster::where('id',$user_id)->update([
                                   'phone'=>$ph_tele
                               ]);
                            }
                        }
                    }
                    if (array_key_exists("email",$formData))
                    {
                        if($formData['email']!='')
                        {
                            $exist = CustomerTelecom::where('user_id',$user_id)->where('usr_telecom_typ_id',1)->where('is_deleted',0)->where('is_active',1)->first();
                            if($exist)
                            {
                                CustomerTelecom::where('user_id',$user_id)->where('usr_telecom_typ_id',1)->where('is_deleted',0)->where('is_active',1)->update(['usr_telecom_value' => $formData['email']]);
                            }
                            else
                            {
                                $telecom_ph = CustomerTelecom::create(['org_id' => 1,
                               'user_id' => $user_id,
                               'usr_telecom_typ_id'=>1,
                               'usr_telecom_value'=>$formData['email'],
                               'is_active'=>1,
                               'is_deleted'=>0,
                               'created_at'=>date("Y-m-d H:i:s"),
                               'updated_at'=>date("Y-m-d H:i:s")]);
                               $ph_tele=$telecom_ph->id;

                               CustomerMaster::where('id',$user_id)->update([
                                   'email'=>$ph_tele
                               ]);
                            }
                        }
                    }
                    // CustomerAddress::where('user_id',$user_id)->where('is_deleted',0)->where('is_active',1)->where('is_default',1)->update(['address_1' => $formData['address'],
                    //        'country_id' => $formData['country'],
                    //        'state_id' =>$formData['state'],
                    //        'city_id'=>$formData['city'],
                    //        ]);
                    if (array_key_exists("password",$formData))
                    {
                        if($formData['password']!='')
                        {
                            $pass['password_hash']= Hash::make(trim($formData['password']));
                           CustomerSecurity::where('user_id',$user_id)->where('is_deleted',0)->where('is_active',1)->update($pass); 
                        }
                    }
                    
                    //CRM UPDATE
            $country=$state=$city='';        
            if(isset($formData['country'])) 
            {
                $country = Country::where('id',$formData['country'])->first()->country_name;
            }
            if(isset($formData['state'])) 
            {
                $state = State::where('id',$formData['state'])->first()->state_name;
            }
            if(isset($formData['city'])) 
            {
                $city = City::where('id',$formData['city'])->first()->city_name;
            }
            
            $crmMaster = CustomerMaster::where('id',$user_id)->first()->crm_unique_id;
            if($crmMaster>0)
            {
                $crmMasterID=$crmMaster;
            }
            else
            {
                $crmMasterID=0;
            }
            $headers[] = 'Content-Type: application/json';
           $datapass = json_encode(array(
            'unique_id'=>$user_id,
            'Customer_Id'=>$crmMasterID,
            'CustomerName' => $formData['first_name']." ".$last_name,
            'EmailID' => $formData['email'],
            'MobileNo'=> $formData['phone'],
            'CustomerStatus'=>1,
            'GSTNomber'=>'',
            'CustomerPOCName'=>$formData['first_name'],
            'DivisionId'=>49,
            'Street'=>'NULL',
            'City'=>$city,
            'Country'=>$country,
            'State'=>$state,
            'Customer_Type_Id'=>'',
            'CustomerCode'=>'',
            'IsNDPApplicable'=>'',
            'AuthorityApproval'=>'',
            'ActiveFlag'=>'',
            'BranchId'=>'',
            'UserId'=>0,
            'OrganisationId'=>50,
            'IndustryID'=>'',
            'SourceID'=>'',
            'HowToJoin'=>'',
            'HowToServeUrself'=>'',
            'Typology'=>'',
            'HowIsStoreFront'=>'',
            'StoreInterior'=>'',
            'Ambience'=>'',
            'MainNeeds'=>'',
            'Competitors'=>'',
            'BusinessType'=>'',
            'NoOfSeats'=>'',
            'TurnOver'=>'',
            'StoreFile'=>'',
            'CRFile'=>'',
            'VATFile'=>'',
            'MenuFile'=>'',
            'CRNo'=>'',
            'VATNo'=>'',
            'MobileNo1'=>'',
            'MobileNo2'=>'',
            'LandLine'=>'',
            'Need'=>'',
            'Quantity'=>''
        ));     
           $url_cust_reg = config('crm.customer_api');
           $handle = curl_init($url_cust_reg);
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $datapass);
            curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);    
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($handle);
            curl_close($handle);
            $return_response = json_decode($response);
            
            // return $response;die;
            // CustomerMaster::where('id',$user_id)->update(['crm_unique_id'=>$return_response->data->Customer_Id,'customer_code'=>$return_response->data->CustomerCode]);
            // //**end CRM
                    
                    return array('httpcode'=>'200','status'=>'success','message'=>'Profile updated','data'=>['message' =>'Profile updated successfully!']);
                }
        }else{ return invalidToken(); }
    }
    
    public function userAddress(Request $request)
    {
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
            $val       =   [];
            $formData   =   $request->all(); 
            $rules      =   array();
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 
                    $list =  CustomerAddress::where('user_id',$user_id)->where('is_deleted',0)->where('is_active',1)->get();
                    foreach($list  as $row)
                    {
                        if(!empty($row->country_id)){ $country = $row->country->country_name;} else { $country = '';}
                        if(!empty($row->state_id)){ $state = $row->state->state_name;} else { $state = '';}
                        if(!empty($row->city_id)){ $city = $row->city->city_name;} else { $city = '';}

                         $data['id']            =   $row->id;
                         $data['name']          =   $row->name;
                         $data['address_type']  =   $row->type->usr_addr_typ_name;
                         $data['address_type_id']=   $row->type->id;
                         $data['country_code']         =   $row->country_code;
                         $data['phone']         =   $row->phone;
                         $data['country']       =   $country;
                         $data['state']         =   $state;
                         $data['city']          =   $city;
                         $data['country_id']    =   $row->country_id;
                         $data['state_id']      =   $row->state_id;
                         $data['city_id']       =   $row->city_id;
                         $data['address1']      =   $row->address_1;
                         $data['address2']      =   $row->address_2;
                         $data['pincode']       =   $row->pincode;
                         $data['street']        =   $row->street;
                         $data['house']         =   $row->house;
                         $data['neighborhood']  =   $row->neighborhood;
                         $data['latitude']      =   $row->latitude;
                         $data['longitude']     =   $row->longitude; 
                         $data['is_default']    =   $row->is_default;  
                         
                         $val[] = $data;
                    }
                   
                    return array('httpcode'=>'200','status'=>'success','message'=>'User address list','data'=>['address_list'=>$val]);
                }
        }else{ return invalidToken(); }
    }

    public function addAddress(Request $request)
    {
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
            $formData   =   $request->all(); 
            $rules      =   array();
            $rules['name']          = 'required|string';
            $rules['address_type']  = 'required|numeric';
            $rules['country_code']       = 'required';
            $rules['phone']         = 'required|numeric|digits_between:7,12';
            $rules['country']       = 'required|numeric';
            $rules['state']         = 'required|numeric';
            // $rules['city']          = 'required|numeric';
            $rules['house']         = 'nullable';
            $rules['street']        = 'nullable';
            $rules['neighborhood'] = 'nullable';
            $rules['address1']      = 'required|string';
            $rules['address2']      = 'required|string';
            // $rules['pincode']       = 'required';
            $rules['latitude']      = 'numeric';
            $rules['longitude']     = 'numeric';
            $rules['is_default']    = 'required|numeric';
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 
                    $data['user_id']            = $user_id;
                    $data['name']               = $formData['name'];
                    $data['usr_addr_typ_id']    = $formData['address_type'];
                    $data['country_code']              = $formData['country_code'];
                    $data['phone']              = $formData['phone'];
                    $data['country_id']         = $formData['country'];
                    $data['state_id']           = $formData['state'];
                    $data['city_id']            = $formData['city'];
                    $data['address_1']          = $formData['address1'];
                    $data['address_2']          = $formData['address2'];
                    $data['pincode']            = $formData['pincode'];
                    $data['street']             = $formData['street'];
                    $data['house']              = $formData['house'];
                    $data['neighborhood']       = $formData['neighborhood'];
                    $data['latitude']           = $formData['latitude'];
                    $data['longitude']          = $formData['longitude'];
                    $data['created_by']         = $user_id;
                    $data['updated_by']         = $user_id;
                    $exist = CustomerAddress::where('user_id',$user_id)->where('is_active',1)->where('is_deleted',0)->first();
                    if($exist)
                    {
                        $data['is_default'] = $formData['is_default'];
                        if($formData['is_default'] == 1)
                        {
                            $dafault = CustomerAddress::where('user_id',$user_id)->where('is_active',1)->where('is_default',1)->where('is_deleted',0)->update(['is_default' => 0]);
                        }
                    }
                    else
                    {
                        $data['is_default']         = 1;
                    }
                    CustomerAddress::create($data);
                    return array('httpcode'=>'200','status'=>'success','message'=>'Address added','data'=>['message' =>'Your address added successfully!']);
                }
        }else{ return invalidToken(); }
    }

    public function editAddress(Request $request)
    {
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
            $formData   =   $request->all(); 
            $rules      =   array();
            $rules['address_id']    = 'required|numeric';
            $rules['name']          = 'required|string';
            $rules['address_type']  = 'required|numeric';
            $rules['country_code']       = 'required|numeric';
            $rules['phone']         = 'required|numeric|digits_between:7,12';
            $rules['country']       = 'required|numeric';
            $rules['state']         = 'required|numeric';
            // $rules['city']          = 'required|numeric';
            $rules['address1']      = 'required|string';
            $rules['address2']      = 'required|string';
            // $rules['pincode']       = 'required';
            $rules['house']         = 'nullable';
            $rules['street']        = 'nullable';
            $rules['neighborhood'] = 'nullable';
            $rules['latitude']      = 'numeric';
            $rules['longitude']     = 'numeric';
            $rules['is_default']    = 'required|numeric';
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 
                    $data['name']               = $formData['name'];
                    $data['usr_addr_typ_id']    = $formData['address_type'];
                    $data['country_code']              = $formData['country_code'];
                    $data['phone']              = $formData['phone'];
                    $data['country_id']         = $formData['country'];
                    $data['state_id']           = $formData['state'];
                    $data['city_id']            = $formData['city'];
                    $data['address_1']          = $formData['address1'];
                    $data['address_2']          = $formData['address2'];
                    $data['pincode']            = $formData['pincode'];
                    $data['latitude']           = $formData['latitude'];
                    $data['street']             = $formData['street'];
                    $data['house']              = $formData['house'];
                    $data['neighborhood']       = $formData['neighborhood'];
                    $data['longitude']          = $formData['longitude'];
                    $data['updated_by']         = $user_id;
                    $exist = CustomerAddress::where('user_id',$user_id)->where('is_active',1)->where('is_deleted',0)->where('id',$formData['address_id'])->first();
                    if($exist)
                    {
                        if($formData['is_default'] == 1)
                        {
                            $dafault = CustomerAddress::where('user_id',$user_id)->where('is_active',1)->where('is_default',1)->where('is_deleted',0)->update(['is_default' => 0]);
                            $data['is_default']         = $formData['is_default'];
                        }
                        else if($formData['is_default'] == 0)
                        {
                            $currentData = CustomerAddress::where('user_id',$user_id)->where('is_active',1)->where('is_deleted',0)->where('is_default',1)->where('id',$formData['address_id'])->first();
                            if($currentData)
                            {
                                $existData = CustomerAddress::where('user_id',$user_id)->where('is_active',1)->where('id','!=' ,$formData['address_id'])->where('is_deleted',0)->first();
                                if($existData)
                                {
                                    CustomerAddress::where('user_id',$user_id)->where('id','!=' ,$formData['address_id'])->where('is_active',1)->where('is_deleted',0)->take(1)->update(['is_default'=>1]);
                                    $data['is_default']         = $formData['is_default'];
                                }
                            }
                        }
                        CustomerAddress::where('id',$formData['address_id'])->update($data);
                        return array('httpcode'=>'200','status'=>'success','message'=>'Address updated','data'=>['message' =>'Your address updated successfully!']);
                    }
                    else
                    {
                        return array('httpcode'=>'400','status'=>'error','message'=>'Not found','data'=>['message' =>'Address not found!']);
                    }
                }
        }else{ return invalidToken(); }
    }

    public function deleteAddress(Request $request)
    {
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
            $formData   =   $request->all(); 
            $rules      =   array();
            $rules['address_id']    = 'required|numeric';
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 
                    
                    $exist = CustomerAddress::where('user_id',$user_id)->where('is_active',1)->where('is_deleted',0)->where('id',$formData['address_id'])->first();
                    if($exist)
                    {
                        $default_exist = CustomerAddress::where('user_id',$user_id)->where('is_active',1)->where('is_deleted',0)->where('is_default',1)->where('id',$formData['address_id'])->first();
                        if($default_exist)
                        {
                            $data['is_deleted']         = 1;
                            CustomerAddress::where('id',$formData['address_id'])->update($data);

                            $existData = CustomerAddress::where('user_id',$user_id)->where('is_active',1)->where('is_deleted',0)->first();
                            if($existData)
                            {
                                CustomerAddress::where('user_id',$user_id)->where('is_active',1)->where('is_deleted',0)->take(1)->update(['is_default'=>1]);
                            }
                        }
                        else
                        {
                            $data['is_deleted']         = 1;
                            CustomerAddress::where('id',$formData['address_id'])->update($data);
                        }
                        return array('httpcode'=>'200','status'=>'success','message'=>'Address removed','data'=>['message' =>'Your address removed successfully!']);
                    }
                    else
                    {
                        return array('httpcode'=>'400','status'=>'error','message'=>'Not found','data'=>['message' =>'Address not found!']);
                    }
                }
        }else{ return invalidToken(); }
    }
    
    public function defaultAddress(Request $request)
    {
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
            $formData   =   $request->all(); 
            $data = [];
            $rules      =   array();
            $rules['address_id']    = 'required|numeric';
            $rules['is_default']    = 'required|numeric';
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 
                    
                    $exist = CustomerAddress::where('user_id',$user_id)->where('is_active',1)->where('is_deleted',0)->where('id',$formData['address_id'])->first();
                    if($exist)
                    {
                        if($formData['is_default'] == 1)
                        {
                            $dafault = CustomerAddress::where('user_id',$user_id)->where('is_active',1)->where('is_default',1)->where('is_deleted',0)->update(['is_default' => 0]);
                            $data['is_default']         = $formData['is_default'];
                        }
                        else if($formData['is_default'] == 0)
                        {
                            $currentData = CustomerAddress::where('user_id',$user_id)->where('is_active',1)->where('is_deleted',0)->where('is_default',1)->where('id',$formData['address_id'])->first();
                            if($currentData)
                            {
                                $existData = CustomerAddress::where('user_id',$user_id)->where('is_active',1)->where('id','!=' ,$formData['address_id'])->where('is_deleted',0)->first();
                                if($existData)
                                {
                                    CustomerAddress::where('user_id',$user_id)->where('id','!=' ,$formData['address_id'])->where('is_active',1)->where('is_deleted',0)->take(1)->update(['is_default'=>1]);
                                    $data['is_default']         = $formData['is_default'];
                                }
                            }
                        }
                        CustomerAddress::where('id',$formData['address_id'])->update($data);
                        return array('httpcode'=>'200','status'=>'success','message'=>'Default address updated','data'=>['message' =>'Default address updated successfully!']);
                    }
                    else
                    {
                        return array('httpcode'=>'400','status'=>'error','message'=>'Not found','data'=>['message' =>'Address not found!']);
                    }
                }
        }else{ return invalidToken(); }
    }
    
    function logout(Request $request){
        if($user = validateToken($request->post('access_token'))){ 
            $user_id    =   $user['user_id'];
            CustomerLogin::where('user_id',$user_id)->update(['is_login'=>0,'access_token'=>NULL]);
            return array('httpcode'=>'200','status'=>'success','message'=>'Logged out successfully!','data'=>array('message'=>'You are logged out successfully'));     
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
            $rules['quantity']     = 'required|numeric|min:1';
            $rules['product_id']   = 'required|numeric';
            $rules['reason']       = 'required|string';
            // $rules['message']      = 'required|string';
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 
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
                    $sales =  SalesOrder::where('cust_id',$user_id)->where('id',$formData['sale_id'])->first(); 
                    if($sales)
                    {
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
                                $orderreturn = SalesOrderReturn::create(['sales_id' => $formData['sale_id'],'seller_id' => $sales->seller_id,'type'=>$type,'user_id' => $user_id,'sales_item_id' => $prds->id,'prd_id' => $formData['product_id'],'qty' => $formData['quantity'],'amount' =>  $amt,'reason' =>  $formData['reason'],'desc' =>  $formData['message'],'issue_item'=>$formData['issue_item'],'status'=>"return_initiated"]);
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
                        return array('httpcode'=>'400','status'=>'error','message'=>'Not Found','data'=>['message' =>'Order not found!']);
                    }
                }
        }else{ return invalidToken(); }
    }

    public function usageCoupon(Request $request)
    {
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
            $val       =   [];
            $formData   =   $request->all(); 
            $rules      =   array();
            if (array_key_exists("start_date",$formData))
            {
                if($formData['start_date']!='')
                {
                    $rules['start_date']    = 'required|date_format:Y-m-d|before:end_date';
                    $rules['end_date']      = 'required|date_format:Y-m-d';
                }
            }
            
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 
                    $coupen =  CouponHist::where('user_id',$user_id);

                    if (array_key_exists("start_date",$formData))
                    {
                        if($formData['start_date']!='')
                        {
                            $coupen = $coupen->whereDate('created_at', '>=', $formData['start_date'])
                            ->whereDate('created_at', '<=', $formData['end_date']);
                        }
                    }
                    $list = $coupen->get();
                    foreach($list  as $row)
                    {
                         $orderId                   =   $row->order_id;
                         $orders = SalesOrder::where('id',$orderId)->first();
                         $data['id']                =   $row->id;
                         $data['order_id']          =   $orderId;
                         $data['purchase_date']     =   date('d-m-Y',strtotime($orders->created_at));
                         $data['order_value']       =   $orders->g_total;
                         $data['coupon_code']       =   $row->coupon->ofr_code;
                         $data['coupon_value']      =   $orders->discount;
                         $data['created_at']        =   date('d-m-Y',strtotime($row->created_at));
                         $val[] = $data;
                    }
                   
                    return array('httpcode'=>'200','status'=>'success','message'=>'coupons','data'=>['coupons'=>$val]);
                }
        }else{ return invalidToken(); }
    }

    public function recent_views(Request $request)
    {
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
            $val        =   [];
            $formData   =   $request->all(); 
            $rules      =   array();
            $rules['lang_id']           = 'required|numeric';
            
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 
                    $lang =  $request->lang_id;
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
                    
                    $views =  Prd_Recent_View::where('user_id',$user_id)->orderBy('id', 'desc')->first();   
                    
                    if($views)
                    {
                        $prdIds = $views->prd_id;  
                        $prdId  = explode(",",$prdIds); $prdId  = array_reverse($prdId); 
                        foreach($prdId  as $pId)
                        {
                         $avaliable = Product::where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->where('id',$pId)->first();
                         $products = Product::where('id',$pId)->first(); 

                         $data['product_id']        =   $pId;
                         $data['product_name']      =   $products->name;//$this->get_content($products->name_cnt_id,$lang);;
                         $data['product_rating']    =   $this->get_rates($products->id);
                         //$data['actual_price']      =   $products->prdPrice->price;
                         $data['currency']          =   $currency;
                         //$data['sale_price']        =   $this->get_sale_price($products->id);
                         
                         $checkprd_w =  UsrWishlist::where('user_id',$user_id)->where('prd_id',$pId)->where('is_deleted',0)->first();
                        if($checkprd_w)
                        {
                        $wishlist = 1;
                        }
                        else
                        {
                        $wishlist = 0;
                        }
                        
                        
                        $data['is_wishlisted']= $wishlist;
                        
                         if($products->subCategory->code=='XAU')
                        {
                        $gold=true;
                        $subcategory_code='XAU';
                        $variable = get_variable_price_fn($subcategory_code,$carat=null);
                        if($products->weight>0)
                        {
                            $variable_price = $variable*$products->weight;
                        }
                        else
                        {
                            $variable_price = $variable;
                        }
                        
                        // show min carat price in listing if available

                            $min_carat=[];
                            $extra_fields_e=AssignedFields::where('prd_id',$pId)->whereIn('field_id',function($query) {
                            $query->select('id')->from('prd_fields')->where('variable_rate',1)->where('is_active',1)->where('is_deleted',0);})->where('is_deleted',0)->groupBy('field_id')->get();
                            
                            if(!empty($extra_fields_e))
                            {
                            $i=1;
                            foreach($extra_fields_e as $rows){
                            
                            $min_carat=extra_field_values($pId,$rows->field_id,$products->fixed_price,getTax()->value);
                            $i++;
                            }
                            }
                            else
                            {
                            $min_carat =[];
                            }
                            
                            if(isset($min_carat) && count($min_carat)>0)
                            {
                            $variable_price = min(array_column( $min_carat,'variable_price'));
                            
                            }

                    }
                    else
                    {
                        $gold= false;
                        $variable_price =0;
                    }
                    $data['is_gold']=$gold;   
                    $data['fixed_price']= round($products->fixed_price * $crv);
                    $data['variable_price']=round($variable_price * $crv);
                    $data['weight']=$products->weight;
                    
                    $tot_price = ($variable_price+$products->fixed_price)*$crv;
                    $tax = getTax()->value;
                    $mjs_fee=getCustomerFee()->mjs_fee;
                    $pg_fee=getCustomerFee()->pg_fee;
                    $mjsfee= ($mjs_fee/100)*$tot_price;
                    $pgfee = ($pg_fee/100)*$tot_price;
                    $data['mjs_fee']= $mjsfee;
                    $data['pg_fee']=$pgfee;
                    $tot_price += $data['mjs_fee'] + $data['pg_fee'];
                    // $tot_price = round($tot_price);
                    $tax_price = ($tax/100)*$tot_price;
                    $data['product_tax']=round($tax_price);
                    $data['actual_price']=round($tot_price);
                    
                         $data['product_image']     =   $this->get_product_image($products->id);
                         $data['shop_name']         =   $products->Store($products->seller_id)->store_name;
                         if($avaliable == NULL)
                         {
                            $data['status']         =   'Unavaliable';
                         }
                         else
                         {
                         $data['status']            =   'Avaliable';
                         }
                         $val[] = $data;
                         }
                        
                    }
                    else
                    {
                       $val        =   []; 
                    }
                    return array('httpcode'=>'200','status'=>'success','message'=>'Recent views','data'=>['recent_views'=>$val]);
                }
        }else{ return invalidToken(); }
    }

    public function wallet_amount(Request $request)
    {
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
            $val       =   [];
            $formData   =   $request->all(); 
            $rules      =   array();
            // if (array_key_exists("search",$formData))
            // {
            //     if($formData['search']!='')
            //     {
            //         $rules['search']    = 'string';
                    
            //     }
            // }

            // if (array_key_exists("start_date",$formData))
            // {
            //     if($formData['start_date']!='')
            //     {
            //         $rules['start_date']    = 'required|date_format:Y-m-d|before:end_date';
            //         $rules['end_date']      = 'required|date_format:Y-m-d';
            //     }
            // }
            
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 
                    $wallet =  CustomerWallet_Model::where('user_id',$user_id);
                    $credit = $wallet->sum('credit');
                    $debit = $wallet->sum('debit');
                    $total = $credit - $debit;
                    $total = number_format($total,2);
                    // if (array_key_exists("search",$formData))
                    // {
                    //     if($formData['search']!='')
                    //     {
                    //         $wallet = $wallet->where('source', 'like', '%' . $formData['search'] . '%')
                    //         ->orWhere('credit', 'like', '%' . $formData['search'] . '%')
                    //         ->orWhere('debit', 'like', '%' . $formData['search'] . '%');
                    //     }
                    // }
                    // if (array_key_exists("start_date",$formData))
                    // {
                    //     if($formData['start_date']!='')
                    //     {
                    //         $wallet = $wallet->whereDate('created_at', '>=', $formData['start_date'])
                    //         ->whereDate('created_at', '<=', $formData['end_date']);
                    //     }
                    // }
                    
                    // if($request->filter)
                    // {
                    //     $filter= $request->filter;
                    //     if($filter=='paid')
                    //     {
                    //         $search='order';
                    //         $wallet = $wallet->where('source', 'like', '%' . $search . '%');
                            
                    //     }
                    //     else if($filter=='refund')
                    //     {
                    //         $search='refund';
                    //         $wallet = $wallet->where('source', 'like', '%' . $search . '%');
                    //     }
                    //     else if($filter=='reward')
                    //     {
                            
                    //         $wallet = $wallet->whereIn('source',['Reward','First Buy']);
                    //     }
                    //     else
                    //     {
                    //         //nothing
                    //     }
                    // }
                    $list = $wallet->get();
                    foreach($list  as $row)
                    {
                         if($row->source == 'Order' || $row->source =='First Buy')
                         {
                            $orderId                   =   $row->source_id;
                            $srcID = SalesOrder::where('id',$orderId)->first()->order_id;
                            $srcs  = 'Order #:'.$srcID;
                         }
                         elseif($row->source == 'Reward')
                         {
                            $srcs  = Reward::where('id',$row->source_id)->first()->reward;
                         }
                         elseif($row->source =='Cancel Order' || $row->source =='Return order')
                         {
                            $refund  = SalesOrderRefundPayment::where('id',$row->source_id)->first();
                            $srcs = 'Order #:'.$refund->order->order_id;
                         }
                         else
                         {
                            $srcs  = '';
                         }
                         
                         $data['id']                =   $row->id;
                         $data['source_ids']        =   $srcs;
                         $data['source']            =   $row->source;
                         if((float)$row->credit>0)
                            {$data['credit_value']=true;}
                        else
                        {
                            $data['credit_value']=false;
                        }
                         $data['credit']            =   $row->credit." ".getCurrency()->name;
                         $data['debit']             =   $row->debit." ".getCurrency()->name;
                         $data['created_at']        =   date('d-m-Y | h:i a',strtotime($row->created_at));
                         $val[] = $data;
                    }
                   
                    return array('httpcode'=>'200','status'=>'success','message'=>'wallet','data'=>['tot_credit'=>$credit." ".getCurrency()->name,'tot_debit'=>$debit." ".getCurrency()->name,'total_balance'=>$total." ".getCurrency()->name,'wallet'=>$val]);
                }
        }else{ return invalidToken(); }
    }
    
    public function notifications(Request $request)
    {
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
            $val       =   [];
            $formData   =   $request->all(); 
            
            $list =  UsrNotification::where('notify_to',$user_id)->where('status',1)->orderBy('id','DESC')->get();
            foreach($list  as $row)
            {

                 $data['id']           =    $row->id;
                 $data['title']        =    $row->title;
                 $data['notify_type']  =    $row->notify_type;
                // if($row->notify_type == "seller_chat") { 
                // $chat= Chat::where('id',$row->ref_id)->first(); 
                // $data['ref_id']       =    $chat->sale_id;
                // }else { $data['ref_id']       =    $row->ref_id; }
                 $data['ref_id']       =    $row->ref_id;
                 $data['description']  =    $row->description;
                 
                 $data['ref_link']     =    url($row->ref_link);
                 $data['viewed']       =    $row->viewed;
                 $data['created_at']   =    date('d-m-Y h:i:s',strtotime($row->created_at));
                 $val[] = $data;
            }
                   
            return array('httpcode'=>'200','status'=>'success','message'=>'All notifications','data'=>['notifications'=>$val]);
                
        }else{ return invalidToken(); }
    }

    public function addresstype(Request $request)
    {
        $usr_type=[];
        $type = CustomerAddressType::where('is_active',1)->where('is_deleted',0)->get();
        foreach($type as $row)
        {
            $list['id'] = $row->id;
            $list['name'] = $row->usr_addr_typ_name;
            $list['desc'] = $row->usr_addr_typ_desc;
            $usr_type[]=$list;
        }
        return array('httpcode'=>'200','status'=>'success','message'=>'Address type','data'=>['type' =>$usr_type]);
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
            $rules['refund_mode']          = 'required|numeric';
            if($request->refund_mode == 2)
            {
                $rules['bank_name']        = 'required|string';
                $rules['account_number']   = 'required|string';
                $rules['branch_name']      = 'required|string';
                $rules['ifsc_code']        = 'required|string';
            }
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
                        $refundcharge = SettingOther::first();
                        SalesOrderReturn::where('id',$formData['return_id'])->update(['status'=>'shipment_initiated']);
                        SalesOrderReturnShipment::create(['return_id' => $formData['return_id'],'description' => $formData['shipment_detail'],'document' => 'app/public/shipment_bills/'.$filename]);
                        $tot = $return->amount;
                        $gtot = $tot - $refundcharge->refund_deduction;
                        SalesOrderRefundPayment::create([
                         'ref_id' => $formData['return_id'],'sales_id' => $return->sales_id,'source' =>'return','refund_mode' => $formData['refund_mode'],'total' => $tot,'refund_tax' => $refundcharge->refund_deduction,'grand_total' => $gtot,'bank_name' => $formData['bank_name'],'account_number' => $formData['account_number'],'branch_name' => $formData['branch_name'],'ifsc_code' => $formData['ifsc_code']]);

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
    
    //CONFIG PRODUCT PRICE
        
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
        
        //Product special price
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
        
        //SHOCKING SALE PRICE
        function shock_sale_price($prdid)
        {
            
            $offer['offer_price']=false;
            $current_date=Carbon::now();
            
            $prod_data= Product::where('id',$prdid)->first();
            $shock = PrdShock_Sale::join('prd_shock_sale_products','prd_shock_sale.id','=','prd_shock_sale_products.shock_sale_id')
            ->where('prd_shock_sale.is_active',1)->where('prd_shock_sale.is_deleted',0)->whereDate('prd_shock_sale.start_time','<=',$current_date)->whereDate('prd_shock_sale.end_time','>=',$current_date)
            ->where('prd_shock_sale_products.is_active',1)->where('prd_shock_sale_products.is_deleted',0)->whereRaw("find_in_set($prod_data->id,prd_shock_sale_products.prd_id)")
            ->select('prd_shock_sale.*','prd_shock_sale_products.seller_id','prd_shock_sale_products.prd_id as shock_prd_id')->first();
           
            // else if($deals)
            // {
            //     $offer['offer_name']= 'Daily Deals';   
            //     $offer['offer_id']='';
            //     $offer['url']='';
            //     $offer_list[]=$offer;
            // }
            if($shock)
            {
                $offer['offer_name']= 'Shocking Sale';   
                $offer['offer_id']=$shock->id;
                $offer['url']=url('api/customer/shock-sale');
                if($prod_data->product_type==1){
                $actual_price=$prod_data->prdPrice->price;
                if($shock->discount_type=="amount")
                    {
                        $offer['offer']=getCurrency()->name." ".$shock->discount_value." Off";
                        $discount_value = $shock->discount_value;
                        $unit_price = $actual_price-$discount_value;
                        $offer['offer_price']= $unit_price;
                       

                    }
                    else
                    {
                        $offer['offer']=$shock->discount_value."% Off";
                        $per=$shock->discount_value/100;
                        $per_value = (float)$actual_price*(float)$per;
                        $discount=(float)$actual_price-(float)$per_value;
                        $round= number_format($discount, 2);
                        $offer['offer_price']=$discount;
                    }
                }
                
                else
                {
                   $actual_price=$this->config_product_price($prod_data->id);
                if($shock->discount_type=="amount")
                    {
                        $offer['offer']=getCurrency()->name." ".$shock->discount_value." Off";
                        $discount_value = $shock->discount_value;
                        $unit_price = $actual_price-$discount_value;
                        $offer['offer_price']= $unit_price;
                       

                    }
                    else
                    {
                        $offer['offer']=$shock->discount_value."% Off";
                        $per=$shock->discount_value/100;
                        $per_value = (float)$actual_price*(float)$per;
                        $discount=(float)$actual_price-(float)$per_value;
                        $round= number_format($discount, 2);
                        $offer['offer_price']=$discount;
                    } 
                }
                //$offer_list[]=$offer;
                
                return $offer['offer_price'];
            }
            else
            {
                //$offer_list=[];
                return false;
            }
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
   
   public function exist_pwd_change(Request $request)
    {
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
            $formData   =   $request->all(); 
            $rules      =   array();
            $rules['exist_pwd']='required';
            $rules['password']='min:8|required_with:password_confirmation|confirmed';
              
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                {
                    $security               =   CustomerSecurity::where('user_id',$user_id)->first();
                    if($security)
                    {
                        if(Hash::check($request->exist_pwd, $security->password_hash)){
                            CustomerSecurity::where('user_id',$user_id)->update(['password_hash'=>Hash::make($request->password)]);
                            return array('httpcode'=>'200','status'=>'success','message'=>'Password has been changed','data'=>['message' =>'Your Password has been changed successfully']);
                            
                        }
                        else
                        {
                            return array('httpcode'=>400,'status'=>'error','message'=>'Incorrect existing password ','data'=>array('errors' =>(object)['error_msg'=>'Incorrect existing password']));
                        }
                    }
                }
        }
        else
            {
                
                return ['httpcode'=>400,'status'=>'error','message'=>'Invalid access token','data'=>['response'=>'Invalid access token']];
            }
    }//end
    
    //invite and save

    public function invite_save(Request $request)
    {
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
            
           if($request->post('count')>0) {
               
                $coupon =   CustomerMaster::where('id',$user_id)->first()->ref_code; 
                $count = $request->post('count');
                
                 $invite_save_log =  InviteSaveLog::create(['user_id' => $user_id,
                'count' => $count,
                'created_by'=>$user_id,
                'created_at'=>date("Y-m-d H:i:s")]);

                $existing = InviteSave::where('is_active',1)->where('is_valid',1)->where('is_deleted',0)->where('user_id',$user_id)->orderBy('id','DESC')->first(); 
                
                if($existing){
                    $coupon =$existing->coupon_code; 
                    $count = $existing->count + $count;
                    InviteSave::where('id',$existing->id)->update(['is_deleted'=>1]);
                }
                
                // if($count >= 10) {
                    
                //     $cname = $user['first_name']." ".$user['last_name'];
                //     $temp_data = array("coupon"=>$coupon,'email'=>$user['email'],'cname'=>$cname);
                    
                //     $invite_save =  InviteSave::create(['user_id' => $user_id,
                //     'coupon_code' => $coupon,
                //     'count' => 10,
                //     'created_by'=>$user_id,
                //     'updated_by'=>$user_id,
                //     'created_at'=>date("Y-m-d H:i:s"),
                //     'updated_at'=>date("Y-m-d H:i:s")]);
                
                //     Mail::send('emails.user_invited', $temp_data, function($message) use($temp_data) {
                //     $message->to($temp_data['email']);
                //     $message->from('sujeesh.estrrado@gmail.com',ucfirst(geSiteName()));
                //     $message->subject('Invite and Earn');
                //     });  
                //     $count = $count-10;
                //     $coupon = Str::random(6);
                // }
                
                // dd($count);
                $invite_save =  InviteSave::create(['user_id' => $user_id,
                'coupon_code' => $coupon,
                'count' => $count,
                'created_by'=>$user_id,
                'updated_by'=>$user_id,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")]);

                // Reward controller

              

                       // Reward controller
                
                
                return array('httpcode'=>'200','status'=>'success','message'=>'Success','data'=>['message' =>'Success']);

           }else {
               
               return array('httpcode'=>400,'status'=>'error','message'=>'Invalid Parameters','data'=>array('errors' =>(object)['error_msg'=>'Please select atleast one contact']));
               
           } 
           

               
        }else{ return invalidToken(); }
    }


    function ass_related_product1($prd_id,$special_ofr_available,$lang,$login){
        $data     =   [];
       // dd($login);
        $prod_data       =   AssignedAttribute::where('is_deleted',0)->where('prd_id',$prd_id)->orderBy('attr_id','DESC')->groupBy('attr_id')->get();
         // dd( $prod_data  ) ;
           if(count($prod_data)>0)   { 
                foreach($prod_data as $row)  {
                    //$attr_list['id']=$row->id;
                    $attr_list['attr_id']=$row->attr_id;
                   $attr_list['product_id']=$row->prd_id;
                    //$attr_list['attr_id']=$row->attr_id;
                    $attr_list['attr_name']=$this->get_content($row->PrdAttr->name_cnt_id,$lang);
                   // $attr_list['attr_type']=$row->PrdAttr->type;
                   // $attr_list['attr_data_type']=$row->PrdAttr->data_type;
                   // $attr_list['attr_value_name']=$this->get_content($row->PrdAttr_value->name_cnt_id,$lang);
                    $attr_list['attr_value']=$row->attr_value;
                    $attr_list['image']=config('app.storage_url').$row->attrValue->image;
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
                   
                    
                    //$attr_list['image']=config('app.storage_url').$row->attrValue->image;
                    $arrt_vals['list'][] =$this->inner_attribute($prd_id,$row->attr_id,$special_ofr_available,$row->id,$lang,$login);
                    

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
	

	//Branch List
	
	public function branch_list(Request $request)
    {
		
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
            $master =  CustomerMaster::where('is_active',1)->where('is_deleted',0)->where('id',$user_id)->first(); 
			$data=[];
			$default_address=[];	
			if($master->parent_id==0){
			$branches = CustomerBranches::where('is_active',1)->where('is_deleted',0)->where('user_id',$user_id)->get(); 
			if($branches){
			
			foreach($branches as $k=>$row)
                { 
                    $val['id']       =   $row->id;
                    $val['branch_name']       =   $row->branch_name;
                    $val['branch_image']       =   config('app.storage_url').$row->image;
                    $val['address']       =   CustomerBranches::getBranchAddress($row->address_id);
                    $val['employees']       =   CustomerBranches::getBranchEmployees($row->id);
					$data[]             =   $val;
                }
				$default_address[]       =   CustomerBranches::getDefaultAddress($user_id);

			}else{
				
				$default_address[]    =   CustomerBranches::getDefaultAddress($user_id);
				
			}
			
            }else{
				$branch= CustomerBranchEmployees::getEmployeeBranches($user_id);
				//dd($branch->branch_id);
				if($branch){
				foreach($branch as $row)
					{ 
						$val['id']       =   $row['branch_id'];
						$val['branch_name']       =   $row['branch_name'];;
						$val['branch_address']       =   CustomerBranches::getBranchAddress($row['branch_id']);
						$data[]             =   $val;
					} 
				}
				
			
			}
		$branches =  $data;			
        return array('httpcode'=>'200','status'=>'success','message'=>'Success','data'=>['branches' =>$branches,'default_address' =>$default_address]);

        }else{ return invalidToken(); }
    }
	public function add_branch(Request $request)
    {
		
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
           //dd($request->emp);
			$formData   =   $request->all(); 
            $rules      =   array();
            $rules['address_id']    = 'required|numeric';
            $rules['branch_name']          = 'required|string';
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 
				$image = $request->file('image');
				if($image){
					$msg_type='image';
					$file=$request->file('image');
					$extention=$file->getClientOriginalExtension();
					$filename=date('Ymd').time().rand(100,999).'.'.$extention;
					$file->move(('uploads/storage/app/public/branch/'.date('Ymd').'/'),$filename);
					$filenames='/app/public/branch/'.date('Ymd').'/'.$filename;
					
					
				}else{
					$filenames="";
				}
			
			
			    $branch = CustomerBranches::create(['user_id' => $user_id,
                'branch_name' => $formData['branch_name'],
                'image' => $filenames,
                'address_id' => $formData['address_id'],
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")])->id;
				$branch_id=$branch;
				$user_ids= explode(",", $request->user_ids); 
				if($user_ids){
					foreach($user_ids as $key=>$value){
						//dd($emp_ids);
						$val['branch_id']=$branch_id;
						$val['user_id']=$value;
						$val['created_at']=date("Y-m-d H:i:s");
						$val['updated_at']=date("Y-m-d H:i:s");
						CustomerBranchEmployees::create($val);
					}
				}
				

			
				}			
        return array('httpcode'=>'200','status'=>'success','message'=>'Success','data'=>['branch_id' =>$branch]);

        }else{ return invalidToken(); }
    }
	public function update_branch(Request $request)
    {
		
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
           //dd($request->emp);
			$formData   =   $request->all(); 
            $rules      =   array();
            $rules['address_id']    = 'required|numeric';
            $rules['branch_name']          = 'required|string';
            $rules['branch_id']          = 'required|numeric';
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 
			    $exist=CustomerBranches::where('id',$formData['branch_id'])->where('is_active',1)->where('is_deleted',0)->first();
                if($exist){
				$image = $request->file('image');
				if($image){
					$msg_type='image';
					$file=$request->file('image');
					$extention=$file->getClientOriginalExtension();
					$filename=date('Ymd').time().rand(100,999).'.'.$extention;
					$file->move(('uploads/storage/app/public/branch/'.date('Ymd').'/'),$filename);
					$filenames='/app/public/branch/'.date('Ymd').'/'.$filename;
					
					
				}else{
				$filenames	= $exist->image;
				}
				$data['branch_name'] = $formData['branch_name'];
                $data['address_id'] = $formData['address_id'];
                $data['created_at']= date("Y-m-d H:i:s");
                $data['updated_at']= date("Y-m-d H:i:s");
                $data['image']= $filenames;
				CustomerBranches::where('id',$formData['branch_id'])->update($data);
				$user_ids= explode(",", $request->user_ids); 
				if($user_ids){
					CustomerBranchEmployees::where('branch_id',$formData['branch_id'])->update(['is_deleted'=>1]);
					
					foreach($user_ids as $key=>$value){
						$val['branch_id']=$formData['branch_id'];
						$val['user_id']=$value;
						$val['created_at']=date("Y-m-d H:i:s");
						$val['updated_at']=date("Y-m-d H:i:s");
						CustomerBranchEmployees::create($val);
					}
				}
				return array('httpcode'=>'200','status'=>'success','message'=>'Success');
				}else{
				 return array('httpcode'=>'400','status'=>'Failed','message'=>'Branch Not Found');
				}
				

			
				}			
        

        }else{ return invalidToken(); }
    }
	
	
	public function branch_employees(Request $request)
    {
		
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
             
			
			$employees= CustomerBranchEmployees::join('user_business_branches', 'user_business_branches.id', '=', 'user_branch_employees.branch_id')
			->join('usr_info', 'usr_info.user_id', '=', 'user_branch_employees.user_id')
			->where('user_branch_employees.is_active',1)->where('user_branch_employees.is_deleted',0)->where('user_business_branches.user_id',$user_id)->groupBy('user_branch_employees.user_id')->get(); 
			//dd($employees);
			$data=[];
			if($employees){
			foreach($employees as $k=>$row)
                { 
                    $val['emp_id']       =   $row->user_id;
					if($row->profile_image){
                    $val['profile_image']       	  =  config('app.storage_url').$row->profile_image;
                    }
					$val['name']       	  =   $row->first_name;
                    $val['phone']         =   CustomerBranchEmployees::telecom_ph($row->user_id);
					$val['Branch']        =   CustomerBranchEmployees::getEmployeeBranches($row->user_id);
                    
                    $data[]             =   $val;
                } 
			}
			$branches =  $data;
                
        return array('httpcode'=>'200','status'=>'success','message'=>'Success','data'=>['branches' =>$branches]);
				
        }else{ return invalidToken(); }
    }
	

	
	public function add_employee(Request $request)
    {
		//$product_and_quantity=explode(",", $request->branch_ids); 
		//dd($product_and_quantity);
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
          //dd($user_id);
			$formData   =   $request->all(); 
            $rules      =   array();
            $rules['branch_ids']    = 'required';
            //$rules['branch_id.*']    = 'required|numeric';
            $rules['employee_name']          = 'required|string';
            $rules['country_code']          = 'required|string';
            $rules['phone_number']          = 'required|nullable|min:7,12|unique:usr_telecom,usr_telecom_value';
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                {
				$image = $request->file('image');
				if($image){
					$msg_type='image';
					$file=$request->file('image');
					$extention=$file->getClientOriginalExtension();
					$filename=date('Ymd').time().rand(100,999).'.'.$extention;
					$file->move(('uploads/storage/app/public/profile/'.date('Ymd').'/'),$filename);
					$filenames='/app/public/profile/'.date('Ymd').'/'.$filename;
					
					
				}else{
					$filenames="";
				}
				$random = Str::random(6);
				
				$username=strtolower(str_replace(' ', '', $request->employee_name."-".$random));
				$master =  CustomerMaster::create(['org_id' => 1,
                'username' =>$username ,
                'parent_id' => $user_id,
                'ref_code' => $random,
                'invited_by'=>0,
                'is_active'=>1,
                'is_deleted'=>0,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")]);
				$masterId = $master->id;
				
				$info = CustomerInfo::create(['org_id' => 1,
				'first_name' => $request->employee_name,
				'user_id' => $masterId,
				'usr_role_id' => 6,
				'profile_image'=>$filenames,
				'is_active'=>1,
				'is_deleted'=>0,
				'created_at'=>date("Y-m-d H:i:s"),
				'updated_at'=>date("Y-m-d H:i:s")]);

				$security = CustomerSecurity::create(['org_id' => 1,
				'password_hash' => Hash::make($username),
				'user_id' => $masterId,
				'is_active'=>1,
				'is_deleted'=>0,
				'created_at'=>date("Y-m-d H:i:s"),
				'updated_at'=>date("Y-m-d H:i:s")]);
				if($request->phone_number){
				   $telecom_ph = CustomerTelecom::create(['org_id' => 1,
				   'user_id' => $masterId,
				   'usr_telecom_typ_id'=>2,
				   'country_code'=>$request->country_code,
				   'usr_telecom_value'=>$request->phone_number,
				   'is_active'=>1,
				   'is_deleted'=>0,
				   'created_at'=>date("Y-m-d H:i:s"),
				   'updated_at'=>date("Y-m-d H:i:s")]);
				   $ph_tele=$telecom_ph->id;

				   CustomerMaster::where('id',$masterId)->update([
					   'phone'=>$ph_tele
				   ]);
				   
				    $otp = rand(1000, 9999);
					$otp=1234;
					CustomerRegisterotp::create(['user_id'=>$masterId,'country_code'=>$request->country_code,'phone_number'=>$request->phone_number,'otp'=>$otp,'created_at'=>date('Y-m-d H:i:s')]);

				}
				$phone='+'.$request->country_code.$request->phone_number;
				/*$sid = 'AC501ea7dd496abb44d8f1374c977b2925';
				$token = '19b7537d6789c95e2444cc0c4d837008';
				$client = new Client($sid, $token);

				// Use the client to do fun stuff like send text messages!
				$incoming_phone_number=$client->messages->create(
					// the number you'd like to send the message to
					$phone,
					[
						// A Twilio phone number you purchased at twilio.com/console
						'from' => '+17632972465',
						// the body of the text message you'd like to send
						'body' => 'Hey '.$request->employee_name.'! You Can Now Login Bigbasket Using Your Mobile Number '.$phone
					]
				);*/
				//print($incoming_phone_number->sid);
				
				$branch_ids= explode(",", $request->branch_ids); 
				//dd($branch_ids);
				if($branch_ids){
					foreach($branch_ids as $key=>$value){
						$val['branch_id']=$value;
						$val['user_id']=$masterId;
						$val['created_at']=date("Y-m-d H:i:s");
						$val['updated_at']=date("Y-m-d H:i:s");
						CustomerBranchEmployees::create($val);
					}
				}
				

			
				}			
        return array('httpcode'=>'200','status'=>'success','message'=>'Successfully Created Employee');

        }else{ return invalidToken(); }
    }
	
	public function update_employee(Request $request)
    {
		
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
          //dd($user_id);
			$formData   =   $request->all(); 
            $rules      =   array();
            $rules['branch_ids']    = 'required';
            //$rules['branch_id.*']    = 'required|numeric';
            $rules['employee_name']          = 'required|string';
            $rules['country_code']          = 'required|string';
            $rules['phone_number']          = 'required|nullable|min:7,12';
            $rules['employee_id']          = 'required|numeric';
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                {
				$existing = CustomerMaster::where('is_active',1)->where('is_deleted',0)->where('id',$request->employee_id)->orderBy('id','DESC')->first();  
				//dd($existing->phone);
				if($existing){
					$CustomerInfo = CustomerInfo::where('is_active',1)->where('is_deleted',0)->where('id',$request->employee_id)->first();  
					$existing_phone=CustomerMaster::custPhone($existing->phone);
					if($request->phone_number != $existing_phone){
					
						$checkexist_new = CustomerTelecom::where('is_deleted',0)->where('usr_telecom_value',$request->phone_number)->orderBy('id','DESC')->first();  
						if($checkexist_new){
						return array('httpcode'=>'400','status'=>'error','message'=>"This Phone Number has been already taken" );
						}else{
						$data['usr_telecom_typ_id']=2;
						$data['country_code']=$request->country_code;
						$data['usr_telecom_value']=$request->phone_number;
						$data['is_active']=1;
						$data['is_deleted']=0;
						$data['updated_at']=date("Y-m-d H:i:s");	
						CustomerTelecom::where('id',$existing->phone)->update($data);
						
						$phone='+'.$request->country_code.$request->phone_number;
						/*$sid = 'AC709155bb1b2e2d7479446ac918a4a3df';
						$token = '5b1844c8e9bb9691b090d20690d21167';
						$client = new Client($sid, $token);

						// Use the client to do fun stuff like send text messages!
						$incoming_phone_number=$client->messages->create(
							// the number you'd like to send the message to
							$phone,
							[
								// A Twilio phone number you purchased at twilio.com/console
								'from' => '+17632972465',
								// the body of the text message you'd like to send
								'body' => 'Hey '.$request->employee_name.'! You Can Now Login Bigbasket Using Your Updated Mobile Number '.$phone
							]
						); */
						}
					}
				
					$image = $request->file('image');
					if($image){
						$msg_type='image';
						$file=$request->file('image');
						$extention=$file->getClientOriginalExtension();
						$filename=date('Ymd').time().rand(100,999).'.'.$extention;
						$file->move(('uploads/storage/app/public/profile/'.date('Ymd').'/'),$filename);
						$filenames='/app/public/profile/'.date('Ymd').'/'.$filename;
						
						
					}else{
						if($CustomerInfo){
					$filenames	= $CustomerInfo->profile_image;
						}else{
							$filenames	= "";
						}
					}
					$data1['profile_image']=$request->filenames;
					$data1['first_name']=$request->employee_name;
					$data1['updated_at']=date("Y-m-d H:i:s");
					CustomerInfo::where('user_id',$request->employee_id)->update($data1);
	
					$branch_ids= explode(",", $request->branch_ids); 
				//dd($branch_ids);
				if($branch_ids){
											CustomerBranchEmployees::where('user_id',$request->employee_id)->update(['is_deleted'=>1]);

					foreach($branch_ids as $key=>$value){
						$val['branch_id']=$value;
						$val['user_id']=$request->employee_id;
						$val['created_at']=date("Y-m-d H:i:s");
						$val['updated_at']=date("Y-m-d H:i:s");
						CustomerBranchEmployees::create($val);
					}
				}
					
					
				}else{
					return array('httpcode'=>'400','status'=>'error','message'=>"Employee Not Found");
				}
			
				}			
        return array('httpcode'=>'200','status'=>'success','message'=>'Successfully Updated Employee Details');

        }else{ return invalidToken(); }
    }
	
	public function delete_branch(Request $request){
		
		if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
			$formData   =   $request->all(); 
            $rules      =   array();
            $rules['branch_id']          = 'required|numeric';
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                {
					$existing = CustomerBranches::where('id',$formData['branch_id'])->where('is_active',1)->where('is_deleted',0)->first();
					//dd($existing->phone);
					if($existing){
					$data1['is_deleted']=1;
					$data1['is_active']=0;
					CustomerBranches::where('id',$formData['branch_id'])->update($data1);
					return array('httpcode'=>'200','status'=>'success','message'=>'Successfully Deleted');

					}else{
					return array('httpcode'=>'400','status'=>'error','message'=>'Deleted or Not Exist');

					}
				}
			}
	}
	public function delete_employee(Request $request){
		
		if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
			$formData   =   $request->all(); 
            $rules      =   array();
            $rules['employee_id']          = 'required|numeric';
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                {
					$existing = CustomerMaster::where('is_active',1)->where('is_deleted',0)->where('id',$request->employee_id)->orderBy('id','DESC')->first();  
					//dd($existing->phone);
					if($existing){
					$data1['is_deleted']=1;
					$data1['is_active']=0;
					CustomerMaster::where('id',$request->employee_id)->update($data1);
					CustomerBranchEmployees::where('user_id',$request->employee_id)->update($data1);
					CustomerInfo::where('user_id',$request->employee_id)->update($data1);
					CustomerTelecom::where('user_id',$request->employee_id)->update($data1);
					return array('httpcode'=>'200','status'=>'success','message'=>'Successfully Deleted');

					}else{
					return array('httpcode'=>'400','status'=>'error','message'=>'Deleted or Not Exist');

					}
				}
			}
	}
	
	
	public function my_business_details(Request $request)
    {
		
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
           //dd($request->emp);
			$formData   =   $request->all(); 
            $rules      =   array();
            $rules['business_name']          = 'required|string';
            $rules['established_on']          = 'required|date';
            $rules['business_type']          = 'required|string';
            $rules['description']          = 'required|string';
            $rules['is_branches']          = 'required|numeric';
			$rules['business_image']   = 'image|mimes:jpeg,png,jpg,gif,svg';
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 
			    $exist=CustomerBusinessDetails::where('user_id',$user_id )->first();
                if($exist){
				$data['business_name'] = $formData['business_name'];
                $data['established_on'] = $formData['established_on'];
                $data['business_type'] = $formData['business_type'];
                $data['description'] = $formData['description'];
                $data['is_branches'] = $formData['is_branches'];
                $data['updated_at']= date("Y-m-d H:i:s");
				if($request->hasFile('business_image'))
                    {
                    $file=$request->file('business_image');
                    $extention=$file->getClientOriginalExtension();
                    $filename=time().'.'.$extention;
                    $file->move(('uploads/storage/app/public/business/'),$filename);
                    $data['business_image']=$filename;
					}
				CustomerBusinessDetails::where('user_id',$user_id)->update($data);
				
				}
				else{
				$data['user_id'] = $user_id;
				$data['business_name'] = $formData['business_name'];
                $data['established_on'] = $formData['established_on'];
                $data['business_type'] = $formData['business_type'];
                $data['description'] = $formData['description'];
                $data['is_branches'] = $formData['is_branches'];
				if($request->hasFile('business_image'))
                    {
                    $file=$request->file('business_image');
                    $extention=$file->getClientOriginalExtension();
                    $filename=time().'.'.$extention;
                    $file->move(('uploads/storage/app/public/business/'),$filename);
                    $data['business_image']=$filename;
					}
                $data['created_at']= date("Y-m-d H:i:s");
                $data['updated_at']= date("Y-m-d H:i:s");
				CustomerBusinessDetails::create($data);
					
				}
				return array('httpcode'=>'200','status'=>'success','message'=>'Success');
			}			
        

        }else{ return invalidToken(); }

    }

     public function referral(Request $request)
    {
        Config::get('custom.my_val');
		if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
            $val        =   [];
            $formData   =   $request->all(); 
            $rules      =   array();
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 
                    $customer =  CustomerMaster::where('id',$user_id)->first(); 
                    $invite_save= SettingOther::where('is_active',1)->where('is_deleted',0)->first();
                    if($customer)
                    {
                        $cust_info = CustomerInfo::where('user_id',$user_id)->first(); 
                        $cust_tele = CustomerTelecom::where('user_id',$user_id)->first();
                       
                        $data['ref_code']         =   $customer->ref_code;
                        $rewards              =   Reward::getRewards();
                        $rewards = (object) $rewards;
                        
                        $data['rewards']['type'] =  $rewards->reward;
                        if($rewards->reward == "cashback")
                        {
                            if($rewards->rwd_type_referral == 6)
                            {
                              
                              $data['rewards']['cashback_register'] =  $rewards->referral_cashback_register*$rewards->point_val; 
                              $data['rewards']['cashback_first_purchase'] =  $rewards->referral_cashback_purchase*$rewards->point_val; 
                            }else if($rewards->rwd_type_referral == 5){
                                $data['rewards']['cashback_first_purchase'] =  $rewards->referral_cashback_purchase*$rewards->point_val; 
                            }else{
                                $data['rewards']['cashback_register'] =  $rewards->referral_cashback_register*$rewards->point_val; 
                            }
                            
                        }else{

                             if($rewards->rwd_type_referral == 6)
                            {
                              
                              $data['rewards']['referral_coupon_register_amount'] =  Coupon::getCouponData($rewards->referral_coupon_register)['ofr_value']; 
                              $data['rewards']['referral_coupon_purchase_amount'] =  Coupon::getCouponData($rewards->referral_coupon_purchase)['ofr_value']; 
                            }else if($rewards->rwd_type_referral == 5){
                                $data['rewards']['referral_coupon_purchase_amount'] =  Coupon::getCouponData($rewards->referral_coupon_purchase)['ofr_value']; 
                            }else{
                                $data['rewards']['referral_coupon_register_amount'] =  Coupon::getCouponData($rewards->referral_coupon_register)['ofr_value']; 
                            }

                        }
                        
            
                        $val[] = $data;
                    }
                    else
                    {
                       $val        =   []; 
                    }
                    return array('httpcode'=>'200','status'=>'success','message'=>'Referral','data'=>['referral'=>$val]);
                }
        }else{ return invalidToken(); }

  }
  
public function view_my_business_details(Request $request)
    {
		
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
           //dd($request->emp);
			$formData   =   $request->all(); 
            $exist=CustomerBusinessDetails::where('user_id',$user_id )->first();
            if($exist){
				$data['business_name'] = $exist->business_name;
                $data['established_on'] = $exist->established_on;
                $data['business_type'] = $exist->business_type;
				if(!empty($exist->business_image)){ $avatar = config('app.storage_url').'/app/public/business/'.$exist->business_image;} else { $avatar = config('app.storage_url').'/app/public/no-image.png';}
                $data['business_image'] = $avatar;
                $data['description'] = $exist->description;
                $data['is_branches'] = $exist->is_branches;
                $data['updated_at']= $exist->updated_at;
				
				return array('httpcode'=>'200','status'=>'success','message'=>'Success','data'=>['busniness_details' =>$data]);
			}else{
				
			return array('httpcode'=>'400','status'=>'error','message'=>'Not Updated');	
			}
				
			}			
        else{ return invalidToken(); }


    }
    
     public function prof_image(Request $request)
    {
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
            $formData   =   $request->all(); 
            $rules      =   array();
            $rules['profile_img']   = 'required|image|mimes:jpeg,png,jpg,gif,svg';
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 
                    
                    if($request->hasFile('profile_img'))
                    {
                    $file=$request->file('profile_img');
                    $extention=$file->getClientOriginalExtension();
                    $filename=time().'.'.$extention;
                    $file->move(('uploads/storage/app/public/customer_profile/'),$filename);
                    $info = CustomerInfo::where('user_id',$user_id)->where('is_deleted',0)->where('is_active',1)->update([
                    'profile_image'=>$filename,
                    ]);
                    $cust_info = CustomerInfo::where('user_id',$user_id)->first(); 
                    if(!empty($cust_info->profile_image)){ $avatar = config('app.storage_url').'/app/public/customer_profile/'.$cust_info->profile_image;} else { $avatar = config('app.storage_url').'/app/public/no-avatar.png';}
                    return array('httpcode'=>'200','status'=>'success','message'=>'Profile updated','data'=>['profile_image' =>$avatar]);
					}
					
					
				}
        }else{ return invalidToken(); }
    }
}
