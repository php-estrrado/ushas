<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Session;
use DB;
use App\Models\Auction;
use App\Models\AuctionHist;
use App\Models\AuctionRefundHist;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\Admin;
use App\Models\SettingOther;
use App\Models\customer\CustomerWallet_Model;
use App\Models\SaleOrder;


use App\Rules\Name;
use Validator;

class AuctionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
   
    
    // user roles and modules
    
    public function auctions()
        { 
        $data['title']              =   'Auctions';
        $data['menu']               =   'auctions';
        $data['auctions']              =   Auction::getAuctions();

        $data['sellers']      =    Store::where('is_active',1)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();
        $data['products']      =    Product::where('is_active',1)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();
        $data['maxprice'] = Auction::max('min_bid_price');
        $data['minprice'] = Auction::min('min_bid_price');
        // dd($data);
        return view('admin.auctions.list',$data);
        }



          public function logAuction($acn_id)
        { 
        $data['title']              =   'Auction Logs';
        $data['menu']               =   'auction-log';
        $data['auctions']              =   Auction::getAuctionData($acn_id);
        $data['log']                = AuctionHist::getLog($acn_id);
        $data['acn_id']             = $acn_id;
    
        // dd($data);
 
         return view('admin.auctions.logs',$data);
        }


           public function auctionStatus(Request $request)
        {
        $input = $request->all();
        
        if($input['id']>0) {
        $deleted =  Auction::where('id',$input['id'])->update(array('is_active'=>$input['status']));
        
        return '1';
        }else {
        
        return '0';
        }
        
        }

           public function auctionFilter(Request $request)
        {
            $input = $request->all();
            // return $input;
            $startdate = $input['startdate'];
            $enddate = $input['enddate'];
            $startprice = $input['startprice'];
            $endprice = $input['endprice'];
            $filterPr = $input['filterPr'];
            $filterSell = $input['filterSell'];
            
            $acn_list = Auction::query();
            if(isset($filterSell)) {
            if($filterSell !="") {
            $acn_list = $acn_list->where('seller_id', $filterSell);
            }
            }
            if(isset($filterPr)) {
            if($filterPr !="") {
            $acn_list = $acn_list->where('product_id', $filterPr);
            }
            }
            if(isset($startprice) && isset($endprice)) {
            if($startprice !="" && $endprice !="") {
            $acn_list = $acn_list->when($startprice && $endprice, function ($query) use ($startprice, $endprice) {
            return $query->whereBetween('min_bid_price',[$startprice, $endprice]);
            });
            }
            }

            $acn_list = $acn_list->orderBy('id', 'DESC');
            $acn_list = $acn_list->get();

            // dd($acn_list);

            if($acn_list && count($acn_list) > 0) {
            $acn_html = "";
                            foreach($acn_list as $row) {

                            $valid_from = $row['auct_start'];
                            $valid_till = $row['auct_end'];
                         $prod_img=url('storage/app/public/product/'.$row['product_img']);
                        if(Product::where('id',$row["product_id"])->first()){
                          $prd_title = Product::where('id',$row["product_id"])->first()->name;
                        }else {
                          $prd_title = "";
                        } 
                         $acn_desc = Auction::getCpnContent($row["auction_desc_cid"]);

                          if($row['is_active'] ==1){ $actv= "Active"; $chkd= "checked"; }else{ $actv="Inactive"; $chkd= ""; }
                          if(isset($row['bids'])) { $cnt = count($row['bids']); }else { $cnt = 0; }

                          if($startdate !="" && $enddate !="") {

                        // dd("startdate".$startdate."--valid_from".$valid_from."--enddate".$enddate."---valid_till".$valid_till);
                               if((strtotime($startdate) <= strtotime($valid_from) || strtotime($startdate) <= strtotime($valid_till)) && (strtotime($enddate) >= strtotime($valid_from) ) ) {
                                 
                            $acn_html .='<tr><td class="align-middle select-checkbox" id="moduleid" data-value="'.$row['id'].'">
                                    <label class="custom-control custom-checkbox">
                                  
                                    </label>
                                  </td>
                                  <td class="align-middle" >
                                    <div class="d-flex">
                                    <p>'.$row['auction_code'].'</p>
                                  </div>
                                  </td>

                                  <td class="align-middle" >
                                    
                                      <div class="d-flex">';
                                      if($row['product_img']) {
                                   $acn_html .='     <span class="avatar brround avatar-md d-block" style="background-image: url('. $prod_img.')"></span>';
                                      }else {
                                    $acn_html .='    <span class="avatar brround avatar-md d-block" ></span>';
                                      }
                                  
                                     
                                      
                                    
                                      $acn_html .='<div class="ml-3 mt-1">
                                        <p>'.$prd_title.'</p>
                                      </div>
                                    </div>
                                  
                                  </td>
                                  <td class="align-middle" >
                                    <div class="d-flex">
                                    <p>'.$this->wordlimit($acn_desc, 20, 20).'</p>
                                  </div>
                                  </td>
                                  <td class="align-middle" >
                                    <div class="d-flex">
                                      <p>'.date('Y-m-d', strtotime($row['auct_start'])).'</p>
                                    </div>
                                  </td>
                                  <td class="align-middle" >
                                    <div class="d-flex">
                                      <p>'.date('Y-m-d', strtotime($row['auct_end'])).'</p>
                                    </div>
                                  </td>
                                  <td class="align-middle" >
                                    <div class="d-flex">
                                      <p>'.$row['seller_name'].'</p>
                                    </div>
                                  </td>
                                  <td class="text-nowrap align-middle"  data-search="'.$actv.'">
                                    
                                  <div class="switch">
                                  <input class="switch-input status-btn ser_status" data-selid="'.$row['id'].'"  id="status-'.$row['id'].'"  type="checkbox"  '.$chkd.' >
                                  <label class="switch-paddle" for="status-'.$row['id'].'">
                                  <span class="switch-active" aria-hidden="true">Active</span>
                                  <span class="switch-inactive" aria-hidden="true">Inactive</span>
                                  </label>
                                  </div>                    
                  
                                  </td>
                                  <td class="align-middle" >
                                    <div class="d-flex">
                                      <p>'.$row['min_bid_price'].'</p>
                                    </div>
                                  </td>
                                  <td class="align-middle" >
                                    <div class="d-flex">
                                      <p>'.$row['shipping_cost_id'].'</p>
                                    </div>
                                  </td>
                                  <td class="align-middle" >
                                    <div class="d-flex">
                                      <p>'.$cnt.'</p>
                                    </div>
                                  </td>
                                  <td class="align-middle" >
                                    <div class="d-flex">
                                      <p>'.$row['bid_allocated_to_user'].'</p>
                                    </div>
                                  </td>
                                
                                  <td class="align-middle">
                                    <div class="btn-group align-top">
                                      
                                      <a href="'. url('admin/auctions/log/') .'/'.$row['id'].'"   class="mr-2 btn btn-info btn-sm editmodule"><i class="fe fe-edit mr-1"></i> View</a>
                                      
                                    </div>
                                  </td>
                                  
                                </tr>';

                               }

                          }else {

                          
                             $acn_html .='<tr><td class="align-middle select-checkbox" id="moduleid" data-value="'.$row['id'].'">
                                    <label class="custom-control custom-checkbox">
                                  
                                    </label>
                                  </td>
                                  <td class="align-middle" >
                                    <div class="d-flex">
                                    <p>'.$row['auction_code'].'</p>
                                  </div>
                                  </td>

                                  <td class="align-middle" >
                                    
                                      <div class="d-flex">';
                                      if($row['product_img']) {
                                   $acn_html .='     <span class="avatar brround avatar-md d-block" style="background-image: url('. $prod_img.')"></span>';
                                      }else {
                                    $acn_html .='    <span class="avatar brround avatar-md d-block" ></span>';
                                      }
                                  
                                     
                                      
                                    
                                      $acn_html .='<div class="ml-3 mt-1">
                                        <p>'.$prd_title.'</p>
                                      </div>
                                    </div>
                                  
                                  </td>
                                  <td class="align-middle" >
                                    <div class="d-flex">
                                    <p>'.$this->wordlimit($acn_desc, 20, 20).'</p>
                                  </div>
                                  </td>
                                  <td class="align-middle" >
                                    <div class="d-flex">
                                      <p>'.date('Y-m-d', strtotime($row['auct_start'])).'</p>
                                    </div>
                                  </td>
                                  <td class="align-middle" >
                                    <div class="d-flex">
                                      <p>'.date('Y-m-d', strtotime($row['auct_end'])).'</p>
                                    </div>
                                  </td>
                                  <td class="align-middle" >
                                    <div class="d-flex">
                                      <p>'.$row['seller_name'].'</p>
                                    </div>
                                  </td>
                                  <td class="text-nowrap align-middle"  data-search="'.$actv.'">
                                    
                                  <div class="switch">
                                  <input class="switch-input status-btn ser_status" data-selid="'.$row['id'].'"  id="status-'.$row['id'].'"  type="checkbox"  '.$chkd.' >
                                  <label class="switch-paddle" for="status-'.$row['id'].'">
                                  <span class="switch-active" aria-hidden="true">Active</span>
                                  <span class="switch-inactive" aria-hidden="true">Inactive</span>
                                  </label>
                                  </div>                    
                  
                                  </td>
                                  <td class="align-middle" >
                                    <div class="d-flex">
                                      <p>'.$row['min_bid_price'].'</p>
                                    </div>
                                  </td>
                                  <td class="align-middle" >
                                    <div class="d-flex">
                                      <p>'.$row['shipping_cost_id'].'</p>
                                    </div>
                                  </td>
                                  <td class="align-middle" >
                                    <div class="d-flex">
                                      <p>'.$cnt.'</p>
                                    </div>
                                  </td>
                                  <td class="align-middle" >
                                    <div class="d-flex">
                                      <p>'.$row['bid_allocated_to_user'].'</p>
                                    </div>
                                  </td>
                                
                                  <td class="align-middle">
                                    <div class="btn-group align-top">
                                      
                                      <a href="'. url('admin/auctions/log/') .'/'.$row['id'].'"   class="mr-2 btn btn-info btn-sm editmodule"><i class="fe fe-edit mr-1"></i> View</a>
                                      
                                    </div>
                                  </td>
                                  
                                </tr>';
                          }

                            
                             }
                           }else {
                            $acn_html = "0";
                           }


        return $acn_html;
        
        }
    
           public  function wordlimit($str, $limit=100, $strip = false) {
            $str = ($strip == true)?strip_tags($str):$str;
            if (strlen ($str) > $limit) {
                $str = substr ($str, 0, $limit - 3);
                return (substr ($str, 0, strrpos ($str, ' ')).'...');
            }
            return trim($str);
        }
       public function auctionRefundRequests()
        { 
        $data['title']              =   'Auction Refund Requests';
        $data['menu']               =   'auctions-refund';
        $where_Arr = array('status'=>"processing",'status'=>"closed");
         $data['auctions']              =   Auction::getAuctions(1);

        $data['sellers']      =    Store::where('is_active',1)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();
        $data['products']      =    Product::where('is_active',1)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();
        $data['maxprice'] = Auction::max('min_bid_price');
        $data['minprice'] = Auction::min('min_bid_price');
        // dd($data);
        return view('admin.auctions.refund-requests.list',$data);
        }

           public function logRefundRequests($acn_id)
        { 
        $data['title']              =   'Auction Refund Request List';
        $data['menu']               =   'auctions-refund';
        $auction_data               = Auction::getAuctionData($acn_id);
        $data['auctions']           =   $auction_data;
        // $data['log']                = AuctionHist::refundList($acn_id,$auction_data['bid_allocated_to']);
        $data['acn_id']             = $acn_id;
        $data['refund_charges']             = SettingOther::getOtherSettings();
        // $data['log'] = AuctionRefundHist::getLog($acn_id,'pending');
        $data['log'] = AuctionRefundHist::getLog($acn_id,'');
    
        // dd($data);
 
          return view('admin.auctions.refund-requests.logs',$data);
        }

         public function processRefund(Request $request)
        { 
          $input = $request->all();
          $auction_id = $input['auction_id'];

          $failed = $success = 0;
          foreach($input['to_process'] as $k=>$refund_log){

            $refund_data = AuctionRefundHist::where('id',$refund_log)->first();
            $sale_id = $refund_data->sale_id;
            $user_id = $refund_data->user_id;
            $refund_amount = $refund_data->refund_amount;
            $wallet_arr = array();
            $wallet_arr['user_id'] = $user_id;
            $wallet_arr['source'] = "Auction Refund";
            $wallet_arr['source_id'] = $auction_id;
            $wallet_arr['credit'] = $refund_amount;
            $wallet_arr['desc'] = "Auction Refund";
            $wallet_arr['is_active'] = 1;
            $wallet_arr['is_deleted'] = 0;
            $wallet_arr['created_at'] = date("Y-m-d H:i:s");
            $wallet_arr['updated_at'] = date("Y-m-d H:i:s");
            $update_wallet = CustomerWallet_Model::create($wallet_arr)->id;

            if($update_wallet){
            
            $aucBid = Auction::where('id',$auction_id)->first();
            $from   = 1; 
            $utype  = 1;
            $to     = $user_id;
            $ntype  = 'auction_refund';
            $title  = 'Auction Refund';
            $desc   = 'The amount refunded for #'.$aucBid->auction_code.' auction';
            $refId  = $auction_id;
            $reflink = 'customer/auction';
            $notify  = 'customer';
            addNotification($from,$utype,$to,$ntype,$title,$desc,$refId,$reflink,$notify);
            
            $sale_arr = array();
            $sale_arr['payment_status'] = "refunded";
            $sale_arr['updated_at'] = date("Y-m-d H:i:s");
            SaleOrder::where("id",$sale_id)->update($sale_arr);

            $refund_arr = array();
            $refund_arr['updated_at'] = date("Y-m-d H:i:s");
            $refund_arr['status'] ="completed";
            AuctionRefundHist::where("id",$refund_log)->update($refund_arr);
            $success = 1;
            }else {

            $refund_arr = array();
            $refund_arr['updated_at'] = date("Y-m-d H:i:s");
            $refund_arr['status'] ="failed";
            AuctionRefundHist::where("id",$refund_log)->update($refund_arr);

            $failed = 1;
              
     
            }

  

          }

              $auct_arr = array();
              $auct_arr['status'] = "closed";
              $auct_arr['updated_at'] = date("Y-m-d H:i:s");
              $auct_arr['updated_by'] = auth()->user()->id;
              Auction::where("id",$auction_id)->update($auct_arr);

              if($failed == 1 && $success ==0) {
                Session::flash('message', ['text'=>'Unable to process refund','type'=>'danger']);
              }else if($failed == 0 && $success ==1) {
                Session::flash('message', ['text'=>'Refund processed successfully.','type'=>'success']);
              }else if($failed == 1 && $success ==1) {
                Session::flash('message', ['text'=>'Unable to process some refund request(s).','type'=>'danger']);
              }  


        return redirect()->back();
        }

   
}
