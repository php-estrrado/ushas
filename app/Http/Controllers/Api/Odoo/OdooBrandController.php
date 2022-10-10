<?php

namespace App\Http\Controllers\Api\Odoo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Brand;
use DB;
use Carbon\Carbon;
use App\Models\Product;

use Validator;

class OdooBrandController extends Controller
{
    public function brand_creation(Request $request){
    $validator = Validator::make($request->all(), [
        'brand_id'=>['required_if:type,edit','numeric','min:0'],
        'unique_id'=>['required','numeric','unique:prd_brand,odoo_id,' . $request->brand_id],
        'brand_name'   =>  ['required','unique:prd_brand,name,' . $request->brand_id],
        'brand_desc' => ['required'],
        'brand_image'=>['required','image'],
        'type'=> ['required','in:create,edit'],
        'is_active'=> ['required','in:0,1'],
        'lang_id'=>['required','min:1']
        ]);


        if ($validator->passes()) {

            if($request->brand_id>0)
            {
                if($request->hasFile('brand_image'))
            {
            $file=$request->file('brand_image');
            $extention=$file->getClientOriginalExtension();
            $filename=time().'.'.$extention;
            $file->move(('uploads/storage/app/public/brands/'),$filename);
            }
            else
            {
                $filename=NULL;
            }

        $brands = Brand::where('id',$request->brand_id)->first();  
        if (DB::table('cms_content')->where('cnt_id',$brands->brand_name_cid)->where('lang_id',$request->lang_id)->exists()) {
        DB::table('cms_content')->where('cnt_id',$brands->brand_name_cid)->where('lang_id',$request->lang_id)
        ->update(['content' => $request->brand_name]);
        $brand_name_cid=$brands->brand_name_cid;
        } else {

        $latest = DB::table('cms_content')->orderBy('cnt_id', 'DESC')->first();
        $brand_name_cid=++$latest->cnt_id;
        DB::table('cms_content')->insertGetId([
        'org_id' => 1, 
        'lang_id' => $request->lang_id,
        'cnt_id'=>$brand_name_cid,
        'content' => $request->brand_name,
        'is_active'=>1,
        'created_by'=>1,
        'updated_by'=>1,
        'is_deleted'=>0,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")
        ]);
        $brand_name_cid =$brand_name_cid;

        }

       if (DB::table('cms_content')->where('cnt_id',$brands->brand_desc_cid)->where('lang_id',$request->lang_id)->exists()) {
        DB::table('cms_content')->where('cnt_id',$brands->brand_desc_cid)->where('lang_id',$request->lang_id)
        ->update(['content' => $request->brand_desc]);
        $brand_desc_cid=$brands->brand_desc_cid;
        } else {

        $latest = DB::table('cms_content')->orderBy('cnt_id', 'DESC')->first();
        $brand_desc_cid=++$latest->cnt_id;
        DB::table('cms_content')->insertGetId([
        'org_id' => 1, 
        'lang_id' => $request->lang_id,
        'cnt_id'=>$brand_desc_cid,
        'content' => $request->brand_desc,
        'is_active'=>1,
        'created_by'=>1,
        'updated_by'=>1,
        'is_deleted'=>0,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")
        ]);
        $brand_desc_cid =$brand_desc_cid;

        }
        $brand_id = $request->brand_id;

        $brand =  Brand::where('id',$brand_id)->update([
        'org_id' => 1, 
        'odoo_id' =>$request->unique_id,
        'name' =>$request->brand_name,
        'image' => $filename,
        'brand_name_cid' => $brand_name_cid,
        'brand_desc_cid' => $brand_desc_cid,
        'is_active'=>$request->is_active,
        'is_deleted'=>0,
        'updated_by'=>1,
        'updated_at'=>date("Y-m-d H:i:s")

        ]); 
        return ['httpcode'=>'200','status'=>'success','message'=>'Brand Updated Successfully'];
        }//update
        {
            if($request->hasFile('brand_image'))
            {
            $file=$request->file('brand_image');
            $extention=$file->getClientOriginalExtension();
            $filename=time().'.'.$extention;
            $file->move(('uploads/storage/app/public/brands/'),$filename);
            }
            else
            {
                $filename=NULL;
            }

        $latest = DB::table('cms_content')->orderBy('cnt_id', 'DESC')->first();
        $brand_name_cid=++$latest->cnt_id;
        DB::table('cms_content')->insertGetId([
        'org_id' => 1, 
        'lang_id' => $request->lang_id,
        'cnt_id'=>$brand_name_cid,
        'content' => $request->brand_name,
        'is_active'=>1,
        'created_by'=>1,
        'updated_by'=>1,
        'is_deleted'=>0,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")
        ]);
        $brand_name_cid =$brand_name_cid;

        $latest = DB::table('cms_content')->orderBy('cnt_id', 'DESC')->first();
        $brand_desc_cid=++$latest->cnt_id;
        DB::table('cms_content')->insertGetId([
        'org_id' => 1, 
        'lang_id' => $request->lang_id,
        'cnt_id'=>$brand_desc_cid,
        'content' => $request->lang_id,
        'is_active'=>1,
        'created_by'=>1,
        'updated_by'=>1,
        'is_deleted'=>0,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")
        ]);
        $brand_desc_cid =$brand_desc_cid;

        

        $brand =  Brand::create([
        'org_id' => 1, 
        'name' =>$request->brand_name,
        'odoo_id' =>$request->unique_id,
        'image' => $filename,
        'brand_name_cid' => $brand_name_cid,
        'brand_desc_cid' => $brand_desc_cid,
        'platform' => 'odoo',
        'is_active'=>$request->is_active,
        'is_deleted'=>0,
        'updated_by'=>1,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")])->id; 
        return ['httpcode'=>'200','status'=>'success','message'=>'Brand Created Successfully','data'=>['unique_id'=>$brand]];
        }
        
        }
        else
        {
            return ['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()];
        }

    }

    public function brand_delete(Request $request){
    $validator = Validator::make($request->all(), [
        'brand_id'=>['required','numeric']
        ]);


        if ($validator->passes()) {
            $brands = Brand::where('id',$request->brand_id)->first(); 
            if($brands)
            {
                $brands->is_deleted=1;
                $brands->save();
                return ['httpcode'=>'200','status'=>'success','message'=>'Brand deleted Successfully'];
            }
            else
            {
                return ['httpcode'=>'400','status'=>'error','error'=>'Invalid Brand'];
            }
        }
        else
        {
            return ['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()];
        }
    }
    public function brand_view(Request $request){
    $validator = Validator::make($request->all(), [
        'brand_id'=>['required','numeric']
        ]);


        if ($validator->passes()) {
            return Brand::getBrandsbyId($request->brand_id);
        }
        else
        {
            return ['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()];
        }
    }

    public function brand_list(){
            return Brand::getBrandsApi();
     }
}
