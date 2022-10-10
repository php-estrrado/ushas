<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PrdFields;
use App\Models\PrdFieldsValue;
use App\Models\InputType;
use App\Models\DataType;
use App\Models\Language;
use App\Models\CmsContent;

use Validator;

class FieldsController extends Controller
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

    public function fields(Request $request){
   //     if(permission(9,auth()->user()->role_id) == false){ return redirect('/admin'); }
        $post                   =   (object)$request->post();
        if(isset($post->viewType)){ $viewType = $post->viewType; }else{ $viewType = ''; }
        $data['title']          =   'Fields List';
        $data['menuGroup']      =   'masterGroup';
        $data['menu']           =   'fields';
        $data['active']         =   '';
        $fields             =   PrdFields::where('is_deleted',0);
        if(isset($post->active) &&  $post->active != ''){ 
            $fields         =   $fields->where('is_active',$post->active); 
            $data['active']     =   $post->active;
        }
        $data['fields']     =   $fields->orderBy('id','desc')->get();
        $data['values']         =   getDropdownData(InputType::get(),'identifier','name','');
        $data['inputs']         =   getDropdownData(DataType::get(),'identifier','name','');
        if($viewType == 'ajax') {   return view('admin.field.list',$data); }else{ return view('admin.field.page',$data); }
    }
    
    function field(Request $request, $id=0,$type=''){ 
     //   if(permission(9,auth()->user()->role_id) == false){ return redirect('/'); }
        if($type == 'view')     {   $title = 'View field'; }else if($id > 0){ $title = 'Edit field'; }else{ $title = 'Add field'; }
        $data['title']          =   $title;
        $data['field']           =   PrdFields::where('id',$id)->first();
        $data['fieldVals']       =   PrdFieldsValue::where('field_id',$id)->where('is_deleted',0)->get();
        $data['values']         =   getDropdownData(InputType::where('is_active',1)->get(),'identifier','name','');
        $data['inputs']         =   getDropdownData(DataType::where('is_active',1)->get(),'identifier','name','');
        $data['languages']      =   Language::where('is_active',1)->where('is_deleted',0)->get();
        $data['filters']        =   $request->post();
        if($type == 'view')     { 
            $data['dType']      =   DataType::where('identifier',$data['attr']->data_type)->first();
            $data['iType']      =   InputType::where('identifier',$data['attr']->type)->first();
            return view('admin.field.view',$data); 
        }else{ return view('admin.field.details',$data); }
    }
    
    function validateField(Request $request){ 
        $post                   =   (object)$request->post(); // echo '<pre>'; print_r($post); echo '</pre>'; die;
        $field                   =   $post->field;
        $rules                  =   ['name' => ['required', 'string','max:100'],'required' =>  ['required']];
        if($post->id > 0){      }   else{ $rules['type'] = ['required']; }
        if(isset($field['type'])){
            if($field['type']    ==  'text'){ $rules['data_type'] = ['required']; }
            if($field['type']    !=  'text' && $field['type'] != 'textarea'){ $rules['filter'] = ['required']; }
        }
        $validator              =   Validator::make($field,$rules);
        
        $f_validator = Validator::make($post->value, [
        "val.*"    => "required",
        ]);
        
        if ($f_validator->fails()){   foreach($f_validator->messages()->getMessages() as $k=>$row){ $error['field_val'] ="Field value option is required"; } }
        
        $existName              =   PrdFields::ValidateUnique('name',(object)$field,$post->id); 
        if ($validator->fails()){   foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; } }
        if($existName)          {   $error['name']    =   $errorMag[] = $existName; } 
        if(isset($error)) { return $error; }else{ return 'success'; }
    }
    
    
    function saveField(Request $request){
        $post                   =   (object)$request->post();
        $attrLabels             =   $post->field_title;
        $field                   =   $post->field; $filter = (object)$post->filter;
        if(isset($post->value)) {   $values = $post->value; }else{ $values = false; }
        if($values && isset($values['lang']))  {   $langs  = $values['lang']; }else{ $langs = false; }
      //  echo '<pre>'; print_r($langs); echo '</pre>'; die;
        if(isset($field['type']) &&  $field['type']        !=  'text' && $field['type'] != 'textarea'){ $field['data_type'] = 'string'; }
        if($post->id            >   0){ 
            $msg                =   'Field Updated Successfully!';
           PrdFields::where('id',$post->id)->update($field); $insId = $post->id;
        }else{  $insId           =   PrdFields::create($field)->id; $msg = 'Field Added Successfully!';}
        if($insId){ 
            $attrCntId          =   $this->addCmsContent($post->attr_cnt_id,$attrLabels[$post->attr_cnt_id]);
            $attrUpdate         =   PrdFields::where('id',$insId)->update(['name_cnt_id'=>$attrCntId]);
            if($values)  {   foreach($values['val'] as $k=>$val){
                if($values['id'][$k] > 0){ $valInsId = $values['id'][$k];
                    PrdFieldsValue::where('id',$values['id'][$k])->where('field_id',$insId)->update(['name'=>$val,'updated_by'=>auth()->user()->id]); 
                }else{ 
                    if(PrdFieldsValue::where('field_id',$insId)->where('name',$val)->count() > 0){
                        $valInsId   =   PrdFieldsValue::where('field_id',$insId)->where('name',$val)->first()->id;
                        PrdFieldsValue::where('field_id',$insId)->where('name',$val)->update(['is_deleted'=>0,'updated_by'=>auth()->user()->id]);
                    }else{ $valInsId = PrdFieldsValue::create(['field_id'=>$insId,'name'=>$val,'created_by'=>auth()->user()->id])->id; }
                }
                // if($langs){         foreach($langs as $v=>$lang){ $content[$v]=   $lang[$k]; } }
                // if($content)    {   
                //     $valCntId   =   $this->addCmsContent($values['cnt'][$k],$content); 
                //     $valUpdate  =   PrdFieldsValue::where('id',$valInsId)->update(['name_cnt_id'=>$valCntId]);

                // }
            } }
         //   return redirect('admin/attributes')->with('success',$msg);
            $data['title']      =   'Field List';
            $data['active']     =   $filter->active;
            $fields             =   PrdFields::where('is_deleted',0);
            if(isset($filter->active) &&  $filter->active != ''){ 
                $fields         =   $fields->where('is_active',$filter->active); 
            }
            $data['fields']     =   $fields->orderBy('id','desc')->get();
            $data['values']         =   getDropdownData(InputType::get(),'identifier','name','');
            $data['inputs']         =   getDropdownData(DataType::get(),'identifier','name','');
            return view('admin.field.list',$data);
        }
    }
    
    function addCmsContent($cntId, $contents){
        if(count($contents) > 0){ foreach($contents as $l=>$cnt){ $insId = false;
            $qry                =   CmsContent::where('cnt_id',$cntId)->where('is_deleted',0)->first();
            $query              =   CmsContent::where('cnt_id',$cntId)->where('is_deleted',0)->where('lang_id',$l)->first();
            if($query)          {   CmsContent::where('id',$query->id)->update(['content'=>$cnt,'updated_by'=>auth()->user()->id]); }
            else if($qry)       {   $insId   =   CmsContent::create(['cnt_id'=>$cntId,'lang_id'=>$l,'content'=>$cnt,'created_by'=>auth()->user()->id])->id; }
            else{
                $cms            =   CmsContent::orderBy('cnt_id','desc')->first(); if($cms){ $cntId = ($cms->cnt_id+1); }else{ $cntId = 1; }
                $insId          =   CmsContent::create(['cnt_id'=>$cntId,'lang_id'=>$l,'content'=>$cnt,'created_by'=>auth()->user()->id])->id;
            }
        } } return $cntId;
    }
    
    function updateStatus(Request $request){ 
    //    if(permission(9,auth()->user()->role_id) == false && permission(10,auth()->user()->role_id) == false && permission(11,auth()->user()->role_id) == false){ return redirect('/'); }
        $post               =   (object)$request->post(); 
        $result             =   PrdFields::where('id',$post->id)->update([$post->field => $post->value]);
            if($result){ return ['type'=>'success','id'=>$post->id]; }else{  return ['type'=>'warning','id'=>$post->id]; } 
    }
    //DELETE FIELD VALUE
    function deletefieldval(Request $request){ 
    //    if(permission(9,auth()->user()->role_id) == false && permission(10,auth()->user()->role_id) == false && permission(11,auth()->user()->role_id) == false){ return redirect('/'); }
        $post               =   (object)$request->post(); 
        $result             =   PrdFieldsValue::where('id',$post->id)->update(['is_deleted' => 1]);
            if($result){ return ['type'=>'success','id'=>$post->id]; }else{  return ['type'=>'warning','id'=>$post->id]; } 
    }
    function deleteAttr(Request $request){ return PrdFieldsValue::where('id',$request->post('id'))->update(['status'=>0]); }
}
