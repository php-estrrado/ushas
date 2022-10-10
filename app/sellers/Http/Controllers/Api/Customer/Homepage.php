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
use App\Models\UserRole;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductImage;
use App\Rules\Name;
use Validator;

class Homepage extends Controller
{
    public function index()
    {   //main banner
        $main_banner=Banner::where('is_active',1)->where('is_deleted',0)->where('banner_id',1)->get();
        if(count($main_banner)>0)
        {
        foreach($main_banner as $key)
        {   $image = url('storage/app/public/banner/'.$key->media); 
            $m_arrray=['id'=>$key->id,
            'identifier'=>$key->identifier,
            'title'=>$key->get_content($key->title_cnt_id),
            'description'=>$key->get_content($key->desc_cnt_id),
            'media_type'=>$key->media_type,
            'media'=>$image];
            $main_array[]=$m_arrray;
        }
        }
        else
        {
            $main_array[]='';
        }

        //side top banner
        $side_top_banner=Banner::where('is_active',1)->where('is_deleted',0)->where('banner_id',2)->get();
        if(count($side_top_banner)>0)
        {
        foreach($side_top_banner as $key)
        {   $image = url('storage/app/public/banner/'.$key->media); 
            $top_array=['id'=>$key->id,
            'identifier'=>$key->identifier,
            'title'=>$key->get_content($key->title_cnt_id),
            'description'=>$key->get_content($key->desc_cnt_id),
            'media_type'=>$key->media_type,
            'media'=>$image];
            $top_banner[]=$top_array;
        }
        }
        else
        {
            $top_banner[]='';
        }

        //side bottom banner
        $side_bottom_banner=Banner::where('is_active',1)->where('is_deleted',0)->where('banner_id',3)->get();
        if(count($side_bottom_banner)>0)
        {
        foreach($side_bottom_banner as $key)
        {   $image = url('storage/app/public/banner/'.$key->media); 
            $bottom_array=['id'=>$key->id,
            'identifier'=>$key->identifier,
            'title'=>$key->get_content($key->title_cnt_id),
            'description'=>$key->get_content($key->desc_cnt_id),
            'media_type'=>$key->media_type,
            'media'=>$image];
            $bottom_banner[]=$bottom_array;
        }
        }
        else
        {
            $bottom_banner[]='';
        }

        //category
        $category_data= Category::where('is_active',1)->where('is_deleted',0)->get();
       foreach($category_data as $key)
        {   $image = url('storage/app/public/category/'.$key->image); 
            $category_array=['id'=>$key->category_id,
            'category_name'=>$key->get_content($key->cat_name_cid),
            'description'=>$key->get_content($key->cat_desc_cid),
            'no_of_prds'=>$key->get_count('prd_products','category_id',$key->category_id),
            'image'=>$image];
            $categories[]=$category_array;
        }

        //Featured Products
        $prod_data= Product::where('is_active',1)->where('is_deleted',0)->where('is_featured',1)->get();
            if(count($prod_data)>0)
            {
                foreach($prod_data as $row)
                {
                    $prd_list['id']=$row->id;
                    $prd_list['category_id']=$row->category_id;
                    $prd_list['category_name']=$row->get_content($row->category->cat_name_cid);
                    $prd_list['subcategory_id']=$row->sub_category_id;
                    $prd_list['subcategory_name']=$row->get_content($row->subCategory->sub_name_cid);
                    $prd_list['brand_id']=$row->brand_id;
                    $prd_list['brand_name']=$row->get_content($row->brand->brand_name_cid);
                    $prd_list['short_description']=$row->get_content($row->short_desc_cnt_id);
                    $prd_list['image']=$this->get_product_image($row->id); 

                    $products[]=$prd_list;
                }

            }
            else
            {
                $products[]='';
            }


            //Category and subcategory List
            $last_id=0;
            $i=1;
            foreach($category_data as $cat)
            {
                $cat_list['category_id']=$cat->category_id;
                $cat_list['category_name']=$cat->get_content($cat->cat_name_cid);
                $cat_list['subcategory']=$this->get_subcategory($cat->category_id);  
                
               
                $cat_subcat[]=$cat_list;
            }

        // return response()->json(['httpcode'=>200,'status'=>'success','main_banner'=>$main_array,
        //     'side_top_banner'=>$top_banner,
        //     'side_bottom_banner'=>$bottom_banner,
        //     'category'=>$categories]);
        return response()->json(['httpcode'=>200,'status'=>'success','data'=>['main_banner'=>$main_array,
            'side_top_banner'=>$top_banner,
            'side_bottom_banner'=>$bottom_banner,
            'category'=>$categories,'featured_products'=>$products,'cat_subcat'=>$cat_subcat]]);
    }

    //Product By category

    public function product_by_category(Request $request)
    {
        if($request->id)
        {
            $prod_data= Product::where('is_active',1)->where('is_deleted',0)->where('category_id',$request->id)->get();
            if(!empty($prod_data))
            {
                foreach($prod_data as $row)
                {
                    $prd_list['id']=$row->id;
                    $prd_list['category_id']=$row->category_id;
                    $prd_list['category_name']=$row->get_content($row->category->cat_name_cid);
                    $prd_list['subcategory_id']=$row->sub_category_id;
                    $prd_list['subcategory_name']=$row->get_content($row->subCategory->sub_name_cid);
                    $prd_list['brand_id']=$row->brand_id;
                    $prd_list['brand_name']=$row->get_content($row->brand->brand_name_cid);
                    $prd_list['short_description']=$row->get_content($row->short_desc_cnt_id);
                    $prd_list['image']=$this->get_product_image($row->id); 
            

                    $products[]=$prd_list;
                }

                return response()->json(['httpcode'=>200,'status'=>'success','data'=>$products]);

            }
            else
            {
               return response()->json(['httpcode'=>200,'status'=>'success','message'=>'Product not found']); 
            }

        }
        else
        {
            return response()->json(['status'=>'error','message'=>'Enter valid category']);
        }
    }

    function get_subcategory($cat_id){
        $data     =   [];
        
        $subcat       =   Subcategory::where('category_id',$cat_id)->where('is_active',1)->get(['subcategory_id','sub_name_cid']); 
            if($subcat)   {   foreach($subcat as $k=>$row){ 
                $val['id']    =   $row->subcategory_id;
                $val['subcategory_name']   =  $row->get_content($row->sub_name_cid);
                $data[]       =   $val;
            } }
            else{ $data     =   []; } return $data;
        
    }

    function get_product_image($prd_id){
        $data     =   [];
        
        $product       =   ProductImage::where('prod_id',$prd_id)->get(); 
            if($product)   {   foreach($product as $k=>$row){ 
                $val['image']       =   url('storage/app/public/product/'.$row->image);
                $val['thumbnail']   =   url('storage/app/public/product_thumbnail/'.$row->thumbnail);
                $data[]             =   $val;
            } }
            else{ $data     =   []; } return $data;
        
    }

   
}
