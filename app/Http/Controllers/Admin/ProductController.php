<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;
use App\Models\Modules;
use App\Models\Brand;
use App\Models\Tag;
use App\Models\UserRoles;
use App\Models\Admin;
use App\Models\UserRole;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\ProductImage;
use App\Models\PrdAdminImage;
use App\Models\AdminProduct;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Rules\Name;
use Validator;

class ProductController extends Controller{
    
    public function __construct() { $this->middleware('auth:admin'); }
    
    public function index(Request $request){
        $post                       =   (object)$request->post(); $res = []; 
        $data['title']              =   'Admin Product List';
        $data['menuGroup']          =   'product';
        $data['menu']               =   'admin_product';
        $data['active']             =   '';
        $data['categories']         =   getDropdownData(Category::where('is_active',1)->where('is_deleted',0)->get(),'category_id','cat_name');
        $data['subCats']            =   getDropdownData(Subcategory::where('is_active',1)->where('is_deleted',0)->get(),'subcategory_id','subcategory_name');
        $products                   =   AdminProduct::where('is_deleted',NULL)->orWhere('is_deleted',0);
        if(isset($request->active)     &&  $request->active != ''){ 
            $products               =   $products->where('is_active',$request->active); 
            $data['active']         =   $request->active;
        }if(isset($request->category)     &&  $request->category != ''){ 
            $products               =   $products->where('category_id',$request->category); 
            $data['category']       =   $request->category;
        }if(isset($request->sub_cat)     &&  $request->sub_cat != ''){ 
            $products               =   $products->where('sub_category_id',$request->sub_cat); 
            $data['subCats']        =   $request->sub_cat;
        }
        if(isset($post->vType)       ==  'ajax'){
           $search                  =   (isset($post->search['value']))? $post->search['value'] : ''; 
           $start                   =   (isset($post->start))? $post->start : 0; 
           $length                  =   (isset($post->length))? $post->length : 10; 
           $draw                    =   (isset($post->draw))? $post->draw : ''; 
           $totCount                =   $products->count(); $filtCount  =   $products->count();
           if($search != ''){
                $products           =   $products->where(function($qry) use ($search){
                                            $qry->where('name', 'LIKE', '%'.$search.'%');
                                            $qry->orWhereIn('category_id', $this->getCatPrdIds($search));
                                            $qry->orWhereIn('sub_category_id', $this->getSubCatPrdIds($search));
                                        });
                $filtCount          =   $products->count();
           }
           if($length>0){$products  =   $products->offset($start)->limit($length); }
           $products                =   $products->orderBy('id','desc')->get();
           if($products){ foreach   (   $products as $row){ $action = '';
               if($row->is_active   ==  1){ $checked    = 'checked="checked"'; $act = 'Active'; }else{ $checked = '';  $act = 'Inactive'; }
               $val['id']           =   '';                                
               $val['name']         =   '<a  href="'.url('admin/product/view').'/'.$row->id.'"  id="dtlBtn-'.$row->id.'" class="font-weight-bold viewDtl">'.$row->name.'</a>';
               $val['cat']          =   $row->category->cat_name;      
               $val['sub_cat']      =   $row->subCategory->subcategory_name;
               $val['created_at']   =   date('d M Y, g:i a',strtotime($row->created_at)); 
               $val['status']       =   '<div class="switch" data-search="'.$act.'">
                                            <input class="switch-input status-btn" id="status-'.$row->id.'" type="checkbox" '.$checked.' name="status">
                                            <label class="switch-paddle" for="status-'.$row->id.'">
                                                <span class="switch-active" aria-hidden="true">Active</span><span class="switch-inactive" aria-hidden="true">Inactive</span>
                                            </label>
                                        </div>';
             $val['statuss']       =  $act;                            
                if(checkPermission('admin/product/list','edit') == true){
                    $action         .=   '<a href="'.url('admin/product/edit').'/'.$row->id.'"   class="mr-2 btn btn-info btn-sm"><i class="fe fe-edit mr-1"></i> Edit</a>';
                }if(checkPermission('admin/product/list','delete') == true){
                    $action         .=   '<button  class="btn btn-sm btn-secondary deleteproduct" type="button" onclick="delete_cat('.$row->id.')" ><i class="fe fe-trash-2 mr-1"></i>Delete</button>';
                }
               $val['action']       =   $action; $res[] = $val;  
           } }
           $returnData = array(
			"draw"            => $draw,   
			"recordsTotal"    => $totCount,  
			"recordsFiltered" => $filtCount,
			"data"            => $res   // total data array
			);
            return $returnData;
        }
        return view('admin.product.product_list',$data);
    }
    public function product()
    {
        $data['title']              =   'Admin Product';
        $data['menu']               =   'Admin Product';
        $data['category']           =    Category::where('is_deleted',NULL)->orWhere('is_deleted',0)->where('is_active',1)->get();
        $data['tag']                =    Tag::where('is_active',1)->where('is_deleted',0)->get();
        $data['brand']              =    Brand::where('is_active',1)->where('is_deleted',0)->get();
        $data['language']           =    DB::table('glo_lang_lk')->where('is_active', 1)->get();
        $data['prod_type']          =    DB::table('prd_product_types')->where('is_deleted',0)->get();
        return view('admin.product.create_product',$data);
    }

