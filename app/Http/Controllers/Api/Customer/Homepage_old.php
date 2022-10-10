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
use App\Models\Productvisitor;
use App\Models\RelatedProduct;
use App\Models\AssignedAttribute;
use App\Models\MetalRates;
use App\Models\UserVisit;
use App\Models\Currency;
use App\Models\UsrWishlist;

use App\Models\AssignedFields;
use App\Models\SettingOther;

use Carbon\Carbon;
use App\Rules\Name;
use Validator;

class Homepage extends Controller
{
    public function index(Request $request)
    {  
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
            
            else{
            
            if($request->currency_code)
        {
            $crv = get_currency_rate($request->currency_code);
            
        }
        else
        {
            $crv=1;
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
        //   $image = "https://kt.estrradoweb.com/storage/app/public/banner/banner_4.jpg"; 
        //     $m_arrray=['id'=>1,
        //     'identifier'=>'Main banner',
        //     'title'=>'Main banner',
        //     'description'=>'Main banner',
        //     'media_type'=>'image',
        //     'media'=>$image];
        //     $main_array[]=$m_arrray;
        
        }
        else
        {
            $main_array[]='';
        }

        // //side top banner
        // $side_top_banner=Banner::where('is_active',1)->where('is_deleted',0)->where('banner_id',2)->get();
        // if(count($side_top_banner)>0)
        // {
        // foreach($side_top_banner as $key)
        // {   
        //     foreach(explode(',',$key->media) as $img)
        //     {
        //         if($key->media_type=="image")
        //         {
        //             $image = config('app.storage_url').'/app/public/side_top_banner/'.$img;
        //         //$image = url('storage/app/public/side_top_banner/'.$img); 
        //         }
        //         else
        //         {
        //             $image = $img;
        //         }
        //     // $image = url('storage/app/public/banner/thumb'.$key->media); 
        //     $top_array=['id'=>$key->id,
        //     'identifier'=>$key->identifier,
        //     'title'=>$key->get_content($key->title_cnt_id,$lang_id),
        //     'description'=>$key->get_content($key->desc_cnt_id,$lang_id),
        //     'media_type'=>$key->media_type,
        //     'media'=>$image];
        //     $top_banner[]=$top_array;
        //     }
        // }
        // }
        // else
        // {
        //     $top_banner[]='';
        // }
        
        $top_banner[]='';

        // //side bottom banner
        // $side_bottom_banner=Banner::where('is_active',1)->where('is_deleted',0)->where('banner_id',3)->get();
        // if(count($side_bottom_banner)>0)
        // {
        // foreach($side_bottom_banner as $key)
        // {   
        //     foreach(explode(',',$key->media) as $img)
        //     {
        //         if($key->media_type=="image")
        //         {
        //             $image = config('app.storage_url').'/app/public/side_bottom_banner/'.$img;
        //       // $image = url('storage/app/public/side_bottom_banner/'.$img); 
        //         }
        //         else
        //         {
        //             $image = $img;
        //         }
        //     //$image = url('storage/app/public/banner/thumb'.$key->media); 
        //     $bottom_array=['id'=>$key->id,
        //     'identifier'=>$key->identifier,
        //     'title'=>$key->get_content($key->title_cnt_id,$lang_id),
        //     'description'=>$key->get_content($key->desc_cnt_id,$lang_id),
        //     'media_type'=>$key->media_type,
        //     'media'=>$image];
        //     $bottom_banner[]=$bottom_array;
        //     }
        // }
        // }
        // else
        // {
        //     $bottom_banner[]='';
        // }
    
        $bottom_banner[]='';
        
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

        //Latest Products
        $products=[];
        $prod_data= Product::where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->limit(6)->orderBy('id','DESC')->get();
            if(count($prod_data)>0)
            {
                
                foreach($prod_data as $row)
                {
                    $store_active = Store::where('is_active',1)->where('seller_id',$row->seller_id)->first();
                    if($store_active)
                    {
                    $prd_list['service_status']=$store_active->service_status;    
                    $prd_list['product_id']=$row->id;
                    $prd_list['product_name']=$row->get_content($row->name_cnt_id,$lang_id);
                    $prd_list['category_id']=$row->category_id;
                    $prd_list['category_name']=$row->get_content($row->category->cat_name_cid,$lang_id);
                    $prd_list['subcategory_id']=$row->sub_category_id;
                    $prd_list['subcategory_name']=$row->get_content($row->subCategory->sub_name_cid,$lang_id);
                    if($row->brand_id)
                    {
                    $prd_list['brand_id']=$row->brand_id;
                    $prd_list['brand_name']=$row->get_content($row->brand->brand_name_cid,$lang_id);
                    }
                    else
                    {
                    $prd_list['brand_id']='';
                    $prd_list['brand_name']='';   
                    }
                    $prd_list['seller']=$row->Store($row->seller_id)->store_name;
                    $prd_list['seller_id']=$row->seller_id;
                    $prd_list['product_type']='simple'; 
                    if($row->subCategory->code=='XAU')
                    {
                        $gold=true;
                        $subcategory_code='XAU';
                        $variable = get_variable_price_fn($subcategory_code,$carat=null);
                        if($row->weight>0)
                        {
                            $variable_price = $variable*$row->weight;
                        }
                        else
                        {
                            $variable_price = $variable;
                        }
                        
                        
                            // show min carat price in listing if available
                            
                                $extras=[];
                                $extra_field=AssignedFields::where('prd_id',$row->id)->whereIn('field_id',function($query) {
                                 $query->select('id')->from('prd_fields')->where('variable_rate',1)->where('is_active',1)->where('is_deleted',0);})->where('is_deleted',0)->groupBy('field_id')->get();
                                
                                if(!empty($extra_field))
                                {
                                $i=1;
                                foreach($extra_field as $rows){
                              
                                $extras=extra_field_values($row->id,$rows->field_id,$row->fixed_price,getTax()->value);
                                $i++;
                                }
                                }
                                else
                                {
                                $extras =[];
                                }
                                
                                if(isset($extras) && count($extras)>0)
                                {
                                    $variable_price = min(array_column( $extras,'variable_price'));
                                    
                                }
                            
                    }
                    else
                    {
                        $gold= false;
                        $variable_price =0;
                    }
                    
                    $prd_list['is_gold']=$gold;   
                    $prd_list['fixed_price']=round($row->fixed_price * $crv);
                    $prd_list['variable_price']=round($variable_price * $crv);
                    $prd_list['weight']=$row->weight;
                    
                    $tot_price = ($variable_price+$row->fixed_price)*$crv;
                    $tax = getTax()->value;
                    
                    $mjs_fee=getCustomerFee()->mjs_fee;
                    $pg_fee=getCustomerFee()->pg_fee;
                    $mjsfee= ($mjs_fee/100)*$tot_price;
                    $pgfee = ($pg_fee/100)*$tot_price;
                    $prd_list['mjs_fee']= $mjsfee;
                    $prd_list['pg_fee'] =$pgfee;
                    $tot_price += $prd_list['mjs_fee'] + $prd_list['pg_fee'];
                    //$tot_price = round($tot_price);
                    $tax_price = ($tax/100)*$tot_price;
                    $prd_list['product_tax']=round($tax_price);
                    $prd_list['actual_price']=round($tot_price);
                    
                    $prd_list['rating']=$this->get_rates($row->id);
                    $prd_list['total_reviews']=$this->get_rates_count($row->id);
                    $prd_list['image']=$this->get_product_image($row->id); 

                    $products[]=$prd_list;
                    }
                }

            }
            else
            {
                $products[]='';
            }
            

            //MOST VIEWED
            $products_visit=[];
            $prd_visit_id=Productvisitor::select('prd_id')->distinct()->pluck('prd_id');
           // $prd_visits = Product::where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->whereIn('id',$prd_visit_id)->limit(4)->orderBy('id','DESC')->get();
           $prd_visits = Product::join('usr_product_visitor', 'usr_product_visitor.prd_id', '=', 'prd_products.id')
        ->where('prd_products.is_active',1)->where('prd_products.is_deleted',0)->where('prd_products.is_approved',1)->where('prd_products.visible',1)
        ->select('prd_products.*')->limit(4)
        ->addSelect(DB::raw('count(usr_product_visitor.user_id != "") as users'))
        ->groupBy('prd_products.id')
        ->orderBy('users', 'DESC')->get();
            if(count($prd_visits)>0)
            {
                
                foreach($prd_visits as $row)
                {
                    $store_active = Store::where('is_active',1)->where('seller_id',$row->seller_id)->first();
                    if($store_active)
                    {
                    $prd_v['service_status']=$store_active->service_status;    
                    $prd_v['product_id']=$row->id;
                    $prd_v['product_name']=$row->get_content($row->name_cnt_id,$lang_id);
                    $prd_v['category_id']=$row->category_id;
                    $prd_v['category_name']=$row->get_content($row->category->cat_name_cid,$lang_id);
                    $prd_v['subcategory_id']=$row->sub_category_id;
                    $prd_v['subcategory_name']=$row->get_content($row->subCategory->sub_name_cid,$lang_id);
                    if($row->brand_id)
                    {
                    $prd_v['brand_id']=$row->brand_id;
                    $prd_v['brand_name']=$row->get_content($row->brand->brand_name_cid,$lang_id);
                    }
                    else
                    {
                    $prd_v['brand_id']='';
                    $prd_v['brand_name']='';   
                    }
                    $prd_v['seller']=$row->Store($row->seller_id)->store_name;
                    $prd_v['seller_id']=$row->seller_id;
                    $prd_v['product_type']='simple'; 
                   if($row->subCategory->code=='XAU')
                    {
                        $gold=true;
                        $subcategory_code='XAU';
                        $variable = get_variable_price_fn($subcategory_code,$carat=null);
                        if($row->weight>0)
                        {
                            $variable_price = $variable*$row->weight;
                        }
                        else
                        {
                            $variable_price = $variable;
                        }
                        
                         // show min carat price in listing if available
                            
                                $extras=[];
                                $extra_field=AssignedFields::where('prd_id',$row->id)->whereIn('field_id',function($query) {
                                 $query->select('id')->from('prd_fields')->where('variable_rate',1)->where('is_active',1)->where('is_deleted',0);})->where('is_deleted',0)->groupBy('field_id')->get();
                                
                                if(!empty($extra_field))
                                {
                                $i=1;
                                foreach($extra_field as $rows){
                              
                                $extras=extra_field_values($row->id,$rows->field_id,$row->fixed_price,getTax()->value);
                                $i++;
                                }
                                }
                                else
                                {
                                $extras =[];
                                }
                                
                                if(isset($extras) && count($extras)>0)
                                {
                                    $variable_price = min(array_column( $extras,'variable_price'));
                                    
                                }
                                
                    }
                    else
                    {
                        $gold= false;
                        $variable_price =0;
                    }
                    $prd_v['is_gold']=$gold;   
                    $prd_v['fixed_price']=round($row->fixed_price * $crv);
                    $prd_v['variable_price']=round($variable_price * $crv);
                    $prd_v['weight']=$row->weight;
                    
                    $tot_price = ($variable_price+$row->fixed_price)*$crv;
                    $tax = getTax()->value;
                    
                      $mjs_fee=getCustomerFee()->mjs_fee;
                    $pg_fee=getCustomerFee()->pg_fee;
                    $mjsfee= ($mjs_fee/100)*$tot_price;
                    $pgfee = ($pg_fee/100)*$tot_price;
                    $prd_v['mjs_fee']= $mjsfee;
                    $prd_v['pg_fee'] =$pgfee;
                    $tot_price += $prd_v['mjs_fee'] + $prd_v['pg_fee'];
                    //$tot_price = round($tot_price);
                    $tax_price = ($tax/100)*$tot_price;
                    $prd_v['product_tax']=round($tax_price);
                    $prd_v['actual_price']=round($tot_price);
                    
                    $prd_v['rating']=$this->get_rates($row->id);
                    $prd_v['total_reviews']=$this->get_rates_count($row->id);
                    $prd_v['image']=$this->get_product_image($row->id); 

                    $products_visit[]=$prd_v;
                    }
                }

            }
            else
            {
                $products_visit=[];
            }
            

            //Category and subcategory List
            
            foreach($category_data as $cat)
            {
                $cat_list['category_id']=$cat->category_id;
                $cat_list['category_name']=$cat->get_content($cat->cat_name_cid,$lang_id);
                $cat_list['subcategory']=$this->get_subcategory($cat->category_id,$lang_id);  
                
               
                $cat_subcat[]=$cat_list;
            }


            


            //Seller info and Rating
            // $store=Store::where('is_active',1)->where('is_deleted',0)->get();
            // if (count($store)>0) {
               
            //   foreach($store as $str)
            //   {
            //     $seller_info = SellerInfo::where('seller_id',$str->seller_id)->where('is_approved',1)->first();
            //     if($seller_info){
            //     $total_seller_prds  = Product::where('seller_id',$str->seller_id)->where('is_active',1)->where('visible',1)->where('is_deleted',0)->where('is_approved',1)->count(); 
            //     $store_list['store_id']=$str->id;
            //     $store_list['seller_id']=$str->seller_id;
            //     $store_list['store_name']=$str->store_name;
            //     $store_list['service_status']=$str->service_status;
            //     $store_list['store_rating']=$this->get_seller_rating($str->seller_id);
            //     $store_list['store_prd_rating']=$this->get_seller_product_rating($str->seller_id);
            //     $store_list['total_products']=$total_seller_prds;
            //     $store_list['logo']=config('app.storage_url').$str->logo;//url($str->logo);
            //     $store_list['banner']=config('app.storage_url').$str->banner;
            //     $store_data[]=$store_list;
            //     }
            //   }

            //   $store_rt = array_column($store_data, 'store_rating');
            //   $prd_rate = array_column($store_data, 'store_prd_rating');
            //   array_multisort($store_rt, SORT_DESC, $prd_rate,SORT_DESC, $store_data);
            // }
            // else
            // {
            //     $store_data=[];
            // }
            
            $store_data=[];

            //LANGUAGE
            $lang=DB::table('glo_lang_lk')->where('is_active', 1)->get();
            foreach($lang as $key)
            {
                $lan['id']=$key->id;
                $lan['name']=$key->glo_lang_name;
                $lan['code']=$key->glo_lang_code;
                $language[]=$lan;
            }

            $invite_save= SettingOther::where('is_active',1)->where('is_deleted',0)->first();
           
            
            

        return ['httpcode'=>200,'status'=>'success','message'=>'Success','data'=>['user_data'=>$user,'invite_save'=>$invite_save->invite_save,'main_banner'=>$main_array,
            'side_top_banner'=>$top_banner,
            'side_bottom_banner'=>$bottom_banner,
            'category'=>$categories,'all_category_icon'=>$all_category,'latest_products'=>$products,'most_viewed'=>$products_visit,'sellers'=>$store_data,'languages'=>$language]];
    
            }
            }

