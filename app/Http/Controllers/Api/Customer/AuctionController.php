<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;
use Carbon\Carbon;
use App\Rules\Name;
use Validator;

use App\Models\UserVisit;
use App\Models\Auction;
use App\Models\AuctionHist;
use App\Models\TaxValue;
use App\Models\Product;
use App\Models\CmsContent;
use App\Models\ProductImage;
use App\Models\SaleOrder;
use App\Models\SalesOrderPayment;
use App\Models\SaleorderItems;
use App\Models\SalesOrderAddress;
use App\Models\CustomerAddress;
use App\Models\SettingOther;
use App\Models\CustomerWallet_Model;
use App\Models\PaymentMethod;
use App\Models\ParentSale;
use App\Models\AuctionRefundHist;

use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

class AuctionController extends Controller
{
    public function auction_detail(Request $request)
    {
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
            $data       =   [];
            $formData   =   $request->all(); 
            $rules      =   array();
            $rules['auction_id'] = 'required|numeric';
            $rules['lang_id']    = 'required|numeric';
            $rules['device_id']  = 'required|string';
            $rules['os_type']    = 'required|string';
            $rules['page_url']   = 'required|url';
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 
                     $usr_visit = UserVisit::create([
                    'org_id' =>1,
                    'device_id'=>$request->device_id,
                    'is_login'=>1,
                    'os'=>$request->os_type,
                    'url'=>$request->page_url,
                    'visited_on'=>date("Y-m-d H:i:s"),
                    'created_at'=>date("Y-m-d H:i:s"),
                    'updated_at'=>date("Y-m-d H:i:s")]);

                    $lang =  $request->lang_id;
                    $auction =  Auction::where('id',$request->auction_id)->where('is_deleted',0)->where('is_active',1)->first();
                    $history =  AuctionHist::where('auction_id',$request->auction_id)->where('user_id',$user_id)
                        ->where('is_deleted',0)->where('is_active',1)->orderBy('created_at', 'desc')->first();
                    if($auction)
                    {   
                         $prdId                     =   $auction->product_id;
                         $today = date('Y-m-d');
                         $bid_charge = SettingOther::first();
                         $products = Product::where('id',$prdId)->first();
                         $tax = TaxValue::where('tax_id',$products->tax_id)
                         ->where('is_deleted',0)->where('is_active',1)
                         ->whereDate('valid_from', '<=', date("Y-m-d"))
                         ->whereDate('valid_to', '>=', date("Y-m-d"));
                         $wallet =  CustomerWallet_Model::where('user_id',$user_id);
                         $credit = $wallet->sum('credit');
                         $debit = $wallet->sum('debit');
                         $wtotal = $credit - $debit;
                         $wtotal = number_format($wtotal,2);
                         $data['id']                =   $auction->id;
                         $data['product_id']        =   $prdId;
                         $data['product_name']      =   $this->get_content($products->name_cnt_id,$lang);
                         $data['product_image']     =   $this->get_product_image($products->id);
                         $data['currency']          =   getCurrency()->name;
                         $data['Bidding_amount']    =   $history->bid_price;
                         $data['Bidding_commission']=   $bid_charge->bid_charge;
                         $data['wallet_total_amount']     =   $wtotal;
                         if($tax->count() > 0)
                         {
                            $del_tax     = $tax->first();
                            $data['tax']  = ($del_tax->percentage / 100) * $history->bid_price;
                         }
                         else
                         {
                            $data['tax']  = 0;
                         }
                         $data['delivery_charge']   =   0;
                         $data['package_charge']    =   0;
                         // $data['total']             =   $data['Bidding_amount'] + $data['Bidding_commission'] + $data['tax']  + $data['delivery_charge'] + $data['package_charge'];
                    }
                   
                    return array('httpcode'=>'200','status'=>'success','message'=>'Auction Detail','data'=>['auction'=>$data]);
                }
        }else{ return invalidToken(); }
    }
    function get_content($field_id,$lang)
    { 
        if($lang=='')
        { 
        $language =DB::table('glo_lang_lk')->where('is_active', 1)->first();
        $language_id=$language->id;
        }
        else
        {
            $language_id=$lang;
        }
        $content_table  =   CmsContent::where('cnt_id', $field_id)->where('lang_id', $language_id)->first();
        if(!empty($content_table))
        { 
            $return_cont = $content_table->content;   
        }
        else
        {
            $return_cont = ''; 
        }
        return $return_cont;
    }
    function get_product_image($prd_id)
    {
        $data     =   [];
        $product       =   ProductImage::where('prd_id',$prd_id)->where('is_deleted',0)->get(); 
        if($product->count() > 0)   
            {   
                foreach($product as $k=>$row)
                { 
                    $val['image']       =   config('app.storage_url').$row->image;
                    $val['thumbnail']   =   config('app.storage_url').$row->thumb;
                    $data[]             =   $val;
                } 
            }
        else
            { 
                $val['image']       =   config('app.storage_url').'/app/public/no-image.png';
                $val['thumbnail']   =   config('app.storage_url').'/app/public/no-image-thumbnail.jpg';
                $data[]     =   $val; 
            } 
        return $data;
    }
    public function auction_checkout(Request $request)
    {
    if($user = validateToken($request->post('access_token')))
        {
        $user_id    =   $user['user_id'];
        $user_email = $user['email'];
        $data       =   [];
        $formData   =   $request->all(); 
        $rules      =   array();
        $rules['auction_id'] = 'required|numeric';
        $rules['address_id'] = 'required|numeric';
        $rules['payment_type'] = 'required|numeric';
        $rules['wallet_amount'] = 'required|numeric';
        $rules['grand_total'] = 'required|numeric';
        $rules['device_id']  = 'required|string';
        $rules['os_type']    = 'required|string';
        $rules['page_url']   = 'required|url';
        $validator  =   Validator::make($request->all(), $rules);
        if ($validator->fails()) 
            {
                foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
            }
        else
            {  
                $auction =  Auction::where('id',$request->auction_id)->where('is_deleted',0)->where('is_active',1)->first();
                if($auction)
                {
                    $usr_visit = UserVisit::create([
                    'org_id' =>1,
                    'device_id'=>$request->device_id,
                    'is_login'=>1,
                    'os'=>$request->os_type,
                    'url'=>$request->page_url,
                    'visited_on'=>date("Y-m-d H:i:s"),
                    'created_at'=>date("Y-m-d H:i:s"),
                    'updated_at'=>date("Y-m-d H:i:s")]);

                    $latestOrder = SaleOrder::orderBy('created_at','DESC')->first();
                    $saleorder_id = date('y').date('m').str_pad($latestOrder->id + 1, 6, "0", STR_PAD_LEFT);
                    
                    $history =  AuctionHist::where('auction_id',$request->auction_id)->where('user_id',$user_id)
                        ->orderBy('created_at', 'desc')->where('is_deleted',0)->where('is_active',1)->first();
                    $addr_list =  CustomerAddress::where('id',$formData['address_id'])->first();
                    $prdId                     =   $auction->product_id;
                    $products = Product::where('id',$prdId)->first();
                    $tax = TaxValue::where('tax_id',$products->tax_id)
                    ->where('is_deleted',0)->where('is_active',1)
                    ->whereDate('valid_from', '<=', date("Y-m-d"))
                    ->whereDate('valid_to', '>=', date("Y-m-d"));   
                    if($tax->count() > 0)
                     {
                        $del_tax     = $tax->first();
                        $tot_tax  = ($del_tax->percentage / 100) * $history->bid_price;
                     }
                     else
                     {
                        $tot_tax  = 0;
                     }
                    $bid_charge = SettingOther::first();
                    $bidComm = $bid_charge->bid_charge;
                    if($products->commi_type == 'amount')
                    {
                        $ecomm = $products->commission;
                    }
                    else
                    {
                        $ecomm = ($products->commission / 100) * $request->grand_total;
                    }
                    $pay_method = PaymentMethod::where('id',$request->payment_type)->where('is_deleted',0)->where('is_active',1)->first();
                    // $grand_tot = $history->bid_price + $tot_tax + $bidComm;
                    $insert_sale_parent = ParentSale::create(['org_id'            => 1,
                      'user_id'           => $user_id,
                      'tot_amount'        => $history->bid_price,
                      'platform_coupon_id'=> 0,
                      'discount_type'     => 0,
                      'discount_amt'      => 0,
                      'wallet_amt'        => $request->wallet_amount,
                      'grand_total'       => $request->grand_total,   
                      'created_at'        => date("Y-m-d H:i:s"),
                      'updated_at'        => date("Y-m-d H:i:s")
                      ]);
                     $parent_sale_id  = $insert_sale_parent->id;        

                    $create_saleorder = SaleOrder::create(['org_id' => 1,
                    'order_id'        => $saleorder_id,
                    'cust_id'         => $user_id,
                    'seller_id'       => $auction->seller_id,
                    'total'           => $history->bid_price,
                    'discount'        => 0,
                    'tax'             => $tot_tax,
                    'shiping_charge'  => 0,
                    'packing_charge'  => 0,
                    'wallet_amount'   => $request->wallet_amount,
                    'bid_charge'      => $bidComm,
                    'g_total'         => $request->grand_total,
                    'ecom_commission' => $ecomm,
                    'discount_type'   => '',  
                    'coupon_id'       => 0,
                    'order_status'    => 'pending',
                    'payment_status'  => 'pending',
                    'shipping_status' => 'pending',
                    'cancel_process'  => 0,
                    'created_at'    =>date("Y-m-d H:i:s"),
                    'updated_at'    =>date("Y-m-d H:i:s")]);
                    $sale_id  = $create_saleorder->id;

                    AuctionHist::where('auction_id',$request->auction_id)->where('user_id',$user_id)
                        ->where('is_deleted',0)->where('is_active',1)->orderBy('created_at', 'desc')->first()->update(['sale_id'=>$sale_id]);

                    $saleorder_payment = SalesOrderPayment::create(['org_id' => 1,
                    'sales_id'         => $sale_id,
                    'payment_method_id'=> $formData['payment_type'],
                    'payment_type'     => $pay_method->title,
                    'transaction_id'   => '',
                    'payment_data'     => '',
                    'amount'           => $request->grand_total,
                    'payment_status'  => 'pending']);
                     
                    $create_saleitem = SaleorderItems::create([
                    'sales_id'        => $sale_id,
                    'parent_id'       => $parent_sale_id,
                    'prd_id'          => $prdId,
                    'prd_type'        => $products->product_type,
                    'prd_name'        => $products->name,
                    'price'           => $history->bid_price,
                    'qty'             => 1,
                    'total'           => $history->bid_price,
                    'discount'        => 0,
                    'tax'             => $tot_tax,
                    'row_total'       => $request->grand_total,
                    'coupon_id'       => '', 
                    'created_at'    =>date("Y-m-d H:i:s"),
                    'updated_at'    =>date("Y-m-d H:i:s"),
                    'is_deleted'    =>0]);   

                    $insert_address = SalesOrderAddress::create(['sales_id' => $sale_id,
                    'cust_id'         => $user_id,
                    'ref_addr_id'     => $formData['address_id'],
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

                    if($request->wallet_amount > 0)
                    {
                        CustomerWallet_Model::create(['user_id'=>$user_id,'source_id' => $sale_id,'source'=>'auction order','credit'=>0,'debit'=>$request->wallet_amount,'desc'=>'Auction purchase','is_active'=>1]);
                    }

                     return array('httpcode'=>'200','status'=>'success','message'=>'Order placed','data'=>['order_id'=>$saleorder_id]);
                }
                   
                else
                {
                    return array('httpcode'=>'400','status'=>'error','message'=>'Not Found','data'=>['message' =>'Auction not found!']);
                }
                
             }
        }
        else
        { 
            return invalidToken(); 
        }
    }

    public function auction_order_list(Request $request)
    {
        if($user = validateToken($request->post('access_token')))
        {
            $user_id    =   $user['user_id'];
            $data       =   $val = [];
            $formData   =   $request->all(); 
            $rules      =   array();
            $rules['lang_id']    = 'required|numeric';
            $rules['device_id']  = 'required|string';
            $rules['os_type']    = 'required|string';
            $rules['page_url']   = 'required|url';
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 
                     $usr_visit = UserVisit::create([
                    'org_id' =>1,
                    'device_id'=>$request->device_id,
                    'is_login'=>1,
                    'os'=>$request->os_type,
                    'url'=>$request->page_url,
                    'visited_on'=>date("Y-m-d H:i:s"),
                    'created_at'=>date("Y-m-d H:i:s"),
                    'updated_at'=>date("Y-m-d H:i:s")]);

                    $lang =  $request->lang_id;
                    
                    $history =  AuctionHist::where('user_id',$user_id)->where('sale_id','>',0)->where('is_deleted',0)->where('is_active',1)->orderBy('created_at', 'desc')->get();
                    if($history)
                    {
                        foreach($history as $hist)
                        {
                            $auction =  Auction::where('id',$hist->auction_id)->where('is_deleted',0)->where('is_active',1)->first();
                            $prdId                   =   $auction->product_id;
                            $products = Product::where('id',$prdId)->first();
                            $data['id']              =   $hist->id;
                            $data['sale_id']         =   $hist->sale_id;
                            $data['auction_id']      =   $auction->id;
                            $data['product_id']      =   $auction->product_id;
                            $data['product_name']    =   $this->get_content($products->name_cnt_id,$lang);
                           if($auction->sellerInfo){ $data['seller_name']     =   $auction->sellerInfo->fname; } else { $data['seller_name']     =""; }
                            $data['start_date']      =   date('d/m/Y',strtotime($auction->auct_start));;
                            $data['end_date']        =   date('d/m/Y',strtotime($auction->auct_end));;
                            $data['bid_price']       =   $hist->bid_price;
                            $data['currency']        =   getCurrency()->name;
                            if($auction->status == 'closed')
                            {
                                $data['status']          =   'closed';  
                            }
                            else
                            {
                                $data['status']          =   'Auction Inprogress'; 
                            }
                            if($auction->status == 'closed' && $auction->bid_allocated_to == $user_id && $auction->sale_id == $hist->sale_id)  
                            {
                                $data['winner_status']   =   1; 
                            }
                            else
                            {
                                $data['winner_status']   =   0; 
                                if($auction->status == 'closed')
                                {
                                    
                                    $ref_status = AuctionRefundHist::where('auction_id',$hist->auction_id)->where('user_id',$user_id);
                                    if($ref_status->count() > 0)
                                    {
                                        $sts       =   $ref_status->first()->status;
                                        if($sts == 'completed')
                                        {
                                            $refsts = 'Refunded';
                                        }
                                        else
                                        {
                                            $refsts = 'Refunnd Initiated';
                                        }

                                    }
                                    else
                                    {
                                        $refsts = 'Refunnd Initiated';
                                    }
                                    $data['status']          =   $refsts;
                                }
                            }
                                          
                            $val[] = $data;
                        }
                    }

                    else
                    {
                         $val        =   []; 
                    }
                return array('httpcode'=>'200','status'=>'success','message'=>'Auction Orders','data'=>['order_list'=>$val]);
                }
        }
        else
        { 
            return invalidToken(); }

    }
}