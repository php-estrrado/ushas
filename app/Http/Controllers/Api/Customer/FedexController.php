<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Rules\Name;
use Validator;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Http;

class FedexController extends Controller
{
    public function fedexAuth()
    {
        $api_url = \Config::get('services.fedex.url');
        $api_key = \Config::get('services.fedex.key');
        $api_secret = \Config::get('services.fedex.secret');
        $input_arr = array('grant_type'=>'client_credentials','client_id'=>$api_key,'client_secret'=>$api_secret);
        $input =  http_build_query($input_arr);
       
        // $request = new Http();
        // $request->setUrl('https://apis-sandbox.fedex.com/oauth/token');
        // $request->setMethod(HTTP_METH_POST);

        // $request->setHeaders(array(
        // 'Content-Type' => 'application/x-www-form-urlencoded'
        // ));

        // $request->setBody($input); 

        // try {
        // $response = $request->send();

        // echo $response->getBody();
        // } catch (HttpException $ex) {
        // echo $ex;
        // }


        $url = $api_url.'/oauth/token';
        $method = 'POST';
        $headers = array(
        "content-type: application/x-www-form-urlencoded"
        );


        $curl = curl_init();
        
        curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_URL => $url,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POSTFIELDS => $input
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
        echo $response;
        }

    }
   
}