    //Category and subcategory List separate
       public function category_subcategory(Request $request) 
       {   
           $cat_subcat=[];
           $lang_id=$request->lang_id;
           //category
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
                $cat_list['category_image']=$image;
                $cat_list['no_of_prds']=$cat->get_count('prd_products','category_id',$cat->category_id);
                $cat_list['subcategory']=$this->get_subcategory($cat->category_id,$lang_id);  
                
               
                $cat_subcat[]=$cat_list;
                //     }
                // }
            }
            
            // $order = array_column($cat_subcat, 'category_name');
            // array_multisort($order, SORT_ASC, $cat_subcat);

            return ['httpcode'=>200,'status'=>'success','message'=>'Success','data'=>['cat_subcat'=>$cat_subcat]];
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
        $prod_exist = Product::where('brand_id',$row->id)->where('is_active',1)->where('is_deleted',0)->where('visible',1)->where('is_approved',1)->first();
         if($prod_exist)
            {
                $store_active = Store::where('is_active',1)->where('seller_id',$prod_exist->seller_id)->first();
                    if($store_active)
                    {
            $b_list['brand_id']=$row->id;
            $b_list['brand_name']=$this->get_content($row->brand_name_cid,$lang_id);
            $b_list['brand_description']=$this->get_content($row->brand_desc_cid,$lang_id); 

            $brandlist[]=$b_list;
                    }
            }
         }
         asort($brandlist);
         return ['httpcode'=>200,'status'=>'success','message'=>'Success','data'=>['brands'=>$brandlist]];
    }

   }  

    public function most_searched(Request $request)
    {
        //Most search
           //$search_dir = PrdSearch_Directory::select('type_id','type_name','keyword')->groupBy('keyword')->orderByRaw('COUNT(*) DESC')->limit(100)->get();
           $search_dir = PrdSearch_Directory::select('type_id','type_name','keyword', DB::raw('count(*) as total'))->groupBy('keyword')->orderBy('total','DESC')->get();
            $search_brand=[];
            $most_search=[];

           // return [$search_dir];die;
            if(!empty($search_dir))
            {
                $i=0;
            foreach($search_dir as $dir)
            {  
                
                if(strlen($dir->keyword)>3){
                
                //$dir_list['length']  =  strlen($dir->keyword);
                $dir_list['keyword']= ucwords($dir->keyword);
                $dir_list['keyword_id']=$dir->type_id;
                $dir_list['no_of_search']=$dir->total;
                
                    $dir_list['keyword_type']=$dir->type_name;
                
                    $dir_list['url']=url('/api/customer/product-detail');
                
                // else if($dir->type_name=='category')
                // {
                //     $dir_list['url']=url('/api/customer/product-category');
                // }
                // else if($dir->type_name=='subcategory')
                // {
                //     $dir_list['url']=url('/api/customer/product-subcategory');
                // }
                // else
                // {
                //     $dir_list1['url']=url('/api/customer/product-brand');
                // }
                $most_search[]    = $dir_list;
                
                }
                
            }
            $most_search= array_slice($most_search, 0, 6);
            
          }
          else
          {
            $search_brand=[];
          }
          
         
          return ['httpcode'=>200,'status'=>'success','message'=>'Success','data'=>['most_search'=>$most_search]];
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
        if($request->keyword ==""){
            return ['httpcode'=>400,'status'=>'error','message'=>'Keyword is required','data'=>[]];
        }
        //CURRENCY
                if($request->currency_code)
                {
                    $crv = get_currency_rate($request->currency_code);
                }
                else
                {
                    $crv=1;
                }
                //**CURRENCY
        $products=[];
        $data_product=[];
        // $data_category=[];
        // $data_subcategory=[];
        // $data_brand=[];
        // $data_tag=[];

        $filter_id =$request->category_id;
        
        $products_p = [];
        // $products_c=[];
        // $products_s=[];
        // $products_b=[];
        // $products_t=[];
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

        //  $data_category = DB::table('cms_content')
        // ->leftjoin('category',function($q){
        //     $q->on('category.cat_name_cid' ,'cms_content.cnt_id');
        // })->select(['category.category_id'])
        // ->where('cms_content.content', 'Like', '%' . $request->keyword . '%')
        // ->get()->unique();   

         $data_product = DB::table('prd_products')->select(['id'])
        ->where('name', 'Like', '%' . $request->keyword . '%')
        ->get()->unique();



        // $data_subcategory = DB::table('cms_content')
        // ->leftjoin('subcategory',function($q){
        //     $q->on('subcategory.sub_name_cid' ,'cms_content.cnt_id');
        // })->select(['subcategory.subcategory_id'])
        // ->where('cms_content.content', 'Like', '%' . $request->keyword . '%')
        // ->get()->unique();

        // $data_brand = DB::table('cms_content')
        // ->leftjoin('prd_brand',function($q){
        //     $q->on('prd_brand.brand_name_cid' ,'cms_content.cnt_id');
        // })->select(['prd_brand.id'])
        // ->where('cms_content.content', 'Like', '%' . $request->keyword . '%')
        // ->get()->unique();

        
     }
      
        if(count($data_product)>0)
        {
            foreach($data_product as $key)
            {
                $arrays[]=$this->get_search_products($key->id,$category='',$subcategory='',$brand='',$tag='',$request->lang_id,$request->keyword,$user_id,$crv);
                if($arrays!='' && !empty($arrays))
                {   $products_p=array_filter($arrays, function($v){ 
 return !is_null($v) && $v !== ''; 
});
                    $products_p=array_values($products_p);
                }
            }
        }
