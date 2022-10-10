<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Mail;
use App\Models\SalesOrder;
use App\Models\Tax;
use App\Models\Language;
use App\Models\customer\Customer;
use App\Models\customer\CustomerInfo;
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
use App\Models\Email;
use App\Models\SaleOrder;
use App\Models\SaleorderItems;
use App\Models\SalesOrderShippingStatus;
use Validator;

class SalesOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function orders(Request $request,$type=''){ // echo Auth::user()->id; die;
        $post                       =   (object)$request->post();
        if(isset($post->viewType))  {   $viewType = $post->viewType; }else{ $viewType = ''; }
        $data['title']              =   'Sales Orders';
        $data['menuGroup']          =   'salesGroup';
        $data['menu']               =   'sales_request';
        $data['start_date']         =   ''; $data['end_date'] =   ''; $data['p_status'] =   ''; $data['o_status'] =   '';
        $data['seller']             =   '';
        $orders                     =   SalesOrder::where('order_status', '!=', "initiated")->where('org_id',1);
        if($type == 'request')      {   $orders = $orders->where('order_status','pending'); }
        else if($type == 'ref_reqs'){   $orders = $orders->where('order_status','cancelled')->where('payment_status','success'); }
        $data['title']              =   'Sales Order';
       // if($type == 'international'){   
       //  $data['title']              =   'International Orders';
       //  $orders = $orders->whereIn('id',function($query) {
       //  // $query->select('sales_id')->from('sales_order_shippings')->where('ship_operator_id',2);
       //     $query->select('sales_id')->from('sales_order_adderss')->where('country','!=',229);
            
       //  });
       //  }else {
       //      $data['title']              =   'Domestic Orders';
       //  $orders = $orders->whereIn('id',function($query) {
       //  // $query->select('sales_id')->from('sales_order_shippings')->where('ship_operator_id',1);
       //  $query->select('sales_id')->from('sales_order_adderss')->where('country',229);
            
