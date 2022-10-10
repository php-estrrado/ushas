<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;
use App\Models\Organization\OrganizationCategory;
use App\Models\Organization\OrganizationCmsContent;
use App\Rules\Name;
use Validator;


class CategoryController extends Controller
{
    public function __construct(){
        $this->middleware('auth:admin');
    }
	
	public function list(Request $request){ 
	$post                       =   (object)$request->post(); $usrIds = []; 
    $data['vType']              =   '';
	if(isset($post->vType)      ==  'ajax'){ $data['vType']          =   $post->vType;
		return OrganizationCategory::getListData($post); }
    else{ return view('organization.master.category.list',$data); }
		
    }

    public function category(){
        $data['title']              =   'Category';
        $data['menu']               =   'Category List';
        $data['active']             =   '';
        $data['category']           =    OrganizationCategory::where('is_deleted',NULL)->orWhere('is_deleted',0)->orderBy('id','DESC')->get();
        $data['category_sort']      =    OrganizationCategory::where('is_deleted',NULL)->orWhere('is_deleted',0)->orderBy('sort_order')->get();
        return view('organization.master.category.list',$data);
    }
    

    public function createCategory(){
		
        $data['title']         =   'Category';
        $data['menu']          =   'Category';
        $data['language']      =    tenancy()->central(function ($tenant) {
							        return DB::table('glo_lang_lk')->where('is_active', 1)->get();
									});
        return view('organization.master.category.create', $data);
    }
	
	public function catContent(Request $request){
	$post =  (object)$request->post();
	$lang_id=$post->lang_id;
	$title_id=$post->title_id;
	$desc_id=$post->desc_id;
	$data['language']      =    tenancy()->central(function ($tenant) {
							        return DB::table('glo_lang_lk')->where('is_active', 1)->get();
									});
	$data['lang_id']=$post->lang_id;
	$data['category_name']=DB::table('cms_content')->where('cnt_id', $title_id)->where('lang_id', $lang_id)->first();
    $data['category_desc']=DB::table('cms_content')->where('cnt_id', $desc_id)->where('lang_id', $lang_id)->first(); 
	return view('organization.master.category.includes.contents',$data);
	}
	public function validateCategory(Request $request){
        $existName      =  $error = false;
		$post           =  (object)$request->post(); 
        if($post->image_file ==""){
         		$rules          =   [
                'category_name' => 'required', 'string',
				'language'=> 'required',
				'category_description'=> 'required',
				'language'=> 'required',
				'category_image'=>'required','image','mimes:jpeg,png,jpg'
				];
        }else{
         	 $rules          =   [
                'category_name' => 'required', 'string',
				'language'=> 'required',
				'category_description'=> 'required',
				'language'=> 'required',
				'category_image'=>'image','mimes:jpeg,png,jpg'
              
            ];
         }
		$validator = Validator::make($request->all() ,$rules);
		if($validator->fails()) {
            foreach($validator->messages()->getMessages() as $k=>$row){ 
			$error[$k] = $row[0]; 
		}
		}
		if($error) { return $error; }else{ return 'success'; } return 'success'; 
    }
	
