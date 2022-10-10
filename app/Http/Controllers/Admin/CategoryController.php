<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;
use App\Models\Modules;
use App\Models\UserRoles;
use App\Models\Admin;
use App\Models\UserRole;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\PrdFields;
use App\Models\CategoryFields;
use App\Models\SubcategoryList;

use App\Rules\Name;
use Validator;


class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function category()
    {
        $data['title']              =   'Category';
        $data['menu']               =   'Category List';
        $data['active']             =   '';
        $data['category']           =    Category::where('is_deleted',NULL)->orWhere('is_deleted',0)->orderBy('category_id','DESC')->get();
        $data['category_sort']      =    Category::where('is_deleted',NULL)->orWhere('is_deleted',0)->orderBy('sort_order')->get();
        return view('admin.master.category_list',$data);
    }
    

    public function insert_category()
    {
        $data['title']         =   'Category';
        $data['menu']          =   'Category';
        $data['language']      =    DB::table('glo_lang_lk')->where('is_active', 1)->get();
		$data['default_language']      		=   DB::table('glo_lang_lk')->where('is_default', 1)->first();

        return view('admin.master.create_category', $data);
    }

    public function create_category(Request $request)
    {
		//dd($request->category_name);
        $validate= $request->validate([
            'category_name' => ['required', 'string'],
            'language'=> ['required'],
            'category_description'=> ['required'],
            'language'=> ['required'],
            'local_name'=>['nullable','string'],
            'category_image'=>['required','image','mimes:jpeg,png,jpg']
        ]);
        if (Category::where('cat_name', '=', $validate['category_name'])->where('is_deleted', '=',0)->exists()) {
            Session::flash('message', ['text'=>'Category Already Exist','type'=>'warning']);
            // return redirect(route('admin.category'));
             return back()->withInput($request->all());
        }
        else if (Category::where('local_name', '=', $validate['local_name'])->where('is_deleted', '=',0)->exists()) {
            Session::flash('message', ['text'=>'Local name of Category Already Exist','type'=>'warning']);
            // return redirect(route('admin.category'));
             return back()->withInput($request->all());
        }
        else
        {
            $file=$request->file('category_image');
            $extention=$file->getClientOriginalExtension();
            $filename=time().'.'.$extention;
            $file->move(('storage/app/public/category/'),$filename);

            $latest = DB::table('cms_content')->orderBy('id', 'DESC')->first();
            $latest_cat_cid=++$latest->cnt_id;
            $latest_desc_cid =$latest_cat_cid+1;
            // dd($latest_desc_cid);
            // die;

            $cat_cid = DB::table('cms_content')->insertGetId(
                ['org_id' => 1, 'lang_id' => $validate['language'],'cnt_id'=>$latest_cat_cid,'content' => $validate['category_name'],'is_active'=>1,'created_by'=>auth()->user()->id,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
            );
            $cat_desc_cid = DB::table('cms_content')->insertGetId(
                ['org_id' => 1, 'lang_id' => $validate['language'],'cnt_id'=>$latest_desc_cid,'content' => $validate['category_description'],'is_active'=>1,'created_by'=>auth()->user()->id,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
            );
			//dd($request);
if(isset($request->is_rating)){
				$rating=1;
			}else{
				$rating=0;
			}
			
            Category::create([
            'cat_name_cid' => $latest_cat_cid,
            'cat_name'=>$validate['category_name'],
            'slug' => $validate['category_name'],
            'local_name'=>$validate['local_name'],
            'cat_desc_cid' => $latest_desc_cid,
            'image' => $filename,
            'sort_order'=>0,
            'is_active'=>$request->status,
            'is_rating'=>$rating,
            'is_deleted'=>0,
            'created_by'=>auth()->user()->id,
            'modified_by'=>auth()->user()->id,
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")

        ]);
            Session::flash('message', ['text'=>'Created successfully','type'=>'success']);
            return redirect(route('admin.category'));
        }
    }

    public function edit_category($cat_id)
    {
        $data['title']              =   'Category';
        $data['menu']               =   'Category';
        $data['language']           =    DB::table('glo_lang_lk')->where('is_active', 1)->get();
        $data['category']           =    Category::where('category_id',$cat_id)->first();
        $data['default_language']      		=   DB::table('glo_lang_lk')->where('is_default', 1)->first();

		return view('admin.master.edit_category',$data);
    }

    public function update_category(Request $request,$cat_id)
    {
		$default_language    		=   DB::table('glo_lang_lk')->where('is_default', 1)->first();

        $validate= $request->validate([
            'category_name' => ['required', 'string'],
            'language'=> ['required'],
            'category_description'=> ['required'],
            'local_name'=>['nullable','string'],
            'category_image'=> ['image','mimes:jpeg,png,jpg']
        ]);

            if($request->hasFile('category_image'))
            {
            $file=$request->file('category_image');
            $extention=$file->getClientOriginalExtension();
            $filename=time().'.'.$extention;
            $file->move(('storage/app/public/category/'),$filename);
            }
            else
            {
                $filename=$request->image_file;
            }

            //update category name
            if (DB::table('cms_content')->where('cnt_id', $request->cat_content_id)->where('lang_id', $validate['language'])->exists()) {
                DB::table('cms_content')
                ->where('cnt_id', $request->cat_content_id)->where('lang_id', $validate['language'])
                ->update(['content' => $validate['category_name']]);
                $cat_cid=$request->cat_content_id;
            }else if (DB::table('cms_content')->where('cnt_id', $request->cat_content_id)->exists()) 
            {
				DB::table('cms_content')->insertGetId(
                    [ 'lang_id' => $request->language,'cnt_id'=>$request->cat_content_id,'content' => $request->category_name,'is_active'=>1,'created_by'=>auth()->user()->id,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
                );
                $cat_cid=$request->cat_content_id;
			}
            else
            {
                $latest = DB::table('cms_content')->orderBy('cnt_id', 'DESC')->first();
                $latest_cat_cid=++$latest->cnt_id;
                 DB::table('cms_content')->insertGetId(
                    ['org_id' => 1, 'lang_id' => $validate['language'],'cnt_id'=>$latest_cat_cid,'content' => $validate['category_name'],'is_active'=>1,'created_by'=>auth()->user()->id,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
                );
                $cat_cid =$latest_cat_cid;
            }
            //update category desc
            if (DB::table('cms_content')->where('cnt_id', $request->desc_content_id)->where('lang_id', $validate['language'])->exists()) {
                 DB::table('cms_content')
                ->where('cnt_id', $request->desc_content_id)->where('lang_id', $validate['language'])
                ->update(['content' => $validate['category_description']]);
                $cat_desc_cid=$request->desc_content_id;
            }else if (DB::table('cms_content')->where('cnt_id', $request->desc_content_id)->exists()) 
            {
				DB::table('cms_content')->insertGetId(
                    [ 'lang_id' => $request->language,'cnt_id'=>$request->desc_content_id,'content' => $request->category_description,'is_active'=>1,'created_by'=>auth()->user()->id,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
                );
                $cat_desc_cid=$request->desc_content_id;
			}
            else
            {
                $latest = DB::table('cms_content')->orderBy('cnt_id', 'DESC')->first();
                $latest_desc_cid=++$latest->cnt_id;
                DB::table('cms_content')->insertGetId(
                    ['org_id' => 1, 'lang_id' => $validate['language'],'cnt_id'=>$latest_desc_cid,'content' => $validate['category_description'],'is_active'=>1,'created_by'=>auth()->user()->id,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
                );
                $cat_desc_cid = $latest_desc_cid;
            }


            if(isset($request->is_rating)){
				$rating=1;
			}else{
				$rating=0;
			}
			if($request->language ==$default_language->id){
				$data1['local_name'] =$validate['local_name'];
				$data1['slug'] =$validate['category_name'];
				$data1['cat_name'] = $validate['category_name'];
			}
				//$data1['cat_name_cid'] = $cat_cid;
				//$data1['cat_desc_cid'] =$cat_desc_cid;
				
				$data1['is_rating'] = $rating;
			
			$data1['image'] = $filename;
			$data1['is_active'] = $request->status;
			$data1['is_deleted'] = 0;
			$data1['modified_by'] = auth()->user()->id;
			$data1['updated_at'] = date("Y-m-d H:i:s");
            Category::where('category_id',$cat_id)->update($data1);
            Session::flash('message', ['text'=>'Updated successfully','type'=>'success']);
            return redirect(route('admin.category'));

    }

    public function delete_category()
    {
        $cat_id=$_POST['cat_id'];
        Category::where('category_id',$cat_id)->update([
            'is_active'=>0,
            'is_deleted'=>1,
            'modified_by'=>auth()->user()->id,
            'updated_at'=>date("Y-m-d H:i:s")

        ]);
            Session::flash('message', ['text'=>'Deleted successfully','type'=>'success']);
    }

    public function change_status(Request $request)
    {
        $category = Category::find($request->cat_id);
        $category->is_active = $request->status;
        $category->save();

        return response()->json(['success'=>'User status change successfully.']);
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
        $data['extra_fields']      =    PrdFields::where('is_active',1)->where('is_deleted',0)->get();
        $data['subcategories']      =    getDropdownData(SubcategoryList::where('is_active',1)->get(),'id','name');
		$data['default_language']      		=   DB::table('glo_lang_lk')->where('is_default', 1)->first();
        return view('admin.master.create_subcategory', $data);
    }

    public function subcatedata($cateid='',$selectid='')
    {
      $sub_data=array();
      $squery    =   Subcategory::where('is_active',1)->where('category_id',$cateid)->where('is_deleted',0)->where('parent',0)->orderBy('subcategory_id','desc')->get();
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
      $squery2    =   Subcategory::where('is_active',1)->where('category_id',$cateid)->where('parent',$subid)->where('is_deleted',0)->orderBy('subcategory_id','desc')->get();
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
            'sub_category_name' => ['required'],
            'local_name'=>['nullable','string'],
            'language'=> ['required'],
            'category'=> ['required'],
            // 'subcategory_image'=>['required','image','mimes:jpeg,png,jpg']
        ]);
        if (Subcategory::where('sabcatlist_id', '=', $validate['sub_category_name'])->where('category_id', '=',$validate['category'])->where('is_deleted', '=',0)->exists()) {
            Session::flash('message', ['text'=>'Sub-Category Already Exist','type'=>'warning']);
            return back()->withInput($request->all());
        }
        // else if (Subcategory::where('local_name', '=', $validate['local_name'])->where('is_deleted', '=',0)->exists()) {
        //     Session::flash('message', ['text'=>'Local name of Subcategory Already Exist','type'=>'warning']);
        //     return back()->withInput($request->all());
        //

		
		
		
        else
        {
			
			if($validate['sub_category_name']){
			
			$subcattegorydetails=SubcategoryList::where('id', '=', $validate['sub_category_name'])->first();
			$subcat_name=$subcattegorydetails->name;
		}
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
                ['org_id' => 1, 'lang_id' => $validate['language'],'cnt_id'=>$latest_cat_cid,'content' => $subcat_name,'is_active'=>1,'created_by'=>auth()->user()->id,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
            );
            $cat_desc_cid = DB::table('cms_content')->insertGetId(
                ['org_id' => 1, 'lang_id' => $validate['language'],'cnt_id'=>$latest_desc_cid,'content' => $request['subcategory_description'],'is_active'=>1,'created_by'=>auth()->user()->id,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
            );
  /************************************SELECT LEVEL********** */
  
        if ($request->parent!='') {
            $level=1;
            $parent_id    =   Subcategory::where('is_active', 1)->where('subcategory_name', $request->parent)->where('is_deleted', 0)->first();
            $parentid_1 = $parent_id->subcategory_id;
            
            $squery2    =   Subcategory::where('is_active',1)->where('category_id',$parent_id->subcategory_id)->where('is_deleted',0)->first();
            $squery_sol   =   Subcategory::where('is_active',1)->where('category_id',$validate['category'])->where('is_deleted',0)->get();
           
            if(!empty($squery2))
            {
                $level=1;
                
            }
            else
            {
                
                foreach ($squery_sol as $rows) {
                    $squery3    =   Subcategory::where('is_active', 1)->where('category_id', $validate['category'])->where('parent', $rows->subcategory_id)->where('is_deleted', 0)->get();
                   
                    foreach ($squery3 as $srow) {
                        
                        if ($srow->subcategory_id == $parent_id->subcategory_id) {
                            $level++;
                            break;
                        }
                        else
                        {
                            $level++;
                        }
                    }
                }
            }
            // echo $level;
            // echo "<br>".$parent_id->subcategory_id;
            // die;

        }
        else
        {
            $parentid_1=0;
            $level=0;
        }
           $insId      = Subcategory::insertGetId([
            'category_id'=>$validate['category'],
            'sub_name_cid' => $latest_cat_cid,
              'subcategory_name'=>SubcategoryList::where("id",$validate['sub_category_name'])->first()->name,
            'code'=>SubcategoryList::where("id",$validate['sub_category_name'])->first()->code,
            'sabcatlist_id'=>$validate['sub_category_name'],
            // 'local_name'=>$validate['local_name'],
            'slug' => SubcategoryList::where("id",$validate['sub_category_name'])->first()->name,
            'desc_cid' => $latest_desc_cid,
            'image' => $filename,
            'parent'=> $parentid_1,
            'level'=>$level,
            'is_active'=>1,
            'is_deleted'=>0,
            'created_by'=>auth()->user()->id,
            'modified_by'=>auth()->user()->id,
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")

        ]);
        
         if(isset($request->extra_fields)){
            
            if($insId){
             
            $cat_ext = [];
            $cat_ext['cat_id'] = $insId;
            $cat_ext['field_id'] = implode(',', $request->extra_fields);
            $cat_ext['created_by'] = auth()->user()->id;

            CategoryFields::create($cat_ext);
            }

            }
            
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
        $data['subcategory_sort']   =    Subcategory::where('is_deleted',NULL)->orWhere('is_deleted',0)->orderBy('sort_order')->get();
        $data['category_sort']      =    Category::where('is_deleted',0)->orderBy('sort_order')->get();
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
        $data['subcategories_list']      =    getDropdownData(SubcategoryList::where('is_active',1)->get(),'id','name');
        $data['extra_fields']      =    PrdFields::where('is_active',1)->where('is_deleted',0)->get();
        $data['default_language']      		=   DB::table('glo_lang_lk')->where('is_default', 1)->first();
		$existing_fields      =    CategoryFields::where('cat_id',$scat_id)->first();
        if($existing_fields){
            $data['existing_fields']      =   explode(",", $existing_fields->field_id);
        }else{
           $data['existing_fields']      =    []; 
        }
        return view('admin.master.edit_subcategory',$data);
    }

    public function update_subcategory(Request $request,$cat_id)
    {
        
        $default_language    		=   DB::table('glo_lang_lk')->where('is_default', 1)->first();
		$subcatname=$request->lang_sub_category_name;
		//dd($request->sub_category_name);
		//$slected_subcategory=Subcategory::where('sabcatlist_id', '=', $request->sub_category_name)->where('subcategory_id','!=',$cat_id)->where('category_id', '=',$request->category)->where('is_deleted', '=',0)->first();
       // $slected_subcategory->
		$validate= $request->validate([
            'sub_category_name' => ['required'],
            'local_name'=>['nullable','string'],
            'language'=> ['required'],
            'category'=> ['required'],
            // 'subcategory_image'=> ['image','mimes:jpeg,png,jpg']
        ]);
         if (Subcategory::where('sabcatlist_id', '=', $validate['sub_category_name'])->where('subcategory_id','!=',$cat_id)->where('category_id', '=',$validate['category'])->where('is_deleted', '=',0)->exists()) {
        
            Session::flash('message', ['text'=>'Sub-Category Already Exist','type'=>'warning']);
            return back()->withInput($request->all());
        }
		if($validate['sub_category_name']){
			
			$subcattegorydetails=SubcategoryList::where('id', '=', $validate['sub_category_name'])->first();
			$subcat_name=$subcattegorydetails->name;
		}
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
                ->update(['content' => $subcatname]);
                $scat_cid=$request->sub_name_cid;
            }
			else if (DB::table('cms_content')->where('cnt_id', $request->sub_name_cid)->exists()) 
            {
				DB::table('cms_content')->insertGetId(
                    ['org_id' => 1, 'lang_id' => $validate['language'],'cnt_id'=>$request->sub_name_cid,'content' => $subcatname,'is_active'=>1,'created_by'=>auth()->user()->id,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
                );
                $scat_cid=$request->sub_name_cid;
			}
            else
            {
                
                 DB::table('cms_content')->insertGetId(
                    ['org_id' => 1, 'lang_id' => $validate['language'],'cnt_id'=>$latest_cat_cid,'content' => $subcatname,'is_active'=>1,'created_by'=>auth()->user()->id,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
                );
                $scat_cid =$latest_cat_cid;
            }
            //update category desc
			//dd( $request->subcategory_description);
            if (DB::table('cms_content')->where('cnt_id', $request->desc_cid)->where('lang_id', $validate['language'])->exists()) {
                 DB::table('cms_content')
                ->where('cnt_id', $request->desc_cid)->where('lang_id', $validate['language'])
                ->update(['content' => $request->subcategory_description]);
                $scat_desc_cid=$request->desc_cid;
            }else if (DB::table('cms_content')->where('cnt_id', $request->desc_cid)->exists()) 
            {
				DB::table('cms_content')->insertGetId(
                    ['org_id' => 1, 'lang_id' => $validate['language'],'cnt_id'=>$request->desc_cid,'content' => $request->subcategory_description,'is_active'=>1,'created_by'=>auth()->user()->id,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
                );
                $scat_desc_cid=$request->desc_cid;
			}
            else
            {
                
                DB::table('cms_content')->insertGetId(
                    ['org_id' => 1, 'lang_id' => $validate['language'],'cnt_id'=>$latest_desc_cid,'content' => $request['subcategory_description'],'is_active'=>1,'created_by'=>auth()->user()->id,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
                );
                $scat_desc_cid = $latest_desc_cid;
            }

            if ($request->parent) {
                $level=1;
                $parent_id    =   Subcategory::where('is_active', 1)->where('subcategory_name', $request->parent)->where('is_deleted', 0)->first();
                $parentid_1 = $parent_id->subcategory_id;
                $squery2    =   Subcategory::where('is_active',1)->where('category_id',$parent_id->subcategory_id)->where('is_deleted',0)->first();
                $squery_sol   =   Subcategory::where('is_active',1)->where('category_id',$validate['category'])->where('is_deleted',0)->get();
               
                if(!empty($squery2))
                {
                    $level=1;
                    
                }
                else
                {
                    
                    foreach ($squery_sol as $rows) {
                        $squery3    =   Subcategory::where('is_active', 1)->where('category_id', $validate['category'])->where('parent', $rows->subcategory_id)->where('is_deleted', 0)->get();
                       
                        foreach ($squery3 as $srow) {
                            
                            if ($srow->subcategory_id == $parent_id->subcategory_id) {
                                $level++;
                                break;
                            }
                            else
                            {
                                $level++;
                            }
                        }
                    }
                }
                // echo $level;
                // echo "<br>".$parent_id->subcategory_id;
                // die;
    
            }
            else
            {
                $parentid_1=0;
                $level=0;
            }



            Subcategory::where('subcategory_id',$cat_id)->update([
                'category_id'=>$validate['category'],
                //'sub_name_cid' => $scat_cid,
                'subcategory_name'=>SubcategoryList::where("id",$validate['sub_category_name'])->first()->name,
            'code'=>SubcategoryList::where("id",$validate['sub_category_name'])->first()->code,
            'sabcatlist_id'=>$validate['sub_category_name'],
                // 'local_name'=>$validate['local_name'],
                'slug' =>SubcategoryList::where("id",$validate['sub_category_name'])->first()->name,
                //'desc_cid' => $scat_desc_cid,
                'image' => $filename,
                'parent'=> $parentid_1,
                'level'=>$level,
                'is_active'=>$request->status,
                'modified_by'=>auth()->user()->id,
                'updated_at'=>date("Y-m-d H:i:s")

        ]);
            if(isset($request->extra_fields)){
            $ext_fields = $request->extra_fields;
            }else {
            $ext_fields = [];
            }
            
            $cat_ext = [];
            $cat_ext['field_id'] = implode(',', $ext_fields);
    
            $existing_fields      =    CategoryFields::where('cat_id',$cat_id)->first();
            if($existing_fields){
            CategoryFields::where('cat_id',$cat_id)->update($cat_ext);
            }else{
            
            $cat_ext['cat_id'] = $cat_id;
            $cat_ext['created_by'] = auth()->user()->id;

            CategoryFields::create($cat_ext);
            }
        
        
            
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
        $data['extra_fields']      =    PrdFields::where('is_active',1)->where('is_deleted',0)->get();
        $existing_fields      =    CategoryFields::where('cat_id',$scat_id)->first();
        if($existing_fields){
        $data['existing_fields']      =   explode(",", $existing_fields->field_id);
        }else{
        $data['existing_fields']      =    []; 
        }
        return view('admin.master.view_subcategory',$data);
    }
    
        public function new_subcategory_item(Request $request)
    {
        $validate= $request->validate([
            'item_name' => ['required', 'string'],
        ]);
        if (SubcategoryList::where('name', '=', $validate['item_name'])->where('is_active', '=',1)->exists()) {
            Session::flash('message', ['text'=>'Subcategory Item Already Exist','type'=>'warning']);
            // return redirect(route('admin.category'));
             return back()->withInput($request->all());
        }
        else
        {
          

            SubcategoryList::create([
            'name'=>$validate['item_name'],
            'is_active'=>$request->is_active,
        ]);
            Session::flash('message', ['text'=>'Created successfully','type'=>'success']);
            return back();
        }
    }
	
	public function catContent(Request $request){
	$post =  (object)$request->post();
	$lang_id=$post->lang_id;
	$title_id=$post->title_id;
	$desc_id=$post->desc_id;
	$cat_id=$post->cat_id;
	$data['language']      = DB::table('glo_lang_lk')->where('is_active', 1)->get();
	$data['category']           =    Category::where('category_id',$cat_id)->first();						
	$data['lang_id']=$post->lang_id;
	//dd($data['category'] );
	return view('admin.master.includes.content',$data);
	}
	
	public function subcatContent(Request $request){
		$post =  (object)$request->post();
		$lang_id=$post->lang_id;
		$title_id=$post->title_id;
		$desc_id=$post->desc_id;
		$subcat_id=$post->subcat_id;
		$data['language']= DB::table('glo_lang_lk')->where('is_active', 1)->get();
		$data['subcategory']= Subcategory::where('subcategory_id',$subcat_id)->first();
		$data['subcategories_list']      =    getDropdownData(SubcategoryList::where('is_active',1)->get(),'id','name');
		$data['lang_id']=$post->lang_id;
		return view('admin.master.includes.subcat_content',$data);
	}

}


