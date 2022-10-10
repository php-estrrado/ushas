<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;
use App\Models\Admin;
use App\Models\UserRole;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\PrdPrice;
use App\Models\PrdAdminImage;
use App\Models\UsrWishlist;
use App\Models\UserVisit;
use App\Models\UserProductVisit;
use App\Models\LoyaltyRewards;
use App\Models\LogLoyaltyPoints;
use App\Models\LoyalityPoints;
use App\Models\LoyaltyRedeemed;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;
use Carbon\Carbon;
use App\Rules\Name;
use Validator;

class LoyaltyPointController extends Controller
{
    public function point_list(Request $request)
    {
   if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
        $user_id = $user['user_id'];
        $lang=$request->lang_id;
        $validator=  Validator::make($request->all(),[
            'access_token' => ['required']
        ]);
        $input = $request->all();

    if ($validator->fails()) 
    {    
      return ['httpcode'=>400,'status'=>'error','message'=>'Invalid parameters','data'=>['errors'=>$validator->messages()]];
    }
    else
    {
        $bal_points = LogLoyaltyPoints::getbalancePoints($user_id);
        $points_list = LogLoyaltyPoints::getData($user_id);
         $points_data = LogLoyaltyPoints::getpointsData($user_id);
      //dd();
        return ['httpcode'=>'200','status'=>'success','message'=>'Points list','data'=>['total_points'=>$points_data['total_credit'],'used_points'=>$points_data['total_debit'],'balance'=>$points_data['balance'],'point_list'=>$points_list]];
        
    }
   }

   public function reward_list(Request $request){
   if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
   $user_id = $user['user_id'];
   $lang=$request->lang_id;
     $bal_points = LogLoyaltyPoints::getbalancePoints($user_id);
     $rwd_list = [];
     if($bal_points>0)
     {
        $rwd = LoyaltyRewards::where('required_points','<=',$bal_points)->where('is_active',1)->where('is_deleted',0)->get();
//dd($rwd);
        foreach($rwd as $row)
        {
            $list['reward_id'] = $row->id;
            $list['product_id'] = $row->product_id;
            $list['product_name'] = $row->product->name;
            $list['required_points'] = $row->required_points;
            $list['max_qty'] = $row->quantity;
			$list['price'] = $this->get_price($row->product_id);
            $list['image'] = $this->get_product_image($row->product_id);
            $rwd_list[] = $list;

        }

        return ['httpcode'=>'200','status'=>'success','message'=>'Rewards list','data'=>['balance'=>$bal_points,'reward_list'=>$rwd_list]];

     }else{
		 
		return ['httpcode'=>400,'status'=>'error','message'=>'No Rewards Found'];
	 }

    }
	public function get_price($prdid){ 
		//$offer['offer_price']=false;
        $current_date=Carbon::now();
        $prod_data= Product::where('id',$prdid)->first();
		$Price = PrdPrice::where('is_deleted',0)->where('prd_id',$prdid)->orderBy('id','DESC')->first();        
		$actual_price=NULL;  
		if($Price){
				$actual_price=$Price->price;
                }else{
                $actual_price=NULL;   
                }
					
                return $actual_price;
	
		
	
	   
	}

    public function reward_redeem(Request $request)
    {
   if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
        $user_id = $user['user_id'];
        $lang=$request->lang_id;
        $validator=  Validator::make($request->all(),[
            'access_token' => ['required'],
            'reward_id'    => ['required','numeric'],
            'qty'          => ['required','numeric']
        ]);
        $input = $request->all();

    if ($validator->fails()) 
    {    
      return ['httpcode'=>400,'status'=>'error','message'=>'Invalid parameters','data'=>['errors'=>$validator->messages()]];
    }
    else
    {
        $bal_points = LogLoyaltyPoints::getbalancePoints($user_id);
        $rwd = LoyaltyRewards::where('id',$request->reward_id)->where('is_active',1)->where('is_deleted',0)->first();

        if($rwd)
        {
             if($request->qty <= $rwd->required_points)
             {
            $purchase_rwd = $rwd->required_points*$request->qty;
            if($purchase_rwd <= $bal_points)
            {    
             $redm = LoyaltyRedeemed::create(['user_id'=>$user_id,'reward_id'=>$rwd->id,'redeemed_quantity'=>$request->qty,'redemption_date'=>date('Y-m-d H:i:s'),'status'=>'pending'])->id;
             $log =  LogLoyaltyPoints::create(['user_id'=>$user_id,'credit'=>0,'debit'=>$purchase_rwd]);
               
             return ['httpcode'=>'200','status'=>'success','message'=>'Redeemed Successfully'];
            }
            else
            {
                return ['httpcode'=>400,'status'=>'error','message'=>'Reward points exceeded','data'=>['errors'=>'Reward points exceeded']];
            }
           }
           else
           {
                return ['httpcode'=>400,'status'=>'error','message'=>'Reward quantity exceeds the limit','data'=>['errors'=>'Reward quantity exceeds the limit']];
           }
        }
        else
        {
            return ['httpcode'=>400,'status'=>'error','message'=>'Invalid parameters','data'=>['errors'=>'Check the params']];
        }
    }

    }

    function get_product_image($prd_id){
        $data     =   [];
        
        //$admin_pro=Product::where('id',$prd_id)->first();
        
        
        $product_seller       =   ProductImage::where('prd_id',$prd_id)->where('is_deleted',0)->get();
        if(!empty($product_seller))
        {
            foreach($product_seller as $k=>$row){ 
                if($row->image)
                {
                $val['image']       =   config('app.storage_url').$row->image;
                }
                if($row->thumb)
                {
                $val['thumbnail']   =   config('app.storage_url').$row->thumb;
                }
                $data[]             =   $val;
            }
        }
        
        else{ $data     =   []; } return $data;
        
    }
}
