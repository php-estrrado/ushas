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
use App\Models\Occasion;

use App\Models\Admin;


use App\Rules\Name;
use Validator;

class OccasionController extends Controller
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
    
    public function occasions()
        { 
        $data['title']              =   'Occasions';
        $data['menu']               =   'occasions';
        $data['occasions']              =   Occasion::getOccasions();
        // dd($data);
        return view('admin.occasions.list',$data);
        }

        public function createOccasion()
        { 
        $data['title']              =   'Create Occasion';
        $data['menu']               =   'create-Occasion';
        $data['language']      =    DB::table('glo_lang_lk')->where('is_active', 1)->get();
        // dd($data);
        return view('admin.occasions.create',$data);
        }

        public function editOccasion($occasion_id)
        { 
        $data['title']              =   'Edit Occasion';
        $data['menu']               =   'edit-Occasion';
        $data['occasion']              =  Occasion::getOccasion($occasion_id);
        $data['language']           =    DB::table('glo_lang_lk')->where('is_active', 1)->get();
       //  dd($data['brand'] );
        return view('admin.occasions.edit',$data);
        }
         public function viewOccasion($occasion_id)
        { 
        $data['title']              =   'View Occasion';
        $data['menu']               =   'view-occasion';
        $data['occasion']              =  Occasion::getOccasion($occasion_id);
        $data['language']           =    DB::table('glo_lang_lk')->where('is_active', 1)->get();
        // dd($data);
        return view('admin.occasions.view',$data);
        }

        public function occasionSave(Request $request)
        { 
        $input = $request->all();
        // dd($input);


        if($input['id']>0){

        $validator= $request->validate([
        'occasion'   =>  ['required','unique:occasion_wear,occasion,' . $input['id']],
      
		
        ], [], 
        [
        'occasion' => 'Occasion Name',
      
        
        ]);
		if($request->hasFile('occasion_image'))
            {
            $file=$request->file('occasion_image');
            $extention=$file->getClientOriginalExtension();
            $filename=time().'.'.$extention;
            $file->move(('uploads/storage/app/public/occasions/'),$filename);
            }
            else
            {
                $filename=$request->image_file;
            }

        if (DB::table('cms_content')->where('cnt_id',$input['occasion_name_cid'])->where('lang_id',$input['glo_lang_cid'])->exists()) {
        DB::table('cms_content')->where('cnt_id',$input['occasion_name_cid'])->where('lang_id',$input['glo_lang_cid'])
        ->update(['content' => $input['occasion']]);
        $occasion_name_cid=$input['occasion_name_cid'];
        } else {

        $latest = DB::table('cms_content')->orderBy('cnt_id', 'DESC')->first();
        $occasion_name_cid=++$latest->cnt_id;
        DB::table('cms_content')->insertGetId([
        
        'lang_id' => $input['glo_lang_cid'],
        'cnt_id'=>$occasion_name_cid,
        'content' => $input['occasion'],
        'is_active'=>1,
        'created_by'=>auth()->user()->id,
        'updated_by'=>auth()->user()->id,
        'is_deleted'=>0,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")
        ]);
        $occasion_name_cid =$occasion_name_cid;

        }

        $occasion_id = $input['id'];
        if($occasion_name_cid !="" && $occasion_id !="") {

        $occasion =  Occasion::where('id',$occasion_id)->update([
       
        'occasion' =>$input['occasion'],
		'image' => $filename,
        'occasion_name_cid' => $occasion_name_cid,
        'is_active'=>$input['is_active'],
        'is_deleted'=>0,
        'updated_by'=>auth()->user()->id,
        'updated_at'=>date("Y-m-d H:i:s")

        ]); 
        Session::flash('message', ['text'=>'Occasion updated successfully','type'=>'success']); 
        }else {
        Session::flash('message', ['text'=>'Occasion updation failed','type'=>'danger']);
        }


        $data['title']              =   'Occasions';
        $data['menu']               =   'Occasion';
        $data['occasion']              =   Occasion::getOccasions();




        }else{

        $validator= $request->validate([
        'occasion'   =>  ['required','unique:occasion_wear,occasion,1,is_deleted'],
        'occasion_image' => ['required']
        ], [], 
        [
        'occasion' => 'Occasion Name',
        'occasion_image' => 'Occasion Image',
        ]);

		if($request->hasFile('occasion_image'))
            {
            $file=$request->file('occasion_image');
            $extention=$file->getClientOriginalExtension();
            $filename=time().'.'.$extention;
            $file->move(('uploads/storage/app/public/occasions/'),$filename);
            }
            else
            {
                $filename=$request->image_file;
            }
        $latest = DB::table('cms_content')->orderBy('id', 'DESC')->first();
        $occasion_name_cid=++$latest->cnt_id;
       

        $occasion= DB::table('cms_content')->insertGetId([
     
        'lang_id' => $input['glo_lang_cid'],
        'cnt_id'=>$occasion_name_cid,
        'content' => $input['occasion'],
        'is_active'=>1,
        'created_by'=>auth()->user()->id,
        'updated_by'=>auth()->user()->id,
        'is_deleted'=>0,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")
        ]);


        if($occasion !="") {
        $occasion_ins =  Occasion::create([
     
        'occasion' =>$input['occasion'],
        'image' => $filename,
        'occasion_name_cid' => $occasion_name_cid,
        'is_active'=>$input['is_active'],
        'is_deleted'=>0,
        'created_by'=>auth()->user()->id,
        'modified_by'=>auth()->user()->id,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")

        ]);   
        $lastId = $occasion_ins->id;
        if($lastId) {
        Session::flash('message', ['text'=>'Occasion created successfully','type'=>'success']);  
        }else {
        Session::flash('message', ['text'=>'Occasion creation failed','type'=>'danger']);
        }
        }else {
        Session::flash('message', ['text'=>'Occasion creation failed','type'=>'danger']);
        }


        $data['title']              =   'Occasions';
        $data['menu']               =   'Occasion';
        $data['occasions']              =   Occasion::getOccasions();



        }
        return redirect(route('admin.occasions'));

        }


        public function OccasionDelete(Request $request)
        {
        $input = $request->all();

        if($input['id']>0) {
        $deleted =  Occasion::where('id',$input['id'])->update(array('is_deleted'=>1,'is_active'=>0));
        Session::flash('message', ['text'=>'Occasion deleted successfully.','type'=>'success']);
        return true;
        }else {
        Session::flash('message', ['text'=>'Occasion failed to delete.','type'=>'danger']);
        return false;
        }

        }
           public function OccasionStatus(Request $request)
        {
        $input = $request->all();
        
        if($input['id']>0) {
        $deleted =  Occasion::where('id',$input['id'])->update(array('is_active'=>$input['status']));
        
        return '1';
        }else {
        
        return '0';
        }
        
        }
    

   
}
