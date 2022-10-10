<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use Intervention\Image\Facades\Image;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;

use App\Models\AdminProduct;
use App\Models\ProductType;
use App\Models\PrdPrice;
use App\Models\PrdImage;
use App\Models\Language;
use App\Models\SellerInfo;
use App\Models\PrdOffer;
use App\Models\PrdShockingSale;
use DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

use Validator;
use Session;

class ShockingSaleController extends Controller{
     public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function shocking_sales()
    { 

        $data['title']              =   'Shocking Sale';
        $data['menu']               =   'shocking-sales';
        $data['products']           =  PrdShockingSale::getShockingSales();
        
        // dd($data);
        return view('admin.shocking_sale.list',$data);
    }
    
   

     function createShockingSale(){
        $data['title']              =   'Create Shocking Sale';
        $data['menu']               =   'create-shocking-sales';
        $data['language']      =    DB::table('glo_lang_lk')->where('is_active', 1)->get();
       
        // dd($data);
        return view('admin.shocking_sale.create',$data);
    }

         function editShockingSale($sale_id){
        $data['title']              =   'Edit Shocking Sale';
        $data['menu']               =   'create-shocking-sales';
        $data['language']      =    DB::table('glo_lang_lk')->where('is_active', 1)->get();
        $data['shockingsale']      = PrdShockingSale::getShockingSale($sale_id);
      
        // dd($data);
        return view('admin.shocking_sale.edit',$data);
    }


     function saveShockingSale(Request $request){
       $input = $request->all();
        // dd($input); 
       $validator= $request->validate([
        'caption'   =>  ['required'],
        // 'prd_id' => ['required'],
        'sale_start' => ['required'],
        'sale_end' => ['required'],
        'offer_value' => ['required']

        ], [], 
        [
    
        'caption' => 'Caption',
        // 'prd_id' => 'Product',
        'sale_start' => 'Valid From',
        'sale_end' => 'Valid To',
        'offer_value' => 'Offer Value'
        ]);
       if($input['id']>0) {


        if (DB::table('cms_content')->where('cnt_id',$input['title_cid'])->where('lang_id',$input['glo_lang_cid'])->exists()) {
        DB::table('cms_content')->where('cnt_id',$input['title_cid'])->where('lang_id',$input['glo_lang_cid'])
        ->update(['content' => $input['caption']]);
        $caption_cid=$input['title_cid'];
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
        if($caption_cid !="") {


$sale_arr = [];

$sale_arr['title_cid'] = $caption_cid;
$sale_arr['start_time'] = $input['sale_start'];
$sale_arr['end_time'] = $input['sale_end'];
$sale_arr['discount_type'] = $input['ofr_type'];
$sale_arr['discount_value'] = $input['offer_value'];
$sale_arr['is_active'] = $input['is_active'];
$sale_arr['is_deleted'] = 0;
$sale_arr['user_type'] = 'admin';
$sale_arr['updated_by'] = auth()->user()->id;
$sale_arr['updated_at'] = date("Y-m-d H:i:s");

        $coupon =  PrdShockingSale::where('id',$input['id'])->update($sale_arr); 
        Session::flash('message', ['text'=>'Shocking Sale updated successfully','type'=>'success']); 

        }else {
        Session::flash('message', ['text'=>'Shocking Sale updation failed','type'=>'danger']);
        }



       }else {

       $shocksale_exists = $this->findExistingSales($input['sale_start'],$input['sale_end']);
      
       if($shocksale_exists ==1) {
        Session::flash('message', ['text'=>'Shocking Sale already exists on selected time period.','type'=>'danger']);
        return redirect()->back()->withInput($request->input());
       }


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

        // $prd_id                    =  implode(",",$input['prd_id']) ;
       
        $sale_arr = [];
        $sale_arr['org_id'] = 1;
        $sale_arr['title_cid'] = $caption_cid;
        // $sale_arr['prd_id'] = $prd_id;
        $sale_arr['start_time'] = $input['sale_start'];
        $sale_arr['end_time'] = $input['sale_end'];
        $sale_arr['discount_type'] = $input['ofr_type'];
        $sale_arr['discount_value'] = $input['offer_value'];
        $sale_arr['is_active'] = $input['is_active'];
        $sale_arr['is_deleted'] = 0;
        $sale_arr['user_type'] = 'seller';
        
        if($input['id'] >0){
        $sale_arr['updated_by'] = auth()->user()->id;
        $sale_arr['updated_at'] = date("Y-m-d H:i:s");
        $saleId =  PrdShockingSale::where('id',$post->id)->update($sale_arr); 
         $msg    =   'Shocking Sale updated successfully!';
        }else {
        $sale_arr['created_by'] = auth()->user()->id;
        $saleId                  =   PrdShockingSale::create($sale_arr)->id;
         $msg    =   'Shocking Sale added successfully!';

        }
        
       
        if($saleId){   
Session::flash('message', ['text'=>$msg,'type'=>'success']);  
             }else{
Session::flash('message', ['text'=>'Shocking Sale creation failed','type'=>'danger']);
           }


       }



       

            // dd($data);
        return redirect(route('admin.shocking_sales'));
    }
     function findExistingSales($start,$end) {
        $exists = 0;

        $findings = PrdShockingSale::where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->where(function ($query)  use ($start,$end) { $query->whereBetween('start_time', [$start, $end])->orWhereBetween('end_time', [$start, $end]);})->get();
        
       if($findings && count($findings) > 0) {
        $exists = 1;
       }
       return $exists;
        
    }

    public function ShockingSaleStatus(Request $request)
        {
        $input = $request->all();
        
        if($input['id']>0) {
        $deleted =  PrdShockingSale::where('id',$input['id'])->update(array('is_active'=>$input['status']));
        
        return '1';
        }else {
        
        return '0';
        }
        
        }
         public function ShockingSaleDelete(Request $request)
        {
        $input = $request->all();
        
        if($input['id']>0) {
        $deleted =  PrdShockingSale::where('id',$input['id'])->update(array('is_deleted'=>1,'is_active'=>0));
        Session::flash('message', ['text'=>'Shocking Sale deleted successfully.','type'=>'success']);
        return true;
        }else {
        Session::flash('message', ['text'=>'Shocking Sale failed to delete.','type'=>'danger']);
        return false;
        }

        }

    
}
