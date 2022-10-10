<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
class SellerBank extends Model{
    use HasFactory;
    protected $table = 'usr_seller_bank_details';
    protected $fillable = ['seller_id','ac_no', 'ac_holder', 'bank', 'acc_type', 'ifsc','branch','is_active','created_at','updated_at','is_deleted'];
    public function sellerMst(){ return $this->belongsTo(Seller ::class, 'seller_id'); }
 
    public function store($sellerId){ return Store::where('seller_id',$sellerId)->first(); }

    static function getBankData($seller_id=''){ 
            
         
              $bankdata = SellerBank::where('seller_id',$seller_id)->where('is_deleted',0)->get();
           
            if($bankdata){ 
            $data               =   [];
            foreach($bankdata    as  $row){

            $data['id']        =   $row->id;
            $data['seller_id']        =   $row->seller_id;
            $decrypt= Crypt::decryptString($row->ac_no);
            $data['ac_no']        =   $decrypt;
            $data['ac_holder']        =   $row->ac_holder;
            $data['bank_name']        =   $row->bank;
            $data['acc_type']        =   $row->acc_type;
            $data['ifsc']       =     $row->ifsc;
            $data['branch']       =   $row->branch;
            $data['is_active']       =   $row->is_active;
            $data['created_at']       =   $row->created_at;
            $data['updated_at']       =   $row->updated_at;
            $data['is_deleted']       =   $row->is_deleted;
            }

            return $data;
            }else{ return false; }

        }
}
