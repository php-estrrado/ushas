<?php

namespace App\Http\Controllers\Seller\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\SellerInfo;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
         $this->middleware('guest')->except('logout');
        $this->middleware('guest:seller')->except('logout');
    }
    public function showSellerLoginForm(){ 
        return view('seller.auth.login');
    }

    public function sellerLogin(Request $request)
    {
        // dd($request->all());


        $validator = Validator::make($request->all(), [
        'email'   => 'required|email',
        'password' => 'required|min:6'
        ]);



 
        if (Auth::guard('seller')->attempt(['username' => $request->email, 'password' => $request->password])) {
            
            
            if(SellerInfo::where('seller_id', Auth::guard('seller')->user()->id)->first()) {
                if(SellerInfo::where('seller_id', Auth::guard('seller')->user()->id)->first()->is_approved == 1) {
                 
                    return redirect()->intended('/dashboard');
                }else {
 
                    Auth::guard('seller')->logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect('/login')->withInput($request->only('email', 'remember'))->with('message',' The seller is not approved yet. ');

                }
            }else{
                return back()->withInput($request->only('email', 'remember'))->with('message',' These credentials do not match our records. ');
            }
            
        }
        return back()->withInput($request->only('email', 'remember'))->with('message',' These credentials do not match our records. ');
    }
    
   
    
}
