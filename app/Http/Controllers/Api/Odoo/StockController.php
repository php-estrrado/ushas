<?php

namespace App\Http\Controllers\Api\Odoo;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Session;
use DB;
use App\Models\PrdPrice;
use App\Models\PrdStock;
use App\Models\Admin;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


use App\Rules\Name;
use Validator;

class StockController extends Controller
{
    public function delete($id){ 
	$exist = PrdPrice::where('id',$id)->where('is_deleted','0')->first();			
			if($exist){
				PrdPrice::where('id',$id)->update(['is_deleted'=>1]);
				return array('httpcode'=>'200','status'=>'success','message'=>'Success','data'=>"Deleted Successfully");

			}else{
				return array('httpcode'=>'400','status'=>'error','message'=>"Not Found",'data'=>"Price Not Found");

			}
	}
	public function list($product_id){ 
		$datas = PrdPrice::where('prd_id',$product_id)->orderby('id','desc')->where('is_deleted','0')->get();			
			if($datas){
				$pricelist=[];
				foreach($datas as $data){
				$list['id']    = $data->id;
				$list['product_id'] = $data->prd_id;
				$list['odoo_id'] = $data->odoo_id;
				$list['platform'] = $data->platform;
				$list['price']    	= $data->price;
				$list['sale_price']        = $data->sale_price;
				$list['sale_start_on']        = $data->sale_start_date;
				$list['sale_ends_on']        = $data->sale_end_date;
				$pricelist[]=$list;
				}
				return array('httpcode'=>'200','status'=>'success','message'=>'Success','price list'=>$pricelist);

			}else{
				return array('httpcode'=>'400','status'=>'error','message'=>"Not Found",'data'=>"Product Prices Not found");

			}
	}
		
	public function create(Request $request){ 
        $input = $request->all();
		$rules      =   array();
        $rules['product_id']    = 'required|numeric';
        $rules['price']    = 'required|numeric';
        $rules['sale_price']        = 'required|numeric';
        $rules['sale_start_on']        = 'required|date';
        $rules['sales_ends_on']      = 'required|date|after_or_equal:sale_start_on';
        $rules['odoo_id']        = 'required|numeric|unique:prd_prices,odoo_id';
		$validator  =   Validator::make($request->all(), $rules);
		if($validator->fails()){
			foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
			return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
		} else {
            
			$Price=PrdPrice::create([
            'prd_id' => $input['product_id'],
            'price'=>$input['price'],
            'sale_price' => $input['sale_price'],
			'odoo_id' => $input['odoo_id'],
            'platform' => "odoo",
            'sale_start_date'=>$input['sale_start_on'],
            'sale_end_date' => $input['sales_ends_on'],
            'created_by' => 1,
            'modified_by'=>1,
            'platform'=>"odoo",
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
			]);
			
		return array('httpcode'=>'200','status'=>'success','message'=>'Success','data'=>['insert_id' =>$Price]);
		}
		
	}

