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
use App\Models\AdminProduct;
use App\Rules\Name;
use Validator;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index()
    {
        $data['title']              =   'Admin Product';
        $data['menu']               =   'Admin Product';
        $data['product']            =    AdminProduct::where('is_deleted',NULL)->orWhere('is_deleted',0)->orderBy('id','DESC')->get();
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
            'language'=> ['required']
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
            // dd($latest_desc_cid);
            // die;

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

            if($request->tag!='')
            {
                $tagids=implode(',',$request->tag);
            }
            else
            {
                $tagids='';
            }

            AdminProduct::create([
            'name' => $validate['product_name'],
            'name_cid' => $latest_name_cid,
            'product_type'=>$validate['product_type'],
            'category_id' => $validate['category'],
            'sub_category_id'=>$validate['subcategory_id'],
            'brand_id' => $request->brand,
            'tag_ids' => $tagids,
            'short_desc' => $latest_sdesc_cid,
            'content'=>$latest_content_cid,
            'desc' => $latest_ldesc_cid,
            'is_active'=>$request->status,
            'is_deleted'=>0,
            'created_by'=>auth()->user()->id,
            'updated_by'=>auth()->user()->id,
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")

        ]);
        $prd_id = AdminProduct::latest('id')->first();
        if ($request->hasFile('product_image')) {
            foreach ($request->file('product_image') as $file) {

               // $file=$filekey;
               // $extention=$file->getClientOriginalExtension();
                $filename = time().rand(1,100).'.'.$file->extension();
               // $filename=time().rand(1,100).'.'.$extention;
                $file->move(('storage/app/public/product/'), $filename);
                ProductImage::create([
            'prod_id'=>$prd_id->id,
            'image'=>$filename,
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")]);
            }
        }
            Session::flash('message', ['text'=>'Created successfully','type'=>'success']);
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
        return view('admin.product.edit_product',$data);
    }

    public function update_product(Request $request,$prd_id)
    {
        $validate= $request->validate([
            'product_name'=>['required','string'],
            'category' => ['required'],
            'subcategory_id' => ['required'],
            'language'=> ['required'],
            'short_description'=> ['required'],
            'product_type'=> ['required'],
            'language'=> ['required']
        ]);
        
            $latest = DB::table('cms_content')->orderBy('cnt_id', 'DESC')->first();
            $latest_name_cid=++$latest->cnt_id;
            $latest_sdesc_cid =$latest_name_cid+1;
            // dd($latest_desc_cid);
            // die;

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
            'product_type'=>$validate['product_type'],
            'category_id' => $validate['category'],
            'sub_category_id'=>$validate['subcategory_id'],
            'brand_id' => $request->brand,
            'tag_ids' => $tagids,
            'short_desc' => $latest_sdesc_cid,
            'content'=>$latest_content_cid,
            'desc' => $latest_ldesc_cid,
            'is_active'=>$request->status,
            'updated_by'=>auth()->user()->id,
            'updated_at'=>date("Y-m-d H:i:s")

        ]);
        if($request->hasFile('product_image'))
            {
            //$del_img=ProductImage::where('prod_id',$prd_id)->delete();
            foreach ($request->file('product_image') as $file) {

                 $filename = time().rand(1,100).'.'.$file->extension();
                // $filename=time().rand(1,100).'.'.$extention;
                 $file->move(('storage/app/public/product/'), $filename);
                 ProductImage::create([
             'prod_id'=>$prd_id,
             'image'=>$filename,
             'created_at'=>date("Y-m-d H:i:s"),
             'updated_at'=>date("Y-m-d H:i:s")]);
             }
            }
            Session::flash('message', ['text'=>'Updated successfully','type'=>'success']);
            return redirect(route('admin.productlist'));
        
    }
    
    public function remove_image(Request $request)
    {
        $prd_img_id=$request->img_id;
        $del_img=ProductImage::where('id',$prd_img_id)->delete();
        response()->json(['success'=>'Removed successfully.']);
    }
}
