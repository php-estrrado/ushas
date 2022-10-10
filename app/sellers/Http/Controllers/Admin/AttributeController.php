<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PrdAttribute;
use App\Models\PrdAttributeValue;
use App\Models\InputType;
use App\Models\DataType;
use App\Models\Language;
use App\Models\CmsContent;

use Validator;

class AttributeController extends Controller
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

    public function attributes(Request $request){
   //     if(permission(9,auth()->user()->role_id) == false){ return redirect('/admin'); }
        $post                   =   (object)$request->post();
        if(isset($post->viewType)){ $viewType = $post->viewType; }else{ $viewType = ''; }
        $data['title']          =   'Attribute List';
        $data['menuGroup']      =   'masterGroup';
        $data['menu']           =   'attributes';
        $data['active']         =   '';
        $attributes             =   PrdAttribute::where('is_deleted',0);
        if(isset($post->active) &&  $post->active != ''){ 
            $attributes         =   $attributes->where('is_active',$post->active); 
            $data['active']     =   $post->active;
        }
        $data['attributes']     =   $attributes->orderBy('id','desc')->get();
        $data['values']         =   getDropdownData(InputType::get(),'identifier','name','');
        $data['inputs']         =   getDropdownData(DataType::get(),'identifier','name','');
        if($viewType == 'ajax') {   return view('admin.attribute.list',$data); }else{ return view('admin.attribute.page',$data); }
    }
    
    function attribute(Request $request, $id=0,$type=''){ 
     //   if(permission(9,auth()->user()->role_id) == false){ return redirect('/'); }
        if($type == 'view')     {   $title = 'View Attribute'; }else if($id > 0){ $title = 'Edit Attribute'; }else{ $title = 'Add Attribute'; }
        $data['title']          =   $title;
        $data['attr']           =   PrdAttribute::where('id',$id)->first();
        $data['attrVals']       =   PrdAttributeValue::where('attr_id',$id)->where('is_deleted',0)->get();
        $data['values']         =   getDropdownData(InputType::where('is_active',1)->get(),'identifier','name','');
        $data['inputs']         =   getDropdownData(DataType::where('is_active',1)->get(),'identifier','name','');
        $data['languages']      =   Language::where('is_active',1)->where('is_deleted',0)->get();
        $data['filters']        =   $request->post();
        if($type == 'view')     { 
            $data['dType']      =   DataType::where('identifier',$data['attr']->data_type)->first();
            $data['iType']      =   InputType::where('identifier',$data['attr']->type)->first();
            return view('admin.attribute.view',$data); 
        }else{ return view('admin.attribute.details',$data); }
    }
    
    function validateAttr(Request $request){ 
        $post                   =   (object)$request->post(); // echo '<pre>'; print_r($post); echo '</pre>'; die;
        $attr                   =   $post->attr;
        $rules                  =   ['name' => ['required', 'string','max:100'],'required' =>  ['required']];
        if($post->id > 0){      }   else{ $rules['type'] = ['required']; }
        if(isset($attr['type'])){
            if($attr['type']    ==  'text'){ $rules['data_type'] = ['required']; }
            if($attr['type']    !=  'text' && $attr['type'] != 'textarea'){ $rules['filter'] = ['required']; }
        }
        $validator              =   Validator::make($attr,$rules);
        $existName              =   PrdAttribute::ValidateUnique('name',(object)$attr,$post->id); 
        if ($validator->fails()){   foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; } }
     //   if($existName)          {   $error['name']    =   $errorMag[] = $existName; } 
        if(isset($error)) { return $error; }else{ return 'success'; }
    }
    
    
    function saveAttr(Request $request){
        $post                   =   (object)$request->post();
        $attrLabels             =   $post->attr_title;
        $attr                   =   $post->attr; $filter = (object)$post->filter;
        if(isset($post->value)) {   $values = $post->value; }else{ $values = false; }
        if($values && isset($values['lang']))  {   $langs  = $values['lang']; }else{ $langs = false; }
      //  echo '<pre>'; print_r($langs); echo '</pre>'; die;
        if(isset($attr['type']) &&  $attr['type']        !=  'text' && $attr['type'] != 'textarea'){ $attr['data_type'] = 'string'; }
        if($post->id            >   0){ 
            $msg                =   'Attribute Updated Successfully!';
           PrdAttribute::where('id',$post->id)->update($attr); $insId = $post->id;
        }else{  $insId           =   PrdAttribute::create($attr)->id; $msg = 'Attribute Added Successfully!';}
        if($insId){ 
            $attrCntId          =   $this->addCmsContent($post->attr_cnt_id,$attrLabels[$post->attr_cnt_id]);
            $attrUpdate         =   PrdAttribute::where('id',$insId)->update(['name_cnt_id'=>$attrCntId]);
            if($values)  {   foreach($values['val'] as $k=>$val){
                if($values['id'][$k] > 0){ $valInsId = $values['id'][$k];
                    PrdAttributeValue::where('id',$values['id'][$k])->where('attr_id',$insId)->update(['name'=>$val,'updated_by'=>auth()->user()->id]); 
                }else{ 
                    if(PrdAttributeValue::where('attr_id',$insId)->where('name',$val)->count() > 0){
                        $valInsId   =   PrdAttributeValue::where('attr_id',$insId)->where('name',$val)->first()->id;
                        PrdAttributeValue::where('attr_id',$insId)->where('name',$val)->update(['is_deleted'=>0,'updated_by'=>auth()->user()->id]);
                    }else{ $valInsId = PrdAttributeValue::create(['attr_id'=>$insId,'name'=>$val,'created_by'=>auth()->user()->id])->id; }
                }
                if($langs){         foreach($langs as $v=>$lang){ $content[$v]=   $lang[$k]; } }
                if($content)    {   
                    $valCntId   =   $this->addCmsContent($values['cnt'][$k],$content); 
                    $valUpdate  =   PrdAttributeValue::where('id',$valInsId)->update(['name_cnt_id'=>$valCntId]);

                }
            } }
         //   return redirect('admin/attributes')->with('success',$msg);
            $data['title']      =   'Attribute List';
            $data['active']     =   $filter->active;
            $attributes             =   PrdAttribute::where('is_deleted',0);
            if(isset($filter->active) &&  $filter->active != ''){ 
                $attributes         =   $attributes->where('is_active',$filter->active); 
            }
            $data['attributes']     =   $attributes->orderBy('id','desc')->get();
            $data['values']         =   getDropdownData(InputType::get(),'identifier','name','');
            $data['inputs']         =   getDropdownData(DataType::get(),'identifier','name','');
            return view('admin.attribute.list',$data);
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
        $result             =   PrdAttribute::where('id',$post->id)->update([$post->field => $post->value]);
            if($result){ return ['type'=>'success','id'=>$post->id]; }else{  return ['type'=>'warning','id'=>$post->id]; } 
    }
    function deleteAttr(Request $request){ return AttributeValue::where('id',$request->post('id'))->update(['status'=>0]); }
}