    public function subCat(Request $request)
    {

        $parent_id = $request->cat_id;

        $subcategories = Subcategory::where('category_id',$parent_id)->where('is_deleted',0)->where('is_active',1)->get();
        $sublist=array();
        foreach($subcategories as $key)
        {
             $default_lang =DB::table('glo_lang_lk')->where('is_active', 1)->first();
             $subcategory_name=DB::table('cms_content')->where('cnt_id', $key->sub_name_cid)->where('lang_id', $default_lang->id)->first();
             $sublist[]=array('subcategory_id'=>$key->subcategory_id,'subname'=>$subcategory_name->content);
        }
        return response()->json([
            'subcategories' => $sublist
        ]);
    }

    public function taglist(Request $request)
    {

        $cat_id = $request->cat_id;
        $subcat_id = $request->subcat_id;

        $subcategories = Tag::where('cat_id',$cat_id)->where('subcat_id',$subcat_id)->where('is_deleted',0)->where('is_active',1)->get();
        $sublist=array();
        foreach($subcategories as $key)
        {
             $default_lang =DB::table('glo_lang_lk')->where('is_active', 1)->first();
             $tag_name=DB::table('cms_content')->where('cnt_id', $key->tag_name_cid)->where('lang_id', $default_lang->id)->first();
             $sublist[]=array('id'=>$key->id,'tag_name'=>$tag_name->content);
        }
        return response()->json([
            'tags' => $sublist
        ]);
    }

