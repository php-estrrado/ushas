<?php

namespace App\Http\Controllers\Api\Odoo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Tax;
use App\Models\TaxValue;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\CmsContent;
use Validator;

class GeneralController extends Controller
{
    public function tax_creation(Request $request){
    $validator = Validator::make($request->all(), [
        'tax_name'=>['required'],
        'valid_from'   =>  ['nullable', 'date_format:Y-m-d','after_or_equal:'.date('Y-m-d')],
        'valid_to' => ['nullable', 'date_format:Y-m-d','after_or_equal:'.date('Y-m-d')],
        'percentage_value'=>['required','numeric']
        ]);


        if ($validator->passes()) {
            $lang_id =1;
            $from =$to=NULL;
            if($request->valid_from){$from =$request->valid_from;}
            if($request->valid_to){$to =$request->valid_to;}
            $tax =  Tax::create([
        'org_id' => 1, 
        'name' =>$request->tax_name,
        'tax_name_cid' => $this->addCmsContent(NULL,1,$request->tax_name),
        'is_active'=>1,
        'is_deleted'=>0,
        'created_by'=>1,
        'modified_by'=>1,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")]); 
     
        $lastId = $tax->id;
          $taxvalue =  TaxValue::create([
        'org_id' => 1, 
        'tax_id' => $lastId,
        'percentage' => $request->percentage_value,
        'valid_from' =>$from,
        'valid_to' =>$to,
        'state_id' => 0,
        'country_id' =>191,
        'is_active'=>1,
        'is_deleted'=>0,
        'created_by'=>1,
        'modified_by'=>1,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")
        ]);  

        return ['httpcode'=>'200','status'=>'success','message'=>'Tax created','data'=>['unique_id'=>$lastId]]; 
        }
         else
        {
            return ['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()];
        }
    }

    public function tax_edit(Request $request){
    $validator = Validator::make($request->all(), [
        'tax_id'=>['required'],
        'tax_name'=>['required'],
        'valid_from'   =>  ['nullable', 'date_format:Y-m-d','after_or_equal:'.date('Y-m-d')],
        'valid_to' => ['nullable', 'date_format:Y-m-d','after_or_equal:'.date('Y-m-d')],
        'percentage_value'=>['required']
        ]);


        if ($validator->passes()) {
            $lang_id =1;
            $taxDetail = Tax::where('id',$request->tax_id)->first();
            if($taxDetail)
            {
            $from =$to=NULL;
            if($request->valid_from){$from =$request->valid_from;}
            if($request->valid_to){$to =$request->valid_to;}
            $tax =  Tax::where('id',$request->tax_id)->update([
        'name' =>$request->tax_name,
        'tax_name_cid' => $this->addCmsContent($taxDetail->tax_name_cid,1,$request->tax_name),
        'updated_at'=>date("Y-m-d H:i:s")]); 

          $taxvalue =  TaxValue::where('tax_id',$request->tax_id)->update([
        'percentage' => $request->percentage_value,
        'valid_from' =>$from,
        'valid_to' =>$to,
        'updated_at'=>date("Y-m-d H:i:s")
        ]);  

        return ['httpcode'=>'200','status'=>'success','message'=>'Tax Updated']; 
         }
         else
         {
           return ['httpcode'=>'400','status'=>'error','error'=>'Not found']; 
         }
        }
         else
        {
            return ['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()];
        }
    }

    public function tax_view(Request $request){
    $validator = Validator::make($request->all(), [
        'tax_id'=>['required']
        ]);


        if ($validator->passes()) {
            $lang_id =1;
            $taxDetail = Tax::where('id',$request->tax_id)->first();
            if($taxDetail)
            {
            $taxdata = Tax::getTaxDataAPI($taxDetail->id);

        return ['httpcode'=>'200','status'=>'success','message'=>'Tax','data'=>['tax_data'=>$taxdata]]; 
         }
         else
         {
           return ['httpcode'=>'400','status'=>'error','error'=>'Not found']; 
         }
        }
         else
        {
            return ['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()];
        }
    }

    public function tax_list(Request $request){
       $taxdata = Tax::getTaxAPI();
       return ['httpcode'=>'200','status'=>'success','message'=>'Tax','data'=>['tax_data'=>$taxdata]]; 
   }

   public function tax_delete(Request $request){
    $validator = Validator::make($request->all(), [
        'tax_id'=>['required']
        ]);
        if ($validator->passes()) {
            $taxDetail = Tax::where('id',$request->tax_id)->first();
            if($taxDetail)
            {
            $taxdelete = Tax::where('id',$taxDetail->id)->update(['is_deleted'=>1]);
            $taxvaldelete = TaxValue::where('tax_id',$taxDetail->id)->update(['is_deleted'=>1]);

        return ['httpcode'=>'200','status'=>'success','message'=>'Tax deleted']; 
         }
         else
         {
           return ['httpcode'=>'400','status'=>'error','error'=>'Not found']; 
         }
        }
         else
        {
            return ['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()];
        }
    }

    public function country_list(){
        $country =[];
        $country = Country::where('is_deleted',0)->select('id','sortname','country_name','phonecode')->get();
        return ['httpcode'=>'200','status'=>'success','message'=>'Country List','data'=>['country'=>$country]];
    }

    public function state_list(Request $request){
        $state =[];
        $state = State::where('is_deleted',0)->select('id','country_id','state_name','state_code');
        if($request->country_id)
        {
            $state = $state->where('country_id',$request->country_id);
        }
        $state = $state->get();
        return ['httpcode'=>'200','status'=>'success','message'=>'State List','data'=>['state'=>$state]];
    }
    public function city_list(Request $request){
        $state =[];
        $state = City::where('is_deleted',0)->select('id','state_id','city_name');
        if($request->state_id)
        {
            $state = $state->where('state_id',$request->state_id);
        }
        $state = $state->get();
        return ['httpcode'=>'200','status'=>'success','message'=>'City List','data'=>['city'=>$state]];
    }

    function addCmsContent($cntId,$l, $cnt){
        if($cnt){
        if($cntId){
        $qry                =   CmsContent::where('cnt_id',$cntId)->where('is_deleted',0)->first();
        if($qry)
        {
            CmsContent::where('cnt_id',$cntId)->update(['content'=>$cnt,'updated_by'=>1]);
            $insertId = $qry->cnt_id;
        }
        else
        {
            $insertId=CmsContent::create(['cnt_id'=>$cntId,'lang_id'=>$l,'content'=>$cnt,'created_by'=>1])->id;
            $insertId = CmsContent::where('id',$insertId)->first()->cnt_id;
        }
       }
       else{
            $cms            =   CmsContent::orderBy('cnt_id','desc')->first(); if($cms){ $cntId = ($cms->cnt_id+1); }else{ $cntId = 1; }
            $cmscont        =   CmsContent::create(['cnt_id'=>$cntId,'lang_id'=>$l,'content'=>$cnt,'created_by'=>1])->id;
            $insertId = $cntId;
        }
        }
        else
        {
            $insertId =NULL;
        }
        
        return $insertId;

    }
}
