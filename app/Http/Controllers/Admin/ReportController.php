<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;
use App\Models\Coupon;
use App\Models\CouponHist;
use App\Models\CustomerAddress;
use App\Models\CustomerAddressType;
use App\Models\Store;
use App\Models\SellerReview;
use App\Models\SaleOrder;
use App\Models\SaleorderItems;
use App\Models\SalesOrderAddress;
use App\Models\SalesOrderPayment;
use App\Models\Product;
use App\Models\PrdPrice;
use App\Models\PrdReview;
use App\Models\UsrWishlist;
use Carbon\Carbon;
use App\Rules\Name;
use Validator;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function coupon_report(Request $request)
    {
         $post                       =   (object)$request->post();
        if(isset($post->viewType))  {   $viewType = $post->viewType; }else{ $viewType = ''; }
        $data['title']              =   'Coupon report';
        $data['menu']               =   'Coupon report';
        $data['product']            =    Product::where('is_deleted',0)->where('is_active',1)->where('is_approved',1)->get();
        $visitor      =  CouponHist::join('sales_orders','coupon_usage_hist.coupon_id','=','sales_orders.coupon_id')->join('coupon','coupon_usage_hist.coupon_id','=','coupon.id')->join('sales_order_items','sales_orders.id','=','sales_order_items.sales_id')->select('*',DB::raw('count(coupon_usage_hist.user_id) as users'),DB::raw('sales_orders.seller_id as sale_seller'))->selectRaw("sum(sales_orders.total) as purchase")->selectRaw("sum(sales_orders.discount) as discount")->whereNotIn('order_status',['refunded','cancelled']);
        $data['start_date']         =   ''; $data['end_date'] =   ''; $data['p_status'] =   ''; $data['o_status'] =   '';
        $data['seller']             =   '';

        if($viewType=='ajax')
        {
         if(isset($post->start_date) &&  $post->start_date != ''){ 
            $visitor                 =   $visitor->whereDate('sales_orders.created_at','>=',$post->start_date); 
            $data['start_date']     =   $post->start_date;
        }
        if(isset($post->end_date)   &&  $post->end_date != ''){ 
            $visitor                 =   $visitor->whereDate('sales_orders.created_at','<=',$post->end_date); 
            $data['end_date']       =   $post->end_date;
        }
        if(isset($post->seller)     &&  $post->seller != ''){ 
            
            $visitor                 =   $visitor->where('sales_orders.seller_id',$post->seller); 
            $data['seller']          =   $post->seller;
        }
        if(isset($post->product)   &&  $post->product != ''){ 
            $visitor                 =   $visitor->where('sales_order_items.prd_id',$post->product); 
            $data['product']       =   $post->product;
        }
       }
    //    $data['visit']   = $visitor->select(DB::raw('COUNT(id) AS users WHERE user_id != "1"'), 
    //  DB::raw('COUNT(id) AS visitors WHERE user_id = "1"'))
    // ->groupBy('prd_id')->get();

       $data['data'] = $visitor->groupBy('coupon_usage_hist.coupon_id')->orderBy('sales_orders.created_at','DESC')->get();

        $data['sellers']            =   getDropdownData($this->getSellers(),'seller_id','store_name');
        if($viewType == 'ajax') {   
            
            return view('admin.coupon_report.list',$data); 
        }else{ 
            return view('admin.coupon_report.page',$data);
             }
       
    }
    
    public function wishlist_report(Request $request)
    {
         $post                       =   (object)$request->post();
        if(isset($post->viewType))  {   $viewType = $post->viewType; }else{ $viewType = ''; }
        $data['title']              =   'Wishlist report';
        $data['menu']               =   'Wishlist report';
        $visitor                    =   UsrWishlist::join('sales_order_items','usr_wishlists.prd_id','=','sales_order_items.prd_id')->where('usr_wishlists.is_deleted',0);
        $data['start_date']         =   ''; $data['end_date'] =   ''; $data['p_status'] =   ''; $data['o_status'] =   '';
        $data['seller']             =   '';
        if($viewType=='ajax')
        {
         if(isset($post->start_date) &&  $post->start_date != ''){ 
            $visitor                 =   $visitor->whereDate('usr_wishlists.created_at','>=',$post->start_date); 
            $data['start_date']     =   $post->start_date;
        }
        if(isset($post->end_date)   &&  $post->end_date != ''){ 
            $visitor                 =   $visitor->whereDate('usr_wishlists.created_at','<=',$post->end_date); 
            $data['end_date']       =   $post->end_date;
        }
        if(isset($post->seller)     &&  $post->seller != ''){ 
            
            $seller_products = Product::where('seller_id',$post->seller)->where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->pluck('id');
            $visitor                 =   $visitor->whereIn('usr_wishlists.prd_id',$seller_products); 
            $data['seller']          =   $post->seller;
        }
       }
       $data['data'] = $visitor->groupBy('usr_wishlists.prd_id')->orderBy('usr_wishlists.created_at','DESC')->get();

        $data['sellers']            =   getDropdownData($this->get_all_Sellers(),'seller_id','store_name');
        if($viewType == 'ajax') {   
            
            return view('admin.wishlist_report.list',$data); 
        }
        else{ 
            // print_r($data);
            // die;
            return view('admin.wishlist_report.page',$data);
             }
    }
    
    public function best_purchase_report(Request $request)
    {
         $post                       =   (object)$request->post();
        if(isset($post->viewType))  {   $viewType = $post->viewType; }else{ $viewType = ''; }
        $data['title']              =   'Best purchase report';
        $data['menu']               =   'Best purchase report';
        $visitor                    =  SaleorderItems::join('sales_orders','sales_order_items.sales_id','=','sales_orders.id')->where('sales_orders.order_status','=','delivered')->select('*',DB::raw('sum(sales_order_items.qty) as sold'));
        $data['start_date']         =   ''; $data['end_date'] =   ''; $data['p_status'] =   ''; $data['o_status'] =   '';
        $data['seller']             =   '';
        if($viewType=='ajax')
        {
         if(isset($post->start_date) &&  $post->start_date != ''){ 
            $visitor                 =   $visitor->whereDate('sales_orders.created_at','>=',$post->start_date); 
            $data['start_date']     =   $post->start_date;
        }
        if(isset($post->end_date)   &&  $post->end_date != ''){ 
            $visitor                 =   $visitor->whereDate('sales_orders.created_at','<=',$post->end_date); 
            $data['end_date']       =   $post->end_date;
        }
        if(isset($post->seller)     &&  $post->seller != ''){ 
            
            $seller_products = Product::where('seller_id',$post->seller)->where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->pluck('id');
            $visitor                 =   $visitor->whereIn('sales_order_items.prd_id',$seller_products); 
            $data['seller']          =   $post->seller;
        }
       }
       $data['sellers']            =   getDropdownData($this->getSellers(),'seller_id','store_name');
       $visitors = $visitor->groupBy('sales_order_items.prd_id')->get();
       
       
       $best=[];
       if(count($visitors)>0)
       {
            foreach($visitors as $row)
            {
                $sale_repeat = SaleorderItems::join('sales_orders','sales_order_items.sales_id','=','sales_orders.id')->where('sales_order_items.prd_id',$row->prd_id)->whereNotIn('order_status',['refunded','cancelled'])->groupBy('sales_orders.cust_id')->count();

                $rate =DB::table('prd_review')->select('*',DB::raw('AVG(rating) as rating'),DB::raw('count(user_id) as reviews'))->where('prd_id',$row->prd_id)->where('is_active',1)->where('is_deleted',0)->first();

              if($row->product){
                $prd['product_name'] = $row->product->get_content($row->product->name_cnt_id);
                $prd['sold']         = $row->sold;
                $prd['cust_repeat']  = $sale_repeat;
                $prd['tot_review']   = $rate->reviews;
                $prd['tot_rating']   = round($rate->rating);
                $best[]              = $prd;
              }
            }
       }
       // print_r($visitors);
       //      die;
       $data['data']    = $best;
        
        if($viewType == 'ajax') {   
            
            return view('admin.bestpurchase_report.list',$data); 
        }
        else{ 
            // print_r($data);
            // die;
            return view('admin.bestpurchase_report.page',$data);
             }
    }

    //Product review
    public function review_report(Request $request,$id='',$type='')
    {
         $post                       =   (object)$request->post();
        if(isset($post->viewType))  {   $viewType = $post->viewType; }else{ $viewType = ''; }
        $data['title']              =   'Product review report';
        $data['menu']               =   'Product review report';
        $visitor                    =   PrdReview::select('*',DB::raw('count(user_id) as reviews'),DB::raw('count(rating) as rating'));
        $data['start_date']         =   ''; $data['end_date'] =   ''; $data['p_status'] =   ''; $data['o_status'] =   ''; $data['product'] ='';
        $data['seller']             =   '';

        $data['product']            = Product::where('is_approved',1)->where('is_deleted',0)->get();

        if($type=='')
        {
        if($viewType=='ajax')
        {
         if(isset($post->start_date) &&  $post->start_date != ''){ 
            $visitor                 =   $visitor->whereDate('created_at','>=',$post->start_date); 
            $data['start_date']     =   $post->start_date;
        }
        if(isset($post->end_date)   &&  $post->end_date != ''){ 
            $visitor                 =   $visitor->whereDate('created_at','<=',$post->end_date); 
            $data['end_date']       =   $post->end_date;
        }
        if(isset($post->seller)     &&  $post->seller != ''){ 
            
            $seller_products = Product::where('seller_id',$post->seller)->where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->pluck('id');
            $visitor                 =   $visitor->whereIn('prd_id',$seller_products); 
            $data['seller']          =   $post->seller;
        }
        if(isset($post->product)   &&  $post->product != ''){ 
            $visitor                =   $visitor->where('prd_id',$post->product); 
            $data['product']       =   $post->product;
        }
       }
       $data['sellers']            =   getDropdownData($this->getSellers(),'seller_id','store_name');
       $visitors = $visitor->groupBy('prd_id')->get();
       
       
       $best=[];
       if(count($visitors)>0)
       {
            foreach($visitors as $row)
            {
                $latest = PrdReview::where('prd_id',$row->prd_id)->latest()->first();
               if($row->product){
                $prd['product_name'] = $row->product->get_content($row->product->name_cnt_id);
                $prd['reviews']      = $row->reviews;
                $prd['rating']       = $row->rating;
                $prd['latest']       = $latest->comment;
                $prd['view']         = $row->prd_id;
                $best[]              = $prd;
               } 
            }
       }
       // print_r($best);
       //      die;
       $data['data']    = $best;
        
        if($viewType == 'ajax') {   
            
            return view('admin.prdreview_report.list',$data); 
        }
        else{ 
            // print_r($data);
            // die;
            return view('admin.prdreview_report.page',$data);
             }
         }
         else
         {
            $pro_name =  Product::where('id',$id)->first();
            $data['product_name']=$pro_name->get_content($pro_name->name_cnt_id);
            $data['data'] =PrdReview::where('prd_id',$id)->get();
            return view('admin.prdreview_report.reviews',$data);
         }
    }


    function getSellers(){
        $sales                      =   SaleOrder::get(['seller_id']); $sellerIds = [];
        if($sales){ foreach($sales  as  $row){ $sellerIds[] = $row->seller_id; } }else{ $sellerIds = [0]; }
        return Store::whereIn('seller_id',$sellerIds)->get();
    }
    
    function getSale_products(){
        $sales                      =   SaleorderItems::get(['prd_id']); $sellerIds = [];
        if($sales){ foreach($sales  as  $row){ $sellerIds[] = $row->seller_id; } }else{ $sellerIds = [0]; }
        return Product::whereIn('id',$sellerIds)->get();
    }
    
    function get_all_Sellers(){
        // $prd                      =   UsrWishlist::where('is_deleted',0)->pluck('prd_id');
        // $seller                   =   Product::whereIn('id',$prd)->where('is_active',1)->where('is_deleted',0)->pluck('seller_id');
        // return Store::where('is_active',1)->where('is_deleted',0)->whereIn('seller_id',$seller)->get();
         return Store::where('is_active',1)->where('is_deleted',0)->get();
    }
}
