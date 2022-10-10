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
use App\Models\Tax;
use App\Models\TaxValue;

use App\Models\Admin;


use App\Rules\Name;
use Validator;

class TaxController extends Controller
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
    
    public function tax()
        { 
        $data['title']              =   'Tax';
        $data['menu']               =   'tax';
        $data['tax']              =   Tax::getTax();
        // dd($data);
        return view('admin.tax.list',$data);
        }

        public function createTax()
        { 
        $data['title']              =   'Create Tax';
        $data['menu']               =   'create-tax';
        $data['language']      =    DB::table('glo_lang_lk')->where('is_active', 1)->get();
        $data['states']      =    DB::table('states')->where('is_deleted', 0)->get();
        $data['countries']      =    DB::table('countries')->where('is_deleted', 0)->get();
    
        // dd($data);
        return view('admin.tax.create',$data);
        }

        public function statesdata(Request $request)
    {
  
         $input = $request->all();
         $country_id = $input['country_id'];
        
            $sub_data=array();
           
              $squery    =  DB::table('states')->where('country_id', $country_id)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();
            
              if($squery->count()> 0)
                {
                  //$sub_data[]=array();
                  foreach($squery as $srow)
                  { 
                        $kk=array();
                        $kk['id'] = $srow->id;
                        $kk['title'] = ucfirst($srow->state_name);
                        $sub_data[]=$kk;
                    
                  }
                }
            
          $result=array('val'=>'1','subdata'=>$sub_data);  
          return json_encode($result);
    }

   

        public function editTax($tax_id)
        { 
        $data['title']              =   'Edit Tax';
        $data['menu']               =   'edit-tax';
        $data['tax']              =  Tax::getTaxData($tax_id);
        $data['language']      =    DB::table('glo_lang_lk')->where('is_active', 1)->get();
        $data['states']      =    DB::table('states')->where('is_deleted', 0)->get();
        $data['countries']      =    DB::table('countries')->where('is_deleted', 0)->get();
        // dd($data);
        return view('admin.tax.edit',$data);
        }
            public function viewTax($tax_id)
        { 
        $data['title']              =   'View Tax';
        $data['menu']               =   'view-tax';
        $data['tax']              =  Tax::getTaxData($tax_id);
        $data['language']      =    DB::table('glo_lang_lk')->where('is_active', 1)->get();
        $data['states']      =    DB::table('states')->where('is_deleted', 0)->get();
        $data['countries']      =    DB::table('countries')->where('is_deleted', 0)->get();
        // dd($data);
        return view('admin.tax.view',$data);
        }

        public function taxSave(Request $request)
        { 
        $input = $request->all();
        // dd($input);


        if($input['id']>0){

     
       $validator= $request->validate([
        'tax_name'   =>  ['required','unique:prd_tax,name,' . $input['id']],
        'tax_desc' => ['required'],
        'country' => ['required'],
        'valid_from' => ['required'],
        'valid_to' => ['required'],
        'percentage' => ['required']

        ], [], 
        [
        'tax_name' => 'Tax Name',
        'tax_desc' => 'Tax Description',
        'country' => 'Country',
        'valid_from' => 'Valid From',
        'valid_to' => 'Valid To',
        'percentage' => 'Percentage'
        ]);

        if (DB::table('cms_content')->where('cnt_id',$input['tax_name_cid'])->where('lang_id',$input['glo_lang_cid'])->exists()) {
        DB::table('cms_content')->where('cnt_id',$input['tax_name_cid'])->where('lang_id',$input['glo_lang_cid'])
        ->update(['content' => $input['tax_name']]);
        $tax_name_cid=$input['tax_name_cid'];
        } else {

        $latest = DB::table('cms_content')->orderBy('cnt_id', 'DESC')->first();
        $tax_name_cid=++$latest->cnt_id;
        DB::table('cms_content')->insertGetId([
        'org_id' => 1, 
        'lang_id' => $input['glo_lang_cid'],
        'cnt_id'=>$tax_name_cid,
        'content' => $input['tax_name'],
        'is_active'=>1,
        'created_by'=>auth()->user()->id,
        'updated_by'=>auth()->user()->id,
        'is_deleted'=>0,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")
        ]);
     

        }

       if (DB::table('cms_content')->where('cnt_id',$input['tax_desc_cid'])->where('lang_id',$input['glo_lang_cid'])->exists()) {
        DB::table('cms_content')->where('cnt_id',$input['tax_desc_cid'])->where('lang_id',$input['glo_lang_cid'])
        ->update(['content' => $input['tax_desc']]);
        $tax_desc_cid=$input['tax_desc_cid'];
        } else {

        $latest = DB::table('cms_content')->orderBy('cnt_id', 'DESC')->first();
        $tax_desc_cid=++$latest->cnt_id;
        DB::table('cms_content')->insertGetId([
        'org_id' => 1, 
        'lang_id' => $input['glo_lang_cid'],
        'cnt_id'=>$tax_desc_cid,
        'content' => $input['tax_desc'],
        'is_active'=>1,
        'created_by'=>auth()->user()->id,
        'updated_by'=>auth()->user()->id,
        'is_deleted'=>0,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")
        ]);
        

        }
        $tax_id = $input['id'];
        if($input['state'] =="") { $input['state']=0;}
        if($tax_desc_cid !="" && $tax_name_cid !="" && $tax_id !="") {


        $tax =  Tax::where('id',$tax_id)->update([
        'org_id' => 1, 
        'name' =>$input['tax_name'],
        'tax_name_cid' => $tax_name_cid,
        'tax_desc_cid' => $tax_desc_cid,
        'is_active'=>$input['is_active'],
        'is_deleted'=>0,
        'updated_by'=>auth()->user()->id,
        'updated_at'=>date("Y-m-d H:i:s")

        ]); 
     
        $taxval_id = $input['taxval_id'];
          $taxvalue =  TaxValue::where('id',$taxval_id)->update([
        'org_id' => 1, 
        'tax_id' => $tax_id,
        'percentage' => $input['percentage'],
        'valid_from' =>date('Y-m-d', strtotime($input['valid_from'])),
        'valid_to' => date('Y-m-d', strtotime($input['valid_to'])),
        'state_id' => $input['state'],
        'country_id' => $input['country'],
        'is_active'=>$input['is_active'],
        'is_deleted'=>0,
        'updated_by'=>auth()->user()->id,
        'updated_at'=>date("Y-m-d H:i:s")

        ]);   
  
        Session::flash('message', ['text'=>'Tax updated successfully','type'=>'success']);  

        }else {
        Session::flash('message', ['text'=>'Tax updation failed','type'=>'danger']);
        }







        }else{

        $validator= $request->validate([
        'tax_name'   => ['required','unique:prd_tax,name,'],
        'tax_desc' => ['required'],
        'country' => ['required'],
        'valid_from' => ['required'],
        'valid_to' => ['required'],
        'percentage' => ['required']

        ], [], 
        [
        'tax_name' => 'Tax Name',
        'tax_desc' => 'Tax Description',
        'country' => 'Country',
        'valid_from' => 'Valid From',
        'valid_to' => 'Valid To',
        'percentage' => 'Percentage'
        ]);


        $latest = DB::table('cms_content')->orderBy('id', 'DESC')->first();
        $tax_name_cid=++$latest->cnt_id;
        $tax_desc_cid =$tax_name_cid+1;

        $tax_name= DB::table('cms_content')->insertGetId([
        'org_id' => 1, 
        'lang_id' => $input['glo_lang_cid'],
        'cnt_id'=>$tax_name_cid,
        'content' => $input['tax_name'],
        'is_active'=>1,
        'created_by'=>auth()->user()->id,
        'updated_by'=>auth()->user()->id,
        'is_deleted'=>0,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")
        ]);


        $tax_desc= DB::table('cms_content')->insertGetId([
        'org_id' => 1, 
        'lang_id' => $input['glo_lang_cid'],
        'cnt_id'=>$tax_desc_cid,
        'content' => $input['tax_desc'],
        'is_active'=>1,
        'created_by'=>auth()->user()->id,
        'updated_by'=>auth()->user()->id,
        'is_deleted'=>0,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")
        ]);
        if($input['state'] =="") { $input['state']=0;}
        if($tax_name !="" && $tax_desc !="") {
        $tax =  Tax::create([
        'org_id' => 1, 
        'name' =>$input['tax_name'],
        'tax_name_cid' => $tax_name_cid,
        'tax_desc_cid' => $tax_desc_cid,
        'is_active'=>$input['is_active'],
        'is_deleted'=>0,
        'created_by'=>auth()->user()->id,
        'modified_by'=>auth()->user()->id,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")

        ]); 
     
        $lastId = $tax->id;
          $taxvalue =  TaxValue::create([
        'org_id' => 1, 
        'tax_id' => $lastId,
        'percentage' => $input['percentage'],
        'valid_from' =>date('Y-m-d', strtotime($input['valid_from'])),
        'valid_to' => date('Y-m-d', strtotime($input['valid_to'])),
        'state_id' => $input['state'],
        'country_id' => $input['country'],
        'is_active'=>$input['is_active'],
        'is_deleted'=>0,
        'created_by'=>auth()->user()->id,
        'modified_by'=>auth()->user()->id,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")

        ]);   
         $lastId = $taxvalue->id;
        if($lastId) {
        Session::flash('message', ['text'=>'Tax created successfully','type'=>'success']);  
        }else {
        Session::flash('message', ['text'=>'Tax creation failed','type'=>'danger']);
        }
        }else {
        Session::flash('message', ['text'=>'Tax creation failed','type'=>'danger']);
        }

        }
               $data['title']              =   'Tax';
        $data['menu']               =   'tax';
        $data['tax']              =   Tax::getTax();
        return redirect(route('admin.tax'));

        }


        public function taxDelete(Request $request)
        {
        $input = $request->all();
        
        if($input['id']>0) {
        $deleted =  Tax::where('id',$input['id'])->update(array('is_deleted'=>1,'is_active'=>0));
        $deleted =  TaxValue::where('tax_id',$input['id'])->update(array('is_deleted'=>1,'is_active'=>0));
        Session::flash('message', ['text'=>'Tax deleted successfully.','type'=>'success']);
        return true;
        }else {
        Session::flash('message', ['text'=>'Tax failed to delete.','type'=>'danger']);
        return false;
        }

        }
           public function taxStatus(Request $request)
        {
        $input = $request->all();
        
        if($input['id']>0) {
        $deleted =  Tax::where('id',$input['id'])->update(array('is_active'=>$input['status']));
        $deleted =  TaxValue::where('tax_id',$input['id'])->update(array('is_active'=>$input['status']));
        
        return '1';
        }else {
        
        return '0';
        }
        
        }
    

   
}
