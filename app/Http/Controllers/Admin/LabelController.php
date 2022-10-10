<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;
use App\Models\Label;
//use App\Models\;
use App\Rules\Name;
use Validator;


class LabelController extends Controller
{
    public function __construct(){
        $this->middleware('auth:admin');
    }
	
	public function list(Request $request){ 
	$post                       =   (object)$request->post(); $usrIds = []; 
        $data['title']              =   'Labels';
        $data['menuGroup']          =   '';
        $data['menu']               =   '';
        $data['vType']              =   '';
        if(isset($post->vType)      ==  'ajax'){
			$data['vType']          =   $post->vType;
			return Label::getListData($post);
		}else{ return view('admin.labels.list',$data); }
		
    }

    

    public function create(){
        $data['title']         =   'Labels';
        $data['menu']          =   '';
        $data['language']      =   DB::table('glo_lang_lk')->where('is_active', 1)->get();
        return view('admin.labels.create', $data);
    }
	
	public function labelContent(Request $request){
	$post =  (object)$request->post();
	$lang_id=$post->lang_id;
	$label_cnt_id=$post->label_cnt_id;
	$label_id=$post->label_id;
	if($label_id){
	$data['labels']           =    Label::where('id',$label_id)->first();
	}
	$data['language']      = DB::table('glo_lang_lk')->where('is_active', 1)->get();
									
	$data['lang_id']=$post->lang_id;
	$data['labelContent']=DB::table('cms_content')->where('cnt_id', $label_cnt_id)->where('lang_id', $lang_id)->first();
	return view('admin.labels.includes.contents',$data);
	}
	public function validateLabel(Request $request){
        $existName      =  $error = false;
		$post           =  (object)$request->post(); 
        
         		$rules          =   [
                'label_for' => 'required', 'string',
				'language'=> 'required',
				'labelcontent'=> 'required',
				];
        
		$validator = Validator::make($request->all() ,$rules);
		if($validator->fails()) {
            foreach($validator->messages()->getMessages() as $k=>$row){ 
			$error[$k] = $row[0]; 
		}
		}
		if($error) { return $error; }else{ return 'success'; } return 'success'; 
    }
	
	public function saveLabel(Request $request){

        $post  = (object)$request->post(); 
		$input = $request->all();
		$success=0;
        
        $label=$post->labelcontent;
        //dd($post->label_id);
			if($post->label_id){
			$success=2;	
			$label_id = $post->label_id;
				if (DB::table('cms_content')->where('cnt_id', $request->label_content_id)->where('lang_id', $request->language)->exists()) {
                
				DB::table('cms_content')
                ->where('cnt_id', $request->label_content_id)->where('lang_id', $request->language)
                ->update(['content' => $request->labelcontent]);
                $label_cid=$request->label_content_id;
            }else if (DB::table('cms_content')->where('cnt_id', $request->label_content_id)->exists()){
				DB::table('cms_content')->insertGetId(
                    [ 'lang_id' => $request->language,'cnt_id'=>$request->label_content_id,'content' => $request->labelcontent,'is_active'=>1,'created_by'=>auth()->user()->id,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
                );
                 $label_cid=$request->label_content_id;
			}
            else{	
				
                $latest = DB::table('cms_content')->orderBy('cnt_id', 'DESC')->first();
                $latest_label_cid=++$latest->cnt_id;
                 DB::table('cms_content')->insertGetId(
                    [ 'lang_id' => $request->language,'cnt_id'=>$latest_label_cid,'content' => $request->labelcontent,'is_active'=>1,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'updated_at'=>date("Y-m-d H:i:s")]
                );
                $label_cid =$latest_label_cid;
            }
			
			if($request->is_default==1){           
			$data1['label'] = $request->labelcontent;
			$data1['identifier'] = $request->identifier;
			}
			$data1['label_cid'] = $label_cid;
			
			$data1['is_active'] = $request->status;
			$data1['is_deleted'] = 0;
			$data1['updated_by'] = auth()->user()->id;
			$data1['updated_at'] = date("Y-m-d H:i:s");
						
           


            Label::where('id',$label_id)->update($data1);
				
				
			}else{
			$latest = DB::table('cms_content')->orderBy('cnt_id', 'DESC')->first();
			
			if($latest){
				
			$latest_label_cid=++$latest->cnt_id;
			}else{
				$latest_label_cid=1;
			}
			$latest_desc_cid =$latest_label_cid+1;
				$label=$post->labelcontent;
				$language=$post->language;

            $cat_cid = DB::table('cms_content')->insertGetId(
                [ 'lang_id' => $language,'cnt_id'=>$latest_label_cid,'content' => $label,'is_active'=>1,'created_by'=>auth()->user()->id,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
            );
            
			//$max=OrganizationCategory::max('sort_order');
			//$sort = $max+1;
            $catId=Label::create([
            'label_cid' => $latest_label_cid,
            'label'=>$label,
			'identifier'=>$request->identifier,
            'label_for' => $request->label_for,
            'is_active'=>$request->status,
            'is_deleted'=>0,
            'created_by'=>auth()->user()->id,
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
			])->id;
			if($catId){
				$success=1;
			}
			}
		return $success; 

    }


    public function editLabel($id){
        $data['title']              =   'Label';
        $data['menu']               =   '';
        $data['language']           =    DB::table('glo_lang_lk')->where('is_active', 1)->get();
									
        $data['labels']           =    Label::where('id',$id)->first();
		$label_id = $data['labels'] ->label_cid;
		$data['labelContent']=DB::table('cms_content')->where('cnt_id', $label_id)->first();
	   //dd($data['labelContent']);
	   return view('admin.labels.create',$data);
    }

    
    public function labelStatus(Request $request){
        $input = $request->all();
        if($input['id']>0) {
        Label::where('id',$input['id'])->update(array('is_active'=>$input['status']));
        
        return '1';
        }else {
        
        return '0';
        }
        
    }
	public function deleteLabel(Request $request){
        $post               =  (object)$request->post(); 
        $label_id = $post->label_id;
		Label::where('id',$label_id)->update(['is_deleted' => 1]);
		return '1';
	}
	
	
    
}


