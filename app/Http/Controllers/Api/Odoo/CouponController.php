<?php

namespace App\Http\Controllers\Api\Odoo;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Session;
use DB;
use App\Models\Coupon;
use App\Models\CouponHist;
use App\Models\Category;
use App\Models\Store;
use App\Models\Admin;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


use App\Rules\Name;
use Validator;

class CouponController extends Controller
{
    
	
	public function delete($id){ 
	$exist = Coupon::where('id',$id)->where('is_deleted','0')->first();			
			if($exist){
				Coupon::where('id',$id)->update(['is_deleted'=>1]);
				return array('httpcode'=>'200','status'=>'success','message'=>'Success','data'=>"Deleted Successfully");

			}else{
				return array('httpcode'=>'400','status'=>'error','message'=>"Not Found",'data'=>"Coupon Not Found");

			}
	}
	
	public function list(){ 
		$datas = Coupon::where('is_deleted','0')->orderby('id','desc')->get();			
			if($datas){
				$couponlist=[];
				foreach($datas as $data){
				$coupon['id'] = $data->id;
				$coupon['platform'] = $data->platform;
				$coupon['odoo_id'] = $data->odoo_id;
				$coupon['coupon_title'] = get_content($data->cpn_title_cid,$lang="");
				$coupon['coupon_desc'] =  get_content($data->cpn_title_cid,$lang="");
				$coupon['ofr_code'] = $data->ofr_code;
				$coupon['category_id'] =$data->category_id;
				$coupon['subcategory_id'] = $data->subcategory_id;
				$coupon['purchase_type'] =$data->purchase_type;
				$coupon['purchase_number'] = $data->purchase_number;
				$coupon['purchase_amount'] =$data->purchase_amount;
				$coupon['ofr_value_type'] =$data->ofr_value_type;
				$coupon['ofr_value'] =$data->ofr_value;
				$coupon['ofr_type'] =$data->ofr_type;
				$coupon['ofr_min_amount'] =$data->ofr_min_amount;
				$coupon['validity_type'] =$data->validity_type;
				$coupon['valid_from'] =$data->valid_from;
				$coupon['valid_to'] =$data->valid_to;
				$coupon['valid_days'] =$data->valid_days;
				$coupon['image'] =$data->image;
				$couponlist[]=$coupon;
				}
				return array('httpcode'=>'200','status'=>'success','message'=>'Success','Coupons'=>$couponlist);

			}else{
				return array('httpcode'=>'400','status'=>'error','message'=>"Not Found",'data'=>"Coupons Not found");

			}
	}
	public function update(Request $request,$id){ 
        $input = $request->all();
		$rules      =   array();
        $rules['coupon_title']    = 'required|string';
        $rules['coupon_desc']    = 'required|string';
        $rules['ofr_code']        = 'required|string|unique:coupon,ofr_code,'.$id.',id';
        $rules['odoo_id']        = 'required|numeric|unique:coupon,odoo_id,'.$id.',id';
        $rules['category_id']     = 'nullable|numeric';
        $rules['subcategory_id']  = 'nullable|numeric';
        $rules['purchase_type']   = 'required|in:number,amount';
        $rules['purchase_number'] = 'required_if:purchase_type,==,number';
        $rules['purchase_amount'] = 'required_if:purchase_type,==,amount';
        $rules['ofr_value_type']  = 'required|in:percentage,amount';
        $rules['ofr_value']       = 'required|numeric';
        $rules['ofr_type']        = 'required|in:cashback,discount';
        $rules['ofr_min_amount']  = 'numeric|nullable';
        $rules['validity_type']   = 'required|in:range,days';
        $rules['valid_from']      = 'required_if:validity_type,==,range';
        $rules['valid_to']        = 'required_if:purchase_type,==,range';
        $rules['valid_days']      = 'required_if:purchase_type,==,days';
        $rules['file']      = 'image|mimes:jpeg,png,jpg,gif,svg';
        $rules['is_active']      = 'required|in:0,1';
        $validator  =   Validator::make($request->all(), $rules);
		if($validator->fails()){
			foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
			return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
		} else {
			$existcoupon=Coupon::where('id',$id)->where('is_deleted',0)->first();
			if($existcoupon){
			if(DB::table('cms_content')->where('cnt_id',$existcoupon->cpn_title_cid)->exists()) {
			DB::table('cms_content')->where('cnt_id',$existcoupon->cpn_title_cid)
			->update(['content' => $input['coupon_title']]);
			$cpn_title_cid=$existcoupon->cpn_title_cid;
			} else {
				$latest = DB::table('cms_content')->orderBy('cnt_id', 'DESC')->first();
				$cpn_title_cid=++$latest->cnt_id;
				DB::table('cms_content')->insertGetId([
				'org_id' => 1, 
				'lang_id' => $input['glo_lang_cid'],
				'cnt_id'=>$cpn_title_cid,
				'content' => $input['coupon_title'],
				'is_active'=>1,
				'created_by'=>auth()->user()->id,
				'updated_by'=>auth()->user()->id,
				'is_deleted'=>0,
				'created_at'=>date("Y-m-d H:i:s"),
				'updated_at'=>date("Y-m-d H:i:s")
			]);
			}

		   if (DB::table('cms_content')->where('cnt_id',$existcoupon->cpn_desc_cid)->exists()) {
			DB::table('cms_content')->where('cnt_id',$existcoupon->cpn_desc_cid)
			->update(['content' => $input['coupon_desc']]);
			$cpn_desc_cid=$existcoupon->cpn_desc_cid;
			} else {

			$latest = DB::table('cms_content')->orderBy('cnt_id', 'DESC')->first();
			$cpn_desc_cid=++$latest->cnt_id;
			DB::table('cms_content')->insertGetId([
			'org_id' => 1, 
			'lang_id' => $input['glo_lang_cid'],
			'cnt_id'=>$cpn_desc_cid,
			'content' => $input['coupon_desc'],
			'is_active'=>1,
			'created_by'=>auth()->user()->id,
			'updated_by'=>auth()->user()->id,
			'is_deleted'=>0,
			'created_at'=>date("Y-m-d H:i:s"),
			'updated_at'=>date("Y-m-d H:i:s")
			]);
			

			}
			
			if($input['subcategory_id'] =="") { $input['subcategory_id']=0;}
			if($input['purchase_amount'] =="") { $input['purchase_amount']=0;}
			if($input['purchase_number'] =="") { $input['purchase_number']=0;}
			if($input['valid_from'] =="") { $input['valid_from']=null;}
			if($input['valid_to'] =="") { $input['valid_to']=null;}
			if($input['valid_days'] =="") { $input['valid_days']=0;}
			if($cpn_desc_cid !="" && $cpn_title_cid !="" && $id !="") {

				$coupon =  Coupon::where('id',$id)->update([
				'org_id' => 1, 
				'cpn_title_cid' => $cpn_title_cid,
				'cpn_desc_cid' => $cpn_desc_cid,
				'odoo_id' => $input['odoo_id'],
				'platform' => "odoo",
				'category_id'=>$input['category_id'],
				'subcategory_id'=>$input['subcategory_id'],
				'purchase_type'=>$input['purchase_type'],
				'purchase_number'=>$input['purchase_number'],
				'purchase_amount'=>$input['purchase_amount'],
				'ofr_value_type'=>$input['ofr_value_type'],
				'ofr_value'=>$input['ofr_value'],
				'ofr_type'=>$input['ofr_type'],
				'ofr_code'=>$input['ofr_code'],
				'ofr_min_amount'=>$input['ofr_min_amount'],
				'validity_type'=>$input['validity_type'],
				'valid_from'=>$input['valid_from'],
				'valid_to'=>$input['valid_to'],
				'valid_days'=>$input['valid_days'],
				'is_active'=>$input['is_active'],
				'is_deleted'=>0,
				'updated_by'=>1,
				'updated_at'=>date("Y-m-d H:i:s")

				]); 

				if($request->hasFile('image')){ 
								$image = $request->file('image'); 
								$imgName            =   rand(99,999).time().'.'.$image->extension();
								$path               =   '/app/public/coupon/'.$id;
								$img                =   Image::make($image->path()); 
								$destinationPath    =   storage_path($path); 
								$image->move($destinationPath.'/', $imgName);
								$imgUpload          =   uploadFile($path,$imgName);
								$img_value = $path.'/'.$imgName;
								//echo $img_value;die;
								$coupon_img =  Coupon::where('id',$id)->update(['image'=>$img_value]);
				}
				$Coupon=Coupon::find($id);
				return array('httpcode'=>'200','status'=>'success','message'=>'Success','data'=>['coupon_id' =>$Coupon]);
				}
			}else{
				return array('httpcode'=>'400','status'=>'error','message'=>'coupon not found');
			}
		}
    }
	public function create(Request $request){ 
        $input = $request->all();
		$rules      =   array();
        $rules['coupon_title']    = 'required|string';
        $rules['coupon_desc']    = 'required|string';
        $rules['ofr_code']        = 'required|string|unique:coupon,ofr_code';
        $rules['odoo_id']        = 'required|numeric|unique:coupon,odoo_id';
        $rules['category_id']     = 'nullable|numeric';
        $rules['subcategory_id']  = 'nullable|numeric';
        $rules['purchase_type']   = 'required|in:number,amount';
        $rules['purchase_number'] = 'required_if:purchase_type,==,number';
        $rules['purchase_amount'] = 'required_if:purchase_type,==,amount';
        $rules['ofr_value_type']  = 'required|in:percentage,amount';
        $rules['ofr_value']       = 'required|numeric';
        $rules['ofr_type']        = 'required|in:cashback,discount';
        $rules['ofr_min_amount']  = 'numeric|nullable';
        $rules['validity_type']   = 'required|in:range,days';
        $rules['valid_from']      = 'required_if:validity_type,==,range';
        $rules['valid_to']        = 'required_if:purchase_type,==,range';
        $rules['valid_days']      = 'required_if:purchase_type,==,days';
        $rules['file']      = 'image|mimes:jpeg,png,jpg,gif,svg';
        $rules['is_active']      = 'required|in:0,1';
        $validator  =   Validator::make($request->all(), $rules);
		if($validator->fails()){
			foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
			return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
		} else { 
			$latest = DB::table('cms_content')->orderBy('id', 'DESC')->first();
			$cpn_name_cid=++$latest->cnt_id;
			$cpn_desc_cid =$cpn_name_cid+1;

			$cpn_name= DB::table('cms_content')->insertGetId([
			'org_id' => 1, 
			'lang_id' => 1,
			'cnt_id'=>$cpn_name_cid,
			'content' => $input['coupon_title'],
			'is_active'=>1,
			'created_by'=>1,
			'updated_by'=>1,
			'is_deleted'=>0,
			'created_at'=>date("Y-m-d H:i:s"),
			'updated_at'=>date("Y-m-d H:i:s")
			]);


			$cpn_desc= DB::table('cms_content')->insertGetId([
			'org_id' => 1, 
			'lang_id' => 1,
			'cnt_id'=>$cpn_desc_cid,
			'content' => $input['coupon_desc'],
			'is_active'=>1,
			'created_by'=>1,
			'updated_by'=>1,
			'is_deleted'=>0,
			'created_at'=>date("Y-m-d H:i:s"),
			'updated_at'=>date("Y-m-d H:i:s")
			]);
			if($input['subcategory_id'] =="") { $input['subcategory_id']=0;}
			if($input['purchase_amount'] =="") { $input['purchase_amount']=0;}
			if($input['purchase_number'] =="") { $input['purchase_number']=0;}
			if($input['valid_days'] =="") { $input['valid_days']=0;}
			if($cpn_name !="" && $cpn_desc !="") {
				$coupon =  Coupon::create([
				'org_id' => 1, 
				'cpn_title_cid' => $cpn_name_cid,
				'cpn_desc_cid' => $cpn_desc_cid,
				'odoo_id' => $input['odoo_id'],
				'platform' => "odoo",
				'category_id'=>$input['category_id'],
				'subcategory_id'=>$input['subcategory_id'],
				'purchase_type'=>$input['purchase_type'],
				'purchase_number'=>$input['purchase_number'],
				'purchase_amount'=>$input['purchase_amount'],
				'ofr_value_type'=>$input['ofr_value_type'],
				'ofr_value'=>$input['ofr_value'],
				'ofr_type'=>$input['ofr_type'],
				'ofr_code'=>$input['ofr_code'],
				'ofr_min_amount'=>$input['ofr_min_amount'],
				'validity_type'=>$input['validity_type'],
				'valid_from'=>$input['valid_from'],
				'valid_to'=>$input['valid_to'],
				'valid_days'=>$input['valid_days'],
				'is_active'=>$input['is_active'],
				'is_deleted'=>0,
				'created_by'=>1,
				'user_type'=>'admin',
				'updated_by'=>1,
				'created_at'=>date("Y-m-d H:i:s"),
				'updated_at'=>date("Y-m-d H:i:s")

				]);   
				$lastId = $coupon->id;
				if($lastId) {
					if($request->hasFile('image')){ 
									$image = $request->file('image'); 
									$imgName            =   rand(99,999).time().'.'.$image->extension();
									$path               =   '/app/public/coupon/'.$lastId;
									$img                =   Image::make($image->path()); 
									$destinationPath    =   storage_path($path); 
									$image->move($destinationPath.'/', $imgName);
									$imgUpload          =   uploadFile($path,$imgName);
									$img_value = $path.'/'.$imgName;
					$coupon_img =  Coupon::where('id',$lastId)->update(['image'=>$img_value]);
									}
					return array('httpcode'=>'200','status'=>'success','message'=>'Success','data'=>['coupon_id' =>$lastId]);
				}
			}
		}
    }

   
}
