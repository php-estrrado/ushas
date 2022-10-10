<?php

namespace App\Http\Controllers;
use Config;
use Artisan;
use Validator;
use DB;
use Hash;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      //  $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
      //  return view('home');
        return redirect('/admin');
    }

    public function lockscreen()
    {
       return view('lockscreen');
      
    }

    public function configSet()
    {
      
      if (session('password_entered')) {
      
      $data['app_name']              =   config("settings.app_name");
      $data['base_url']              =   config("settings.base_url");
      $data['m_lang']              =   config("settings.m_lang");
      $data['m_currency']              =   config("settings.m_currency");
      $data['prod_type']              =   config("settings.prod_type");
      $data['payment_gateway']              =   config("settings.payment_gateway");
      $data['shipping_md']              =   config("settings.shipping_md");
      $data['return_flow']              =   config("settings.return_flow");
      $data['seller_panel']              =   config("settings.seller_panel");
      $data['cust_approval']              =   config("settings.cust_approval");
      $data['cust_credits']              =   config("settings.cust_credits");
      $data['cust_referral']              =   config("settings.cust_referral");
      $data['extra_fields']              =   config("settings.extra_fields");
      $data['brands']              =   config("settings.brands");
      $data['prod_return']              =   config("settings.prod_return");
      $data['refund']              =   config("settings.refund");
      $data['discount']              =   config("settings.discount");
      $data['rewards']              =   config("settings.rewards");
      $data['blog']              =   config("settings.blog");
      $data['support_ticket']              =   config("settings.support_ticket");
      $data['loyality_points']              =   config("settings.loyality_points");
      $data['branches']              =   config("settings.branches");
      $data['auction']              =   config("settings.auction");
      $data['crm_integration']              =   config("settings.crm_integration");

       return view('config',$data);

    }

        return view('lockscreen');
        
    }

    public function saveSet(Request $request)
    {
     $input = $request->all();

    $rules            =   [
    'app_name'                  =>  'required','base_url'   =>  'required',
    'm_lang'       =>  'required', 'm_currency'    =>  'required',
    'prod_type' => 'required','payment_gateway' => 'required','shipping_md' => 'required'
    ,'return_flow' => 'required'
    ];
    $validator              =   Validator::make($input['config'],$rules);

    if ($validator->fails()){
    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; }
    return redirect()->back()->withErrors($error);
    }  

     
     foreach ($input['config'] as $key => $value) {
        putConfig("settings","$key","$value");
     }

        return redirect(route('admin.config'));
    }

    public function unlockpwd(Request $request)
    {
     $input = $request->all();

    $rules            =   [
    'password'                  =>  'required'
    ];
    $validator              =   Validator::make($input,$rules);

    if ($validator->fails()){
    foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; }
    return redirect()->back()->withErrors($error);
    }  
      $password = DB::table('config')->first();
      if($password)
      {
        $password = $password->password;
     
        if(Hash::check($input['password'], $password)) {
          session(['password_entered' => true]);
        }else{
          
           return redirect()->back()->withErrors(['error'=>"Password doesnt match."]);
        }
      }else{
        
        return redirect()->back()->withErrors(['error'=>"Password not found."]);
      }
      

      return redirect(route('admin.config'));
    }

     public function clearSettings()
    {
       Artisan::call('config:cache');
        return redirect(route('admin.config'));
    }

    

}
