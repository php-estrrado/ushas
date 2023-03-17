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
use App\Models\Coupon;
use Illuminate\Support\Str;
use Validator;

class CouponController extends Controller
{
    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unique_id'=>['required','numeric'],
            'org_id'=>['required','numeric'],
            'cpn_title'=>['required','max:255'],
            'cpn_desc'=>['required','max:255'],
            'category_id'=>['nullable','numeric'],
            'subcategory_id'=>['nullable','numeric'],
            'seller_id'=>['nullable','numeric'],
            'purchase_type'=>['required','in:number,amount'],
            'ofr_value_type'=>['required','in:percentage,amount'],
            'ofr_value'=>['required','max:255'],
            'ofr_type'=>['required','in:cashback,discount'],
            'ofr_code'=>['required','max:255'],
            'ofr_min_amount'=>['required','numeric'],
            'validity_type'=>['required','in:range,days'],
            'platform'=>['required','in:ecom']
        ]);

        if($validator->passes())
        {
            $unique_id = $request->unique_id;
            $coupon_validate = Coupon::where('unique_id',$unique_id)->first();

            if($coupon_validate)
            {
                return response()->json(['httpcode'=>400,'status'=>'error','error'=>'Coupon Already Exists!']);
            }
            else
            {
                $coupon['unique_id'] = $request->unique_id;
                $coupon['org_id'] = $request->org_id;
                $coupon['cpn_title_cid'] = $this->addCmsContent(NULL,1,$request->cpn_title);
                $coupon['cpn_desc_cid'] = $this->addCmsContent(NULL,1,$request->cpn_desc);
                $coupon['category_id'] = $request->category_id;
                $coupon['subcategory_id'] = $request->subcategory_id;
                $coupon['seller_id'] = $request->seller_id;
                $coupon['purchase_type'] = $request->purchase_type;
                $coupon['purchase_number'] = $request->purchase_number;
                $coupon['purchase_amount'] = $request->purchase_amount;
                $coupon['ofr_value_type'] = $request->ofr_value_type;
                $coupon['ofr_value'] = $request->ofr_value;
                $coupon['ofr_type'] = $request->ofr_type;
                $coupon['ofr_code'] = $request->ofr_code;
                $coupon['ofr_min_amount'] = $request->ofr_min_amount;
                $coupon['validity_type'] = $request->validity_type;
                $coupon['valid_from'] = $request->valid_from;
                $coupon['valid_to'] = $request->valid_to;
                $coupon['valid_days'] = $request->valid_days;
                $coupon['image'] = $request->image;
                $coupon['is_active'] = $request->is_active;
                $coupon['is_deleted'] = $request->is_deleted;
                $coupon['platform'] = $request->platform;
                $coupon['created_by'] = $request->created_by;
                $coupon['user_type'] = $request->user_type;
                $coupon['updated_by'] = $request->updated_by;
                $coupon['created_at'] = date('Y-m-d H:i:s');
                $coupon['updated_at'] = date('Y-m-d H:i:s');

                $coupon_id = Coupon::create($coupon)->id;

                return response()->json(['httpcode'=>200,'success'=>'Successfully Added!','primary_key'=>$coupon_id]);
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
            'coupon_id'=>['required','numeric'],
            'unique_id'=>['required','numeric'],
            'org_id'=>['required','numeric'],
            'cpn_title'=>['required','max:255'],
            'cpn_desc'=>['required','max:255'],
            'category_id'=>['nullable','numeric'],
            'subcategory_id'=>['nullable','numeric'],
            'seller_id'=>['nullable','numeric'],
            'purchase_type'=>['required','in:number,amount'],
            'ofr_value_type'=>['required','in:percentage,amount'],
            'ofr_value'=>['required','max:255'],
            'ofr_type'=>['required','in:cashback,discount'],
            'ofr_code'=>['required','max:255'],
            'ofr_min_amount'=>['required','numeric'],
            'validity_type'=>['required','in:range,days'],
            'platform'=>['required','in:ecom']
        ]);

        if($validator->passes())
        {
            $coupon_id = $request->coupon_id;
            $unique_id = $request->unique_id;
            
            $coupon_validate = Coupon::where('unique_id',$unique_id)->where('id','!=',$coupon_id)->first();

            if($coupon_validate)
            {
                return response()->json(['httpcode'=>400,'status'=>'error','error'=>'Coupon Already Exists!']);
            }
            else
            {
                $coupon_check = Coupon::where('unique_id',$unique_id)->where('id',$coupon_id)->first();

                if($coupon_check)
                {
                    $coupon['unique_id'] = $request->unique_id;
                    $coupon['org_id'] = $request->org_id;
                    $coupon['cpn_title_cid'] = $this->addCmsContent($coupon_check->cpn_title_cid,1,$request->cpn_title);
                    $coupon['cpn_desc_cid'] = $this->addCmsContent($coupon_check->cpn_desc_cid,1,$request->cpn_desc);
                    $coupon['category_id'] = $request->category_id;
                    $coupon['subcategory_id'] = $request->subcategory_id;
                    $coupon['seller_id'] = $request->seller_id;
                    $coupon['purchase_type'] = $request->purchase_type;
                    $coupon['purchase_number'] = $request->purchase_number;
                    $coupon['purchase_amount'] = $request->purchase_amount;
                    $coupon['ofr_value_type'] = $request->ofr_value_type;
                    $coupon['ofr_value'] = $request->ofr_value;
                    $coupon['ofr_type'] = $request->ofr_type;
                    $coupon['ofr_code'] = $request->ofr_code;
                    $coupon['ofr_min_amount'] = $request->ofr_min_amount;
                    $coupon['validity_type'] = $request->validity_type;
                    $coupon['valid_from'] = $request->valid_from;
                    $coupon['valid_to'] = $request->valid_to;
                    $coupon['valid_days'] = $request->valid_days;
                    $coupon['image'] = $request->image;
                    $coupon['is_active'] = $request->is_active;
                    $coupon['is_deleted'] = $request->is_deleted;
                    $coupon['platform'] = $request->platform;
                    $coupon['created_by'] = $request->created_by;
                    $coupon['user_type'] = $request->user_type;
                    $coupon['updated_by'] = $request->updated_by;
                    $coupon['created_at'] = date('Y-m-d H:i:s');
                    $coupon['updated_at'] = date('Y-m-d H:i:s');

                    Coupon::where('id',$coupon_id)->update($coupon);

                    return response()->json(['httpcode'=>200,'success'=>'Successfully Updated!','primary_key'=>$coupon_id]); 
                }
                else
                {
                    return response()->json(['httpcode'=>400,'status'=>'error','error'=>'Coupon Doesnot Exists!']);
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
            'coupon_id'=>['required','numeric'],
            'org_id'=>['required','numeric'],
            'platform'=>['required','in:ecom']
        ]);

        if($validator->passes())
        {
            $coupon_id = $request->coupon_id;
            $org_id = $request->org_id;
            $platform = $request->platform;

            $coupon_check = Coupon::where('id',$coupon_id)->where('org_id',$org_id)->where('platform',$platform)->first();

            if($coupon_check)
            {
                Coupon::where('id',$coupon_id)->where('org_id',$org_id)->where('platform',$platform)->update(['is_active'=>0,'is_deleted'=>1]);

                return response()->json(['httpcode'=>200,'success'=>'Successfully Deleted!','primary_key'=>$coupon_id]);
            }
            else
            {
                return response()->json(['httpcode'=>400,'status'=>'error','error'=>'Coupon Doesnot Exists!']);
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

            $coupons = Coupon::where('org_id',$org_id)->where('platform',$platform)->where('is_deleted',0)->orderBy('id','DESC')->get();

            if(count($coupons) > 0)
            {
                $coupon_list = [];

                foreach($coupons as $coupon)
                {
                    $list['coupon_id'] = $coupon['id'];
                    $list['unique_id'] = $coupon['unique_id'];
                    $list['org_id'] = $coupon['org_id'];
                    $list['cpn_title_cid'] = $coupon['cpn_title'];
                    $list['cpn_desc_cid'] = $coupon['cpn_desc'];
                    $list['category_id'] = $coupon['category_id'];
                    $list['subcategory_id'] = $coupon['subcategory_id'];
                    $list['seller_id'] = $coupon['seller_id'];
                    $list['purchase_type'] = $coupon['purchase_type'];
                    $list['purchase_number'] = $coupon['purchase_number'];
                    $list['purchase_amount'] = $coupon['purchase_amount'];
                    $list['ofr_value_type'] = $coupon['ofr_value_type'];
                    $list['ofr_value'] = $coupon['ofr_value'];
                    $list['ofr_type'] = $coupon['ofr_type'];
                    $list['ofr_code'] = $coupon['ofr_code'];
                    $list['ofr_min_amount'] = $coupon['ofr_min_amount'];
                    $list['validity_type'] = $coupon['validity_type'];
                    $list['valid_from'] = $coupon['valid_from'];
                    $list['valid_to'] = $coupon['valid_to'];
                    $list['valid_days'] = $coupon['valid_days'];
                    $list['image'] = $coupon['image'];
                    $list['is_active'] = $coupon['is_active'];
                    $list['is_deleted'] = $coupon['is_deleted'];
                    $list['platform'] = $coupon['platform'];
                    $list['created_by'] = $coupon['created_by'];
                    $list['user_type'] = $coupon['user_type'];
                    $list['updated_by'] = $coupon['updated_by'];
                    $list['created_at'] = $coupon['created_at'];
                    $list['updated_at'] = $coupon['updated_at'];

                    $coupon_list[] = $list;
                }

                return response()->json(['httpcode'=>200,'success'=>'Coupon List!','data'=>$coupon_list]);
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