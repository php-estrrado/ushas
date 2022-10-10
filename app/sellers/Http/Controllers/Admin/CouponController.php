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
use App\Models\Coupon;
use App\Models\CouponHist;
use App\Models\Category;
use App\Models\Store;

use App\Models\Admin;


use App\Rules\Name;
use Validator;

class CouponController extends Controller
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
   
    
    // user roles and modules
    
    public function coupons()
        { 
        $data['title']              =   'Coupons';
        $data['menu']               =   'coupons';
        $data['coupons']              =   Coupon::getCoupons();
        $data['maxprice'] = Coupon::max('ofr_value');
        $data['minprice'] = Coupon::min('ofr_value');
         $data['categories']      =   Category::where('is_active',1)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();
        $data['sellers']      =    Store::where('is_active',1)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();
        
        // dd($data);
        return view('admin.benefits.coupons.list',$data);
        }

        public function createCoupon()
        { 
        $data['title']              =   'Create Coupon';
        $data['menu']               =   'create-coupon';
        $data['language']      =    DB::table('glo_lang_lk')->where('is_active', 1)->get();
        $data['categories']      =   Category::where('is_active',1)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();
        $data['sellers']      =    Store::where('is_active',1)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();
        // dd($data);
        return view('admin.benefits.coupons.create',$data);
        }

        public function subcatedata(Request $request)
    {
  
         $input = $request->all();
         $cateid = $input['category_id'];
        if(isset($input['selectid'])) { $selectid = $input['selectid']; }else {  $selectid = '';}
            $sub_data=array();
           
              $squery    =  DB::table('subcategory')->where('category_id', $cateid)->where('parent', 0)->where('is_active', 1)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();

            
              if($squery->count()> 0)
                {

                  //$sub_data[]=array();
                  foreach($squery as $srow)
                  { 

                    if($srow->subcategory_id != $selectid)
                    {
                        $kk=array();
                        $kk['id'] = $srow->subcategory_id;
                        $kk['title'] = ucfirst($srow->subcategory_name);
                        $tt=$this->subtree($cateid,$srow->subcategory_id,$selectid);
                        if($tt)
                        {
                          if($selectid=='product')
                          {
                            $kk['isSelectable']=false;
                          }
                          $kk['subs']=$tt;
                        }
                        $sub_data[]=$kk;
                    }
                  }
                }
            
          $result=array('val'=>'1','subdata'=>$sub_data);  
          return json_encode($result);
    }

    function subtree($cateid,$subid,$selectid='')
    {
      $jj=array();
      $squery2    =   DB::table('subcategory')->where('category_id', $cateid)->where('parent', $subid)->where('is_active', 1)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();


    
      if($squery2->count() > 0)
      {
        foreach($squery2 as $srow)
        { 
          if($srow->subcategory_id != $selectid)
            {  
                $kk=array();
                $kk['id'] = $srow->subcategory_id;
                $kk['title'] = ucfirst($srow->subcategory_name);
                $tt=$this->subtree($cateid,$srow->subcategory_id,$selectid);
                if($tt)
                {
                  if($selectid=='product')
                  {
                    $kk['isSelectable']=false;
                  }
                  $kk['subs']=$tt;
                }
                $jj[]=$kk;
            }
            
        }
      }
      return $jj;
    }

        public function editCoupon($cpn_id)
        { 
        $data['title']              =   'Edit Coupon';
        $data['menu']               =   'edit-coupon';
        $data['coupon']              =  Coupon::getCouponData($cpn_id);
        $data['language']      =    DB::table('glo_lang_lk')->where('is_active', 1)->get();
        $data['categories']      =   Category::where('is_active',1)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();
        $data['sellers']      =    Store::where('is_active',1)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();
        // dd($data);
        return view('admin.benefits.coupons.edit',$data);
        }

        //    public function viewTag($tag_id)
        // { 
        // $data['title']              =   'View Tag';
        // $data['menu']               =   'view-tag';
        // $data['tag']              =  Tag::getTag($tag_id);
        // $data['language']      =    DB::table('glo_lang_lk')->where('is_active', 1)->get();
        // $data['categories']      =   Category::where('is_active',1)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();
        // // $data['subcategories']      =    $this->subcatedata(4);
        // // dd($data);
        // return view('admin.tags.view',$data);
        // }

          public function logCoupon($cpn_id)
        { 
        $data['title']              =   'Coupon Logs';
        $data['menu']               =   'coupon-log';
        $data['coupons']              =  Coupon::getCoupons();
        $data['log']                = CouponHist::getLog($cpn_id);
        $data['cpn_id']             = $cpn_id;
    
        // dd($data);
 
         return view('admin.benefits.coupons.logs',$data);
        }

        public function couponSave(Request $request)
        { 
        $input = $request->all();
      
        // dd($input);

        if($input['id']>0){

     
        $validator= $request->validate([
        'coupon_title'   =>  ['required'],
        'ofr_value' => ['required'],
        'ofr_code' => ['required']

        ], [], 
        [
        'coupon_title' => 'Coupon Title',
        'ofr_value' => 'Offer Value',
        'ofr_code' => 'Offer Code'
        ]);

        if (DB::table('cms_content')->where('cnt_id',$input['cpn_title_cid'])->where('lang_id',$input['glo_lang_cid'])->exists()) {
        DB::table('cms_content')->where('cnt_id',$input['cpn_title_cid'])->where('lang_id',$input['glo_lang_cid'])
        ->update(['content' => $input['coupon_title']]);
        $cpn_title_cid=$input['cpn_title_cid'];
        } else {

        $latest = DB::table('cms_content')->orderBy('cnt_id', 'DESC')->first();
        $cpn_title_cid=++$latest->cnt_id;
        DB::table('cms_content')->insertGetId([
        'org_id' => 1, 
        'lang_id' => $input['glo_lang_cid'],
        'cnt_id'=>$cpn_title_cid,
        'content' => $input['coupon_title'],
        'is_active'=>1,
        'created_by'=>auth()->user()->id,
        'updated_by'=>auth()->user()->id,
        'is_deleted'=>0,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")
        ]);
     

        }

       if (DB::table('cms_content')->where('cnt_id',$input['cpn_desc_cid'])->where('lang_id',$input['glo_lang_cid'])->exists()) {
        DB::table('cms_content')->where('cnt_id',$input['cpn_desc_cid'])->where('lang_id',$input['glo_lang_cid'])
        ->update(['content' => $input['coupon_desc']]);
        $cpn_desc_cid=$input['cpn_desc_cid'];
        } else {

        $latest = DB::table('cms_content')->orderBy('cnt_id', 'DESC')->first();
        $cpn_desc_cid=++$latest->cnt_id;
        DB::table('cms_content')->insertGetId([
        'org_id' => 1, 
        'lang_id' => $input['glo_lang_cid'],
        'cnt_id'=>$cpn_desc_cid,
        'content' => $input['coupon_desc'],
        'is_active'=>1,
        'created_by'=>auth()->user()->id,
        'updated_by'=>auth()->user()->id,
        'is_deleted'=>0,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")
        ]);
        

        }
        $cpn_id = $input['id'];
        if($input['subcat_id'] =="") { $input['subcat_id']=0;}
        if($input['purchase_amount'] =="") { $input['purchase_amount']=0;}
        if($input['purchase_number'] =="") { $input['purchase_number']=0;}
        if($input['valid_from'] =="") { $input['valid_from']=null;}
        if($input['valid_to'] =="") { $input['valid_to']=null;}
        if($input['valid_days'] =="") { $input['valid_days']=0;}
        if($cpn_desc_cid !="" && $cpn_title_cid !="" && $cpn_id !="") {

        $coupon =  Coupon::where('id',$cpn_id)->update([
        'org_id' => 1, 
        'cpn_title_cid' => $cpn_title_cid,
        'cpn_desc_cid' => $cpn_desc_cid,
        'category_id'=>$input['category_id'],
        'subcategory_id'=>$input['subcat_id'],
        'seller_id'=>$input['seller_id'],
        'purchase_type'=>$input['purchase_type'],
        'purchase_number'=>$input['purchase_number'],
        'purchase_amount'=>$input['purchase_amount'],
        'ofr_value_type'=>$input['ofr_value_type'],
        'ofr_value'=>$input['ofr_value'],
        'ofr_type'=>$input['ofr_type'],
        'ofr_code'=>$input['ofr_code'],
        'ofr_min_amount'=>$input['ofr_min_amount'],
        'validity_type'=>$input['validity_type'],
        'valid_from'=>$input['valid_from'],
        'valid_to'=>$input['valid_to'],
        'valid_days'=>$input['valid_days'],
        'is_active'=>$input['is_active'],
        'is_deleted'=>0,
        'updated_by'=>auth()->user()->id,
        'updated_at'=>date("Y-m-d H:i:s")

        ]); 
        Session::flash('message', ['text'=>'Coupon updated successfully','type'=>'success']); 
        }else {
        Session::flash('message', ['text'=>'Coupon updation failed','type'=>'danger']);
        }







        }else{

     $validator= $request->validate([
        'coupon_title'   =>  ['required'],
        'ofr_value' => ['required'],
        'ofr_code' => ['required']

        ], [], 
        [
        'coupon_title' => 'Coupon Title',
        'ofr_value' => 'Offer Value',
        'ofr_code' => 'Offer Code'
        ]);


  // dd($input);

        $latest = DB::table('cms_content')->orderBy('id', 'DESC')->first();
        $cpn_name_cid=++$latest->cnt_id;
        $cpn_desc_cid =$cpn_name_cid+1;

        $cpn_name= DB::table('cms_content')->insertGetId([
        'org_id' => 1, 
        'lang_id' => $input['glo_lang_cid'],
        'cnt_id'=>$cpn_name_cid,
        'content' => $input['coupon_title'],
        'is_active'=>1,
        'created_by'=>auth()->user()->id,
        'updated_by'=>auth()->user()->id,
        'is_deleted'=>0,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")
        ]);


        $cpn_desc= DB::table('cms_content')->insertGetId([
        'org_id' => 1, 
        'lang_id' => $input['glo_lang_cid'],
        'cnt_id'=>$cpn_desc_cid,
        'content' => $input['coupon_desc'],
        'is_active'=>1,
        'created_by'=>auth()->user()->id,
        'updated_by'=>auth()->user()->id,
        'is_deleted'=>0,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")
        ]);
        if($input['subcat_id'] =="") { $input['subcat_id']=0;}
        if($input['purchase_amount'] =="") { $input['purchase_amount']=0;}
        if($input['purchase_number'] =="") { $input['purchase_number']=0;}
        if($input['valid_days'] =="") { $input['valid_days']=0;}
        if($cpn_name !="" && $cpn_desc !="") {
        $coupon =  Coupon::create([
        'org_id' => 1, 
        'cpn_title_cid' => $cpn_name_cid,
        'cpn_desc_cid' => $cpn_desc_cid,
        'category_id'=>$input['category_id'],
        'subcategory_id'=>$input['subcat_id'],
        'seller_id'=>$input['seller_id'],
        'purchase_type'=>$input['purchase_type'],
        'purchase_number'=>$input['purchase_number'],
        'purchase_amount'=>$input['purchase_amount'],
        'ofr_value_type'=>$input['ofr_value_type'],
        'ofr_value'=>$input['ofr_value'],
        'ofr_type'=>$input['ofr_type'],
        'ofr_code'=>$input['ofr_code'],
        'ofr_min_amount'=>$input['ofr_min_amount'],
        'validity_type'=>$input['validity_type'],
        'valid_from'=>$input['valid_from'],
        'valid_to'=>$input['valid_to'],
        'valid_days'=>$input['valid_days'],
        'is_active'=>$input['is_active'],
        'is_deleted'=>0,
        'created_by'=>auth()->user()->id,
        'updated_by'=>auth()->user()->id,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")

        ]);   
        $lastId = $coupon->id;
        if($lastId) {
        Session::flash('message', ['text'=>'Coupon created successfully','type'=>'success']);  
        }else {
        Session::flash('message', ['text'=>'Coupon creation failed','type'=>'danger']);
        }
        }else {
        Session::flash('message', ['text'=>'Coupon creation failed','type'=>'danger']);
        }

        }
               $data['title']              =   'Coupons';
        $data['menu']               =   'coupons';
        $data['brands']              =  Coupon::getCoupons();
        return redirect(route('admin.coupons'));

        }


        public function couponDelete(Request $request)
        {
        $input = $request->all();
        
        if($input['id']>0) {
        $deleted =  Coupon::where('id',$input['id'])->update(array('is_deleted'=>1,'is_active'=>0));
        Session::flash('message', ['text'=>'Coupon deleted successfully.','type'=>'success']);
        return true;
        }else {
        Session::flash('message', ['text'=>'Coupon failed to delete.','type'=>'danger']);
        return false;
        }

        }
           public function couponStatus(Request $request)
        {
        $input = $request->all();
        
        if($input['id']>0) {
        $deleted =  Coupon::where('id',$input['id'])->update(array('is_active'=>$input['status']));
        
        return '1';
        }else {
        
        return '0';
        }
        
        }

             public function couponfilter(Request $request)
        {
        $input = $request->all();
        // dd()
$startdate = $input['startdate'];
$enddate = $input['enddate'];
$startprice = $input['startprice'];
$endprice = $input['endprice'];
$typesel = $input['typesel'];
if($typesel == 1) {
  $field_ser = "amount";
}else {
$field_ser = "percentage";
}

 // $coupons = Coupon::where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})
 //    ->where('ofr_value_type', $field_ser)
 //    ->when($startdate && $enddate, function ($query) use ($startdate, $enddate) {
 //        return $query->whereBetween('valid_from',[$startdate, $enddate])->orWhereBetween('valid_to',[$startdate, $enddate]);
 //    })
    
 //    ->when($startprice && $endprice, function ($query) use ($startprice, $endprice) {
 //        return $query->whereBetween('ofr_value',[$startprice, $endprice]);
 //    })

    $coupons = Coupon::where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})
    ->where('ofr_value_type', $field_ser)
   
    ->when($startprice && $endprice, function ($query) use ($startprice, $endprice) {
        return $query->whereBetween('ofr_value',[$startprice, $endprice]);
    })->orderBy('id', 'DESC')->get();  

             $categories      =   Category::where('is_active',1)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();
        $sellers =    Store::where('is_active',1)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();


