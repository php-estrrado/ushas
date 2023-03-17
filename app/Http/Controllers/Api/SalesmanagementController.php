<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\SalesOrder;
use App\Models\SaleOrder;
use App\Models\SalesOrderItem;
use App\Models\SalesOrderAddress;
use App\Models\SalesOrderPayment;
use App\Models\SalesOrderStatus;
use App\Models\SalesOrderStatusList;
use App\Models\CustomerPoints;
use Illuminate\Support\Str;
use Validator;

class SalesmanagementController extends Controller
{
    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'org_id'=>['required','numeric'],
            'order_id'=>['required','numeric'],
            'parent_sale_id'=>['required','numeric'],
            'cust_id'=>['required','numeric'],
            'store_id'=>['required','numeric'],
            'branch_id'=>['required','numeric'],
            // 'delivery_date'=>['nullable','date_format:d-m-Y H:i:s'],
            'platform'=>['required','in:ecom'],
            'parent_id'=>['required','numeric'],
            'prd_id'=>['required','numeric'],
            'prd_type'=>['required','numeric'],
            'prd_name'=>['required','max:255'],
            'price'=>['required','numeric'],
            'addr_id'=>['required','numeric'],
            'name'=>['required','max:255'],
            'phone'=>['required','numeric'],
            'email'=>['required','max:255'],
            'address1'=>['required','max:255'],
            'zip_code'=>['required','numeric'],
            'city'=>['required','numeric'],
            'state'=>['required','numeric'],
            'country'=>['required','numeric'],
            's_addr_id'=>['required','numeric'],
            's_name'=>['required','max:255'],
            's_phone'=>['required','numeric'],
            's_email'=>['required','max:255'],
            's_address1'=>['required','max:255'],
            's_zip_code'=>['required','numeric'],
            's_city'=>['required','numeric'],
            's_state'=>['required','numeric'],
            's_country'=>['required','numeric'],
            'payment_method_id'=>['required','numeric'],
            'payment_type'=>['required','max:255'],
            'amount'=>['required','numeric'],
            'payment_status'=>['required','max:255']
        ]);

        if($validator->passes())
        {
            $saleorders_validate = SaleOrder::where('order_id',$request->order_id)->first();

            if($saleorders_validate)
            {
                return response()->json(['httpcode'=>400,'status'=>'error','error'=>'Saleorder Already Exists!']);
            }
            else
            {
                $sales['crm_id'] = $request->crm_id;
                $sales['org_id'] = $request->org_id;
                $sales['order_id'] = $request->order_id;
                $sales['parent_sale_id'] = $request->parent_sale_id;
                $sales['cust_id'] = $request->cust_id;
                $sales['store_id'] = $request->store_id;
                $sales['branch_id'] = $request->branch_id;
                $sales['total'] = $request->total;
                $sales['discount'] = $request->discount;
                $sales['coupon_discount'] = $request->coupon_discount;
                $sales['tax'] = $request->tax;
                $sales['shiping_charge'] = $request->shiping_charge;
                $sales['packing_charge'] = $request->packing_charge;
                $sales['payment_gateway_charge'] = $request->payment_gateway_charge;
                $sales['wallet_amount'] = $request->wallet_amount;
                $sales['g_total'] = $request->g_total;
                $sales['currency_amount'] = $request->currency_amount;
                $sales['ecom_commission'] = $request->ecom_commission;
                $sales['discount_type'] = $request->discount_type;
                $sales['coupon_id'] = $request->coupon_id;
                $sales['invite_coupon_id'] = $request->invite_coupon_id;
                $sales['delivery_date'] = date('Y-m-d H:i:s',strtotime($request->delivery_date));
                $sales['delivery_status'] = $request->delivery_status;
                $sales['order_status'] = $request->order_status;
                $sales['payment_status'] = $request->payment_status;
                $sales['shipping_status'] = $request->shipping_status;
                $sales['cancel_process'] = $request->cancel_process;
                $sales['cust_message'] = $request->cust_message;
                $sales['platform'] = $request->platform;
                $sales['created_at'] = date('Y-m-d H:i:s');
                $sales['updated_at'] = date('Y-m-d H:i:s');

                $sales_orders_id = SaleOrder::create($sales)->id;

                $sales_items['sales_id'] = $sales_orders_id;
                $sales_items['parent_id'] = $request->parent_id;
                $sales_items['prd_id'] = $request->prd_id;
                $sales_items['attr_ids'] = $request->attr_ids;
                $sales_items['prd_type'] = $request->prd_type;
                $sales_items['prd_name'] = $request->prd_name;
                $sales_items['price'] = $request->price;
                $sales_items['qty'] = $request->qty;
                $sales_items['total'] = $request->total;
                $sales_items['mjs_fee'] = $request->mjs_fee;
                $sales_items['pg_fee'] = $request->pg_fee;
                $sales_items['discount'] = $request->items_discount;
                $sales_items['tax'] = $request->items_tax;
                $sales_items['tax_seller'] = $request->tax_seller;
                $sales_items['row_total'] = $request->row_total;
                $sales_items['coupon_id'] = $request->coupon_id;
                $sales_items['is_deleted'] = 0;
                $sales_items['created_at'] = date('Y-m-d H:i:s');
                $sales_items['updated_at'] = date('Y-m-d H:i:s');

                $sales_items_id = SalesOrderItem::create($sales_items)->id;

                $sales_address['sales_id'] = $sales_orders_id;
                $sales_address['cust_id'] = $request->cust_id;
                $sales_address['addr_id'] = $request->addr_id;
                $sales_address['ref_addr_id'] = $request->ref_addr_id;
                $sales_address['name'] = $request->name;
                $sales_address['phone'] = $request->phone;
                $sales_address['country_code'] = $request->country_code;
                $sales_address['email'] = $request->email;
                $sales_address['address1'] = $request->address1;
                $sales_address['address2'] = $request->address2;
                $sales_address['zip_code'] = $request->zip_code;
                $sales_address['city'] = $request->city;
                $sales_address['state'] = $request->state;
                $sales_address['country'] = $request->country;
                $sales_address['latitude'] = $request->latitude;
                $sales_address['longitude'] = $request->longitude;
                $sales_address['s_addr_id'] = $request->s_addr_id;
                $sales_address['s_name'] = $request->s_name;
                $sales_address['s_phone'] = $request->s_phone;
                $sales_address['s_country_code'] = $request->s_country_code;
                $sales_address['s_email'] = $request->s_email;
                $sales_address['s_address1'] = $request->s_address1;
                $sales_address['s_address2'] = $request->s_address2;
                $sales_address['s_zip_code'] = $request->s_zip_code;
                $sales_address['s_city'] = $request->s_city;
                $sales_address['s_state'] = $request->s_state;
                $sales_address['s_country'] = $request->s_country;
                $sales_address['s_latitude'] = $request->s_latitude;
                $sales_address['s_longitude'] = $request->s_longitude;
                $sales_address['created_at'] = date('Y-m-d H:i:s');
                $sales_address['updated_at'] = date('Y-m-d H:i:s');
                
                $sales_address_id = SalesOrderAddress::create($sales_address)->id;

                $sales_payment['sales_id'] = $sales_orders_id;
                $sales_payment['payment_method_id'] = $request->payment_method_id;
                $sales_payment['payment_type'] = $request->payment_type;
                $sales_payment['transaction_id'] = $request->transaction_id;
                $sales_payment['payment_data'] = $request->payment_data;
                $sales_payment['amount'] = $request->amount;
                $sales_payment['payment_status'] = $request->payment_status;
                $sales_payment['created_at'] = date('Y-m-d H:i:s');
                $sales_payment['updated_at'] = date('Y-m-d H:i:s');

                $sales_payment_id = SalesOrderPayment::create($sales_payment)->id;

                $sales_status['org_id'] = $request->org_id;
                $sales_status['sale_id'] = $sales_orders_id;
                $sales_status['status'] = $request->sales_order_status;
                $sales_status['created_by'] = $request->created_by;
                $sales_status['updated_by'] = $request->created_by;
                $sales_status['created_at'] = date('Y-m-d H:i:s');
                $sales_status['updated_at'] = date('Y-m-d H:i:s');

                $sales_status_id = SalesOrderStatus::create($sales_status)->id;

                // $points['user_id'] = $request->cust_id;
                // $points['sales_id'] = $sales_orders_id;
                // $points['credit'] = 0;
                // $points['debit'] = $request->customer_points;
                // $points['is_deleted'] = 0;
                // $points['created_at'] = date('Y-m-d H:i:s');
                // $points['updated_at'] = date('Y-m-d H:i:s');

                // $customer_points_id = CustomerPoints::create($points)->id;

                return response()->json(['httpcode'=>200,'success'=>'Successfully Added!','primary_key'=>$sales_orders_id]);
            }
        }
        else
        {
            return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sales_id'=>['required','numeric'],
            'org_id'=>['required','numeric'],
            'order_id'=>['required','numeric'],
            'parent_sale_id'=>['required','numeric'],
            'cust_id'=>['required','numeric'],
            'store_id'=>['required','numeric'],
            'branch_id'=>['required','numeric'],
            // 'delivery_date'=>['nullable','date_format:d-m-Y H:i:s'],
            'platform'=>['required','in:ecom'],
            'parent_id'=>['required','numeric'],
            'prd_id'=>['required','numeric'],
            'prd_type'=>['required','numeric'],
            'prd_name'=>['required','max:255'],
            'price'=>['required','numeric'],
            'addr_id'=>['required','numeric'],
            'name'=>['required','max:255'],
            'phone'=>['required','numeric'],
            'email'=>['required','max:255'],
            'address1'=>['required','max:255'],
            'zip_code'=>['required','numeric'],
            'city'=>['required','numeric'],
            'state'=>['required','numeric'],
            'country'=>['required','numeric'],
            's_addr_id'=>['required','numeric'],
            's_name'=>['required','max:255'],
            's_phone'=>['required','numeric'],
            's_email'=>['required','max:255'],
            's_address1'=>['required','max:255'],
            's_zip_code'=>['required','numeric'],
            's_city'=>['required','numeric'],
            's_state'=>['required','numeric'],
            's_country'=>['required','numeric'],
            'payment_method_id'=>['required','numeric'],
            'payment_type'=>['required','max:255'],
            'amount'=>['required','numeric'],
            'payment_status'=>['required','max:255']
        ]);

        if($validator->passes())
        {
            $sales_orders_id = $request->sales_id;
            $saleorders_validate = SaleOrder::where('order_id',$request->order_id)->where('id','!=',$sales_orders_id)->first();

            if($saleorders_validate)
            {
                return response()->json(['httpcode'=>400,'status'=>'error','error'=>'Salesorder Already Exists!']);
            }
            else
            {

                $saleorders_check = SaleOrder::where('id',$sales_orders_id)->first();

                if($saleorders_check)
                {
                    $sales['crm_id'] = $request->crm_id;
                    $sales['org_id'] = $request->org_id;
                    $sales['order_id'] = $request->order_id;
                    $sales['parent_sale_id'] = $request->parent_sale_id;
                    $sales['cust_id'] = $request->cust_id;
                    $sales['store_id'] = $request->store_id;
                    $sales['branch_id'] = $request->branch_id;
                    $sales['total'] = $request->total;
                    $sales['discount'] = $request->discount;
                    $sales['coupon_discount'] = $request->coupon_discount;
                    $sales['tax'] = $request->tax;
                    $sales['shiping_charge'] = $request->shiping_charge;
                    $sales['packing_charge'] = $request->packing_charge;
                    $sales['payment_gateway_charge'] = $request->payment_gateway_charge;
                    $sales['wallet_amount'] = $request->wallet_amount;
                    $sales['g_total'] = $request->g_total;
                    $sales['currency_amount'] = $request->currency_amount;
                    $sales['ecom_commission'] = $request->ecom_commission;
                    $sales['discount_type'] = $request->discount_type;
                    $sales['coupon_id'] = $request->coupon_id;
                    $sales['invite_coupon_id'] = $request->invite_coupon_id;
                    $sales['delivery_date'] = date('Y-m-d H:i:s',strtotime($request->delivery_date));
                    $sales['delivery_status'] = $request->delivery_status;
                    $sales['order_status'] = $request->order_status;
                    $sales['payment_status'] = $request->payment_status;
                    $sales['shipping_status'] = $request->shipping_status;
                    $sales['cancel_process'] = $request->cancel_process;
                    $sales['cust_message'] = $request->cust_message;
                    $sales['platform'] = $request->platform;
                    $sales['created_at'] = date('Y-m-d H:i:s');
                    $sales['updated_at'] = date('Y-m-d H:i:s');

                    SaleOrder::where('id',$sales_orders_id)->update($sales);

                    $sales_items['sales_id'] = $sales_orders_id;
                    $sales_items['parent_id'] = $request->parent_id;
                    $sales_items['prd_id'] = $request->prd_id;
                    $sales_items['attr_ids'] = $request->attr_ids;
                    $sales_items['prd_type'] = $request->prd_type;
                    $sales_items['prd_name'] = $request->prd_name;
                    $sales_items['price'] = $request->price;
                    $sales_items['qty'] = $request->qty;
                    $sales_items['total'] = $request->total;
                    $sales_items['mjs_fee'] = $request->mjs_fee;
                    $sales_items['pg_fee'] = $request->pg_fee;
                    $sales_items['discount'] = $request->items_discount;
                    $sales_items['tax'] = $request->items_tax;
                    $sales_items['tax_seller'] = $request->tax_seller;
                    $sales_items['row_total'] = $request->row_total;
                    $sales_items['coupon_id'] = $request->coupon_id;
                    $sales_items['is_deleted'] = 0;
                    $sales_items['created_at'] = date('Y-m-d H:i:s');
                    $sales_items['updated_at'] = date('Y-m-d H:i:s');

                    SalesOrderItem::where('sales_id',$sales_orders_id)->update($sales_items);

                    $sales_address['sales_id'] = $sales_orders_id;
                    $sales_address['cust_id'] = $request->cust_id;
                    $sales_address['addr_id'] = $request->addr_id;
                    $sales_address['ref_addr_id'] = $request->ref_addr_id;
                    $sales_address['name'] = $request->name;
                    $sales_address['phone'] = $request->phone;
                    $sales_address['country_code'] = $request->country_code;
                    $sales_address['email'] = $request->email;
                    $sales_address['address1'] = $request->address1;
                    $sales_address['address2'] = $request->address2;
                    $sales_address['zip_code'] = $request->zip_code;
                    $sales_address['city'] = $request->city;
                    $sales_address['state'] = $request->state;
                    $sales_address['country'] = $request->country;
                    $sales_address['latitude'] = $request->latitude;
                    $sales_address['longitude'] = $request->longitude;
                    $sales_address['s_addr_id'] = $request->s_addr_id;
                    $sales_address['s_name'] = $request->s_name;
                    $sales_address['s_phone'] = $request->s_phone;
                    $sales_address['s_country_code'] = $request->s_country_code;
                    $sales_address['s_email'] = $request->s_email;
                    $sales_address['s_address1'] = $request->s_address1;
                    $sales_address['s_address2'] = $request->s_address2;
                    $sales_address['s_zip_code'] = $request->s_zip_code;
                    $sales_address['s_city'] = $request->s_city;
                    $sales_address['s_state'] = $request->s_state;
                    $sales_address['s_country'] = $request->s_country;
                    $sales_address['s_latitude'] = $request->s_latitude;
                    $sales_address['s_longitude'] = $request->s_longitude;
                    $sales_address['created_at'] = date('Y-m-d H:i:s');
                    $sales_address['updated_at'] = date('Y-m-d H:i:s');
                    
                    SalesOrderAddress::where('sales_id',$sales_orders_id)->update($sales_address);

                    $sales_payment['sales_id'] = $sales_orders_id;
                    $sales_payment['payment_method_id'] = $request->payment_method_id;
                    $sales_payment['payment_type'] = $request->payment_type;
                    $sales_payment['transaction_id'] = $request->transaction_id;
                    $sales_payment['payment_data'] = $request->payment_data;
                    $sales_payment['amount'] = $request->amount;
                    $sales_payment['payment_status'] = $request->payment_status;
                    $sales_payment['created_at'] = date('Y-m-d H:i:s');
                    $sales_payment['updated_at'] = date('Y-m-d H:i:s');

                    SalesOrderPayment::where('sales_id',$sales_orders_id)->update($sales_payment);

                    $sales_status['org_id'] = $request->org_id;
                    $sales_status['sale_id'] = $sales_orders_id;
                    $sales_status['status'] = $request->sales_order_status;
                    $sales_status['created_by'] = $request->created_by;
                    $sales_status['updated_by'] = $request->created_by;
                    $sales_status['created_at'] = date('Y-m-d H:i:s');
                    $sales_status['updated_at'] = date('Y-m-d H:i:s');

                    SalesOrderStatus::where('sale_id',$sales_orders_id)->update($sales_status);

                    // $points['user_id'] = $request->cust_id;
                    // $points['sales_id'] = $sales_orders_id;
                    // $points['credit'] = 0;
                    // $points['debit'] = $request->customer_points;
                    // $points['is_deleted'] = 0;
                    // $points['created_at'] = date('Y-m-d H:i:s');
                    // $points['updated_at'] = date('Y-m-d H:i:s');

                    // CustomerPoints::where('sales_id',$sales_orders_id)->update($points);

                    return response()->json(['httpcode'=>200,'success'=>'Successfully Updated!','primary_key'=>$sales_orders_id]);
                }
                else
                {
                    return response()->json(['httpcode'=>400,'status'=>'error','error'=>'Salesorder Doesnot Exists!']);
                }
            }
        }
        else
        {
            return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
        }
    }

    public function orderstatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sales_id'=>['required','numeric'],
            'order_status'=>['required','max:255']
        ]);

        if($validator->passes())
        {
            $sales_id = $request->sales_id;
            $order_status = $request->order_status;
            
            $sales_validate = SaleOrder::where('id',$sales_id)->first();

            if($sales_validate)
            {
                SaleOrder::where('id',$sales_id)->update(['order_status'=>$order_status]);
                $order_status_id = SalesOrderStatusList::where('identifier',$order_status)->first();
                SalesOrderStatus::where('sale_id',$sales_id)->update(['status'=>$order_status_id->id]);

                return response()->json(['httpcode'=>200,'success'=>'Successfully Updated!','primary_key'=>$sales_id]);
            }
            else
            {
                return response()->json(['httpcode'=>400,'status'=>'error','error'=>'Salesorder Doesnot Exists!']);
            }
        }
        else
        {
            return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
        }
    }
}