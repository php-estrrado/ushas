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
use App\Models\Category;
use App\Models\Store;

use App\Models\Admin;


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

             public function couponfilter(Request $request)
        {
        $input = $request->all();
        // dd()
$startdate = $input['startdate'];
$enddate = $input['enddate'];
$startprice = $input['startprice'];
$endprice = $input['endprice'];
$typesel = $input['typesel'];
if($typesel == 1) {
  $field_ser = "amount";
}else {
$field_ser = "percentage";
}

 // $coupons = Coupon::where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})
 //    ->where('ofr_value_type', $field_ser)
 //    ->when($startdate && $enddate, function ($query) use ($startdate, $enddate) {
 //        return $query->whereBetween('valid_from',[$startdate, $enddate])->orWhereBetween('valid_to',[$startdate, $enddate]);
 //    })
    
 //    ->when($startprice && $endprice, function ($query) use ($startprice, $endprice) {
 //        return $query->whereBetween('ofr_value',[$startprice, $endprice]);
 //    })

    $coupons = Coupon::where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})
    ->where('ofr_value_type', $field_ser)
   
    ->when($startprice && $endprice, function ($query) use ($startprice, $endprice) {
        return $query->whereBetween('ofr_value',[$startprice, $endprice]);
    })->orderBy('id', 'DESC')->get();  

             $categories      =   Category::where('is_active',1)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();
        $sellers =    Store::where('is_active',1)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();


