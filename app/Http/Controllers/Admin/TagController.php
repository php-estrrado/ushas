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
use App\Models\Tag;
use App\Models\Category;

use App\Models\Admin;


use App\Rules\Name;
use Validator;

class TagController extends Controller
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
    
    public function tags()
        { 
        $data['title']              =   'Tags';
        $data['menu']               =   'tags';
        $data['tags']              =   Tag::getTags();
        // dd($data);
        return view('admin.tags.list',$data);
        }

        public function createTag()
        { 
        $data['title']              =   'Create Tag';
        $data['menu']               =   'create-tag';
        $data['language']      =    DB::table('glo_lang_lk')->where('is_active', 1)->get();
        $data['categories']      =   Category::where('is_active',1)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();
        // $data['subcategories']      =    $this->subcatedata(4);
        // dd($data);
        return view('admin.tags.create',$data);
        }

        public function subcatedata(Request $request)
    {
  
         $input = $request->all();
         $cateid = $input['category_id'];
        if(isset($input['selectid'])) { $selectid = $input['selectid']; }else {  $selectid = '';}
            $sub_data=array();
           
              $squery    =  DB::table('subcategory')->where('category_id', $cateid)->where('parent', 0)->where('is_active', 1)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();

            
              if($squery->count()> 0)
                {

                  //$sub_data[]=array();
                  foreach($squery as $srow)
                  { 

                    if($srow->subcategory_id != $selectid)
                    {
                        $kk=array();
                        $kk['id'] = $srow->subcategory_id;
                        $kk['title'] = ucfirst($srow->subcategory_name);
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
          return json_encode($result);
    }

    function subtree($cateid,$subid,$selectid='')
    {
      $jj=array();
      $squery2    =   DB::table('subcategory')->where('category_id', $cateid)->where('parent', $subid)->where('is_active', 1)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();


    
      if($squery2->count() > 0)
      {
        foreach($squery2 as $srow)
        { 
          if($srow->subcategory_id != $selectid)
            {  
                $kk=array();
                $kk['id'] = $srow->subcategory_id;
                $kk['title'] = ucfirst($srow->subcategory_name);
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

        public function editTag($tag_id)
        { 
        $data['title']              =   'Edit Tag';
        $data['menu']               =   'edit-tag';
        $data['tag']              =  Tag::getTag($tag_id);
        $data['language']      =    DB::table('glo_lang_lk')->where('is_active', 1)->get();
        $data['categories']      =   Category::where('is_active',1)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();
        // $data['subcategories']      =    $this->subcatedata(4);
        // dd($data);
        return view('admin.tags.edit',$data);
        }
         public function viewTag($tag_id)
        { 
        $data['title']              =   'View Tag';
        $data['menu']               =   'view-tag';
        $data['tag']              =  Tag::getTag($tag_id);
        $data['language']      =    DB::table('glo_lang_lk')->where('is_active', 1)->get();
        $data['categories']      =   Category::where('is_active',1)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();
        // $data['subcategories']      =    $this->subcatedata(4);
        // dd($data);
        return view('admin.tags.view',$data);
        }

        public function tagSave(Request $request)
        { 
        $input = $request->all();
        // dd($input);


        if($input['id']>0){

     
        $validator= $request->validate([
        'tag_name'   =>  ['required','unique:prod_tag,name,' . $input['id']],
        'tag_desc' => ['required'],
        'cat_id' => ['required']

        ], [], 
        [
        'tag_name' => 'Tag Name',
        'tag_desc' => 'Tag Description',
        'cat_id' => 'Category'
        ]);

        if (DB::table('cms_content')->where('cnt_id',$input['tag_name_cid'])->where('lang_id',$input['glo_lang_cid'])->exists()) {
        DB::table('cms_content')->where('cnt_id',$input['tag_name_cid'])->where('lang_id',$input['glo_lang_cid'])
        ->update(['content' => $input['tag_name']]);
        $tag_name_cid=$input['tag_name_cid'];
        } else {

        $latest = DB::table('cms_content')->orderBy('cnt_id', 'DESC')->first();
        $tag_name_cid=++$latest->cnt_id;
        DB::table('cms_content')->insertGetId([
        'org_id' => 1, 
        'lang_id' => $input['glo_lang_cid'],
        'cnt_id'=>$tag_name_cid,
        'content' => $input['tag_name'],
        'is_active'=>1,
        'created_by'=>auth()->user()->id,
        'updated_by'=>auth()->user()->id,
        'is_deleted'=>0,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")
        ]);
     

        }

       if (DB::table('cms_content')->where('cnt_id',$input['tag_desc_cid'])->where('lang_id',$input['glo_lang_cid'])->exists()) {
        DB::table('cms_content')->where('cnt_id',$input['tag_desc_cid'])->where('lang_id',$input['glo_lang_cid'])
        ->update(['content' => $input['tag_desc']]);
        $tag_desc_cid=$input['tag_desc_cid'];
        } else {

        $latest = DB::table('cms_content')->orderBy('cnt_id', 'DESC')->first();
        $tag_desc_cid=++$latest->cnt_id;
        DB::table('cms_content')->insertGetId([
        'org_id' => 1, 
        'lang_id' => $input['glo_lang_cid'],
        'cnt_id'=>$tag_desc_cid,
        'content' => $input['tag_desc'],
        'is_active'=>1,
        'created_by'=>auth()->user()->id,
        'updated_by'=>auth()->user()->id,
        'is_deleted'=>0,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")
        ]);
        

        }
        $tag_id = $input['id'];
        if($input['subcat_id'] =="") { $input['subcat_id']=0;}
        if($tag_desc_cid !="" && $tag_name_cid !="" && $tag_id !="") {

        $tag =  Tag::where('id',$tag_id)->update([
        'org_id' => 1, 
        'name' => $input['tag_name'], 
        'tag_name_cid' => $tag_name_cid,
        'tag_desc_cid' => $tag_desc_cid,
        'cat_id'=>$input['cat_id'],
        'subcat_id'=>$input['subcat_id'],
        'is_active'=>$input['is_active'],
        'is_deleted'=>0,
        'updated_by'=>auth()->user()->id,
        'updated_at'=>date("Y-m-d H:i:s")

        ]); 
        Session::flash('message', ['text'=>'Tag updated successfully','type'=>'success']); 
        }else {
        Session::flash('message', ['text'=>'Tag updation failed','type'=>'danger']);
        }







        }else{

        $validator= $request->validate([
        'tag_name'   =>  ['required','unique:prod_tag,name,'],
        'tag_desc' => ['required'],
        'cat_id' => ['required']

        ], [], 
        [
        'tag_name' => 'Tag Name',
        'tag_desc' => 'Tag Description',
        'cat_id' => 'Category'
        ]);


        $latest = DB::table('cms_content')->orderBy('id', 'DESC')->first();
        $tag_name_cid=++$latest->cnt_id;
        $tag_desc_cid =$tag_name_cid+1;

        $tag_name= DB::table('cms_content')->insertGetId([
        'org_id' => 1, 
        'lang_id' => $input['glo_lang_cid'],
        'cnt_id'=>$tag_name_cid,
        'content' => $input['tag_name'],
        'is_active'=>1,
        'created_by'=>auth()->user()->id,
        'updated_by'=>auth()->user()->id,
        'is_deleted'=>0,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")
        ]);


        $tag_desc= DB::table('cms_content')->insertGetId([
        'org_id' => 1, 
        'lang_id' => $input['glo_lang_cid'],
        'cnt_id'=>$tag_desc_cid,
        'content' => $input['tag_desc'],
        'is_active'=>1,
        'created_by'=>auth()->user()->id,
        'updated_by'=>auth()->user()->id,
        'is_deleted'=>0,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")
        ]);
        if($input['subcat_id'] =="") { $input['subcat_id']=0;}
        if($tag_name !="" && $tag_desc !="") {
        $tag =  Tag::create([
        'org_id' => 1, 
        'name' => $input['tag_name'], 
        'tag_name_cid' => $tag_name_cid,
        'tag_desc_cid' => $tag_desc_cid,
        'cat_id'=>$input['cat_id'],
        'subcat_id'=>$input['subcat_id'],
        'is_active'=>$input['is_active'],
        'is_deleted'=>0,
        'created_by'=>auth()->user()->id,
        'modified_by'=>auth()->user()->id,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")

        ]);   
        $lastId = $tag->id;
        if($lastId) {
        Session::flash('message', ['text'=>'Tag created successfully','type'=>'success']);  
        }else {
        Session::flash('message', ['text'=>'Tag creation failed','type'=>'danger']);
        }
        }else {
        Session::flash('message', ['text'=>'Tag creation failed','type'=>'danger']);
        }

        }
               $data['title']              =   'Tags';
        $data['menu']               =   'tags';
        $data['brands']              =  Tag::getTags();
        return redirect(route('admin.tags'));

        }


        public function tagDelete(Request $request)
        {
        $input = $request->all();
        
        if($input['id']>0) {
        $deleted =  Tag::where('id',$input['id'])->update(array('is_deleted'=>1,'is_active'=>0));
        Session::flash('message', ['text'=>'Tag deleted successfully.','type'=>'success']);
        return true;
        }else {
        Session::flash('message', ['text'=>'Tag failed to delete.','type'=>'danger']);
        return false;
        }

        }
           public function tagStatus(Request $request)
        {
        $input = $request->all();
        
        if($input['id']>0) {
        $deleted =  Tag::where('id',$input['id'])->update(array('is_active'=>$input['status']));
        
        return '1';
        }else {
        
        return '0';
        }
        
        }
    

   
}
