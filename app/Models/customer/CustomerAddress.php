<?php

namespace App\Models\customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\customer\CustomerAddressType;
use App\Models\SalesOrderAddress;
class CustomerAddress extends Model
{
    use HasFactory;
    protected $table = 'usr_address';
    protected $guarded=[];
    
    static function getUserAddress($user_id,$default=''){ 

      if($default !="") {

      $addr_list =  CustomerAddress::where('user_id',$user_id)->where('is_active',1)->where('is_default',1)->where('is_deleted',0)->get();
      }else {
      $addr_list =  CustomerAddress::where('user_id',$user_id)->where('is_active',1)->where('is_deleted',0)->get();
      }
       
     
        if($addr_list){ 
        $data               =   [];
        foreach($addr_list    as  $row){
        $data[$row->id]['id']        =   $row->id;
        $data[$row->id]['type']         =   CustomerAddress::getAddrType($row->usr_addr_typ_id);
        $data[$row->id]['city_data']       =    getCities($row->city_id);
        $data[$row->id]['address_1']        =   $row->address_1;
        $data[$row->id]['address_2']        =   $row->address_2;
        $data[$row->id]['pincode']        =   $row->pincode;
        $data[$row->id]['latitude']        =   $row->latitude;
        $data[$row->id]['longitude']        =   $row->longitude;
        $data[$row->id]['is_default']        =   $row->is_default;
        $data[$row->id]['is_active']       =   $row->is_active; 
        $data[$row->id]['is_deleted']       =   $row->is_deleted;
        $data[$row->id]['created_at']       =   $row->created_at; 
        }

        return $data;
        }else{ return false; }
    }
    
    
    static function getUserAddress_sale($user_id,$sale=''){ 


      $addr_list =  SalesOrderAddress::where('cust_id',$user_id)->where('sales_id',$sale)->get();
      
       
     
        if($addr_list){ 
        $data               =   [];
        foreach($addr_list    as  $row){
        $data[$row->id]['id']        =   $row->id;
        $data[$row->id]['type']         =   CustomerAddress::getAddrType($row->addr_id);
        $data[$row->id]['city_data']       =    getCities($row->city);
        $data[$row->id]['address_1']        =   $row->address1;
        $data[$row->id]['address_2']        =   $row->address2;
        $data[$row->id]['pincode']        =   $row->zip_code;
        $data[$row->id]['latitude']        =   $row->latitude;
        $data[$row->id]['longitude']        =   $row->longitude;
       // $data[$row->id]['is_default']        =   $row->is_default;
       $data[$row->id]['phone']       =   $row->s_phone; 
       $data[$row->id]['country_code']       =   $row->s_country_code; 
        $data[$row->id]['is_active']       =   $row->is_active; 
        $data[$row->id]['is_deleted']       =   $row->is_deleted;
        $data[$row->id]['created_at']       =   $row->created_at; 
        }

        return $data;
        }else{ return false; }
    }

    static function getAddrType($field_id){ 

        $addr_type =CustomerAddressType::where('id', $field_id)->first();
        if($addr_type){ 
        $return_cont = $addr_type->usr_addr_typ_name;
        return $return_cont;
        }else{ return false; }
        }
           
}
