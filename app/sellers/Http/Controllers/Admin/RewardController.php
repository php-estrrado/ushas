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
use App\Models\Reward;
use App\Models\RewardType;
use App\Models\Category;
use App\Models\Store;

use App\Models\Admin;


use App\Rules\Name;
use Validator;

class RewardController extends Controller
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
    
    public function rewards()
        { 
        $data['title']              =   'Rewards';
        $data['menu']               =   'rewards';
        $data['rewards']              =   Reward::getRewards();
        
        // dd($data);
        return view('admin.benefits.rewards.view',$data);
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

        

        public function rewardSave(Request $request)
        { 
        $input = $request->all();
      
        // dd($input);

        if($input['id']>0){

     
        $validator= $request->validate([
        'point_val'   =>  ['required'],
        'ord_amount' => ['required'],
        'ord_min_amount' => ['required']

        ], [], 
        [
        'point_val' => 'Referral Point Value',
        'ord_amount' => 'Amount',
        'ord_min_amount' => 'Minimum Order Amount'
        ]);

      if(isset($input['reward_points'])) {
        foreach ($input['reward_points'] as $rk => $rv) {
                $RewardTypeVal =  RewardType::where('id',$rk)->update([
                    'points'=>$rv,
                    'updated_by'=>auth()->user()->id,
                    'updated_at'=>date("Y-m-d H:i:s")
                ]);
        }
       }
       // Reward::where('is_active',1)->update(array('is_deleted'=>1,'is_active'=>0));

         $RewardVal =  Reward::where('id',$input['id'])->update([
         'org_id' => 1, 
        'rwd_type' => $input['rwd_type'],
        'ord_amount' => $input['ord_amount'],
        'ord_type' => $input['ord_type'],
        'ord_min_amount' => $input['ord_min_amount'],
        'point_val' => $input['point_val'],
        'is_active'=>1,
        'is_deleted'=>0,
        'updated_by'=>auth()->user()->id,
        'updated_at'=>date("Y-m-d H:i:s")
                ]);

        if($RewardVal) {

        Session::flash('message', ['text'=>'Reward updated successfully','type'=>'success']); 
        }else {
        Session::flash('message', ['text'=>'Reward updation failed','type'=>'danger']);
        }


        }else{

       $validator= $request->validate([
        'point_val'   =>  ['required'],
        'ord_amount' => ['required'],
        'ord_min_amount' => ['required']

        ], [], 
        [
        'point_val' => 'Referral Point Value',
        'ord_amount' => 'Amount',
        'ord_min_amount' => 'Minimum Order Amount'
        ]);



  // dd($input);
       if(isset($input['reward_points'])) {
        foreach ($input['reward_points'] as $rk => $rv) {
                $RewardTypeVal =  RewardType::where('id',$rk)->update([
                    'points'=>$rv,
                    'updated_by'=>auth()->user()->id,
                    'updated_at'=>date("Y-m-d H:i:s")
                ]);
        }
       }
  $RewardVal =  Reward::create([
                            'org_id' => 1, 
        'rwd_type' => $input['rwd_type'],
        'ord_amount' => $input['ord_amount'],
        'ord_type' => $input['ord_type'],
        'ord_min_amount' => $input['ord_min_amount'],
        'point_val' => $input['point_val'],
        'is_active'=>1,
        'created_by'=>auth()->user()->id,
        'updated_by'=>auth()->user()->id,
        'is_deleted'=>0,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")
                ]);

     
        $lastId = $RewardVal->id;
        if($lastId) {
        Session::flash('message', ['text'=>'Reward created successfully','type'=>'success']);  
        }else {
        Session::flash('message', ['text'=>'Reward creation failed','type'=>'danger']);
        }
        


        }

        $data['title']              =   'Rewards';
        $data['menu']               =   'rewards';
        $data['rewards']              =   Reward::getRewards();

        // dd($data);
    
        return redirect(route('admin.rewards'));
    }
               



        

             
    

   
}
