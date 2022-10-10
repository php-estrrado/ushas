<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Session;
use DB;
use App\Models\Brand;

use App\Models\Admin;


use App\Rules\Name;
use Validator;

class BrandController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
   
    
    // user roles and modules
    
    public function brands()
        { 
        $data['title']              =   'Brands';
        $data['menu']               =   'brands';
        $data['brands']              =   Brand::getBrands();
        // dd($data);
        return view('admin.brands.list',$data);
        }

        public function createBrand()
        { 
        $data['title']              =   'Create Brand';
        $data['menu']               =   'create-brand';
        $data['language']      =    DB::table('glo_lang_lk')->where('is_active', 1)->get();
        // dd($data);
        return view('admin.brands.create',$data);
        }

        public function editBrand($brand_id)
        { 
        $data['title']              =   'Edit Brand';
        $data['menu']               =   'edit-brand';
        $data['brand']              =  Brand::getBrand($brand_id);
        $data['language']           =    DB::table('glo_lang_lk')->where('is_active', 1)->get();
        // dd($data);
        return view('admin.brands.edit',$data);
        }
         public function viewBrand($brand_id)
        { 
        $data['title']              =   'View Brand';
        $data['menu']               =   'view-brand';
        $data['brand']              =  Brand::getBrand($brand_id);
        $data['language']           =    DB::table('glo_lang_lk')->where('is_active', 1)->get();
        // dd($data);
        return view('admin.brands.view',$data);
        }

        public function brandSave(Request $request)
        { 
        $input = $request->all();
        // dd($input);


        if($input['id']>0){

        $validator= $request->validate([
        'brand_name'   =>  ['required'],
        'brand_desc' => ['required']
        ], [], 
        [
        'brand_name' => 'Brand Name',
        'brand_desc' => 'Brand Description'
        ]);


        if (DB::table('cms_content')->where('cnt_id',$input['brand_name_cid'])->where('lang_id',$input['glo_lang_cid'])->exists()) {
        DB::table('cms_content')->where('cnt_id',$input['brand_name_cid'])->where('lang_id',$input['glo_lang_cid'])
        ->update(['content' => $input['brand_name']]);
        $brand_name_cid=$input['brand_name_cid'];
        } else {

        $latest = DB::table('cms_content')->orderBy('cnt_id', 'DESC')->first();
        $brand_name_cid=++$latest->cnt_id;
        DB::table('cms_content')->insertGetId([
        'org_id' => 1, 
        'lang_id' => $input['glo_lang_cid'],
        'cnt_id'=>$brand_name_cid,
        'content' => $input['brand_name'],
        'is_active'=>1,
        'created_by'=>auth()->user()->id,
        'updated_by'=>auth()->user()->id,
        'is_deleted'=>0,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")
        ]);
        $brand_name_cid =$brand_name_cid;

        }

       if (DB::table('cms_content')->where('cnt_id',$input['brand_desc_cid'])->where('lang_id',$input['glo_lang_cid'])->exists()) {
        DB::table('cms_content')->where('cnt_id',$input['brand_desc_cid'])->where('lang_id',$input['glo_lang_cid'])
        ->update(['content' => $input['brand_desc']]);
        $brand_desc_cid=$input['brand_desc_cid'];
        } else {

        $latest = DB::table('cms_content')->orderBy('cnt_id', 'DESC')->first();
        $brand_desc_cid=++$latest->cnt_id;
        DB::table('cms_content')->insertGetId([
        'org_id' => 1, 
        'lang_id' => $input['glo_lang_cid'],
        'cnt_id'=>$brand_desc_cid,
        'content' => $input['brand_desc'],
        'is_active'=>1,
        'created_by'=>auth()->user()->id,
        'updated_by'=>auth()->user()->id,
        'is_deleted'=>0,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")
        ]);
        $brand_desc_cid =$brand_desc_cid;

        }
        $brand_id = $input['id'];
        if($brand_desc_cid !="" && $brand_name_cid !="" && $brand_id !="") {

        $brand =  Brand::where('id',$brand_id)->update([
        'org_id' => 1, 
        'brand_name_cid' => $brand_name_cid,
        'brand_desc_cid' => $brand_desc_cid,
        'is_active'=>$input['is_active'],
        'is_deleted'=>0,
        'updated_by'=>auth()->user()->id,
        'updated_at'=>date("Y-m-d H:i:s")

        ]); 
        Session::flash('message', ['text'=>'Brand updated successfully','type'=>'success']); 
        }else {
        Session::flash('message', ['text'=>'Brand updation failed','type'=>'danger']);
        }


        $data['title']              =   'Brands';
        $data['menu']               =   'brands';
        $data['brands']              =   Brand::getBrands();




        }else{

        $validator= $request->validate([
        'brand_name'   =>  ['required'],
        'brand_desc' => ['required']
        ], [], 
        [
        'brand_name' => 'Brand Name',
        'brand_desc' => 'Brand Description'
        ]);


        $latest = DB::table('cms_content')->orderBy('id', 'DESC')->first();
        $brand_name_cid=++$latest->cnt_id;
        $brand_desc_cid =$brand_name_cid+1;

        $brand_name= DB::table('cms_content')->insertGetId([
        'org_id' => 1, 
        'lang_id' => $input['glo_lang_cid'],
        'cnt_id'=>$brand_name_cid,
        'content' => $input['brand_name'],
        'is_active'=>1,
        'created_by'=>auth()->user()->id,
        'updated_by'=>auth()->user()->id,
        'is_deleted'=>0,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")
        ]);


        $brand_desc= DB::table('cms_content')->insertGetId([
        'org_id' => 1, 
        'lang_id' => $input['glo_lang_cid'],
        'cnt_id'=>$brand_desc_cid,
        'content' => $input['brand_desc'],
        'is_active'=>1,
        'created_by'=>auth()->user()->id,
        'updated_by'=>auth()->user()->id,
        'is_deleted'=>0,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")
        ]);

        if($brand_name !="" && $brand_desc !="") {
        $brand =  Brand::create([
        'org_id' => 1, 
        'brand_name_cid' => $brand_name_cid,
        'brand_desc_cid' => $brand_desc_cid,
        'is_active'=>$input['is_active'],
        'is_deleted'=>0,
        'created_by'=>auth()->user()->id,
        'modified_by'=>auth()->user()->id,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")

        ]);   
        $lastId = $brand->id;
        if($lastId) {
        Session::flash('message', ['text'=>'Brand created successfully','type'=>'success']);  
        }else {
        Session::flash('message', ['text'=>'Brand creation failed','type'=>'danger']);
        }
        }else {
        Session::flash('message', ['text'=>'Brand creation failed','type'=>'danger']);
        }


        $data['title']              =   'Brands';
        $data['menu']               =   'brands';
        $data['brands']              =   Brand::getBrands();



        }
        return redirect(route('admin.brands'));

        }


        public function brandDelete(Request $request)
        {
        $input = $request->all();

        if($input['id']>0) {
        $deleted =  Brand::where('id',$input['id'])->update(array('is_deleted'=>1,'is_active'=>0));
        Session::flash('message', ['text'=>'Brand deleted successfully.','type'=>'success']);
        return true;
        }else {
        Session::flash('message', ['text'=>'Brand failed to delete.','type'=>'danger']);
        return false;
        }

        }
           public function brandStatus(Request $request)
        {
        $input = $request->all();
        
        if($input['id']>0) {
        $deleted =  Brand::where('id',$input['id'])->update(array('is_active'=>$input['status']));
        
        return '1';
        }else {
        
        return '0';
        }
        
        }
    

   
}
