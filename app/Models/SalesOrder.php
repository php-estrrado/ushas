<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    use HasFactory;
    protected $fillable     =   [
                                    'org_id','parent_sale_id','order_id','cust_id','seller_id','total','discount','tax','packing_charge','payment_gateway_charge','wallet_amount','bid_charge','g_total','currency_amount',
                                    'discount_type','coupon_id','invite_coupon_id','order_status','branch_id'
                                ];
    public function seller(){ return $this->belongsTo(Seller ::class, 'seller_id'); }
   public function store($sellerId){ return Store::where('seller_id',$sellerId)->first(); }
    public function customer(){ return $this->belongsTo(Customer::class, 'cust_id'); }
    public function address(){ return $this->hasOne(SalesOrderAddress ::class, 'sales_id'); }
    public function payment(){ return $this->hasOne(SalesOrderPayment ::class, 'sales_id'); }
    public function shipping(){ return $this->hasOne(SalesOrderShipping ::class, 'sales_id'); }
    public function calcel(){ return $this->hasOne(SalesOrderCancel ::class, 'sales_id')->latest(); }
    public function payments(){ return $this->hasMany(SalesOrderPayment ::class, 'sales_id'); }
    public function items(){ return $this->hasMany(SalesOrderItem ::class, 'sales_id'); }
        
    public function totEarnings($sellerId){ return SalesOrder::where('seller_id',$sellerId)->where('payment_status','success'); }
    public function paidSettlement($sellerId){ return Settlement::where('seller_id',$sellerId)->where('is_deleted',0)->sum('amount'); }
    public function paymentstripe($orderid){ $pay= StripePayments::where('sale_id',$orderid)->first(); if($pay){ $id = json_decode($pay->response,TRUE); return $id['id'];} else { return "NILL";}}
    public function telecom($user_id){ $usr= CustomerTelecom::where('user_id',$user_id)->where('usr_telecom_typ_id',2)->first(); if($usr){  return $usr;} else { return "";}}
    
    public function totalEarnings($sellerId,$sale_id){ 
        
        $list =  SalesOrder::where('seller_id',$sellerId)->where('id',$sale_id)->where('order_status','delivered')->where('payment_status','paid')->get();
        if($list)
            {
                 $commission = 0; $earnings = 0; $mjs_tax=0;
                $data          =   [];  
                foreach($list  as $row)
                {
                    
                    $products = SalesOrderItem::where('is_deleted',0)->where('sales_id',$row->id)->get();
                    foreach($products as $prd)
                    {
                        $prds[] = $prd->prd_name;
                        $earnings += ($prd->price * $prd->qty ) + $prd->tax_seller;
                        $mjs_tax += ($prd->tax - $prd->tax_seller);
                        $commission += ($prd->mjs_fee + $prd->pg_fee);
                    }
                    // $earnings += $row->shiping_charge;
                    //  $earnings = $earnings- $row->discount;
                }
                $data['earnings']          =   round($earnings);
                $data['commission']          =   round($commission);
                $data['mjs_tax']          =   round($mjs_tax);
                
            }
            return $data;
    }
    
     static function totalSellerEarnings($sellerId){  
         $list =  SalesOrder::where('seller_id',$sellerId)->where('order_status','delivered')->where('payment_status','paid')->get();
        if($list)
            {
                 $commission = 0; $earnings = 0;$mjs_tax=0;
                $data          =   [];  
                foreach($list  as $row)
                {
                    
                    $products = SalesOrderItem::where('is_deleted',0)->where('sales_id',$row->id)->get();
                    foreach($products as $prd)
                    {
                        $prds[] = $prd->prd_name;
                        $earnings += ($prd->price * $prd->qty ) + $prd->tax_seller;
                        $mjs_tax += ($prd->tax - $prd->tax_seller);
                        $commission += ($prd->mjs_fee + $prd->pg_fee);
                    }
                    // $earnings += $row->shiping_charge;
                    //  $earnings = $earnings- $row->discount;
                }
                $data['earnings']          =   round($earnings);
                $data['commission']          =   round($commission);
                
            }
            return $data;
    }
    
    static function product_amount($sellerId,$row){  
         $list =  SalesOrder::where('seller_id',$sellerId)->where('id',$row)->where('order_status','delivered')->where('payment_status','paid')->get();
        if($list)
            {
                 $commission = 0; $earnings = 0;$mjs_tax=0;
                $data          =   [];  
                foreach($list  as $row)
                {
                    
                    $products = SalesOrderItem::where('is_deleted',0)->where('sales_id',$row->id)->get();
                    foreach($products as $prd)
                    {
                        $prds[] = $prd->prd_name;
                        $earnings += ($prd->price * $prd->qty );
                        
                    }
                    // $earnings += $row->shiping_charge;
                    //  $earnings = $earnings- $row->discount;
                }
                $data['seller_amount']          =   round($earnings);
                
            }
            return $data;
    }
   public function payment_method(){ return $this->belongsTo(SalesOrderPayment::class, 'sales_id'); }

    
}

