<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use DB;
use App\Models\Language;
use App\Models\UserRoles;
use App\Models\Admin;
use App\Rules\Name;
use Validator;

class LanguageController extends Controller
{
    public function __construct(){ $this->middleware('auth:admin'); }
    public function list(Request $request)
    {
        $data['title']              =   'Language List';
        $data['menuGroup']          =   'Settings';
        $data['menu']               =   'Language';
        $data['list']               =   Language::where('is_deleted',0)->orderBy('is_default','DESC')->get();
        return view('admin.language.page',$data);
    }
    public function addnew(Request $request)
    {
        $data['title']              =   'Language';
        $data['menuGroup']          =   'Settings';
        $data['menu']               =   'Language';
        $data['menutype']           =   'create';
        $data['lang']               =   '';
        return view('admin.language.create',$data);
    }

    public function insert(Request $request,$id=0)
    {
        if($id>0)
            {
            $validate= $request->validate([
            'language_name' => ['required', 'string','unique:glo_lang_lk,glo_lang_name,' . $id],
            'language_code' => ['required', 'string','max:4','unique:glo_lang_lk,glo_lang_code,' . $id],
            'language_desc' => ['nullable', 'string'],
            'status'=> ['required']
            ]);  
            $create = ['glo_lang_name'=>$request->language_name,
                       'glo_lang_code'=>$request->language_code,
                       'glo_lang_desc'=>$request->language_desc,
                       'is_active'=>$request->status,
                       'is_deleted'=>0,
                       'created_at'=>date("Y-m-d H:i:s"),
                       'updated_at'=>date("Y-m-d H:i:s")];  
                       
                     
            $insert_id = Language::where('id',$id)->update($create);
            
            Session::flash('message', ['text'=>'Language updated successfully','type'=>'success']);
            return redirect(url('admin/language'));  
            }
            else
            {
           $validate= $request->validate([
            'language_name' => ['required', 'string','unique:glo_lang_lk,glo_lang_name,1,is_deleted'],
            'language_code' => ['required', 'string','max:4','unique:glo_lang_lk,glo_lang_code,1,is_deleted'],
            'language_desc' => ['nullable', 'string'],
            'status'=> ['required']]);
        // if (Currency::where('currency_code', '=', $validate['currency_code'])->where('country_id',$validate['country'])->where('is_deleted', '=',0)->exists()) {
        //     Session::flash('message', ['text'=>'Currency Already Exist','type'=>'warning']);
        //     //return redirect(route('admin.newcurrency'));
        //     return redirect(route('admin.newcurrency'))->withInput();
        // }
        // else
        // {
            $create = ['glo_lang_name'=>$request->language_name,
                       'glo_lang_code'=>$request->language_code,
                       'glo_lang_desc'=>$request->language_desc,
                       'is_active'=>$request->status,
                       'is_deleted'=>0,
                       'created_at'=>date("Y-m-d H:i:s"),
                       'updated_at'=>date("Y-m-d H:i:s")];
            
            $insert_id = Language::create($create)->id;
            
           
            Session::flash('message', ['text'=>'Language created successfully','type'=>'success']);
            return redirect(url('admin/language'));  
                    
      //  }
      }
    }

    public function edit($id)
    {
        $data['title']              =   'Language';
        $data['menuGroup']          =   'Settings';
        $data['menu']               =   'Language';
        $data['menutype']           =   'Edit';
        $data['lang']               =   Language::where('is_deleted',0)->where('id',$id)->first();
        return view('admin.language.create',$data);
    }

    public function delete(Request $request)
    {
       //return $request->id;die;
       // $array=['is_deleted'=>1];
        $id=$request->id;
        $currency =  Language::where('id',$id)->first();
        $currency->is_deleted=1;
        $currency->save();
        //$update = Currency::where('id',$id)->udpate($array);
        Session::flash('message', ['text'=>'Deleted successfully','type'=>'success']);
    }
    
        public function statusUpdate(Request $request)
        {
        $input = $request->all();
        
        if($input['id']>0) {
        $deleted =  Language::where('id',$input['id'])->update(array('is_active'=>$input['status']));
        
        return '1';
        }else {
        
        return '0';
        }
        
        }
}
