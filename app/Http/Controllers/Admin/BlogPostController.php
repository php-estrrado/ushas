<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Session;
use App\Models\BlogTag;
use App\Models\BlogPost;

class BlogPostController extends Controller
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
        $data['title']        =   'Blog Posts';
        $data['menu']         =   'blog-posts';
        $data['blog_posts']    =    BlogPost::where('is_deleted',NULL)->orWhere('is_deleted',0)->get();
        return view('admin.blog.post.list',$data);
    }

    public function create(){ 
        $data['title']              =   'New Blog Post';
        $data['menu']               =   'create-blog-post';
        return view('admin.blog.post.create',$data);
    }

    public function save(Request $request){ 

        $rules = array(
            'bp_name'        => 'required|string|unique:blog_posts,bp_name,NULL,bp_id,is_deleted,0',
            'bp_description' => 'required',
        );
        $niceNames = ['bp_name' => 'Post Name','bp_description' => 'Post Description']; 
        $validator = Validator::make($request->all(), $rules,[],$niceNames);
        if ($validator->fails()) {
            return redirect(route('admin.blognewpost'))->withErrors($validator)->withInput($request->except('password'));
        }
        else
        {
            $blogpost = new BlogPost;
            $blogpost->bp_name              =   $request->get('bp_name');
            $blogpost->bp_description       =   $request->get('bp_description');
            $blogpost->is_active            =   $request->get('status');
            $blogpost->created_by           =   auth()->user()->id;
            $blogpost->updated_by           =   auth()->user()->id;
            $blogpost->save();
            Session::flash('message', ['text'=>'Created successfully','type'=>'success']);
            return redirect(route('admin.blogposts'));
        }

    }

    public function edit($bpost_id){
        $data['title']          =   'Edit Blog Post';
        $data['menu']           =   'edit-blog-post';
        $data['blog_post']       =    BlogPost::where('bp_id',$bpost_id)->Where('is_deleted',0)->first();
        return view('admin.blog.post.edit',$data);
    }

    public function update(Request $request,$bpost_id){ 
        $rules = array(
            'bp_name'        => 'required|string|unique:blog_posts,bp_name,'.$bpost_id.',bp_id,is_deleted,0',
            'bp_description' => 'required',
        );
        $niceNames = ['bp_name' => 'Post Name','bp_description' => 'Post Description']; 
        $validator = Validator::make($request->all(), $rules,[],$niceNames);
        if ($validator->fails()) {
            return redirect(route('admin.blogeditpost',$bpost_id))->withErrors($validator)->withInput($request->except('password'));
        }
        else
        {
            $blogpost = BlogPost::find($bpost_id);
            $blogpost->bp_name              =   $request->get('bp_name');
            $blogpost->bp_description       =   $request->get('bp_description');
            $blogpost->is_active            =   $request->get('status');
            $blogpost->updated_by           =   auth()->user()->id;
            $blogpost->save();
            Session::flash('message', ['text'=>'Updated successfully','type'=>'success']);
            return redirect(route('admin.blogposts'));
        }

    }

    public function updatestatus(Request $request){ 
        $blogpost = BlogPost::find($request->bpost_id);
        $blogpost->is_active = $request->status;
        $blogpost->updated_by =   auth()->user()->id;
        $blogpost->save();
        return response()->json(['success'=>'Blog Post status change successfully.']);
    }

    public function delete(Request $request){
        $blogpost = BlogPost::find($request->bpost_id);
        $blogpost->is_active  = '0';
        $blogpost->is_deleted = '1';
        $blogpost->deleted_by =   auth()->user()->id;
        $blogpost->updated_by =   auth()->user()->id;
        $blogpost->save();
        Session::flash('message', ['text'=>'Deleted successfully','type'=>'success']);
        return response()->json(['success'=>'Blog Post deleted successfully.']);
    }
}
