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
use App\Models\SettingOther;
use App\Models\Category;
use App\Models\Store;

use App\Models\Admin;


use App\Rules\Name;
use Validator;

class SettingsOthersController extends Controller
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
    
    public function settings()
        { 
        $data['title']              =   'Settings';
        $data['menu']               =   'settings';
        $data['settings']              =   SettingOther::getOtherSettings();
        
        //dd($data);
        return view('admin.settings.view',$data);
        }

    

        

        public function settingsSave(Request $request)
        { 
        $input = $request->all();
      
        // dd($input);

        // if($input['id']>0){

     
        // // $validator= $request->validate([
        // // 'refund_deduction'   =>  ['required'],
        // // 'return_period' => ['required'],
        // // 'bid_charge' => ['required']

        // // ], [], 
        // // [
        // // 'refund_deduction' => 'Refund Deduction',
        // // 'return_period' => 'Return Time Period',
        // // 'bid_charge' => 'Auction Bid Charges'
        // // ]);
        
        // $validator= $request->validate([
        // 'mjs_fee'   =>  ['required','digits_between:1,5','numeric'],
        // 'pg_fee' => ['required','digits_between:1,5','numeric'],
        // 'tax' => ['required','digits_between:1,5','numeric']
        
        
        // ], [], 
        // [
        // 'mjs_fee' => 'MJS Fee',
        // 'return_period' => 'Payment Gateway Fee',
        // 'tax' => 'Tax'
        // ]);



        //  $settingsval =  SettingOther::where('id',$input['id'])->update([
        //  'org_id' => 1, 
        //  'type' => 'tax',
        // 'name' => 'VAT',
        // 'value' => $input['tax'],
        // 'refund_deduction' =>0,
        // 'return_period' => 0,
        // 'bid_charge' => 0,
        // 'mjs_fee' => $input['mjs_fee'],
        // 'pg_fee' => $input['pg_fee'],
        // 'is_active'=>1,
        // 'is_deleted'=>0,
        // 'updated_by'=>auth()->user()->id,
        // 'updated_at'=>date("Y-m-d H:i:s")
        //         ]);

        // if($settingsval) {

        // Session::flash('message', ['text'=>'Settings updated successfully','type'=>'success']); 
        // }else {
        // Session::flash('message', ['text'=>'Settings updation failed','type'=>'danger']);
        // }


        // }else{

   $validator= $request->validate([
         'refund_deduction'   =>  ['required'],
         'return_period' => ['required'],
         'point_equivalent' => ['required'],

        ], [], 
        [
         'refund_deduction' => 'Refund Deduction',
         'return_period' => 'Return Time Period',
         'point_equivalent' => 'Points Equivalent',
        ]);

       


        $settingsupdate =  SettingOther::where('is_active',1)->update([
        'is_active'=>0,
        'is_deleted'=>1,
        'updated_by'=>auth()->user()->id,
        'updated_at'=>date("Y-m-d H:i:s")
                ]);

        $settingsval =  SettingOther::create([
        'org_id' => 1, 
        'type' => 'tax',
        'name' => 0,
        'value' => 0,
          'refund_deduction' =>$input['refund_deduction'],
        'return_period' =>$input['return_period'],
        'point_equivalent' =>$input['point_equivalent'],
        'bid_charge' => 0,
        'mjs_fee' => 0,
        'pg_fee' => 0,
        'invite_save' =>0,
        'invite_discount' => 0,
        'valid_days' => 0,
        'is_active'=>1,
        'created_by'=>auth()->user()->id,
        'updated_by'=>auth()->user()->id,
        'is_deleted'=>0,
        'created_at'=>date("Y-m-d H:i:s"),
        'updated_at'=>date("Y-m-d H:i:s")
                ]);

     
        $lastId = $settingsval->id;
        if($lastId) {
        Session::flash('message', ['text'=>'Settings saved successfully','type'=>'success']);  
        }else {
        Session::flash('message', ['text'=>'Settings saving failed','type'=>'danger']);
        }
        


        // }

        $data['title']              =   'Settings';
        $data['menu']               =   'settings';
        $data['settings']              =   SettingOther::getOtherSettings();

        // dd($data);
    
        return redirect(route('admin.settings'));
    }
               



        

             
    

   
}
