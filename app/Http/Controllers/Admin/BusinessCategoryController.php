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
use App\Models\BusinessCategory;
use App\Models\BusinessCategoryOrder;

use App\Rules\Name;
use Validator;


class BusinessCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $data['title']              =   'Category';
        $data['menu']               =   'Category List';
        $data['active']             =   '';
        $data['business_category']  =   BusinessCategory::where('is_deleted',NULL)->orWhere('is_deleted',0)->get();
        $data['category']           =   Category::where('is_deleted',NULL)->orWhere('is_deleted',0)->orderBy('category_id','DESC')->get();
        $data['category_sort']      =   Category::where('is_deleted',NULL)->orWhere('is_deleted',0)->orderBy('sort_order')->get();
        return view('admin.master.business_category_list',$data);
    }
    


    
    public function sort_order(Request $request)
    {
		
		$bcat_id=$request->business_category;
		$cat_ids=$request->row_order;
		//dd($bcat_id);
        $id_ary = explode(",",$request->row_order);
		//\DB::enableQueryLog();
		$businesscategory = BusinessCategoryOrder::where('business_category_id',$bcat_id)->where('is_deleted',0)->Where('is_active',1)->get();
     // dd(\DB::getQueryLog());
	   if(count($businesscategory) == 0){
			foreach($id_ary as $key=>$cat){
				BusinessCategoryOrder::create(['business_category_id'=>$bcat_id,
				'business_category_id'=>$bcat_id,
				'category_id'=>$cat,
				'sort_order'=>$key+1,
				'is_active'=>1,
				'is_deleted'=>0
				]);				
			}
		}else{
			BusinessCategoryOrder::where('business_category_id', $bcat_id)->where('is_deleted',0)->Where('is_active',1)
            ->update(['is_deleted' => 1,'is_active' => 0]);
			//dd();
			foreach($id_ary as $key=>$cat){
				BusinessCategoryOrder::create(['business_category_id'=>$bcat_id,
				'business_category_id'=>$bcat_id,
				'category_id'=>$cat,
				'sort_order'=>$key+1,
				'is_active'=>1,
				'is_deleted'=>0
				]);				
			}			
			
		}
		
        Session::flash('message', ['text'=>'Sorted successfully','type'=>'success']);
        return redirect(route('admin.businessCategory'));

    }


	 public function checkExist(Request $request){
		$cat_ids=$request->category_id; 
        $businesscategory = BusinessCategoryOrder::where('business_category_id',$request->bcat_id)->where('is_deleted',0)->Where('is_active',1)->pluck('category_id');
		 if(count($businesscategory)>0){
			// \DB::enableQueryLog();
			$data['category_sort']     =   Category::join('crm_category_order', 'category.category_id', '=', 'crm_category_order.category_id')->where('crm_category_order.business_category_id',$request->bcat_id)
			->whereIn('category.category_id',$businesscategory)->Where('category.is_deleted',0)->Where('crm_category_order.is_active',1)->Where('crm_category_order.is_deleted',0)->orderBy('crm_category_order.sort_order')
			->get();
			//dd(\DB::getQueryLog());
			$data['category_not_in']      =   Category::whereNotIn('category_id',$businesscategory)->Where('is_deleted',0)->Where('is_active',1)->orderBy('sort_order')->get();
		   }else{
			$data['category_sort']      =   Category::Where('is_deleted',0)->orderBy('sort_order')->get();
		   }
		   
					return view('admin.master.includes.businessCategoryList',$data);

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
			//dd( $request->desc_cid);
            if (DB::table('cms_content')->where('cnt_id', $request->desc_cid)->where('lang_id', $validate['language'])->exists()) {
                 DB::table('cms_content')
                ->where('cnt_id', $request->desc_cid)->where('lang_id', $validate['language'])
                ->update(['content' => $request->subcategory_description]);
                $scat_desc_cid=$request->desc_cid;
            }else if (DB::table('cms_content')->where('cnt_id', $request->desc_cid)->exists()) 
            {
				DB::table('cms_content')->insertGetId(
                    ['org_id' => 1, 'lang_id' => $validate['language'],'cnt_id'=>$request->desc_cid,'content' => $validate['subcategory_description'],'is_active'=>1,'created_by'=>auth()->user()->id,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
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


