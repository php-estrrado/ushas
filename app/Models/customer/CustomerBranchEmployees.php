<?php

namespace App\Models\customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\customer\CustomerAddressType;
class CustomerBranchEmployees extends Model
{
    use HasFactory;
    protected $table = 'user_branch_employees';
    protected $guarded=[];
	
	static function getEmployeeBranches($emp_id){ 
		$branches =  CustomerBranchEmployees::join('user_business_branches', 'user_business_branches.id', '=', 'user_branch_employees.branch_id')
		->where('user_branch_employees.user_id',$emp_id)->where('user_business_branches.is_active',1)->where('user_business_branches.is_deleted',0)->groupBy('user_branch_employees.branch_id')->get();
		//dd($branches);
		$data               =   [];
		if($branches){ 
        foreach($branches    as  $row){
        $data[$row->id]['branch_id']        =   $row->branch_id;
        $data[$row->id]['branch_name']      =    $row->branch_name;
        $data[$row->id]['branch_address']	=   CustomerBranchEmployees::getBranchAddress($row->address_id);
        
		}
		return $data;
        }else{
			
		return false;
		
		}
	}
	
    public function telecom_ph($user_id){ return CustomerTelecom::select('country_code', 'usr_telecom_value')->where('user_id',$user_id)->where('usr_telecom_typ_id',2)->where('is_active',1)->where('is_deleted',0)->first(); }
    public function telecom_email($user_id){ return CustomerTelecom::where('user_id',$user_id)->where('usr_telecom_typ_id',1)->where('is_active',1)->where('is_deleted',0)->first(); }
	//public function teleEmail(){ return $this->belongsTo(CustomerTelecom ::class, 'email'); }

    static function getBranchAddress($address_id){ 
	//dd($address_id);
		$address =  CustomerAddress::where('id',$address_id)->where('is_active',1)->where('is_deleted',0)->first();
		$data               =   [];
		//dd($data);
		if($address){ 
        
       
        $data['id']        =   $address->id;
        $data['type']      =   CustomerBranchEmployees::getAddrType($address->usr_addr_typ_id);
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
	    static function getAddrType($field_id){ 

        $addr_type =CustomerAddressType::where('id', $field_id)->first();
        if($addr_type){ 
        $return_cont = $addr_type->usr_addr_typ_name;
        return $return_cont;
        }else{ return false; }
        }
	      
}