	public function saveCategory(Request $request){

        $post  = (object)$request->post(); 
		$input = $request->all();
		$success=0;
        $file=$request->file('category_image');
        if($request->hasFile('category_image')){
			$extention=$file->getClientOriginalExtension();
			$filename=time().'.'.$extention;
			$path  =   '/organization/category';
			$destinationPath    =   storage_path($path); 
            if(!file_exists($destinationPath)) { mkdir($destinationPath, 755, true);}
            $file->move($destinationPath, $filename);
			$filename       =   $path.'/'.$filename;
			} else{
				 $filename=$request->image_file;
			}
			if($post->cat_id){
			$success=2;	
			$cat_id = $post->cat_id;
				if (DB::table('cms_content')->where('cnt_id', $request->cat_content_id)->where('lang_id', $request->language)->exists()) {
                
				DB::table('cms_content')
                ->where('cnt_id', $request->cat_content_id)->where('lang_id', $request->language)
                ->update(['content' => $request->category_name]);
                $cat_cid=$request->cat_content_id;
            }
            else if (DB::table('cms_content')->where('cnt_id', $request->cat_content_id)->exists()) 
            {
				DB::table('cms_content')->insertGetId(
                    [ 'lang_id' => $request->language,'cnt_id'=>$request->cat_content_id,'content' => $request->category_name,'is_active'=>1,'created_by'=>auth()->user()->id,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
                );
                
			}else{	
				
                $latest = DB::table('cms_content')->orderBy('cnt_id', 'DESC')->first();
                $latest_cat_cid=++$latest->cnt_id;
                 DB::table('cms_content')->insertGetId(
                    [ 'lang_id' => $request->language,'cnt_id'=>$latest_cat_cid,'content' => $request->category_name,'is_active'=>1,'created_by'=>auth()->user()->id,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
                );
                $cat_cid =$latest_cat_cid;
            }
			
            if (DB::table('cms_content')->where('cnt_id', $request->desc_content_id)->where('lang_id', $request->language)->exists()) {
                 DB::table('cms_content')
                ->where('cnt_id', $request->desc_content_id)->where('lang_id', $request->language)
                ->update(['content' => $request->category_description]);
                $cat_desc_cid=$request->desc_content_id;
            } else if (DB::table('cms_content')->where('cnt_id', $request->desc_content_id)->exists()) 
            {
				DB::table('cms_content')->insertGetId(
                    [ 'lang_id' => $request->language,'cnt_id'=>$request->desc_content_id,'content' => $request->category_description,'is_active'=>1,'created_by'=>auth()->user()->id,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
                );
                
			}
            else
            {
                $latest = DB::table('cms_content')->orderBy('cnt_id', 'DESC')->first();
                $latest_desc_cid=++$latest->cnt_id;
                DB::table('cms_content')->insertGetId(
                    ['lang_id' => $request->language,'cnt_id'=>$latest_desc_cid,'content' => $request->category_description,'is_active'=>1,'created_by'=>auth()->user()->id,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
                );
                $cat_desc_cid = $latest_desc_cid;
            }



            OrganizationCategory::where('id',$cat_id)->update([
            'title_cid' => $request->cat_content_id,
            //'title'=>$request->category_name,
            //'slug' => $validate['category_name'],
            'desc_cid' => $request->desc_content_id,
            'image' => $filename,
            'is_active'=>$request->status,
            'is_deleted'=>0,
            'updated_by'=>auth()->user()->id,
            'updated_at'=>date("Y-m-d H:i:s")

        ]);
				
				
			}else{
			$latest = DB::table('cms_content')->orderBy('id', 'DESC')->first();
			if($latest){
				
			$latest_cat_cid=++$latest->cnt_id;
			}else{
				$latest_cat_cid=1;
			}
			$latest_desc_cid =$latest_cat_cid+1;
				$catname=$post->category_name;
				$catdesc=$post->category_description;
				$language=$post->language;

            $cat_cid = DB::table('cms_content')->insertGetId(
                [ 'lang_id' => $language,'cnt_id'=>$latest_cat_cid,'content' => $catname,'is_active'=>1,'created_by'=>auth()->user()->id,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
            );
            $cat_desc_cid = DB::table('cms_content')->insertGetId(
                ['lang_id' => $language,'cnt_id'=>$latest_desc_cid,'content' => $catdesc,'is_active'=>1,'created_by'=>auth()->user()->id,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
            );
			$max=OrganizationCategory::max('sort_order');
			$sort = $max+1;
            $catId=OrganizationCategory::create([
            'title_cid' => $latest_cat_cid,
            'title'=>$catname,
            //'slug' => $catname,
            'desc_cid' => $latest_desc_cid,
            'image' => $filename,
            'sort_order'=>$sort,
            'is_active'=>$request->status,
            'is_deleted'=>0,
            'created_by'=>auth()->user()->id,
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
			])->id;
			if($catId){
				$success=1;
			}
			}
		return $success; 

    }


    public function editCategory($cat_id){
        $data['title']              =   'Category';
        $data['menu']               =   'Category';
        $data['language']           =    tenancy()->central(function ($tenant) {
							        return DB::table('glo_lang_lk')->where('is_active', 1)->get();
									});
        $data['category']           =    OrganizationCategory::where('id',$cat_id)->first();
		$title_id = $data['category']->title_cid;
		$desc_id = $data['category']->desc_cid;
		$data['category_name']=DB::table('cms_content')->where('cnt_id', $title_id)->first();
		$data['category_desc']=DB::table('cms_content')->where('cnt_id', $desc_id)->first(); 
	   return view('organization.master.category.create',$data);
    }

    public function categoryStatus(Request $request){
        $input = $request->all();
        if($input['id']>0) {
        OrganizationCategory::where('id',$input['id'])->update(array('is_active'=>$input['status']));
        
        return '1';
        }else {
        
        return '0';
        }
        
    }
    
    public function sort_order(Request $request)
    {
        $id_ary = explode(",",$request->row_order);
       
        for($i=1;$i<=count($id_ary);$i++) 
        {
            Category::where('category_id', $id_ary[$i-1])
            ->update(['sort_order' => $i]);
            
        }
        Session::flash('message', ['text'=>'Sorted successfully','type'=>'success']);
        return redirect(route('admin.category'));

    }  
      public function subcat_sort_order(Request $request)
    {
        $id_ary = explode(",",$request->row_order);
       
        for($i=1;$i<=count($id_ary);$i++) 
        {
            Subcategory::where('subcategory_id', $id_ary[$i-1])
            ->update(['sort_order' => $i]);
            
        }
        Session::flash('message', ['text'=>'Sorted successfully','type'=>'success']);
        return redirect(route('admin.subcategory'));

    } 
    
    public function view_category($cat_id)
    {
        $data['title']              =   'Category';
        $data['menu']               =   'Category List';
        $data['language']           =    DB::table('glo_lang_lk')->where('is_active', 1)->get();
        $data['category']           =    Category::where('category_id',$cat_id)->first();
        return view('admin.master.view_category',$data);
    }
    /*******=======Sub category*********====== */
    public function new_subcategory()
    {
        $data['title']         =   'Subcategory';
        $data['menu']          =   'Subcategory';
        $data['language']      =    DB::table('glo_lang_lk')->where('is_active', 1)->get();
        $data['category']      =    Category::where('is_active',1)->where('is_deleted',0)->get();
        //dd($data['language']);
        return view('admin.master.create_subcategory', $data);
    }

    public function subcatedata($cateid='',$selectid='')
    {
      $sub_data=array();
      $squery    =   Subcategory::where('is_active',1)->where('category_id',$cateid)->where('is_deleted',0)->orderBy('subcategory_id','desc')->get();
    //   dd($squery);
    //   die;
      if($squery->count()> 0)
        {
          //$sub_data[]=array('id'=>'','title'=>'Select Sub Category');
          foreach($squery as $srow)
          {
            if($srow->subcategory_id != $selectid)
            {   
                $default_lang =DB::table('glo_lang_lk')->where('is_active', 1)->first();
                $category_name=DB::table('cms_content')->where('cnt_id', $srow->sub_name_cid)->where('lang_id', $default_lang->id)->first();
                $kk=array();
                $kk['id'] = $srow->subcategory_id;
                $kk['title'] = ucfirst($category_name->content);
                $tt=$this->subtree($cateid,$srow->subcategory_id,$selectid);
                if($tt)
                {
                  if($selectid=='product')
                  {
                    $kk['isSelectable']=false;
                  }
                  $kk['subs']=$tt;
                }
                $sub_data[]=$kk;
            }
          }
        }
      $result=array('val'=>'1','subdata'=>$sub_data);
      echo json_encode($result);
    }

    function subtree($cateid,$subid,$selectid='')
    {
      $jj=array();
      $squery2    =   Subcategory::where('is_active',1)->where('category_id',$cateid)->where('is_deleted',0)->orderBy('subcategory_id','desc')->get();
      if($squery2->count() > 0)
      {
        foreach($squery2 as $srow)
        {
          if($srow->subcategory_id != $selectid)
            {
                $default_lang =DB::table('glo_lang_lk')->where('is_active', 1)->first();
                $category_name=DB::table('cms_content')->where('cnt_id', $srow->sub_name_cid)->where('lang_id', $default_lang->id)->first();
                $kk=array();
                $kk['id'] = $srow->subcategory_id;
                $kk['title'] = ucfirst($category_name->content);
                $tt=$this->subtree($cateid,$srow->subcategory_id,$selectid);
                if($tt)
                {
                  if($selectid=='product')
                  {
                    $kk['isSelectable']=false;
                  }
                  $kk['subs']=$tt;
                }
                $jj[]=$kk;
            }

        }
      }
      return $jj;
    }

    public function create_subcategory(Request $request)
    {
        
        $validate= $request->validate([
            'sub_category_name' => ['required', 'string'],
            'language'=> ['required'],
            'category'=> ['required'],
            'subcategory_image'=>['required','image','mimes:jpeg,png,jpg']
        ]);
        if (Subcategory::where('subcategory_name', '=', $validate['sub_category_name'])->where('is_deleted', '=',0)->exists()) {
            Session::flash('message', ['text'=>'Sub-Category Already Exist','type'=>'warning']);
            return back()->withInput($request->all());
        }
        // else if (Subcategory::where('local_name', '=', $validate['local_name'])->where('is_deleted', '=',0)->exists()) {
        //     Session::flash('message', ['text'=>'Local name of Subcategory Already Exist','type'=>'warning']);
        //     return back()->withInput($request->all());
        // }
        else
        {
            if($request->hasFile('subcategory_image'))
            {
            $file=$request->file('subcategory_image');
            $extention=$file->getClientOriginalExtension();
            $filename=time().'.'.$extention;
            $file->move(('storage/app/public/subcategory/'),$filename);
            }
            else
            {
                $filename='';
            }

            $latest = DB::table('cms_content')->orderBy('cnt_id', 'DESC')->first();
            $latest_cat_cid=++$latest->cnt_id;
            $latest_desc_cid =$latest_cat_cid+1;
            // dd($latest_desc_cid);
            // die;

            $cat_cid = DB::table('cms_content')->insertGetId(
                ['org_id' => 1, 'lang_id' => $validate['language'],'cnt_id'=>$latest_cat_cid,'content' => $validate['sub_category_name'],'is_active'=>1,'created_by'=>auth()->user()->id,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
            );
            $cat_desc_cid = DB::table('cms_content')->insertGetId(
                ['org_id' => 1, 'lang_id' => $validate['language'],'cnt_id'=>$latest_desc_cid,'content' => $request['subcategory_description'],'is_active'=>1,'created_by'=>auth()->user()->id,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
            );
  
            Subcategory::create([
            'category_id'=>$validate['category'],
            'sub_name_cid' => $latest_cat_cid,
            'subcategory_name'=>$validate['sub_category_name'],
            'slug' => $validate['sub_category_name'],
            'desc_cid' => $latest_desc_cid,
            'image' => $filename,
            'is_active'=>$request->status,
            'is_deleted'=>0,
            'created_by'=>auth()->user()->id,
            'modified_by'=>auth()->user()->id,
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")

        ]);
            Session::flash('message', ['text'=>'Created successfully','type'=>'success']);
           return redirect(route('admin.subcategory'));
        }
    }

    public function subcategory()
    {
        $data['title']              =   'Subcategory';
        $data['menu']               =   'SubCategory';
        $data['active']             =   '';
        $data['category']           =    Subcategory::where('is_deleted',NULL)->orWhere('is_deleted',0)->orderBy('subcategory_id','DESC')->get();
        $data['subcategory_sort']      =    Subcategory::where('is_deleted',NULL)->orWhere('is_deleted',0)->orderBy('sort_order')->get();
        return view('admin.master.subcategory_list',$data);
    }
    public function delete_subcategory()
    {
        $cat_id=$_POST['cat_id'];
        Subcategory::where('subcategory_id',$cat_id)->update([
            'is_active'=>0,
            'is_deleted'=>1,
            'modified_by'=>auth()->user()->id,
            'updated_at'=>date("Y-m-d H:i:s")

        ]);
            Session::flash('message', ['text'=>'Deleted successfully','type'=>'success']);
    }

    public function change_status_subcategory(Request $request)
    {
        $category = Subcategory::find($request->cat_id);
        $category->is_active = $request->status;
        $category->save();

        return response()->json(['success'=>'User status change successfully.']);
    }

    public function edit_subcategory($scat_id)
    {
        $data['title']              =   'Subcategory';
        $data['menu']               =   'Subcategory';
        $data['language']           =    DB::table('glo_lang_lk')->where('is_active', 1)->get();
        $data['category']           =    Category::where('is_deleted',NULL)->orWhere('is_deleted',0)->get();
        $data['subcategory']        =    Subcategory::where('subcategory_id',$scat_id)->first();
        return view('admin.master.edit_subcategory',$data);
    }

    public function update_subcategory(Request $request,$cat_id)
    {
        

        $validate= $request->validate([
            'sub_category_name' => ['required', 'string'],
            'language'=> ['required'],
            'category'=> ['required'],
            'subcategory_image'=> ['image','mimes:jpeg,png,jpg']
        ]);
        
        $latest = DB::table('cms_content')->orderBy('cnt_id', 'DESC')->first();
        $latest_cat_cid=++$latest->cnt_id;
        $latest_desc_cid=$latest_cat_cid+1;

            if($request->hasFile('subcategory_image'))
            {
            $file=$request->file('subcategory_image');
            $extention=$file->getClientOriginalExtension();
            $filename=time().'.'.$extention;
            $file->move(('storage/app/public/subcategory/'),$filename);
            }
            else
            {
                $filename=$request->image_file;
            }

            //update category name
            if (DB::table('cms_content')->where('cnt_id', $request->sub_name_cid)->where('lang_id', $validate['language'])->exists()) {
                DB::table('cms_content')
                ->where('cnt_id', $request->sub_name_cid)->where('lang_id', $validate['language'])
                ->update(['content' => $validate['sub_category_name']]);
                $scat_cid=$request->sub_name_cid;
            }
            else
            {
                
                 DB::table('cms_content')->insertGetId(
                    ['org_id' => 1, 'lang_id' => $validate['language'],'cnt_id'=>$latest_cat_cid,'content' => $validate['sub_category_name'],'is_active'=>1,'created_by'=>auth()->user()->id,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
                );
                $scat_cid =$latest_cat_cid;
            }
            //update category desc
            if (DB::table('cms_content')->where('cnt_id', $request->desc_cid)->where('lang_id', $validate['language'])->exists()) {
                 DB::table('cms_content')
                ->where('cnt_id', $request->desc_cid)->where('lang_id', $validate['language'])
                ->update(['content' => $request['subcategory_description']]);
                $scat_desc_cid=$request->desc_cid;
            }
            else
            {
                
                DB::table('cms_content')->insertGetId(
                    ['org_id' => 1, 'lang_id' => $validate['language'],'cnt_id'=>$latest_desc_cid,'content' => $request['subcategory_description'],'is_active'=>1,'created_by'=>auth()->user()->id,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
                );
                $scat_desc_cid = $latest_desc_cid;
            }

            Subcategory::where('subcategory_id',$cat_id)->update([
                'category_id'=>$validate['category'],
                'sub_name_cid' => $scat_cid,
                'subcategory_name'=>$validate['sub_category_name'],
                'slug' => $validate['sub_category_name'],
                'desc_cid' => $scat_desc_cid,
                'image' => $filename,
                'is_active'=>$request->status,
                'modified_by'=>auth()->user()->id,
                'updated_at'=>date("Y-m-d H:i:s")

        ]);
            Session::flash('message', ['text'=>'Updated successfully','type'=>'success']);
            return redirect(route('admin.subcategory'));

    }
    
    public function view_subcategory($scat_id)
    {
        $data['title']              =   'Subcategory';
        $data['menu']               =   'Subcategory List';
        $data['language']           =    DB::table('glo_lang_lk')->where('is_active', 1)->get();
        $data['category']           =    Category::where('is_deleted',NULL)->orWhere('is_deleted',0)->get();
        $data['subcategory']        =    Subcategory::where('subcategory_id',$scat_id)->first();
        return view('admin.master.view_subcategory',$data);
    }
	
	
	public function reorderCategory(Request $request)
    {
        $category = OrganizationCategory::get();
		$success=0;
        foreach ($category as $cat) {
            foreach ($request->order as $order) {
                if ($order['id'] == $cat->id) {
                    $cat->update(['sort_order' => $order['position']]);
					$success=1;
				}
            }
        }

        return $success;
    }

}