	public function update(Request $request,$id){ 
        $input = $request->all();
		$rules      =   array();
        $rules['product_id']    = 'required|numeric';
        $rules['price']    = 'required|numeric';
        $rules['sale_price']        = 'numeric';
        $rules['sale_start_on']        = 'date';
		$rules['odoo_id']        = 'required|numeric|unique:prd_prices,odoo_id,'.$id.',id';
        $rules['sales_ends_on']      = 'date|after_or_equal:sale_start_on';
        $validator  =   Validator::make($request->all(), $rules);
		if($validator->fails()){
			foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
			return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
		} else {
            $exist=PrdPrice::where('id',$id)->where('is_deleted',0)->first();
            if($exist){		
			
			$Price=PrdPrice::where('id',$id)->update([
            'prd_id' => $input['product_id'],
            'price'=>$input['price'],
            'sale_price' => $input['sale_price'],
            'sale_start_date'=>$input['sale_start_on'],
            'sale_end_date' => $input['sales_ends_on'],
			'odoo_id' => $input['odoo_id'],
            'platform' => "odoo",
            'created_by' => 1,
            'modified_by'=>1,
            'platform'=>"odoo",
            'updated_at'=>date("Y-m-d H:i:s")
			]);
		$PrdPrice=PrdPrice::find($id);	
		return array('httpcode'=>'200','status'=>'success','message'=>'Success','data'=>['data' =>$PrdPrice]);
		} else{
		return array('httpcode'=>'400','status'=>'error','message'=>'Not Found');	
		}

		}
		
	}
	public  function slugify($text, string $divider = '-'){
	  // replace non letter or digits by divider
	  $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

	  // transliterate
	  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

	  // remove unwanted characters
	  $text = preg_replace('~[^-\w]+~', '', $text);

	  // trim
	  $text = trim($text, $divider);

	  // remove duplicate divider
	  $text = preg_replace('~-+~', $divider, $text);

	  // lowercase
	  $text = strtolower($text);

	  if (empty($text)) {
		return 'n-a';
	  }

	  return $text;
	}

	
	public function stockcreate(Request $request){ 
        $input = $request->all();
		$rules      =   array();
        $rules['product_id']    = 'required|numeric';
        $rules['stock']    = 'required|numeric';
		$rules['odoo_id']        = 'required|numeric|unique:prd_stocks,odoo_id';
        
        $validator  =   Validator::make($request->all(), $rules);
		if($validator->fails()){
			foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
			return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
		} else {
            
			$Stock=PrdStock::create([
            'prd_id' => $input['product_id'],
            'qty'=>$input['stock'],
            'type' => "add",
			'odoo_id' => $input['odoo_id'],
            'platform' => "odoo",
            'created_by'=>1,
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
			])->id;
			
		return array('httpcode'=>'200','status'=>'success','message'=>'Success','data'=>['insert_id' =>$Stock]);
		}
		
	}
	
	public function stockupdate(Request $request,$id){ 
        $input = $request->all();
		$rules      =   array();
        $rules['product_id']    = 'required|numeric';
        $rules['stock']    = 'required|numeric';
		$rules['odoo_id']        = 'required|numeric|unique:prd_stocks,odoo_id,'.$id.',id';
        $validator  =   Validator::make($request->all(), $rules);
		if($validator->fails()){
			foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; $errorMag[] = $row[0]; }  
			return array('httpcode'=>'400','status'=>'error','message'=>$errorMag[0],'data'=>array('errors' =>(object)$error));
		} else {
            $exist=PrdStock::where('id',$id)->where('is_deleted',0)->first();
            if($exist){		
			
			$PrdStock=PrdStock::where('id',$id)->update([
            'prd_id' => $input['product_id'],
            'qty'=>$input['stock'],
            'type' => "add",
			'odoo_id' => $input['odoo_id'],
            'platform' => "odoo",
            'created_by'=>1,
            'updated_at'=>date("Y-m-d H:i:s")
			]);
		$PrdStock=PrdStock::find($id);	
		return array('httpcode'=>'200','status'=>'success','message'=>'Success','data'=>['data' =>$PrdStock]);
		} else{
		return array('httpcode'=>'400','status'=>'error','message'=>'Not Found');	
		}

		}
		
	}
	public function stockdelete($id){ 
	$exist = PrdStock::where('id',$id)->where('is_deleted','0')->first();			
			if($exist){
				PrdStock::where('id',$id)->update(['is_deleted'=>1]);
				return array('httpcode'=>'200','status'=>'success','message'=>'Success','data'=>"Deleted Successfully");

			}else{
				return array('httpcode'=>'400','status'=>'error','message'=>"Not Found",'data'=>"Price Not Found");

			}
	}
	
	public function stocklist($product_id){ 
		$datas = PrdStock::where('prd_id',$product_id)->orderby('id','desc')->where('is_deleted','0')->get();			
			if($datas){
				$stocklist=[];
				foreach($datas as $data){
				$list['id']    = $data->id;
				$list['product_id'] = $data->prd_id;
				$list['odoo_id'] = $data->odoo_id;
				$list['platform'] = $data->platform;
				$list['stock']    	= $data->qty;
				$stocklist[]=$list;
				}
				return array('httpcode'=>'200','status'=>'success','message'=>'Success','stock list'=>$stocklist);

			}else{
				return array('httpcode'=>'400','status'=>'error','message'=>"Not Found",'data'=>"Product Prices Not found");

			}
	}

   
}
