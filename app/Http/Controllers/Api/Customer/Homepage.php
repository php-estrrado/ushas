<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
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
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Subcategory;
use App\Models\Store;
use App\Models\SellerReview;
use App\Models\SellerInfo;
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
use App\Models\PrdSearch_Directory;
use App\Models\RelatedProduct;
use App\Models\AssignedAttribute;
use App\Models\UserVisit;
use App\Models\PrdImage;
use App\Models\PrdShockingSaleProduct;
use App\Models\Currency;

use Carbon\Carbon;
use App\Rules\Name;
use Validator;

use App\Models\crm\{CrmAssortmentMaster, CrmChildProductsMaster, CrmCustomerType,CrmPartAssortmentDetails,
CrmPartAssortmentMaster,CrmProduct,CrmSalesPriceList,CrmSalesPriceType,CrmSize};

class Homepage extends Controller
{
    public function index(Request $request)
    {  
		//dd($request->post);
        $lang_id=$request->lang_id;
        $login=0;
        $user_id=null;
        $user = [];
        
        $validator=  Validator::make($request->all(),[
            'device_id' => ['required'],
            'os_type'=> ['required','string','min:3','max:3'],
            'page_url'=>['required']
        ]);
        if ($validator->fails()) 
            {    
              return ['httpcode'=>400,'status'=>'error','message'=>'invalid','data'=>['errors'=>$validator->messages()]];
            }
            
            
        
        if($request->post('access_token')){
            if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
            $login=1;
            $user_id = $user['user_id'];
            
        }
        
        $usr_visit =UserVisit::create([
                'org_id' =>1,
                'device_id'=>$request->device_id,
                'is_login'=>$login,
                'os'=>$request->os_type,
                'url'=>$request->page_url,
                'visited_on'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")]);
    

        //category
        $category_data= Category::where('is_active',1)->where('is_deleted',0)->orderBy('sort_order')->get();
       foreach($category_data as $key)
        {   $image = url('storage/app/public/category/'.$key->image); 
            $category_array=['id'=>$key->category_id,
            'category_name'=>$key->get_content($key->cat_name_cid,$lang_id),
            'description'=>$key->get_content($key->cat_desc_cid,$lang_id),
            'no_of_prds'=>$key->get_count('prd_products','category_id',$key->category_id),
            'image'=>$image];
            $categories[]=$category_array;
        }
        
        $all_category=['category_name'=>'All categories','image'=>url('storage/app/public/category/more_cat.png')];


			//New Arrivals 
        $latest_products=[];
        $latest_prod_data= Product::where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->where('visible',1)->orderBy('id','DESC')->take(10)->get();
          //print_r($prod_data);exit;
		  if(count($latest_prod_data)>0)
            {
                foreach($latest_prod_data as $row)
                {
                    $store_active = Store::where('is_active',1)->where('seller_id',$row->seller_id)->first();
                   // if($store_active) {
                    //$prd_list['service_status']=$store_active->service_status;    
                    $latest_prd_list['product_id']=$row->id;
                    $latest_prd_list['product_name']=$this->get_content($row->name_cnt_id,$lang_id);
                    $latest_prd_list['category_id']=$row->category_id;
					if($latest_prd_list['category_id']){
						$category=Category::where('category_id',$row->category_id)->first();
						$latest_prd_list['is_rating']=$category->is_rating;
					}else{
						
						$latest_prd_list['is_rating']=0;
					}
                    $latest_prd_list['category_name']=$row->get_content($row->category->cat_name_cid,$lang_id);
                    $latest_prd_list['subcategory_id']=$row->sub_category_id;
                    $latest_prd_list['subcategory_name']=$row->get_content($row->subCategory->sub_name_cid,$lang_id);
                    if($row->brand_id)
                    {
                    $latest_prd_list['brand_id']=$row->brand_id;
                    $latest_prd_list['brand_name']=$row->get_content(@$row->brand->brand_name_cid,$lang_id);
                    }
                    else
                    {
                    $latest_prd_list['brand_id']='';
                    $latest_prd_list['brand_name']='';   
                    }
                    //$prd_list['seller']=$row->Store($row->seller_id)->store_name;
                    //$prd_list['seller_id']=$row->seller_id;
                    if($row->product_type==1)
                    {
                    $latest_prd_list['product_type']='simple';    
						
						$latest_prd_price=$this->get_price($row->id,$type=1,$login);
						foreach ($latest_prd_price as $item) {
							foreach ($item as $key => $value) {
								$latest_prd_list[$key] = $value;
							} 
						}

					}
                    else
                    {
                     $latest_prd_list['product_type']='config'; 
                      $latest_prd_list['min_order_qty']="Null";
					 $latest_prd_list['bulk_order_qty']="Null";
					 $minprice_prd_id=$this->min_price_product($row->id);
					//dd($minprice_prd_id);
					$latest_prd_price=$this->get_price($minprice_prd_id,$type=2,$login);
					foreach ($latest_prd_price as $item) {
						foreach ($item as $key => $value) {
							$latest_prd_list[$key] = $value;
						} 
					}
						
					}
                    $latest_prd_list['short_description']=$row->get_content($row->short_desc_cnt_id,$lang_id);
                    $latest_prd_list['rating']=$this->get_rates($row->id);
                    $latest_prd_list['total_reviews']=$this->get_rates_count($row->id);
                    $latest_prd_list['image']=$this->get_product_image($row->id); 
					//ASSOCIATIVE products
					 $associative_prd=[];
                    if($row->product_type == 2)
                    {  
                        $prices = $this->config_product_price($row->id); 
                        $special_ofr_available=$this->get_special_ofr_value($prices,$row->id); 
                        $prd_ass = AssociatProduct::where('prd_id',$row->id)->where('is_deleted',0)->get();
                        if(count($prd_ass)>0)
                        {
                            foreach($prd_ass as $rows)
                            {
                            $product_visibility= Product::where('id',$rows->ass_prd_id)->where('is_active',1)->where('is_deleted',0)->first();
                            if($product_visibility){
                            //$associative_prd[]=$this->ass_related_product($rows->ass_prd_id,$lang);
                            
                                $associative_prd[]=$this->ass_related_product1($product_visibility->id,$special_ofr_available,$lang_id,$login);
                            
                            
                             }
                            }
                        }
                        else
                        {
                            $associative_prd=[];
                        }
                  }
                  else
                  {
                    $associative_prd=[];
                  }		

                  
			 $variants_list=[];
				 //Variants List   
				if($associative_prd){
					//dd(count($associative_prd));
					$variants_list = array();
					foreach($associative_prd as $asso_prod){
						$attr_value=$asso_prod['attr_value'];
						$attr_name=$asso_prod['attr_name'];
						$data['pro_id']=$asso_prod['product_id'];
						if($asso_prod['sub_attributes']){
						foreach($asso_prod['sub_attributes'] as $row1){
						
						if($attr_value){
						$data['combination']=$attr_value." ".$attr_name ." - ".$row1['attr_value']." ".$row1['attr_name'];
						}else{
						$data['variants']=$row1['attr_value']." ".$row1['attr_name'];
						}
						$data['stock']=$row1['stock'];
						$data['is_out_of_stock']=$row1['is_out_of_stock'];
						$data['out_of_stock_selling']=$row1['out_of_stock_selling'];
						$data['min_order_qty']=$asso_prod['min_order'];
						$data['bulk_order_qty']=$asso_prod['bulk_order'];
						$data['image']=$row1['image'];
						$price=$this->get_price($data['pro_id'],$type=2,$login);
						foreach ($price as $item) {
							foreach ($item as $key => $value) {
								$data[$key] = $value;
							} 
						}	
						
						}
						}else{
						    $data['combination']=$attr_value." ".$attr_name;
						    $data['stock']=$asso_prod['stock'];
    					    $data['is_out_of_stock']=$asso_prod['is_out_of_stock'];
    					    $data['out_of_stock_selling']=$asso_prod['out_of_stock_selling'];
    						$data['min_order_qty']=$asso_prod['min_order'];
    						$data['bulk_order_qty']=$asso_prod['bulk_order'];
    						$data['image']=$asso_prod['image'];
    						$price=$this->get_price($data['pro_id'],$type=2,$login);
    						foreach ($price as $item) {
    							foreach ($item as $key => $value) {
    								$data[$key] = $value;
    							} 
    						}
						    
						}
					
					$variants_list[] = $data;
					}
				}    

                    
			$latest_prd_list['variants_list']=$variants_list;
                    $latest_products[]=$latest_prd_list;
                    //}
                }

            }
            else
            {
                $latest_products[]='';
            }

			     
			
			//Subcategory list of grocery
            $grocery_subcat=[];
			$grocery_subcat= $this->get_subcategory(33,$lang_id);
			$fruit_veg_subcat=[];
            $fruit_veg_subcat= $this->get_subcategory(32,$lang_id);
           

            //Category and subcategory List
            
            foreach($category_data as $cat)
            {
                $cat_list['category_id']=$cat->category_id;
                $cat_list['category_name']=$cat->get_content($cat->cat_name_cid,$lang_id);
                $cat_list['subcategory']=$this->get_subcategory($cat->category_id,$lang_id);  
                
               
                $cat_subcat[]=$cat_list;
            }


            //Shocking sales
           
            

            //LANGUAGE
            $lang=DB::table('glo_lang_lk')->where('is_active', 1)->get();
            foreach($lang as $key)
            {
                $lan['id']=$key->id;
                $lan['name']=$key->glo_lang_name;
                $lan['code']=$key->glo_lang_code;
                $language[]=$lan;
            }

            //cart products
            if($request->post('access_token'))
            {
                $cart_data=Cart::where('user_id',$user['user_id'])->where('is_active',1)->where('is_deleted',0)->count();
                if($cart_data>0)
                {
                    $cart=$cart_data;
                }
                else
                {
                    $cart='';
                }
            }
            else
            {
                $cart='';
            }
            $brandlist=[];
			$brand  = Brand::where('is_active',1)->where('is_deleted',0)->get();
			if(count($brand)>0)
			{
				foreach($brand as $row)
				{
				$prod_exist = Product::where('brand_id',$row->id)->where('is_active',1)->where('is_deleted',0)->where('visible',1)->where('is_approved',1)->first();
				// if($prod_exist)
				//	{
						
					$b_list['brand_id']=$row->id;
					$b_list['brand_name']=$this->get_content($row->brand_name_cid,$lang_id);
					$b_list['brand_name']=$this->get_content($row->brand_name_cid,$lang_id);
					$b_list['brand_description']=$this->get_content($row->brand_desc_cid,$lang_id); 
					if($row->image){
                    $b_list['brand_image']=config('app.storage_url').'/app/public/brands/'.$row->image;
					}else{
					    $b_list['brand_image']="";
					}
					
					$brandlist[]=$b_list;
							
					//}
				 }
				 
			}
            






        return ['httpcode'=>200,'status'=>'success','page'=>'Home','message'=>'Success','data'=>['user_data'=>$user,

            'category'=>$categories,
            'grocery_subcat'=>$grocery_subcat,
			'all_category_icon'=>$all_category,
			// 'beverage_products'=>$beverage_products,
			// 'fruit_and_vegs'=>$fruit_veg_subcat,
			// //'grocery_products'=>$grocery_products,
			// 'featured_products'=>$products,
			// 'daily_deals'=>$daily_product,
            // 'comingsoon_products'=>$comingsoon_product,
			'cat_subcat'=>$cat_subcat,
			// 'shocking_sale'=>$shock_data,
			//'offer_products'=>$offer_products,
			'new_arrivals'=>$latest_products,
			// 'trending_products'=>$trending_products,
			// 'explore_products'=>$explore_products,
			//'auction'=>$auction_data,
			//'sellers'=>$store_data,
			'brands'=>$brandlist,
			'languages'=>$language,
			'cart'=>$cart,
			'currency'=>getCurrency()->name,
			//'most_search_brand'=>$search_brand
			]];
    }

    public function home_explore(Request $request){

         $lang_id=$request->lang_id;
        $login=0;
        $user_id=null;
        $user = [];
        
        $validator=  Validator::make($request->all(),[
            'device_id' => ['required'],
            'os_type'=> ['required','string','min:3','max:3'],
            'page_url'=>['required']
        ]);
        if ($validator->fails()) 
            {    
              return ['httpcode'=>400,'status'=>'error','message'=>'invalid','data'=>['errors'=>$validator->messages()]];
            }
            
        if($request->post('access_token')){
            if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
            $login=1;
            $user_id = $user['user_id'];
            
        }

//explore 
        $explore_products=[];
        $explore_prod_data= Product::where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->where('visible',1)->inRandomOrder()->limit(10)->get();
          if(count($explore_prod_data)>0)
            {
                foreach($explore_prod_data as $row)
                {
                    $store_active = Store::where('is_active',1)->where('seller_id',$row->seller_id)->first();
                   // if($store_active) {
                    //$prd_list['service_status']=$store_active->service_status;    
                    $explore_prd_list['product_id']=$row->id;
                    $explore_prd_list['product_name']=$this->get_content($row->name_cnt_id,$lang_id);
                    $explore_prd_list['category_id']=$row->category_id;
                    if($explore_prd_list['category_id']){
                        $category=Category::where('category_id',$row->category_id)->first();
                        $explore_prd_list['is_rating']=$category->is_rating;
                    }else{
                        
                        $explore_prd_list['is_rating']=0;
                    }
                    $explore_prd_list['category_name']=$row->get_content($row->category->cat_name_cid,$lang_id);
                    $explore_prd_list['subcategory_id']=$row->sub_category_id;
                    $explore_prd_list['subcategory_name']=$row->get_content($row->subCategory->sub_name_cid,$lang_id);
                    if($row->brand_id)
                    {
                    $explore_prd_list['brand_id']=$row->brand_id;
                    $explore_prd_list['brand_name']=$row->get_content(@$row->brand->brand_name_cid,$lang_id);
                    }
                    else
                    {
                    $explore_prd_list['brand_id']='';
                    $explore_prd_list['brand_name']='';   
                    }
                    //$prd_list['seller']=$row->Store($row->seller_id)->store_name;
                    //$prd_list['seller_id']=$row->seller_id;
                    if($row->product_type==1)
                    {
                    $explore_prd_list['product_type']='simple';    
                        
                        $explore_prd_price=$this->get_price($row->id,$type=1,$login);
                        foreach ($explore_prd_price as $item) {
                            foreach ($item as $key => $value) {
                                $explore_prd_list[$key] = $value;
                            } 
                        }                   
                    }
                    else
                    {
                     $explore_prd_list['product_type']='config'; 
                     $explore_prd_list['min_order_qty']="Null";
                     $explore_prd_list['bulk_order_qty']="Null";
                     $minprice_prd_id=$this->min_price_product($row->id);
                    //dd($minprice_prd_id);
                    $explore_prd_price=$this->get_price($minprice_prd_id,$type=2,$login);
                        foreach ($explore_prd_price as $item) {
                            foreach ($item as $key => $value) {
                                $explore_prd_list[$key] = $value;
                            } 
                        }
                        
                    }
                    $explore_prd_list['short_description']=$row->get_content($row->short_desc_cnt_id,$lang_id);
                    $explore_prd_list['rating']=$this->get_rates($row->id);
                    $explore_prd_list['total_reviews']=$this->get_rates_count($row->id);
                    $explore_prd_list['image']=$this->get_product_image($row->id); 
//ASSOCIATIVE products
                     $associative_prd=[];
                    if($row->product_type == 2)
                    {  
                        $prices = $this->config_product_price($row->id); 
                        $special_ofr_available=$this->get_special_ofr_value($prices,$row->id); 
                        $prd_ass = AssociatProduct::where('prd_id',$row->id)->where('is_deleted',0)->get();
                        if(count($prd_ass)>0)
                        {
                            foreach($prd_ass as $rows)
                            {
                            $product_visibility= Product::where('id',$rows->ass_prd_id)->where('is_active',1)->where('is_deleted',0)->first();
                            if($product_visibility){
                            //$associative_prd[]=$this->ass_related_product($rows->ass_prd_id,$lang);
                            
                                $associative_prd[]=$this->ass_related_product1($product_visibility->id,$special_ofr_available,$lang_id,$login);
                            
                            
                             }
                            }
                        }
                        else
                        {
                            $associative_prd=[];
                        }
                  }
                  else
                  {
                    $associative_prd=[];
                  }     

                  
             $variants_list=[];
                 //Variants List   
                if($associative_prd){
                    //dd(count($associative_prd));
                    $variants_list = array();
                    foreach($associative_prd as $asso_prod){
                        $attr_value=$asso_prod['attr_value'];
                        $attr_name=$asso_prod['attr_name'];
                        $data['pro_id']=$asso_prod['product_id'];
                        if($asso_prod['sub_attributes']){
                        foreach($asso_prod['sub_attributes'] as $row1){
                        
                        if($attr_value){
                        $data['combination']=$attr_value." ".$attr_name ." - ".$row1['attr_value']." ".$row1['attr_name'];
                        }else{
                        $data['variants']=$row1['attr_value']." ".$row1['attr_name'];
                        }
                        $data['stock']=$row1['stock'];
                        $data['is_out_of_stock']=$row1['is_out_of_stock'];
                        $data['out_of_stock_selling']=$row1['out_of_stock_selling'];
                        $data['min_order_qty']=$asso_prod['min_order'];
                        $data['bulk_order_qty']=$asso_prod['bulk_order'];
                        $data['image']=$row1['image'];
                        $price=$this->get_price($data['pro_id'],$type=2,$login);
                        foreach ($price as $item) {
                            foreach ($item as $key => $value) {
                                $data[$key] = $value;
                            } 
                        }   
                        
                        }
                        }else{
                            $data['combination']=$attr_value." ".$attr_name;
                            $data['stock']=$asso_prod['stock'];
                            $data['is_out_of_stock']=$asso_prod['is_out_of_stock'];
                            $data['out_of_stock_selling']=$asso_prod['out_of_stock_selling'];
                            $data['min_order_qty']=$asso_prod['min_order'];
                            $data['bulk_order_qty']=$asso_prod['bulk_order'];
                            $data['image']=$asso_prod['image'];
                            $price=$this->get_price($data['pro_id'],$type=2,$login);
                            foreach ($price as $item) {
                                foreach ($item as $key => $value) {
                                    $data[$key] = $value;
                                } 
                            }
                            
                        }
                    
                    $variants_list[] = $data;
                    }
                }    

                    
            $explore_prd_list['variants_list']=$variants_list;
                    $explore_products[]=$explore_prd_list;
                    //}
                }

            }
            else
            {
                $explore_products[]='';
            }
        
           return ['httpcode'=>200,'status'=>'success','page'=>'Home','message'=>'Success','data'=>[
       
           'explore_products'=>$explore_products,
          
            ]];

    }

    public function home_banners(Request $request){

         $lang_id=$request->lang_id;
        $login=0;
        $user_id=null;
        $user = [];
        
        $validator=  Validator::make($request->all(),[
            'device_id' => ['required'],
            'os_type'=> ['required','string','min:3','max:3'],
            'page_url'=>['required']
        ]);
        if ($validator->fails()) 
            {    
              return ['httpcode'=>400,'status'=>'error','message'=>'invalid','data'=>['errors'=>$validator->messages()]];
            }
            
        if($request->post('access_token')){
            if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
            $login=1;
            $user_id = $user['user_id'];
            
        }

             //main banner
        $main_banner=Banner::where('is_active',1)->where('is_deleted',0)->where('banner_id',1)->orderBy('id','DESC')->get();
        if(count($main_banner)>0)
        {
        foreach($main_banner as $key)
        {   
            foreach(explode(',',$key->media) as $img)
            {
                if($key->media_type=="image")
                {
                $image = config('app.storage_url').'/app/public/banner/'.$img; 
                }
                else
                {
                    $image = $img;
                }
            $m_arrray=['id'=>$key->id,
            'identifier'=>$key->identifier,
            'title'=>$key->get_content($key->title_cnt_id,$lang_id),
            'description'=>$key->get_content($key->desc_cnt_id,$lang_id),
            'alt_text'=>$key->get_content($key->alt_text,$lang_id),
            'button_label'=>$key->get_content($key->btn_label,$lang_id),
            'button_link'=>$key->btn_link,
            'media_type'=>$key->media_type,
            'media'=>$image];
            $main_array[]=$m_arrray;
            }
        }

        
        }
        else
        {
            $main_array[]='';
        }

        //side top banner
        $top_offer_banners=Banner::where('is_active',1)->where('is_deleted',0)->where('banner_id',2)->get();
        if(count($top_offer_banners)>0)
        {
        foreach($top_offer_banners as $key)
        {   
            foreach(explode(',',$key->media) as $img)
            {
                if($key->media_type=="image")
                {
                    $image = config('app.storage_url').'/app/public/banner/'.$img;
                //$image = url('storage/app/public/side_top_banner/'.$img); 
                }
                else
                {
                    $image = $img;
                }
            // $image = url('storage/app/public/banner/thumb'.$key->media); 
            $top_array=['id'=>$key->id,
            'identifier'=>$key->identifier,
            'title'=>$key->get_content($key->title_cnt_id,$lang_id),
            'description'=>$key->get_content($key->desc_cnt_id,$lang_id),
            'media_type'=>$key->media_type,
            'media'=>$image];
            $top_offer_banner[]=$top_array;
            }
        }
        }
        else
        {
            $top_offer_banner[]='';
        }

        //side bottom banner
        $center_offer_banners=Banner::where('is_active',1)->where('is_deleted',0)->where('banner_id',3)->get();
        if(count($center_offer_banners)>0)
        {
        foreach($center_offer_banners as $key)
        {   
            foreach(explode(',',$key->media) as $img)
            {
                if($key->media_type=="image")
                {
                    $image = config('app.storage_url').'/app/public/banner/'.$img;
               // $image = url('storage/app/public/side_bottom_banner/'.$img); 
                }
                else
                {
                    $image = $img;
                }
            //$image = url('storage/app/public/banner/thumb'.$key->media); 
            $bottom_array=['id'=>$key->id,
            'identifier'=>$key->identifier,
            'title'=>$key->get_content($key->title_cnt_id,$lang_id),
            'description'=>$key->get_content($key->desc_cnt_id,$lang_id),
            'media_type'=>$key->media_type,
            'media'=>$image];
            $center_offer_banner[]=$bottom_array;
            }
        }
        }
        else
        {
            $center_offer_banner[]='';
        } 
        
        //bottom offer banner
        $bottom_offer_banners=Banner::where('is_active',1)->where('is_deleted',0)->where('banner_id',4)->get();
        if(count($bottom_offer_banners)>0)
        {
        foreach($bottom_offer_banners as $key)
        {   
            foreach(explode(',',$key->media) as $img)
            {
                if($key->media_type=="image")
                {
                    $image = config('app.storage_url').'/app/public/banner/'.$img;
               // $image = url('storage/app/public/side_bottom_banner/'.$img); 
                }
                else
                {
                    $image = $img;
                }
            //$image = url('storage/app/public/banner/thumb'.$key->media); 
            $bottom_array=['id'=>$key->id,
            'identifier'=>$key->identifier,
            'title'=>$key->get_content($key->title_cnt_id,$lang_id),
            'description'=>$key->get_content($key->desc_cnt_id,$lang_id),
            'media_type'=>$key->media_type,
            'media'=>$image];
            $bottom_offer_banner[]=$bottom_array;
            }
        }
        }
        else
        {
            $bottom_offer_banner[]='';
        }

           return ['httpcode'=>200,'status'=>'success','page'=>'Home','message'=>'Success','data'=>[
       
           'main_banner'=>$main_array,
            'top_offer_banner'=>$top_offer_banner,
            'center_offer_banner'=>$center_offer_banner,
            'bottom_offer_banner'=>$bottom_offer_banner,
          
            ]];

    }


    public function home_featured(Request $request){

         $lang_id=$request->lang_id;
        $login=0;
        $user_id=null;
        $user = [];
        
        $validator=  Validator::make($request->all(),[
            'device_id' => ['required'],
            'os_type'=> ['required','string','min:3','max:3'],
            'page_url'=>['required']
        ]);
        if ($validator->fails()) 
            {    
              return ['httpcode'=>400,'status'=>'error','message'=>'invalid','data'=>['errors'=>$validator->messages()]];
            }
            
        if($request->post('access_token')){
            if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
            $login=1;
            $user_id = $user['user_id'];
            
        }

            //Featured Products
        $products=[];
        $prod_data= Product::where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->where('visible',1)->where('is_featured',1)->orderBy('id','DESC')->take(10)->get();
          //print_r($prod_data);exit;
          if(count($prod_data)>0)
            {
                foreach($prod_data as $row)
                {
                    $store_active = Store::where('is_active',1)->where('seller_id',$row->seller_id)->first();
                   // if($store_active) {
                    //$prd_list['service_status']=$store_active->service_status;    
                    $prd_list['product_id']=$row->id;
                    $prd_list['product_name']=$row->get_content($row->name_cnt_id,$lang_id);
                    $prd_list['category_id']=$row->category_id;
                    if($prd_list['category_id']){
                        $category=Category::where('category_id',$row->category_id)->first();
                        $prd_list['is_rating']=$category->is_rating;
                    }else{
                        
                        $prd_list['is_rating']=0;
                    }
                    $prd_list['category_name']=$row->get_content($row->category->cat_name_cid,$lang_id);
                    $prd_list['subcategory_id']=$row->sub_category_id;
                    $prd_list['subcategory_name']=$row->get_content($row->subCategory->sub_name_cid,$lang_id);
                    if($row->brand_id)
                    {
                    $prd_list['brand_id']=$row->brand_id;
                    $prd_list['brand_name']=$row->get_content(@$row->brand->brand_name_cid,$lang_id);
                    }
                    else
                    {
                    $prd_list['brand_id']='';
                    $prd_list['brand_name']='';   
                    }
                    //$prd_list['seller']=$row->Store($row->seller_id)->store_name;
                    //$prd_list['seller_id']=$row->seller_id;
                     if($row->product_type==1)
                    {
                    $prd_list['product_type']='simple';    
                        
                        $prd_price=$this->get_price($row->id,$type=1,$login);
                        foreach ($prd_price as $item) {
                            foreach ($item as $key => $value) {
                                $prd_list[$key] = $value;
                            } 
                        }
                        
                        
                    }
                    else
                    {
                     $prd_list['product_type']='config'; 
                     
                     $minprice_prd_id=$this->min_price_product($row->id);
                    //dd($minprice_prd_id);
                    $prd_price=$this->get_price($minprice_prd_id,$type=2,$login);
                    foreach ($prd_price as $item) {
                            foreach ($item as $key => $value) {
                                $prd_list[$key] = $value;
                            } 
                        }
                        
                    }    
                    $prd_list['short_description']=$row->get_content($row->short_desc_cnt_id,$lang_id);
                    $prd_list['rating']=$this->get_rates($row->id);
                    $prd_list['total_reviews']=$this->get_rates_count($row->id);
                    $prd_list['image']=$this->get_product_image($row->id); 
                     //ASSOCIATIVE products
                     $associative_prd=[];
                    if($row->product_type == 2)
                    {  
                        $prices = $this->config_product_price($row->id); 
                        $special_ofr_available=$this->get_special_ofr_value($prices,$row->id); 
                        $prd_ass = AssociatProduct::where('prd_id',$row->id)->where('is_deleted',0)->get();
                        if(count($prd_ass)>0)
                        {
                            foreach($prd_ass as $rows)
                            {
                            $product_visibility= Product::where('id',$rows->ass_prd_id)->where('is_active',1)->where('is_deleted',0)->first();
                            if($product_visibility){
                            //$associative_prd[]=$this->ass_related_product($rows->ass_prd_id,$lang);
                            
                                $associative_prd[]=$this->ass_related_product1($product_visibility->id,$special_ofr_available,$lang_id,$login);
                            
                            
                             }
                            }
                        }
                        else
                        {
                            $associative_prd=[];
                        }
                  }
                  else
                  {
                    $associative_prd=[];
                  }     

                  
             $variants_list=[];
                 //Variants List   
                if($associative_prd){
                    //dd(count($associative_prd));
                    $variants_list = array();
                    foreach($associative_prd as $asso_prod){
                        $attr_value=$asso_prod['attr_value'];
                        $attr_name=$asso_prod['attr_name'];
                        $data['pro_id']=$asso_prod['product_id'];
                        if($asso_prod['sub_attributes']){
                        foreach($asso_prod['sub_attributes'] as $row1){
                        
                        if($attr_value){
                        $data['combination']=$attr_value." ".$attr_name ." - ".$row1['attr_value']." ".$row1['attr_name'];
                        }else{
                        $data['variants']=$row1['attr_value']." ".$row1['attr_name'];
                        }
                        $data['stock']=$row1['stock'];
                        $data['is_out_of_stock']=$row1['is_out_of_stock'];
                        $data['out_of_stock_selling']=$row1['out_of_stock_selling'];
                        $data['min_order_qty']=$asso_prod['min_order'];
                        $data['bulk_order_qty']=$asso_prod['bulk_order'];
                        $data['image']=$row1['image'];
                        $price=$this->get_price($data['pro_id'],$type=2,$login);
                        foreach ($price as $item) {
                            foreach ($item as $key => $value) {
                                $data[$key] = $value;
                            } 
                        }   
                        
                        }
                        }else{
                            $data['combination']=$attr_value." ".$attr_name;
                            $data['stock']=$asso_prod['stock'];
                            $data['is_out_of_stock']=$asso_prod['is_out_of_stock'];
                            $data['out_of_stock_selling']=$asso_prod['out_of_stock_selling'];
                            $data['min_order_qty']=$asso_prod['min_order'];
                            $data['bulk_order_qty']=$asso_prod['bulk_order'];
                            $data['image']=$asso_prod['image'];
                            $price=$this->get_price($data['pro_id'],$type=2,$login);
                            foreach ($price as $item) {
                                foreach ($item as $key => $value) {
                                    $data[$key] = $value;
                                } 
                            }
                            
                        }
                    
                    $variants_list[] = $data;
                    }
                }    

                    
                    $prd_list['variants_list']=$variants_list;
                    $products[]=$prd_list;
                    //}
                }

            }
            else
            {
                $products[]='';
            }

           return ['httpcode'=>200,'status'=>'success','page'=>'Home','message'=>'Success','data'=>[
       
          'featured_products'=>$products,
          
            ]];

    }

   public function home_trending_products(Request $request){

         $lang_id=$request->lang_id;
        $login=0;
        $user_id=null;
        $user = [];
        
        $validator=  Validator::make($request->all(),[
            'device_id' => ['required'],
            'os_type'=> ['required','string','min:3','max:3'],
            'page_url'=>['required']
        ]);
        if ($validator->fails()) 
            {    
              return ['httpcode'=>400,'status'=>'error','message'=>'invalid','data'=>['errors'=>$validator->messages()]];
            }
            
        if($request->post('access_token')){
            if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
            $login=1;
            $user_id = $user['user_id'];
            
        }

             
       //Trending Products
        $trending_products=[];
           $trending_product=Product::join("sales_order_items as b","prd_products.id","=","b.parent_id")
            ->select(DB::raw("sum(b.qty) as total"), "prd_products.*")                        
            ->where('b.row_total','>',0)->where('prd_products.visible',1)->where('prd_products.is_approved',1)->where('prd_products.is_active',1)->where('prd_products.is_deleted',0)->orderBy('total','desc')->groupBy('b.parent_id')
            ->skip(0)->take(10)->get();
          if(!empty($trending_product))
            {
                foreach($trending_product as $row)
                {
                   
                    //$prd_list['service_status']=$store_active->service_status;     
                    $trending_prd_list['product_id']=$row->id;
                    $trending_prd_list['product_name']=$this->get_content($row->name_cnt_id,$lang_id);
                    $trending_prd_list['category_id']=$row->category_id;
                    if($trending_prd_list['category_id']){
                        $category=Category::where('category_id',$row->category_id)->first();
                        $trending_prd_list['is_rating']=$category->is_rating;
                    }else{
                        
                        $trending_prd_list['is_rating']=0;
                    }
                    $trending_prd_list['category_name']=$this->get_content($row->category->cat_name_cid,$lang_id);
                    $trending_prd_list['subcategory_id']=$row->sub_category_id;
                    $trending_prd_list['subcategory_name']=$this->get_content($row->subCategory->sub_name_cid,$lang_id);
                    if($row->brand_id)
                    {
                    $trending_prd_list['brand_id']=$row->brand_id;
                    $trending_prd_list['brand_name']=$this->get_content(@$row->brand->brand_name_cid,$lang_id);
                    }
                    else
                    {
                    $trending_prd_list['brand_id']='';
                    $trending_prd_list['brand_name']='';  
                    }
                    if($row->product_type==1)
                    {
                    $trending_prd_list['product_type']='simple';    
                    $trending_prd_list['min_order_qty']=$row->min_order;
                        $trending_prd_list['bulk_order_qty']=$row->bulk_order;  
                        $price=$this->get_price($row->id,$type=1,$login);
                        foreach ($price as $item) {
                                foreach ($item as $key => $value) {
                                    $trending_prd_list[$key] = $value;
                                } 
                            }
                    }
                    else
                    {
                     $trending_prd_list['product_type']='config'; 
                     $trending_prd_list['min_order_qty']="Null";
                     $trending_prd_list['bulk_order_qty']="Null";
                     $minprice_prd_id=$this->min_price_product($row->id);
                    $price=$this->get_price($minprice_prd_id,$type=2,$login);
                    foreach ($price as $item) {
                                foreach ($item as $key => $value) {
                                    $trending_prd_list[$key] = $value;
                                } 
                            }
                        
                    }
                    $trending_prd_list['short_description']=$this->get_content($row->short_desc_cnt_id,$lang_id);
                    $trending_prd_list['tag']=$this->get_product_tag($row->id,$lang_id);
                    $trending_prd_list['rating']=$this->get_rates($row->id);
                    if($trending_prd_list['category_id']){
                        $category=Category::where('category_id',$row->category_id)->first();
                        $trending_prd_list['is_rating']=$category->is_rating;
                    }else{
                        
                        $trending_prd_list['is_rating']=0;
                    }
                    $trending_prd_list['total_reviews']=$this->get_rates_count($row->id);
                    //$prd_list['seller']=$row->Store($row->seller_id)->store_name;
                    //$prd_list['seller_id']=$row->seller_id;
                    $trending_prd_list['image']=$this->get_product_image($row->id); 
                     //ASSOCIATIVE products
                     $associative_prd=[];
                    if($row->product_type == 2)
                    {  
                        $prices = $this->config_product_price($row->id); 
                        $special_ofr_available=$this->get_special_ofr_value($prices,$row->id); 
                        $prd_ass = AssociatProduct::where('prd_id',$row->id)->where('is_deleted',0)->get();
                        if(count($prd_ass)>0)
                        {
                            foreach($prd_ass as $rows)
                            {
                            $product_visibility= Product::where('id',$rows->ass_prd_id)->where('is_active',1)->where('is_deleted',0)->first();
                            if($product_visibility){
                            //$associative_prd[]=$this->ass_related_product($rows->ass_prd_id,$lang);
                            
                                $associative_prd[]=$this->ass_related_product1($product_visibility->id,$special_ofr_available,$lang_id,$login);
                            
                            
                             }
                            }
                        }
                        else
                        {
                            $associative_prd=[];
                        }
                  }
                  else
                  {
                    $associative_prd=[];
                  }     

                  
             $variants_list=[];
                 //Variants List   
                if($associative_prd){
                    //dd(count($associative_prd));
                    $variants_list = array();
                    foreach($associative_prd as $asso_prod){
                        $attr_value=$asso_prod['attr_value'];
                        $attr_name=$asso_prod['attr_name'];
                        $data['pro_id']=$asso_prod['product_id'];
                        if($asso_prod['sub_attributes']){
                        foreach($asso_prod['sub_attributes'] as $row1){
                        
                        if($attr_value){
                        $data['combination']=$attr_value." ".$attr_name ." - ".$row1['attr_value']." ".$row1['attr_name'];
                        }else{
                        $data['variants']=$row1['attr_value']." ".$row1['attr_name'];
                        }
                        $data['stock']=$row1['stock'];
                        $data['is_out_of_stock']=$row1['is_out_of_stock'];
                        $data['out_of_stock_selling']=$row1['out_of_stock_selling'];
                        $data['min_order_qty']=$asso_prod['min_order'];
                        $data['bulk_order_qty']=$asso_prod['bulk_order'];
                        $data['image']=$row1['image'];
                        $price=$this->get_price($data['pro_id'],$type=2,$login);
                        foreach ($price as $item) {
                            foreach ($item as $key => $value) {
                                $data[$key] = $value;
                            } 
                        }   
                        
                        }
                        }else{
                            $data['combination']=$attr_value." ".$attr_name;
                            $data['stock']=$asso_prod['stock'];
                            $data['is_out_of_stock']=$asso_prod['is_out_of_stock'];
                            $data['out_of_stock_selling']=$asso_prod['out_of_stock_selling'];
                            $data['min_order_qty']=$asso_prod['min_order'];
                            $data['bulk_order_qty']=$asso_prod['bulk_order'];
                            $data['image']=$asso_prod['image'];
                            $price=$this->get_price($data['pro_id'],$type=2,$login);
                            foreach ($price as $item) {
                                foreach ($item as $key => $value) {
                                    $data[$key] = $value;
                                } 
                            }
                            
                        }
                    
                    $variants_list[] = $data;
                    }
                }    

                    
            $trending_prd_list['variants_list']=$variants_list;

           // $prd_list['config_prd']=$associative_prd; 
            //$trending_prd_list['offers']=$this->available_offers($row->id,$lang); 
            $trending_products[]=$trending_prd_list;
            }

            }
            else
            {
                $trending_products[]='';
            }
            

            
           return ['httpcode'=>200,'status'=>'success','page'=>'Home','message'=>'Success','data'=>[
       
           'trending_products'=>$trending_products,
          
            ]];

    }


        public function home_daily_deals(Request $request){

         $lang_id=$request->lang_id;
        $login=0;
        $user_id=null;
        $user = [];
        
        $validator=  Validator::make($request->all(),[
            'device_id' => ['required'],
            'os_type'=> ['required','string','min:3','max:3'],
            'page_url'=>['required']
        ]);
        if ($validator->fails()) 
            {    
              return ['httpcode'=>400,'status'=>'error','message'=>'invalid','data'=>['errors'=>$validator->messages()]];
            }
            
        if($request->post('access_token')){
            if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
            $login=1;
            $user_id = $user['user_id'];
            
        }

             
      
            //Daily Deals
        $daily_product=[];
        $daily_deals= Product::where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->where('daily_deals',1)->where('visible',1)->orderBy('id','DESC')->take(10)->get();
          //print_r($prod_data);exit;
          if(count($daily_deals)>0){
                foreach($daily_deals as $row){
                    $store_active = Store::where('is_active',1)->where('seller_id',$row->seller_id)->first();
                   // if($store_active) {
                    //$prd_list['service_status']=$store_active->service_status;    
                    $deals_prd_list['product_id']=$row->id;
                    $deals_prd_list['product_name']=$this->get_content($row->name_cnt_id,$lang_id);
                    $deals_prd_list['category_id']=$row->category_id;
                    if($deals_prd_list['category_id']){
                        $category=Category::where('category_id',$row->category_id)->first();
                        $deals_prd_list['is_rating']=$category->is_rating;
                    }else{
                        
                        $deals_prd_list['is_rating']=0;
                    }
                    $deals_prd_list['category_name']=$row->get_content($row->category->cat_name_cid,$lang_id);
                    $deals_prd_list['subcategory_id']=$row->sub_category_id;
                    $deals_prd_list['subcategory_name']=$row->get_content($row->subCategory->sub_name_cid,$lang_id);
                    if($row->brand_id)
                    {
                    $deals_prd_list['brand_id']=$row->brand_id;
                    $deals_prd_list['brand_name']=$row->get_content(@$row->brand->brand_name_cid,$lang_id);
                    }
                    else
                    {
                    $deals_prd_list['brand_id']='';
                    $deals_prd_list['brand_name']='';   
                    }
                    //$prd_list['seller']=$row->Store($row->seller_id)->store_name;
                    //$prd_list['seller_id']=$row->seller_id;
                     if($row->product_type==1)
                    {
                    $deals_prd_list['product_type']='simple';    
                      
                        $beverage_prd_price=get_crm_price($row,$type=1,$user);   
                        foreach ($beverage_prd_price as $key => $value) {
                        $deals_prd_list[$key] = $value;
                        }   
                    }
                    else
                    {
                     $deals_prd_list['product_type']='config'; 
                     $deals_prd_list['min_order_qty']="Null";
                     $deals_prd_list['bulk_order_qty']="Null";
                    $beverage_prd_price=get_crm_price($row,$type=1,$user);
                        foreach ($beverage_prd_price as $key => $value) {
                            $deals_prd_list[$key] = $value;
                        } 
                        
                    }
                    $deals_prd_list['shock_sale_price'] = $this->shock_sale_price($row->id);    
                    $deals_prd_list['short_description']=$row->get_content($row->short_desc_cnt_id,$lang_id);
                    $deals_prd_list['rating']=$this->get_rates($row->id);
                    $deals_prd_list['total_reviews']=$this->get_rates_count($row->id);
                    $deals_prd_list['image']=$this->get_product_image($row->id); 
    
                    $daily_product[]=$deals_prd_list;
                    //}
                }

            }
            else
            {
                $daily_product[]='';
            }


           return ['httpcode'=>200,'status'=>'success','page'=>'Home','message'=>'Success','data'=>[
       
           'daily_deals'=>$daily_product,
          
            ]];

    }

    public function home_coming_soon(Request $request){

         $lang_id=$request->lang_id;
        $login=0;
        $user_id=null;
        $user = [];
        
        $validator=  Validator::make($request->all(),[
            'device_id' => ['required'],
            'os_type'=> ['required','string','min:3','max:3'],
            'page_url'=>['required']
        ]);
        if ($validator->fails()) 
            {    
              return ['httpcode'=>400,'status'=>'error','message'=>'invalid','data'=>['errors'=>$validator->messages()]];
            }
            
        if($request->post('access_token')){
            if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
            $login=1;
            $user_id = $user['user_id'];
            
        }

             
        //Coming Soon
        $comingsoon_product=[];
        $comingsoon= Product::where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->where('is_comingsoon',1)->where('visible',1)->orderBy('id','DESC')->take(10)->get();
          //print_r($prod_data);exit;
          if(count($comingsoon)>0){
                foreach($comingsoon as $row){
      
                    $coming_prd_list['product_id']=$row->id;
                    $coming_prd_list['product_name']=$this->get_content($row->name_cnt_id,$lang_id);
                    $coming_prd_list['category_id']=$row->category_id;
                    if($coming_prd_list['category_id']){
                        $category=Category::where('category_id',$row->category_id)->first();
                        $coming_prd_list['is_rating']=$category->is_rating;
                    }else{
                        
                        $coming_prd_list['is_rating']=0;
                    }
                    $coming_prd_list['category_name']=$row->get_content($row->category->cat_name_cid,$lang_id);
                    $coming_prd_list['subcategory_id']=$row->sub_category_id;
                    $coming_prd_list['subcategory_name']=$row->get_content($row->subCategory->sub_name_cid,$lang_id);
                    if($row->brand_id)
                    {
                    $coming_prd_list['brand_id']=$row->brand_id;
                    $coming_prd_list['brand_name']=$row->get_content(@$row->brand->brand_name_cid,$lang_id);
                    }
                    else
                    {
                    $coming_prd_list['brand_id']='';
                    $coming_prd_list['brand_name']='';   
                    }
                    //$prd_list['seller']=$row->Store($row->seller_id)->store_name;
                    //$prd_list['seller_id']=$row->seller_id;
                     if($row->product_type==1)
                    {
                    $coming_prd_list['product_type']='simple';    
                        
                        $beverage_prd_price=get_crm_price($row,$type=1,$user);
                        foreach ($beverage_prd_price as $key => $value) {
                            $coming_prd_list[$key] = $value;
                        } 
                        
                    }
                    else
                    {
                     $coming_prd_list['product_type']='config'; 
                     $coming_prd_list['min_order_qty']="Null";
                     $coming_prd_list['bulk_order_qty']="Null";
                     $beverage_prd_price=get_crm_price($row,$type=1,$user);
                        foreach ($beverage_prd_price as $key => $value) {
                            $coming_prd_list[$key] = $value;
                        } 

                        
                    }
                    $coming_prd_list['shock_sale_price'] = $this->shock_sale_price($row->id);    
                    $coming_prd_list['short_description']=$row->get_content($row->short_desc_cnt_id,$lang_id);
                    $coming_prd_list['rating']=$this->get_rates($row->id);
                    $coming_prd_list['total_reviews']=$this->get_rates_count($row->id);
                    $coming_prd_list['image']=$this->get_product_image($row->id); 
   

                    $comingsoon_product[]=$coming_prd_list;
                    //}
                }

            }
            else
            {
                $comingsoon_product[]='';
            }

           return ['httpcode'=>200,'status'=>'success','page'=>'Home','message'=>'Success','data'=>[
       
           'comingsoon_products'=>$comingsoon_product,
          
            ]];

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
				 if($parent_product){
				 $parent_prdid=$parent_product->prd_id;
				 }
				 else{
				 $parent_prdid=0;
				 }
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
				$offer['actual_price']="$Price->price";
			}else{
				$offer['actual_price']="NULL";
			}
            //$offer['url']=url('api/customer/shock-sale');
           
                $actual_price=$prod_data->prdPrice->price;
				if($shock->discount_type=="amount"){
					$offer['offer']=getCurrency()->name." ".$shock->discount_value." Off";
					$discount_value = $shock->discount_value;
					$unit_price = $actual_price-$discount_value;
					$round= number_format($unit_price, 2);
					if($login==1){
						$offer['offer_price']=$round;
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
			/*}else{
					$actual_price=$this->config_product_price($prod_data->id);
					if($shock->discount_type=="amount"){
                        $offer['offer']=getCurrency()->name." ".$shock->discount_value." Off";
                        $discount_value = $shock->discount_value;
                        $unit_price = $actual_price-$discount_value;
                        $offer['price']= $unit_price;
                    }
                    else{
                        $offer['offer']=$shock->discount_value."% Off";
                        $per=$shock->discount_value/100;
                        $per_value = (float)$actual_price*(float)$per;
                        $discount=(float)$actual_price-(float)$per_value;
                        $round= number_format($discount, 2);
                        $offer['price']=$discount;
                    } 
            }*/
            //$offer_list[]=$offer;
        }else if($specialOffer){
			
			
			if($specialOffer->quantity_limit >1){
			$offer['offer_name']= false ;
			$offer['discount_type']= false; 
			$offer['offer']= false; 
            if($login==1){
				$offer['actual_price']='"'.number_format($Price->price, 2).'"';
			}else{
				$offer['actual_price']="NULL";
			}
            $offer['offer_price']=false;
				
			}else{
			$offer['offer_name']= 'Special Offer'; 
			$offer['discount_type']= $specialOffer->discount_type;   
			$discount_val = $specialOffer->discount_value;
			$discount_typ = $specialOffer->discount_type;
			if($login==1){
				$offer['actual_price']='"'.number_format($Price->price, 2).'"';
			}else{
				$offer['actual_price']="NULL";
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
						$offer['offer_price']=number_format($offer['offer_price'], 2);
					}else{
						$offer['offer_price']=NULL;
					}
			}
			else {
				if($login==1){
							$offer['offer_price']=number_format($Price->price, 2);
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
				    	$offer['actual_price']= '"'.number_format($Price->price, 2).'"';
				    	}else{ 	$offer['actual_price']=  "NULL"; }
			}else{
				$offer['actual_price']="NULL";
			}
		if($login==1){
						$offer['offer_price']=number_format($SalesPrice->sale_price, 2);
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
				    	$offer['actual_price']= '"'.number_format($Price->price, 2).'"';
				    	}else{ 	$offer['actual_price']=  "NULL"; }
			}else{
				$offer['actual_price']="NULL";
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

    //Category and subcategory List separate
       public function category_subcategory(Request $request) 
       {   
			$cat_subcat=[];
			$lang_id=$request->lang_id;
			$category_data= Category::where('is_active',1)->where('is_deleted',0)->orderBy('sort_order')->get();
           
		   foreach($category_data as $cat)
            {  $image = url('storage/app/public/category/'.$cat->image);
                // $prod_exist = Product::where('category_id',$cat->category_id)->where('is_active',1)->where('is_deleted',0)->where('visible',1)->where('is_approved',1)->first();
                // if($prod_exist)
                // {
                //     $store_active = Store::where('is_active',1)->where('seller_id',$prod_exist->seller_id)->first();
                //     if($store_active)
                //     {
                $cat_list['category_id']=$cat->category_id;
                
				$cat_list['category_name']=$cat->get_content($cat->cat_name_cid,$lang_id);
                 $cat_list['is_rating']=$cat->is_rating;
				$cat_list['category_image']=$image;
                $cat_list['subcategory']=$this->get_subcategory($cat->category_id,$lang_id);  
                
               
                $cat_subcat[]=$cat_list;
                //     }
                // }
            }
            
            // $order = array_column($cat_subcat, 'category_name');
            // array_multisort($order, SORT_ASC, $cat_subcat);

            return ['httpcode'=>200,'status'=>'success','page'=>'Category and subcategory list','message'=>'Success','data'=>['cat_subcat'=>$cat_subcat]];
       }     
       
       
    //All Brand List
   
   public function brands(Request $request)  
   {
    $lang_id=$request->lang_id;
    $brandlist=[];
    $brand  = Brand::where('is_active',1)->where('is_deleted',0)->get();
   
	if(count($brand)>0)
    {
        foreach($brand as $row)
        {
        //$prod_exist = Product::where('brand_id',$row->id)->where('is_active',1)->where('is_deleted',0)->where('visible',1)->where('is_approved',1)->first();
        // if($prod_exist){
               
            $b_list['brand_id']=$row->id;
            $b_list['brand_name']=$this->get_content($row->brand_name_cid,$lang_id);
            $b_list['brand_description']=$this->get_content($row->brand_desc_cid,$lang_id); 
if($row->image){
                    $b_list['brand_image']=config('app.storage_url').'/app/public/brands/'.$row->image;
					}else{
					    $b_list['brand_image']="";
					}
            $brandlist[]=$b_list;
                   
            //}
         }
         asort($brandlist);
         return ['httpcode'=>200,'status'=>'success','page'=>'Brand','message'=>'Success','data'=>['brands'=>$brandlist]];
    }

   }  
   
   public function get_subcategory_list(Request $request){
       
       $lang_id=$request->lang_id;
       $login=0;
        
        $validator=  Validator::make($request->all(),[
            'category_id' => ['required']
        ]);
        if ($validator->fails()) 
            {    
              return ['httpcode'=>400,'status'=>'error','message'=>'invalid','data'=>['errors'=>$validator->messages()]];
            }
        $data     =   [];
        
        $subcat       =   Subcategory::where('category_id',$request->category_id)->where('is_active',1)->get(['subcategory_id','sub_name_cid','image']); 
            
			if($subcat)   {   foreach($subcat as $k=>$row){ 
                if($row->image!='')
                {
                $image = url('storage/app/public/subcategory/'.$row->image);
                }
                else
                {
                    $image='';
                }
                $val['id']    =   $row->subcategory_id;
                //dd
				$val['subcategory_name']   =  $row->get_content($row->sub_name_cid,$lang_id);
               
				$val['subcategory_image']  =  $image;
                $data[]       =   $val;
				
            } }
            else{ $data     =   []; } return ['httpcode'=>200,'status'=>'success','message'=>'Success','data'=>['subcategory'=>$data]];
        
    }
  public function shocking_sale(Request $request)
   {
    $lang_id=$request->lang_id;
		$category    = $request->category_id;
        $subcategory = $request->subcategory_id;
        $brand       = $request->brand_id;
        $max_price   = $request->max_price;
        $min_price   = $request->min_price;
        $latest      = $request->latest;
        $low_to_high = $request->low_to_high;
        $high_to_low = $request->high_to_low;
       if($category){
        $category=(explode(",",$category));
		}
		$offset = $request->offset;
        $limit = $request->limit;
         if($request->offset==''){
			$offset =0; 
		 }
		 if($request->limit==''){
			$limit =10; 
		 }
    
        $login=0;
        $user_id=null;
        $user = [];
        $product_count=0;
        $validator=  Validator::make($request->all(),[
            'device_id' => ['required'],
            'os_type'=> ['required','string','min:3','max:3'],
            'page_url'=>['required']
        ]);
        if ($validator->fails()) 
            {    
              return ['httpcode'=>400,'status'=>'error','message'=>'invalid','data'=>['errors'=>$validator->messages()]];
            }
            
        if($request->post('access_token')){
            if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
            $login=1;
        }

            $shock_data=[];
            $current_date=Carbon::now();
            $shock = PrdShock_Sale::where('is_active','1')->where('is_deleted','0')->whereDate('start_time','<=',$current_date)->whereDate('end_time','>',$current_date)->skip($offset)->take($limit)->get(); 
            $shock_page_data=[];
			$count=1;
            if(count($shock)>0)
            {
                $usr_visit =UserVisit::create([
                            'org_id' =>1,
                            'device_id'=>$request->device_id,
                            'is_login'=>$login,
                            'os'=>$request->os_type,
                            'url'=>$request->page_url,
                            'visited_on'=>date("Y-m-d H:i:s"),
                            'created_at'=>date("Y-m-d H:i:s"),
                            'updated_at'=>date("Y-m-d H:i:s")]);
             $shock_time=[];  
			 
			   
            foreach($shock as $rows)
            {
				$shock_data=[];
				$variants_list = [];
				$associative_prd=[];
                $shockProducts=PrdShockingSaleProduct::where('shock_sale_id',$rows->id)->where('is_active',1)->where('is_deleted',0)->get();
				$shock_time['shock_sale_id']=$rows->id;
                $shock_time['shock_sale_title']=$this->get_content($rows->title_cid,$lang_id);
                $shock_time['start_time']=$rows->start_time;
                $shock_time['end_time']=$rows->end_time;
				
				
                if($shockProducts){
					
				foreach($shockProducts as $shock_rows)
                {
					$query = Product::query();
					$query->select('prd_products.*');
                    $query ->where('id',$shock_rows->prd_id)->where('is_active',1)->where('is_deleted',0)->where('visible',1)->where('is_approved',1);
					$query->when($category, function ($q,$category) {
					return $q->wherein('prd_products.category_id', $category);
					});
					$valid_prduct = $query->groupBy('prd_products.id')->first();
                    if($valid_prduct){
						$product_count = $count; 
						$count++; 
						$shock_list['shock_sale_id']=$shock_rows->shock_sale_id;
						$shock_list['shock_sale_title']=$this->get_content($shock_rows->title_cid,$lang_id);
						$shock_list['product_id']=$shock_rows->prd_id;
						$shock_list['product_name']=$valid_prduct->name;
						$shock_list['category_name']=$this->get_content($valid_prduct->category->cat_name_cid,$lang_id);
						$shock_list['subcategory_id']=$valid_prduct->sub_category_id;
						$shock_list['subcategory_name']=$this->get_content($valid_prduct->subCategory->sub_name_cid,$lang_id);
						if($valid_prduct->brand_id)
						{
						$prd_list['brand_id']=$valid_prduct->brand_id;
						$prd_list['brand_name']=$this->get_content($valid_prduct->brand->brand_name_cid,$lang_id);
						}
						else
						{
						$prd_list['brand_id']='';
						$prd_list['brand_name']='';
						}
                
               if($valid_prduct->product_type==1)
                    {
                    $shock_list['product_type']='simple';    
					$shock_list['min_order_qty']=$valid_prduct->min_order;
					$shock_list['bulk_order_qty']=$valid_prduct->bulk_order;	
						$price=$this->get_price($shock_rows->prd_id,$type=1,$login=1);
						foreach ($price as $item) {
								foreach ($item as $key => $value) {
									$shock_list[$key] = $value;
								} 
							}
					}
                    else
                    {
                     $shock_list['product_type']='config'; 
                     
					$minprice_prd_id=$this->min_price_product($shock_rows->prd_id);
					$price=$this->get_price($minprice_prd_id,$type=2,$login);
                    
					foreach ($price as $item) {
								foreach ($item as $key => $value) {
									$shock_list[$key] = $value;
								} 
							}
					}	
						if($valid_prduct->product_type == 2)
                    {  
                        $prices = $this->config_product_price($valid_prduct->id); 
                        $special_ofr_available=$this->get_special_ofr_value($prices,$valid_prduct->id); 
                        $prd_ass = AssociatProduct::where('prd_id',$valid_prduct->id)->where('is_deleted',0)->get();
                        //dd($prd_ass);
						if(count($prd_ass)>0)
                        {
                            foreach($prd_ass as $rows)
                            {
                            $product_visibility= Product::where('id',$rows->ass_prd_id)->where('is_active',1)->where('is_deleted',0)->first();
                            if($product_visibility){
                            
                                $associative_prd[]=$this->ass_related_product1($product_visibility->id,$special_ofr_available,$lang_id,$login);
                            
                            
                             }
                            }
                        }
                        else
                        {
                            $associative_prd=[];
                        }
                  }
                  else
                  {
                    $associative_prd=[];
                  }	
						
				$variants_list=[];		                
				if($associative_prd){
					$variants_list = array();
					foreach($associative_prd as $asso_prod){
						$attr_value=$asso_prod['attr_value'];
						$attr_name=$asso_prod['attr_name'];
						$data['pro_id']=$asso_prod['product_id'];
						foreach($asso_prod['sub_attributes'] as $row1){
						
						if($attr_value){
						$data['combination']=$attr_value." ".$attr_name ." - ".$row1['attr_value']." ".$row1['attr_name'];
						}else{
						$data['variants']=$row1['attr_value']." ".$row1['attr_name'];
						}
						$data['stock']=$row1['stock'];
						$data['is_out_of_stock']=$row1['is_out_of_stock'];
						$data['out_of_stock_selling']=$row1['out_of_stock_selling'];
						$data['min_order_qty']=$asso_prod['min_order'];
						$data['bulk_order_qty']=$asso_prod['bulk_order'];
						$data['image']=$row1['image'];
						$price=$this->get_price($data['pro_id'],$type=2,$login);
						foreach ($price as $item) {
							foreach ($item as $key => $value) {
								$data[$key] = $value;
							} 
						}	
						
						}
					
					$variants_list[] = $data;
					}
				}    

                    
				$shock_list['variants_list']=$variants_list;
                $shock_list['rating']=$this->get_rates($shock_rows->prd_id);
                $shock_list['total_reviews']=$this->get_rates_count($shock_rows->prd_id);
                $shock_list['image']=$this->get_product_image($shock_rows->prd_id);
                
                    
                    $shock_data[]=$shock_list;
                    }
                
                    $order = array_column($shock_data, 'rating');

                    array_multisort($order, SORT_DESC, $shock_data);
                    $shock_page = $this->paginate($shock_data);
                    $shock_page_data = $shock_page->items();
					$shock_time['products']	=$shock_page_data;
					
				
					
				  }
                }
				
                $shock_datas[]=$shock_time;
				
                }
				 
            }
            else
            {
                $shock_time=[];
				$product_count=0;
            }
            
          // dd($count);

            return ['httpcode'=>200,'status'=>'success','page'=>'Shocking Sale','message'=>'Success','data'=>['shock_sale'=>$shock_datas,'product count'=>$product_count]];
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
						
						$atr_inn['image']=$this->get_product_image($prd_id);
						
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
   //shocking sale all product
  public function shocking_sale_products(Request $request)
   {
    $lang_id=$request->lang_id;
    
        $category    = $request->category_id;
        $subcategory = $request->subcategory_id;
        $brand       = $request->brand_id;
        $max_price   = $request->max_price;
        $min_price   = $request->min_price;
        $latest      = $request->latest;
        $low_to_high = $request->low_to_high;
        $high_to_low = $request->high_to_low;
    
    $login=0;
        $user_id=null;
        $user = [];
        $validator=  Validator::make($request->all(),[
            'device_id' => ['required'],
            'os_type'=> ['required','string','min:3','max:3'],
            'page_url'=>['required']
        ]);
        if ($validator->fails()) 
            {    
              return ['httpcode'=>400,'status'=>'error','message'=>'invalid','data'=>['errors'=>$validator->messages()]];
            }
            
        if($request->post('access_token')){
            if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
            $login=1;
        }

           //Shocking sales
            $shock_data=[];
            $current_date=Carbon::now();
            $shock = PrdShock_Sale::join('prd_shock_sale_products','prd_shock_sale.id','=','prd_shock_sale_products.shock_sale_id');
            
            //  $shock=   $shock->join('prd_prices', 'prd_prices.prd_id', '=', 'prd_shock_sale_products.prd_id')->where('prd_prices.is_deleted',0);
            
            // if($max_price!='' && $min_price!='')
            // {
            //     $shock = $shock->whereBetween('prd_prices.price', [$min_price, $max_price]);
            // }
            $shock=$shock->where('prd_shock_sale.is_active',1)->where('prd_shock_sale.is_deleted',0)->whereDate('prd_shock_sale.start_time','<=',$current_date)->whereDate('prd_shock_sale.end_time','>',$current_date)
            ->where('prd_shock_sale_products.is_active',1)->where('prd_shock_sale_products.is_deleted',0)
            ->select('prd_shock_sale.*','prd_shock_sale_products.seller_id','prd_shock_sale_products.prd_id as shock_prd_id');
            
            // if($low_to_high==1)
            // {   return "low";die;
            //     $shock = $shock->orderBy('prd_prices.price', 'ASC');
            // }
            // if($high_to_low==1)
            // { return "high";die;
            //     $shock = $shock->orderBy('prd_prices.price', 'DESC');
            // }
            if($latest==1)
            { 
                $shock = $shock->orderBy('prd_shock_sale.created_at','DESC');
            }
            
            $shock= $shock->get();
            $shock_page_data=[];
            if(count($shock)>0)
            {
                $usr_visit =UserVisit::create([
                            'org_id' =>1,
                            'device_id'=>$request->device_id,
                            'is_login'=>$login,
                            'os'=>$request->os_type,
                            'url'=>$request->page_url,
                            'visited_on'=>date("Y-m-d H:i:s"),
                            'created_at'=>date("Y-m-d H:i:s"),
                            'updated_at'=>date("Y-m-d H:i:s")]);
                            
            foreach($shock as $rows)
            {
                
                foreach(explode(',',$rows->shock_prd_id) as $shock_rows)
                {
                    $valid_prduct = Product::where('id',$shock_rows)->where('is_active',1)->where('is_deleted',0)->where('visible',1)->where('is_approved',1)->when($category, function ($q,$category) {
                        return $q->where('category_id', $category);
                    })
                    ->when($subcategory, function ($q,$subcategory) {
                        return $q->where('sub_category_id', $subcategory);
                    })
                    ->when($brand, function ($q,$brand) {
                        return $q->where('brand_id', $brand);
                    })
                    ->when($latest == 1, function ($q,$latest) {
                        return $q->orderBy('created_at','DESC');
                    })->first();
                    if($valid_prduct){
                    $category_cid = $rows->category_data($rows->product_data($shock_rows)->category_id)->cat_name_cid;
                    $subcategory_cid = $rows->subcategory_data($rows->product_data($shock_rows)->sub_category_id)->sub_name_cid;
                    if($rows->product_data($shock_rows)->brand_id)
                    {
                     $brand_cid = $rows->brand_data($rows->product_data($shock_rows)->brand_id)->brand_name_cid;
                    }
                    if($valid_prduct->product_type==1)
                    {
                        $a_price =$rows->price_data($shock_rows)->price;
                    }
                    else
                    {
                        $a_price =$this->config_product_price($shock_rows);
                    }
                    $actual_price = $a_price;
                $shock_list['shock_sale_id']=$rows->id;
                $shock_list['shock_sale_title']=$this->get_content($rows->title_cid,$lang_id);
                $shock_list['product_id']=$shock_rows;
                $shock_list['product_name']=$this->get_content($rows->product_data($shock_rows)->name_cnt_id,$lang_id);
                $shock_list['category']=$this->get_content($category_cid,$lang_id);
                $shock_list['subcategory']=$this->get_content($subcategory_cid,$lang_id);
                if($rows->product_data($shock_rows)->brand_id)
                    {
                $shock_list['brand']=$this->get_content($brand_cid,$lang_id);
                    }
                else
                {
                 $shock_list['brand']=''; 
                }
                $shock_time['start_time']=$rows->start_time;
                $shock_time['end_time']=$rows->end_time;
                $shock_list['actual_price']=$actual_price;
                //$actual_price=$rows->Product->prdPrice->price;
                if($valid_prduct->product_type==1)
                    {
                if($rows->discount_type=="percentage")
                {
                    $shock_list['offer']=$rows->discount_value."% OFF";
                    $per=($rows->discount_value/100)*$actual_price;
                    $discount=(float)$actual_price-(float)$per;
                    $round= round($discount);
                    $shock_list['offer_price']=$round;
                }
                else
                {
                    $shock_list['offer']=$rows->discount_value." OFF";
                    $ofr_price=(float)$actual_price-(float)$rows->discount_value;
                    $shock_list['offer_price']=round($ofr_price);
                }
                    }
                    
                    else
                    {
                       if($rows->discount_type=="percentage")
                {
                    $shock_list['offer']=$rows->discount_value."% OFF";
                    $per=($rows->discount_value/100)*$actual_price;
                    $shock_list['offer_price']=$a_price-$per;
                }
                else
                {
                    $shock_list['offer']=$rows->discount_value." OFF";
                    $shock_list['offer_price']=$a_price-$rows->discount_value;
                } 
                    }
                $shock_list['rating']=$this->get_rates($shock_rows);
                $shock_list['total_reviews']=$this->get_rates_count($shock_rows);
                $shock_list['image']=$this->get_product_image($shock_rows);
                
                    $shock_time[]=$shock_time;
                    $shock_data[]=$shock_list;
                    }//end valid
                  }
                  
            
                }
                    $order = array_column($shock_data, 'rating');

                    array_multisort($order, SORT_DESC, $shock_data);
                    $shock_page = $this->paginate($shock_data);
                    $shock_page_data = $shock_page->items();
            }
            else
            {
                $shock_data=[];
            }
            
            $tot_count = count($shock_data);

            return ['httpcode'=>200,'status'=>'success','message'=>'Success','data'=>['shock_sale'=>$shock_page_data,'total_products'=>$tot_count]];
   }   
   
   public function paginate($items, $perPage = 12, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function filter_on_search(Request $request)
    {
        //category
        $category_data= Category::where('is_active',1)->where('is_deleted',0)->get();
        $all[]=['id'=>'','category_name'=>"All"];
       foreach($category_data as $key)
        {   
            $category_array=['id'=>$key->category_id,
            'category_name'=>$key->get_content($key->cat_name_cid,$request->lang_id)];
            $categories[]=$category_array;
        }
        $filter=array_merge($all,$categories);
        return ['httpcode'=>200,'status'=>'success','message'=>'Success','data'=>$filter];
    }

    //SEARCH PRODUCT
    public function product_search(Request $request)
    {
        if($request->post('access_token')){
            if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
            $user_id = $user['user_id'];
        }
        else
        {
            $user_id = null;
        }
        $products=[];
        $data_product=[];
        $data_category=[];
        $data_subcategory=[];
        $data_brand=[];
        $data_tag=[];

        
        //prediction

        // $predict1= DB::table('cms_content')
        // ->join('prd_products', 'prd_products.name_cnt_id', '=', 'cms_content.cnt_id')
        // ->where('cms_content.content', 'Like', '%' . $request->keyword . '%')
        // ->where('prd_products.is_active',1)->where('prd_products.is_deleted',0)
        //         ->get(['cms_content.content']);
        // $predict2= DB::table('cms_content')
        // ->join('category', 'category.cat_name_cid', '=', 'cms_content.cnt_id')
        // ->where('cms_content.content', 'Like', '%' . $request->keyword . '%')
        // ->where('category.is_active',1)->where('category.is_deleted',0)
        //         ->get(['cms_content.content']);   
        // $predict3= DB::table('cms_content')
        // ->join('subcategory', 'subcategory.sub_name_cid', '=', 'cms_content.cnt_id')
        // ->where('cms_content.content', 'Like', '%' . $request->keyword . '%')
        // ->where('subcategory.is_active',1)->where('subcategory.is_deleted',0)
        //         ->get(['cms_content.content']); 
        // $predict4= DB::table('cms_content')
        // ->join('prod_tag', 'prod_tag.tag_name_cid', '=', 'cms_content.cnt_id')
        // ->where('cms_content.content', 'Like', '%' . $request->keyword . '%')
        // ->where('prod_tag.is_active',1)->where('prod_tag.is_deleted',0)
        //         ->get(['cms_content.content']);  
        // $predict5= DB::table('cms_content')
        // ->join('prod_brand', 'prod_brand.brand_name_cid', '=', 'cms_content.cnt_id')
        // ->where('cms_content.content', 'Like', '%' . $request->keyword . '%')
        // ->where('prod_brand.is_active',1)->where('prod_brand.is_deleted',0)
        //         ->get(['cms_content.content']);    

        // $predict111=array_merge((array)$predict1,(array)$predict2,(array)$predict3,(array)$predict4, (array)$predict5);             
        // $predict=array_filter($predict111);
       


        $filter_id =$request->category_id;
        
        $products_p = [];
        $products_c=[];
        $products_s=[];
        $products_b=[];
        $products_t=[];
        $product_collect =[];
        if($filter_id!='')
        {
        $data_product = DB::table('cms_content')
        ->leftjoin('prd_products',function($q){
            $q->on('prd_products.name_cnt_id' ,'cms_content.cnt_id');
        })->select(['prd_products.id'])
        ->where('prd_products.category_id',$filter_id)
        ->where('cms_content.content', 'Like', '%' . $request->keyword . '%')
        ->get()->unique();
        }

        else{

         $data_category = DB::table('cms_content')
        ->leftjoin('category',function($q){
            $q->on('category.cat_name_cid' ,'cms_content.cnt_id');
        })->select(['category.category_id'])
        ->where('cms_content.content', 'Like', '%' . $request->keyword . '%')
        ->get()->unique();   

         $data_product = DB::table('cms_content')
        ->leftjoin('prd_products',function($q){
            $q->on('prd_products.name_cnt_id' ,'cms_content.cnt_id');
        })->select(['prd_products.id'])
        ->where('cms_content.content', 'Like', '%' . $request->keyword . '%')
        ->get()->unique();



        $data_subcategory = DB::table('cms_content')
        ->leftjoin('subcategory',function($q){
            $q->on('subcategory.sub_name_cid' ,'cms_content.cnt_id');
        })->select(['subcategory.subcategory_id'])
        ->where('cms_content.content', 'Like', '%' . $request->keyword . '%')
        ->get()->unique();

        $data_brand = DB::table('cms_content')
        ->leftjoin('prd_brand',function($q){
            $q->on('prd_brand.brand_name_cid' ,'cms_content.cnt_id');
        })->select(['prd_brand.id'])
        ->where('cms_content.content', 'Like', '%' . $request->keyword . '%')
        ->get()->unique();

        $data_tag = DB::table('cms_content')
        ->leftjoin('prod_tag',function($q){
            $q->on('prod_tag.tag_name_cid' ,'cms_content.cnt_id');
        })->select(['prod_tag.id'])
        ->where('cms_content.content', 'Like', '%' . $request->keyword . '%')
        ->get()->unique();
     }
      
        if(count($data_product)>0)
        {
            foreach($data_product as $key)
            {
                $arrays[]=$this->get_search_products($key->id,$category='',$subcategory='',$brand='',$tag='',$request->lang_id,$request->keyword,$user_id);
                if($arrays!='' && !empty($arrays))
                {   $products_p=array_filter($arrays, function($v){ 
 return !is_null($v) && $v !== ''; 
});
                    $products_p=array_values($products_p);
                }
            }
        }
       if(count($data_category)>0 && !empty($data_category))
       {
            foreach($data_category as $row)
            {
                if($row->category_id!='')
                {
                $arrays[]=$this->get_search_products($prd_id='',$row->category_id,$subcategory='',$brand='',$tag='',$request->lang_id,$request->keyword,$user_id);
                if($arrays!='' && !empty($arrays))
                {   
                    $products_c=array_filter($arrays, function($v){ 
 return !is_null($v) && $v !== ''; 
});
                   $products_c=array_values($products_c);
                    foreach($products_c as $fr)
                    {
                        $products_c=$fr;
                    }
                }
               }
            }
        }
        

        if(count($data_subcategory)>0 && !empty($data_category))
       {
            foreach($data_subcategory as $row)
            {
                if($row->subcategory_id)
                {
                $arrays[]=$this->get_search_products($prd_id='',$category='',$row->subcategory_id,$brand='',$tag='',$request->lang_id,$request->keyword,$user_id);
                if($arrays!=null && !empty($arrays))
                {
                    $products_s=array_filter($arrays, function($v){ 
 return !is_null($v) && $v !== ''; 
});
                    $products_s=array_values($products_s);
                    foreach($products_s as $fr)
                    {
                        $products_s=$fr;
                    }
                }
               }
            }
        }
       //return $products_c;die;

        if(count($data_brand)>0)
       {
            foreach($data_brand as $row)
            {
                if($row->id)
                {
                $arrays[]=$this->get_search_products($prd_id='',$category='',$subcategory='',$brand=$row->id,$tag='',$request->lang_id,$request->keyword,$user_id);
                if($arrays!='' && !empty($arrays))
                {
                    $products_b=array_filter($arrays, function($v){ 
 return !is_null($v) && $v !== ''; 
});
                    $products_b=array_values($products_b);
                    foreach($products_b as $fr)
                    {
                        $products_b=$fr;
                    }
                }
               }

            }
        }

        if(count($data_tag)>0)
       {
            foreach($data_tag as $row)
            {
                if($row->id!='')
                {
                $arrays[]=$this->get_search_products($prd_id='',$category='',$subcategory='',$brand='',$row->id,$request->lang_id,$request->keyword,$user_id);
                if($arrays!='' && !empty($arrays))
                {
                    $products_t=array_filter($arrays, function($v){ 
 return !is_null($v) && $v !== ''; 
});
                   $products_t=array_values($products_t);
                    foreach($products_t as $fr)
                    {
                        $products_t=$fr;
                    }
                }
               }
            }
        }
    //      if(count($products_p)>0 || count($products_c)>0 || count($products_s)>0 || count($products_b)>0 || count($products_t)>0){
    //  // $product_collect = $products_p->concat($products_c)->concat($products_s)->concat($products_b)->concat($products_t);
    //  $product_collect = $products_p->collect($products_c)->collect($products_s)->collect($products_b)->collect($products_t);
    //     //$product_collect = $products_p;
        
    //      }
         
         if(count($products_p)>0)
         {
             $product_collect=$products_p;
         }
         else if(count($products_c)>0)
         {
             $product_collect=$products_c;
         }
         else if(count($products_s)>0)
         {
             $product_collect=$products_s;
         }
         else if(count($products_b)>0)
         {
             $product_collect=$products_b;
         }
         else 
         {
             $product_collect=$products_t;
         }
       
       $products_page = $this->paginate($product_collect);
       $products_page_data = $products_page->items();
       $products_page_data=array_unique($products_page_data, SORT_REGULAR);
       $products_page_data=array_filter($products_page_data);
       $no_of_prds= count($products_page_data);
        
        return ['httpcode'=>200,'status'=>'success','message'=>'search','data'=>['keyword'=>$request->keyword,'no_of_products'=>$no_of_prds,'products'=>$products_page_data]];

   }


//Get products
   function get_search_products($prd_id,$category,$subcategory,$brand,$tag,$lang,$keyword,$user_id)
   {
    $prod_data=[];
    if($prd_id!='')
    {
    $prod_data= Product::where('is_active',1)->where('is_deleted',0)->where('visible',1)->where('id',$prd_id)->where('is_approved',1)->get();
        foreach($prod_data as $key)
        {
            $store_active = Store::where('is_active',1)->where('seller_id',$key->seller_id)->first();
                    if($store_active)
                    {
            $create_directory = PrdSearch_Directory::create(['type_name' =>'product',
                'type_id'    => $key->id,
                'keyword'    => $keyword,
                'user_id'    => $user_id,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")]);
                    }
        }
    }
    if($category!='')
    {
     $prod_data= Product::where('is_active',1)->where('is_deleted',0)->where('visible',1)->where('is_approved',1)->where('category_id',$category)->get(); 
     foreach($prod_data as $key)
        {
            $store_active = Store::where('is_active',1)->where('seller_id',$key->seller_id)->first();
                    if($store_active)
                    {
            $create_directory = PrdSearch_Directory::create(['type_name' =>'category',
                'type_id'    => $key->category_id,
                'keyword'    => $keyword,
                'user_id'    => $user_id,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")]);
                    }
        }
    }
    if($subcategory!='')
    {
     $prod_data= Product::where('is_active',1)->where('is_deleted',0)->where('visible',1)->where('is_approved',1)->where('sub_category_id',$subcategory)->get(); 
     foreach($prod_data as $key)
        {
            $store_active = Store::where('is_active',1)->where('seller_id',$key->seller_id)->first();
                    if($store_active)
                    {
            $create_directory = PrdSearch_Directory::create(['type_name' =>'subcategory',
                'type_id'    => $key->sub_category_id,
                'keyword'    => $keyword,
                'user_id'    => $user_id,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")]);
                    }
        }

    }
    if($brand!='')
    {
     $prod_data= Product::where('is_active',1)->where('is_deleted',0)->where('visible',1)->where('is_approved',1)->where('brand_id',$brand)->get(); 
     foreach($prod_data as $key)
        {
            $store_active = Store::where('is_active',1)->where('seller_id',$key->seller_id)->first();
                    if($store_active)
                    {
            $create_directory = PrdSearch_Directory::create(['type_name' =>'brand',
                'type_id'    => $key->brand_id,
                'keyword'    => $keyword,
                'user_id'    => $user_id,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")]);
                    }
        }
    }
    if($tag!='')
    {
     $prod_tags= PrdAssignedTag::where('is_deleted',0)->where('tag_id',$tag)->first();
     if($prod_tags)
     {
     $prod_data= Product::where('is_active',1)->where('is_deleted',0)->where('visible',1)->where('is_approved',1)->where('id',$prod_tags->prd_id)->get(); 
     }
   

    }
            if(count($prod_data)>0)
            {
                foreach($prod_data as $row)
                { 
                    
                    $prd_list['id']=$row->id;
                    $prd_list['product_name']=$row->get_content($row->name_cnt_id,$lang);
                    $prd_list['category_id']=$row->category_id;
                    $prd_list['category_name']=$row->get_content($row->category->cat_name_cid,$lang);
                    $prd_list['subcategory_id']=$row->sub_category_id;
                    $prd_list['subcategory_name']=$row->get_content($row->subCategory->sub_name_cid,$lang);
                    if($row->brand_id)
                    {
                    $prd_list['brand_id']=$row->brand_id;
                    $prd_list['brand_name']=$row->get_content(@$row->brand->brand_name_cid,$lang);
                    }
                    $prd_list['seller_id']=$row->seller_id;
                    $prd_list['seller']=$row->Store($row->seller_id)->store_name;
                    if($row->product_type==1){
                    $prd_list['product_type']='simple';        
                    $prd_list['actual_price']=number_format($row->prdPrice->price,2);
                    $prd_list['sale_price']=$this->get_sale_price($row->id);
                    $prd_list['special_ofr_price']=$this->get_special_ofr_price($row->id,$row->prdPrice->price);
                    }
                    else
                    {
                    $prd_list['product_type']='config';        
                    $prd_list['actual_price']=$this->config_product_price($row->id);
                    $prd_list['sale_price']=$this->get_sale_price($row->id);  
                    $c_price=$prd_list['actual_price'];
                    $prd_list['special_ofr_price']=$this->get_special_ofr_price($row->id,$c_price);
                    }
                    $prd_list['shock_sale_price'] = $this->shock_sale_price($row->id);
                    $prd_list['short_description']=$row->get_content($row->short_desc_cnt_id,$lang);
                    $prd_list['tag']=$this->get_product_tag($row->id,$lang);
                    $prd_list['rating']=$this->get_rates($row->id);
                    $prd_list['total_reviews']=$this->get_rates_count($row->id);
                    $prd_list['seller']=$row->Store($row->seller_id)->store_name;
                    $prd_list['image']=$this->get_product_image($row->id); 
            
                    if($prd_id!='')
                    {
                    $products=$prd_list;
                    }
                    else
                    {
                    $products[]=$prd_list;   
                    
                    }
                   
                    
                   }
                  
                    return $products;
           }
}

    
   

    function get_subcategory($cat_id,$lang_id){
        $data     =   [];
        
        $subcat       =   Subcategory::where('category_id',$cat_id)->where('is_active',1)->get(['subcategory_id','sub_name_cid','image']); 
            
			if($subcat)   {   foreach($subcat as $k=>$row){ 
                if($row->image!='')
                {
                $image = url('storage/app/public/subcategory/'.$row->image);
                }
                else
                {
                    $image='';
                }
                $val['id']    =   $row->subcategory_id;
                //dd
				$val['subcategory_name']   =  $row->get_content($row->sub_name_cid,$lang_id);
               
				$val['subcategory_image']  =  $image;
                $data[]       =   $val;
				
            } }
            else{ $data     =   []; } return $data;
        
    }

    // function get_product_image($prd_id){
    //     $data     =   [];
        
    //     $admin_pro=Product::where('id',$prd_id)->first();
        
    //     if($admin_pro->admin_prd_id > 0)
    //     {
    //     $product       =   ProductImage::where('prd_id',$prd_id)->where('is_deleted',0)->get(); 
    //     }
    //     else
    //     {
    //     $product       =   PrdAdminImage::where('prd_id',$prd_id)->where('is_deleted',0)->get();
    //     }
    //         if($product)   {   foreach($product as $k=>$row){ 
    //             if($row->image)
    //             {
    //             $val['image']       =   config('app.storage_url').$row->image;
    //             }
    //             if($row->thumbnail)
    //             {
    //             $val['thumbnail']   =   config('app.storage_url').$row->thumbnail;
    //             }
    //             $data[]             =   $val;
    //         } }
    //         else{ $data     =   []; } return $data;
        
    // }
    
    function get_product_image($prd_id){
        $data     =   [];
        
        //$admin_pro=Product::where('id',$prd_id)->first();
        
        
        $product_seller       =   PrdImage::where('prd_id',$prd_id)->where('is_deleted',0)->get();
        
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

    function get_product_tag($prd_id,$lang_id){
        $data     =   [];
        
        $product       =   PrdAssignedTag::where('prd_id',$prd_id)->get(); 
            if($product)   {   foreach($product as $k=>$row){ 
                $val['tag_name']    =   $this->get_content($row->tag->tag_name_cid,$lang='');
                $data[]             =   $val;
            } }
            else{ $data     =   []; } return $data;
        
    }

   

  // Product Attributes
    function get_product_attributes($prd_id){
        $data     =   [];
        
        $prod_data       =   AssignedAttribute::where('is_deleted',0)->where('prd_id',$prd_id)->get();
            if(count($prod_data)>0)   { 
                foreach($prod_data as $row)  {
                    $attr_list['id']=$row->id;
                    //$attr_list['attr_id']=$row->attr_id;
                    $attr_list['attr_name']=$this->get_content($row->PrdAttr->name_cnt_id,$lang_id='');
                    $attr_list['attr_type']=$row->PrdAttr->type;
                    $attr_list['attr_data_type']=$row->PrdAttr->data_type;
                    $attr_list['attr_value_name']=$this->get_content($row->PrdAttr_value->name_cnt_id,$lang_id='');
                    $attr_list['attr_value']=$row->attr_value;
                    $data[]             =   $attr_list;
                }
             }
            else{ $data     =   []; } return $data;
        
    }

    //Avg rating
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

        //Avg rating of Seller/Store
        function get_seller_rating($field_id){ 

        $rate =SellerReview::select(DB::raw('AVG(rating) as rating'))->where('seller_id',$field_id)->where('is_active',1)->first();
        if($rate){ 
        $return_val = round($rate->rating);
        return $return_val;
        }
        else
            { $return_val=0;
                return $return_val; }
        }

        //Seller product rating
    function get_seller_product_rating($field_id){ 

        $rate = DB::table('prd_products')
        ->select(DB::raw('AVG(prd_review.rating) as rating'))
            ->leftJoin('prd_review', 'prd_review.prd_id', 'prd_products.id')
            ->where('prd_products.seller_id',$field_id)
            ->groupBy('prd_products.seller_id')->first();        
        if($rate){ 
        $return_val = round($rate->rating);
        return $return_val;
        }
        else
            { $return_val=0;
                return $return_val; }
        }

        //Each Product review
    function get_product_review($prd_id){
        $data     =   [];
        
        $prod_data       =   PrdReview::where('is_deleted',0)->where('is_active',1)->where('prd_id',$prd_id)->get();
            if(count($prod_data)>0)   { 
                foreach($prod_data as $row)  {
                    $user=$row->customerinfo($row->user_id);
                    $list['review_id']=$row->id;
                    $list['user_id']=$user->first_name." ".$user->middle_name." ".$user->last_name;
                    $list['rating']=$this->get_rates($row->id);
                    $list['total_reviews']=$this->get_rates_count($row->id);
                    $list['comment']=$row->comment;
                    if($row->image)
                    {
                    $list['image']=url('storage/app/public/product_review/'.$row->image);;  
                    }
                    else
                    {
                     $list['image']='';  
                    }
                    $list['date']=date('d M Y',strtotime($row->created_at));
                    $data[]             =   $list;
                }
                $order = array_column($data, 'rating');

                array_multisort($order, SORT_DESC, $data);
             }
            else{ $data     =   []; } return $data;
        
    }

    //count down Timer
    public function timer($start_date,$end_date)
    {
        $d1 = new DateTime($start_date);
        $d2 = new DateTime($end_date);
        $interval = $d1->diff($d2);
        $diffInSeconds = $interval->s; //45
        $diffInMinutes = $interval->i; //23
        $diffInHours   = $interval->h; //8
        $diffInDays    = $interval->d; //21
        $diffInMonths  = $interval->m; //4
        $diffInYears   = $interval->y; //1

        return $diffInYears;
    }

    //Product sale price
    public function get_sale_price($field_id){ 

       $current_date=Carbon::now();
       $rows = PrdPrice::where('is_deleted',0)->where('prd_id',$field_id)->whereDate('sale_end_date','>=',$current_date)->orderBy('id','DESC')->first();        
        //dd($rows);
		if($rows){ 
        $return_val = $rows->sale_price;
        return $return_val;
        }
        else
            { $return_val=false;
                return $return_val; }
        }

       // No.of products
        function number_of_products($seller_id){
        
        $count       =   Product::where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->where('visible',1)->where('seller_id',$seller_id)->count();
        return $count;
       }

     function get_content($field_id,$lang){ 
		if($lang==''){
		$language =DB::table('glo_lang_lk')->where('is_active', 1)->where('is_default', 1)->first();
		$lang=$language->id;	
		}
        $content_table=DB::table('cms_content')->where('cnt_id', $field_id)->where('lang_id', $lang)->first();
        if(!empty($content_table)){ 
        $return_cont = $content_table->content;
        return $return_cont;
        }else {
            $language =DB::table('glo_lang_lk')->where('is_active', 1)->first();
			$language_id=$language->id;
			$content_table=DB::table('cms_content')->where('cnt_id', $field_id)->where('lang_id', $language_id)->first();
			if(!empty($content_table)){ 
			$return_cont = $content_table->content;
			return $return_cont;
			}else{
				return false;
			}
		}
	 }
   
   //CONFIG PRODUCT PRICE
        
        function config_product_price($prd_id)
        {
            $val = '';
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
			//dd($return_val);
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
        
        public function get_special_ofr_value($field_id){ 

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
		
	public function currency(Request $request)
    { 
        $list = [];
        $currency = Currency::where('is_deleted',0)->where('is_active',1)->get();
        foreach($currency as $row)
        {
            $cur['currency_id']  = $row->id;
            $cur['is_default']   = $row->is_default;
            $cur['currency_code']= $row->currency_code;
            $cur['currency_name']= $row->currency_name;
            $cur['currency_image']= config('app.storage_url').$row->image;
            $list[] = $cur;
        }
        return ['httpcode'=>200,'status'=>'success','message'=>'Currency list','data'=>['currency'=>$list]];
    }
	
	public function fruit_veg_sub_list(Request $request){
        $lang_id=$request->lang_id;
		$grocery_subcat= $this->get_subcategory(32,$lang_id);
		return ['httpcode'=>200,'status'=>'success','message'=>'Subcategory list','data'=>['grocery_subcat'=>$grocery_subcat]];
	}

   
}