       //  });  
       //  }
        if(isset($post->start_date) &&  $post->start_date != ''){ 
            $orders                 =   $orders->whereDate('created_at','>=',$post->start_date); 
            $data['start_date']     =   $post->start_date;
        }
        if(isset($post->end_date)   &&  $post->end_date != ''){ 
            $orders                 =   $orders->whereDate('created_at','<=',$post->end_date); 
            $data['end_date']       =   $post->end_date;
        }
        if(isset($post->seller)     &&  $post->seller != ''){ 
            $orders                 =   $orders->where('seller_id',$post->seller); 
            $data['seller']         =   $post->seller;
        }if(isset($post->p_status)  &&  $post->p_status != ''){ 
            $orders                 =   $orders->where('payment_status',$post->p_status); 
            $data['p_status']       =   $post->p_status;
        }if(isset($post->o_status)  &&  $post->o_status != ''){ 
            $orders                 =   $orders->where('order_status',$post->o_status); 
            $data['o_status']       =   $post->o_status;
        }
        $data['type']       =   $type;
        $data['sellers']            =  []; //getDropdownData($this->getSalesSellers(),'seller_id','fname');
        $data['orderStatusList']    =   getDropdownData(SalesOrderStatusList::where('is_active',1)->where('is_deleted',0)->orderBy('short','asc')->get(),'identifier','title');
        $data['orders']             =   $orders->orderBy('id','desc')->get(); 
        if($type == 'request'){   
            
            if($viewType == 'ajax') {   return view('admin.sales.order_request.list.content',$data); }else{ return view('admin.sales.order_request.page',$data); }        
        }else if($type== 'ref_reqs'){
          
            if($viewType == 'ajax') {   return view('admin.sales.refund_request.list.content',$data); }else{   return view('admin.sales.refund_request.page',$data); }        
        }else{ if($viewType         ==  'ajax') { return view('admin.sales.order.list.content',$data); }else{  return view('admin.sales.order.page',$data); 
            
        } }
    }
    
    function cancelOrders(Request $request,$type=''){
        $post                       =   (object)$request->post();
        if(isset($post->viewType))  {   $viewType = $post->viewType; }else{ $viewType = ''; }
        $data['title']              =   'Cancel Orders';
        $data['menuGroup']          =   'salesGroup';
        $data['menu']               =   'cancel_order';
        $data['type']               =   $type;
        $orders                     =   SalesOrderCancel::where('is_deleted',0);
        if($type == 'request')      {   $orders =   $orders->where('status','pending'); }
        else if($type == 'past')    {   $orders =   $orders->where('status','!=','pending'); }
        if(isset($post->start_date) &&  $post->start_date != ''){ 
            $orders                 =   $orders->whereDate('created_at','>=',$post->start_date); 
            $data['start_date']     =   $post->start_date;
        }
        if(isset($post->end_date)   &&  $post->end_date != ''){ 
            $orders                 =   $orders->whereDate('created_at','<=',$post->end_date); 
            $data['end_date']       =   $post->end_date;
        }
        $data['orders']             =   $orders->orderBy('id','desc')->get();
    //    echo '<pre>'; print_r($post); echo '</pre>'; die;
    
        if($viewType == 'ajax')     {   return view('admin.sales.cancel_order.list.content',$data); }      
        return view('admin.sales.cancel_order.page',$data); 
    }
    
    function order(Request $request, $id=0,$type=''){
        $post                       =   (object)$request->post();
        $data['title']              =   'Sales Orders';
        $data['menuGroup']          =   'salesGroup';
        $data['menu']               =   'sales_order';
        $data['order']              =   SalesOrder::where('id',$id)->first();
        
        if($type == 'request')      {   return view('admin.sales.order_request.view',$data); }
    }
    
    function invoice(Request $request, $id=0){
        $post                       =   (object)$request->post();
        $data['title']              =   'Sales Orders';
        $data['menuGroup']          =   'salesGroup';
        $data['menu']               =   'sales_order';
        $data['order']              =   SalesOrder::where('id',$id)->first();
        return view('admin.sales.order.invoice',$data);
    }
    
    function cancelOrder(Request $request,$id='',$type=''){
        $post                       =   (object)$request->post();
        $data['title']              =   'Cancel Order Detail';
        $data['menuGroup']          =   'salesGroup';
        $data['menu']               =   'cancel_detail';
       
        $data['res']                =   SalesOrderCancel::where('id',$id)->first();
         // dd($data);
      //  if($type == 'request')      {   return view('sales.cancel_order.page',$data); }
        return view('admin.sales.cancel_order.view',$data); 
    }
    
    function updateStatus(Request $request){
        $post                       =   (object)$request->post(); // echo '<pre>'; print_r($post); echo '</pre>'; die;
        if($post->model             ==  'order_cancels'){
            $update                 =   SalesOrderCancel::where('id',$post->id)->update([$post->field => $post->value]);
            $cancelRec              =   SalesOrderCancel::where('id',$post->id)->first(); $salesId = $cancelRec->sales_id;
            if($post->value         ==  'accepted'){
             $cnl = ['order_status'=>'cancelled','cancel_process' => 2]; 

             //ORDER Notification accepted
             $saleval = SalesOrder::where('id',$cancelRec->sales_id)->first();
            $from   = 1; 
            $utype  = 1;
            $to_c     = $saleval->cust_id;
            $to_a     = 1;
            $ntype  = 'cancel_order';
            $title  = 'Order has been cancelled and refund initiated';
            $desc   = 'Order #'.$saleval->order_id.' order has been cancelled by customer request and refund initiated';
            $refId  = $post->id;
            $reflink = 'customer/order/detail';
            $reflink_a = 'admin/sales/cancel/orders';
            $notify_c  = 'customer';
            $notify_a  = 'admin';
            addNotification($from,$utype,$to_c,$ntype,$title,$desc,$refId,$reflink,$notify_c);
            addNotification($from,$utype,$to_a,$ntype,$title,$desc,$refId,$reflink_a,$notify_a);

            //Refund
            // $sales_refund_tbl = SalesOrderRefundPayment::where('sales_id',$saleval->id)->first();
            // if(!$sales_refund_tbl){
            $create_sales_refund_tbl = SalesOrderRefundPayment::create(['ref_id'=>$cancelRec->id,'sales_id'=>$saleval->id,'source'=>'cancel_order','refund_mode'=>1,'total'=>$saleval->g_total,'refund_tax'=>0,'grand_total'=>$saleval->g_total])->id;
            $this->refund_to_wallet($saleval,'cancel_order');


            //email
            $customer_email=$saleval->customer->custEmail($saleval->customer->email);
           $data['data'] = array("customer_name"=>$saleval->customer->info->first_name,'title'=>'Order Cancelled','message'=>'Your Order:'.$saleval->order_id.' has been cancelled and refund initiated','saleorder_id'=>$saleval->order_id);
                                    $var = Mail::send('emails.cancel_order', $data, function($message) use($data,$customer_email) {
                                    $message->from(getadmin_mail(),geSiteName());    
                                    $message->to($customer_email);
                                   // $message->cc(['aleenaantony1020@gmail.com']); //myjewelleryshopper@gmail.com
                                    $message->subject('Order Cancelled');
                                    });
            // }//if not refunded
           }
            else if($post->value    ==  'rejected'){ $cnl['cancel_process'] = 3; $this->addCancelResponse($post->id,$post->reply); 
            //ORDER Notification rejected
             $saleval = SalesOrder::where('id',$cancelRec->sales_id)->first();
            $from   = 1; 
            $utype  = 1;
            $to_c     = $saleval->cust_id;
            $to_a     = 1;
            $ntype  = 'cancel_order';
            $title  = 'Cancel request has been rejected';
            $desc   = 'Order #'.$saleval->order_id.' cancellation request has been rejected';
            $refId  = $post->id;
            $reflink = 'customer/order/detail';
            $reflink_a = 'admin/sales/cancel/orders';
            $notify_c  = 'customer';
            $notify_a  = 'admin';
            addNotification($from,$utype,$to_c,$ntype,$title,$desc,$refId,$reflink,$notify_c);
            addNotification($from,$utype,$to_a,$ntype,$title,$desc,$refId,$reflink_a,$notify_a);
        }
            $update                 =   SalesOrder::where('id',$cancelRec->sales_id)->update($cnl);
            $orders                 =   SalesOrderCancel::where('is_deleted',0);
        }else if($post->model             ==  'refund'){
            $update                 =   SalesOrder::where('id',$post->id)->update([$post->field => $post->value]);
            $saleval = SalesOrder::where('id',$post->id)->first();
            $from   = 1; 
            $utype  = 1;
            $to     = $saleval->cust_id;
            $ntype  = 'refund_accepted';
            $title  = 'Refund Accepted';
            $desc   = 'The amount refunded for #'.$saleval->order_id.' order';
            $refId  = $post->id;
            $reflink = 'customer/order/detail';
            $notify  = 'customer';
            addNotification($from,$utype,$to,$ntype,$title,$desc,$refId,$reflink,$notify);
            $orders                 =   SalesOrder::where('order_status', '!=', "initiated")->where('org_id',1); $salesId = $post->id;
        }else{ 
            $update                 =   SalesOrder::where('id',$post->id)->update([$post->field => $post->value]);
            $cInsId                 =   SalesOrderCancel::create(['sales_id'=>$post->id,'seller_id'=>auth()->user()->id,'created_by'=>auth()->user()->id,'role_id'=>auth()->user()->role_id])->id;
                                        $this->addCancelNote($cInsId,$post->title,$post->desc);
            $orders                 =   SalesOrder::where('order_status', '!=', "initiated")->where('org_id',1); $salesId = $post->id;
        }
        $stHistory                  =   ['sales_id'=>$salesId,'status'=>$post->value,'created_by'=>auth()->user()->id,'role_id'=>auth()->user()->role_id];
        $stHistory['description']   =   "Order cancelled";    SalesOrderStatusHistory::create($stHistory);
        if($post->type              ==  'request'){ $orders = $orders->where($post->field,'pending'); }
        elseif($post->type          ==  'past'){    $orders = $orders->where($post->field,'!=','pending'); }
        elseif($post->type          ==  'ref_reqs'){    $orders = $orders->where('order_status','cancelled')->where('payment_status','success'); }
        
        if(isset($post->start_date) &&  $post->start_date != ''){ 
            $orders                 =   $orders->whereDate('created_at','>=',$post->start_date); 
            $data['start_date']     =   $post->start_date;
        }
        if(isset($post->end_date)   &&  $post->end_date != ''){ 
            $orders                 =   $orders->whereDate('created_at','<=',$post->end_date); 
            $data['end_date']       =   $post->end_date;
        }
        $data['type']               =   $post->type;
        $data['orders']             =   $orders->orderBy('id','desc')->get();
        return view('admin/'.$post->page.'.list.content',$data);
    }
    
    function addCancelNote($cId,$title,$note){
        SalesOrderCancelNote::create(['cancel_id'=>$cId,'title'=>$title,'note'=>$note,'created_by'=>auth()->user()->id,'role_id'=>auth()->user()->role_id]);
    }
    
    function addCancelResponse($cancelId,$response){
        SalesOrderCancelNote::create(['cancel_id'=>$cancelId,'created_by'=>auth()->user()->id,'role_id'=>auth()->user()->role_id,'response'=>$response]);
    }
    function updateOrderStatus(Request $request){
        $post                       =   (object)$request->post(); // echo '<pre>'; print_r($post); echo '</pre>'; die;
        $update                 =   SalesOrder::where('id',$post->id)->update([$post->field => $post->value]);
        if($post->value=="delivered"){
        $update                 =   SalesOrderShippingStatus::where('sales_id',$post->id)->update(['status' => $post->value]);
        }
        $salesId = $post->id;
        $stHistory                  =   ['sales_id'=>$salesId,'status'=>$post->value,'created_by'=>auth()->user()->id,'role_id'=>auth()->user()->role_id];
        $stHistory['description']   =   "Order ".$post->value." by admin";    SalesOrderStatusHistory::create($stHistory);
        //$this->orderStatusEmailSend($salesId);
		$saleorder = SalesOrder::where('id',$post->id)->first();
		$from   = 1; 
            $utype  = 1;
            $to_c     = $saleorder->cust_id;
			
			//dd($to_c);
            $to_a     = 1;
            
            $refId  = $post->id;
            $reflink = 'customer/order/detail';
            $reflink_a = 'admin/sales/orders';
            $notify_c  = 'customer';
            $notify_a  = 'admin';
			
			$ntype  = $post->value;
            $title  = 'order_'.$post->value;
            $desc   = 'Order #'.$saleorder->order_id.',order '.$post->value.' by the admin';
			addNotification($from,$utype,$to_c,$ntype,$title,$desc,$refId,$reflink,$notify_c);
            addNotification($from,$utype,$to_a,$ntype,$title,$desc,$refId,$reflink_a,$notify_a);
		
		if($update>0){
			return 1;
		}else{
			return 0;
		}
    }
    function orderStatusEmail(Request $request){
        $post                       =   (object)$request->post();
        $sales                      =   SalesOrder::where('id',$post->id)->first();
        $msg = '<h4>Hi, ' . $sales->address->name . ' </h4>';
        $msg .= '<p>You order has been '.$sales->order_status. ' by Admin</p>';
        $msg .= '<p>Order ID : <span>'.$sales->order_id.'</span></p><p>Order Date : <span>'.date('d M Y',strtotime($sales->created_at)).'</span</p>';
        Email::sendEmail(geAdminEmail(), $sales->address->email, '#'.$sales->order_id.' :: Amount '.$sales->order_status, $msg);
    }
    
    function getSalesSellers(){
        $sales                      =   SalesOrder::get(['seller_id']); $sellerIds = [];
        if($sales){ foreach($sales  as  $row){ 
            $seller_info =SellerInfo::where('seller_id',$row->seller_id)->where('is_deleted',0)->first();
            if($seller_info){
            $sellerIds[] = $row->seller_id; } } }else{ $sellerIds = [0]; }
        return SellerInfo::whereIn('seller_id',$sellerIds)->get();
    }
    
    public function refundOrders(Request $request,$type='refund_request'){  //echo Auth::user()->id; die;
        $post                       =   (object)$request->post(); 
        if(isset($post->viewType))  {   $viewType = $post->viewType; }else{ $viewType = ''; }
        $data['title']              =   'Refund Request Requests';
        $data['menuGroup']          =   'salesGroup';
        $data['menu']               =   'sales_request';
        $data['start_date']         =   ''; $data['end_date'] =   ''; 
        // $data['p_status'] =   ''; $data['o_status'] =   '';
        $data['seller']             =   '';
        $refunds                     =   SalesOrderRefundPayment::select('sales_orders.order_status','sales_orders.payment_status','sales_orders.order_id','sales_orders.cust_id','sales_order_refund_payments.*')->join('sales_orders','sales_orders.id','=','sales_order_refund_payments.sales_id')->where('sales_order_refund_payments.is_deleted',0)->where('sales_order_refund_payments.is_active',1);
        // if($type == 'request')      {   $orders = $orders->where('order_status','pending'); }
        // else if($type == 'ref_reqs'){   $orders = $orders->where('order_status','cancelled')->where('payment_status','success'); }
        if(isset($post->start_date) &&  $post->start_date != ''){ 
            $refunds                 =   $orders->whereDate('sales_order_refund_payments.created_at','>=',$post->start_date); 
            $data['start_date']     =   $post->start_date;
        }
        if(isset($post->end_date)   &&  $post->end_date != ''){ 
            $refunds                 =   $orders->whereDate('sales_order_refund_payments.created_at','<=',$post->end_date); 
            $data['end_date']       =   $post->end_date;
        }
      
        $data['sellers']            =  []; //getDropdownData($this->getSalesSellers(),'seller_id','fname');
        $data['orderStatusList']    =   getDropdownData(SalesOrderStatusList::where('is_active',1)->where('is_deleted',0)->orderBy('short','asc')->get(),'identifier','title');
        $data['refunds']             =   $refunds->orderBy('id','desc')->get();
       // dd($data['refunds']);
        if($viewType == 'ajax') {   return view('admin.sales.refund_request.list.content',$data); }else{ return view('admin.sales.refund_request.page',$data); }        
       
    }
    
    function refund(Request $request, $id=0,$type='')
     {
        $post                       =   (object)$request->post();
        $data['title']              =   'Order Refund Requests';
        $data['menuGroup']          =   'salesGroup';
        $data['menu']               =   'sales_order';
        
        $ord                        =   SalesOrderRefundPayment::where('id',$id)->where('is_deleted',0)->where('is_active',1)->first();
        $data['order']              =   $ord;
        $user_id                    =   $ord->order->cust_id;
        // $histories =  AuctionHist::where('user_id',$user_id)->where('sale_id',$ord->sales_id)->where('is_deleted',0)->where('is_active',1); 
        // $auctionwin = Auction::where('bid_allocated_to',$user_id)->where('sale_id',$ord->sales_id)->where('status','closed')->where('is_deleted',0)->where('is_active',1);
        // if($histories->count() > 0)
        // {
        //     if($auctionwin->count() > 0)
        //     {
        //         $au_status=   'True';
        //         $charge    =   $ord->order->bid_charge;
        //     }
        //     else
        //     {
        //         $au_status=   'False';
        //         $charge    =   0;
        //     }
        // }
        // else
        // {
        //     $au_status =   'False';
        //     $charge    =   0;
        // }
        $data['au_status'] ='';//$au_status;
        $data['bidding_charge'] = '';//$charge;
        if($type == 'request')      {   return view('admin.sales.refund_request.view',$data); }
    }
    
    function refundupdateStatus(Request $request){
        $post                       =   (object)$request->post(); 
        $refunddata  =  SalesOrderRefundPayment::where('id',$post->id)->where('is_deleted',0)->where('is_active',1)->first();
        $user_id                    =   $refunddata->order->cust_id;

        $saleval = $refunddata->order;
            $from   = 1; 
            $utype  = 1;
            $to_c     = $saleval->cust_id;
            $to_a     = 1;
            $ntype  = 'refunded';
            $title  = 'Return order amount refunded';
            $desc   = 'Order #'.$saleval->order_id.',return ordered amount refunded';
            $refId  = $post->id;
            $reflink = 'customer/order/detail';
            $reflink_a = 'admin/sales/return/orders';
            $notify_c  = 'customer';
            $notify_a  = 'admin';

        if($refunddata->source == 'cancel')
        {
            if($refunddata->refund_mode == '1')
            {
                CustomerWallet_Model::create(['user_id'=>$user_id,'source_id'=>$post->id,'source'=>'Cancel Order','credit'=>$refunddata->grand_total,'desc'=>$post->desc,'is_active'=>1]);
            }
            SalesOrder::where('id',$refunddata->sales_id)->update(['payment_status'=>$post->value]);
            $stHistory                  =   ['sales_id'=>$refunddata->sales_id,'status'=>$post->value,'created_by'=>auth()->user()->id,'role_id'=>auth()->user()->role_id];
             $stHistory['description']   =   $post->desc;    
             SalesOrderStatusHistory::create($stHistory);
             addNotification($from,$utype,$to_c,$ntype,$title,$desc,$refId,$reflink,$notify_c);
            addNotification($from,$utype,$to_a,$ntype,$title,$desc,$refId,$reflink_a,$notify_a);
        }
        else
        {
            if($refunddata->refund_mode == '1')
            {
                CustomerWallet_Model::create(['user_id'=>$user_id,'source_id'=>$post->id,'source'=>'Return order','credit'=>$refunddata->grand_total,'desc'=>$post->desc,'is_active'=>1]);
            }
            SalesOrderReturn::where('id',$refunddata->ref_id)->update(['status'=>'refund_completed','payment_status'=>$post->value]);
            SalesOrderReturnStatus::create(['sales_id'=>$refunddata->sales_id,'return_id'=>$refunddata->ref_id,'status'=>'refund_completed']);
            addNotification($from,$utype,$to_c,$ntype,$title,$desc,$refId,$reflink,$notify_c);
            addNotification($from,$utype,$to_a,$ntype,$title,$desc,$refId,$reflink_a,$notify_a);

            //email
            $customer_email=$saleval->customer->custEmail($saleval->customer->email);
           $data['data'] = array("customer_name"=>$saleval->customer->info->first_name,'title'=>'Your Order refund has been accepted','message'=>'We wanted to let you know that your Order refund for the order:'.$saleval->order_id.' has been accepted for the following','saleorder_id'=>$saleval->id,'return_id'=>$refunddata->ref_id);
                                    $var = Mail::send('emails.return_order', $data, function($message) use($data,$customer_email) {
                                    $message->from(getadmin_mail(),geSiteName());    
                                    $message->to($customer_email);
                                    $message->cc(['aleenaantony1020@gmail.com']); //myjewelleryshopper@gmail.com
                                    $message->subject('Your Order refund has been accepted');
                                    });
        }
        $data['start_date']         =   ''; $data['end_date'] =   ''; 
        $data['seller']             =   '';
        $refunds                     =   SalesOrderRefundPayment::select('sales_orders.order_status','sales_orders.payment_status','sales_orders.order_id','sales_orders.cust_id','sales_order_refund_payments.*')->join('sales_orders','sales_orders.id','=','sales_order_refund_payments.sales_id')->where('sales_order_refund_payments.is_deleted',0)->where('sales_order_refund_payments.is_active',1)->whereIn('sales_orders.payment_status',['success','paid']);
        if(isset($post->start_date) &&  $post->start_date != ''){ 
            $refunds                 =   $orders->whereDate('sales_order_refund_payments.created_at','>=',$post->start_date); 
            $data['start_date']     =   $post->start_date;
        }
        if(isset($post->end_date)   &&  $post->end_date != ''){ 
            $refunds                 =   $orders->whereDate('sales_order_refund_payments.created_at','<=',$post->end_date); 
            $data['end_date']       =   $post->end_date;
        }
      
        $data['sellers']            =   [];//getDropdownData($this->getSalesSellers(),'seller_id','fname');
        $data['orderStatusList']    =   getDropdownData(SalesOrderStatusList::where('is_active',1)->where('is_deleted',0)->orderBy('short','asc')->get(),'identifier','title');
        $data['refunds']             =   $refunds->orderBy('id','desc')->get();
        return view($post->page.'.list.content',$data);
    }

    function refund_to_wallet($sale,$type)
    {
        $refunddata = CustomerWallet_Model::where('source','Cancel Order')->where('user_id',$sale->cust_id)->where('source_id',$sale->id)->first();
        if(!$refunddata)
        {
            $user_id                    =   $sale->cust_id;
        
            
                CustomerWallet_Model::create(['user_id'=>$user_id,'source_id'=>$sale->id,'source'=>'Cancel Order','credit'=>$sale->g_total,'desc'=>$sale->order_id." order cancelled",'is_active'=>1]);
            
            SalesOrder::where('id',$sale->id)->update(['payment_status'=>'refunded']);
            $stHistory                  =   ['sales_id'=>$sale->id,'status'=>'refunded','created_by'=>auth()->user()->id,'role_id'=>auth()->user()->role_id];
             $stHistory['description']   =   $sale->order_id." order cancelled";    
             //SalesOrderStatusHistory::create($stHistory);
        
        }
    }


    //RETURN ORDERS

    function returnOrders(Request $request,$type=''){
        $post                       =   (object)$request->post();
        if(isset($post->viewType))  {   $viewType = $post->viewType; }else{ $viewType = ''; }
        $data['title']              =   'Return Orders';
        $data['menuGroup']          =   'salesGroup';
        $data['menu']               =   'return_order';
        $data['type']               =   $type;
        $orders                     =   SalesOrderReturn::where('is_deleted',0);
        if($type == 'request')      {   $orders =   $orders->whereNotIn('status',['refund_completed','shipment_rejected','return_rejected']); }
        else if($type == 'past')    {   $orders =   $orders->whereIn('status',['refund_completed','shipment_rejected','return_rejected']); }
        if(isset($post->start_date) &&  $post->start_date != ''){ 
            $orders                 =   $orders->whereDate('created_at','>=',$post->start_date); 
            $data['start_date']     =   $post->start_date;
        }
        if(isset($post->end_date)   &&  $post->end_date != ''){ 
            $orders                 =   $orders->whereDate('created_at','<=',$post->end_date); 
            $data['end_date']       =   $post->end_date;
        }
        $data['orders']             =   $orders->orderBy('id','desc')->get();
    //    echo '<pre>'; print_r($post); echo '</pre>'; die;
        if($viewType == 'ajax')     {   return view('admin.sales.return_order.list.content',$data); }      
        return view('admin.sales.return_order.page',$data); 
    }
	
	/*
	
    //RETURN ORDERS BY Aleena

    function returnOrders(Request $request,$type=''){
        $post                       =   (object)$request->post();
        if(isset($post->viewType))  {   $viewType = $post->viewType; }else{ $viewType = ''; }
        $data['title']              =   'Return Orders';
        $data['menuGroup']          =   'salesGroup';
        $data['menu']               =   'return_order';
        $data['type']               =   $type;
        $orders                     =   SalesOrderReturn::where('is_deleted',0);
        if($type == 'request')      {   $orders =   $orders->whereNotIn('status',['refund_completed','shipment_rejected','return_rejected']); }
        else if($type == 'past')    {   $orders =   $orders->whereIn('status',['refund_completed','shipment_rejected','return_rejected']); }
        if(isset($post->start_date) &&  $post->start_date != ''){ 
            $orders                 =   $orders->whereDate('created_at','>=',$post->start_date); 
            $data['start_date']     =   $post->start_date;
        }
        if(isset($post->end_date)   &&  $post->end_date != ''){ 
            $orders                 =   $orders->whereDate('created_at','<=',$post->end_date); 
            $data['end_date']       =   $post->end_date;
        }
        $data['orders']             =   $orders->orderBy('id','desc')->get();
    //    echo '<pre>'; print_r($post); echo '</pre>'; die;
        if($viewType == 'ajax')     {   return view('admin.sales.return_order.list.content',$data); }      
        return view('admin.sales.return_order.page',$data); 
    }*/
    function returnOrder(Request $request,$id='',$type=''){
        $post                       =   (object)$request->post();
        $data['title']              =   'Return Order Detail';
        $data['menuGroup']          =   'salesGroup';
        $data['menu']               =   'return_detail';
        $data['res']                =   SalesOrderReturn::where('id',$id)->first();
        return view('admin.sales.return_order.view',$data); 
    }

    function returnUpdateStatus(Request $request){
        $post                       =   (object)$request->post(); //echo '<pre>'; print_r($post); echo '</pre>'; die;
        
        $saleval = SalesOrderReturn::where('id',$post->id)->first();
            $from   = 1; 
            $utype  = 1;
            $to_c     = $saleval->order->cust_id;
			
			//dd($to_c);
            $to_a     = 1;
            
            $refId  = $post->id;
            $reflink = 'customer/order/detail';
            $reflink_a = 'admin/sales/return/orders';
            $notify_c  = 'customer';
            $notify_a  = 'admin';
        if($post->model             ==  'order_return')
        { 
            if($post->value         ==  'accepted')
            {
                 $update                 =   SalesOrderReturn::where('id',$post->id)->update(['status' => 'return_accepted']);
                $salesorder             =  SalesOrderReturn::where('id',$post->id)->first();
                SalesOrderReturnStatus::create(['sales_id'=>$salesorder->sales_id,'return_id'=>$post->id,'status' => 'return_accepted']); 
            $ntype  = 'return_accepted';
            $title  = 'Return request accepted';
            $desc   = 'Order #'.$saleval->order->order_id.',return request accepted by the admin';
            }
            else if($post->value    ==  'rejected')
            { 
                $update                 =   SalesOrderReturn::where('id',$post->id)->update(['status' => 'return_rejected']);
                $salesorder             =  SalesOrderReturn::where('id',$post->id)->first();
                SalesOrderReturnStatus::create(['sales_id'=>$salesorder->sales_id,'return_id'=>$post->id,'status' => 'return_rejected']);
                 $ntype  = 'return_rejected';
            $title  = 'Return request rejected';
            $desc   = 'Order #'.$saleval->order->order_id.',return request rejected by the admin';
            }
            $orders       =   SalesOrderReturn::where('is_deleted',0);
            addNotification($from,$utype,$to_c,$ntype,$title,$desc,$refId,$reflink,$notify_c);
            addNotification($from,$utype,$to_a,$ntype,$title,$desc,$refId,$reflink_a,$notify_a);

        }
        else if($post->model             ==  'order_shipment')
        { 
            if($post->value         ==  'accepted')
            {
				if($saleval->return_type=="replace"){
					
				$latestorder_ids=1;
            $latestOrder = SaleOrder::orderBy('created_at','DESC')->first();
            
            if($latestOrder)
            {
                $latestorder_ids = $latestOrder->id;
            }
           
            $saleorder_id = date('y').date('m').str_pad($latestorder_ids + 1, 6, "0", STR_PAD_LEFT);
	
					
					$create_saleorder = SaleOrder::create(['org_id' => 1,
                'parent_sale_id'  =>$saleval->sales_id,
                'order_id'        => $saleorder_id,
                'cust_id'         => $saleval->user_id,
                'branch_id'         => $saleval->order->branch_id,
                //'seller_id'       => $rows['seller_id'],
                'total'           =>  $saleval->amount,
                'discount'        => 0,
                'tax'             => 0,
                'shiping_charge'  => 0,
                'packing_charge'  => 0,
                'wallet_amount'   => 0,
                'g_total'         => $saleval->amount,
                //'ecom_commission' => $rows['commission'],
                'discount_type'   => 0,  
                'coupon_id'       => 0,
                'order_status'    => 'accepted',
                'payment_status'  => 'paid',
                'shipping_status' => 'pending',
                'cancel_process'  => 0,
                'cust_message'    => "",    
                'created_at'    =>date("Y-m-d H:i:s"),
                'updated_at'    =>date("Y-m-d H:i:s")]);
                $sale_id  = $create_saleorder->id;
				
				
				
				$saleorder_payment = SalesOrderPayment::create(['org_id' => 1,
                'sales_id'         => $sale_id,
                'payment_method_id'=> $saleval->order->payment->payment_method_id,
                'payment_type'     => $saleval->order->payment->payment_type,
                'transaction_id'   => $saleval->order->payment->transaction_id,
                'payment_data'     => "",
                'amount'           => $saleval->amount,
                'payment_status'   => "success"]);
				
				$replaced_product=SaleorderItems::where('id',$saleval->sales_item_id)->where('is_deleted',0)->first();
                //if()
				$create_saleorder = SaleorderItems::create([
                'sales_id'        => $sale_id,
                'parent_id'       => $sale_id,
                'prd_id'          => $prod_data->product_id,
                'prd_type'        => $prod_data->product_type,
                'prd_name'        => $product_name,
                'price'           => $actual_price,
                'qty'             => $prod_data->quantity,
                'total'           => $tot_actual,
                'discount'        => 0,
                'tax'             => $total_tax_amount,
                'row_total'       => $tot_actual + $total_tax_amount,
                'coupon_id'       => '', 
                'created_at'    =>date("Y-m-d H:i:s"),
                'updated_at'    =>date("Y-m-d H:i:s"),
                'is_deleted'    =>0]);  
                 
           $prd_stock_update = PrdStock::create([
                                                 'type'       =>'destroy',
                                                 'prd_id'     => $prod_data->product_id,
                                                 'qty'        => $prod_data->quantity,
                                                 'rate'       => $actual_price,
                                                 'created_by' => $user_id,
                                                 'sale_id'    => $sale_id,
                                                 'created_at' => date("Y-m-d H:i:s"),
                                                 'updated_at' => date("Y-m-d H:i:s")
                                                 ]); 
                                
				$insert_address = SalesOrderAddress::create(['sales_id' => $sale_id,
                'order_id'        => $saleorder_id,
                'cust_id'         => $user_id,
                'ref_addr_id'     => $input['address_id'],
                'addr_id'         => $addr_list->usr_addr_typ_id,
                'name'            => $addr_list->name,
                'phone'           => $addr_list->phone,
                'email'           => $user_email,
                'address1'        => $addr_list->address_1,
                'address2'        => $addr_list->address_2,
                'zip_code'        => $addr_list->pincode,
                'city'            => $addr_list->city_id,
                'state'           => $addr_list->state_id,
                'country'         => $addr_list->country_id,  
                'latitude'        => $addr_list->latitude,
                'longitude'       => $addr_list->longitude,
                's_addr_id'       => $addr_list->usr_addr_typ_id,
                's_name'          => $addr_list->name,
                's_phone'         => $addr_list->phone,
                's_email'         => $user_email,
                's_address1'      => $addr_list->address_1,
                's_address2'      => $addr_list->address_2,
                's_zip_code'      => $addr_list->pincode,
                's_city'          => $addr_list->city_id,
                's_state'         => $addr_list->state_id,
                's_country'       => $addr_list->country_id,  
                's_latitude'      => $addr_list->latitude,
                's_longitude'     => $addr_list->longitude]);
            

				
				 $update                 =   SalesOrderReturn::where('id',$post->id)->update(['status' => 'order_initiated']);
                $salesorder             =  SalesOrderReturn::where('id',$post->id)->first();
                SalesOrderReturnStatus::create(['sales_id'=>$salesorder->sales_id,'return_id'=>$post->id,'status' => 'refund_initiated']); 
                $ntype  = 'order_initiated';
				$title  = 'order initiated for return & replacement';
				$desc   = 'Order #'.$saleorder_id.',order initiated for return & replacement  by the admin';

				
				}else{
                $update                 =   SalesOrderReturn::where('id',$post->id)->update(['status' => 'refund_initiated']);
                $salesorder             =  SalesOrderReturn::where('id',$post->id)->first();
                SalesOrderReturnStatus::create(['sales_id'=>$salesorder->sales_id,'return_id'=>$post->id,'status' => 'refund_initiated']); 
                $ntype  = 'refund_initiated';
				$title  = 'Return order refund initiated';
				$desc   = 'Order #'.$saleval->order->order_id.',return order refund initiated by the admin';
				}
			
			}
            else if($post->value    ==  'rejected')
            { 
                $update                 =   SalesOrderReturn::where('id',$post->id)->update(['status' => 'shipment_rejected']);
                $salesorder             =  SalesOrderReturn::where('id',$post->id)->first();
                SalesOrderReturnStatus::create(['sales_id'=>$salesorder->sales_id,'return_id'=>$post->id,'status' => 'shipment_rejected']);
                $ntype  = 'shipment_rejected';
            $title  = 'Return order shipment rejected';
            $desc   = 'Order #'.$saleval->order->order_id.',return order shipment rejected by the admin';
            }
            $orders       =   SalesOrderReturn::where('is_deleted',0);

            addNotification($from,$utype,$to_c,$ntype,$title,$desc,$refId,$reflink,$notify_c);
            addNotification($from,$utype,$to_a,$ntype,$title,$desc,$refId,$reflink_a,$notify_a);
        
        }
    
        else {$orders       =   SalesOrderReturn::where('is_deleted',0);}
         if($post->type == 'request')      {   $orders =   $orders->whereNotIn('status',['refund_completed','shipment_rejected','return_rejected']); }
        else if($post->type == 'past')    {   $orders =   $orders->whereIn('status',['refund_completed','shipment_rejected','return_rejected']); }
        if(isset($post->start_date) &&  $post->start_date != ''){ 
            $orders                 =   $orders->whereDate('created_at','>=',$post->start_date); 
            $data['start_date']     =   $post->start_date;
        }
        if(isset($post->end_date)   &&  $post->end_date != ''){ 
            $orders                 =   $orders->whereDate('created_at','<=',$post->end_date); 
            $data['end_date']       =   $post->end_date;
        }
         $data['type']              =   $post->type;
        $data['orders']             =   $orders->orderBy('id','desc')->get();
        return view('admin.'.$post->page.'.list.content',$data);
    }
}