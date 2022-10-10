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


use App\Rules\Name;
use Validator;
use App\Models\MetalRates;
use App\Models\Currency;

class MetalratesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth:seller');
    }
   
    
        public function fetchRates()
        { 
            
            $endpoint = 'latest';
            $access_key =  config('services.metalrates.METALS_KEY');
            
            //default currency
            $base = "AED";
            $d_currency = Currency::where('is_default',1)->where('is_deleted',0)->first();
            if($d_currency){
             $base = $d_currency->currency_code;   
            }
            

            $api_attribs_map = array(
            
            "gold"      =>  "XAU",
            "silver"    =>  "XAG",
            "platinum"  =>  "XPT",
            "palladium" =>  "XPD",
            "rhodium"   =>  "XRH",
            "ruthenium"   =>  "RUTH"
            
            );

            // Initialize CURL:
            $metals_api_uri = "https://metals-api.com/api/$endpoint?access_key=$access_key&base=".$base."";
            $ch = curl_init($metals_api_uri);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Store the data:
            $metal_rates = curl_exec($ch);
            curl_close($ch);

            // $metal_rates = json_decode($metal_rates, true);
            //  dd($metal_rates);
            // gold carat rates
             $endpoint = 'carat';
             
                         // Initialize CURL:
            $metals_api_uri = "https://metals-api.com/api/$endpoint?access_key=$access_key&base=".$base."&symbols=XAU,XAG,XPT,XPD,XRH,RUTH&utm=zactonz";
            $ch = curl_init($metals_api_uri);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Store the data:
            $carat_rates = curl_exec($ch);
            curl_close($ch);

            // Decode JSON response:
            $carat_rates_dec = json_decode($carat_rates, true);
            $metal_rates_dec = json_decode($metal_rates, true);
            if($carat_rates_dec && $metal_rates_dec ){
               
                if($carat_rates_dec['success'] == true && $metal_rates_dec['success'] == true) {
                    $metal_arr= [];
            $metal_arr['metal_rates'] = $metal_rates;
            $metal_arr['carat_rates'] = $carat_rates;
             MetalRates::create($metal_arr);
             
                }else {
                  
                }
            }
            
            
            
      
        }

   
}
