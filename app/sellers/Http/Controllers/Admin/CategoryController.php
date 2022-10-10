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
        //dd($data['language']);
        return view('admin.master.create_category', $data);
    }

    public function create_category(Request $request)
    {
        $validate= $request->validate([
            'category_name' => ['required', 'string'],
            'language'=> ['required'],
            'category_description'=> ['required'],
            'language'=> ['required'],
            'category_image'=>['required','image','mimes:jpeg,png,jpg']
        ]);
        if (Category::where('cat_name', '=', $validate['category_name'])->where('is_deleted', '=',0)->exists()) {
            Session::flash('message', ['text'=>'Category Already Exist','type'=>'warning']);
            return redirect(route('admin.category'));
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

            Category::create([
            'cat_name_cid' => $latest_cat_cid,
            'cat_name'=>$validate['category_name'],
            'slug' => $validate['category_name'],
            'cat_desc_cid' => $latest_desc_cid,
            'image' => $filename,
            'sort_order'=>0,
            'is_active'=>$request->status,
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
        return view('admin.master.edit_category',$data);
    }

    public function update_category(Request $request,$cat_id)
    {

        $validate= $request->validate([
            'category_name' => ['required', 'string'],
            'language'=> ['required'],
            'category_description'=> ['required'],
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



            Category::where('category_id',$cat_id)->update([
            'cat_name_cid' => $cat_cid,
            'cat_name'=>$validate['category_name'],
            'slug' => $validate['category_name'],
            'cat_desc_cid' => $cat_desc_cid,
            'image' => $filename,
            'is_active'=>$request->status,
            'is_deleted'=>0,
            'modified_by'=>auth()->user()->id,
            'updated_at'=>date("Y-m-d H:i:s")

        ]);
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
            'sub_category_name' => ['required', 'string'],
            'language'=> ['required'],
            'category'=> ['required'],
            'subcategory_image'=>['image','mimes:jpeg,png,jpg']
        ]);
        if (Subcategory::where('subcategory_name', '=', $validate['sub_category_name'])->where('is_deleted', '=',0)->exists()) {
            Session::flash('message', ['text'=>'Sub-Category Already Exist','type'=>'warning']);
            return redirect(route('admin.subcategory'));
        }
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
            Subcategory::create([
            'category_id'=>$validate['category'],
            'sub_name_cid' => $latest_cat_cid,
            'subcategory_name'=>$validate['sub_category_name'],
            'slug' => $validate['sub_category_name'],
            'desc_cid' => $latest_desc_cid,
            'image' => $filename,
            'parent'=> $parentid_1,
            'level'=>$level,
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
                'sub_name_cid' => $scat_cid,
                'subcategory_name'=>$validate['sub_category_name'],
                'slug' => $validate['sub_category_name'],
                'desc_cid' => $scat_desc_cid,
                'image' => $filename,
                'parent'=> $parentid_1,
                'level'=>$level,
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

}


