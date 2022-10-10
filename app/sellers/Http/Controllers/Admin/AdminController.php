<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManagerStatic as Image;

use Session;
use DB;
use App\Models\Modules;
// use App\Models\UserRoles;
use App\Models\Admin;
use App\Models\UserRole;

use App\Rules\Name;
use Validator;

class AdminController extends Controller
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
    public function index()
    { 
        return view('admin.admin');
    }
    
    function profile(){
        return view('admin.profile');
    }

    function validateUser(Request $request){
        $profile                =   $request->post('profile');
        $rules                  =   [
                                        'fname'                 =>  ['required', 'string','max:100', new Name],
                                        'email'                 =>  'required|string|email|max:100',
                                        'phone'                 =>  'required|numeric|digits_between:7,12',
                                    ];
        if($profile['lname']   !=  ''){ $rules['lname']        =   ['required', 'string','max:100', new Name]; }
        $validator              =   Validator::make($profile,$rules);
        $validEmail             =   Admin::ValidateUnique('email',(object)$profile,auth()->user()->id);
        $validPhone             =   Admin::ValidatePhone('phone',(object)$profile,auth()->user()->id);
        if ($validator->fails()) {
           foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; }
        }
        if($validEmail){ $error['email']    =   $validEmail; }
        if($validPhone){ $error['phone']    =   $validPhone; }
        if(isset($error)) { return $error; }else{ return 'success'; }
    }
    
    function saveProfile(Request $request){
        $profile                =   Admin::where('id',auth()->user()->id)->update($request->post('profile'));
        if($profile){   return      back()->with('success',' Profile updated successfully! '); }else{ return back(); }
    }
    
    function validatePassword(Request $request){
        $post                   =   (object)$request->post();
        $validator              =   Validator::make($request->post(),['curr_password' => 'required|string|regex:/^\S*$/u','password' => 'required|string|min:6|regex:/^\S*$/u|confirmed']);
        $user                   =   Admin::where('id',auth()->user()->id)->first();
        if ($validator->fails()) {
           foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; }
        }
        if (Hash::check($request->curr_password, $user->getOriginal('password'))) {
        }else{ $error['curr_password'] = 'Invalid current password'; }
        if(isset($error)) { return $error; }else{ return 'success';  }
    }
    
    function savePassword(Request $request){
        $res        =   Admin::where('id',auth()->user()->id)->update(['password' => Hash::make($request->post('password'))]);
        if($res){ return 'success'; }else{ return 'error'; }
    }
    
    function adminLogout(){ 
        Auth::logout(); return redirect('admin/login');
    }
    
    
    
        
    
        
        //admins list
        public function admins()
        { 
        $data['title']              =   'Admins';
        $data['menu']               =   'admin-list';
        $data['admins']              =   Admin::where('role_id',2)->where(function ($query) {
    $query->where('is_deleted', '=', NULL)
          ->orWhere('is_deleted', '=', 0);})->get();
        // dd($data);
        return view('admin.admins.list',$data);
        }

              public function createAdmin()
        { 
        $data['title']              =   'Create Admin';
        $data['menu']               =   'create-admin';
        $data['modules']              =   Admin::where('is_deleted',NULL)->orWhere('is_deleted',0)->get();
        return view('admin.admins.create',$data);
        }

        public function editAdmin($role_id)
        { 
        $data['title']              =   'Edit Admin';
        $data['menu']               =   'edit-admin';
        $data['admin']              =   Admin::where('id',$role_id)->first();

        return view('admin.admins.edit',$data);
        }
    
        public function viewAdmin($role_id)
        { 
        $data['title']              =   'View Admin';
        $data['menu']               =   'view-admin';
        $data['admin']              =   Admin::where('id',$role_id)->first();

        return view('admin.admins.view',$data);
        }
        public function adminSave(Request $request){
        $post           =   (object)$request->post();
        // dd($post);
        $user           =   $post->user; 
        if($post->id    >   0){
        if($post->user['password']      ==  ''){ unset($post->user['password']); }
        else{ $post->user['password']   =   Hash::make($post->user['password']); }
        $post->user['updated_at']       =   date('Y-m-d H:i:s');
        $insId      =   $post->id; Admin::where('id',$post->id)->update($post->user);   
        $msg        =   'Admin details updated successfully!';
        }else{
        
        $rules                  =   [
        
        'email'                 =>  'required|unique:admins|email|max:100',
        'phone'                 =>  'required|numeric|unique:admins',
        ];
        $validator              =   Validator::make($user,$rules);
        if ($validator->fails()) {
        foreach($validator->messages()->getMessages() as $k=>$row){  $error[$k] = $row[0];
        Session::flash('message', ['text'=>$row[0],'type'=>'danger']); }
        
        return redirect(route('admin.admins'));
        }
        
        $post->user['password']         =   Hash::make($post->user['password']);
        $post->user['created_at']       =   date('Y-m-d H:i:s');
        $insId      =   Admin::create($post->user)->id;
        $msg        =   'Admin details added successfully!';
        }
        if($insId){
        
        if($request->file('avatar') && $request->file('avatar') != ''){
        $image = $request->file('avatar');
        $input['imagename'] = 'avatar.'.$image->extension();
        $path               =   '/app/public/user/'.$insId;
        $destinationPath = storage_path($path.'/thumbnail');
        $img = Image::make($image->path());
        if (!file_exists($destinationPath)) { mkdir($destinationPath, 755, true);}
        $img->resize(150, 150, function ($constraint) {
        $constraint->aspectRatio();
        })->save($destinationPath.'/'.$input['imagename']);
        $destinationPath = storage_path($path);
        $image->move($destinationPath, $input['imagename']);
        Admin::where('id',$insId)->update(['avatar'=>$path.'/'.$input['imagename']]); 
        }
        $data['title']              =   'Admins';
        $data['menu']               =   'admin-list';
        $data['admins']              =   Admin::where('role_id',2)->where(function ($query) {
        $query->where('is_deleted', '=', NULL)
        ->orWhere('is_deleted', '=', 0);})->get();
        // dd($data);
        Session::flash('message', ['text'=>$msg,'type'=>'success']);
        return redirect(route('admin.admins'));
        
        }else{ 
        
        Session::flash('message', ['text'=>"Failed to save details.",'type'=>'danger']);
        
        return redirect(route('admin.admins'));
        }
        }
    
         
        public function adminStatus(Request $request)
        {
        $input = $request->all();
        
        if($input['id']>0) {
        $deleted =  Admin::where('id',$input['id'])->update(array('is_active'=>$input['status']));
        
        return '1';
        }else {
        
        return '0';
        }
        
        }
    
}