//       if(count($data_category)>0 && !empty($data_category))
//       {
//             foreach($data_category as $row)
//             {
//                 if($row->category_id!='')
//                 {
//                 $arrays[]=$this->get_search_products($prd_id='',$row->category_id,$subcategory='',$brand='',$tag='',$request->lang_id,$request->keyword,$user_id,$crv);
//                 if($arrays!='' && !empty($arrays))
//                 {   
//                     $products_c=array_filter($arrays, function($v){ 
//  return !is_null($v) && $v !== ''; 
// });
//                   $products_c=array_values($products_c);
//                     foreach($products_c as $fr)
//                     {
//                         $products_c=$fr;
//                     }
//                 }
//               }
//             }
//         }
        

//         if(count($data_subcategory)>0 && !empty($data_category))
//       {
//             foreach($data_subcategory as $row)
//             {
//                 if($row->subcategory_id)
//                 {
//                 $arrays[]=$this->get_search_products($prd_id='',$category='',$row->subcategory_id,$brand='',$tag='',$request->lang_id,$request->keyword,$user_id,$crv);
//                 if($arrays!=null && !empty($arrays))
//                 {
//                     $products_s=array_filter($arrays, function($v){ 
//  return !is_null($v) && $v !== ''; 
// });
//                     $products_s=array_values($products_s);
//                     foreach($products_s as $fr)
//                     {
//                         $products_s=$fr;
//                     }
//                 }
//               }
//             }
//         }
//       //return $products_c;die;

