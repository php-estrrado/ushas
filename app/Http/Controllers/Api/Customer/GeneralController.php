<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Label;
use Session;
use DB;
use Carbon\Carbon;
use App\Rules\Name;
use Validator;

class GeneralController extends Controller
{
    public function language_list(Request $request)
    {
        $language=[];
        //LANGUAGE
            $lang=DB::table('glo_lang_lk')->where('is_active', 1)->get();
            foreach($lang as $key)
            {
                $lan['id']=$key->id;
                $lan['name']=$key->glo_lang_name;
                $lan['code']=$key->glo_lang_code;
                $language[]=$lan;
            }

          return ['httpcode'=>200,'status'=>'success','message'=>'Language List','data'=>['language'=>$language]];  
    }

    public function label_list(Request $request)
    {
        
        $validator=  Validator::make($request->all(),[
            'type'=>['required','in:web,app'],
            'lang_id'=>['nullable','numeric']
        ]);
        $lang=$request->lang_id;
        $label =[];
        if ($validator->fails()) 
        {    
          return ['httpcode'=>400,'status'=>'error','message'=>'Invalid parameters','data'=>['errors'=>$validator->messages()]];
        }
        else
        {
        $lbl = Label::where('is_active',1)->where('is_deleted',0)->where('label_for',$request->type)->get();
       // dd(count($lbl));
            foreach($lbl as $row)
            {
               $list['label_id']  = $row->id;
               $list['identifier']= $row->identifier;
               $list['label']     = $this->get_content($row->label_cid,$lang);
               $label[] = $list;
            }
            return ['httpcode'=>200,'status'=>'success','message'=>'Label List','data'=>['label'=>$label]]; 
        }
    }

    function get_content($field_id,$lang){ 
     
        if($lang=='')
        { 
        $language =DB::table('glo_lang_lk')->where('is_active', 1)->first();
        $language_id=$language->id;
        }
        else
        {
            $language_id=$lang;
        }
        $content_table=DB::table('cms_content')->where('cnt_id', $field_id)->where('lang_id', $language_id)->first();
        if(!empty($content_table)){ 
        $return_cont = $content_table->content;
        return $return_cont;
        }
        else
            { return false; }
        }
}
