<?php

namespace App\Http\Controllers\Api\CRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\PasswordReset;
use App\Models\Email;
use App\Models\CustomerMaster;
use App\Models\CustomerInfo;
use App\Models\CustomerSecurity;
use App\Models\CustomerTelecom;
use App\Models\CustomerAddress;

use App\Models\SalesOrder;
use Validator;

class CrmCustomerSales extends Controller
{
    public function customer_sales(Request $request)
    {
        $data       =   $request->all(); 
        $post       =   (object)$request->post();
        $rules      =   array();
        $rules['id']=   'required';
        $validator  =   Validator::make($request->all(), $rules);
        if ($validator->fails()) 
            {
                foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
            }
        else
            { 
                $userIn = CustomerMaster::where('crm_unique_id',$post->id)->where('usr_platform','crm')->first();
                if($userIn)
                {
                    $saledata = [];
                    $sales = SalesOrder::where('cust_id',$userIn->id)->get();
                    foreach($sales as $row)
                    {
                        $list['sale_primary_id']  = $row->id;
                        $list['order_id']         = $row->order_id;
                        $list['customer_name']    = $row->customer->info->first_name;
                        $list['total']            = $row->total;
                        $list['tax']              = $row->tax;
                        $list['discount']         = $row->total;
                        $list['grand_total']      = $row->g_total;
                        $list['delivery_status']  = $row->delivery_status;
                        $list['order_status']     = $row->order_status;
                        $list['payment_status']   = $row->payment_status;
                        $list['order_date']       = $row->created_at;
                        $saledata[] = $list; 
                    }

                    return ['httpcode'=>200,'status'=>'success','message'=>'Sale orders','data'=>$saledata];
                }
                else
                {
                    return ['httpcode'=>404,'status'=>'error','message'=>'No user found'];
                }
             }
    }

    //all sale crm
    public function customer_all_sales_crm(Request $request)
    {
        $data       =   $request->all(); 
        $post       =   (object)$request->post();
        $rules      =   array();
        
                $userIn = CustomerMaster::whereNotNull('crm_unique_id')->where('usr_platform','crm')->pluck('id');
                if(count($userIn)>0)
                {
                    $saledata = [];
                    $sales = SalesOrder::whereIn('cust_id',$userIn)->get();
                    foreach($sales as $row)
                    {
                        $list['sale_primary_id']  = $row->id;
                        $list['order_id']         = $row->order_id;
                        $list['customer_name']    = $row->customer->info->first_name;
                        $list['crm_unique_id']    = $row->customer->crm_unique_id;
                        $list['total']            = $row->total;
                        $list['tax']              = $row->tax;
                        $list['discount']         = $row->total;
                        $list['grand_total']      = $row->g_total;
                        $list['delivery_status']  = $row->delivery_status;
                        $list['order_status']     = $row->order_status;
                        $list['payment_status']   = $row->payment_status;
                        $list['order_date']       = $row->created_at;
                        $saledata[] = $list; 
                    }

                    return ['httpcode'=>200,'status'=>'success','message'=>'Sale orders','data'=>$saledata];
                }
                else
                {
                    return ['httpcode'=>404,'status'=>'error','message'=>'No data found'];
                }
             
    }
    
    //all sale
    public function customer_all_sales(Request $request)
    {
        $data       =   $request->all(); 
        $post       =   (object)$request->post();
        $rules      =   array();
        $rules['year']    = 'nullable|digits:4|numeric';
        $rules['month']   = 'nullable|digits:2|numeric|in:01,02,03,04,05,06,07,08,09,10,11,12';
        $validator  =   Validator::make($request->all(), $rules);
        if ($validator->fails()) 
            {
                foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
            }
            else
            {
                
                $userIn = CustomerMaster::where('is_deleted',0)->pluck('id');
                if(count($userIn)>0)
                {
                    $saledata = [];
                    $g_tot =0;
                    $sales = SalesOrder::whereIn('cust_id',$userIn);
                    if($request->month)
                    {
                        $sales = $sales->whereMonth('created_at',$request->month);
                    }
                    if($request->year)
                    {
                        $sales = $sales->whereYear('created_at',$request->year);
                    }
                    if($request->search)
                    {   $search = $request->search;
                        $sales = $sales->whereIn('cust_id',function($query) use ($search){
                            $query->select('user_id')->from('usr_info')->where('first_name', 'like', "%{$search}%");
                        });
                    }
                    $sales = $sales->orderBy('id','DESC')->get();
                    foreach($sales as $row)
                    {
                        if($row->address && $row->address->city>0)
                        {$city= $row->address->bcity->city_name;}else{$city = false;}
                        if($row->customer->info->profile_image)
                           {
                             $imagec=config('app.storage_url').'/app/public/customer_profile/'.$row->customer->info->profile_image;
                            //$imagec=url('/uploads/storage/app/public/customer_profile/'.$customer->profile_image);
                           } 
                           else
                           {
                            $imagec=url('/public/admin/assets/images/users/2.jpg');
                           } 
                        $list['sale_primary_id']  = $row->id;
                        $list['order_id']         = $row->order_id;
                        $list['customer_name']    = $row->customer->info->first_name;
                        $list['customer_code']    = $row->customer->customer_code;
                        $list['image']            = $imagec;
                        $list['city']             = $city;
                        $list['usr_from']         = $row->customer->usr_platform;
                        $list['crm_unique_id']    = $row->customer->crm_unique_id;
                        $list['total']            = $row->total;
                        $list['tax']              = round($row->tax,2);
                        $list['discount']         = $row->total;
                        $list['grand_total']      = round($row->g_total,2);
                        $list['delivery_status']  = $row->delivery_status;
                        $list['order_status']     = $row->order_status;
                        $list['payment_status']   = $row->payment_status;
                        $list['order_date']       = $row->created_at;
                        $g_tot+=$row->g_total;
                        $saledata[] = $list; 
                    }

                    return ['httpcode'=>200,'status'=>'success','message'=>'Sale orders','data'=>$saledata,'total'=>round($g_tot,2)];
                }
                else
                {
                    return ['httpcode'=>404,'status'=>'error','message'=>'No data found'];
                }
             }
             
    }
}
