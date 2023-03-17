<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CmsContent;
use App\Models\PrdOffer;
use Illuminate\Support\Str;
use Validator;

class DiscountController extends Controller
{
    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unique_id'=>['required','numeric'],
            'org_id'=>['required','numeric'],
            'prd_id'=>['required','numeric'],
            'discount_value'=>['required','numeric'],
            'discount_type'=>['required','in:percentage,amount'],
            'quantity_limit'=>['required','numeric'],
            'valid_from'=>['nullable','date_format:d-m-Y'],
            'valid_to'=>['nullable','date_format:d-m-Y'],
            'platform'=>['required','in:ecom']
        ]);

        if($validator->passes())
        {
            $unique_id = $request->unique_id;
            $discount_validate = PrdOffer::where('unique_id',$unique_id)->first();

            if($discount_validate)
            {
                return response()->json(['httpcode'=>400,'status'=>'error','error'=>'Discount Already Exists!']);
            }
            else
            {
                $discount['unique_id'] = $request->unique_id;
                $discount['org_id'] = $request->org_id;
                $discount['prd_id'] = $request->prd_id;
                $discount['discount_value'] = $request->discount_value;
                $discount['discount_type'] = $request->discount_type;
                $discount['quantity_limit'] = $request->quantity_limit;
                $discount['valid_from'] = date('Y-m-d',strtotime($request->valid_from));
                $discount['valid_to'] = date('Y-m-d',strtotime($request->valid_to));
                $discount['is_active'] = $request->is_active;
                $discount['is_deleted'] = 0;
                $discount['platform'] = $request->platform;
                $discount['created_by'] = 1;
                $discount['updated_by'] = 1;
                $discount['created_at'] = date('Y-m-d H:i:s');
                $discount['updated_at'] = date('Y-m-d H:i:s');

                $discount_id = PrdOffer::create($discount)->id;

                return response()->json(['httpcode'=>200,'success'=>'Successfully Added!','primary_key'=>$discount_id]);
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
            'discount_id'=>['required','numeric'],
            'unique_id'=>['required','numeric'],
            'org_id'=>['required','numeric'],
            'prd_id'=>['required','numeric'],
            'discount_value'=>['required','numeric'],
            'discount_type'=>['required','in:percentage,amount'],
            'quantity_limit'=>['required','numeric'],
            'valid_from'=>['nullable','date_format:d-m-Y'],
            'valid_to'=>['nullable','date_format:d-m-Y'],
            'platform'=>['required','in:ecom']
        ]);

        if($validator->passes())
        {
            $discount_id = $request->discount_id;
            $unique_id = $request->unique_id;

            $discount_validate = PrdOffer::where('unique_id',$unique_id)->where('id','!=',$discount_id)->first();

            if($discount_validate)
            {
                return response()->json(['httpcode'=>400,'status'=>'error','error'=>'Discount Already Exists!']);
            }
            else
            {
                $discount_check = PrdOffer::where('unique_id',$unique_id)->where('id',$discount_id)->first();

                if($discount_check)
                {
                    $discount['unique_id'] = $request->unique_id;
                    $discount['org_id'] = $request->org_id;
                    $discount['prd_id'] = $request->prd_id;
                    $discount['discount_value'] = $request->discount_value;
                    $discount['discount_type'] = $request->discount_type;
                    $discount['quantity_limit'] = $request->quantity_limit;
                    $discount['valid_from'] = date('Y-m-d',strtotime($request->valid_from));
                    $discount['valid_to'] = date('Y-m-d',strtotime($request->valid_to));
                    $discount['is_active'] = $request->is_active;
                    $discount['is_deleted'] = 0;
                    $discount['platform'] = $request->platform;
                    $discount['created_by'] = 1;
                    $discount['updated_by'] = 1;
                    $discount['created_at'] = date('Y-m-d H:i:s');
                    $discount['updated_at'] = date('Y-m-d H:i:s');

                    PrdOffer::where('id',$discount_id)->update($discount);

                    return response()->json(['httpcode'=>200,'success'=>'Successfully Updated!','primary_key'=>$discount_id]);
                }
                else
                {
                    return response()->json(['httpcode'=>400,'status'=>'error','error'=>'Discount Doesnot Exists!']);
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
            'discount_id'=>['required','numeric'],
            'org_id'=>['required','numeric'],
            'platform'=>['required','in:ecom']
        ]);

        if($validator->passes())
        {
            $discount_id = $request->discount_id;
            $org_id = $request->org_id;
            $platform = $request->platform;

            $discount_validate = PrdOffer::where('org_id',$org_id)->where('id',$discount_id)->where('platform',$platform)->first();

            if($discount_validate)
            {
                PrdOffer::where('id',$discount_id)->update(['is_deleted'=>1,'is_active'=>0]);

                return response()->json(['httpcode'=>200,'success'=>'Successfully Deleted!','primary_key'=>$discount_id]);
            }
            else
            {
                return response()->json(['httpcode'=>400,'status'=>'error','error'=>'Discount Doesnot Exists!']);
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
            'org_id'=>['required','numeric'],
            'platform'=>['required','in:ecom']
        ]);

        if($validator->passes())
        {
            $org_id = $request->org_id;
            $platform = $request->platform;

            $discounts = PrdOffer::where('org_id',$org_id)->where('platform',$platform)->orderBy('id','DESC')->get();

            if(count($discounts) > 0)
            {
                $discounts_list = [];

                foreach($discounts as $discount)
                {
                    $list['discount_id'] = $discount['id'];
                    $list['unique_id'] = $discount['unique_id'];
                    $list['org_id'] = $discount['org_id'];
                    $list['prd_id'] = $discount['prd_id'];
                    $list['discount_value'] = $discount['discount_value'];
                    $list['discount_type'] = $discount['discount_type'];
                    $list['quantity_limit'] = $discount['quantity_limit'];
                    $list['valid_from'] = $discount['valid_from'];
                    $list['valid_to'] = $discount['valid_to'];
                    $list['is_active'] = $discount['is_active'];
                    $list['platform'] = $discount['platform'];
                    $list['updated_by'] = $discount['updated_by'];
                    $list['updated_at'] = $discount['updated_at'];

                    $discounts_list[] = $list;
                }

                return response()->json(['httpcode'=>200,'success'=>'Discounts List!','data'=>$discounts_list]);
            }
            else
            {
                return response()->json(['httpcode'=>400,'status'=>'error','error'=>'No Data available!']);
            }
        }
        else
        {
            return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
        }
    }
}