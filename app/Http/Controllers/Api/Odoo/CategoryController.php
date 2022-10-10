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
use App\Models\Language;
use App\Models\CmsContent;
use App\Models\Store;
use App\Models\Admin;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


use App\Rules\Name;
use Validator;

class CategoryController extends Controller
{
    
	public function list(){ 
		$datas = Category::where('is_deleted','0')->orderby('category_id','desc')->get();			
			if($datas){
				$category=[];
				foreach($datas as $data){
				$cat['category_id'] = $data->category_id;
				$cat['odoo_id'] = $data->odoo_id;
				$cat['platform'] = $data->platform;
				$cat['cat_name'] = get_content($data->cat_name_cid,$lang="");
				$cat['cat_desc'] = get_content($data->cat_desc_cid,$lang="");
				$cat['slug'] = $data->slug;
				$cat['local_name'] =$data->local_name;
				$cat['image'] = $data->image;
				$cat['sort_order'] =$data->sort_order;
				$cat['is_active'] =$data->is_active;
				$cat['is_rating'] =$data->is_rating;
				$cat['is_deleted'] =$data->is_deleted;
				$category[]=$cat;
				}
				return array('httpcode'=>'200','status'=>'success','message'=>'Success','Categories'=>$category);

			}else{
				return array('httpcode'=>'400','status'=>'error','message'=>"Not Found",'data'=>"Product Prices Not found");

			}
	}
	public function delete($id){ 
	$exist = Category::where('category_id',$id)->where('is_deleted','0')->first();			
			if($exist){
				Category::where('category_id',$id)->update(['is_deleted'=>1]);
				return array('httpcode'=>'200','status'=>'success','message'=>'Success','data'=>"Deleted Successfully");

			}else{
				return array('httpcode'=>'400','status'=>'error','message'=>"Not Found",'data'=>"Category Not Found");

			}
	}
	public function update(Request $request,$id){ 
        $input = $request->all();
		//dd($input);
		$rules      =   array();
        $rules['category_name']    = 'required|string|unique:category,cat_name,'.$id.',category_id';
        $rules['category_description']    = 'required|string';
        $rules['local_name']        = 'required|string|unique:category,cat_name,'.$id.',category_id';
        $rules['odoo_id']        = 'required|numeric|unique:category,odoo_id,'.$id.',category_id';
		$rules['is_rating']        = 'required|string';
        $rules['image']      = 'required|image|mimes:jpeg,png,jpg,gif,svg';
        $rules['is_active']      = 'required|in:0,1';
        $validator  =   Validator::make($request->all(), $rules);
		if($validator->fails()){
			foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
			return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
		} else {
			$exist = Category::where('category_id',$id)->where('is_deleted','0')->where('is_active','1')->first();			
			if($exist){
			$language=Language::where('is_default','1')->where('is_deleted','0')->where('is_active','1')->first();	
			$langID=$language->id;
			$exist = Category::where('category_id',$id)->where('is_deleted','0')->where('is_active','1')->first();			
			$cat_title_id=$exist->cat_name_cid;
			$cat_desc_id=$exist->cat_desc_cid;
			$sort_order=$exist->sort_order;
			if (DB::table('cms_content')->where('cnt_id', $cat_title_id)->where('lang_id', $langID)->exists()) {
                DB::table('cms_content')
                ->where('cnt_id', $cat_title_id)->where('lang_id', $langID)
                ->update(['content' => $input['category_name']]);
                $cat_cid=$cat_title_id;
            }else if (DB::table('cms_content')->where('cnt_id', $cat_title_id)->exists()) 
            {
				DB::table('cms_content')->insertGetId(
                    [ 'lang_id' => $langID,'cnt_id'=>$cat_title_id,'content' => $input['category_name'],'is_active'=>1,'created_by'=>1,'updated_by'=>1,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
                );
                $cat_cid=$cat_title_id;
			}
            else
            {
                $latest = DB::table('cms_content')->orderBy('cnt_id', 'DESC')->first();
                $latest_cat_cid=++$latest->cnt_id;
                 DB::table('cms_content')->insertGetId(
                    ['org_id' => 1, 'lang_id' => $langID,'cnt_id'=>$latest_cat_cid,'content' => $input['category_description'],'is_active'=>1,'created_by'=>1,'updated_by'=>1,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
                );
                $cat_cid =$latest_cat_cid;
            }
            //update category desc
            if (DB::table('cms_content')->where('cnt_id', $cat_desc_id)->where('lang_id', $langID)->exists()) {
                 DB::table('cms_content')
                ->where('cnt_id', $cat_desc_id)->where('lang_id', $langID)
                ->update(['content' => $input['category_description']]);
                $cat_desc_cid=$cat_desc_id;
            }else if (DB::table('cms_content')->where('cnt_id', $cat_desc_id)->exists()) 
            {
				DB::table('cms_content')->insertGetId(
                    [ 'lang_id' => $request->language,'cnt_id'=>$cat_desc_id,'content' => $request->category_description,'is_active'=>1,'created_by'=>1,'updated_by'=>1,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
                );
                $cat_desc_cid=$cat_desc_id;
			}
            else
            {
                $latest = DB::table('cms_content')->orderBy('cnt_id', 'DESC')->first();
                $latest_desc_cid=++$latest->cnt_id;
                DB::table('cms_content')->insertGetId(
                    ['org_id' => 1, 'lang_id' => $langID,'cnt_id'=>$latest_desc_cid,'content' => $input['category_description'],'is_active'=>1,'created_by'=>1,'updated_by'=>1,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
                );
                $cat_desc_cid = $latest_desc_cid;
            }
			
			
			
			
			if($request->hasFile('image')){ 
				$file=$request->file('image');
				//dd($file);
				$extention=$file->getClientOriginalExtension();
				$filename=time().'.'.$extention;
				$file->move(('storage/app/public/category/'),$filename);
			}else{
				$filename=$exist->image;
			}
			
			
            $cat['cat_name_cid'] = $cat_cid;
            $cat['cat_name'] =$input['category_name'];
            $cat['odoo_id'] =$input['odoo_id'];
            $cat['platform'] ="odoo";
            $cat['slug'] = $this->slugify($input['category_name']);
            $cat['local_name'] =$input['local_name'];
            $cat['cat_desc_cid'] = $cat_desc_cid;
            $cat['image'] = $filename;
            $cat['sort_order'] =$sort_order;
            $cat['is_active'] =$input['is_active'];
            $cat['is_rating'] =$input['is_rating'];
            $cat['is_deleted'] =0;
            $cat['created_by'] =1;
            $cat['modified_by'] =1;
            $cat['updated_at']= date("Y-m-d H:i:s");
			Category::where('category_id',$id)->update($cat);
			$category=Category::find($id);
			return array('httpcode'=>'200','status'=>'success','message'=>'Success','data'=>['Category' =>$category]);
		}else{
			return array('httpcode'=>'400','status'=>'error','message'=>"Not Found",'data'=>"Category Not Found");
		}
		}
		
		
	}	
	public function create(Request $request){ 
	   // dd();
        $input = $request->all();
		$rules      =   array();
        $rules['category_name']    = 'required|string|unique:category,cat_name';
        $rules['category_description']    = 'required|string';
		$rules['odoo_id']        = 'required|numeric|unique:category,odoo_id';
        $rules['local_name']        = 'required|string|unique:category,local_name';
        $rules['is_rating']        = 'required|string';
        $rules['image']      = 'required|image|mimes:jpeg,png,jpg,gif,svg';
        $rules['is_active']      = 'required|in:0,1';
        $validator  =   Validator::make($request->all(), $rules);
		if($validator->fails()){
			foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
			return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
		} else { 
			$latest = DB::table('cms_content')->orderBy('id', 'DESC')->first();
            $latest_cat_cid=++$latest->cnt_id;
            $latest_desc_cid =$latest_cat_cid+1;
			$language=Language::where('is_default','1')->where('is_deleted','0')->where('is_active','1')->first();	
			$langID=$language->id;
			$cat_cid = CmsContent::create(
                ['org_id' => 1, 'lang_id' => $langID,'cnt_id'=>$latest_cat_cid,'content' => $input['category_name'],'is_active'=>1,'created_by'=>1,'updated_by'=>1,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")])->id;
            $cat_desc_cid = CmsContent::create(
                ['org_id' => 1, 'lang_id' => $langID,'cnt_id'=>$latest_desc_cid,'content' => $input['category_description'],'is_active'=>1,'created_by'=>1,'updated_by'=>1,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")])->id;
			if($request->hasFile('image')){ 
				$file=$request->file('image');
				//dd($file);
				$extention=$file->getClientOriginalExtension();
				$filename=time().'.'.$extention;
				$file->move(('storage/app/public/category/'),$filename);
			}else{
				$filename="";
			}
			$sort_order=Category::max('sort_order');
			$category=Category::create([
            'cat_name_cid' => $latest_cat_cid,
            'odoo_id' => $input['odoo_id'],
            'platform' => "odoo",
            'cat_name'=>$input['category_name'],
            'slug' => $this->slugify($input['category_name']),
            'local_name'=>$input['local_name'],
            'cat_desc_cid' => $latest_desc_cid,
            'image' => $filename,
            'sort_order'=>$sort_order+1,
            'is_active'=>$input['is_active'],
            'is_rating'=>$input['is_rating'],
            'is_deleted'=>0,
            'created_by'=>1,
            'modified_by'=>1,
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
			])->category_id;
			
		return array('httpcode'=>'200','status'=>'success','message'=>'Success','data'=>['insert_id' =>$category]);
		}
		
	}	
	public  function slugify($text, string $divider = '-'){
	  // replace non letter or digits by divider
	  $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

	  // transliterate
	  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

	  // remove unwanted characters
	  $text = preg_replace('~[^-\w]+~', '', $text);

	  // trim
	  $text = trim($text, $divider);

	  // remove duplicate divider
	  $text = preg_replace('~-+~', $divider, $text);

	  // lowercase
	  $text = strtolower($text);

	  if (empty($text)) {
		return 'n-a';
	  }

	  return $text;
	}

   
}
