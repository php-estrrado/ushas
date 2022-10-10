<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\customer\CustomerAddressType;
class CustomerBranches extends Model
{
    use HasFactory;
    protected $table = 'user_business_branches';
    protected $guarded=[];
    
	static function getBranchAddress($address_id){ 
		$address =  CustomerAddress::where('id',$address_id)->where('is_active',1)->where('is_deleted',0)->first();
		$data               =   [];
		//dd($data);
		if($address){ 
        
       
        $data['id']        =   $address->id;
        $data['type']      =   CustomerBranches::getAddrType($address->usr_addr_typ_id);
        $data['city_data'] =    getCities($address->city_id);
        $data['address_1'] =   $address->address_1;
        $data['address_2'] =   $address->address_2;
        $data['pincode']   =   $address->pincode;
        $data['latitude']  =   $address->latitude;
        $data['longitude'] =   $address->longitude;
        $data['is_default']=   $address->is_default;
        $data['is_active'] =   $address->is_active; 
        $data['is_deleted']=   $address->is_deleted;
        $data['created_at']=   $address->created_at; 
		
		return $data;
		
        }else{
			
		return false;
		
		}
	} 
	static function getDefaultAddress($user_id){ 
		$address =  CustomerAddress::where('user_id',$user_id)->where('is_active',1)->where('is_default',1)->where('is_deleted',0)->first();
		$data               =   [];
		//dd($data);
		if($address){ 
        
       
        $data['id']        =   $address->id;
        $data['type']      =   CustomerBranches::getAddrType($address->usr_addr_typ_id);
        $data['city_data'] =    getCities($address->city_id);
        $data['address_1'] =   $address->address_1;
        $data['address_2'] =   $address->address_2;
        $data['pincode']   =   $address->pincode;
        $data['latitude']  =   $address->latitude;
        $data['longitude'] =   $address->longitude;
        $data['is_default']=   $address->is_default;
        $data['is_active'] =   $address->is_active; 
        $data['is_deleted']=   $address->is_deleted;
        $data['created_at']=   $address->created_at; 
		
		return $data;
		
        }else{
			
		return false;
		
		}
	}
	static function getBranchEmployees($branch_id){ 
		$employees =  CustomerBranchEmployees::where('branch_id',$branch_id)->where('is_active',1)->where('is_deleted',0)->get();
		
		$data               =   [];
		if($employees){ 
        foreach($employees    as  $row){
        $data[$row->id]['id']        =   $row->id;
        $data[$row->id]['employee_data']      =   CustomerBranches::getEmployeeDetails($row->user_id);
        $data[$row->id]['created_at']       =   $row->created_at; 
        
		}
		return $data;
        }else{
			
		return false;
		
		}
	}
	static function getEmployeeDetails($user_id){ 
		$employee =  CustomerInfo::where('user_id',$user_id)->where('is_active',1)->where('is_deleted',0)->first();
		
		$data               =   [];
		//dd($data);
		if($employee){ 
        
       
        $data['user_id']        =   $employee->id;
        $data['employee_name']  =   $employee->first_name;
        $data['is_active']      =   $employee->is_active; 
        $data['is_deleted']     =   $employee->is_deleted;
        $data['created_at']     =   $employee->created_at; 
      
		return $data;
        }else{
			
		return false;
		
		}
	}
    static function getAddrType($field_id){ 

        $addr_type =CustomerAddressType::where('id', $field_id)->first();
        if($addr_type){ 
        $return_cont = $addr_type->usr_addr_typ_name;
        return $return_cont;
        }else{ return false; }
        }       
}
