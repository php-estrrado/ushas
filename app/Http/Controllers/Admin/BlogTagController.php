<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Session;
use App\Models\BlogTag;

class BlogTagController extends Controller
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
        $data['title']        =   'Blog Tags';
        $data['menu']         =   'Blog Tags';
        $data['blog_tags']    =    BlogTag::where('is_deleted',NULL)->orWhere('is_deleted',0)->get();
        return view('admin.blog.tag.list',$data);
    }

    public function create(){ 
        $data['title']              =   'Create Blog Tag';
        $data['menu']               =   'create-blog-tag';
        return view('admin.blog.tag.create',$data);
    }

    public function save(Request $request){ 

        $rules = array(
            'bt_name'        => 'required|string|unique:blog_tags,bt_name,NULL,bt_id,is_deleted,0',
            'bt_description' => 'required',
        );
        $niceNames = ['bt_name' => 'Tag Name','bt_description' => 'Tag Description']; 
        $validator = Validator::make($request->all(), $rules,[],$niceNames);
        if ($validator->fails()) {
            return redirect(route('admin.blognewtag'))->withErrors($validator)->withInput($request->except('password'));
        }
        else
        {
            $blogtag = new BlogTag;
            $blogtag->bt_name              =   $request->get('bt_name');
            $blogtag->bt_description       =   $request->get('bt_description');
            $blogtag->is_active            =   $request->get('status');
            $blogtag->created_by           =   auth()->user()->id;
            $blogtag->updated_by           =   auth()->user()->id;
            $blogtag->save();
            Session::flash('message', ['text'=>'Created successfully','type'=>'success']);
            return redirect(route('admin.blogtags'));
        }

    }

    public function edit($btag_id){
        $data['title']          =   'Edit Blog Tag';
        $data['menu']           =   'edit-blog-tag';
        $data['blog_tag']       =    BlogTag::where('bt_id',$btag_id)->Where('is_deleted',0)->first();
        return view('admin.blog.tag.edit',$data);
    }

    public function update(Request $request,$btag_id){ 
        $rules = array(
            'bt_name'        => 'required|string|unique:blog_tags,bt_name,'.$btag_id.',bt_id,is_deleted,0',
            'bt_description' => 'required',
        );
        $niceNames = ['bt_name' => 'Tag Name','bt_description' => 'Tag Description']; 
        $validator = Validator::make($request->all(), $rules,[],$niceNames);
        if ($validator->fails()) {
            return redirect(route('admin.blogedittag',$btag_id))->withErrors($validator)->withInput($request->except('password'));
        }
        else
        {
            $blogtag = BlogTag::find($btag_id);
            $blogtag->bt_name              =   $request->get('bt_name');
            $blogtag->bt_description       =   $request->get('bt_description');
            $blogtag->is_active            =   $request->get('status');
            $blogtag->updated_by           =   auth()->user()->id;
            $blogtag->save();
            Session::flash('message', ['text'=>'Updated successfully','type'=>'success']);
            return redirect(route('admin.blogtags'));
        }

    }

    public function updatestatus(Request $request){ 
        $blogtag = BlogTag::find($request->btag_id);
        $blogtag->is_active = $request->status;
        $blogtag->updated_by =   auth()->user()->id;
        $blogtag->save();
        return response()->json(['success'=>'Blog Tag status change successfully.']);
    }

    public function delete(Request $request){
        $blogtag = BlogTag::find($request->btag_id);
        $blogtag->is_active  = '0';
        $blogtag->is_deleted = '1';
        $blogtag->deleted_by =   auth()->user()->id;
        $blogtag->updated_by =   auth()->user()->id;
        $blogtag->save();
        Session::flash('message', ['text'=>'Deleted successfully','type'=>'success']);
        return response()->json(['success'=>'Blog Tag deleted successfully.']);
    }
}