//         if(count($data_brand)>0)
//       {
//             foreach($data_brand as $row)
//             {
//                 if($row->id)
//                 {
//                 $arrays[]=$this->get_search_products($prd_id='',$category='',$subcategory='',$brand=$row->id,$tag='',$request->lang_id,$request->keyword,$user_id,$crv);
//                 if($arrays!='' && !empty($arrays))
//                 {
//                     $products_b=array_filter($arrays, function($v){ 
//  return !is_null($v) && $v !== ''; 
// });
//                     $products_b=array_values($products_b);
//                     foreach($products_b as $fr)
//                     {
//                         $products_b=$fr;
//                     }
//                 }
//               }

//             }
//         }

        
         
         if(isset($products_p) && count($products_p)>0)
         {
             $product_collect=$products_p;
         }
         else if(isset($products_c) && count($products_c)>0  )
         {
             $product_collect=$products_c;
         }
         else if(isset($products_s) && count($products_s)>0)
         {
             $product_collect=$products_s;
         }
         else 
         {
             if(isset($products_b)){
                $product_collect=$products_b; 
             }
             
         }
       
       $products_page = $this->paginate($product_collect);
       $products_page_data = $products_page->items();
       $products_page_data=array_unique($products_page_data, SORT_REGULAR);
       $products_page_data=array_filter($products_page_data);
       $no_of_prds= count($products_page_data);
        
        return ['httpcode'=>200,'status'=>'success','message'=>'search','data'=>['keyword'=>$request->keyword,'no_of_products'=>$no_of_prds,'products'=>$products_page_data]];

   }
   
   
   
   //CURRENCY
   
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


              //Featured Products
   
   public function featured_prod(Request $request)
    { 
        
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
            
            else{
            
            if($request->currency_code)
        {
            $crv = get_currency_rate($request->currency_code);
            
        }
        else
        {
            $crv=1;
        }
            
        if($request->post('access_token')){
            if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
            $login=1;
            $user_id = $user['user_id'];
            
        }
        $products=[];
        if($request->post('limit')  ) {
          $limits = $request->post('limit'); $offset = $request->post('offset');
        $prod_data= Product::where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->skip($offset)->take($limits)->orderBy('id','DESC')->get();   
        }else {
             
        $prod_data= Product::where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->orderBy('id','DESC')->get();
        }
       
            if(count($prod_data)>0)
            {
                
                foreach($prod_data as $row)
                {
                    $store_active = Store::where('is_active',1)->where('seller_id',$row->seller_id)->first();
                    if($store_active)
                    {
                    $prd_list['service_status']=$store_active->service_status;    
                    $prd_list['product_id']=$row->id;
                    $prd_list['product_name']=$row->get_content($row->name_cnt_id,$lang_id);
                    $prd_list['category_id']=$row->category_id;
                    $prd_list['category_name']=$row->get_content($row->category->cat_name_cid,$lang_id);
                    $prd_list['subcategory_id']=$row->sub_category_id;
                    $prd_list['subcategory_name']=$row->get_content($row->subCategory->sub_name_cid,$lang_id);
                    if($row->brand_id)
                    {
                    $prd_list['brand_id']=$row->brand_id;
                    $prd_list['brand_name']=$row->get_content($row->brand->brand_name_cid,$lang_id);
                    }
                    else
                    {
                    $prd_list['brand_id']='';
                    $prd_list['brand_name']='';   
                    }
                    $prd_list['seller']=$row->Store($row->seller_id)->store_name;
                    $prd_list['seller_id']=$row->seller_id;
                    $prd_list['product_type']='simple'; 
                    if($row->subCategory->code=='XAU')
                    {
                        $gold=true;
                        $subcategory_code='XAU';
                        $variable = get_variable_price_fn($subcategory_code,$carat=null);
                        if($row->weight>0)
                        {
                            $variable_price = $variable*$row->weight;
                        }
                        else
                        {
                            $variable_price = $variable;
                        }
                        
                        
                            // show min carat price in listing if available
                            
                                $extras=[];
                                $extra_field=AssignedFields::where('prd_id',$row->id)->whereIn('field_id',function($query) {
                                 $query->select('id')->from('prd_fields')->where('variable_rate',1)->where('is_active',1)->where('is_deleted',0);})->where('is_deleted',0)->groupBy('field_id')->get();
                                
                                if(!empty($extra_field))
                                {
                                $i=1;
                                foreach($extra_field as $rows){
                              
                                $extras=extra_field_values($row->id,$rows->field_id,$row->fixed_price,getTax()->value);
                                $i++;
                                }
                                }
                                else
                                {
                                $extras =[];
                                }
                                
                                if(isset($extras) && count($extras)>0)
                                {
                                    $variable_price = min(array_column( $extras,'variable_price'));
                                    
                                }
                            
                    }
                    else
                    {
                        $gold= false;
                        $variable_price =0;
                    }
                    
                    $prd_list['is_gold']=$gold;   
                    $prd_list['fixed_price']=round($row->fixed_price * $crv);
                    $prd_list['variable_price']=round($variable_price * $crv);
                    $prd_list['weight']=$row->weight;
                    
                    $tot_price = ($variable_price+$row->fixed_price)*$crv;
                    $tax = getTax()->value;
                    
                    $mjs_fee=getCustomerFee()->mjs_fee;
                    $pg_fee=getCustomerFee()->pg_fee;
                    $mjsfee= ($mjs_fee/100)*$tot_price;
                    $pgfee = ($pg_fee/100)*$tot_price;
                    $prd_list['mjs_fee']= $mjsfee;
                    $prd_list['pg_fee'] =$pgfee;
                    $tot_price += $prd_list['mjs_fee'] + $prd_list['pg_fee'];
                    //$tot_price = round($tot_price);
                    $tax_price = ($tax/100)*$tot_price;
                    $prd_list['product_tax']=round($tax_price);
                    $prd_list['actual_price']=round($tot_price);
                    
                    $prd_list['rating']=$this->get_rates($row->id);
                    $prd_list['total_reviews']=$this->get_rates_count($row->id);
                    $prd_list['image']=$this->get_product_image($row->id); 

                    $products[]=$prd_list;
                    }
                }

            }
            else
            {
                $products[]='';
            }
            }
        return ['httpcode'=>200,'status'=>'success','message'=>'Product list','data'=>['products'=>$products]];
    }

