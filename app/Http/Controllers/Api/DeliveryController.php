<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Delivery;
use App\Models\CmsContent;
use Illuminate\Support\Str;
use Validator;

class DeliveryController extends Controller
{
    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'org_id'=>['required','numeric'],
            'delivery_type_name'=>['required','max:255'],
            'delivery_description'=>['required','max:255'],
            'delivery_charges'=>['required','max:255']
        ]);

        if($validator->passes())
        {
            $delivery_type_name = $request->delivery_type_name;
            $deliverytype_validate = Delivery::where('delivery_type_name',$delivery_type_name)->first();

            if($deliverytype_validate)
            {
                return response()->json(['httpcode'=>400,'status'=>'error','error'=>'Delivery Type Already Exists!']);
            }
            else
            {
                $delivery['org_id'] = $request->org_id;
                $delivery['delivery_type_name'] = $request->delivery_type_name;
                $delivery['delivery_description'] = $this->addCmsContent(NULL,1,$request->delivery_description);
                $delivery['delivery_charges'] = $request->delivery_charges;
                $delivery['is_active'] = $request->is_active;
                $delivery['is_deleted'] = 0;
                $delivery['created_by'] = 1;
                $delivery['updated_by'] = 1;
                $delivery['created_at'] = date('Y-m-d H:i:s');
                $delivery['updated_at'] = date('Y-m-d H:i:s');

                $delivery_type_id = Delivery::create($delivery)->id;

                return response()->json(['httpcode'=>200,'success'=>'Successfully Added!','primary_key'=>$delivery_type_id]);
            }
        }
        else
        {
            return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'delivery_type_id'=>['required','numeric'],
            'org_id'=>['required','numeric'],
            'delivery_type_name'=>['required','max:255'],
            'delivery_description'=>['required','max:255'],
            'delivery_charges'=>['required','max:255']
        ]);

        if($validator->passes())
        {
            $delivery_type_id = $request->delivery_type_id;
            $delivery_type_name = $request->delivery_type_name;

            $deliverytype_validate = Delivery::where('delivery_type_name',$delivery_type_name)->where('id','!=',$delivery_type_id)->first();

            if($deliverytype_validate)
            {
                return response()->json(['httpcode'=>400,'status'=>'error','error'=>'Delivery Type Already Exists!']);
            }
            else
            {
                $deliverytype = Delivery::where('id',$delivery_type_id)->first();

                if($deliverytype)
                {
                    $delivery['org_id'] = $request->org_id;
                    $delivery['delivery_type_name'] = $request->delivery_type_name;
                    $delivery['delivery_description'] = $this->addCmsContent($deliverytype->delivery_description,1,$request->delivery_description);
                    $delivery['delivery_charges'] = $request->delivery_charges;
                    $delivery['is_active'] = $request->is_active;
                    $delivery['is_deleted'] = 0;
                    $delivery['created_by'] = 1;
                    $delivery['updated_by'] = 1;
                    $delivery['created_at'] = date('Y-m-d H:i:s');
                    $delivery['updated_at'] = date('Y-m-d H:i:s');

                    Delivery::where('id',$delivery_type_id)->update($delivery);

                    return response()->json(['httpcode'=>200,'success'=>'Successfully Updated!','primary_key'=>$delivery_type_id]);
                }
                else
                {
                    return response()->json(['httpcode'=>400,'status'=>'error','error'=>'Delivery Type Doesnot Exists!']);
                }
            }
        }
        else
        {
            return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
        }
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'delivery_type_id'=>['required','numeric'],
            'org_id'=>['required','numeric']
        ]);

        if($validator->passes())
        {
            $delivery_type_id = $request->delivery_type_id;
            $org_id = $request->org_id;

            $deliverytype = Delivery::where('id',$delivery_type_id)->where('org_id',$org_id)->first();

            if($deliverytype)
            {
                Delivery::where('id',$delivery_type_id)->where('org_id',$org_id)->update(['is_active'=>0,'is_deleted'=>1]);

                return response()->json(['httpcode'=>200,'success'=>'Successfully Deleted!','primary_key'=>$delivery_type_id]);
            }
            else
            {
                return response()->json(['httpcode'=>400,'status'=>'error','error'=>'Delivery Type Doesnot Exists!']);
            }

        }
        else
        {
            return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
        }
    }

    public function list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'org_id'=>['required','numeric']
        ]);

        if($validator->passes())
        {
            $org_id = $request->org_id;

            $delivery_types = Delivery::where('org_id',$org_id)->where('is_deleted',0)->orderBy('id','DESC')->get();

            $delivery_type_list = [];

            if(count($delivery_types) > 0)
            {
                foreach($delivery_types as $delivery_type)
                {
                    $list['delivery_type_id'] = $delivery_type->id;
                    $list['delivery_type_name'] = $delivery_type->delivery_type_name;
                    $list['delivery_description'] = $delivery_type->delivery_description;
                    $list['delivery_charges'] = $delivery_type->delivery_charges;
                    $list['is_active'] = $delivery_type->is_active;
                    $list['created_by'] = $delivery_type->created_by;
                    $list['created_at'] = $delivery_type->created_at;
                    $list['org_id'] = $delivery_type->org_id;

                    $delivery_type_list[] = $list;
                }
                return ['httpcode'=>200,'status'=>'success','message'=>'Delivery Type List','data'=>$delivery_type_list];
            }
            return response()->json(['httpcode'=>'400','status'=>'error','error'=>'No Data Available']);
        }
        else
        {
            return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
        }
    }

    function addCmsContent($cntId,$l, $cnt)
    {
        if($cnt)
        {
            if($cntId)
            {
                $qry = CmsContent::where('cnt_id',$cntId)->where('is_deleted',0)->first();
                if($qry)
                {
                    CmsContent::where('cnt_id',$cntId)->update(['content'=>$cnt,'updated_by'=>1]);
                    $insertId = $qry->cnt_id;
                }
                else
                {
                    $insertId=CmsContent::create(['cnt_id'=>$cntId,'lang_id'=>$l,'content'=>$cnt,'created_by'=>1])->id;
                    $insertId = CmsContent::where('id',$insertId)->first()->cnt_id;
                }
            }
            else
            {
                $cms = CmsContent::orderBy('cnt_id','desc')->first();
                if($cms)
                {
                    $cntId = ($cms->cnt_id+1); }else{ $cntId = 1;
                }
                $cmscont = CmsContent::create(['cnt_id'=>$cntId,'lang_id'=>$l,'content'=>$cnt,'created_by'=>1])->id;
                $insertId = $cntId;
            }
        }
        else
        {
            $insertId =NULL;
        }
        
        return $insertId;
    }
}