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
use App\Models\Subcategory;
use App\Models\SubcategoryList;
use App\Models\Language;
use App\Models\CmsContent;
use App\Models\Store;
use App\Models\Admin;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


use App\Rules\Name;
use Validator;

class SubCategoryController extends Controller
{
	public function list(){ 
		$datas = Subcategory::where('is_deleted','0')->orderby('subcategory_id','desc')->get();			
			if($datas){
				$category=[];
				foreach($datas as $data){
				$cat['subcategory_id'] = $data->subcategory_id;
				$cat['category_id'] = $data->category_id;
				$cat['odoo_id'] = $data->odoo_id;
				$cat['platform'] = $data->platform;
				$cat['subcategory_name'] =get_content($data->sub_name_cid,$lang="");
				$cat['subcategory_desc'] =get_content($data->desc_cid,$lang="");
				$cat['slug'] = $data->slug;
				$cat['image'] = $data->image;
				$cat['sort_order'] =$data->sort_order;
				$cat['is_active'] =$data->is_active;
				$cat['is_rating'] =$data->is_rating;
				$cat['is_deleted'] =$data->is_deleted;
				$category[]=$cat;
				}
				return array('httpcode'=>'200','status'=>'success','message'=>'Success','Subcategories'=>$category);

			}else{
				return array('httpcode'=>'400','status'=>'error','message'=>"Not Found",'data'=>"Product Prices Not found");

			}
	}
    public function delete($id){ 
	$exist = Subcategory::where('subcategory_id',$id)->where('is_deleted','0')->first();			
			if($exist){
				
				Subcategory::where('subcategory_id',$id)->update(['is_deleted'=>1]);
				SubcategoryList::where('id',$exist->sabcatlist_id)->update(['is_deleted'=>1]);
				return array('httpcode'=>'200','status'=>'success','message'=>'Success','data'=>"Deleted Successfully");

			}else{
				return array('httpcode'=>'400','status'=>'error','message'=>"Not Found",'data'=>"Subcategory Not Found");

			}
	}
	public function update(Request $request,$id){ 
        $input = $request->all();
		//dd($input);
		$rules      =   array();
        $rules['category_id']    = 'required|numeric';
        $rules['subcategory_name']    = 'required|string';
        $rules['subcategory_description']    = 'required|string';
		$rules['odoo_id']        = 'required|numeric|unique:subcategory,odoo_id,'.$id.',subcategory_id';
        $rules['image']      = 'required|image|mimes:jpeg,png,jpg,gif,svg';
        $rules['is_active']      = 'required|in:0,1';
        $validator  =   Validator::make($request->all(), $rules);
		if($validator->fails()){
			foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
			return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
		} else {
			$exist = Subcategory::where('subcategory_id',$id)->where('is_deleted','0')->where('is_active','1')->first();			
			if($exist){
			$checkexistname=SubcategoryList::where('name',$input['subcategory_name'])->where('id','<>',$exist->sabcatlist_id)->where('is_active','1')->first();			
			if($checkexistname)	{
				return array('httpcode'=>'400','status'=>'error','message'=>'subcategory name already exist');
			}else{
			$language=Language::where('is_default','1')->where('is_deleted','0')->where('is_active','1')->first();	
			$langID=$language->id;
			$cat_title_id=$exist->cat_name_cid;
			$cat_desc_id=$exist->cat_desc_cid;
			$sort_order=$exist->sort_order;
			if (DB::table('cms_content')->where('cnt_id', $cat_title_id)->where('lang_id', $langID)->exists()) {
                DB::table('cms_content')
                ->where('cnt_id', $cat_title_id)->where('lang_id', $langID)
                ->update(['content' => $input['subcategory_name']]);
                $subcat_cid=$cat_title_id;
            }else if (DB::table('cms_content')->where('cnt_id', $cat_title_id)->exists()) 
            {
				DB::table('cms_content')->insertGetId(
                    [ 'lang_id' => $langID,'cnt_id'=>$cat_title_id,'content' => $input['subcategory_name'],'is_active'=>1,'created_by'=>1,'updated_by'=>1,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
                );
                $subcat_cid=$cat_title_id;
			}
            else
            {
                $latest = DB::table('cms_content')->orderBy('cnt_id', 'DESC')->first();
                $latest_cat_cid=++$latest->cnt_id;
                 DB::table('cms_content')->insertGetId(
                    ['org_id' => 1, 'lang_id' => $langID,'cnt_id'=>$latest_cat_cid,'content' => $input['subcategory_name'],'is_active'=>1,'created_by'=>1,'updated_by'=>1,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
                );
                $subcat_cid =$latest_cat_cid;
            }
            //update category desc
            if (DB::table('cms_content')->where('cnt_id', $cat_desc_id)->where('lang_id', $langID)->exists()) {
                 DB::table('cms_content')
                ->where('cnt_id', $cat_desc_id)->where('lang_id', $langID)
                ->update(['content' => $input['category_description']]);
                $subcat_desc_cid=$cat_desc_id;
            }else if (DB::table('cms_content')->where('cnt_id', $cat_desc_id)->exists()) 
            {
				DB::table('cms_content')->insertGetId(
                    [ 'lang_id' => $request->language,'cnt_id'=>$cat_desc_id,'content' => $input['subcategory_description'],'is_active'=>1,'created_by'=>1,'updated_by'=>1,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
                );
                $subcat_desc_cid=$cat_desc_id;
			}
            else
            {
                $latest = DB::table('cms_content')->orderBy('cnt_id', 'DESC')->first();
                $latest_desc_cid=++$latest->cnt_id;
                DB::table('cms_content')->insertGetId(
                    ['org_id' => 1, 'lang_id' => $langID,'cnt_id'=>$latest_desc_cid,'content' => $input['subcategory_description'],'is_active'=>1,'created_by'=>1,'updated_by'=>1,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
                );
                $subcat_desc_cid = $latest_desc_cid;
            }
			
			
			
			
			if($request->hasFile('image')){ 
				$file=$request->file('image');
				//dd($file);
				$extention=$file->getClientOriginalExtension();
				$filename=time().'.'.$extention;
				$file->move(('storage/app/public/subcategory/'),$filename);
			}else{
				$filename=$exist->image;
			}
			
			$SubcategoryList=SubcategoryList::where('id',$exist->sabcatlist_id)->Update([
            'name'=>$input['subcategory_name'],
            'code' => "",
            'is_active'=>1,
			'odoo_id' => $input['odoo_id'],
            'platform' => "odoo",
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
			]);
			
            Subcategory::where('subcategory_id',$id)->Update([
            'category_id'=>$input['category_id'],
            'sub_name_cid' => $subcat_cid,
            'subcategory_name'=>$input['subcategory_name'],
            'code'=>"",
			'odoo_id' => $input['odoo_id'],
            'platform' => "odoo",
            'sabcatlist_id'=>$exist->sabcatlist_id,
            'slug' => $this->slugify($input['subcategory_name']),
            'desc_cid' => $subcat_desc_cid,
            'image' => $filename,
            'parent'=> 0,
            'level'=>0,
            'is_active'=>1,
            'is_deleted'=>0,
            'created_by'=>1,
            'modified_by'=>1,
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
			]);
			$Subcategory=Subcategory::find($id);
			
			return array('httpcode'=>'200','status'=>'success','message'=>'Success','data'=>['Category' =>$Subcategory]);
			}
		}else{
			return array('httpcode'=>'400','status'=>'error','message'=>"Not Found",'data'=>"Category Not Found");
		}
		}
		
		
	}	
	public function create(Request $request){ 
        $input = $request->all();
		$rules      =   array();
        $rules['category_id']    = 'required|numeric';
		$rules['odoo_id']        = 'required|numeric|unique:subcategory_list,odoo_id';
        $rules['subcategory_name']    = 'required|string|unique:subcategory_list,name';
        $rules['subcategory_description']    = 'required|string';
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
                ['org_id' => 1, 'lang_id' => $langID,'cnt_id'=>$latest_cat_cid,'content' => $input['subcategory_name'],'is_active'=>1,'created_by'=>1,'updated_by'=>1,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")])->id;
            $cat_desc_cid = CmsContent::create(
                ['org_id' => 1, 'lang_id' => $langID,'cnt_id'=>$latest_desc_cid,'content' => $input['subcategory_description'],'is_active'=>1,'created_by'=>1,'updated_by'=>1,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")])->id;
			if($request->hasFile('image')){ 
				$file=$request->file('image');
				//dd($file);
				$extention=$file->getClientOriginalExtension();
				$filename=time().'.'.$extention;
				$file->move(('storage/app/public/subcategory/'),$filename);
			}else{
				$filename="";
			}
			$sort_order=Subcategory::max('sort_order');
			$SubcategoryList=SubcategoryList::create([
            'name'=>$input['subcategory_name'],
            'code' => "",
            'is_active'=>1,
			'odoo_id' => $input['odoo_id'],
            'platform' => "odoo",
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
			])->id;
			$Subcategory=Subcategory::create([
            'category_id'=>$input['category_id'],
            'sub_name_cid' => $latest_cat_cid,
            'subcategory_name'=>$input['subcategory_name'],
			'odoo_id' => $input['odoo_id'],
            'platform' => "odoo",
            'code'=>"",
            'sabcatlist_id'=>$SubcategoryList,
            'slug' => $this->slugify($input['subcategory_name']),
            'desc_cid' => $latest_desc_cid,
            'image' => $filename,
            'parent'=> 0,
            'level'=>0,
            'is_active'=>1,
            'is_deleted'=>0,
            'created_by'=>1,
            'modified_by'=>1,
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
			])->subcategory_id;
			
		return array('httpcode'=>'200','status'=>'success','message'=>'Success','data'=>['insert_id' =>$Subcategory]);
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
