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

class ModulesController extends Controller
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
    
        public function modules()
        { 
        $data['title']              =   'Modules';
        $data['menu']               =   'modules';
        $data['modules']              =   Modules::getAllModules();
        $data['active_modules']              =   Modules::getModules();
        // dd($data);
        return view('admin.modules',$data);
        }

        public function moduleSave(Request $request)
        {
        $input = $request->all();
        // dd( $input);
        if($input['id']>0){

            if($input['link'] !="#") {
                  $validator=  $request->validate([
        'link'   =>  [
        'required',
        Rule::unique('module')->ignore($input['id']),
        ]
        ]); 
            }
       $validate= $request->validate([
            'module_name' => ['required', 'string'],
            'class'=> ['required'],
            'link'=> ['required', 'string']
        ]);

        $input = $request->except('_token');
        $Modules = Modules::where('id',$input['id'])->update($input);
        // dd($Modules);
        Session::flash('message', ['text'=>'Module updated successfully','type'=>'success']);
        }else {
            // dd($input);
            if($input['link'] !="#") {
 $validator= $request->validate([
            'module_name' => ['required', 'string'],
            'class'=> ['required'],
        'link'   =>  [
        'required',
         Rule::unique('module'),
        ]
        ]);
}else {
     $validator= $request->validate([
            'module_name' => ['required', 'string'],
            'class'=> ['required'],
        'link'   =>  [
        'required',
       
        ]
        ]);
}
        // dd($input);
        $input['org_id'] =1;
        $input['is_deleted'] =0;
        $Modules = Modules::create($input);
        Session::flash('message', ['text'=>'Module created successfully','type'=>'success']);
        }



        return redirect(route('admin.modules'));
        }

        public function moduleDelete(Request $request)
        {
        $input = $request->all();

        if($input['id']>0) {
        $deleted =  Modules::where('id',$input['id'])->update(array('is_deleted'=>1));
        Session::flash('message', ['text'=>'Module deleted successfully.','type'=>'success']);
        return true;
        }else {
        Session::flash('message', ['text'=>'Module failed to delete.','type'=>'danger']);
        return false;
        }

        }
    
        public function moduleStatus(Request $request)
        {
        $input = $request->all();

        if($input['id']>0) {
        $deleted =  Modules::where('id',$input['id'])->update(array('is_active'=>$input['status']));
        $childs =  Modules::where('parent',$input['id'])->update(array('is_active'=>$input['status']));
        
        return '1';
        }else {
        
        return '0';
        }

        }
    public function sort_order(Request $request)
    {
        $ids_arr = explode(",",$request->new_order);
        // dd($ids_arr);
       
        for($i=1;$i<=count($ids_arr);$i++) 
        {
            Modules::where('id', $ids_arr[$i-1])
            ->update(['sort' => $i]);
            
        }
        Session::flash('message', ['text'=>'Sorted successfully','type'=>'success']);
       return redirect(route('admin.modules'));

    }  

   
}
