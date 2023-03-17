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
use App\Models\CustomerPoints;
use App\Models\UsrWishlist;
use App\Models\UserVisit;
use App\Models\UserProductVisit;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;
use Carbon\Carbon;
use App\Rules\Name;
use Validator;

class CustomerPointsController extends Controller
{
    public function point_list(Request $request)
    {
        if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
        
        $access_token = $request->access_token;
        $user_logins = DB::table('usr_logins')->where('access_token',$access_token)->first();

        $user_id = $user_logins->user_id;

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
            $bal_points = CustomerPoints::getbalancePoints($user_id);
            $points_list = CustomerPoints::getData($user_id);
            $points_data = CustomerPoints::getpointsData($user_id);
            
            return ['httpcode'=>'200','status'=>'success','message'=>'Points list','data'=>['total_points'=>$points_data['total_credit'],'used_points'=>$points_data['total_debit'],'balance'=>$points_data['balance'],'point_list'=>$points_list]];
            
        }
    }     
    
}