if($coupons && count($coupons) > 0) {
  $cpn_html = "";
                                          foreach($coupons as $row) {


                                            if($row['validity_type'] == "days") 
                          {
                            $days = $row['valid_days'];
                        $valid_till = date('Y-m-d', strtotime($row['created_at'] ."+$days days"));
                        $valid_from = date('Y-m-d', strtotime($row['created_at']));
                          }else {
                            $valid_from = $row['valid_from'];
                            $valid_till = $row['valid_to'];
                          }
                          if($startdate !="" && $enddate !="") {

                            // if((strtotime($startdate) <= strtotime($valid_from) || strtotime($startdate) <= strtotime($valid_till)) && (strtotime($enddate) <= strtotime($valid_till) || strtotime($enddate) >= strtotime($valid_from)) )

                               if((strtotime($startdate) <= strtotime($valid_from) || strtotime($startdate) <= strtotime($valid_till)) && (strtotime($enddate) >= strtotime($valid_from) ) ) {
                                $cpn_title = Coupon::getCpnContent($row["cpn_title_cid"]);
                            $cpn_html .=' <tr><td class="align-middle select-checkbox" id="moduleid" data-value="'.$row["id"].'">
                                    <label class="custom-control custom-checkbox">
                                      
                                      
                                    </label>
                                  </td>
                                  <td class="align-middle" >
                                    <div class="d-flex">
                                  
                                  <h6 class=" font-weight-bold">'.$cpn_title.'</h6>
                                        
                                      
                                    </div>
                                  </td>
                                  <td class="align-middle" >
                                    <div class="d-flex">
                                    <p>'.$row["ofr_code"].'</p>
                                  </div>
                                  </td>
                                  <td class="align-middle" >
                                    <div class="d-flex">
                                      <p>'.$row["ofr_value"].'</p>
                                    </div>
                                  </td>
                                  <td class="align-middle" >
                                    <div class="d-flex">
                                      <p>'. ucfirst($row["ofr_type"]) .'</p>
                                    </div>
                                  </td>
                                  <td class="text-nowrap align-middle">';

                        
                          if($row["is_active"] ==1){ $activestat = "Active"; $activesel = "checked"; }else{ $activestat ="Inactive"; $activesel = ""; }
          
                                   $cpn_html .='  <p>'. $valid_till .'</p>
                                  </td>
                                  
                                  <td class="text-nowrap align-middle"  data-search="'.$activestat.'">
                                    
                                  <div class="switch">
                                  <input class="switch-input status-btn ser_status" data-selid="'.$row["id"].'"  id="status-'.$row["id"].'"  type="checkbox" '.$activesel.' >
                                  <label class="switch-paddle" for="status-'.$row["id"].'">
                                  <span class="switch-active" aria-hidden="true">Active</span>
                                  <span class="switch-inactive" aria-hidden="true">Inactive</span>
                                  </label>
                                  </div>                    
                  
                                  </td>
                                  <td class="text-nowrap align-middle"><span>'.date("d M Y",strtotime($row["created_at"])).'</span></td>
                                  
                                  <td class="align-middle">
                                    <div class="btn-group align-top">
                                      
                                      <a href="'.url("admin/coupons/log/").'/'.$row["id"].'"   class="mr-2 btn btn-info btn-sm editmodule"><i class="fe fe-edit mr-1"></i> View</a>
                                      
                                    </div>
                                  </td>
                                  <td class="align-middle">
                                    <div class="btn-group align-top">
                                      
                                      <a href="'.url("admin/coupons/edit/") .'/'.$row["id"].'"   class="mr-2 btn btn-info btn-sm editmodule"><i class="fe fe-edit mr-1"></i> Edit</a>
                                      <button  class="btn btn-secondary btn-sm deletemodule" onclick="deletecpn('.$row["id"].');" type="button"><i class="fe fe-trash-2  mr-1"></i>Delete</button>
                                    </div>
                                  </td>
                                </tr>';

                               }
                          }else {

                            $cpn_title = Coupon::getCpnContent($row["cpn_title_cid"]);
                            $cpn_html .=' <tr>
                                  <td class="align-middle select-checkbox" id="moduleid" data-value="'.$row["id"].'">
                                    <label class="custom-control custom-checkbox">
                                      
                                      
                                    </label>
                                  </td>
                                  <td class="align-middle" >
                                    <div class="d-flex">
                                  
                                  <h6 class=" font-weight-bold">'.$cpn_title.'</h6>
                                        
                                      
                                    </div>
                                  </td>
                                  <td class="align-middle" >
                                    <div class="d-flex">
                                    <p>'.$row["ofr_code"].'</p>
                                  </div>
                                  </td>
                                  <td class="align-middle" >
                                    <div class="d-flex">
                                      <p>'.$row["ofr_value"].'</p>
                                    </div>
                                  </td>
                                  <td class="align-middle" >
                                    <div class="d-flex">
                                      <p>'. ucfirst($row["ofr_type"]) .'</p>
                                    </div>
                                  </td>
                                  <td class="text-nowrap align-middle">';

                          $valid_till = "";
                          if($row["validity_type"] == "days") 
                          {
                            $days = $row['valid_days'];
                        $valid_till = date('d-m-Y', strtotime($row['created_at'] ."+$days days"));
                          }
                          
                          else {
                            $valid_till = $row['valid_to'];
                          }
                          if($row["is_active"] ==1){ $activestat = "Active"; $activesel = "checked"; }else{ $activestat ="Inactive"; $activesel = ""; }
          
                                   $cpn_html .='  <p>'. $valid_till .'</p>
                                  </td>
                                  
                                  <td class="text-nowrap align-middle"  data-search="'.$activestat.'">
                                    
                                  <div class="switch">
                                  <input class="switch-input status-btn ser_status" data-selid="'.$row["id"].'"  id="status-'.$row["id"].'"  type="checkbox" '.$activesel.' >
                                  <label class="switch-paddle" for="status-'.$row["id"].'">
                                  <span class="switch-active" aria-hidden="true">Active</span>
                                  <span class="switch-inactive" aria-hidden="true">Inactive</span>
                                  </label>
                                  </div>                    
                  
                                  </td>
                                  <td class="text-nowrap align-middle"><span>'.date("d M Y",strtotime($row["created_at"])).'</span></td>
                                  
                                  <td class="align-middle">
                                    <div class="btn-group align-top">
                                      
                                      <a href="'.url("admin/coupons/log/").'/'.$row["id"].'"   class="mr-2 btn btn-info btn-sm editmodule"><i class="fe fe-edit mr-1"></i> View</a>
                                      
                                    </div>
                                  </td>
                                  <td class="align-middle">
                                    <div class="btn-group align-top">
                                      
                                      <a href="'.url("admin/coupons/edit/") .'/'.$row["id"].'"   class="mr-2 btn btn-info btn-sm editmodule"><i class="fe fe-edit mr-1"></i> Edit</a>
                                      <button  class="btn btn-secondary btn-sm deletemodule" onclick="deletecpn('.$row["id"].');" type="button"><i class="fe fe-trash-2  mr-1"></i>Delete</button>
                                    </div>
                                  </td>
                                </tr>';
                          }

                            
                             }
                           }else {
                            $cpn_html = "0";
                           }


        return $cpn_html;
        
        }
    

   
}
