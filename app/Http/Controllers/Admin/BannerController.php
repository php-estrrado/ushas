<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use Intervention\Image\Facades\Image;

use App\Models\Language;
use App\Models\Banner;
use App\Models\BannerType;

use DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

use Validator;
use Session;

class BannerController extends Controller{
     public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function banners()
    { 

        $data['title']              =   'Banners';
        $data['menu']               =   'banner';
        $data['banners']           =  Banner::getBanners();
        
        // dd($data);
        return view('admin.banners.list',$data);
    }
    
   

     function createBanner(){
        $data['title']              =   'Create Banner';
        $data['menu']               =   'create-banner';
        $data['language']      =    DB::table('glo_lang_lk')->where('is_active', 1)->get();
        $data['banner_types']           =  BannerType::getBannerTypes();
       
        // dd($data);
        return view('admin.banners.create',$data);
    }

         function editBanner($bn_id){
        $data['title']              =   'Edit Banner';
        $data['menu']               =   'edit-banner';
        $data['language']      =    DB::table('glo_lang_lk')->where('is_active', 1)->get();
        $data['banner']      = Banner::getBannerData($bn_id);
        $data['banner_types']           =  BannerType::getBannerTypes();
      
        // dd($data);
        return view('admin.banners.edit',$data);
    }


