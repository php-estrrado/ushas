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
	protected $fillable = ['branch_id','user_id','is_active','is_deleted'];

	
	static function getEmployeeBranches($emp_id){ 
		$branches =  CustomerBranchEmployees::join('user_business_branches', 'user_business_branches.id', '=', 'user_branch_employees.branch_id')
		->where('user_branch_employees.user_id',$emp_id)->where('user_business_branches.is_active',1)->where('user_business_branches.is_deleted',0)->groupBy('user_branch_employees.branch_id')->get();
		//dd($branches);
		$data               =   [];
		if($branches){ 
        foreach($branches    as  $row){
        $data[$row->id]['branch_id']        =   $row->branch_id;
        $data[$row->id]['branch_name']      =    $row->branch_name;
        
        
		}
		return $data;
        }else{
			
		return false;
		
		}
	}
	
    public function telecom_ph($user_id){ return CustomerTelecom::select('country_code', 'usr_telecom_value')->where('user_id',$user_id)->where('usr_telecom_typ_id',2)->where('is_active',1)->where('is_deleted',0)->first(); }
    public function telecom_email($user_id){ return CustomerTelecom::where('user_id',$user_id)->where('usr_telecom_typ_id',1)->where('is_active',1)->where('is_deleted',0)->first(); }

    
	      
}
