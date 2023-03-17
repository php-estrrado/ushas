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
use App\Models\Subcategory;
use App\Models\Store;
use App\Models\SellerReview;
use App\Models\SaleOrder;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\PrdAdminImage;
use App\Models\ProductDaily;
use App\Models\PrdAssignedTag;
use App\Models\PrdReview;
use App\Models\PrdShock_Sale;
use App\Models\PrdShockingSaleProduct;
use App\Models\PrdPrice;
use App\Models\Prd_Recent_View;
use App\Models\PrdOffer;
use App\Models\Productvisitor;
use App\Models\RelatedProduct;
use App\Models\AssignedAttribute;
use App\Models\UsrWishlist;
use App\Models\PrdImage;
use App\Models\UserVisit;
use App\Models\UserProductVisit;
use App\Models\Occasion;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;
use Carbon\Carbon;
use App\Rules\Name;
use Validator;
use DBlackborough\Quill\Render;

use App\Models\crm\{CrmAssortmentMaster, CrmChildProductsMaster, CrmCustomerType,CrmPartAssortmentDetails,
CrmPartAssortmentMaster,CrmProduct,CrmSalesPriceList,CrmSalesPriceType,CrmSize,CrmBranch,CrmCompany,CrmColour};


class ProductController extends Controller
{
    
    //Product By category

    public function product_by_category(Request $request)
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
        if ($validator->fails()) 
            {    
              return ['httpcode'=>400,'status'=>'error','message'=>'invalid','data'=>['errors'=>$validator->messages()]];
            }
            
        if($request->post('access_token')){
            if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
            $login=1;
        }
        
        $products=[];