     function saveBanner(Request $request){
       $input = $request->all();
        // dd($input); 
       $validator= $request->validate([
        'caption'   =>  ['required'],
    
        ], [], 
        [
    
        'caption' => 'Caption',
        // 'prd_id' => 'Product',

        ]);
       $images                 =   $request->file('image'); 
       if($input['id']>0) {


        if (DB::table('cms_content')->where('cnt_id',$input['title_cnt_id'])->where('lang_id',$input['glo_lang_cid'])->exists()) {
        DB::table('cms_content')->where('cnt_id',$input['title_cnt_id'])->where('lang_id',$input['glo_lang_cid'])
        ->update(['content' => $input['caption']]);
        $caption_cid=$input['title_cnt_id'];
        } else {

        $latest = DB::table('cms_content')->orderBy('id', 'DESC')->first();
        $caption_cid=++$latest->cnt_id;
  

        $caption_name= DB::table('cms_content')->insertGetId([
        'org_id' => 1, 
        'lang_id' => $input['glo_lang_cid'],
        'cnt_id'=>$caption_cid,
        'content' => $input['caption'],
        'is_active'=>1,
        'created_by'=>auth()->user()->id,
        'updated_by'=>auth()->user()->id,
        'is_deleted'=>0,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")
        ]);
        }
        if (DB::table('cms_content')->where('cnt_id',$input['alt_text_cid'])->where('lang_id',$input['glo_lang_cid'])->exists()) {
        DB::table('cms_content')->where('cnt_id',$input['alt_text_cid'])->where('lang_id',$input['glo_lang_cid'])
        ->update(['content' => $input['alt_text']]);
        $alttext_cid=$input['alt_text_cid'];
        } else {

        $latest = DB::table('cms_content')->orderBy('id', 'DESC')->first();
        $alttext_cid=++$latest->cnt_id;
  

        $caption_name= DB::table('cms_content')->insertGetId([
        'org_id' => 1, 
        'lang_id' => $input['glo_lang_cid'],
        'cnt_id'=>$alttext_cid,
        'content' => $input['alt_text'],
        'is_active'=>1,
        'created_by'=>auth()->user()->id,
        'updated_by'=>auth()->user()->id,
        'is_deleted'=>0,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")
        ]);
        }

          if (DB::table('cms_content')->where('cnt_id',$input['btn_label_cid'])->where('lang_id',$input['glo_lang_cid'])->exists()) {
        DB::table('cms_content')->where('cnt_id',$input['btn_label_cid'])->where('lang_id',$input['glo_lang_cid'])
        ->update(['content' => $input['btn_text']]);
        $btn_label_cid=$input['btn_label_cid'];
        } else {

        $latest = DB::table('cms_content')->orderBy('id', 'DESC')->first();
        $btn_label_cid=++$latest->cnt_id;
  

        $caption_name= DB::table('cms_content')->insertGetId([
        'org_id' => 1, 
        'lang_id' => $input['glo_lang_cid'],
        'cnt_id'=>$btn_label_cid,
        'content' => $input['btn_text'],
        'is_active'=>1,
        'created_by'=>auth()->user()->id,
        'updated_by'=>auth()->user()->id,
        'is_deleted'=>0,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")
        ]);
        }
        if($caption_cid !="") {


        $banner_arr = [];
        $banner_arr['org_id'] = 1;
        $banner_arr['banner_id'] = $input['banner_type'];
        $banner_arr['identifier'] = $input['caption'];
        $banner_arr['title'] = $input['caption'];
        $banner_arr['title_cnt_id'] = $caption_cid;
        $banner_arr['desc_cnt_id'] = $caption_cid;
        $banner_arr['alt_text'] = $alttext_cid;

        $banner_arr['btn_label'] = $btn_label_cid;
        // $banner_arr['prd_id'] = $prd_id;
        $banner_arr['btn_link'] = $input['btn_link'];

        // $banner_arr['media'] = $input['offer_value'];
        $banner_arr['is_active'] = $input['is_active'];
        $banner_arr['is_deleted'] = 0;


        $banner_arr['updated_by'] = auth()->user()->id;
        $banner_arr['updated_at'] = date("Y-m-d H:i:s");

        $existing_arr ="";
        if($input['media_type'] =="image") {

        $media_type="image"; 
        $banner_arr['upload_type'] = $media_type;
        $banner_arr['media_type'] = $media_type;
       if(isset($input['existing'])){ $existing_arr =  implode(",",$input['existing']); } 
        if($images){ $m = 0; $img_arr = array();
        foreach($images as $k=>$image){

        $m++;
        $imgName            =   time()."_".$m.'.'.$image->extension();
        $img_arr[]          =$imgName;
        $path               =   '/app/public/banner/';
        $destinationPath    =   storage_path($path.'/thumb');
        $img                =   Image::make($image->path());
        if(!file_exists($destinationPath)) { mkdir($destinationPath, 755, true);}
        $img->resize(250, 250, function($constraint){ $constraint->aspectRatio(); })->save($destinationPath.'/'.$imgName);
        $destinationPath    =   storage_path($path);
        $image->move($destinationPath, $imgName);
        $imgUpload          =   uploadFile($path,$imgName);
        $thumbUpload        =   uploadFile($path.'/thumb',$imgName);
        // if($imgUpload){


        // }
        } 
        if($existing_arr !="")
        {
        $banner_arr['media'] = $existing_arr.",".implode(",", $img_arr);
        $banner_arr['thumb'] = $existing_arr.",".implode(",", $img_arr);
        }else {
        $banner_arr['media'] = implode(",", $img_arr);
        $banner_arr['thumb'] = implode(",", $img_arr);

        }
        
        

        }else {
         $banner_arr['media'] = $existing_arr;
        $banner_arr['thumb'] = $existing_arr;
        }
        $bannerId                  =   Banner::where('id',$input['id'])->update($banner_arr);
        }else { 


        $media_type="video";
        $banner_arr['upload_type'] = $media_type;
        $banner_arr['media_type'] = $media_type;
        if(isset($input['media_link']))
        {
        $banner_arr['media'] = $input['media_link'];
        $bannerId                  =   Banner::where('id',$input['id'])->update($banner_arr);
        } 


        }

      
        Session::flash('message', ['text'=>'Banner updated successfully','type'=>'success']); 

        }else {
        Session::flash('message', ['text'=>'Banner updation failed','type'=>'danger']);
        }



       }else {



        $latest = DB::table('cms_content')->orderBy('id', 'DESC')->first();
        $caption_cid=++$latest->cnt_id;
        $btntext_cid=$caption_cid+1;
        $alttext_cid=$btntext_cid+1;
  

        $caption_name= DB::table('cms_content')->insertGetId([
        'org_id' => 1, 
        'lang_id' => $input['glo_lang_cid'],
        'cnt_id'=>$caption_cid,
        'content' => $input['caption'],
        'is_active'=>1,
        'created_by'=>auth()->user()->id,
        'updated_by'=>auth()->user()->id,
        'is_deleted'=>0,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")
        ]);
        if(isset($input['btn_text'])) {
         $btn_name= DB::table('cms_content')->insertGetId([
        'org_id' => 1, 
        'lang_id' => $input['glo_lang_cid'],
        'cnt_id'=>$btntext_cid,
        'content' => $input['btn_text'],
        'is_active'=>1,
        'created_by'=>auth()->user()->id,
        'updated_by'=>auth()->user()->id,
        'is_deleted'=>0,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")
        ]);   
        }else {
            $btntext_cid = 0;
        }
        if(isset($input['alt_text'])) {
         $btn_name= DB::table('cms_content')->insertGetId([
        'org_id' => 1, 
        'lang_id' => $input['glo_lang_cid'],
        'cnt_id'=>$alttext_cid,
        'content' => $input['alt_text'],
        'is_active'=>1,
        'created_by'=>auth()->user()->id,
        'updated_by'=>auth()->user()->id,
        'is_deleted'=>0,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")
        ]);   
        }else {
            $alttext_cid = 0;
        }
        
        $banner_arr = [];
        $banner_arr['org_id'] = 1;
        $banner_arr['banner_id'] = $input['banner_type'];
        $banner_arr['identifier'] = $input['caption'];
        $banner_arr['title'] = $input['caption'];
        $banner_arr['title_cnt_id'] = $caption_cid;
        $banner_arr['desc_cnt_id'] = $caption_cid;
        $banner_arr['alt_text'] = $alttext_cid;

        $banner_arr['btn_label'] = $btntext_cid;
        // $banner_arr['prd_id'] = $prd_id;
        $banner_arr['btn_link'] = $input['btn_link'];

        // $banner_arr['media'] = $input['offer_value'];
        $banner_arr['is_active'] = $input['is_active'];
        $banner_arr['is_deleted'] = 0;
        $banner_arr['created_at'] =  date("Y-m-d H:i:s");
        $banner_arr['created_by'] = auth()->user()->id;
 
        if($input['media_type'] =="image") {

        $media_type="image"; 
        $banner_arr['upload_type'] = $media_type;
        $banner_arr['media_type'] = $media_type;

           if($images){ $m = 0; $img_arr = array();
            foreach($images as $k=>$image){

                $m++;
            $imgName            =   time()."_".$m.'.'.$image->extension();
            $img_arr[]          =$imgName;
            $path               =   '/app/public/banner/';
            $destinationPath    =   storage_path($path.'/thumb');
            $img                =   Image::make($image->path());
            if(!file_exists($destinationPath)) { mkdir($destinationPath, 755, true);}
            $img->resize(250, 250, function($constraint){ $constraint->aspectRatio(); })->save($destinationPath.'/'.$imgName);
            $destinationPath    =   storage_path($path);
            $image->move($destinationPath, $imgName);
            $imgUpload          =   uploadFile($path,$imgName);
            $thumbUpload        =   uploadFile($path.'/thumb',$imgName);
            // if($imgUpload){
            
               
            // }
        } 
        $banner_arr['media'] = implode(",", $img_arr);
        $banner_arr['thumb'] = implode(",", $img_arr);
        $bannerId                  =   Banner::create($banner_arr)->id;

        }

        }else { 


        $media_type="video";
        $banner_arr['upload_type'] = $media_type;
        $banner_arr['media_type'] = $media_type;
        if(isset($input['media_link']))
        {
            $banner_arr['media'] = $input['media_link'];
            $bannerId                  =   Banner::create($banner_arr)->id;
        } 


        }
        
        
         $msg    =   'Banner added successfully!';

     
        
       
        if($bannerId){   
Session::flash('message', ['text'=>$msg,'type'=>'success']);  
             }else{
Session::flash('message', ['text'=>'Banner creation failed','type'=>'danger']);
           }


       }

        return redirect(route('admin.banners'));
    }
   

    public function BannerStatus(Request $request)
        {
        $input = $request->all();
        
        if($input['id']>0) {
        $deleted =  Banner::where('id',$input['id'])->update(array('is_active'=>$input['status']));
        
        return '1';
        }else {
        
        return '0';
        }
        
        }
         public function BannerDelete(Request $request)
        {
        $input = $request->all();
        
        if($input['id']>0) {
        $deleted =  Banner::where('id',$input['id'])->update(array('is_deleted'=>1,'is_active'=>0));
        Session::flash('message', ['text'=>'Banner deleted successfully.','type'=>'success']);
        return true;
        }else {
        Session::flash('message', ['text'=>'Banner failed to delete.','type'=>'danger']);
        return false;
        }

        }

    
}
