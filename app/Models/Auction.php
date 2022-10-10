<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;
use App\Models\customer\CustomerInfo;
use App\Models\AuctionHist;
use App\Models\Store;
use App\Models\Product;
use App\Models\ProductImage;
class Auction extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'auction';
    
    public function sellerInfo(){ return $this->hasOne(SellerInfo ::class, 'seller_id'); }
    protected $fillable = ['org_id','auction_code', 'seller_id', 'cat_id','subcat_id','product_id','auction_desc_cid','min_bid_price',
'shipping_cost_id','auct_start','auct_end','sale_id','bid_allocated_to','status','is_active','is_deleted','created_by','updated_by','created_at','updated_at'];

        static function getAuctions($refund=''){ 
            
            if($refund !=""){
                $acn_list = Auction::where(function ($query) { $query->where('status', '=', "processing")->orWhere('status', '=', "closed");})->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->orderBy('id', 'DESC')->get(); 
            }else{
$acn_list = Auction::where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->orderBy('id', 'DESC')->get();     
            }
           
         

            if($acn_list){ 
            $data               =   [];
            foreach($acn_list    as  $row){
            $data[$row->id]['id']        =   $row->id;
            $data[$row->id]['auction_code']        =   $row->auction_code;
            $data[$row->id]['seller_id']        =   $row->seller_id;
            $data[$row->id]['seller_name']        =  Store::where('seller_id',$row->seller_id)->first()->store_name;
            $data[$row->id]['product_id']        =   $row->product_id;
            if(Product::where('id',$row->product_id)->first()) { $data[$row->id]['product_name']        =  Product::where('id',$row->product_id)->first()->name; }else { $data[$row->id]['product_name']        =""; }
            if(ProductImage::where('prd_id',$row->product_id)->first()) {
                $data[$row->id]['product_img']        =  ProductImage::where('prd_id',$row->product_id)->first()->image; }else {
                    $data[$row->id]['product_img']        = '';
                    
                }
            $data[$row->id]['auct_desc']       =    Auction::getCpnContent($row->auction_desc_cid);
            $data[$row->id]['auct_start']       =   $row->auct_start;
            $data[$row->id]['auct_end']       =   $row->auct_end;
            $data[$row->id]['min_bid_price']       =   $row->min_bid_price;
            $data[$row->id]['shipping_cost_id']       =   $row->shipping_cost_id;
            $data[$row->id]['bid_allocated_to']       =   $row->bid_allocated_to;
            $user_name = CustomerInfo::where('user_id', $row->bid_allocated_to)->first();
            if($user_name){
                $user_name = $user_name->first_name." ". $user_name->last_name;
            }else{
              $user_name = "";  
            }
            $data[$row->id]['bid_allocated_to_user']       =   $user_name;
            $data[$row->id]['bids']       =   AuctionHist::getLog($row->id);  
            $data[$row->id]['status']       =   $row->status;
            $data[$row->id]['is_active']       =   $row->is_active;
            $data[$row->id]['is_deleted']       =   $row->is_deleted;
            $data[$row->id]['created_at']       =   $row->created_at; 
            }

            return $data;
            }else{ return false; }

        }
    
        static function getCpnContent($field_id){ 

        $language =DB::table('glo_lang_lk')->where('is_active', 1)->first();
        $content_table=DB::table('cms_content')->where('cnt_id', $field_id)->where('lang_id', $language->id)->first();
        if($content_table){ 
        $return_cont = $content_table->content;
        return $return_cont;
        }else{ return false; }
        }

        static function getAuctionData($acn_id){ 
            
           $acn_list = Auction::where("id",$acn_id)->orderBy('id', 'DESC')->get();     
         

            if($acn_list){ 
            $data               =   [];
            foreach($acn_list    as  $row){
            $data['id']        =   $row->id;
            $data['auction_code']        =   $row->auction_code;
            $data['seller_id']        =   $row->seller_id;
            $data['seller_name']        =  Store::where('seller_id',$row->seller_id)->first()->store_name;
            $data['product_id']        =   $row->product_id;
          if(Product::where('id',$row->product_id)->first()){
             $data['product_name']        =  Product::where('id',$row->product_id)->first()->name;
         }else {  $data['product_name']        =  ""; } 
           if(ProductImage::where('prd_id',$row->product_id)->first()) {
              $data['product_img']        =  ProductImage::where('prd_id',$row->product_id)->first()->image; 
           }else {
               $data['product_img']        =  '';
           }
            $data['auct_desc']       =    Auction::getCpnContent($row->auction_desc_cid);
            $data['auct_start']       =   $row->auct_start;
            $data['auct_end']       =   $row->auct_end;
            $data['min_bid_price']       =   $row->min_bid_price;
            $data['shipping_cost_id']       =   $row->shipping_cost_id;
            $data['bid_allocated_to']       =   $row->bid_allocated_to;
            $user_name = CustomerInfo::where('user_id', $row->bid_allocated_to)->first();
            if($user_name){
                $user_name = $user_name->first_name." ". $user_name->last_name;
            }else{
              $user_name = "";  
            }
            $data['bid_allocated_to_user']       =   $user_name;
            $data['bids']       =   AuctionHist::getLog($row->id);  
            $data['is_active']       =   $row->is_active;
            $data['status']       =   $row->status;
            $data['is_deleted']       =   $row->is_deleted;
            $data['created_at']       =   $row->created_at; 
            }

            return $data;
            }else{ return false; }

        }
        
        /*********************For API*****************/
        public function Product(){ return $this->belongsTo(Product::class, 'product_id'); }
        public function Store($seller_id){ return DB::table('usr_stores')->where('seller_id', $seller_id)->first(); }
        static function AuctionHist($value){ 
         $data =AuctionHist::where('auction_id',$value)->where('is_active',1)->where('is_deleted',0)->get();
         if($data)
         { 
            $count=count($data);
            return $count;
         }
         else{
            return 0;
         }
        } 
      
        
}