        if($request->id!='')
        {
            $prod_data= Product::where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->where('visible',1)->where('category_id',$request->id)->paginate(12);
            
            if(!empty($prod_data))
            {
                foreach($prod_data as $row)
                {
                   
                    //$prd_list['service_status']=$store_active->service_status;     
                    $prd_list['product_id']=$row->id;
                    $prd_list['product_name']=$this->get_content($row->name_cnt_id,$lang);
                    $prd_list['category_id']=$row->category_id;
                    $prd_list['category_name']=$this->get_content($row->category->cat_name_cid,$lang);
                    $prd_list['subcategory_id']=$row->sub_category_id;
                    $prd_list['subcategory_name']=$this->get_content($row->subCategory->sub_name_cid,$lang);
                    if($row->brand_id)
                    {
                    $prd_list['brand_id']=$row->brand_id;
                    $prd_list['brand_name']=$this->get_content(@$row->brand->brand_name_cid,$lang);
                    }
                    else
                    {
                    $prd_list['brand_id']='';
                    $prd_list['brand_name']='';  
                    }
                    if($row->product_type==1)
                    {
					$prd_list['min_order_qty']=$row->min_order;
					$prd_list['bulk_order_qty']=$row->bulk_order;
                    $prd_list['product_type']='simple';    
						
						$price=$this->get_price($row->id,$type=1,$login);
						foreach ($price as $item) {
							foreach ($item as $key => $value) {
								$prd_list[$key] = $value;
							} 
						}	
					}
                    else
                    {
                     $prd_list['product_type']='config'; 
                     
					 $minprice_prd_id=$this->min_price_product($row->id);
					$price=$this->get_price($minprice_prd_id,$type=2,$login);
                   foreach ($price as $item) {
							foreach ($item as $key => $value) {
								$prd_list[$key] = $value;
							} 
						}	
						
					}  
                    $prd_list['short_description']=$this->get_content($row->short_desc_cnt_id,$lang);
                    $prd_list['tag']=$this->get_product_tag($row->id,$lang);
                    $prd_list['rating']=$this->get_rates($row->id);
					
                    $prd_list['total_reviews']=$this->get_rates_count($row->id);
                    //$prd_list['seller']=$row->Store($row->seller_id)->store_name;
                    //$prd_list['seller_id']=$row->seller_id;
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
                           // dd($login);
                                $associative_prd[]=$this->ass_related_product1($product_visibility->id,$special_ofr_available,$lang,$login);
                            
                            
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
				//dd($associative_prd);
                 $variants_list=[]; 
				 //Variants List   
				if($associative_prd){
					//dd(count($associative_prd));
					$variants_list = array();
					foreach($associative_prd as $asso_prod){
						$attr_value=$asso_prod['attr_value'];
						$data['pro_id']=$asso_prod['product_id'];
						foreach($asso_prod['sub_attributes'] as $row1){
						
						if($attr_value){
						$data['combination']=$attr_value."-".$row1['attr_value'];
						}else{
						$data['variants']=$row1['attr_value'];
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

                    
			$prd_list['variants_list']=$variants_list;

            $prd_list['config_prd']=$associative_prd; 
            $prd_list['offers']=$this->available_offers($row->id,$lang); 
            $products[]=$prd_list;
            
                
                }
				/*if($min_price!="" && $max_price!="" ){
				$new=[];
				$flag = 0;
				foreach($products as $key=>$value){
					if($value["actual_price"] >= $min_price && $value["actual_price"] <= $max_price){
						$flag = 1;
					}else{
						$flag = 0;
					}
					if($flag==1){
					$new[$key] = $value;
					}
				}
 
				
				$products=$new;
				}*/
             
			
			
			
			
           if(!empty($products))
           {
               $total_products=$prod_data->total();
           }
           else
           {
               $total_products=0;
           }
            return response()->json(['httpcode'=>200,'status'=>'success','data'=>['products'=>$products,'total_products'=>$total_products]]);

            }
            else
            {
               return ['httpcode'=>200,'status'=>'success','message'=>'Product not found']; 
            }

        }
        else
        {
            return ['httpcode'=>200,'status'=>'error','message'=>'Enter valid category'];
        }
    }
    
    //Product by sub category
    public function product_by_subcategory(Request $request)
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
        if ($validator->fails()) 
            {    
              return ['httpcode'=>400,'status'=>'error','message'=>'invalid','data'=>['errors'=>$validator->messages()]];
            }
            
            if($request->post('access_token')){
            if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
            $login=1;
        }

        if($request->subcat_id!='')
        {
            $products=[];
            $prod_data= Product::where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->where('visible',1)->where('sub_category_id',$request->subcat_id)->paginate(12);
            if(!empty($prod_data))
            {
                foreach($prod_data as $row)
                {
                   
                    //$prd_list['service_status']=$store_active->service_status;     
                    $prd_list['product_id']=$row->id;
                    $prd_list['product_name']=$this->get_content($row->name_cnt_id,$lang);
                    $prd_list['category_id']=$row->category_id;
                    $prd_list['category_name']=$this->get_content($row->category->cat_name_cid,$lang);
                    $prd_list['subcategory_id']=$row->sub_category_id;
                    $prd_list['subcategory_name']=$this->get_content($row->subCategory->sub_name_cid,$lang);
                    if($row->brand_id)
                    {
                    $prd_list['brand_id']=$row->brand_id;
                    $prd_list['brand_name']=$this->get_content(@$row->brand->brand_name_cid,$lang);
                    }
                    else
                    {
                    $prd_list['brand_id']='';
                    $prd_list['brand_name']='';  
                    }
                    if($row->product_type==1)
                    {
					$prd_list['min_order_qty']=$row->min_order;
					$prd_list['bulk_order_qty']=$row->bulk_order;
                    $prd_list['product_type']='simple';    
						
						$price=$this->get_price($row->id,$type=1,$login);
						foreach ($price as $item) {
							foreach ($item as $key => $value) {
								$prd_list[$key] = $value;
							} 
						}	
					}
                    else
                    {
                     $prd_list['product_type']='config'; 
                     
					 $minprice_prd_id=$this->min_price_product($row->id);
					$price=$this->get_price($minprice_prd_id,$type=2,$login);
                   foreach ($price as $item) {
							foreach ($item as $key => $value) {
								$prd_list[$key] = $value;
							} 
						}	
						
					}  
                    $prd_list['short_description']=$this->get_content($row->short_desc_cnt_id,$lang);
                    $prd_list['tag']=$this->get_product_tag($row->id,$lang);
                    $prd_list['rating']=$this->get_rates($row->id);
					
                    $prd_list['total_reviews']=$this->get_rates_count($row->id);
                    //$prd_list['seller']=$row->Store($row->seller_id)->store_name;
                    //$prd_list['seller_id']=$row->seller_id;
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
                           // dd($login);
                                $associative_prd[]=$this->ass_related_product1($product_visibility->id,$special_ofr_available,$lang,$login);
                            
                            
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
				//dd($associative_prd);
                 $variants_list=[]; 
				 //Variants List   
				if($associative_prd){
					//dd(count($associative_prd));
					$variants_list = array();
					foreach($associative_prd as $asso_prod){
						$attr_value=$asso_prod['attr_value'];
						$data['pro_id']=$asso_prod['product_id'];
						foreach($asso_prod['sub_attributes'] as $row1){
						
						if($attr_value){
						$data['combination']=$attr_value."-".$row1['attr_value'];
						}else{
						$data['variants']=$row1['attr_value'];
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

                    
			$prd_list['variants_list']=$variants_list;

            $prd_list['config_prd']=$associative_prd; 
            $prd_list['offers']=$this->available_offers($row->id,$lang); 
            $products[]=$prd_list;
            
                
                }
				/*if($min_price!="" && $max_price!="" ){
				$new=[];
				$flag = 0;
				foreach($products as $key=>$value){
					if($value["actual_price"] >= $min_price && $value["actual_price"] <= $max_price){
						$flag = 1;
					}else{
						$flag = 0;
					}
					if($flag==1){
					$new[$key] = $value;
					}
				}
 
				
				$products=$new;
				}*/
             
			
			
			
			
           if(!empty($products))
           {
               $total_products=$prod_data->total();
           }
           else
           {
               $total_products=0;
           }

            return response()->json(['httpcode'=>200,'status'=>'success','data'=>['products'=>$products,'total_products'=>$total_products]]);

            }
            else
            {
               return ['httpcode'=>404,'status'=>'error','message'=>'Product not found']; 
            }

        }
        else
        {
            return ['httpcode'=>404,'status'=>'error','message'=>'Enter valid subcategory'];
        }
    }

    //Product by brand
    public function product_by_brand(Request $request)
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
        if ($validator->fails()) 
            {    
              return ['httpcode'=>400,'status'=>'error','message'=>'invalid','data'=>['errors'=>$validator->messages()]];
            }
            
            if($request->post('access_token')){
            if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
            $login=1;
        }

        if($request->brand_id!='')
        {
            $products=[];
            $prod_data= Product::where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->where('visible',1)->where('brand_id',$request->brand_id)->paginate(12);
             if(!empty($prod_data))
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
                
                foreach($prod_data as $row)
                {
                    $store_active = Store::where('is_active',1)->where('seller_id',$row->seller_id)->first();
                    if($store_active)
                    {
                    $prd_list['service_status']=$store_active->service_status;    
                    $prd_list['id']=$row->id;
                    $prd_list['product_name']=$row->name;//$this->get_content($row->name_cnt_id,$lang);
                    $prd_list['category_id']=$row->category_id;
					if($prd_list['category_id']){
						$category=Category::where('category_id',$row->category_id)->first();
						$prd_list['is_rating']=$category->is_rating;
					}else{
						
						$prd_list['is_rating']=0;
					}
                    $prd_list['category_name']=$this->get_content($row->category->cat_name_cid,$lang);
                    $prd_list['subcategory_id']=$row->sub_category_id;
                    $prd_list['subcategory_name']=$this->get_content($row->subCategory->sub_name_cid,$lang);
                    if($row->brand_id)
                    {
                    $prd_list['brand_id']=$row->brand_id;
                    $prd_list['brand_name']=$this->get_content(@$row->brand->brand_name_cid,$lang);
                    }
                    else
                    {
                    $prd_list['brand_id']='';
                    $prd_list['brand_name']='';
                    }
                    $prd_list['seller']=$row->Store($row->seller_id)->store_name;
                    $prd_list['seller_id']=$row->seller_id;
                    if($row->product_type==1){
                    $prd_list['product_type']='simple';    
                    $prd_list['actual_price']=number_format($row->prdPrice->price,2);
                    $prd_list['special_ofr_price']=$this->get_special_ofr_price($row->id,$row->prdPrice->price);
                    }
                    else
                    {
                     $prd_list['product_type']='config';    
                     $prd_list['actual_price']=$this->config_product_price($row->id);
                     $c_price=$prd_list['actual_price'];
                     $prd_list['special_ofr_price']=$this->get_special_ofr_price($row->id,$c_price);
                    }
                    $prd_list['sale_price']=$this->get_sale_price($row->id);
                    
                    $prd_list['shock_sale_price'] = $this->shock_sale_price($row->id);
                    $prd_list['short_description']=$this->get_content($row->short_desc_cnt_id,$lang);
                    $prd_list['tag']=$this->get_product_tag($row->id,$lang);
                    $prd_list['rating']=$this->get_rates($row->id);
                    $prd_list['total_reviews']=$this->get_rates_count($row->id);
                    $prd_list['seller']=$row->Store($row->seller_id)->store_name;
                    $prd_list['image']=$this->get_product_image($row->id); 
            

                    $products[]=$prd_list;
                    
                    }
                }

                return ['httpcode'=>200,'status'=>'success','data'=>['products'=>$products]];

            }
            else
            {
               return ['httpcode'=>404,'status'=>'error','message'=>'Product not found']; 
            }

        }
        else
        {
            return ['httpcode'=>404,'status'=>'error','message'=>'Enter valid subcategory'];
        }
    }

    //product Detail page
     public function product_detail(Request $request)
    {
        $login=0;
        $user_id=null; $user=[];
        if($request->post('access_token')){
            if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
            $user_id = $user['user_id'];
            $checkprd =  UsrWishlist::where('user_id',$user_id)->where('prd_id',$request->id)->where('is_deleted',0)->first();
            if($checkprd)
            {
                $wishlist = 1;
            }
            else
            {
                $wishlist = 0;
            }
            $login=1;
        }
        else
        {
            $wishlist = 0;
        }
        
        $lang=$request->lang_id;
        if($request->id != '')
        {
            $prod_data= Product::where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->where('visible',1)->where('id',$request->id)->first();
            $prd_rel=RelatedProduct::where('prd_id',$request->id)->where('is_deleted',0)->get();
            
            if(!empty($prod_data))
            {
               
                //Insert visitors log
                $usr_product_visit=Productvisitor::create([
                'prd_id' => $request->id,
                'user_id'=>$user_id,
                'device_id'=>$request->device_id,
                'os'=>$request->os_type,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")]);

                $usr_visit =UserVisit::create([
                'org_id' =>1,
                'device_id'=>$request->device_id,
                'is_login'=>$login,
                'os'=>$request->os_type,
                'url'=>$request->page_url,
                'visited_on'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")]);
                //insert on recent view
                if($request->post('access_token')){
                            $recent_views = Prd_Recent_View::where('user_id',$user_id)->first();
                                if($recent_views)
                                {
                                $recent_prd = $recent_views->prd_id;
                                foreach(explode(",",$recent_prd) as $news)
                                {
                                    $new_array[] = $news;
                                }
                                if(count($new_array)>20)
                                {
                                   array_shift($new_array);
                                }
                                $prd_new_id[] = $request->id; 
                                $merge = array_merge($new_array,$prd_new_id);
                                $unique_arr = array_unique($merge);
                                $new_prd =implode(",", $unique_arr);
                    
                                $create=Prd_Recent_View::where('id',$recent_views->id)->update([
                                'prd_id' => $new_prd,
                                'updated_at'=>date("Y-m-d H:i:s")]);
                                }
                                else
                                {
                                $create=Prd_Recent_View::create([
                                'prd_id' => $request->id,
                                'user_id'=>$user_id,
                                'created_at'=>date("Y-m-d H:i:s"),
                                'updated_at'=>date("Y-m-d H:i:s")]);
                                }
                     }
                    $prd_list['product_id']=$prod_data->id;
                    $prd_list['product_name']=$this->get_content($prod_data->name_cnt_id,$lang);
                    if($prod_data->product_type==1)
                    {
                    $prd_list['product_type']='simple';  
					if($prod_data->min_order){
					$prd_list['minimum_quantity']=$prod_data->min_order;
					}else{
					$prd_list['minimum_quantity']=0;
					}
					if($prod_data->bulk_order){
					$prd_list['bulk_quantity']=$prod_data->bulk_order;
					}else{
					$prd_list['bulk_quantity']=0;
					}						
                        $price=get_crm_price($prod_data,$type=1,$user);
                        ;
                        foreach ($price as $key => $value) {
                        $prd_list[$key] = $value;
                        } 
					
					
					}
                    else
                    {
                     $prd_list['product_type']='config'; 
                     $prd_list['min_order_qty']=0;
					  $prd_list['bulk_order_qty']=0;

                    $price=get_crm_price($prod_data,$type=1,$user);

                    foreach ($price as $key => $value) {
                    $prd_list[$key] = $value;
                    } 
						
					}
                    $prd_list['sku']=$prod_data->sku;
                    $prd_list['seller_id']=$prod_data->seller_id;
                    $prd_list['category_id']=$prod_data->category_id;
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
                    $prd_list['is_featured']=$prod_data->is_featured;
                    $prd_list['short_description']=$this->get_content($prod_data->short_desc_cnt_id,$lang);
                    $prd_list['long_description']=$this->get_content($prod_data->desc_cnt_id,$lang);
                    $prd_list['content']=$this->get_content($prod_data->content_cnt_id,$lang);
                    $prd_list['specification']=$this->get_content($prod_data->spec_cnt_id,$lang);
                    $prd_list['points']=$prod_data->points;
                    
                    $color_id = $prod_data->crmProduct->ColourID;
                    if(!empty($color_id))
                    {
                        $prd_list['color'] = CrmColour::where('ColourId',$color_id)->first()->ColourName;
                    }
                    else
                    {
                        $prd_list['color'] = '';
                    }
                    
                    if($prod_data->spec_cnt_id)
                    {
                        $result='';
                        $spec =$this->get_content($prod_data->spec_cnt_id,$lang);
                        
						if($spec!=false){
                		    try {
                		    $quill = new \DBlackborough\Quill\Render($spec);
                		    $result = $quill->render();
                        }
                        catch(\Exception $e){
                            //echo $e->getMessage();
                        }
                        }
                        else
                        {
                            $result=false;
                        }
                		
                       $prd_list['quill_specification']	= $result;	
                    }
                    else
                    {
                        $prd_list['quill_specification']	= false;
                    }
                    if($prod_data->product_type == 1)
                    {    
                   
                    
                    $prd_list['stock']=$prod_data->prdStock($prod_data->id);
                    if($prod_data->is_out_of_stock <= 0)
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
                    
                       
                    $prd_list['in_wishlist']=$wishlist;
                    $prd_list['tag']=$this->get_product_tag($prod_data->id,$lang); 
                    $prd_list['image']=$this->get_product_image($prod_data->id);
                     
                    $prd_list['rating']=$this->get_rates($prod_data->id);
                    $prd_list['total_reviews']=$this->get_rates_count($prod_data->id);
                    $products=$prd_list;
                    $associative_prd=[];
                    $product_assortments=[];
                   
                     //ASSOCIATIVE products
                    if($prod_data->product_type == 2)
                    {  

                        // $prices = $this->config_product_price($prod_data->id); 
                        // $special_ofr_available=$this->get_special_ofr_value($prices,$prod_data->id); 

                        $crm_product = $prod_data->crmProduct;
                        $variants_list = [];
                        if($crm_product)
                        {
                            $crm_product_id = $crm_product->id;

                            // check assortments
                            $prd_assort = CrmPartAssortmentMaster::where('productID',$crm_product_id)->where('is_deleted',0)->get();
                            if(count($prd_assort)>0)
                            {
                            foreach($prd_assort as $rows)
                            {
                                // dd($rows);
                                // $product_assortments['assortment_id'] = $rows->AssortmentID;
                                if($rows->Assortments)
                                {
                                    $assortment = $rows->Assortments;

                                    // dd($rows->AssortmentsDetail);
                                    $product_assortments['assortment'] = $assortment->Assortment;
                                    $product_assortments['assortment_id'] = $assortment->AssortmentID;
                                    if($rows->AssortmentsDetail)
                                    {
                                        
                                        $k=0; $product_assortments['assortment_data'] = [];
                                        foreach($rows->AssortmentsDetail as $child_prod_k=>$child_val)
                                        {
                                            
                                           if($child_val->ChildProduct){ $product_assortments['assortment_data']['children'][$k]['size'] = $child_val->ChildProduct->SizeInfo->SizeName; }else{
                                               $product_assortments['assortment_data']['children'][$k]['size'] = "";
                                               
                                           } 
                                            $product_assortments['assortment_data']['children'][$k]['child_product_id'] = $child_val->ChildProductID;
                                            $product_assortments['assortment_data']['children'][$k]['child_quantity'] = $child_val->ChildQuantity;
                                           if($child_val->ChildProduct){
                                               $product_assortments['assortment_data']['children'][$k]['available_quantity'] = $child_val->ChildProduct->ChildPrdStock($child_val->ChildProduct->ChildProductID,$prod_data->id);
                                               
                                           }else{
                                               $product_assortments['assortment_data']['children'][$k]['available_quantity'] = 0;
                                           } 

                                            $k++;
                                        }
                                  

                                    }
                                    
                                }

                                $variants_list[] = $product_assortments;
                            }
                            }

                            //custom assortment 

                            $custom_assort = [];

                            $cust_assort = $crm_product->childProducts;
                            if($cust_assort)
                            {
                                $v=0;
                                foreach($cust_assort as $ck=>$cv)
                                {
                                    $custom_assort['custom']['assortment_data']['children'][$v]['size'] = $cv->SizeInfo->SizeName;
                                    $custom_assort['custom']['assortment_data']['children'][$v]['child_product_id'] = $cv->ChildProductID;
                                    $custom_assort['custom']['assortment_data']['children'][$v]['available'] = $cv->ChildPrdStock($cv->ChildProductID,$prod_data->id);
                                    $v++;
                                }
                            }

                                            
                            $variants_list[] =$custom_assort;
                            // dd($prd_assort);
                        }


                        
                  }

                  // dd($custom_assort);
                  
				  

				// dd($variants_list);
                    //related products
                    $prd_rel=RelatedProduct::where('prd_id',$request->id)->where('is_deleted',0)->get();
                    if(count($prd_rel)>0)
                    {
                        foreach($prd_rel as $key)
                        {
                        //$related['products']=$this->related_product($key->rel_prd_id);
                        $related_products[]=$this->related_product($key->rel_prd_id,$lang,$user);

                        }
                        // $order = array_column($related_products, 'rating');
                        // array_multisort($order, SORT_DESC, $related_products);

                    }
                    else
                    {
                        $related_products=[];
                    }
					$related_products=array_values(array_filter($related_products));
					//Viewed products
					//$viewed_prd_list[]=[];
					$viewed_prds=[];
				$viewed_prod = Product::join('usr_product_visitor', 'usr_product_visitor.prd_id', '=', 'prd_products.id')
					->where('usr_product_visitor.user_id',$user_id )->where('usr_product_visitor.prd_id','!=',$request->id )->orderBy('usr_product_visitor.id','DESC')->groupBy('prd_products.id')
					->where('prd_products.visible','=',1 )
					->select(DB::raw("prd_products.*"))
					->get();
        
				if(count($viewed_prod)>0){
					foreach($viewed_prod as $row){
						$viewed_prds[]=$this->viewed_products($row->id,$lang,$user);
					}
				}
				else
				{
					$viewed_prds[]='';
				}
				$viewed_prds=array_values(array_filter($viewed_prds));
				
                    if($prod_data->brand_id!='')
                    {
                        $brand_products=$this->related_brand_product($prod_data->brand_id,$prod_data->id,$lang);
                    }
                    else
                    {
                        $brand_products=[];
                    }

                    //Product review

                    $product_review=$this->get_product_review($prod_data->id,$count='');
                    $product_review_count=$this->get_product_review($prod_data->id,$count=1);
                    $product_review_range=$this->get_product_review_date_range($prod_data->id);
                    //seller information

                   
                    
                    //Available offers for this product
            $current_date=Carbon::now();
            $deals= Product::where('id',$prod_data->id)->where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->where('visible',1)->where('daily_deals',1)->get();
            $shock = PrdShock_Sale::join('prd_shock_sale_products','prd_shock_sale.id','=','prd_shock_sale_products.shock_sale_id')
            ->where('prd_shock_sale.is_active',1)->where('prd_shock_sale.is_deleted',0)->whereDate('prd_shock_sale.start_time','<=',$current_date)->whereDate('prd_shock_sale.end_time','>=',$current_date)
            ->where('prd_shock_sale_products.is_active',1)->where('prd_shock_sale_products.is_deleted',0)->whereRaw("find_in_set($prod_data->id,prd_shock_sale_products.prd_id)")
            ->select('prd_shock_sale.*','prd_shock_sale_products.seller_id','prd_shock_sale_products.prd_id as shock_prd_id')->
            first();
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
                        $unit_price = (int)$actual_price-(int)$discount_value;
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
                $offer_list[]=$offer;
            }
            else
            {
                $offer_list=[];
            }



                return response()->json(['httpcode'=>200,'status'=>'success','data'=>['product'=>$products,'varaiants_list'=>@$variants_list,'relative_products'=>$related_products,'viewed_products'=>$viewed_prds,'review'=>$product_review,'total_review'=>$product_review_count,'rate_range'=>$product_review_range,'offer'=>$offer_list,'currency'=>getCurrency()->name]]);

           
            
            }
            else
            {
               return response()->json(['httpcode'=>404,'status'=>'error','message'=>'Product not found']); 
            }

        }
        else
        {
            return response()->json(['httpcode'=>400,'status'=>'error','message'=>'Enter valid Product id']);
        }
    }

     // DAILY DEAL PRODUCT DETAIL PAGE
    public function daily_deal(Request $request)
    {
        $lang=$request->lang_id;
         //Daily deals

            $daily= ProductDaily::where('is_active',1)->where('is_deleted',0)->where('id',$request->deal_id)->first();
            if($daily)
            {
                   
                    $product_id=$daily->Productdetail($daily->prd_id);
                    $actual_price=$daily->ProductPrice($daily->prd_id)->price;
                    $sale_price=$this->get_sale_price($daily->prd_id);
                    $d_list['deal_id']=$daily->id;
                    $d_list['product_id']=$product_id->id;
                    $d_list['product_name']=$this->get_content($product_id->name_cnt_id,$lang);
                    $d_list['catagory']=$this->get_content($product_id->category->cat_name_cid,$lang);
                    $d_list['sub_catagory']=$this->get_content($product_id->subCategory->sub_name_cid,$lang);
                    if($product_id->brand_id!='')
                    {
                    $d_list['brand']=$this->get_content($product_id->brand->brand_name_cid,$lang);
                    }
                    else
                    {
                        $d_list['brand']="";
                    }
                    $d_list['short_description']=$this->get_content($product_id->short_desc_cnt_id,$lang);
                    $d_list['long_description']=$this->get_content($product_id->desc_cnt_id,$lang);
                    $d_list['content']=$this->get_content($product_id->content_cnt_id,$lang);
                    $d_list['is_out_of_stock']=$product_id->is_out_of_stock;
                    $d_list['seller']=$daily->Store($product_id->seller_id)->store_name;
                    if($row->product_type==1){
                    $d_list['product_type']='simple';    
                    $d_list['actual_price']=$this->get_actual_price($daily->prd_id);
                    }
                    else
                    {
                     $d_list['product_type']='config';    
                     $d_list['actual_price']=$this->config_product_price($daily->prd_id);
                    }
                    $d_list['sale_price']=$this->get_sale_price($daily->prd_id);
                    $d_list['shock_sale_price'] = $this->shock_sale_price($daily->prd_id);
                    // $d_list['actual_price']=number_format($daily->ProductPrice($daily->prd_id)->price,2);
                    // $d_list['sale_price']=$this->get_sale_price($daily->prd_id);
                    if($daily->discount_type=="amount")
                    {
                        $d_list['offer']=$daily->discount_value." Off";
                        $d_list['offer_price']=round($actual_price-$daily->discount_value,2);

                    }
                    else
                    {
                        $d_list['offer']=$daily->discount_value."% Off";
                        $per=($daily->discount_value/100)*$actual_price;
                        $discount=(float)$actual_price-(float)$per;
                        $round= number_format($discount, 2);
                        $d_list['offer_price']=$round;
                    }
                    $d_list['rating']=$this->get_rates($daily->prd_id);
                    $d_list['total_reviews']=$this->get_rates_count($daily->prd_id);
                    $d_list['tag']=$this->get_product_tag($product_id->id,$lang);
                    $d_list['image']=$this->get_product_image($product_id->id);
                    if($product_id->product_type==2){
                    $d_list['attributes']=$this->get_product_attributes($product_id->id,$lang);
                    }


                    $daily_product[]=$d_list;
                

            }
            else
            {
                $daily_product=[];
            }

            return response()->json(['httpcode'=>200,'status'=>'success','data'=>$daily_product]);

    }

    public function shocking_sale(Request $request)
    {
        $lang=$request->lang_id;
        
        $login=0;
        $wishlist = 0;
        $user_id=null;
        $user = [];
        $validator=  Validator::make($request->all(),[
            'shock_sale_id'=>['required','numeric'],
            'product_id'=>['required','numeric'],
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
             $user_id    =   $user['user_id'];
            $login=1;
            
            $checkprd =  UsrWishlist::where('user_id',$user_id)->where('prd_id',$request->product_id)->where('is_deleted',0)->first();
            if($checkprd)
            {
                $wishlist = 1;
            }
            else
            {
                $wishlist = 0;
            }
        }
        
        
        //Shocking sales
        $shock_data=[];
           // $current_date=date('Y-m-d H:i:s');
            $current_date=Carbon::now();
            $shock = PrdShockingSaleProduct::join('prd_shock_sale','prd_shock_sale_products.shock_sale_id','=','prd_shock_sale.id')
            ->where('prd_shock_sale.is_active',1)->where('prd_shock_sale.is_deleted',0)->where('prd_shock_sale_products.shock_sale_id',$request->shock_sale_id)->whereRaw("find_in_set($request->product_id,prd_shock_sale_products.prd_id)")
             ->where('prd_shock_sale_products.is_active',1)->where('prd_shock_sale_products.is_deleted',0)
            ->select('prd_shock_sale.*','prd_shock_sale_products.*','prd_shock_sale_products.seller_id','prd_shock_sale_products.prd_id as shock_prd_id')->first();
            // echo $shock->seller_id;die;
            if($shock)
            {
                $store_active = Store::where('is_active',1)->where('seller_id',$shock->seller_id)->first();
                    if($store_active)
                    {
                        //$prd_data=Product::where('id',$shock->prd_id)->first();
                $usr_visit =UserVisit::create([
                            'org_id' =>1,
                            'device_id'=>$request->device_id,
                            'is_login'=>$login,
                            'os'=>$request->os_type,
                            'url'=>$request->page_url,
                            'visited_on'=>date("Y-m-d H:i:s"),
                            'created_at'=>date("Y-m-d H:i:s"),
                            'updated_at'=>date("Y-m-d H:i:s")]);
                            
                $shock_list['shock_sale_id']=$shock->shock_sale_id;
                
                $real_product = Product::where('id',$request->product_id)->first();
                if($real_product){
                $shock_list['service_status']=$store_active->service_status;
                $shock_list['in_wishlist']=$wishlist;
                $shock_list['product_id']=$real_product->id;
                $shock_list['product_name']=$this->get_content($real_product->name_cnt_id,$lang);
                $shock_list['category']=$this->get_content($real_product->category->cat_name_cid,$lang);
                $shock_list['subcategory']=$this->get_content($real_product->subCategory->sub_name_cid,$lang);
                if($real_product->brand_id)
                {
                $shock_list['brand']=$this->get_content($real_product->brand->brand_name_cid,$lang);
                }
                $shock_list['short_desc']=$this->get_content($real_product->short_desc_cnt_id,$lang);
                $shock_list['long_desc']=$this->get_content($real_product->desc_cnt_id,$lang);
                $shock_list['content']=$this->get_content($real_product->content_cnt_id,$lang);
                $shock_list['start_time']=$shock->start_time;
                $shock_list['end_time']=$shock->end_time;
                if($real_product->product_type==1)
                {
                $sale_price=$this->get_sale_price($real_product->id);
                $actual_price=$this->get_actual_price($real_product->id);
                $shock_list['actual_price']=$real_product->prdPrice->price;
                $shock_list['sale_price']=$this->get_sale_price($real_product->id);
                $special_ofr_available=0;
                if($shock->discount_type=="percentage")
                {
                    $shock_list['offer']=$shock->discount_value."% OFF";
                    $per=($shock->discount_value/100)*$actual_price;
                    $special_ofr_available=$per;
                    $discount=(float)$actual_price-(float)$per;
                    $round= number_format($discount, 2);
                    $shock_list['offer_price']=$round;
                }
                else
                {
                    $shock_list['offer']=$shock->discount_value." OFF";
                    $special_ofr_available=$shock->discount_value;
                    $ofr_price=(float)$actual_price-(float)$shock->discount_value;
                    $shock_list['offer_price']=$ofr_price;
                }
                }
                else
                {
                    $actual_price=$this->config_product_price($real_product->id);
                    $offer='';
                if($shock->discount_type=="percentage")
                {
                   
                    $per=($shock->discount_value/100)*$actual_price;
                    $offer=$shock->discount_value."% OFF";
                    $special_ofr_available=$per;
                    
                }
                else
                {
                    $special_ofr_available=$shock->discount_value;
                    $offer=$shock->discount_value." OFF";
                }    
                $shock_list['actual_price']=$this->config_product_price($real_product->id);
                $actual_price_shock=$shock_list['actual_price'];
                $shock_list['sale_price']=$this->get_sale_price($real_product->id);;
                $shock_list['offer']=$offer;
                $shock_list['offer_price']=$actual_price_shock-$special_ofr_available;
                }
                
                

                $shock_list['rating']=$this->get_rates($real_product->id);
                $shock_list['total_reviews']=$this->get_rates_count($real_product->id);
                $shock_list['seller']=$shock->Store($real_product->seller_id)->store_name;
                $shock_list['image']=$this->get_product_image($real_product->id);
                if($real_product->product_type==2)
                {
                //ASSOCIATIVE products
                    if($real_product->product_type == 2)
                    {    
                        $prd_ass = AssociatProduct::where('prd_id',$real_product->id)->where('is_deleted',0)->get();
                        if(count($prd_ass)>0)
                        {
                            foreach($prd_ass as $rows)
                            {
                            $product_visibility= Product::where('id',$rows->ass_prd_id)->where('is_active',1)->where('is_deleted',0)->first();
                            if($product_visibility){
                            //$associative_prd[]=$this->ass_related_product($rows->ass_prd_id,$lang);
                            
                                $associative_prd[]=$this->ass_related_product1($product_visibility->id,$special_ofr_available,$lang);
                            
                            
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
        
                $shock_list['attributes']=$associative_prd; 
                }
                
                 $shock_data[]=$shock_list;
                    }//real
                    }
                    return response()->json(['httpcode'=>200,'status'=>'success','data'=>$shock_data]);
            }
            else
            {
                $shock_data=[];
                return response()->json(['httpcode'=>400,'status'=>'error','data'=>'Not found']);
            }
            
    }

    public function auction_product(Request $request)
    {
        $lang=$request->lang_id;
        
        $login=0;
        $user_id=null;
        $user = [];
        $validator=  Validator::make($request->all(),[
            'auction_id' => ['required'],
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
        
        //Auction
            $current_date=Carbon::now();
            $rows = Auction::where('is_active',1)->where('is_deleted',0)->where('bid_allocated_to',0)->where('id',$request->auction_id)->first();
            if($rows)
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
            
               $max_value=AuctionHist::where('auction_id',$rows->id)->max('bid_price');
                if($max_value)
                {
                    $highest_bid = $max_value;
                }
                else
                {
                    $highest_bid ='';
                }  
                
                if($rows->bid_allocated_to==0)
                {
                    $auction_status = "Open";
                }
                else
                {
                    $auction_status = "Closed";
                }
                            
                $auction_list['auction_id']=$rows->id;
                $auction_list['auction_code']=$rows->auction_code;
                $auction_list['auction_status']=$auction_status;
                $auction_list['product_id']=$rows->product_id;
                $auction_list['service_status']=$rows->Store($rows->Product->seller_id)->service_status;
                $auction_list['product_name']=$this->get_content($rows->Product->name_cnt_id,$lang);
                $auction_list['category']=$this->get_content($rows->Product->category->cat_name_cid,$lang);
                $auction_list['subcategory']=$this->get_content($rows->Product->subCategory->sub_name_cid,$lang);
                if($rows->Product->brand_id)
                {
                $auction_list['brand']=$this->get_content($rows->Product->brand->brand_name_cid,$lang);
                }
                $auction_list['short_desc']=$this->get_content($rows->Product->short_desc_cnt_id,$lang);
                $auction_list['long_desc']=$this->get_content($rows->Product->desc_cnt_id,$lang);
                $auction_list['content']=$this->get_content($rows->Product->content_cnt_id,$lang);
                $auction_list['start_date']=$rows->auct_start;
                $auction_list['end_date']=$rows->auct_end;
                
                if($rows->Product->product_type==1)
                {
                $auction_list['product_type']="simple";    
                $auction_list['actual_price']=number_format($rows->Product->prdPrice->price,2);
                }
                else
                {
                 $auction_list['product_type']="config";    
                 $auction_list['actual_price']=$this->config_product_price($rows->product_id); 
                }
                $auction_list['sale_price']=$this->get_sale_price($rows->product_id);
                $auction_list['min_bid_price']=$rows->min_bid_price;
                $auction_list['latest_bid_amt']=$highest_bid;
                $auction_list['rating']=$this->get_rates($rows->product_id);
                $auction_list['total_reviews']=$this->get_rates_count($rows->product_id);
                $auction_list['seller']=$rows->Store($rows->Product->seller_id)->store_name;
                $auction_list['seller_id']=$rows->Product->seller_id;
                $auction_list['no_of_bids']=$rows->AuctionHist($rows->id);
                $auction_list['image']=$this->get_product_image($rows->product_id);
                if($rows->Product->product_type==2){
                $auction_list['attributes']=$this->get_product_attributes($rows->product_id,$lang); 
                }
                $start=Carbon::now();
                $end=Carbon::parse($rows->auct_end);
                $difference=$start->diffInDays($end);
                $auction_list['time_gap']=$difference;
                
                 $auction_data=$auction_list;
                 return response()->json(['httpcode'=>200,'status'=>'success','data'=>$auction_data]);
                

            }
            else
            {
                $auction_data=[];
                return response()->json(['httpcode'=>404,'status'=>'Not found']);

            }
    }


    //Shop detail page
    public function shop_detail(Request $request)
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
        if ($validator->fails()) 
            {    
              return ['httpcode'=>400,'status'=>'error','message'=>'invalid','data'=>['errors'=>$validator->messages()]];
            }
            
        if($request->post('access_token')){
            if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
            $login=1;
        }
        
        
        $store_detail=Store::where('is_active',1)->where('is_deleted',0)->where('seller_id',$request->seller_id)->first();
                        if ($store_detail) { 
                            
                            $usr_visit =UserVisit::create([
                            'org_id' =>1,
                            'device_id'=>$request->device_id,
                            'is_login'=>$login,
                            'os'=>$request->os_type,
                            'url'=>$request->page_url,
                            'visited_on'=>date("Y-m-d H:i:s"),
                            'created_at'=>date("Y-m-d H:i:s"),
                            'updated_at'=>date("Y-m-d H:i:s")]);
                            $store_list['service_status']=$store_detail->service_status;
                            $store_list['store_id']=$store_detail->id;
                            $store_list['seller_id']=$store_detail->seller_id;
                            $store_list['store_name']=$store_detail->store_name;
                            if($store_detail->incharge_phone){
                            $store_list['contact_num']=$store_detail->incharge_isd_code." ".$store_detail->incharge_phone;
                            }
                            else{
                            $store_list['contact_num']='';        
                            }
                            $store_list['store_rating']=$this->get_seller_rating($store_detail->seller_id);
                            $store_list['store_prd_rating']=$this->get_seller_product_rating($store_detail->seller_id); 
                            $store_list['postive_review']=$this->get_per_seller_review($store_detail->seller_id);
                            $store_list['join_date']=date('d M Y',strtotime($store_detail->created_at));
                            $store_list['about']=$store_detail->about;
                            $store_list['logo']=config('app.storage_url').$store_detail->logo;
                            $store_list['banner']=config('app.storage_url').$store_detail->banner;
                            $store_list['no_of_products']=$this->number_of_products($store_detail->seller_id);
                            $tot_order=$this->order_per_seller($store_detail->seller_id);
                            $store_list['total_orders']=$tot_order;
                            $store_list['address_line1']=$store_detail->address;
                            $store_list['address_line2']=$store_detail->address2;
                            $store_list['country']=$store_detail->country->country_name;
                            $store_list['state']=$store_detail->state->state_name;
                            $store_list['city']=$store_detail->city->city_name;
                            $store_data[]=$store_list;

                    $rang_products=[];
                     $dropdown= $this->seller_products($store_detail->seller_id,$lang,$total_pro='',$store_detail->service_status);
                    $total_products=$this->seller_products($store_detail->seller_id,$lang,$total_pro='true',$store_detail->service_status);
                    $seller_review=$this->get_seller_review($store_detail->id);
                    if(count($dropdown)>0)
                    {
                        foreach($dropdown as $prod_data)
                        {
                            if($prod_data['rating']>3)
                            {
                            $prd_list['service_status']=$prod_data['service_status'];    
                            $prd_list['product_id']=$prod_data['product_id'];
                    $prd_list['product_name']=$prod_data['product_name'];
                    $prd_list['seller']=$prod_data['seller'];
                    $prd_list['seller_id']=$prod_data['seller_id'];
                    $prd_list['category_id']=$prod_data['category_id'];
                    $prd_list['category_name']=$prod_data['category_name'];
                    $prd_list['subcategory_id']=$prod_data['subcategory_id'];
                   // $prd_list['subcategory_name']=$prod_data->get_content($prod_data->subCategory->sub_name_cid);
                    if($prod_data['brand_id'])
                    {
                    $prd_list['brand_id']=$prod_data['brand_id'];
                    $prd_list['brand_name']=$prod_data['brand_name'];
                    }
                    else
                    {

                        $prd_list['brand_id']="";
                        $prd_list['brand_name']="";
                    }
                    $prd_list['short_description']=$prod_data['short_description'];
                    $prd_list['long_description']=$prod_data['long_description'];
                    $prd_list['content']=$prod_data['content'];
                    $prd_list['actual_price_quote']=$prod_data['actual_price_quote'];
                    $prd_list['actual_price']=$prod_data['actual_price'];
                    $prd_list['sale_price']=$prod_data['sale_price'];
                    $prd_list['is_out_of_stock']=$prod_data['is_out_of_stock'];
                    $prd_list['tag']=$prod_data['tag']; 
                    $prd_list['rating']=$prod_data['rating'];
                    $prd_list['image']=$prod_data['image']; 
                    $rang_products[]             =   $prd_list;
                        }
                        }
                    }
                    else
                    {
                        $rang_products=[];
                    }

        return response()->json(['httpcode'=>200,'status'=>'success','data'=>['shop_detail'=>$store_data,'product'=>$dropdown,'total_products'=>$total_products,'seller_review'=>$seller_review,'best_products'=>$rang_products]]);
                            
                           }

                      else
                      {
                        return response()->json(['httpcode'=>404,'status'=>'Not found']);
                      }     
    }
    //
	public function get_price($prdid,$type,$login){

    

		//$offer['offer_price']=false;
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
    //Product LISTING page
    public function product_list(Request $request)
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
        if ($validator->fails()) 
            {    
              return ['httpcode'=>400,'status'=>'error','message'=>'invalid','data'=>['errors'=>$validator->messages()]];
            }
            
            if($request->post('access_token')){
            if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
            $login=1;
        }

         //filter

        $prod_data= Product::where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->where('visible',1)->orderBy('id','asc')->paginate(12);
       
		$products=[];
        
        
            if(!empty($prod_data))
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
                
                foreach($prod_data as $row)
                {
                    
                    $prd_list['product_id']=$row->id;
                    $prd_list['product_name']=$this->get_content($row->name_cnt_id,$lang);
                    $prd_list['category_id']=$row->category_id;
                    $prd_list['category_name']=$this->get_content($row->category->cat_name_cid,$lang);
                    $prd_list['subcategory_id']=$row->sub_category_id;
                    $prd_list['subcategory_name']=$this->get_content($row->subCategory->sub_name_cid,$lang);
                    if($row->brand_id)
                    {
                    $prd_list['brand_id']=$row->brand_id;
                    $prd_list['brand_name']=$this->get_content(@$row->brand->brand_name_cid,$lang);
                    }
                    else
                    {
                    $prd_list['brand_id']='';
                    $prd_list['brand_name']='';  
                    }
                     if($row->product_type==1)
                    {
                    $prd_list['product_type']='simple';    
						$prd_list['min_order_qty']=$row->min_order;
						$prd_list['bulk_order_qty']=$row->bulk_order;
						$price=$this->get_price($row->id,$type=1,$login);
						foreach ($price as $item) {
							foreach ($item as $key => $value) {
								$prd_list[$key] = $value;
							} 
						}
					}
                    else
                    {
                     $prd_list['product_type']='config'; 
                     $prd_list['min_order_qty']="Null";
					  $prd_list['bulk_order_qty']="Null";
					 $minprice_prd_id=$this->min_price_product($row->id);
					 $price=$this->get_price($minprice_prd_id,$type=2,$login);
					foreach ($price as $item) {
							foreach ($item as $key => $value) {
								$prd_list[$key] = $value;
							} 
						}		
						
					}   
                    $prd_list['short_description']=$this->get_content($row->short_desc_cnt_id,$lang);
                    $prd_list['tag']=$this->get_product_tag($row->id,$lang);
					if($prd_list['category_id']){
						$category=Category::where('category_id',$row->category_id)->first();
						$prd_list['is_rating']=$category->is_rating;
					}else{
						
						$prd_list['is_rating']=0;
					}
                    $prd_list['rating']=$this->get_rates($row->id);
                    $prd_list['total_reviews']=$this->get_rates_count($row->id);
                    $prd_list['image']=$this->get_product_image($row->id); 
					$associative_prd=[];
                    if($row->product_type == 2)
                    {  
                        $prices = $this->config_product_price($row->id); 
                        $special_ofr_available=$this->get_special_ofr_value($prices,$row->id); 
                        $prd_ass = AssociatProduct::where('prd_id',$row->id)->where('is_deleted',0)->get();
                        //dd($prd_ass);
						if(count($prd_ass)>0)
                        {
                            foreach($prd_ass as $rows)
                            {
                            $product_visibility= Product::where('id',$rows->ass_prd_id)->where('is_active',1)->where('is_deleted',0)->first();
                            if($product_visibility){
                            
                                $associative_prd[]=$this->ass_related_product1($product_visibility->id,$special_ofr_available,$lang,$login);
                            
                            
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
               //  dd($associative_prd);
				if($associative_prd){
					// dd($associative_prd);
					//dd(count($associative_prd));
					$variants_list = array();
					foreach($associative_prd as $asso_prod){
						$attr_value=$asso_prod['attr_value'];
						$data['pro_id']=$asso_prod['product_id'];
						foreach($asso_prod['sub_attributes'] as $row1){
						
						if($attr_value){
						$data['combination']=$attr_value."-".$row1['attr_value'];
						}else{
						$data['variants']=$row1['attr_value'];
						}
						//$data['actual_price']=$row1['actual_price'];
						//$data['actual_price']=$row1['actual_price'];
						//$data['special_ofr_price']=$row1['special_ofr_price'];
						$data['stock']=$row1['stock'];
						$data['is_out_of_stock']=$row1['is_out_of_stock'];
						$data['out_of_stock_selling']=$row1['out_of_stock_selling'];
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

                    
			$prd_list['variants_list']=$variants_list;
			//$prd_list['associative_products']=$associative_prd;
            $prd_list['offers']=$this->available_offers($row->id,$lang); 
            $products[]=$prd_list;
                   
                }
                
                if(!empty($products))
                {
                    $total_products=$prod_data->total();
                }
                else
                {
                    $total_products=0;
                }
                return response()->json(['httpcode'=>200,'status'=>'success','data'=>['products'=>$products,'total_products'=>$total_products]]);

            }
            else
            {
               return response()->json(['httpcode'=>200,'status'=>'success','message'=>'Product not found']); 
            }
    }
    
    public function product_list_filter(Request $request)
    {
        
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
		//dd($login);
		
		$lang=$request->lang_id;
		$category    =$subcategory='';
		if($request->category_id){$category    = explode(',',$request->category_id);}
        
        if($request->subcategory_id){$subcategory = explode(',',$request->subcategory_id);}
        $brand       = '';
        if($request->brand_id){$brand    = explode(',',$request->brand_id);}//$brand       = $request->brand_id;
        $max_price   = $request->max_price;
        $min_price   = $request->min_price;
        $latest      = $request->latest;
        $low_to_high = $request->low_to_high;
        $high_to_low = $request->high_to_low;
        $occassion = $request->occasion_id;
        $popular = $request->popular;
        $offset = $request->offset;
        $limit = $request->limit;
         if($request->offset==''){
			$offset =0; 
		 }
		 if($request->limit==''){
			$limit =10; 
		 }
        $products=[];
		\DB::enableQueryLog();
        $query = Product::query();
		$query->select('prd_products.*');
		if($popular==1){
		$query->join('usr_product_visitor', 'usr_product_visitor.prd_id', '=', 'prd_products.id')
		->addSelect(DB::raw('count(usr_product_visitor.user_id != "") as users'))
        ->orderBy('users', 'DESC');
		}else if($latest == 1){
			$query->orderBy('prd_products.created_at','DESC');
		}else if($low_to_high==1)
            {
           $query->leftJoin('prd_prices', function($query) {
        $query->on('prd_products.id','=','prd_prices.prd_id')
                ->whereRaw('prd_prices.id IN (select MAX(a2.ID) from prd_prices as a2 join prd_products as u2 on u2.id = a2.prd_id group by u2.id)');
        });
        $query->orderBy('price', 'ASC'); 
            }

        else if($high_to_low==1)
            {
            $query->leftJoin('prd_prices', function($query) {
        $query->on('prd_products.id','=','prd_prices.prd_id')
                ->whereRaw('prd_prices.id IN (select MAX(a2.ID) from prd_prices as a2 join prd_products as u2 on u2.id = a2.prd_id group by u2.id)');
        });
        $query->orderBy('price', 'DESC'); 
            }
		else{
			
			$query->orderBy('prd_products.id', 'DESC');
		}
		$query->where('prd_products.is_approved',1)->where('prd_products.visible',1)->where('prd_products.is_active',1)->where('prd_products.is_deleted',0);
		if(isset($occassion) && $occassion>0)
		{
		 
		  $query->where('prd_products.occasion_id',$occassion);  
		}
		if(isset($request->keyword)){
		 // $query->where('prd_products.name', 'LIKE', '%'.$request->keyword.'%'); 
		  $query->where(DB::raw('lower(prd_products.name)'), 'like', '%' . strtolower($request->keyword) . '%');
		}
		
		
		
        $query->when($category, function ($q,$category) {
            return $q->whereIn('prd_products.category_id', $category);
        })
        ->when($subcategory, function ($q,$subcategory) {
            return $q->whereIn('prd_products.sub_category_id', $subcategory);
        })
        ->when($brand, function ($q,$brand) {
            return $q->whereIn('prd_products.brand_id', $brand);
        });
         
		if ($max_price!='' && $min_price!='') {
			$query->join('prd_prices', 'prd_prices.prd_id', '=', 'prd_products.id')
			->whereBetween('prd_prices.price', [$min_price, $max_price]);
		}
		
	
		
        
      $prod_data_count = $query->groupBy('prd_products.id')->paginate(); 
      $prod_data = $query->groupBy('prd_products.id')->skip($offset)->take($limit)->get(); 
        //$prod_data = $query->groupBy('prd_products.id')->get(); 
     //  dd(\DB::getQueryLog());
            if(!empty($prod_data))
            {
                foreach($prod_data as $row)
                {
                   
                    //$prd_list['service_status']=$store_active->service_status;     
                    $prd_list['product_id']=$row->id;
                    $prd_list['product_name']=$this->get_content($row->name_cnt_id,$lang);
                    $prd_list['category_id']=$row->category_id;
                    $prd_list['category_name']=$this->get_content(@$row->category->cat_name_cid,$lang);
                    $prd_list['subcategory_id']=$row->sub_category_id;
                    $prd_list['subcategory_name']=$this->get_content(@$row->subCategory->sub_name_cid,$lang);
                    if($row->brand_id)
                    {
                    $prd_list['brand_id']=$row->brand_id;
                    $prd_list['brand_name']=$this->get_content(@$row->brand->brand_name_cid,$lang);
                    }
                    else
                    {
                    $prd_list['brand_id']='';
                    $prd_list['brand_name']='';  
                    }
                    if($row->product_type==1)
                    {
                    $prd_list['product_type']='simple';    
					$prd_list['min_order_qty']=$row->min_order;
					$prd_list['bulk_order_qty']=$row->bulk_order;	
						
                        $product_list_price=get_crm_price($row,$type=1,$user);
                        foreach ($product_list_price as $key => $value) {
                            $prd_list[$key] = $value;
                        } 
						
					    $prd_list['stock']=$row->prdStock($row->id);
					    //print_r($row->is_out_of_stock);
                        if($prd_list['stock'] <= 0)
                        {
                            $prd_list['is_out_of_stock']=true;
                        }
                        else
                        {
                            $prd_list['is_out_of_stock']=false;
                        }
                        if($row->out_of_stock_selling==0)
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
					 $product_list_price=get_crm_price($row,$type=1,$user);
                        foreach ($product_list_price as $key => $value) {
                            $prd_list[$key] = $value;
                        } 
						
						$prd_list['stock']=NULL;
                        $prd_list['is_out_of_stock']=NULL;
                        $prd_list['out_of_stock_selling']=NULL;
                        	
						
					}  
			     	$prd_list['occasion_id']=$row->occasion_id;
                    $prd_list['short_description']=$this->get_content($row->short_desc_cnt_id,$lang);
                    $prd_list['tag']=$this->get_product_tag($row->id,$lang);
                    if($prd_list['category_id']){
						$category=Category::where('category_id',$row->category_id)->first();
						$prd_list['is_rating']=$category->is_rating;
					}else{
						
						$prd_list['is_rating']=0;
					}
					$prd_list['rating']=$this->get_rates($row->id);
                    $prd_list['total_reviews']=$this->get_rates_count($row->id);
                    //$prd_list['seller']=$row->Store($row->seller_id)->store_name;
                    //$prd_list['seller_id']=$row->seller_id;
                    $prd_list['image']=$this->get_product_image($row->id); 
				
            $prd_list['offers']=$this->available_offers($row->id,$lang); 
            $products[]=$prd_list;
            
                
                }
	
             
			
			
			
			
           if(!empty($products))
           {
               $total_products=$prod_data_count->total();
           }
           else
           {
               $total_products=0;
           }
           
        //   if(isset($request->keyword))
        //   {
        //       $products = (array_search($request->keyword, $products, true));
        //   }
            return response()->json(['httpcode'=>200,'status'=>'success','page'=>'Product filter listing','data'=>['products'=>$products,'total_products'=>$total_products,'currency'=>getCurrency()->name]]);

            }
            else
            {
               return response()->json(['httpcode'=>200,'status'=>'success','page'=>'Product filter listing','message'=>'Product not found']); 
            }        
    } 
     
	public function product_list_filter_high(Request $request)
    {
        
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
		
		
		$lang=$request->lang_id;
        $category    = $request->category_id;
        $subcategory = $request->subcategory_id;
        $brand       = $request->brand_id;
        $high_to_low = $request->high_to_low;
        $offset = $request->offset;
        $limit = $request->limit;
		$max_price   = $request->max_price;
        $min_price   = $request->min_price;
        $store_id   = $request->store_id;

         if($request->offset==''){
			$offset =0; 
		 }
		 if($request->limit==''){
			$limit =10; 
		 }
        $products=[];
		\DB::enableQueryLog();
        $query = Product::query();
		$query->select('prd_products.*');

        if ($max_price!='' && $min_price>=0) {  
         $query->leftJoin('crm_salespricelist', function($query) {
        $query->on('prd_products.id','=','crm_salespricelist.prd_id')
                ->whereRaw('crm_salespricelist.SalesPriceListId IN (select MAX(a2.SalesPriceListId) from crm_salespricelist as a2 join prd_products as u2 on u2.id = a2.prd_id group by u2.id)');
        })->whereBetween('crm_salespricelist.Amount', [$min_price, $max_price]);
        }else{
            $query->leftJoin('crm_salespricelist', function($query) {
        $query->on('prd_products.id','=','crm_salespricelist.prd_id')
                ->whereRaw('crm_salespricelist.SalesPriceListId IN (select MAX(a2.SalesPriceListId) from crm_salespricelist as a2 join prd_products as u2 on u2.id = a2.prd_id group by u2.id)');
        });

        }
        

		
        $query->orderBy('Amount', 'DESC');        
            
           
            
		$query->where('prd_products.is_approved',1)->where('prd_products.visible',1)->where('prd_products.is_active',1)->where('prd_products.is_deleted',0);
		$query->when($category, function ($q,$category) {
            return $q->where('prd_products.category_id', $category);
        })
        ->when($subcategory, function ($q,$subcategory) {
            return $q->where('prd_products.sub_category_id', $subcategory);
        })
        ->when($brand, function ($q,$brand) {
            return $q->where('prd_products.brand_id', $brand);
        });
        if ($store_id!='' && $store_id>=0) {  
            $query->join('crm_productbranches', 'crm_productbranches.prd_id', '=', 'prd_products.id')
            ->whereIn('crm_productbranches.BranchId', [$store_id]);
        }		
		 
		if(isset($request->keyword)){
		  $query->where('name', 'like', '%'.$request->keyword.'%');  
		}
		
        $prod_data_count = $query->paginate(); 
        $prod_data = $query->skip($offset)->take($limit)->get(); 

        //$prod_data = $query->groupBy('prd_products.id')->get(); 
      // dd(\DB::getQueryLog());
            if(!empty($prod_data))
            {
                foreach($prod_data as $row)
                {
                   
                    //$prd_list['service_status']=$store_active->service_status;     
                    $prd_list['product_id']=$row->id;
                    $prd_list['product_name']=$this->get_content($row->name_cnt_id,$lang);
                    $prd_list['category_id']=$row->category_id;
                    $prd_list['category_name']=$this->get_content(@$row->category->cat_name_cid,$lang);
                    $prd_list['subcategory_id']=$row->sub_category_id;
                    $prd_list['subcategory_name']=$this->get_content(@$row->subCategory->sub_name_cid,$lang);
                    if($row->brand_id)
                    {
                    $prd_list['brand_id']=$row->brand_id;
                    $prd_list['brand_name']=$this->get_content(@$row->brand->brand_name_cid,$lang);
                    }
                    else
                    {
                    $prd_list['brand_id']='';
                    $prd_list['brand_name']='';  
                    }
                    if($row->crmStore($row->id))
                    {   
                    $stores= $row->crmStore($row->id); 
                    if($stores){ $prd_list['stores'] = implode(",",$stores); } 
                    }else{
                    $prd_list['stores'] = "";
                    }
                    if($row->product_type==1)
                    {
                    $prd_list['product_type']='simple';    
					$prd_list['min_order_qty']=$row->min_order;
					$prd_list['bulk_order_qty']=$row->bulk_order;	
						$product_list_price=get_crm_price($row,$type=1,$user);
                        foreach ($product_list_price as $key => $value) {
                            $prd_list[$key] = $value;
                        } 
						
					    $prd_list['stock']=$row->prdStock($row->id);
                        if($prd_list['stock'] <= 0)
                        {
                            $prd_list['is_out_of_stock']=true;
                        }
                        else
                        {
                            $prd_list['is_out_of_stock']=false;
                        }
                        if($row->out_of_stock_selling==0)
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
					$product_list_price=get_crm_price($row,$type=1,$user);
                        foreach ($product_list_price as $key => $value) {
                            $prd_list[$key] = $value;
                        } 
						
						$prd_list['stock']=NULL;
                        $prd_list['is_out_of_stock']=NULL;
                        $prd_list['out_of_stock_selling']=NULL;
                        	
						
					} 
                    $prd_list['shock_sale_price'] = $this->shock_sale_price($row->id);
                    $prd_list['short_description']=$this->get_content($row->short_desc_cnt_id,$lang);
                    $prd_list['tag']=$this->get_product_tag($row->id,$lang);
                    $prd_list['rating']=$this->get_rates($row->id);
                    if($prd_list['category_id']){
						$category=Category::where('category_id',$row->category_id)->first();
						$prd_list['is_rating']=$category->is_rating;
					}else{
						
						$prd_list['is_rating']=0;
					}
					$prd_list['total_reviews']=$this->get_rates_count($row->id);
                    //$prd_list['seller']=$row->Store($row->seller_id)->store_name;
                    //$prd_list['seller_id']=$row->seller_id;
                    $prd_list['image']=$this->get_product_image($row->id); 
				
           // $prd_list['config_prd']=$associative_prd; 
            $prd_list['offers']=$this->available_offers($row->id,$lang); 
            $products[]=$prd_list;
            
                
                }
			/*array_multisort(array_map(function($element) {
            return $element['actual_price'];
            }, $products), SORT_DESC, $products);*/
			if(!empty($products))
           {
               $total_products=$prod_data_count->total();
           }
           else
           {
               $total_products=0;
           }
                return response()->json(['httpcode'=>200,'status'=>'success','page'=>'Products High to Low','data'=>['products'=>$products,'total_products'=>$total_products,'currency'=>getCurrency()->name]]);

            }
            else
            {
               return response()->json(['httpcode'=>200,'status'=>'success','page'=>'Products High to Low','message'=>'Product not found']); 
            }        
    }
	public function product_list_filter_low(Request $request)
    {
        
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
		
		
		$lang=$request->lang_id;
        $category    = $request->category_id;
        $subcategory = $request->subcategory_id;
        $brand       = $request->brand_id;
        $low_to_high = $request->low_to_high;
        $store_id   = $request->store_id;


        $offset = $request->offset;
        $limit = $request->limit;
		$max_price   = $request->max_price;
        $min_price   = $request->min_price;
         if($request->offset==''){
			$offset =0; 
		 }
		 if($request->limit==''){
			$limit =10; 
		 }
        $products=[];
		//\DB::enableQueryLog();
        $query = Product::query();
		$query->select('prd_products.*');

        if ($max_price!='' && $min_price>=0) {  

            $query->leftJoin('crm_salespricelist', function($query) {
        $query->on('prd_products.id','=','crm_salespricelist.prd_id')
                ->whereRaw('crm_salespricelist.SalesPriceListId IN (select MAX(a2.SalesPriceListId) from crm_salespricelist as a2 join prd_products as u2 on u2.id = a2.prd_id group by u2.id)');
        })->whereBetween('crm_salespricelist.Amount', [$min_price, $max_price]);
         
        }else{

            $query->leftJoin('crm_salespricelist', function($query) {
        $query->on('prd_products.id','=','crm_salespricelist.prd_id')
                ->whereRaw('crm_salespricelist.SalesPriceListId IN (select MAX(a2.SalesPriceListId) from crm_salespricelist as a2 join prd_products as u2 on u2.id = a2.prd_id group by u2.id)');
        });
            
        }

		
        $query->orderBy('Amount', 'ASC'); 
		$query->where('prd_products.is_approved',1)->where('prd_products.visible',1)->where('prd_products.is_active',1)->where('prd_products.is_deleted',0);
		$query->when($category, function ($q,$category) {
            return $q->where('prd_products.category_id', $category);
        })
        ->when($subcategory, function ($q,$subcategory) {
            return $q->where('prd_products.sub_category_id', $subcategory);
        })
        ->when($brand, function ($q,$brand) {
            return $q->where('prd_products.brand_id', $brand);
        });
        if ($store_id!='' && $store_id>=0) {  
        $query->join('crm_productbranches', 'crm_productbranches.prd_id', '=', 'prd_products.id')
        ->whereIn('crm_productbranches.BranchId', [$store_id]);
        }
		if(isset($request->keyword)){
		  $query->where('name', 'like', '%'.$request->keyword.'%');  
		}
        $prod_data_count = $query->groupBy('prd_products.id')->paginate(); 
        $prod_data = $query->groupBy('prd_products.id')->skip($offset)->take($limit)->get(); 
        // dd($prod_data);
       // dd(\DB::getQueryLog());
	  
            if(!empty($prod_data))
            {
                foreach($prod_data as $row)
                {
                   
                    //$prd_list['service_status']=$store_active->service_status;     
                    $prd_list['product_id']=$row->id;
                    $prd_list['product_name']=$this->get_content($row->name_cnt_id,$lang);
                    $prd_list['category_id']=$row->category_id;
                    $prd_list['category_name']=$this->get_content(@$row->category->cat_name_cid,$lang);
                    $prd_list['subcategory_id']=$row->sub_category_id;
                    $prd_list['subcategory_name']=$this->get_content(@$row->subCategory->sub_name_cid,$lang);
                    if($row->brand_id)
                    {
                    $prd_list['brand_id']=$row->brand_id;
                    $prd_list['brand_name']=$this->get_content(@$row->brand->brand_name_cid,$lang);
                    }
                    else
                    {
                    $prd_list['brand_id']='';
                    $prd_list['brand_name']='';  
                    }
                    if($row->crmStore($row->id))
                    {   
                    $stores= $row->crmStore($row->id); 
                    if($stores){ $prd_list['stores'] = implode(",",$stores); } 
                    }else{
                    $prd_list['stores'] = "";
                    }
                    if($row->product_type==1)
                    {
                    $prd_list['product_type']='simple';    
					$prd_list['min_order_qty']=$row->min_order;
					$prd_list['bulk_order_qty']=$row->bulk_order;	
						$filter_prd_price=get_crm_price($row,$type=1,$user);
                        foreach ($filter_prd_price as $key => $value) {
                            $prd_list[$key] = $value;
                        } 

					    $prd_list['stock']=$row->prdStock($row->id);
                       if($prd_list['stock'] <= 0)
                        {
                            $prd_list['is_out_of_stock']=true;
                        }
                        else
                        {
                            $prd_list['is_out_of_stock']=false;
                        }
                        if($row->out_of_stock_selling==0)
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
					$filter_prd_price=get_crm_price($row,$type=1,$user);
                        foreach ($filter_prd_price as $key => $value) {
                            $prd_list[$key] = $value;
                        } 
						$prd_list['stock']=NULL;
                        $prd_list['is_out_of_stock']=NULL;
                        $prd_list['out_of_stock_selling']=NULL;
                        	
						
					} 
                    $prd_list['short_description']=$this->get_content($row->short_desc_cnt_id,$lang);
                    $prd_list['tag']=$this->get_product_tag($row->id,$lang);
                    if($prd_list['category_id']){
						$category=Category::where('category_id',$row->category_id)->first();
						$prd_list['is_rating']=$category->is_rating;
					}else{
						
						$prd_list['is_rating']=0;
					}
					$prd_list['rating']=$this->get_rates($row->id);
                    $prd_list['total_reviews']=$this->get_rates_count($row->id);
                    //$prd_list['seller']=$row->Store($row->seller_id)->store_name;
                    //$prd_list['seller_id']=$row->seller_id;
                    $prd_list['image']=$this->get_product_image($row->id); 
					 
            $prd_list['offers']=$this->available_offers($row->id,$lang); 
            $products[]=$prd_list;
            
                
                }
			
            
            /*array_multisort(array_map(function($element) {
            return $element['actual_price'];
            }, $products), SORT_ASC, $products);*/
			if(!empty($products))
           {
               $total_products=$prod_data_count->total();
           }
           else
           {
               $total_products=0;
           }
                return response()->json(['httpcode'=>200,'status'=>'success','page'=>'Products Low to High','data'=>['products'=>$products,'total_products'=>$total_products,'currency'=>getCurrency()->name]]);

            }
            else
            {
               return response()->json(['httpcode'=>200,'status'=>'success','page'=>'Products Low to High','message'=>'Product not found']); 
            }        
    } 
	public function product_list_filter_popular(Request $request)
    {
        
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
		
		
		$lang=$request->lang_id;
        $category    = $request->category_id;
        $subcategory = $request->subcategory_id;
        $brand       = $request->brand_id;
		$max_price   = $request->max_price;
        $min_price   = $request->min_price;
        $store_id   = $request->store_id;


        $offset = $request->offset;
        $limit = $request->limit;
         if($request->offset==''){
			$offset =0; 
		 }
		 if($request->limit==''){
			$limit =10; 
		 }
        $products=[];
		//\DB::enableQueryLog();
        $query = Product::query();
		$query->select('prd_products.*');
		
		$query->join('usr_product_visitor', 'usr_product_visitor.prd_id', '=', 'prd_products.id')
		->addSelect(DB::raw('count(usr_product_visitor.user_id != "") as users'))
        ->orderBy('users', 'DESC');
		$query->where('prd_products.is_approved',1)->where('prd_products.visible',1)->where('prd_products.is_active',1)->where('prd_products.is_deleted',0);
		$query->when($category, function ($q,$category) {
            return $q->where('prd_products.category_id', $category);
        })
        ->when($subcategory, function ($q,$subcategory) {
            return $q->where('prd_products.sub_category_id', $subcategory);
        })
        ->when($brand, function ($q,$brand) {
            return $q->where('prd_products.brand_id', $brand);
        });
		
		 if ($max_price!='' && $min_price>=0) {  
            $query->join('crm_salespricelist', 'crm_salespricelist.prd_id', '=', 'prd_products.id')
            ->whereBetween('crm_salespricelist.Amount', [$min_price, $max_price]);
        }
        if ($store_id!='' && $store_id>=0) {  
        $query->join('crm_productbranches', 'crm_productbranches.prd_id', '=', 'prd_products.id')
        ->whereIn('crm_productbranches.BranchId', [$store_id]);
        }
		if(isset($request->keyword)){
		  $query->where('name', 'like', '%'.$request->keyword.'%');  
		}
		
        $prod_data_count = $query->groupBy('prd_products.id')->paginate(); 
        $prod_data = $query->groupBy('prd_products.id')->skip($offset)->take($limit)->get(); 
       //dd(\DB::getQueryLog());
            if(!empty($prod_data))
            {
                foreach($prod_data as $row)
                {
                   
                    //$prd_list['service_status']=$store_active->service_status;     
                    $prd_list['product_id']=$row->id;
                    $prd_list['product_name']=$this->get_content($row->name_cnt_id,$lang);
                    $prd_list['category_id']=$row->category_id;
                    $prd_list['category_name']=$this->get_content(@$row->category->cat_name_cid,$lang);
                    $prd_list['subcategory_id']=$row->sub_category_id;
                    $prd_list['subcategory_name']=$this->get_content(@$row->subCategory->sub_name_cid,$lang);
                    if($row->brand_id)
                    {
                    $prd_list['brand_id']=$row->brand_id;
                    $prd_list['brand_name']=$this->get_content(@$row->brand->brand_name_cid,$lang);
                    }
                    else
                    {
                    $prd_list['brand_id']='';
                    $prd_list['brand_name']='';  
                    }
                    if($row->crmStore($row->id))
                    {   
                        $stores= $row->crmStore($row->id); 
                       if($stores){ $prd_list['stores'] = implode(",",$stores); } 
                    }else{
                        $prd_list['stores'] = "";
                    }
                    if($row->product_type==1)
                    {
                    $prd_list['product_type']='simple';    
					$prd_list['min_order_qty']=$row->min_order;
					$prd_list['bulk_order_qty']=$row->bulk_order;	
						$popular_prd_price=get_crm_price($row,$type=1,$user);
                        foreach ($popular_prd_price as $key => $value) {
                            $prd_list[$key] = $value;
                        } 
						
					    $prd_list['stock']=$row->prdStock($row->id);
                        if($prd_list['stock'] <= 0)
                        {
                            $prd_list['is_out_of_stock']=true;
                        }
                        else
                        {
                            $prd_list['is_out_of_stock']=false;
                        }
                        if($row->out_of_stock_selling==0)
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
					 $popular_prd_price=get_crm_price($row,$type=1,$user);
                        foreach ($popular_prd_price as $key => $value) {
                            $prd_list[$key] = $value;
                        } 
						
						$prd_list['stock']=NULL;
                        $prd_list['is_out_of_stock']=NULL;
                        $prd_list['out_of_stock_selling']=NULL;
                        	
						
					} 
                    $prd_list['shock_sale_price'] = $this->shock_sale_price($row->id);
                    $prd_list['short_description']=$this->get_content($row->short_desc_cnt_id,$lang);
                    $prd_list['tag']=$this->get_product_tag($row->id,$lang);
                    $prd_list['rating']=$this->get_rates($row->id);
                    if($prd_list['category_id']){
						$category=Category::where('category_id',$row->category_id)->first();
						$prd_list['is_rating']=$category->is_rating;
					}else{
						
						$prd_list['is_rating']=0;
					}
					$prd_list['total_reviews']=$this->get_rates_count($row->id);
                    //$prd_list['seller']=$row->Store($row->seller_id)->store_name;
                    //$prd_list['seller_id']=$row->seller_id;
                    $prd_list['image']=$this->get_product_image($row->id); 
					
            $prd_list['offers']=$this->available_offers($row->id,$lang); 
            $products[]=$prd_list;
            
                
                }
			
           if(!empty($products))
           {
               $total_products=$prod_data_count->total();
           }
           else
           {
               $total_products=0;
           }
                return response()->json(['httpcode'=>200,'status'=>'success','page'=>'Popular Products','data'=>['products'=>$products,'total_products'=>$total_products,'currency'=>getCurrency()->name]]);

            }
            else
            {
               return response()->json(['httpcode'=>200,'status'=>'success','page'=>'Popular Products','message'=>'Product not found']); 
            }        
    }
    public function product_list_filter_latest(Request $request)
    {
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
		
		
		$lang=$request->lang_id;
        $category    = $request->category_id;
        $subcategory = $request->subcategory_id;
        $brand       = $request->brand_id;
		$max_price   = $request->max_price;
        $min_price   = $request->min_price;
        $store_id   = $request->store_id;
      
        $offset = $request->offset;
        $limit = $request->limit;
         if($request->offset==''){
			$offset =0; 
		 }
		 if($request->limit==''){
			$limit =10; 
		 }
        $products=[];
		//\DB::enableQueryLog();
        $query = Product::query();
		$query->select('prd_products.*');
		$query->orderBy('prd_products.id', 'DESC');
		
		$query->where('prd_products.is_approved',1)->where('prd_products.visible',1)->where('prd_products.is_active',1)->where('prd_products.is_deleted',0);
		$query->when($category, function ($q,$category) {
            return $q->where('prd_products.category_id', $category);
        })
        ->when($subcategory, function ($q,$subcategory) {
            return $q->where('prd_products.sub_category_id', $subcategory);
        })
        ->when($brand, function ($q,$brand) {
            return $q->where('prd_products.brand_id', $brand);
        });
        
         if ($max_price!='' && $min_price>=0) {  
			$query->join('crm_salespricelist', 'crm_salespricelist.prd_id', '=', 'prd_products.id')
			->whereBetween('crm_salespricelist.Amount', [$min_price, $max_price]);
		}

        if ($store_id!='' && $store_id>=0) {  
            $query->join('crm_productbranches', 'crm_productbranches.prd_id', '=', 'prd_products.id')
            ->whereIn('crm_productbranches.BranchId', [$store_id]);
        }
		
		if(isset($request->keyword)){
		  $query->where('name', 'like', '%'.$request->keyword.'%');  
		}
        
		$prod_data_count = $query->groupBy('prd_products.id')->paginate(); 
        $prod_data = $query->groupBy('prd_products.id')->skip($offset)->take($limit)->get(); 
      // dd($prod_data);
            if(!empty($prod_data))
            {
                foreach($prod_data as $row)
                {
                 
                    //$prd_list['service_status']=$store_active->service_status;     
                    $prd_list['product_id']=$row->id; 
                    $prd_list['product_name']=$this->get_content($row->name_cnt_id,$lang);
                    $prd_list['category_id']=$row->category_id;
                    $prd_list['category_name']=$this->get_content(@$row->category->cat_name_cid,$lang);
                    $prd_list['subcategory_id']=$row->sub_category_id;
                    $prd_list['subcategory_name']=$this->get_content(@$row->subCategory->sub_name_cid,$lang);
                    if($row->brand_id)
                    {
                    $prd_list['brand_id']=$row->brand_id;
                    $prd_list['brand_name']=$this->get_content(@$row->brand->brand_name_cid,$lang);
                    }
                    else
                    {
                    $prd_list['brand_id']='';
                    $prd_list['brand_name']='';  
                    }
                    if($row->crmStore($row->id))
                    {   
                        $stores= $row->crmStore($row->id); 
                       if($stores){ $prd_list['stores'] = implode(",",$stores); } 
                    }else{
                        $prd_list['stores'] = "";
                    }
                    if($row->product_type==1)
                    {
                    $prd_list['product_type']='simple';    
					$prd_list['min_order_qty']=$row->min_order;
					$prd_list['bulk_order_qty']=$row->bulk_order;	
						$latest_prd_price=get_crm_price($row,$type=1,$user);
                        foreach ($latest_prd_price as $key => $value) {
                            $prd_list[$key] = $value;
                        } 
						
					    $prd_list['stock']=$row->prdStock($row->id);
                        if($prd_list['stock'] <= 0)
                        {
                            $prd_list['is_out_of_stock']=true;
                        }
                        else
                        {
                            $prd_list['is_out_of_stock']=false;
                        }
                        if($row->out_of_stock_selling==0)
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
					 $latest_prd_price=get_crm_price($row,$type=1,$user);
                        foreach ($latest_prd_price as $key => $value) {
                            $prd_list[$key] = $value;
                        } 
						
						$prd_list['stock']=NULL;
                        $prd_list['is_out_of_stock']=NULL;
                        $prd_list['out_of_stock_selling']=NULL;
                        	
						
					}
					$prd_list['short_description']=$this->get_content($row->short_desc_cnt_id,$lang);
                    $prd_list['tag']=$this->get_product_tag($row->id,$lang);
                    $prd_list['rating']=$this->get_rates($row->id);
                    if($prd_list['category_id']){
						$category=Category::where('category_id',$row->category_id)->first();
						$prd_list['is_rating']=@$category->is_rating;
					}else{
						
						$prd_list['is_rating']=0;
					}
					$prd_list['total_reviews']=$this->get_rates_count($row->id);
                    //$prd_list['seller']=$row->Store($row->seller_id)->store_name;
                    //$prd_list['seller_id']=$row->seller_id;
                    $prd_list['image']=$this->get_product_image($row->id); 
					
            $prd_list['offers']=$this->available_offers($row->id,$lang); 
            $products[]=$prd_list;
            }
			
           if(!empty($products))
           {
               $total_products=$prod_data_count->total();
           }
           else
           {
               $total_products=0;
           }
                return response()->json(['httpcode'=>200,'status'=>'success','page'=>'Latest Products','data'=>['products'=>$products,'total_products'=>$total_products,'currency'=>getCurrency()->name]]);

            }
            else
            {
               return response()->json(['httpcode'=>200,'status'=>'success','page'=>'Latest Products','message'=>'Product not found']); 
            }        
    }
    
     public function featured_products(Request $request){
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
		
		
		$lang=$request->lang_id;
        $category    = $request->category_id;
        $subcategory = $request->subcategory_id;
        $brand       = $request->brand_id;
		$max_price   = $request->max_price;
        $min_price   = $request->min_price;
       
        $offset = $request->offset;
        $limit = $request->limit;
         if($request->offset==''){
			$offset =0; 
		 }
		 if($request->limit==''){
			$limit =10; 
		 }
        $products=[];
		\DB::enableQueryLog();
        $query = Product::query();
		$query->select('prd_products.*');
		$query->orderBy('prd_products.id', 'DESC');
		
		$query->where('prd_products.is_featured',1)->where('prd_products.is_approved',1)->where('prd_products.visible',1)->where('prd_products.is_active',1)->where('prd_products.is_deleted',0);
		$query->when($category, function ($q,$category) {
            return $q->where('prd_products.category_id', $category);
        })
        ->when($subcategory, function ($q,$subcategory) {
            return $q->where('prd_products.sub_category_id', $subcategory);
        })
        ->when($brand, function ($q,$brand) {
            return $q->where('prd_products.brand_id', $brand);
        });
         if ($max_price!='' && $min_price!='') {
			$query->join('prd_prices', 'prd_prices.prd_id', '=', 'prd_products.id')
			->whereBetween('prd_prices.price', [$min_price, $max_price]);
		}
		
		if(isset($request->keyword)){
		  $query->where('name', 'like', '%'.$request->keyword.'%');  
		}
        
		$prod_data_count = $query->groupBy('prd_products.id')->paginate(); 
        $prod_data = $query->groupBy('prd_products.id')->skip($offset)->take($limit)->get(); 
       //dd(\DB::getQueryLog());
            if(!empty($prod_data))
            {
                foreach($prod_data as $row)
                {
                   
                    //$prd_list['service_status']=$store_active->service_status;     
                    $prd_list['product_id']=$row->id;
                    $prd_list['product_name']=$this->get_content($row->name_cnt_id,$lang);
                    $prd_list['category_id']=$row->category_id;
                    $prd_list['category_name']=$this->get_content(@$row->category->cat_name_cid,$lang);
                    $prd_list['subcategory_id']=$row->sub_category_id;
                    $prd_list['subcategory_name']=$this->get_content(@$row->subCategory->sub_name_cid,$lang);
                    if($row->brand_id)
                    {
                    $prd_list['brand_id']=$row->brand_id;
                    $prd_list['brand_name']=$this->get_content(@$row->brand->brand_name_cid,$lang);
                    }
                    else
                    {
                    $prd_list['brand_id']='';
                    $prd_list['brand_name']='';  
                    }
                    if($row->product_type==1)
                    {
                    $prd_list['product_type']='simple';    
					$prd_list['min_order_qty']=$row->min_order;
					$prd_list['bulk_order_qty']=$row->bulk_order;	
						$price=$this->get_price($row->id,$type=1,$login);
						foreach ($price as $item) {
							foreach ($item as $key => $value) {
								$prd_list[$key] = $value;
							} 
						}
						
					    $prd_list['stock']=$row->prdStock($row->id);
                        if($prd_list['stock'] <= 0)
                        {
                            $prd_list['is_out_of_stock']=true;
                        }
                        else
                        {
                            $prd_list['is_out_of_stock']=false;
                        }
                        if($row->out_of_stock_selling==0)
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
					 $minprice_prd_id=$this->min_price_product($row->id);
					 $price=$this->get_price($minprice_prd_id,$type=2,$login);
                     foreach ($price as $item) {
							foreach ($item as $key => $value) {
								$prd_list[$key] = $value;
							} 
						}
						
						$prd_list['stock']=NULL;
                        $prd_list['is_out_of_stock']=NULL;
                        $prd_list['out_of_stock_selling']=NULL;
                        	
						
					} 
					$prd_list['short_description']=$this->get_content($row->short_desc_cnt_id,$lang);
                    $prd_list['tag']=$this->get_product_tag($row->id,$lang);
                    $prd_list['rating']=$this->get_rates($row->id);
                    if($prd_list['category_id']){
						$category=Category::where('category_id',$row->category_id)->first();
						$prd_list['is_rating']=@$category->is_rating;
					}else{
						
						$prd_list['is_rating']=0;
					}
					$prd_list['total_reviews']=$this->get_rates_count($row->id);
                    //$prd_list['seller']=$row->Store($row->seller_id)->store_name;
                    //$prd_list['seller_id']=$row->seller_id;
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
                            
                                $associative_prd[]=$this->ass_related_product1($product_visibility->id,$special_ofr_available,$lang,$login);
                            
                            
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

           // $prd_list['config_prd']=$associative_prd; 
            $prd_list['offers']=$this->available_offers($row->id,$lang); 
            $products[]=$prd_list;
            }
			
           if(!empty($products))
           {
               $total_products=$prod_data_count->total();
           }
           else
           {
               $total_products=0;
           }
                return response()->json(['httpcode'=>200,'status'=>'success','page'=>'Featured Products','data'=>['products'=>$products,'total_products'=>$total_products,'currency'=>getCurrency()->name]]);

            }
            else
            {
               return response()->json(['httpcode'=>200,'status'=>'success','page'=>'Featured Products','message'=>'Product not found']); 
            }  
    } 
    
    public function daily_deals(Request $request)
    {
        $lang=$request->lang_id;

        $category    = $request->category_id;
        $subcategory = $request->subcategory_id;
        $brand       = $request->brand_id;
        $max_price   = $request->max_price;
        $min_price   = $request->min_price;
        $latest      = $request->latest;
        $low_to_high = $request->low_to_high;
        $high_to_low = $request->high_to_low;

        $products=[];
        $query = Product::query();

        
        
        if ($max_price!='' && $min_price!='') {
        $query->join('prd_prices', 'prd_prices.prd_id', '=', 'prd_products.id')
        ->where('prd_products.is_active',1)->where('prd_products.is_deleted',0)->where('prd_products.is_approved',1)->where('prd_products.daily_deals',1)
        ->where('prd_prices.is_deleted',0)
        ->when($category, function ($q,$category) {
            return $q->where('prd_products.category_id', $category);
        })
        ->when($subcategory, function ($q,$subcategory) {
            return $q->where('prd_products.sub_category_id', $subcategory);
        })
        ->when($brand, function ($q,$brand) {
            return $q->where('prd_products.brand_id', $brand);
        })
        ->when($latest == 1, function ($q,$latest) {
            return $q->orderBy('prd_products.created_at','DESC');
        })
        ->whereBetween('prd_prices.price', [$min_price, $max_price])
        ->select('prd_products.*');
        }
        else
        {
            if($low_to_high==1)
            {
        $query->join('prd_prices', 'prd_prices.prd_id', '=', 'prd_products.id')
        ->where('prd_products.is_active',1)->where('prd_products.is_deleted',0)->where('prd_products.is_approved',1)->where('prd_products.daily_deals',1)
        ->where('prd_prices.is_deleted',0)
        ->when($category, function ($q,$category) {
            return $q->where('prd_products.category_id', $category);
        })
        ->when($subcategory, function ($q,$subcategory) {
            return $q->where('prd_products.sub_category_id', $subcategory);
        })
        ->when($brand, function ($q,$brand) {
            return $q->where('prd_products.brand_id', $brand);
        })
        ->when($latest == 1, function ($q,$latest) {
            return $q->orderBy('prd_products.created_at','DESC');
        })
        ->select('prd_products.*')
        ->orderBy('prd_prices.price', 'ASC');
            }

           if($high_to_low==1)
            {
        $query->join('prd_prices', 'prd_prices.prd_id', '=', 'prd_products.id')
        ->where('prd_products.is_active',1)->where('prd_products.is_deleted',0)->where('prd_products.is_approved',1)->where('prd_products.daily_deals',1)
        ->where('prd_prices.is_deleted',0)
        ->when($category, function ($q,$category) {
            return $q->where('prd_products.category_id', $category);
        })
        ->when($subcategory, function ($q,$subcategory) {
            return $q->where('prd_products.sub_category_id', $subcategory);
        })
        ->when($brand, function ($q,$brand) {
            return $q->where('prd_products.brand_id', $brand);
        })
        ->when($latest == 1, function ($q,$latest) {
            return $q->orderBy('prd_products.created_at','DESC');
        })
        ->select('prd_products.*')
        ->orderBy('prd_prices.price', 'DESC');
            } 

          if($category!='' || $subcategory!='' || $brand!='' || $latest!='') 
          { 

            if($low_to_high!=1 && $high_to_low!=1 && $min_price=='' && $max_price==''){
            $query->where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->where('daily_deals',1);
        $query->when($category, function ($q,$category) {
            return $q->where('category_id', $category);
        });
        $query->when($subcategory, function ($q,$subcategory) {
            return $q->where('sub_category_id', $subcategory);
        });
        $query->when($brand, function ($q,$brand) {
            return $q->where('brand_id', $brand);
        });
        $query->when($latest == 1, function ($q,$latest) {
            return $q->orderBy('created_at','DESC');
        });
            }
          }
          if($low_to_high!=1 && $high_to_low!=1 && $min_price=='' && $max_price=='')
            {
        $query->where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->where('daily_deals',1);
        $query->when($category, function ($q,$category) {
            return $q->where('category_id', $category);
        });
        $query->when($subcategory, function ($q,$subcategory) {
            return $q->where('sub_category_id', $subcategory);
        });
        $query->when($brand, function ($q,$brand) {
            return $q->where('brand_id', $brand);
        });
        $query->when($latest == 1, function ($q,$latest) {
            return $q->orderBy('created_at','DESC');
        });
            }
        }

        $prod_data = $query->groupBy('prd_products.id')->paginate(12);   

            

       
            if(!empty($prod_data))
            {
                foreach($prod_data as $row)
                {
                    $store_active = Store::where('is_active',1)->where('seller_id',$row->seller_id)->first();
                    if($store_active)
                    {
                    $prd_list['service_status']=$store_active->service_status;     
                    $prd_list['product_id']=$row->id;
                    $prd_list['seller_id']=$row->seller_id;
                    $prd_list['seller']=$row->Store($row->seller_id)->store_name;
                    $prd_list['product_name']=$this->get_content($row->name_cnt_id,$lang);
                    $prd_list['category_id']=$row->category_id;
                    $prd_list['category_name']=$this->get_content(@$row->category->cat_name_cid,$lang);
                    $prd_list['subcategory_id']=$row->sub_category_id;
                    $prd_list['subcategory_name']=$this->get_content(@$row->subCategory->sub_name_cid,$lang);
                    if($row->brand_id)
                    {
                    $prd_list['brand_id']=$row->brand_id;
                    $prd_list['brand_name']=$this->get_content(@$row->brand->brand_name_cid,$lang);
                    }
                    else
                    {
                    $prd_list['brand_id']='';
                    $prd_list['brand_name']='';  
                    }
                    if($row->product_type==1)
                    {
                    $prd_list['product_type']='simple';     
                    $prd_list['actual_price']=$this->get_actual_price($row->id);
                    $prd_list['sale_price']=$this->get_sale_price($row->id);
                    $prd_list['special_ofr_price']=$this->get_special_ofr_price($row->id,$prd_list['actual_price']);
                    }
                    else
                    {
                    $prd_list['product_type']='config';     
                    $prd_list['actual_price']=$this->config_product_price($row->id);
                    $prd_list['sale_price']=$this->config_product_sale_price($row->id); 
                    $c_price= $prd_list['actual_price'];
                    $prd_list['special_ofr_price']=$this->get_special_ofr_price($row->id,$c_price);
                    }
                    $prd_list['shock_sale_price'] = $this->shock_sale_price($row->id);
                    $prd_list['short_description']=$this->get_content($row->short_desc_cnt_id,$lang);
                    $prd_list['tag']=$this->get_product_tag($row->id,$lang);
                    $prd_list['rating']=$this->get_rates($row->id);
                    $prd_list['total_reviews']=$this->get_rates_count($row->id);
                    $prd_list['seller']=$row->Store($row->seller_id)->store_name;
                    $prd_list['image']=$this->get_product_image($row->id); 
            

                  

            $prd_list['offers']=$this->available_offers($row->id,$lang); 
            $products[]=$prd_list;
            
                }//Active store
                }
           
           if(!empty($products))
           {
               $total_products=$prod_data->total();
           }
           else
           {
               $total_products=0;
           }
                return response()->json(['httpcode'=>200,'status'=>'success','data'=>['products'=>$products,'total_products'=>$total_products]]);

            }
            else
            {
               return response()->json(['httpcode'=>200,'status'=>'success','message'=>'Product not found']); 
            }       
    }

    function available_offers($prd_id,$lang)
    {
        //Available offers for this product
            $current_date=Carbon::now();
            $deals= Product::where('is_active',1)->where('is_deleted',0)->where('id',$prd_id)->where('daily_deals',1)->first();
            $shock = PrdShock_Sale::where('is_active',1)->where('is_deleted',0)->where('prd_id',$prd_id)->whereDate('start_time','<=',$current_date)->whereDate('end_time','>=',$current_date)->first();
            if($deals)
            {
                $offer['offer_name']= 'Daily Deals';   
                $offer['product_id']=$prd_id;
                $offer['url']='';
                $offer_list[]=$offer;
            }
            else if($shock)
            {
                $offer['offer_name']= 'Shocking Sale';   
                $offer['shock_sale_id']=$shock->id;
                $offer['url']=url('api/customer/shock-sale');
                $offer_list[]=$offer;
            }
            else
            {
                $offer_list=[];
            }

            return $offer_list;
    }

    //SEARCH INSIDE SHOP
    public function product_search(Request $request)
    {
        $lang=$request->lang_id;
		$offset = $request->offset;
        $limit = $request->limit;
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

        $products=[];
        
        $products_p=[];
        $products_c=[];
        $products_s=[];
        $products_b=[];
        $products_t=[];
        $data_collect=[];
        $no_of_prds=0;
        if($request->keyword){
        //  $data =   DB::table('cms_content')
        // ->join('prd_products', 'cms_content.cnt_id', '=', 'prd_products.name_cnt_id')
        // ->join('category', 'prd_products.category_id', '=', 'category.category_id')
        // ->where('cms_content.content', 'Like', '%' . $request->keyword . '%')
        // ->where('prd_products.seller_id',$request->seller_id)
        // ->get();

         $data_product = DB::table('cms_content')
        ->join('prd_products',function($q){
            $q->on('prd_products.name_cnt_id' ,'cms_content.cnt_id');
        })->select(['prd_products.id'])
        ->where('cms_content.content', 'Like', '%' . $request->keyword . '%')
        ->get();
        $data_category = DB::table('cms_content')
        ->join('category',function($q){
            $q->on('category.cat_name_cid' ,'cms_content.cnt_id');
        })->select(['category.category_id'])
        ->where('cms_content.content', 'Like', '%' . $request->keyword . '%')
        ->get();

        $data_subcategory = DB::table('cms_content')
        ->join('subcategory',function($q){
            $q->on('subcategory.sub_name_cid' ,'cms_content.cnt_id');
        })->select(['subcategory.subcategory_id'])
        ->where('cms_content.content', 'Like', '%' . $request->keyword . '%')
        ->get();

        $data_brand = DB::table('cms_content')
        ->join('prd_brand',function($q){
            $q->on('prd_brand.brand_name_cid' ,'cms_content.cnt_id');
        })->select(['prd_brand.id'])
        ->where('cms_content.content', 'Like', '%' . $request->keyword . '%')
        ->get();

        if(count($data_product)>0)
        {
            foreach($data_product as $key)
            {
                $products=$this->get_search_products($key->id,$category='',$subcategory='',$brand='',$tag='',$request->lang_id,$login);
                if($products!='' && !empty($products))
                {   $products_p=array_filter($products, function($v){ 
 return !is_null($v) && $v !== ''; 
});
                    $products_p=array_values($products_p);
                }
            }
        }
       if(count($data_category)>0)
       {
            foreach($data_category as $row)
            {
                if($row->category_id!='')
                {
                $products=$this->get_search_products($prd_id='',$row->category_id,$subcategory='',$brand='',$tag='',$request->lang_id,$login);
                if($products!='' && !empty($products))
                {   $products_c=array_filter($products, function($v){ 
 return !is_null($v) && $v !== ''; 
});
                    $products_c=array_values($products_c);
                }
               }
            }
        }
        if(count($data_subcategory)>0)
       {
            foreach($data_subcategory as $row)
            {
                if($row->subcategory_id!='')
                {
                $products=$this->get_search_products($prd_id='',$category='',$row->subcategory_id,$brand='',$tag='',$request->lang_id,$login);
                if($products!='' && !empty($products))
                {   $products_s=array_filter($products, function($v){ 
 return !is_null($v) && $v !== ''; 
});
                    $products_s=array_values($products_s);
                }
               }
            }
        }

        if(count($data_brand)>0)
       {
            foreach($data_brand as $row)
            {
                if($row->id!='')
                {
                $products=$this->get_search_products($prd_id='',$category='',$subcategory='',$brand=$row->id,$tag='',$request->lang_id,$login);
                if($products!='' && !empty($products))
                {   $products_b=array_filter($products, function($v){ 
 return !is_null($v) && $v !== ''; 
});
                    $products_b=array_values($products_b);
                
				}
               }
            }
			//dd($data_brand);
        }
        
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
       
    //   $products_page = $this->paginate($product_collect);
    //   $products_page_data = $products_page->items();
   
       $products_page_data=array_unique($product_collect, SORT_REGULAR);
        //dd(count($products_page_data));
       $products_page_data=array_filter($product_collect);
       $data_collect=array_slice($products_page_data, $offset, $limit);
       $no_of_prds= count($products_page_data);

        }
        
        return ['httpcode'=>200,'status'=>'success','data'=>['products'=>$data_collect,'count'=>$no_of_prds]];

   }


//Get products
   function get_search_products($prd_id,$category,$subcategory,$brand,$tag,$lang,$login)
   {
     $prod=[];
    if($prd_id!='' )
    {
    $prod_data= Product::where('is_active',1)->where('is_deleted',0)->where('visible',1)->where('id',$prd_id)->get();
    }
    if($category!='')
    {
     $prod_data= Product::where('is_active',1)->where('is_deleted',0)->where('visible',1)->where('category_id',$category)->get(); 
    }
    if($subcategory!='')
    {
     $prod_data= Product::where('is_active',1)->where('is_deleted',0)->where('visible',1)->where('sub_category_id',$subcategory)->get(); 
    }
    if($brand!='')
    {
     $prod_data= Product::where('is_active',1)->where('is_deleted',0)->where('visible',1)->where('brand_id',$brand)->get(); 
    }
            if(count($prod_data)>0)
            {
                foreach($prod_data as $row){
                   
                    //$prd_list['service_status']=$store_active->service_status;     
                    $prd_list['product_id']=$row->id;
                    $prd_list['product_name']=$this->get_content($row->name_cnt_id,$lang);
                    $prd_list['category_id']=$row->category_id;
                    $prd_list['category_name']=$this->get_content(@$row->category->cat_name_cid,$lang);
                    $prd_list['subcategory_id']=$row->sub_category_id;
                    $prd_list['subcategory_name']=$this->get_content(@$row->subCategory->sub_name_cid,$lang);
                    if($row->brand_id)
                    {
                    $prd_list['brand_id']=$row->brand_id;
                    $prd_list['brand_name']=$this->get_content(@$row->brand->brand_name_cid,$lang);
                    }
                    else
                    {
                    $prd_list['brand_id']='';
                    $prd_list['brand_name']='';  
                    }
                   if($row->product_type==1)
                    {
                    $prd_list['product_type']='simple';    
					$prd_list['min_order_qty']=$row->min_order;
					$prd_list['bulk_order_qty']=$row->bulk_order;	
						$price=$this->get_price($row->id,$type=1,$login);
						foreach ($price as $item) {
							foreach ($item as $key => $value) {
								$prd_list[$key] = $value;
							} 
						}
						
					    $prd_list['stock']=$row->prdStock($row->id);
                        if($prd_list['stock'] <= 0)
                        {
                            $prd_list['is_out_of_stock']=true;
                        }
                        else
                        {
                            $prd_list['is_out_of_stock']=false;
                        }
                        if($row->out_of_stock_selling==0)
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
					 $minprice_prd_id=$this->min_price_product($row->id);
					 $price=$this->get_price($minprice_prd_id,$type=2,$login);
                     foreach ($price as $item) {
							foreach ($item as $key => $value) {
								$prd_list[$key] = $value;
							} 
						}
						
						$prd_list['stock']=NULL;
                        $prd_list['is_out_of_stock']=NULL;
                        $prd_list['out_of_stock_selling']=NULL;
                        	
						
					} 
					$prd_list['short_description']=$this->get_content($row->short_desc_cnt_id,$lang);
                    $prd_list['tag']=$this->get_product_tag($row->id,$lang);
                    $prd_list['rating']=$this->get_rates($row->id);
                    if($prd_list['category_id']){
						$category=Category::where('category_id',$row->category_id)->first();
						$prd_list['is_rating']=@$category->is_rating;
					}else{
						
						$prd_list['is_rating']=0;
					}
					$prd_list['total_reviews']=$this->get_rates_count($row->id);
                    //$prd_list['seller']=$row->Store($row->seller_id)->store_name;
                    //$prd_list['seller_id']=$row->seller_id;
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
                            
                                $associative_prd[]=$this->ass_related_product1($product_visibility->id,$special_ofr_available,$lang,$login);
                            
                            
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

           // $prd_list['config_prd']=$associative_prd; 
           // $prd_list['offers']=$this->available_offers($row->id,$lang); 
            $prod[]=$prd_list;
           
            }
            
                   return $prod;
           }
          
}

// GET sub category
    function get_subcategory($cat_id){
        $data     =   [];
        
        $subcat       =   Subcategory::where('category_id',$cat_id)->where('is_active',1)->get(['subcategory_id','sub_name_cid']); 
            if($subcat)   {   foreach($subcat as $k=>$row){ 
                $val['id']    =   $row->subcategory_id;
                $val['subcategory_name']   =  $this->get_content($row->sub_name_cid,$lang='');
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

    function get_product_tag($prd_id,$lang){
        $data     =   [];
        
        //$product       =   PrdAssignedTag::where('prd_id',$prd_id)->get(); 
        $product       =   Product::where('id',$prd_id)->first();
            if($product->tag_id)   {    
               // $val['tag_name']    =   $this->get_content($product->tag->tag_name_cid,$lang);
                  $val                =   $this->get_content($product->tag->tag_name_cid,$lang);
                  $data[]               =   $val;
            }
            else{ $data     =   []; } return $data;
        
    }
   
   function ass_related_product($prd_id,$lang){
        $data     =   [];
        
        $prod_data       =   Product::where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->where('visible',0)->where('id',$prd_id)->first();
            if($prod_data)   {    
                $store_active = Store::where('is_active',1)->where('seller_id',$prod_data->seller_id)->first();
                    if($store_active)
                    {
                    $prd_list['service_status']=$store_active->service_status;     
                    $prd_list['product_id']=$prod_data->id;
                    $prd_list['product_name']=$this->get_content($prod_data->name_cnt_id,$lang);
                    $prd_list['category_id']=$prod_data->category_id;
                    $prd_list['category_name']=$this->get_content(@$prod_data->category->cat_name_cid,$lang);
                    $prd_list['subcategory_id']=$prod_data->sub_category_id;
                    $prd_list['subcategory_name']=$this->get_content(@$prod_data->subCategory->sub_name_cid,$lang);
                    if($prod_data->brand_id)
                    {
                    $prd_list['brand_id']=$prod_data->brand_id;
                    $prd_list['brand_name']=$this->get_content($prod_data->brand->brand_name_cid,$lang);
                    }
                    else
                    {
                    $prd_list['brand_id']='';
                    $prd_list['brand_name']='';
                    }
                    $prd_list['short_description']=$this->get_content($prod_data->short_desc_cnt_id,$lang);
                    $prd_list['long_description']=$this->get_content($prod_data->desc_cnt_id,$lang);
                    $prd_list['content']=$this->get_content($prod_data->content_cnt_id,$lang);
                    $prd_list['attributes']=$this->get_product_attributes($prod_data->id,$lang);
                    $prd_list['available_attributes']=$this->get_product_attributes($prod_data->id,$lang);
                    
                    $prd_list['actual_price']=$prod_data->prdPrice->price;
                    $prd_list['sale_price']=$this->get_sale_price($prod_data->id);
                    
                    // $actual_price = number_format($prod_data->prdPrice->price,2);
                    // $prd_list['actual_price_quote']= $actual_price;
                    // $prd_list['actual_price']= $prod_data->prdPrice->price;
                    // $sale_price =$this->get_sale_price($prod_data->prd_id);
                    // $prd_list['sale_price']=$sale_price;
                    
                    $prd_list['is_out_of_stock']=$prod_data->is_out_of_stock;
                    $prd_list['tag']=$this->get_product_tag($prod_data->id,$lang); 
                    $prd_list['rating']=$this->get_rates($prod_data->id);
                    $prd_list['total_reviews']=$this->get_rates_count($prod_data->id);
                    $prd_list['image']=$this->get_product_image($prod_data->id); 
                    $data             =   $prd_list;
                    }
             }
            else{ $data     =   []; } return $data;
        
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
                    
                    
                    if( $attr_list['stock'] <=0)
                        {
                            $attr_list['is_out_of_stock']=true;
                        }
                        else
                        {
                            $attr_list['is_out_of_stock']=false;
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
    function inner_attribute_12($prd_id,$attr_id,$rowId,$lang)
    {
        $data=[];
       // $rowss = AssignedAttribute::where('is_deleted',0)->where('prd_id',$prd_id)->where('attr_id','!=',$attr_id)->whereNotIn('id',[$rowId])->first();
        $rows1 = AssignedAttribute::where('is_deleted',0)->where('attr_id',$attr_id)->whereNotIn('id',[$rowId])->get();
       foreach($rows1 as $rowss){
                     if($rowss && $rowss->id!=$rowId)
                    {
                       
                        $atr_inn['attr_id']=$rowss->attr_id;
                        $atr_inn['product_id']=$rowss->prd_id;
                        $atr_inn['attr_name']= $this->get_content($rowss->PrdAttr->name_cnt_id,$lang);
                        $atr_inn['attr_value']= $rowss->attr_value;
                        $atr_inn['image']=config('app.storage_url').$rowss->attrValue->image;
                        $actual_price = number_format($rowss->Product->prdPrice->price,2);
                        $atr_inn['actual_price_quote']= $actual_price;
                        $atr_inn['actual_price']= $rowss->Product->prdPrice->price;
                        $sale_price =$this->get_sale_price($rowss->prd_id);
                        $atr_inn['sale_price']=$sale_price;
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
                        $data[]             =   $atr_inn;
                        
                    
                    }}return $data;
    }
    function related_product($prd_id,$lang,$user){
        $data     =   [];
        
        $prod_data       =   Product::where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->where('visible',1)->where('id',$prd_id)->first();
            if($prod_data)   {    
                     
                    $prd_list['product_id']=$prod_data->id;
                    $prd_list['product_name']=$this->get_content($prod_data->name_cnt_id,$lang);
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
						$explore_prd_price=get_crm_price($prod_data,$type=1,$user);
                        foreach ($explore_prd_price as $key => $value) {
                        $prd_list[$key] = $value;
                        } 
						
					    $prd_list['stock']=$prod_data->prdStock($prod_data->id);
                        if($prod_data->is_out_of_stock <= 0)
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
					 $explore_prd_price=get_crm_price($prod_data,$type=1,$user);
                        foreach ($explore_prd_price as $key => $value) {
                        $prd_list[$key] = $value;
                        } 
						
						$prd_list['stock']=NULL;
                        $prd_list['is_out_of_stock']=NULL;
                        $prd_list['out_of_stock_selling']=NULL;
                        	
						
					} 
                    $prd_list['is_out_of_stock']=$prod_data->is_out_of_stock;
                    $prd_list['tag']=$this->get_product_tag($prod_data->id,$lang); 
                    $prd_list['rating']=$this->get_rates($prod_data->id);
                    $prd_list['total_reviews']=$this->get_rates_count($prod_data->id);
                    $prd_list['image']=$this->get_product_image($prod_data->id); 
                    
				  
					
					$data             =   $prd_list;
                    
             }
            else{ $data     =   []; } return $data;
        
    }
    function viewed_products($prd_id,$lang,$user){
        $data      =   [];
	    $prod_data =   Product::where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->where('visible',1)->where('id',$prd_id)->first();
        if($prod_data)   {    
                     
                    $prd_list['product_id']=$prod_data->id;
                    $prd_list['product_name']=$this->get_content($prod_data->name_cnt_id,$lang);
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
						$explore_prd_price=get_crm_price($prod_data,$type=1,$user);
                        foreach ($explore_prd_price as $key => $value) {
                        $prd_list[$key] = $value;
                        } 
						
					    $prd_list['stock']=$prod_data->prdStock($prod_data->id);
                        if($prod_data->is_out_of_stock <= 0)
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
					 $explore_prd_price=get_crm_price($prod_data,$type=1,$user);
                        foreach ($explore_prd_price as $key => $value) {
                        $prd_list[$key] = $value;
                        } 
						
						$prd_list['stock']=NULL;
                        $prd_list['is_out_of_stock']=NULL;
                        $prd_list['out_of_stock_selling']=NULL;
                        	
						
					}
                   // $prd_list['is_out_of_stock']=$prod_data->is_out_of_stock;
                    $prd_list['tag']=$this->get_product_tag($prod_data->id,$lang); 
                    $prd_list['rating']=$this->get_rates($prod_data->id);
                    $prd_list['total_reviews']=$this->get_rates_count($prod_data->id);
                    $prd_list['image']=$this->get_product_image($prod_data->id); 
                     
				  
					
					$data             =   $prd_list;
                    
             }
            else{ $data     =   []; } return $data;
        
    }
    

    //Related brand products
    function related_brand_product($brand_id,$prd_id,$lang){
        $data     =   [];
        
        $brand_data       =   Product::where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->where('brand_id',$brand_id)->where('visible',1)->whereNotIn('id', [$prd_id])->get();
            if(count($brand_data)>0)   {   
              foreach($brand_data as $prod_data) {
                  $store_active = Store::where('is_active',1)->where('seller_id',$prod_data->seller_id)->first();
                    if($store_active)
                    {
                    $prd_list['service_status']=$store_active->service_status;     
                    $prd_list['product_id']=$prod_data->id;
                    $prd_list['product_name']=$this->get_content($prod_data->name_cnt_id,$lang);
                    $prd_list['seller']=$prod_data->Store($prod_data->seller_id)->store_name;
                    $prd_list['category_id']=$prod_data->category_id;
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
                    if($prod_data->product_type==1){
                    $prd_list['actual_price']=$this->get_actual_price($prod_data->id);
                    $prd_list['sale_price']=$this->get_sale_price($prod_data->id);
                    $prd_list['product_type']='simple';
                    }
                    else
                    {
                     $prd_list['actual_price']='';
                    $prd_list['sale_price']='';
                    $prd_list['product_type']='config';
                    }
                    $prd_list['short_description']=$this->get_content($prod_data->short_desc_cnt_id,$lang);
                    $prd_list['long_description']=$this->get_content($prod_data->desc_cnt_id,$lang);
                    $prd_list['content']=$this->get_content($prod_data->content_cnt_id,$lang);
                    $prd_list['actual_price']=$this->get_actual_price($prod_data->id);
                    $prd_list['sale_price']=$this->get_sale_price($prod_data->id);
                    $prd_list['is_out_of_stock']=$prod_data->is_out_of_stock;
                    $prd_list['tag']=$this->get_product_tag($prod_data->id,$lang); 
                    $prd_list['rating']=$this->get_rates($prod_data->id);
                    $prd_list['total_reviews']=$this->get_rates_count($prod_data->id);
                    $prd_list['image']=$this->get_product_image($prod_data->id); 
                    $data[]             =   $prd_list;
                    }
                }

                $order = array_column($data, 'rating');

                array_multisort($order, SORT_DESC, $data);
             }
            else{ $data     =   []; } return $data;
        
    }


    //Related seller products
    function seller_products($seller_id,$lang,$total_pro,$service_status){
        $data     =   [];

        $seller_data       =   Product::where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->where('visible',1)->where('seller_id',$seller_id)->get();

           if($total_pro==''){
            if(count($seller_data)>0)   {   
              foreach($seller_data as $prod_data) {
                    
                    $prd_list['service_status']=$service_status;
                    $prd_list['product_id']=$prod_data->id;
                    $prd_list['product_name']=$this->get_content($prod_data->name_cnt_id,$lang='');
                    $prd_list['seller']=$prod_data->Store($prod_data->seller_id)->store_name;
                    $prd_list['seller_id']=$prod_data->seller_id;
                    $prd_list['category_id']=$prod_data->category_id;
                    $prd_list['category_name']=$this->get_content(@$prod_data->category->cat_name_cid,$lang);
                    $prd_list['subcategory_id']=$prod_data->sub_category_id;
                   // $prd_list['subcategory_name']=$prod_data->get_content($prod_data->subCategory->sub_name_cid);
                    if($prod_data->brand_id)
                    {
                    $prd_list['brand_id']=$prod_data->brand_id;
                    $prd_list['brand_name']=$this->get_content($prod_data->brand->brand_name_cid,$lang);
                    }
                    else
                    {

                        $prd_list['brand_id']="";
                        $prd_list['brand_name']="";
                    }
                    $prd_list['short_description']=$this->get_content($prod_data->short_desc_cnt_id,$lang);
                    $prd_list['long_description']=$this->get_content($prod_data->desc_cnt_id,$lang);
                    $prd_list['content']=$this->get_content($prod_data->content_cnt_id,$lang);
                    if($prod_data->product_type==1){
                    $actual_price = number_format($this->get_actual_price($prod_data->id));
                    $prd_list['actual_price_quote']= $actual_price;
                    $prd_list['actual_price']= $this->get_actual_price($prod_data->id);
                    $sale_price =$this->get_sale_price($prod_data->id);
                    $prd_list['sale_price']=$sale_price;
                    }
                    else{
                      
                    $prd_list['actual_price_quote']= '';
                    $prd_list['actual_price']=$this->config_product_price($prod_data->id);
                    $prd_list['sale_price']=$this->get_sale_price($prod_data->id); 
                    }
                    $prd_list['is_out_of_stock']=$prod_data->is_out_of_stock;
                    $prd_list['tag']=$this->get_product_tag($prod_data->id,$lang); 
                    $prd_list['rating']=$this->get_rates($prod_data->id);
                    $prd_list['total_reviews']=$this->get_rates_count($prod_data->id);
                    $prd_list['image']=$this->get_product_image($prod_data->id); 
                    $data[]             =   $prd_list;
                }

                $order = array_column($data, 'rating');

                array_multisort($order, SORT_DESC, $data);
             }
            else{ $data     =   []; } return $data;

            }//if total==''

            else
            {
                $total=count($seller_data);
                return $total;
            }
        }



  // Product Attributes
    function get_product_attributes($prd_id,$lang){
        $data     =   [];
        
        $prod_data       =   AssignedAttribute::where('is_deleted',0)->where('prd_id',$prd_id)->get();
            if(count($prod_data)>0)   { 
                foreach($prod_data as $row)  {
                   // $attr_list['id']=$row->id;
                    //$attr_list['attr_id']=$row->attr_id;
                    $attr_list['attr_name']=$this->get_content($row->PrdAttr->name_cnt_id,$lang);
                   // $attr_list['attr_type']=$row->PrdAttr->type;
                   // $attr_list['attr_data_type']=$row->PrdAttr->data_type;
                   // $attr_list['attr_value_name']=$this->get_content($row->PrdAttr_value->name_cnt_id,$lang);
                    $attr_list['attr_value']=$row->attr_value;
                    
                    $actual_price = number_format($row->prdPrice->price,2);
                    $attr_list['actual_price_quote']= $actual_price;
                    $attr_list['actual_price']= $row->prdPrice->price;
                    $sale_price =$this->get_sale_price($row->prd_id);
                    $attr_list['sale_price']=$sale_price;
                    
                    $attr_list['image']=config('app.storage_url').$row->attrValue->image;
                    $data[]             =   $attr_list;
                }
             }
            else{ $data     =   []; } return $data;
        
    }

    // Seller/Store Review
        function get_seller_review($field_id){ 
            $data=[];
        $review =SellerReview::where('seller_id',$field_id)->where('is_active',1)->get();
        if(count($review)>0){ 
            foreach($review as $row)
            {
            $user=$row->customerinfo($row->user_id);    
            $list['customer_name']=$list['customer_name']=$user->first_name." ".$user->middle_name." ".$user->last_name;
            $list['title']=$row->title;
            $list['rating']=$row->rating;
            $list['comment']=$row->comment;
            $data[]=$list;
           }
            return $data;
        }
        else
            { 
                return $data; }
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
        
        function get_per_seller_review($field_id){ 

        $rate = DB::table('prd_products')
        ->select(DB::raw('AVG(prd_review.rating) as rating'))
            ->leftJoin('prd_review', 'prd_review.prd_id', 'prd_products.id')
            ->where('prd_products.seller_id',$field_id)
            ->groupBy('prd_products.seller_id')->first(); 
        $count_rate = DB::table('prd_products')
            ->leftJoin('prd_review', 'prd_review.prd_id', 'prd_products.id')
            ->where('prd_products.seller_id',$field_id)
            ->groupBy('prd_products.seller_id')->count();     
        if($rate){ 
        $return_val = $rate->rating;
        $count_review = $count_rate;
        $percentage = ($return_val/$count_review)*100;
        return $percentage;
        }
        else
            { $return_val=0;
                return $return_val; }
        }

        //Each Product review
    function get_product_review($prd_id,$count){
        $data     =   [];
        $prod_data       =   PrdReview::where('is_deleted',0)->where('is_active',1)->where('prd_id',$prd_id)->get();

        if($count==1)
        {
            $count_prd=count($prod_data);
            return $count_prd;
        }

        else{
            if(count($prod_data)>0)   { 
                foreach($prod_data as $row)  {
                    $user=$row->customerinfo($row->user_id);
                    $list['review_id']=$row->id;
                    $list['customer_name']=$user->first_name." ".$user->middle_name." ".$user->last_name;
                    $list['rating']=$this->get_rates($row->prd_id);
                    $list['comment']=$row->comment;
                    if($row->image)
                    {
                    $list['image']=config('app.storage_url')."/app/public/product_review/".$row->image;  
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

     //dd($field_id);
       $current_date=Carbon::now();
       $rows = PrdPrice::where('is_deleted',0)->where('prd_id',$field_id)->whereDate('sale_end_date','>=',$current_date)->orderBy('id','DESC')->first();        
        if($rows){ 
        $return_val = $rows->sale_price;
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

       // No.of products
        function number_of_products($seller_id){
        
        $count       =   Product::where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->where('visible',1)->where('seller_id',$seller_id)->count();
        return $count;
       }

       // No.of orders per seller
        function order_per_seller($seller_id){
        
        $count       =   SaleOrder::where('seller_id',$seller_id)->whereNotIn('order_status', ["cancelled"])->count();
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
        
    public function beverage_products(Request $request){
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
		
		
		$lang=$request->lang_id;
        $category    = $request->category_id;
        $subcategory = $request->subcategory_id;
        $brand       = $request->brand_id;
		$max_price   = $request->max_price;
        $min_price   = $request->min_price;
       
        $offset = $request->offset;
        $limit = $request->limit;
         if($request->offset==''){
			$offset =0; 
		 }
		 if($request->limit==''){
			$limit =10; 
		 }
        $products=[];
		\DB::enableQueryLog();
        $query = Product::query();
		$query->select('prd_products.*');
		$query->orderBy('prd_products.id', 'DESC');
		
		
		$query->where('category_id',34)->where('prd_products.is_approved',1)->where('prd_products.visible',1)->where('prd_products.is_active',1)->where('prd_products.is_deleted',0);
		if ($max_price!='' && $min_price!='') {
			$query->join('prd_prices', 'prd_prices.prd_id', '=', 'prd_products.id')
			->whereBetween('prd_prices.price', [$min_price, $max_price]);
		}
		if(isset($request->keyword)){
		  $query->where('name', 'like', '%'.$request->keyword.'%');  
		}
        
		$prod_data_count = $query->groupBy('prd_products.id')->paginate(); 
        $prod_data = $query->groupBy('prd_products.id')->skip($offset)->take($limit)->get(); 
       //dd(\DB::getQueryLog());
            if(!empty($prod_data))
            {
                foreach($prod_data as $row)
                {
                   
                    //$prd_list['service_status']=$store_active->service_status;     
                    $prd_list['product_id']=$row->id;
                    $prd_list['product_name']=$this->get_content($row->name_cnt_id,$lang);
                    $prd_list['category_id']=$row->category_id;
                    $prd_list['category_name']=$this->get_content(@$row->category->cat_name_cid,$lang);
                    $prd_list['subcategory_id']=$row->sub_category_id;
                    $prd_list['subcategory_name']=$this->get_content(@$row->subCategory->sub_name_cid,$lang);
                    if($row->brand_id)
                    {
                    $prd_list['brand_id']=$row->brand_id;
                    $prd_list['brand_name']=$this->get_content(@$row->brand->brand_name_cid,$lang);
                    }
                    else
                    {
                    $prd_list['brand_id']='';
                    $prd_list['brand_name']='';  
                    }
                    if($row->product_type==1)
                    {
                    $prd_list['product_type']='simple';    
					$prd_list['min_order_qty']=$row->min_order;
					$prd_list['bulk_order_qty']=$row->bulk_order;	
						$price=$this->get_price($row->id,$type=1,$login);
						foreach ($price as $item) {
							foreach ($item as $key => $value) {
								$prd_list[$key] = $value;
							} 
						}
						
					    $prd_list['stock']=$row->prdStock($row->id);
                        if($prd_list['stock'] <= 0)
                        {
                            $prd_list['is_out_of_stock']=true;
                        }
                        else
                        {
                            $prd_list['is_out_of_stock']=false;
                        }
                        if($row->out_of_stock_selling==0)
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
					 $minprice_prd_id=$this->min_price_product($row->id);
					 $price=$this->get_price($minprice_prd_id,$type=2,$login);
                     foreach ($price as $item) {
							foreach ($item as $key => $value) {
								$prd_list[$key] = $value;
							} 
						}
						
						$prd_list['stock']=NULL;
                        $prd_list['is_out_of_stock']=NULL;
                        $prd_list['out_of_stock_selling']=NULL;
                        	
						
					}  
					$prd_list['short_description']=$this->get_content($row->short_desc_cnt_id,$lang);
                    $prd_list['tag']=$this->get_product_tag($row->id,$lang);
                    $prd_list['rating']=$this->get_rates($row->id);
                    if($prd_list['category_id']){
						$category=Category::where('category_id',$row->category_id)->first();
						$prd_list['is_rating']=@$category->is_rating;
					}else{
						
						$prd_list['is_rating']=0;
					}
					$prd_list['total_reviews']=$this->get_rates_count($row->id);
                    //$prd_list['seller']=$row->Store($row->seller_id)->store_name;
                    //$prd_list['seller_id']=$row->seller_id;
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
                            
                                $associative_prd[]=$this->ass_related_product1($product_visibility->id,$special_ofr_available,$lang,$login);
                            
                            
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

           // $prd_list['config_prd']=$associative_prd; 
            $prd_list['offers']=$this->available_offers($row->id,$lang); 
            $products[]=$prd_list;
            }
			
           if(!empty($products))
           {
               $total_products=$prod_data_count->total();
           }
           else
           {
               $total_products=0;
           }
                return response()->json(['httpcode'=>200,'status'=>'success','page'=>'Beverage Products','data'=>['products'=>$products,'total_products'=>$total_products,'currency'=>getCurrency()->name]]);

            }
            else
            {
               return response()->json(['httpcode'=>200,'status'=>'success','page'=>'Beverage Products','message'=>'Product not found']); 
            }  
    }
    
     public function explore_products(Request $request){
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
		
		
		$lang=$request->lang_id;
        $category    = $request->category_id;
        $subcategory = $request->subcategory_id;
        $brand       = $request->brand_id;
		$max_price   = $request->max_price;
        $min_price   = $request->min_price;
       
        $offset = $request->offset;
        $limit = $request->limit;
         if($request->offset==''){
			$offset =0; 
		 }
		 if($request->limit==''){
			$limit ="100"; 
		 }
        $products=[];
	//	\DB::enableQueryLog();
        $query = Product::query();
		$query->select('prd_products.*');
		$query->orderBy('prd_products.id', 'DESC');
		
		$query->where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->where('visible',1);
		$query->when($category, function ($q,$category) {
            return $q->where('prd_products.category_id', $category);
        })
        ->when($subcategory, function ($q,$subcategory) {
            return $q->where('prd_products.sub_category_id', $subcategory);
        })
        ->when($brand, function ($q,$brand) {
            return $q->where('prd_products.brand_id', $brand);
        });
         if ($max_price!='' && $min_price!='') {
			$query->join('prd_prices', 'prd_prices.prd_id', '=', 'prd_products.id')
			->whereBetween('prd_prices.price', [$min_price, $max_price]);
		}
		
		if(isset($request->keyword)){
		  $query->where('name', 'like', '%'.$request->keyword.'%');  
		}
        
		$prod_data_count = $query->groupBy('prd_products.id')->paginate(); 
        $prod_data = $query->groupBy('prd_products.id')->skip($offset)->take($limit)->inRandomOrder()->get();
       //dd(\DB::getQueryLog());
            if(!empty($prod_data))
            {
                foreach($prod_data as $row)
                {
                   
                    //$prd_list['service_status']=$store_active->service_status;     
                    $prd_list['product_id']=$row->id;
                    $prd_list['product_name']=$this->get_content($row->name_cnt_id,$lang);
                    $prd_list['category_id']=$row->category_id;
                    $prd_list['category_name']=$this->get_content(@$row->category->cat_name_cid,$lang);
                    $prd_list['subcategory_id']=$row->sub_category_id;
                    $prd_list['subcategory_name']=$this->get_content(@$row->subCategory->sub_name_cid,$lang);
                    if($row->brand_id)
                    {
                    $prd_list['brand_id']=$row->brand_id;
                    $prd_list['brand_name']=$this->get_content(@$row->brand->brand_name_cid,$lang);
                    }
                    else
                    {
                    $prd_list['brand_id']='';
                    $prd_list['brand_name']='';  
                    }
                    if($row->product_type==1)
                    {
                    $prd_list['product_type']='simple';    
					$prd_list['min_order_qty']=$row->min_order;
					$prd_list['bulk_order_qty']=$row->bulk_order;	
						$price=$this->get_price($row->id,$type=1,$login);
						foreach ($price as $item) {
							foreach ($item as $key => $value) {
								$prd_list[$key] = $value;
							} 
						}
						
					    $prd_list['stock']=$row->prdStock($row->id);
                        if($prd_list['stock'] <= 0)
                        {
                            $prd_list['is_out_of_stock']=true;
                        }
                        else
                        {
                            $prd_list['is_out_of_stock']=false;
                        }
                        if($row->out_of_stock_selling==0)
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
					 $minprice_prd_id=$this->min_price_product($row->id);
					 $price=$this->get_price($minprice_prd_id,$type=2,$login);
                     foreach ($price as $item) {
							foreach ($item as $key => $value) {
								$prd_list[$key] = $value;
							} 
						}
						
						$prd_list['stock']=NULL;
                        $prd_list['is_out_of_stock']=NULL;
                        $prd_list['out_of_stock_selling']=NULL;
                        	
						
					}  
					$prd_list['short_description']=$this->get_content($row->short_desc_cnt_id,$lang);
                    $prd_list['tag']=$this->get_product_tag($row->id,$lang);
                    $prd_list['rating']=$this->get_rates($row->id);
                    if($prd_list['category_id']){
						$category=Category::where('category_id',$row->category_id)->first();
						$prd_list['is_rating']=@$category->is_rating;
					}else{
						
						$prd_list['is_rating']=0;
					}
					$prd_list['total_reviews']=$this->get_rates_count($row->id);
                    //$prd_list['seller']=$row->Store($row->seller_id)->store_name;
                    //$prd_list['seller_id']=$row->seller_id;
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
                            
                                $associative_prd[]=$this->ass_related_product1($product_visibility->id,$special_ofr_available,$lang,$login);
                            
                            
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

           // $prd_list['config_prd']=$associative_prd; 
            $prd_list['offers']=$this->available_offers($row->id,$lang); 
            $products[]=$prd_list;
            }
			
           if(!empty($products))
           {
               $total_products=$prod_data_count->total();
           }
           else
           {
               $total_products=0;
           }
                return response()->json(['httpcode'=>200,'status'=>'success','page'=>'Explore Products','data'=>['products'=>$products,'total_products'=>$total_products,'currency'=>getCurrency()->name]]);

            }
            else
            {
               return response()->json(['httpcode'=>200,'status'=>'success','page'=>'Explore Products','message'=>'Product not found']); 
            }  
    }
    
    public function trending_products(Request $request){
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
		
		
		$lang=$request->lang_id;
        $category    = $request->category_id;
        $subcategory = $request->subcategory_id;
        $brand       = $request->brand_id;
		$max_price   = $request->max_price;
        $min_price   = $request->min_price;
       
        $offset = $request->offset;
        $limit = $request->limit;
         if($request->offset==''){
			$offset =0; 
		 }
		 if($request->limit==''){
			$limit =10; 
		 }
        $products=[];
	//	\DB::enableQueryLog();
       $query=Product::join("sales_order_items as b","prd_products.id","=","b.parent_id")
			->select(DB::raw("sum(b.qty) as total"), "prd_products.*")                        
			->where('b.row_total','>',0)->where('prd_products.visible',1)->where('prd_products.is_approved',1)->where('prd_products.is_active',1)->where('prd_products.is_deleted',0)->orderBy('total','desc')->groupBy('b.parent_id');
			
		
        if(isset($request->keyword)){
		  $query->where('name', 'like', '%'.$request->keyword.'%');  
		}
		$prod_data_count = $query->paginate(); 
        $prod_data = $query->skip($offset)->take($limit)->get();
//dd($prod_data);		
       //dd(\DB::getQueryLog());
            if(!empty($prod_data))
            {
                foreach($prod_data as $row){
                   
                    //$prd_list['service_status']=$store_active->service_status;     
                    $prd_list['product_id']=$row->id;
                    $prd_list['product_name']=$this->get_content($row->name_cnt_id,$lang);
                    $prd_list['category_id']=$row->category_id;
                    $prd_list['category_name']=$this->get_content(@$row->category->cat_name_cid,$lang);
                    $prd_list['subcategory_id']=$row->sub_category_id;
                    $prd_list['subcategory_name']=$this->get_content(@$row->subCategory->sub_name_cid,$lang);
                    if($row->brand_id)
                    {
                    $prd_list['brand_id']=$row->brand_id;
                    $prd_list['brand_name']=$this->get_content(@$row->brand->brand_name_cid,$lang);
                    }
                    else
                    {
                    $prd_list['brand_id']='';
                    $prd_list['brand_name']='';  
                    }
                    if($row->product_type==1)
                    {
                    $prd_list['product_type']='simple';    
					$prd_list['min_order_qty']=$row->min_order;
					$prd_list['bulk_order_qty']=$row->bulk_order;	
						$price=$this->get_price($row->id,$type=1,$login);
						foreach ($price as $item) {
							foreach ($item as $key => $value) {
								$prd_list[$key] = $value;
							} 
						}
						
					    $prd_list['stock']=$row->prdStock($row->id);
                        if($prd_list['stock'] <=0)
                        {
                            $prd_list['is_out_of_stock']=true;
                        }
                        else
                        {
                            $prd_list['is_out_of_stock']=false;
                        }
                        if($row->out_of_stock_selling==0)
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
					 $minprice_prd_id=$this->min_price_product($row->id);
					 $price=$this->get_price($minprice_prd_id,$type=2,$login);
                     foreach ($price as $item) {
							foreach ($item as $key => $value) {
								$prd_list[$key] = $value;
							} 
						}
						
						$prd_list['stock']=NULL;
                        $prd_list['is_out_of_stock']=NULL;
                        $prd_list['out_of_stock_selling']=NULL;
                        	
						
					}  
					$prd_list['short_description']=$this->get_content($row->short_desc_cnt_id,$lang);
                    $prd_list['tag']=$this->get_product_tag($row->id,$lang);
                    $prd_list['rating']=$this->get_rates($row->id);
                    if($prd_list['category_id']){
						$category=Category::where('category_id',$row->category_id)->first();
						$prd_list['is_rating']=@$category->is_rating;
					}else{
						
						$prd_list['is_rating']=0;
					}
					$prd_list['total_reviews']=$this->get_rates_count($row->id);
                    //$prd_list['seller']=$row->Store($row->seller_id)->store_name;
                    //$prd_list['seller_id']=$row->seller_id;
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
                            
                                $associative_prd[]=$this->ass_related_product1($product_visibility->id,$special_ofr_available,$lang,$login);
                            
                            
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

           // $prd_list['config_prd']=$associative_prd; 
            $prd_list['offers']=$this->available_offers($row->id,$lang); 
            $products[]=$prd_list;
            }
			
           if(!empty($products))
           {
               $total_products=$prod_data_count->total();
           }
           else
           {
               $total_products=0;
           }
                return response()->json(['httpcode'=>200,'status'=>'success','page'=>'Trending Products','data'=>['products'=>$products,'total_products'=>$total_products,'currency'=>getCurrency()->name]]);

            }
            else
            {
               return response()->json(['httpcode'=>200,'status'=>'success','page'=>'Trending Products','message'=>'Product not found']); 
            }  
    }
	function get_product_review_date_range($prd_id){
        $data     =   [];
        $prod_data       =   PrdReview::where('is_deleted',0)->where('is_active',1)->where('prd_id',$prd_id)->get();

            if(count($prod_data)>0)   { 

                $a=$b=$c=$d=$e=$list1['1Star']=$list1['2Star']=$list1['3Star']=$list1['4Star']=$list1['5Star']=0;
                foreach($prod_data as $row)
    			{
    				if($row->rating==1)
    				{
    					$list1['1Star']=++$a;
    				}
    				else if($row->rating==2)
    				{
    					$list1['2Star']=++$b;
    				}
    				else if($row->rating==3)
    				{
    					$list1['3Star']=++$c;
    				}
    				else if($row->rating==4)
    				{
    					$list1['4Star']=++$d;
    				}
    				else
    				{
    					$list1['5Star']=++$e;
    				}
    			}
    			$data=$list1;
             }
            else{ $data     =   []; } return $data;
        
        
    }
        

public function stores_list(Request $request){

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

        $crm_branches=CrmBranch::where('DelStatus',0)->orderBy('Branch_Id','DESC')->get();
     
        if(count($crm_branches)>0)
        {
        foreach($crm_branches as $key)
        {  
           $crm_branche_arr['id'] = $key->Branch_Id;
           $crm_branche_arr['name'] = $key->Branch_Name;
           $crm_branche_arr['city'] = $key->City;
           $crm_branche_arr['image'] = url('storage/app/public/crm_stores/no-avatar.png');
           $crm_stores_array[] = $crm_branche_arr;
        }
        }
        else
        {
            $crm_stores_array=[];
        }


           return ['httpcode'=>200,'status'=>'success','page'=>'Home','message'=>'Success','data'=>[
       
           'stores'=>$crm_stores_array,
          
            ]];

    }

function getIntervals($min, $max, $nbIntervalls) {
    $max -= $min;  // --------------------------> subtract $min
    $size = round(($max-1) / $nbIntervalls);
    $result = [];

    for ($i = 0; $i < $nbIntervalls; $i++) {
         $inf = $i + $i * $size;
         $sup = $inf + $size < $max ? $inf + $size: $max;

        $result[]=[$inf + $min, $sup + $min];
        if($inf >= $max || $sup >= $max)break;
    }
    return $result;
}

public function pricesplit_list(Request $request){

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

        $min_price = CrmSalesPriceList::where('DelStatus',0)->whereIn('prd_id',function($query) {
        $query->select('id')->from('prd_products')->where('is_deleted',0)->where('is_active',1);})->where('CustomerTypeId',$user['crm_customer_type'])->where('PriceTypeId',1)->whereDate('FromDate', '<=', date("Y-m-d"))->min('Amount');

        $max_price = CrmSalesPriceList::where('DelStatus',0)->whereIn('prd_id',function($query) {
        $query->select('id')->from('prd_products')->where('is_deleted',0)->where('is_active',1);})->where('CustomerTypeId',$user['crm_customer_type'])->where('PriceTypeId',1)->whereDate('FromDate', '<=', date("Y-m-d"))->max('Amount');

        $price_range = $this->getIntervals($min_price, $max_price, 5);


           return ['httpcode'=>200,'status'=>'success','page'=>'Home','message'=>'Success','data'=>[
       
           'price_range'=>$price_range,
          
            ]];

    }
    
    public function all_occasions(Request $request){

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

        $occasions=Occasion::where('is_active',1)->where('is_deleted',0)->orderBy('id','DESC')->get();
     
        if(count($occasions)>0)
        {
        foreach($occasions as $key)
        {  
           $occ_arr['id'] = $key->id;
           $occ_arr['name'] = $this->get_content($key->occasion_name_cid,$lang_id);
           $occ_arr['image'] = url('public/uploads/storage/app/public/occasions/'.$key->image);
           $occassions_list[] = $occ_arr;
        }
        }
        else
        {
            $occassions_list=[];
        }


           return ['httpcode'=>200,'status'=>'success','page'=>'Home','message'=>'Success','data'=>[
       
           'stores'=>$occassions_list,
          
            ]];

    }
    
    public function coming_soon(Request $request){

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
        $comingsoon= Product::where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->where('is_comingsoon',1)->where('visible',1)->orderBy('id','DESC')->get();
          //print_r($prod_data);exit;
          if(count($comingsoon)>0){
                foreach($comingsoon as $row){
      
                    $coming_prd_list['product_id']=$row->id;
                    $coming_prd_list['product_name']=$this->get_content($row->name_cnt_id,$lang_id);
                    $coming_prd_list['category_id']=$row->category_id;
                    if($coming_prd_list['category_id']){
                        $category=Category::where('category_id',$row->category_id)->first();
                        $coming_prd_list['is_rating']=@$category->is_rating;
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

    public function deals_all(Request $request){

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
        $daily_deals= Product::where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->where('daily_deals',1)->where('visible',1)->orderBy('id','DESC')->get();
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
                        $deals_prd_list['is_rating']=@$category->is_rating;
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
	
}