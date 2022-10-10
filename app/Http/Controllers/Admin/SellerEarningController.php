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
use DB;
use Validator;
use Session;

class SellerEarningController extends Controller{
    public function __construct(){ $this->middleware('auth:admin'); }
    public function earnings($sellerId=0){ 
        $data['title']              =   'Seller Earnings';
        $data['menuGroup']          =   'sellerGroup';
        $data['menu']               =   'earning';
        $data['sellerId']           =   $sellerId;
        $data['sellers']            =   getDropdownData($this->getSalesSellers(),'seller_id','fname');
        $data['seller']             =   '';
        if($sellerId > 0){
            $data['earnings']       =   SalesOrder::where('order_status','delivered')->where('payment_status','paid')->where('seller_id',$sellerId)->orderBy('id','desc')->get();
                                        return view('admin.earning.list',$data);
        }else{ $data['earnings']    =   SalesOrder::where('order_status','delivered')->where('payment_status','paid')->orderBy('id','desc')->get(); }
        return view('admin.earning.page',$data);
    }
    
    public function earnings_filter(Request $request){ 
        $post                       =   (object)$request->post();
        if(isset($post->viewType))  {   $viewType = $post->viewType; }else{ $viewType = ''; }
        $data['title']              =   'Seller Earnings';
        $data['menuGroup']          =   'sellerGroup';
        $data['menu']               =   'earning';
        $data['sellerId']           =   '';
        $data['sellers']            =   getDropdownData($this->getSalesSellers(),'seller_id','fname');
        $data['seller']             =   '';
        $earning                    = SalesOrder::where('order_status','delivered')->where('payment_status','paid');
        if($viewType=='ajax')
        {
            if(isset($post->start_date) &&  $post->start_date != ''){ 
            $earning                 =   $earning->whereDate('created_at','>=',$post->start_date); 
            $data['start_date']     =   $post->start_date;
        }
        if(isset($post->end_date)   &&  $post->end_date != ''){ 
            $earning                =   $earning->whereDate('created_at','<=',$post->end_date); 
            $data['end_date']       =   $post->end_date;
        }
        if(isset($post->seller)     &&  $post->seller != ''){ 
            
            $earning                 =   $earning->where('seller_id',$post->seller); 
            $data['seller']          =   $post->seller;
        }
        } $data['earnings']    =   $earning->orderBy('id','desc')->get(); 
        return view('admin.earning.list.content',$data);
    }
    
    public function settlements(Request $request,$sellerId=0){ 
        $post                       =   (object)$request->post();
        $data['title']              =   'Seller Settlements';
        $data['menuGroup']          =   'sellerGroup';
        $data['menu']               =   'settlement';
        $data['sellers']            =   getDropdownData($this->getSalesSellers(),'seller_id','fname');
        if($sellerId > 0){
            $data['settlements']    =   SalesOrder::where('order_status','delivered')->where('payment_status','paid')->groupBy('seller_id')->orderBy('id','desc')->get();
                                        return view('admin.earning.settlement',$data);
        }else{ 
            $data['settlements']    =   SalesOrder::where('order_status','delivered')->where('payment_status','paid')->groupBy('seller_id')->orderBy('id','desc')->get();
            if(isset($post->type)   ==  'ajax'){ return view('admin.settlement.list',$data); }else{ return view('admin.settlement.page',$data); }
        }
        
    }
    public function bulk_settlements(Request $request,$sellerId=0){ 
        $post                       =   (object)$request->post();
        $data['title']              =   'Seller Bulk Settlements';
        $data['menuGroup']          =   'sellerGroup';
        $data['menu']               =   'settlement';
        $data['categories']      =   Category::where('is_active',1)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();
        $data['states']      =    DB::table('states')->where('country_id', 101)->where('is_deleted', 0)->get();
        if($sellerId > 0){
            $data['settlements']    =   SalesOrder::where('order_status','delivered')->where('payment_status','paid')->groupBy('seller_id')->orderBy('id','desc')->get();
                                        //return view('admin.earning.settlement',$data);
        }else{ 
            $data['settlements']    =   SalesOrder::where('order_status','delivered')->where('payment_status','paid')->groupBy('seller_id')->orderBy('id','desc')->get();
            if(isset($post->type)   ==  'ajax'){ return view('admin.bulk_settlement.list',$data); }else{ return view('admin.bulk_settlement.page',$data); }
        }
        
    }
    public function bulkFilter(Request $request)
        {
            $input = $request->all();
            // return $input;

            $settlement_list = SalesOrder::query();
            if(isset($input['filterSell'])) {
                 $filterSell = $input['filterSell'];
                if($filterSell !="") {
                $idsArr = explode(',',$filterSell);  

                $settlement_list = $settlement_list->whereIn('seller_id',function($query) use ($idsArr){
                $query->select('seller_id')->from('usr_store_categories')->whereIn('category_id',$idsArr);});
                
                    
                }
            }
            if(isset($input['city_id'])) {
                $city_id = $input['city_id'];
                if($city_id !="") {
                    $settlement_list = $settlement_list->whereIn('seller_id',function($query) use ($city_id) {
                    $query->select('seller_id')->from('usr_stores')->where('city_id',$city_id);});
                }
            }
            if(isset($input['state_id'])) {
                $state_id = $input['state_id'];
                if($state_id !="") {
                    $settlement_list = $settlement_list->whereIn('seller_id',function($query) use ($state_id) {
                    $query->select('seller_id')->from('usr_stores')->where('state_id',$state_id);});
                }
            }
            if(isset($input['startdate']) && isset($input['enddate'])) {
                $startdate = $input['startdate'];
            $enddate = $input['enddate'];
            if($startdate !="" && $enddate !="") {
            $settlement_list = $settlement_list->when($startdate && $enddate, function ($query) use ($startdate, $enddate) {
            return $query->whereBetween('created_at',[$startdate, $enddate]);
            });
            }
            }

            $settlement_list = $settlement_list->where('order_status','delivered')->where('payment_status','paid')->groupBy('seller_id')->orderBy('id','desc')->get();
           

            // dd($settlement_list);
        //             $data['title']              =   'Seller Bulk Settlements';
        // $data['menuGroup']          =   'sellerGroup';
        // $data['menu']               =   'settlement';
        // $data['filter']               =   1;
        // $data['categories']      =   Category::where('is_active',1)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();
        // $data['states']      =    DB::table('states')->where('country_id', 101)->where('is_deleted', 0)->get();
        // $data['settlements']    = $settlement_list;
        //     return view('admin.bulk_settlement.list.content',$data);

            if($settlement_list && count($settlement_list) > 0) {
            $blk_html = ""; $n = 0;
                            foreach($settlement_list as $row) {

                                $n++;
                          $earnings = $row->totEarnings($row->seller_id); $totEarn = ($earnings->sum('g_total')-$earnings->sum('ecom_commission'));

                                 
                            $blk_html .=' <tr class="dtrow" id="dtrow-'.$row->id.'">
                                                    
                                                   <td class="process_selection">
                                                   <input type="checkbox"  class="processitem" name="to_process['.$row->seller_id.']"  value="'.($totEarn - $row->paidSettlement($row->seller_id)).'" data-items="'.($totEarn - $row->paidSettlement($row->seller_id)).'" ></td>

                                                    <td>'.$row->seller->sellerInfo->fname.'</td>
                                                    <td>'.$row->seller->store->store_name.'</td>
                                                    <td>'. getCurrency()->name .' '.$totEarn.'</td>
                                                    <td>'. getCurrency()->name .' '.$row->paidSettlement($row->seller_id).'</td>
                                                    <td>'. getCurrency()->name .' '.($totEarn - $row->paidSettlement($row->seller_id)).'</td>';
                             }
                          }else {
                            $blk_html = "0";
                          }


        return $blk_html;
        
        }
    