    public function insert_product(Request $request)
    {
    
        $validate= $request->validate([
            'product_name'=>['required','string'],
            'category' => ['required'],
            'subcategory_id' => ['required'],
            'language'=> ['required'],
            'short_description'=> ['required'],
            'product_type'=> ['required'],
            'product_image'=> ['required']
        ]);
        if (AdminProduct::where('name', '=', $validate['product_name'])->where('is_deleted', '=',0)->exists()) {
            Session::flash('message', ['text'=>'Product Already Exist','type'=>'warning']);
            return redirect(route('admin.productlist'));
        }
        else
        {


            $latest = DB::table('cms_content')->orderBy('cnt_id', 'DESC')->first();
            $latest_name_cid=++$latest->cnt_id;
            $latest_sdesc_cid =$latest_name_cid+1;
           

            $name_cid = DB::table('cms_content')->insertGetId(
                ['org_id' => 1, 'lang_id' => $validate['language'],'cnt_id'=>$latest_name_cid,'content' => $validate['product_name'],'is_active'=>1,'created_by'=>auth()->user()->id,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
            );
            $sdesc_cid = DB::table('cms_content')->insertGetId(
                ['org_id' => 1, 'lang_id' => $validate['language'],'cnt_id'=>$latest_sdesc_cid,'content' => $validate['short_description'],'is_active'=>1,'created_by'=>auth()->user()->id,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
            );
            if($request->long_description!='')
            {

            $latest_ldesc_cid =$latest_sdesc_cid+1;
            $ldesc_cid = DB::table('cms_content')->insertGetId(
                ['org_id' => 1, 'lang_id' => $validate['language'],'cnt_id'=>$latest_ldesc_cid,'content' => $request->long_description,'is_active'=>1,'created_by'=>auth()->user()->id,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
            );
            }
            else
            {
                $latest_ldesc_cid='';
            }

            if($request->content!='')
            {

            $latest_content_cid =$latest_sdesc_cid+2;
            $content_cid = DB::table('cms_content')->insertGetId(
                ['org_id' => 1, 'lang_id' => $validate['language'],'cnt_id'=>$latest_content_cid,'content' => $request->content,'is_active'=>1,'created_by'=>auth()->user()->id,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
            );
            }
            else
            {
                $latest_content_cid='';
            }
            
              if($request->specification!='')
            {

            $latest_spec_cid =$latest_sdesc_cid+3;
            $content_cid = DB::table('cms_content')->insertGetId(
                ['org_id' => 1, 'lang_id' => $validate['language'],'cnt_id'=>$latest_spec_cid,'content' => $request->specification,'is_active'=>1,'created_by'=>auth()->user()->id,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
            );
            }
            else
            {
                $latest_spec_cid='';
            }

            if($request->tag!='')
            {
                $tagids=implode(',',$request->tag);
            }
            else
            {
                $tagids='';
            }

            $prd_id = AdminProduct::create([
            'name' => $validate['product_name'],
            'name_cid' => $latest_name_cid,
            'product_type'=>$validate['product_type'],
            'category_id' => $validate['category'],
            'sub_category_id'=>$validate['subcategory_id'],
            'brand_id' => $request->brand,
            'tag_ids' => $tagids,
            'short_desc' => $latest_sdesc_cid,
            'content'=>$latest_content_cid,
             'spec_cnt_id'=>$latest_spec_cid,
            'desc' => $latest_ldesc_cid,
            'is_active'=>$request->status,
            'is_deleted'=>0,
            'created_by'=>auth()->user()->id,
            'updated_by'=>auth()->user()->id,
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")

        ])->id;
       //  $prd_id = 27;
      //  $prd_id = AdminProduct::latest('id')->first();
        if ($request->hasFile('product_image')) {
            // foreach ($request->file('product_image') as $file) {

            //   // $file=$filekey;
            //   // $extention=$file->getClientOriginalExtension();
            //     $filename = time().rand(1,100).'.'.$file->extension();
            //   // $filename=time().rand(1,100).'.'.$extention;
            //     $file->move(('storage/app/public/product/'), $filename);
            //     ProductImage::create([
            // 'prod_id'=>$prd_id->id,
            // 'image'=>$filename,
            // 'created_at'=>date("Y-m-d H:i:s"),
            // 'updated_at'=>date("Y-m-d H:i:s")]);
            // }
            //   $images_crp                 =   $request->img_crp;
            //   foreach($images_crp as $k=>$image)
            //     {

            //     if($image) {
            //     $imgName            =   rand(99,999).time().'.'."png";
            //     $path               =   '/app/public/admin_products/'.$prd_id;
            //     $destinationPath    =   storage_path($path);
            //     if(!file_exists($destinationPath)) { mkdir($destinationPath, 755, true);}
            //     $img                =   Image::make(file_get_contents($image))->save($destinationPath.'/'.$imgName);   
            //     $img                =   Image::make(file_get_contents($image));
            //     $destinationPath    =   storage_path($path.'/thumb');
            //     if(!file_exists($destinationPath)) { mkdir($destinationPath, 755, true);}
            //     $img->resize(250, 250, function($constraint){ $constraint->aspectRatio(); })->save($destinationPath.'/'.$imgName);
            //     // $destinationPath    =   storage_path($path); 
            //     // $image->move($destinationPath.'/', $imgName);
            //     $imgUpload          =   uploadFile($path,$imgName);
            //     $thumbUpload        =   uploadFile($path.'/thumb',$imgName);
            //      if($imgUpload){
            //     PrdAdminImage::create(['prd_id'=>$prd_id,'image'=>$path.'/'.$imgName,'thumb'=>$path.'/thumb/'.$imgName,'created_by'=>auth()->user()->id]);
            //      }
            //     }

            //     }
            
            foreach($request->file('product_image') as $k=>$image){ 
                $imgName            =   time().'.'.$image->extension();
                $path               =   '/app/public/admin_products/'.$prd_id;
                $destinationPath    =   storage_path($path.'/thumb');
                $img                =   Image::make($image->path()); // echo storage_path().'  '. $destinationPath; die;
                if(!file_exists($destinationPath)) { mkdir($destinationPath, 755, true);}
                $img->resize(250, 250, function($constraint){ $constraint->aspectRatio(); })->save($destinationPath.'/'.$imgName);
                $destinationPath    =   storage_path($path);
                $image->move($destinationPath, $imgName);
                $imgUpload          =   uploadFile('/'.$path,$imgName);
                $thumbUpload        =   uploadFile('/'.$path.'/thumb',$imgName);
                if($imgUpload){
                    PrdAdminImage::create(['prd_id'=>$prd_id,'image'=>$path.'/'.$imgName,'thumb'=>$path.'/thumb/'.$imgName,'created_by'=>auth()->user()->id]);
                }
                
                
            }
        } 
            Session::flash('message', ['text'=>'Product created successfully','type'=>'success']);
            return redirect(route('admin.productlist'));
        }
    }

    public function change_status_product(Request $request)
    {
        $product = AdminProduct::find($request->prd_id);
        $product->is_active = $request->status;
        $product->save();

        return response()->json(['success'=>'status change successfully.']);
    }

    public function delete_product(Request $request)
    {
        $prd_id=$request->prd_id;
        AdminProduct::where('id',$prd_id)->update([
            'is_active'=>0,
            'is_deleted'=>1,
            'updated_by'=>auth()->user()->id,
            'updated_at'=>date("Y-m-d H:i:s")

        ]);
            //Session::flash('message', ['text'=>'Deleted successfully','type'=>'success']);
            return response()->json(['success'=>'status change successfully.']);
    }

    public function edit_product($prd_id)
    {
        $data['title']              =   'Admin Product';
        $data['menu']               =   'Admin Product';
        $data['product']            =    AdminProduct::where('id',$prd_id)->first();
        $data['language']           =    DB::table('glo_lang_lk')->where('is_active', 1)->get();
        $data['category']           =    Category::where('is_deleted',NULL)->orWhere('is_deleted',0)->where('is_active',1)->get();
        $data['tag']                =    Tag::where('is_active',1)->where('is_deleted',0)->where('cat_id',$data['product']->category_id)->where('subcat_id',$data['product']->sub_category_id)->get();
        $data['brand']              =    Brand::where('is_active',1)->where('is_deleted',0)->get();
        $data['prod_type']          =    DB::table('prd_product_types')->where('is_deleted',0)->get();
        // dd($data['product']);
        return view('admin.product.edit_product',$data);
    }
      public function view_product($prd_id)
    {
        $data['title']              =   'Admin Product';
        $data['menu']               =   'Admin Product';
        $data['product']            =    AdminProduct::where('id',$prd_id)->first();
        $data['language']           =    DB::table('glo_lang_lk')->where('is_active', 1)->get();
        $data['category']           =    Category::where('is_deleted',NULL)->orWhere('is_deleted',0)->where('is_active',1)->get();
        $data['subCats']            =   getDropdownData(Subcategory::where('is_active',1)->where('is_deleted',0)->get(),'subcategory_id','subcategory_name');
        $data['tag']                =    Tag::where('is_active',1)->where('is_deleted',0)->where('cat_id',$data['product']->category_id)->where('subcat_id',$data['product']->sub_category_id)->get();
        $data['brand']              =    Brand::where('is_active',1)->where('is_deleted',0)->get();
        $data['prod_type']          =    DB::table('prd_product_types')->where('is_deleted',0)->get();
        // dd($data['product']);
        // dd($data);
        return view('admin.product.view_product',$data);
    }

    public function update_product(Request $request,$prd_id)
    { 
        $post       =   (object)$request->post();
        $validate= $request->validate([
            'product_name'=>['required','string'],
            'category' => ['required'],
            'subcategory_id' => ['required'],
            'language'=> ['required'],
            'short_description'=> ['required'],
            'language'=> ['required'],
        ]);
        if($post->up_imgs == 0){ 
            $validate= $request->validate([
            'product_name'=>['required','string'],
            'category' => ['required'],
            'subcategory_id' => ['required'],
            'language'=> ['required'],
            'short_description'=> ['required'],
            'language'=> ['required'],
            'product_image' => ['required']
        ]);
            
        }
        
            $latest = DB::table('cms_content')->orderBy('cnt_id', 'DESC')->first();
            $latest_name_cid=++$latest->cnt_id;
            $latest_sdesc_cid =$latest_name_cid+1;
            

            $name_cid = DB::table('cms_content')->insertGetId(
                ['org_id' => 1, 'lang_id' => $validate['language'],'cnt_id'=>$latest_name_cid,'content' => $validate['product_name'],'is_active'=>1,'created_by'=>auth()->user()->id,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
            );
            $sdesc_cid = DB::table('cms_content')->insertGetId(
                ['org_id' => 1, 'lang_id' => $validate['language'],'cnt_id'=>$latest_sdesc_cid,'content' => $validate['short_description'],'is_active'=>1,'created_by'=>auth()->user()->id,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
            );
            if($request->long_description!='')
            {

            $latest_ldesc_cid =$latest_sdesc_cid+1;
            $ldesc_cid = DB::table('cms_content')->insertGetId(
                ['org_id' => 1, 'lang_id' => $validate['language'],'cnt_id'=>$latest_ldesc_cid,'content' => $request->long_description,'is_active'=>1,'created_by'=>auth()->user()->id,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
            );
            }
            else
            {
                $latest_ldesc_cid='';
            }

            if($request->content!='')
            {

            $latest_content_cid =$latest_sdesc_cid+2;
            $content_cid = DB::table('cms_content')->insertGetId(
                ['org_id' => 1, 'lang_id' => $validate['language'],'cnt_id'=>$latest_content_cid,'content' => $request->content,'is_active'=>1,'created_by'=>auth()->user()->id,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
            );
            }
            else
            {
                $latest_content_cid='';
            }
   if($request->specification!='')
            {

            $latest_spec_cid =$latest_sdesc_cid+3;
            $content_cid = DB::table('cms_content')->insertGetId(
                ['org_id' => 1, 'lang_id' => $validate['language'],'cnt_id'=>$latest_spec_cid,'content' => $request->specification,'is_active'=>1,'created_by'=>auth()->user()->id,'updated_by'=>auth()->user()->id,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
            );
            }
            else
            {
                $latest_spec_cid='';
            }

            if($request->tag!='')
            {
                $tagids=implode(',',$request->tag);
            }
            else
            {
                $tagids='';
            }

            AdminProduct::where('id',$prd_id)->update([
            'name' => $validate['product_name'],
            'name_cid' => $latest_name_cid,
            'category_id' => $validate['category'],
            'sub_category_id'=>$validate['subcategory_id'],
            'short_desc' => $latest_sdesc_cid,
            'content'=>$latest_content_cid,
            'spec_cnt_id'=>$latest_spec_cid,
            'desc' => $latest_ldesc_cid,
            'is_active'=>$request->status,
            'updated_by'=>auth()->user()->id,
            'updated_at'=>date("Y-m-d H:i:s")

        ]);
        if($request->hasFile('product_image'))
            {
                
            //       $images_crp                 =   $request->img_crp;
            //   foreach($images_crp as $k=>$image)
            //     {

            //     if($image) {
            //     $imgName            =   rand(99,999).time().'.'."png";
            //     $path               =   '/app/public/admin_products/'.$prd_id;
            //     $destinationPath    =   storage_path($path);
            //     if(!file_exists($destinationPath)) { mkdir($destinationPath, 755, true);}
            //     $img                =   Image::make(file_get_contents($image))->save($destinationPath.'/'.$imgName);   
            //     $img                =   Image::make(file_get_contents($image));
            //     $destinationPath    =   storage_path($path.'/thumb');
            //     if(!file_exists($destinationPath)) { mkdir($destinationPath, 755, true);}
            //     $img->resize(250, 250, function($constraint){ $constraint->aspectRatio(); })->save($destinationPath.'/'.$imgName);
            //     // $destinationPath    =   storage_path($path); 
            //     // $image->move($destinationPath.'/', $imgName);
            //     $imgUpload          =   uploadFile($path,$imgName);
            //     $thumbUpload        =   uploadFile($path.'/thumb',$imgName);
            //      if($imgUpload){
            //     PrdAdminImage::create(['prd_id'=>$prd_id,'image'=>$path.'/'.$imgName,'thumb'=>$path.'/thumb/'.$imgName,'created_by'=>auth()->user()->id]);
            //      }
            //     }

            //     }
                
            foreach($request->file('product_image') as $k=>$image){ 
                $imgName            =   time().'.'.$image->extension();
                $path               =   '/app/public/admin_products/'.$prd_id;
                $destinationPath    =   storage_path($path.'/thumb'); // print_r($image); die;
                $img                =   Image::make($image->path()); // echo storage_path().'  '. $destinationPath; die;
                if(!file_exists($destinationPath)) { mkdir($destinationPath, 755, true);}
                $img->resize(250, 250, function($constraint){ $constraint->aspectRatio(); })->save($destinationPath.'/'.$imgName); 
                $destinationPath    =   storage_path($path);
                $image->move($destinationPath, $imgName);
                $imgUpload          =   uploadFile('/'.$path,$imgName);
                $thumbUpload        =   uploadFile('/'.$path.'/thumb',$imgName);
                if($imgUpload){
                    PrdAdminImage::create(['prd_id'=>$prd_id,'image'=>$path.'/'.$imgName,'thumb'=>$path.'/thumb/'.$imgName,'created_by'=>auth()->user()->id]);
                }
            }
            }
            Session::flash('message', ['text'=>'Product updated successfully','type'=>'success']);
            return redirect(route('admin.productlist'));
        
    }
    
    public function remove_image(Request $request)
    {
        $prd_img_id=$request->img_id;
        $del_img=PrdAdminImage::where('id',$prd_img_id)->delete();
        response()->json(['success'=>'Removed successfully.']);
    }
    
    function getCatPrdIds($keyword){
        $query              =   Category::where('cat_name', 'LIKE', '%'.$keyword.'%')->where('is_deleted',0); $ids = [0];
        if($query->count()  >   0)  {   foreach($query->get() as $row){ $ids[]    =   $row->category_id; }}return $ids; 
    }
    function getSubCatPrdIds($keyword){
        $query              =   Subcategory::where('subcategory_name', 'LIKE', '%'.$keyword.'%')->where('is_deleted',0); $ids = [0];
        if($query->count()  >   0)  {   foreach($query->get() as $row){ $ids[]    =   $row->subcategory_id; }}return $ids; 
    }
}
