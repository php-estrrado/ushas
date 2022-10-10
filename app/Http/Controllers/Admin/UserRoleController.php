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
use App\Models\Modules;
// use App\Models\UserRoles;
use App\Models\Admin;
use App\Models\UserRole;

use App\Rules\Name;
use Validator;

class UserRoleController extends Controller
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
    
       public function userRole()
        { 
        $data['title']              =   'User Roles';
        $data['menu']               =   'user-roles';
       
        $data['userroles'] = UserRole::getUserRoles();
        // dd($data);
        return view('admin.user-roles.list',$data);
        }
       

        public function createRole()
        { 
        $data['title']              =   'Create User Role';
        $data['menu']               =   'create-user-role';
        $data['modules']              =   Modules::getModules();
        // dd($data);
        return view('admin.user-roles.create',$data);
        }

        public function editRole($role_id)
        { 
        $data['title']              =   'Edit User Role';
        $data['menu']               =   'edit-user-role';
        $data['userrole']              =   UserRole::where('id',$role_id)->first();
        $data['actions']              =    DB::table('usr_role_action')->where('usr_role_id',$role_id)->get();
        $data['modules']              =   Modules::getModules();
        return view('admin.user-roles.edit',$data);
        }
         public function viewRole($role_id)
        { 
        $data['title']              =   'Edit User Role';
        $data['menu']               =   'edit-user-role';
        $data['userrole']              =   UserRole::where('id',$role_id)->first();
        $data['actions']              =    DB::table('usr_role_action')->where('usr_role_id',$role_id)->get();
        $data['modules']              =   Modules::getModules();
        return view('admin.user-roles.view',$data);
        }

        public function roleSave(Request $request)
        { 
        $input = $request->all();
        // dd($input);


        if($input['id']>0){

        $validator= $request->validate([
        'usr_role_name'   =>  [
        'required',
        Rule::unique('usr_role_lk')->ignore($input['id'])->where('is_deleted',0),
        ],
        'usr_role_desc' => ['required', 'string']
        ], [], 
        [
        'usr_role_name' => 'Role Name',
        'usr_role_desc' => 'Role Description'
        ]);

        $roles_arr = [];
        $roles_arr['org_id'] = 1;
        $roles_arr['usr_role_name'] = $input['usr_role_name'];
        $roles_arr['usr_role_desc'] = $input['usr_role_desc'];
        $roles_arr['is_active'] = $input['is_active'];
        $roles_arr['is_deleted'] = 0;


        $UserRoles = UserRole::where('id',$input['id'])->update($roles_arr);

        $data['title']              =   'User Roles';
        $data['menu']               =   'user-roles';
        $data['userroles']              =   UserRole::where('is_deleted',NULL)->orWhere('is_deleted',0)->get();

        if($input['id']  ) {
            if( $input['module_changed'] ==1) {
        DB::table('usr_role_action')->where('usr_role_id',$input['id'])->update(array('is_deleted'=>1,'is_active'=>0));
        // dd($input);
        foreach ($input['modules'] as $mk => $mv) {

        $actions = [];
        $actions['org_id'] =1;
        $actions['usr_role_id'] =$input['id'];   
        $actions['module_id'] =$mk; 
        if(@$mv["'view'"] == 1) {$actions['view'] =1; }else { $actions['view'] =0; }
        if(@$mv["'edit'"] == 1) {$actions['edit'] =1; }else { $actions['edit'] =0; }
        if(@$mv["'delete'"] == 1) {$actions['delete'] =1; }else { $actions['delete'] =0; } 
        $actions['is_active'] = 1;
        $actions['is_deleted'] = 0;

        DB::table('usr_role_action')->insert($actions);
        }
        }

        Session::flash('message', ['text'=>'Role updated successfully','type'=>'success']);
        }else {

        Session::flash('message', ['text'=>'Role updation failed','type'=>'danger']);
        }


        }else{

        $validator= $request->validate([
        'usr_role_name'   =>  [
        'required',
        Rule::unique('usr_role_lk')->where('is_deleted',0),
        ],
        'usr_role_desc' => ['required', 'string']
        ], [], 
        [
        'usr_role_name' => 'Role Name',
        'usr_role_desc' => 'Role Description'
        ]);
        $roles_arr = [];
        $roles_arr['org_id'] = 1;
        $roles_arr['usr_role_name'] = $input['usr_role_name'];
        $roles_arr['usr_role_desc'] = $input['usr_role_desc'];
        $roles_arr['is_active'] = 1;
        $roles_arr['is_deleted'] = 0;


        $UserRoles = UserRole::create($roles_arr);
        $lastId = $UserRoles->id;
        $data['title']              =   'User Roles';
        $data['menu']               =   'user-roles';
        $data['userroles']              =   UserRole::where('is_deleted',NULL)->orWhere('is_deleted',0)->get();

        if($lastId) {

if(isset($input['modules'])) {
            foreach ($input['modules'] as $mk => $mv) {

        $actions = [];
        $actions['org_id'] =1;
        $actions['usr_role_id'] =$lastId;   
        $actions['module_id'] =$mk; 
        if(@$mv["'view'"] == 1) {$actions['view'] =1; }else { $actions['view'] =0; }
        if(@$mv["'edit'"] == 1) {$actions['edit'] =1; }else { $actions['edit'] =0; }
        if(@$mv["'delete'"] == 1) {$actions['delete'] =1; }else { $actions['delete'] =0; } 
        $actions['is_active'] = 1;
        $actions['is_deleted'] = 0;
        DB::table('usr_role_action')->insert($actions);
        }
}
        Session::flash('message', ['text'=>'Role created successfully','type'=>'success']);


        }else {

        Session::flash('message', ['text'=>'Role creation failed','type'=>'danger']);
        }

        }
        return redirect(route('admin.user-roles'));

        }


        public function roleDelete(Request $request)
        {
        $input = $request->all();

        if($input['id']>0) {
        $deleted =  UserRole::where('id',$input['id'])->update(array('is_deleted'=>1,'is_active'=>0));
        DB::table('usr_role_action')->where('usr_role_id',$input['id'])->update(array('is_deleted'=>1,'is_active'=>0));
        Admin::where('role_id',$input['id'])->update(array('is_active'=>0));
        Session::flash('message', ['text'=>'Role deleted successfully.','type'=>'success']);
        return true;
        }else {
        Session::flash('message', ['text'=>'Role failed to delete.','type'=>'danger']);
        return false;
        }

        }
           public function roleStatus(Request $request)
        {
        $input = $request->all();
        
        if($input['id']>0) {
        $deleted =  UserRole::where('id',$input['id'])->update(array('is_active'=>$input['status']));
        if($input['status'] ==0){
             Admin::where('role_id',$input['id'])->update(array('is_active'=>0));
        }
        return '1';
        }else {
        
        return '0';
        }
        
        }
    

   
}
