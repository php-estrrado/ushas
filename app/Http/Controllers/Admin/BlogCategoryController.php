<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Session;
use App\Models\BlogCategory;

class BlogCategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware('auth:admin');
    }

    public function index(){
        $data['title']              =   'Blog Category';
        $data['menu']               =   'Blog Category';
        $data['blog_categories']    =    BlogCategory::where('is_deleted',NULL)->orWhere('is_deleted',0)->get();
        return view('admin.blog.category.list',$data);
    }

    public function create(){ 
        $data['title']              =   'Create Blog Category';
        $data['menu']               =   'create-blog-category';
        return view('admin.blog.category.create',$data);
    }

    public function save(Request $request){ 

        $rules = array(
            'bc_name'        => 'required|string|unique:blog_categories,bc_name,NULL,bc_id,is_deleted,0',
            'bc_description' => 'required',
            //'bc_sortorder'   =>  'numeric'
        );
        $niceNames = ['bc_name' => 'Category Name','bc_description' => 'Category Description','bc_sortorder' => 'Sort Order']; 
        $validator = Validator::make($request->all(), $rules,[],$niceNames);
        if ($validator->fails()) {
            return redirect(route('admin.blognewcategory'))->withErrors($validator)->withInput($request->except('password'));
        }
        else
        {
            $blogcategory = new BlogCategory;
            $blogcategory->bc_name              =   $request->get('bc_name');
            $blogcategory->bc_description       =   $request->get('bc_description');
            $blogcategory->bc_faicon            =   $request->get('bc_faicon');
            $blogcategory->bc_sortorder         =   $request->get('bc_sortorder');
            $blogcategory->is_active            =   $request->get('status');
            $blogcategory->bc_seo_tag           =   $request->get('bc_seo_tag');
            $blogcategory->bc_seo_title         =   $request->get('bc_seo_title');
            $blogcategory->bc_seo_description   =   $request->get('bc_seo_description');
            $blogcategory->created_by           =   auth()->user()->id;
            $blogcategory->updated_by           =   auth()->user()->id;
            $blogcategory->save();
            Session::flash('message', ['text'=>'Created successfully','type'=>'success']);
            return redirect(route('admin.blogcategories'));
        }

    }

    public function edit($bcat_id){
        $data['title']              =   'Edit Blog Category';
        $data['menu']               =   'edit-blog-category';
        $data['blog_category']      =    BlogCategory::where('bc_id',$bcat_id)->Where('is_deleted',0)->first();
        return view('admin.blog.category.edit',$data);
    }

    public function update(Request $request,$bcat_id){ 
        $rules = array(
            'bc_name'        => 'required|string|unique:blog_categories,bc_name,'.$bcat_id.',bc_id,is_deleted,0',
            'bc_description' => 'required',
            //'bc_sortorder'   =>  'numeric'
        );
        $niceNames = ['bc_name' => 'Category Name','bc_description' => 'Category Description','bc_sortorder' => 'Sort Order']; 
        $validator = Validator::make($request->all(), $rules,[],$niceNames);
        if ($validator->fails()) {
            return redirect(route('admin.blogeditcategory',$bcat_id))->withErrors($validator)->withInput($request->except('password'));
        }
        else
        {
            $blogcategory = BlogCategory::find($bcat_id);
            $blogcategory->bc_name              =   $request->get('bc_name');
            $blogcategory->bc_description       =   $request->get('bc_description');
            $blogcategory->bc_faicon            =   $request->get('bc_faicon');
            $blogcategory->bc_sortorder         =   $request->get('bc_sortorder');
            $blogcategory->is_active            =   $request->get('status');
            $blogcategory->bc_seo_tag           =   $request->get('bc_seo_tag');
            $blogcategory->bc_seo_title         =   $request->get('bc_seo_title');
            $blogcategory->bc_seo_description   =   $request->get('bc_seo_description');
            $blogcategory->updated_by           =   auth()->user()->id;
            $blogcategory->save();
            Session::flash('message', ['text'=>'Updated successfully','type'=>'success']);
            return redirect(route('admin.blogcategories'));
        }

    }

    public function updatestatus(Request $request){ 
        $blogcategory = BlogCategory::find($request->bcat_id);
        $blogcategory->is_active = $request->status;
        $blogcategory->updated_by =   auth()->user()->id;
        $blogcategory->save();
        return response()->json(['success'=>'Blog Category status change successfully.']);
    }

    public function delete(Request $request){
        $blogcategory = BlogCategory::find($request->bcat_id);
        $blogcategory->is_active  = '0';
        $blogcategory->is_deleted = '1';
        $blogcategory->deleted_by =   auth()->user()->id;
        $blogcategory->updated_by =   auth()->user()->id;
        $blogcategory->save();
        Session::flash('message', ['text'=>'Deleted successfully','type'=>'success']);
        return response()->json(['success'=>'Blog Category deleted successfully.']);
    }
}
