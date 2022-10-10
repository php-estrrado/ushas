<?php

namespace App\Models;

use App\Models\customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrdReview extends Model
{
    use HasFactory;
    protected $table = 'prd_review';
    protected $fillable = ['prd_id','user_id','rating','sale_id','headline','comment','image','is_active','is_deleted','created_at','updated_at'];
    public function customerinfo($user_id){ return customer\CustomerInfo::where('user_id',$user_id)->first(); }
     public function product(){ return $this->belongsTo(Product ::class, 'prd_id'); }

    static function getProductReviews($prd_id){ 
        $prd = $prd_id;
         
            // $rvw_list = PrdReview::where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->whereIn('user_id',function($query) use ($prd_id) {
            // $query->select('cust_id')->from('sales_orders')->whereIn('id',function($query) use ($prd_id) {
            // $query->select('sales_id')->from('sales_order_items')->where("prd_id",$prd_id); });
            // })->orderBy('id', 'DESC')->get();     
            
            $rvw_list = PrdReview::where('is_deleted',0)->where('prd_id',$prd_id)->orderBy('id', 'DESC')->get();     
            
      
            if($rvw_list){ 
            $data               =   [];
            foreach($rvw_list    as  $row){
            $data[$row->id]['id']        =   $row->id;
            $data[$row->id]['prd_id']         =   $row->prd_id;
            $data[$row->id]['user_id']         =   $row->user_id;
            $data[$row->id]['user']         =   PrdReview::userInfo($row->user_id);
            $data[$row->id]['rating']       =   $row->rating;
            $data[$row->id]['comment']       =   $row->comment;
            $data[$row->id]['headline']       =   $row->headline;
            $data[$row->id]['image']       =   $row->image;
            $data[$row->id]['is_active']       =   $row->is_active;
            $data[$row->id]['created_by']       =   $row->created_by; 
            $data[$row->id]['created_at']       =   $row->created_at; 
              }

            return $data;
            }else{ return false; }

        }
    

    static function userInfo($field_id){ 

        $customer_name = "";
       if(CustomerInfo::where("user_id",$field_id)->first()) {
       $customer = CustomerInfo::where("user_id",$field_id)->first(); 
       if(isset($customer->first_name)){
        $customer_name .=$customer->first_name." ";
       }
       if(isset($customer->middle_name)){
        $customer_name .=$customer->middle_name." ";
       }
       if(isset($customer->last_name)){
        $customer_name .=$customer->last_name;
       }
       
       }  
     
        if($customer_name){ 
           return $customer_name;
        }else{ return false; }
        }
}
