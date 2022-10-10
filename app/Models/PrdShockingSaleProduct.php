<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Product;
use App\Models\PrdShockingSale;
class PrdShockingSaleProduct extends Model{
    use HasFactory;
    protected $table = 'prd_shock_sale_products';
    protected $fillable = ['shock_sale_id','prd_id','is_active','is_deleted'];
 
    public function Product(){ return $this->belongsTo(Product::class, 'prd_id'); }
    public function Store($seller_id){ return DB::table('usr_stores')->where('seller_id', $seller_id)->first(); }
    static function getShockingSalesProducts($sale_id){ 
         
           $shk_list = PrdShockingSaleProduct::where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->where('seller_id',auth()->user()->id)->where('shock_sale_id',$sale_id)->get();     

            if($shk_list){ 
            $data               =   '';
            foreach($shk_list    as  $row){
            // $data[$row->id]['id']        =   $row->id;
            // $data[$row->id]['seller_id']         =   $row->seller_id;
            // $data[$row->id]['shock_sale_id']         =   $row->shock_sale_id;
            // $data[$row->id]['prd_id']         =   $row->prd_id;
            $data       =   PrdShockingSaleProduct::getProducts($row->prd_id);
            // $data[$row->id]['is_active']       =   $row->is_active;
            // $data[$row->id]['created_at']       =   $row->created_at; 
              }

            return $data;
            }else{ return false; }

        }

         static function getShockingSalesProductsStatus($sale_id){ 
         
           $shk_list = PrdShockingSaleProduct::where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->where('seller_id',auth()->user()->id)->where('shock_sale_id',$sale_id)->get();     

            if($shk_list){ 
            $data               =   [];
            foreach($shk_list    as  $row){
            $data['is_active']       =   $row->is_active;
            $data['id']       =   $row->id;
              }

            return $data;
            }else{ return false; }

        }

    static function getProducts($field_id){ 

        $exp = explode(",", $field_id);
        $prd_arr = [];
        foreach($exp as $k=>$v){

          if(Product::where("id",$v)->first()){ $prd_arr[] = Product::where("id",$v)->first()->name; }  
        }
        if($prd_arr){ 
        $return_cont = implode(",", $prd_arr);
        return $return_cont;
        }else{ return false; }
        }

}