//Get products
   function get_search_products($prd_id,$category,$subcategory,$brand,$tag,$lang,$keyword,$user_id,$crv)
   {
    $prod_data=[];
    $wishlist =0;
    if($prd_id!='')
    {
    $prod_data= Product::where('is_active',1)->where('is_deleted',0)->where('id',$prd_id)->where('is_approved',1)->get();
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
     $prod_data= Product::where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->where('category_id',$category)->get(); 
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
     $prod_data= Product::where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->where('sub_category_id',$subcategory)->get(); 
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
     $prod_data= Product::where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->where('brand_id',$brand)->get(); 
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
            if(count($prod_data)>0)
            {
                foreach($prod_data as $row)
                { 
            if($user_id>0) {       
            $checkprd =  UsrWishlist::where('user_id',$user_id)->where('prd_id',$row->id)->where('is_deleted',0)->first();
            if($checkprd)
            {
                $wishlist = 1;
            }
            else
            {
                $wishlist = 0;
            }
            }
            else
            {
                $wishlist = 0;
            }
                    
                    $prd_list['id']=$row->id;
                    $prd_list['product_name']=$row->get_content($row->name_cnt_id,$lang);
                    $prd_list['in_wishlist']=$wishlist;
                    $prd_list['category_id']=$row->category_id;
                    $prd_list['category_name']=$row->get_content($row->category->cat_name_cid,$lang);
                    $prd_list['subcategory_id']=$row->sub_category_id;
                    $prd_list['subcategory_name']=$row->get_content($row->subCategory->sub_name_cid,$lang);
                    if($row->brand_id)
                    {
                    $prd_list['brand_id']=$row->brand_id;
                    $prd_list['brand_name']=$row->get_content($row->brand->brand_name_cid,$lang);
                    }
                    $prd_list['seller_id']=$row->seller_id;
                    $prd_list['seller']=$row->Store($row->seller_id)->store_name;
                    $prd_list['product_type']='simple'; 
                    if($row->subCategory->code=='XAU')
                    {
                        $gold=true;
                        $subcategory_code='XAU';
                        $variable = get_variable_price_fn($subcategory_code,$carat=null);
                        if($row->weight>0)
                        {
                            $variable_price = $variable*$row->weight;
                        }
                        else
                        {
                            $variable_price = $variable;
                        }
                        
                        // show min carat price in listing if available

                        $min_carat=[];
                        $extra_fields_e=AssignedFields::where('prd_id',$row->id)->whereIn('field_id',function($query) {
                        $query->select('id')->from('prd_fields')->where('variable_rate',1)->where('is_active',1)->where('is_deleted',0);})->where('is_deleted',0)->groupBy('field_id')->get();
                        
                        if(!empty($extra_fields_e))
                        {
                        $i=1;
                        foreach($extra_fields_e as $rows){
                        
                        $min_carat=extra_field_values($row->id,$rows->field_id,$row->fixed_price,getTax()->value);
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
                    $prd_list['is_gold']=$gold;   
                    $prd_list['fixed_price']=round($row->fixed_price*$crv);
                    $prd_list['variable_price']=round($variable_price*$crv);
                    $prd_list['weight']=$row->weight;
                    
                    $tot_price = ($variable_price*$crv)+($row->fixed_price*$crv);
                    $tax = getTax()->value;
                    $mjs_fee=getCustomerFee()->mjs_fee;
                    $pg_fee=getCustomerFee()->pg_fee;
                    $prd_list['mjs_fee']= ($mjs_fee/100)*$tot_price;
                    $prd_list['pg_fee']=($pg_fee/100)*$tot_price;
                    $tot_price += $prd_list['mjs_fee'] + $prd_list['pg_fee'];
                    $tot_price = round($tot_price);
                    $tax_price = ($tax/100)*$tot_price;
                    $prd_list['product_tax']=round($tax_price);
                    $prd_list['actual_price']=$tot_price;
                    
                    $prd_list['short_description']=$row->get_content($row->short_desc_cnt_id,$lang);
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
                $val['subcategory_name']   =  $row->get_content($row->sub_name_cid,$lang_id);
                $val['subcategory_image']  =  $image;
                $data[]       =   $val;
            } }
            else{ $data     =   []; } return $data;
        
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
   
}