if($coupons && count($coupons) > 0) {
  $cpn_html = "";
                                          foreach($coupons as $row) {


                                            if($row['validity_type'] == "days") 
                          {
                            $days = $row['valid_days'];
                        $valid_till = date('Y-m-d', strtotime($row['created_at'] ."+$days days"));
                        $valid_from = date('Y-m-d', strtotime($row['created_at']));
                          }else {
                            $valid_from = $row['valid_from'];
                            $valid_till = $row['valid_to'];
                          }
                          if($startdate !="" && $enddate !="") {

                            // if((strtotime($startdate) <= strtotime($valid_from) || strtotime($startdate) <= strtotime($valid_till)) && (strtotime($enddate) <= strtotime($valid_till) || strtotime($enddate) >= strtotime($valid_from)) )

                               if((strtotime($startdate) <= strtotime($valid_from) || strtotime($startdate) <= strtotime($valid_till)) && (strtotime($enddate) >= strtotime($valid_from) ) ) {
                                $cpn_title = Coupon::getCpnContent($row["cpn_title_cid"]);
                            $cpn_html .=' <tr><td class="align-middle select-checkbox" id="moduleid" data-value="'.$row["id"].'">
                                    <label class="custom-control custom-checkbox">
                                      
                                      
                                    </label>
                                  </td>
                                  <td class="align-middle" >
                                    <div class="d-flex">
                                  
                                  <h6 class=" font-weight-bold">'.$cpn_title.'</h6>
                                        
                                      
                                    </div>
                                  </td>
                                  <td class="align-middle" >
                                    <div class="d-flex">
                                    <p>'.$row["ofr_code"].'</p>
                                  </div>
                                  </td>
                                  <td class="align-middle" >
                                    <div class="d-flex">
                                      <p>'.$row["ofr_value"].'</p>
                                    </div>
                                  </td>
                                  <td class="align-middle" >
                                    <div class="d-flex">
                                      <p>'. ucfirst($row["ofr_type"]) .'</p>
                                    </div>
                                  </td>
                                  <td class="text-nowrap align-middle">';

                        
                          if($row["is_active"] ==1){ $activestat = "Active"; $activesel = "checked"; }else{ $activestat ="Inactive"; $activesel = ""; }
          
                                   $cpn_html .='  <p>'. $valid_till .'</p>
                                  </td>
                                  
                                  <td class="text-nowrap align-middle"  data-search="'.$activestat.'">
                                    
                                  <div class="switch">
                                  <input class="switch-input status-btn ser_status" data-selid="'.$row["id"].'"  id="status-'.$row["id"].'"  type="checkbox" '.$activesel.' >
                                  <label class="switch-paddle" for="status-'.$row["id"].'">
                                  <span class="switch-active" aria-hidden="true">Active</span>
                                  <span class="switch-inactive" aria-hidden="true">Inactive</span>
                                  </label>
                                  </div>                    
                  
                                  </td>
                                  <td class="text-nowrap align-middle"><span>'.date("d M Y",strtotime($row["created_at"])).'</span></td>
                                  
                                  <td class="align-middle">
                                    <div class="btn-group align-top">
                                      
                                      <a href="'.url("admin/coupons/log/").'/'.$row["id"].'"   class="mr-2 btn btn-info btn-sm editmodule"><i class="fe fe-edit mr-1"></i> View</a>
                                      
                                    </div>
                                  </td>
                                  <td class="align-middle">
                                    <div class="btn-group align-top">
                                      
                                      <a href="'.url("admin/coupons/edit/") .'/'.$row["id"].'"   class="mr-2 btn btn-info btn-sm editmodule"><i class="fe fe-edit mr-1"></i> Edit</a>
                                      <button  class="btn btn-secondary btn-sm deletemodule" onclick="deletecpn('.$row["id"].');" type="button"><i class="fe fe-trash-2  mr-1"></i>Delete</button>
                                    </div>
                                  </td>
                                </tr>';

                               }
                          }else {

                            $cpn_title = Coupon::getCpnContent($row["cpn_title_cid"]);
                            $cpn_html .=' <tr>
                                  <td class="align-middle select-checkbox" id="moduleid" data-value="'.$row["id"].'">
                                    <label class="custom-control custom-checkbox">
                                      
                                      
                                    </label>
                                  </td>
                                  <td class="align-middle" >
                                    <div class="d-flex">
                                  
                                  <h6 class=" font-weight-bold">'.$cpn_title.'</h6>
                                        
                                      
                                    </div>
                                  </td>
                                  <td class="align-middle" >
                                    <div class="d-flex">
                                    <p>'.$row["ofr_code"].'</p>
                                  </div>
                                  </td>
                                  <td class="align-middle" >
                                    <div class="d-flex">
                                      <p>'.$row["ofr_value"].'</p>
                                    </div>
                                  </td>
                                  <td class="align-middle" >
                                    <div class="d-flex">
                                      <p>'. ucfirst($row["ofr_type"]) .'</p>
                                    </div>
                                  </td>
                                  <td class="text-nowrap align-middle">';

                          $valid_till = "";
                          if($row["validity_type"] == "days") 
                          {
                            $days = $row['valid_days'];
                        $valid_till = date('d-m-Y', strtotime($row['created_at'] ."+$days days"));
                          }
                          
                          else {
                            $valid_till = $row['valid_to'];
                          }
                          if($row["is_active"] ==1){ $activestat = "Active"; $activesel = "checked"; }else{ $activestat ="Inactive"; $activesel = ""; }
          
                                   $cpn_html .='  <p>'. $valid_till .'</p>
                                  </td>
                                  
                                  <td class="text-nowrap align-middle"  data-search="'.$activestat.'">
                                    
                                  <div class="switch">
                                  <input class="switch-input status-btn ser_status" data-selid="'.$row["id"].'"  id="status-'.$row["id"].'"  type="checkbox" '.$activesel.' >
                                  <label class="switch-paddle" for="status-'.$row["id"].'">
                                  <span class="switch-active" aria-hidden="true">Active</span>
                                  <span class="switch-inactive" aria-hidden="true">Inactive</span>
                                  </label>
                                  </div>                    
                  
                                  </td>
                                  <td class="text-nowrap align-middle"><span>'.date("d M Y",strtotime($row["created_at"])).'</span></td>
                                  
                                  <td class="align-middle">
                                    <div class="btn-group align-top">
                                      
                                      <a href="'.url("admin/coupons/log/").'/'.$row["id"].'"   class="mr-2 btn btn-info btn-sm editmodule"><i class="fe fe-edit mr-1"></i> View</a>
                                      
                                    </div>
                                  </td>
                                  <td class="align-middle">
                                    <div class="btn-group align-top">
                                      
                                      <a href="'.url("admin/coupons/edit/") .'/'.$row["id"].'"   class="mr-2 btn btn-info btn-sm editmodule"><i class="fe fe-edit mr-1"></i> Edit</a>
                                      <button  class="btn btn-secondary btn-sm deletemodule" onclick="deletecpn('.$row["id"].');" type="button"><i class="fe fe-trash-2  mr-1"></i>Delete</button>
                                    </div>
                                  </td>
                                </tr>';
                          }

                            
                             }
                           }else {
                            $cpn_html = "0";
                           }


        return $cpn_html;
        
        }
    

   
}