    function payment(Request $request, $sellerId){
        $post                       =   (object)$request->post();
        $data['seller']             =   SellerInfo::where('seller_id',$sellerId)->first();
        $data['store']              =   Store::where('seller_id',$sellerId)->first();
        $data['post']               =   $post;
        $totAmount                  =   SalesOrder::where('order_status','delivered')->where('payment_status','paid')->where('seller_id',$sellerId)->sum('g_total');
        $totCommission              =   SalesOrder::totalSellerEarnings($sellerId);
        $data['earnings']           =   $totCommission['earnings'];
        $data['settled']            =   Settlement::where('seller_id',$sellerId)->where('is_deleted',0)->sum('amount');
        return view('admin.earning.payment',$data);
    }
    
    function saveSettltment(Request $request){
        $post                       =   (object)$request->post();
        $insId                      =   Settlement::create(['seller_id'=>$post->seller_id,'admin_id'=>auth()->user()->id,'amount'=>$post->pay_amt]);
        $data['title']              =   'Seller Earnings';
        $data['menuGroup']          =   'sellerGroup';
        $data['menu']               =   'earning';
        $data['sellerId']           =   '';
        $data['sellers']            =   getDropdownData($this->getSalesSellers(),'seller_id','fname');
        $data['seller']             =   '';
        if($post->page              ==  'earning'){ 
            $data['earnings']       =   SalesOrder::where('order_status','delivered')->where('payment_status','paid')->orderBy('id','desc')->get();
        }else{ $data['settlements'] =   SalesOrder::where('order_status','delivered')->where('payment_status','paid')->groupBy('seller_id')->orderBy('id','desc')->get();
        }
        return view('admin.'.$post->page.'.list',$data);
    }
     function saveBulkSettltment(Request $request){
        $post                       =   (object)$request->post();
        
        if(isset($post->to_process)){
            foreach($post->to_process as $k=>$v){
           
                Settlement::create(['seller_id'=>$k,'admin_id'=>auth()->user()->id,'amount'=>$v]);
            }
            Session::flash('message', ['text'=>'Seller settlement processed successfully.','type'=>'success']);
            
        }else {
            Session::flash('message', ['text'=>'Unable to process settlement','type'=>'danger']);
        }
         return redirect('/admin/seller/bulk-settlements');
    
    }
    
    function getSalesSellers(){
        $sales                      =   SalesOrder::get(['seller_id']); $sellerIds = [];
        if($sales){ foreach($sales  as  $row){ $sellerIds[] = $row->seller_id; } }else{ $sellerIds = [0]; }
        return SellerInfo::whereIn('seller_id',$sellerIds)->get();
    }
}
