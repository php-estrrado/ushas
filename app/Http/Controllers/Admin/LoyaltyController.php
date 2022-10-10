<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;

use App\Models\LoyaltyPoints;
use App\Models\LoyaltyRewards;
use App\Models\LogLoyaltyPoints;
use App\Models\customer\CustomerInfo;
use App\Models\Product;
use App\Models\LoyaltyRedeemed;
use App\Rules\Name;
use Validator;


class LoyaltyController extends Controller
{
    public function __construct(){
        $this->middleware('auth:admin');
    }
	
	public function loyalty(Request $request){
        $data['title']         =   'Loyalty Point';
        $data['menu']          =   '';
        $data['loyality']      =   LoyaltyPoints::first();
		return view('admin.loyalty_points.create',$data);
		
	}
	
	public function validateLoyalty (Request $request){
        $existName      =  $error = false;
		$post           =  (object)$request->post(); 
        
         		$rules          =   [
                'points'=> 'required',
                'value'=> 'required',
				];
        
		$validator = Validator::make($request->all() ,$rules);
		if($validator->fails()) {
            foreach($validator->messages()->getMessages() as $k=>$row){ 
			$error[$k] = $row[0]; 
		}
		}
		if($error) { return $error; }else{ return 'success'; } return 'success'; 
    }
	
	public function saveLoyalty(Request $request){

        $post  = (object)$request->post(); 
		$input = $request->all();
		
		$id=$post->loyality_id;
		$data['point']=$post->points;
		$data['order_amount']=$post->value;
		$data['is_active']=$post->status;
		LoyaltyPoints::where('id',$id)->update($data);

		
		return "1"; 

    }
	
	public function loyalty_rewards(Request $request){
		$data['title']              =   'Loyalty Reward';
        $data['menu']               =   'Loyalty Reward';
		$config_visible				= 	 Product::where('is_deleted','0')->where('is_active','1')->where('product_type','2')->where('visible','1')->pluck('id');
		$rewarded_products				= 	 LoyaltyRewards::where('is_deleted','0')->where('is_active','1')->pluck('product_id');
		$data['products']					= 	 Product::whereNotIn('id',$config_visible)->whereNotIn('id',$rewarded_products)->where('is_deleted','0')->where('is_active','1')->get();
		$post                       =   (object)$request->post();
		if(isset($post->vType)      ==  'ajax'){ 
		$data['vType']          =   $post->vType;
		return LoyaltyRewards::getListData($post); }
        else{ return view('admin.loyalty_points.rewards.list',$data); }
	}
	
	public function validateLoyaltyReward (Request $request){
        $existName      =  $error = false;
		$post           =  (object)$request->post(); 
        
         		$rules          =   [
                'product'=> 'required',
                'points_required'=> 'required|numeric',
                'quantity'=> 'required|numeric|min:1',
				];
        
		$validator = Validator::make($request->all() ,$rules);
		if($validator->fails()) {
            foreach($validator->messages()->getMessages() as $k=>$row){ 
			$error[$k] = $row[0]; 
		}
		}
		if($error) { return $error; }else{ return 'success'; } return 'success'; 
    }
	
	public function saveLoyaltyReward(Request $request){

        $post  = (object)$request->post(); 
		$input = $request->all();
		if(isset($post->id)){
			$id=$post->id;
		}else{
			$id=0;
		}
		$status=0;			
		$data['required_points']=$post->points_required;
		$data['quantity']=$post->quantity;
		if($id){
		LoyaltyRewards::where('id',$id)->update($data);
		$status=2;	
		}else{
		$data['product_id']=$post->product;
		$id=LoyaltyRewards::create($data)->id;
		$status=1;	
		}
		return $status;

    }
	public function editLoyaltyReward($id){
		$data['title']              =   'Loyalty Reward';
        $data['menu']               =   'Loyalty Reward';
		$data['reward']				= 	LoyaltyRewards::join('prd_products', 'prd_products.id', '=', 'loyalty_rewards.product_id')
		->select('loyalty_rewards.*', 'prd_products.name')
		->where('loyalty_rewards.id',$id)->first();
		return view('admin.loyalty_points.rewards.edit',$data); 
	}
	
	public function deleteLoyaltyReward(Request $request){
        $post               =  (object)$request->post(); 
        $id = $post->id;
		LoyaltyRewards::where('id',$id)->update(['is_deleted' => 1]);
		return '1';
	}
	
	public function customerLoyaltyPoints(Request $request){
		$data['title']              =   'Customers Loyalty Points';
        $data['menu']               =   'Customers Loyalty Points';
		$post                       =   (object)$request->post();
		if(isset($post->vType)      ==  'ajax'){ 
		$data['vType']          =   $post->vType;
		return LogLoyaltyPoints::getListData($post); }
        else{ return view('admin.loyalty_points.customer.list',$data); } 
	}
	
	public function customerLoyaltyLog($user_id){
		$data['title']              =   'Customer Loyalty Points Log';
        $data['menu']               =   'Customer Loyalty Points';
		$data['log']=LogLoyaltyPoints::where('user_id',$user_id)->where('is_deleted',0)->get();
		$credit=0;
		$debit=0;
		if($data['log']){
			foreach($data['log'] as $log){
				$credit+= $log->credit;
				$debit+= $log->debit;
			}
		}
		$data['balance']=$credit-$debit;
		$data['total']=$credit;
		
		$data['customer']=CustomerInfo::where('user_id',$user_id)->first();
       //dd($credit);
		return view('admin.loyalty_points.customer.log_list',$data); 
	}
	public function customerLoyaltyReward($user_id){
		$data['title']              =   'Customer Loyalty Points Log';
        $data['menu']               =   'Customer Loyalty Points';
		$data['rewards']=LoyaltyRedeemed::where('user_id',$user_id)->where('is_deleted',0)->get();
		$credit=0;
		$debit=0;
		$data['log']=LogLoyaltyPoints::where('user_id',$user_id)->where('is_deleted',0)->get();

		if($data['log']){
			foreach($data['log'] as $log){
				$credit+= $log->credit;
				$debit+= $log->debit;
			}
		}
		$data['balance']=$credit-$debit;
		$data['total']=$credit;
		
		$data['customer']=CustomerInfo::where('user_id',$user_id)->first();
       //dd($credit);
		return view('admin.loyalty_points.customer.reward_list',$data); 
	}
	
	public function changeStatus(Request $request){
        $post               =  (object)$request->post(); 
        $id = $post->id;
        $status = $post->status;
		LoyaltyRedeemed::where('id',$id)->update(['status' => $status]);
		return '1';
	}
	
	
    
}


