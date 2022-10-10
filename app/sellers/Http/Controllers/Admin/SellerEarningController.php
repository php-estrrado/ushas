<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Brand;
use App\Models\Seller;
use App\Models\Store;
use App\Models\SellerInfo;
use App\Models\SalesOrder;
use App\Models\Settlement;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

use Validator;
use Session;

class SellerEarningController extends Controller{
    public function __construct(){ $this->middleware('auth:admin'); }
    public function earnings($sellerId=0){ 
        $data['title']              =   'Seller Earnings';
        $data['menuGroup']          =   'sellerGroup';
        $data['menu']               =   'earning';
        $data['sellerId']           =   $sellerId;
        if($sellerId > 0){
            $data['earnings']       =   SalesOrder::where('payment_status','success')->where('seller_id',$sellerId)->orderBy('id','desc')->get();
                                        return view('admin.earning.list',$data);
        }else{ $data['earnings']    =   SalesOrder::where('payment_status','success')->orderBy('id','desc')->get(); }
        return view('admin.earning.page',$data);
    }
    
    public function settlements(Request $request,$sellerId=0){ 
        $post                       =   (object)$request->post();
        $data['title']              =   'Seller Settlements';
        $data['menuGroup']          =   'sellerGroup';
        $data['menu']               =   'settlement';
        if($sellerId > 0){
            $data['settlements']    =   SalesOrder::where('payment_status','success')->groupBy('seller_id')->orderBy('id','desc')->get();
                                        return view('admin.earning.settlement',$data);
        }else{ 
            $data['settlements']    =   SalesOrder::where('payment_status','success')->groupBy('seller_id')->orderBy('id','desc')->get();
            if(isset($post->type)   ==  'ajax'){ return view('admin.settlement.list',$data); }else{ return view('admin.settlement.page',$data); }
        }
        
    }
    
    function payment(Request $request, $sellerId){
        $post                       =   (object)$request->post();
        $data['seller']             =   SellerInfo::where('seller_id',$sellerId)->first();
        $data['store']              =   Store::where('seller_id',$sellerId)->first();
        $data['post']               =   $post;
        $totAmount                  =   SalesOrder::where('payment_status','success')->where('seller_id',$sellerId)->sum('g_total');
        $totCommission              =   SalesOrder::where('payment_status','success')->where('seller_id',$sellerId)->sum('ecom_commission');
        $data['earnings']           =   ($totAmount-$totCommission);
        $data['settled']            =   Settlement::where('seller_id',$sellerId)->where('is_deleted',0)->sum('amount');
        return view('admin.earning.payment',$data);
    }
    
    function saveSettltment(Request $request){
        $post                       =   (object)$request->post();
        $insId                      =   Settlement::create(['seller_id'=>$post->seller_id,'admin_id'=>auth()->user()->id,'amount'=>$post->pay_amt]);
        $data['title']              =   'Seller Earnings';
        $data['menuGroup']          =   'sellerGroup';
        $data['menu']               =   'earning';
        if($post->page              ==  'earning'){ 
            $data['earnings']       =   SalesOrder::where('payment_status','success')->orderBy('id','desc')->get();
        }else{ $data['settlements'] =   Settlement::where('is_deleted',0)->groupBy('seller_id')->get(); }
        return view('admin.'.$post->page.'.list',$data);
    }
}
