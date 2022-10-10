<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    use HasFactory;
    protected $table        =   'usr_seller_settilments';
    protected $fillable     =   ['seller_id','admin_id','amount','is_active'];
    public function seller(){ return $this->belongsTo(Seller ::class, 'seller_id'); }
    public function totEarnings($sellerId){ return SalesOrder::where('seller_id',$sellerId)->where('payment_status','success'); }
    public function paidSettlement($sellerId){ return Settlement::where('seller_id',$sellerId)->where('is_deleted',0)->sum('amount'); }
    
     public function totalEarnings($sellerId,$sale_id){ 
        
        $list =  SalesOrder::where('seller_id',$sellerId)->where('id',$sale_id)->where('order_status','delivered')->where('payment_status','paid')->get();
        if($list)
            {
                 $commission = 0; $earnings = 0;
                $data          =   [];  
                foreach($list  as $row)
                {
                    
                    $products = SalesOrderItem::where('is_deleted',0)->where('sales_id',$row->id)->get();
                    foreach($products as $prd)
                    {
                        $prds[] = $prd->prd_name;
                        $earnings += ($prd->price * $prd->qty ) + $prd->tax_seller;
                        $earnings += ($prd->tax - $prd->tax_seller);
                        $commission += ($prd->mjs_fee + $prd->pg_fee);
                    }
                    $earnings += $row->shiping_charge;
                    $earnings = $earnings- $row->discount;

                }
                $data['earnings']          =   round($earnings);
                $data['commission']          =   round($commission);
                
            }
            return $data;
    }
    
     static function totalSellerEarnings($sellerId){  
         $list =  SalesOrder::where('seller_id',$sellerId)->where('order_status','delivered')->where('payment_status','paid')->get();
        if($list)
            {
                 $commission = 0; $earnings = 0;
                $data          =   [];  
                foreach($list  as $row)
                {
                    
                    $products = SalesOrderItem::where('is_deleted',0)->where('sales_id',$row->id)->get();
                    foreach($products as $prd)
                    {
                        $prds[] = $prd->prd_name;
                        $earnings += ($prd->price * $prd->qty ) + $prd->tax_seller;
                        $earnings += ($prd->tax - $prd->tax_seller);
                        $commission += ($prd->mjs_fee + $prd->pg_fee);
                    }
                    $earnings += $row->shiping_charge;
                     $earnings = $earnings- $row->discount;
                }
                $data['earnings']          =   round($earnings);
                $data['commission']          =   round($commission);
                
            }
            return $data;
    }
}

