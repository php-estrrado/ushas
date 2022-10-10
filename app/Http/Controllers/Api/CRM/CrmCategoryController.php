<?php

namespace App\Http\Controllers\Api\CRM;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;
use App\Models\Modules;
use App\Models\UserRoles;
use App\Models\BusinessCategory;

use App\Rules\Name;
use Validator;


class CrmCategoryController extends Controller
{
  

    public function add(Request $request)
    {
		$formData   =   $request->all(); 
        $rules      =   array();
            $rules['crm_id']    = 'required|numeric';
            $rules['name']          = 'required|string';
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 
				$exist=BusinessCategory::where('crm_id',$formData['crm_id'])->where('is_active',1)->where('is_deleted',0)->first();
                if($exist){
					        return array('httpcode'=>'200','status'=>'error','message'=>'Exist');

				}else{
					$business_category_id = BusinessCategory::create(['crm_id' => $formData['crm_id'],
					'name' => $formData['name'],
					'created_at'=>date("Y-m-d H:i:s"),
					'updated_at'=>date("Y-m-d H:i:s")])->id;
					
					        return array('httpcode'=>'200','status'=>'success','message'=>'Success','data'=>['business_category_id' =>$business_category_id]);

				}
				
				
			}			

        
    }
	public function update(Request $request)
    {
		$formData   =   $request->all(); 
        $rules      =   array();
            $rules['crm_id']    = 'required|numeric';
            $rules['name']          = 'required|string';
            $validator  =   Validator::make($request->all(), $rules);
            if ($validator->fails()) 
                {
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
                }
            else
                { 
				$exist=BusinessCategory::where('crm_id',$formData['crm_id'])->where('is_active',1)->where('is_deleted',0)->first();
                if($exist){
					BusinessCategory::where('crm_id',$formData['crm_id'])->update(['crm_id' => $formData['crm_id'],
					'name' => $formData['name'],
					'created_at'=>date("Y-m-d H:i:s"),
					'updated_at'=>date("Y-m-d H:i:s")]);
					$business_category_id=$exist->id;
					return array('httpcode'=>'200','status'=>'success','message'=>'Success','data'=>['business_category_id' =>$business_category_id]);

				}else{
					return array('httpcode'=>'400','status'=>'error','message'=>'Not Exist');
				}
				
			}			

        
    }
    public function deleteCategory(Request $request){
		$formData   =   $request->all(); 
		$rules['crm_id']          = 'required|numeric';
        $validator  =   Validator::make($request->all(), $rules);
        if ($validator->fails()){
                    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
                    return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
        } else{
			$exist=BusinessCategory::where('crm_id',$formData['crm_id'])->where('is_active',1)->where('is_deleted',0)->first();
			if($exist){
				$data1['is_deleted']=1;
				$data1['is_active']=0;
				BusinessCategory::where('crm_id',$formData['crm_id'])->update($data1);
				return array('httpcode'=>'200','status'=>'success','message'=>'Successfully Deleted');
			}else{
				return array('httpcode'=>'400','status'=>'error','message'=>'Not Exist');
			}
		}
			
	}

    

}


