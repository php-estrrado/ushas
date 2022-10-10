<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\SalesOrder;
use App\Models\Tax;
use App\Models\Language;
use App\Models\Customer;
use App\Models\CustomerInfo;
use App\Models\SellerInfo;
use App\Models\SalesOrderAddress;
use App\Models\SalesOrderPayment;
use App\Models\SalesOrderCancel;
use App\Models\SalesOrderCancelNote;
use App\Models\SalesOrderStatusList;
use App\Models\SalesOrderStatusHistory;
use App\Models\SalesOrderRefundPayment;
use App\Models\Auction;
use App\Models\AuctionHist;
use App\Models\SalesOrderReturn;
use App\Models\SalesOrderReturnStatus;
use App\Models\CustomerWallet_Model;
use App\Models\ParentSale;
use App\Models\Email;

use Validator;

class AdminSales extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function orders(Request $request,$type=''){ // echo Auth::user()->id; die;
        $post                       =   (object)$request->post();
        if(isset($post->viewType))  {   $viewType = $post->viewType; }else{ $viewType = ''; }
        $data['title']              =   'Admin Sales';
        $data['menuGroup']          =   'salesGroup';
        $data['menu']               =   'admin_sales';
        $data['start_date']         =   ''; $data['end_date'] =   ''; $data['p_status'] =   ''; $data['o_status'] =   '';
        $data['seller']             =   '';
        $orders                     =   ParentSale::query();
        if(isset($post->start_date) &&  $post->start_date != ''){ 
            $orders                 =   $orders->whereDate('created_at','>=',$post->start_date); 
            $data['start_date']     =   $post->start_date;
        }
        if(isset($post->end_date)   &&  $post->end_date != ''){ 
            $orders                 =   $orders->whereDate('created_at','<=',$post->end_date); 
            $data['end_date']       =   $post->end_date;
        }
        // if(isset($post->seller)     &&  $post->seller != ''){ 
        //     $orders                 =   $orders->where('seller_id',$post->seller); 
        //     $data['seller']         =   $post->seller;
        // }if(isset($post->p_status)  &&  $post->p_status != ''){ 
        //     $orders                 =   $orders->where('payment_status',$post->p_status); 
        //     $data['p_status']       =   $post->p_status;
        // }if(isset($post->o_status)  &&  $post->o_status != ''){ 
        //     $orders                 =   $orders->where('order_status',$post->o_status); 
        //     $data['o_status']       =   $post->o_status;
        // }
        $data['sellers']            =   getDropdownData($this->getSalesSellers(),'seller_id','fname');
        $data['orderStatusList']    =   getDropdownData(SalesOrderStatusList::where('is_active',1)->where('is_deleted',0)->orderBy('short','asc')->get(),'identifier','title');
        $data['orders']             =   $orders->orderBy('id','desc')->get(); 
        if($type == 'request'){   
            if($viewType == 'ajax') {   return view('admin.sales.admin_order.list.content',$data); }else{ return view('admin.sales.admin_order.page',$data); }        
        }else if($type== 'ref_reqs'){ 
            if($viewType == 'ajax') {   return view('admin.sales.refund_request.list.content',$data); }else{ return view('admin.sales.refund_request.page',$data); }        
        }else{ if($viewType         ==  'ajax') { return view('admin.sales.admin_order.list.content',$data); }else{  return view('admin.sales.admin_order.page',$data); } }
    }
    
    function order(Request $request, $id=0,$type=''){
        $post                       =   (object)$request->post();
        $data['title']              =   'Sales Orders';
        $data['menuGroup']          =   'salesGroup';
        $data['menu']               =   'sales_order';
        $data['order']              =   ParentSale::where('id',$id)->first();
        if($type == 'request')      {   return view('admin.sales.admin_order.view',$data); }
    }
    
    function getSalesSellers(){
        $sales                      =   SalesOrder::get(['seller_id']); $sellerIds = [];
        if($sales){ foreach($sales  as  $row){ $sellerIds[] = $row->seller_id; } }else{ $sellerIds = [0]; }
        return SellerInfo::whereIn('seller_id',$sellerIds)->get();
    }
}
