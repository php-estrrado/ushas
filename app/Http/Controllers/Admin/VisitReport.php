<?php

namespace App\Http\Controllers\Admin;
use DB;
use Session;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Productvisitor;
use App\Models\Product;
use App\Models\SellerInfo;
use App\Models\Store;
use App\Models\Admin;
use App\Rules\Name;
use Validator;

class VisitReport extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function product_visit(Request $request)
    {
        
    //product view counts
        $post                       =   (object)$request->post();
        if(isset($post->viewType))  {   $viewType = $post->viewType; }else{ $viewType = ''; }
        $data['title']              =   'Most viewed products';
        $data['menu']               =   'Most viewed products';
        $data['product']            =    Product::where('is_deleted',0)->where('is_active',1)->where('is_approved',1)->get();
        $visitor                     =    Productvisitor::where('org_id',1);
        $data['start_date']         =   ''; $data['end_date'] =   ''; $data['p_status'] =   ''; $data['o_status'] =   '';
        $data['seller']             =   '';

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
        if(isset($post->product)   &&  $post->product != ''){ 
            $visitor                 =   $visitor->where('prd_id',$post->product); 
            $data['product']       =   $post->product;
        }
        if(isset($post->seller)     &&  $post->seller != ''){ 
            $seller_products = Product::where('seller_id',$post->seller)->where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->pluck('id');
            
            $visitor                 =   $visitor->whereIn('prd_id',$seller_products); 
            $data['seller']         =   $post->seller;
        }
       }
    //    $data['visit']   = $visitor->select(DB::raw('COUNT(id) AS users WHERE user_id != "1"'), 
    //  DB::raw('COUNT(id) AS visitors WHERE user_id = "1"'))
    // ->groupBy('prd_id')->get();

       $data['visit'] = $visitor->select('*',DB::raw('count(*) as total'))->selectRaw("count(user_id != '') as users")->orderBy('created_at','DESC')->groupBy('prd_id')->get();

        $data['sellers']            =   getDropdownData($this->getSellers(),'seller_id','store_name');
        $data['product']            =   Product::where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->get();
        if($viewType == 'ajax') {   
            
            return view('admin.product_visit_report.list',$data); 
        }else{ 
            return view('admin.product_visit_report.page',$data);
             }
       
    }

    function getSellers(){
        $sales                      =   Product::get(['seller_id']); $sellerIds = [];
        if($sales){ foreach($sales  as  $row){ $sellerIds[] = $row->seller_id; } }else{ $sellerIds = [0]; }
        return Store::whereIn('seller_id',$sellerIds)->get();
    }
    
   
}
