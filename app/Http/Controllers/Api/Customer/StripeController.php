<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Session;
use Stripe;
use Carbon\Carbon;
use App\Rules\Name;
use Validator;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

use App\Models\CustomerInfo;

class StripeController extends Controller
{
     public function stripeKeys(Request $request)
    {
        if($user = validateToken($request->post('access_token')))
        {       
                 $user_id    =   $user['user_id'];
                 $formData   =   $request->all(); 
        
                \Stripe\Stripe::setApiKey(\Config::get('services.stripe.secret'));
                // Use an existing Customer ID if this is a returning customer.
                // $customer_id = $formData['customer_id'];
                $customer_id = CustomerInfo::where('user_id',$user_id)->first()->stripe_id; 
                
                if ($customer_id != "") {
                    
                    $customer = \Stripe\Customer::retrieve($customer_id);
                
                
                } else {
                
                $customer = \Stripe\Customer::create(['email'    => $user['email'],'phone'    => $user['phone'],'name'    => $user['first_name'],"metadata" => ["user_id" => $user_id]]);
                
                CustomerInfo::where('user_id',$user_id)->where('is_deleted',0)->where('is_active',1)->update([
                           'stripe_id' => $customer->id,
                           
                           ]);
                }
                
                if(isset($formData['order_id'])) {
                   $order_id = $formData['order_id'];
                }else {
                    $order_id = '';
                }
                
                if(isset($formData['req_type'])) {
                   $req_type = $formData['req_type']; //credit if user credit payment otherwise sale
                }else {
                    $req_type = 'sale';
                }

                
                $ephemeralKey = \Stripe\EphemeralKey::create(
                    ['customer' => $customer->id],[
                    'stripe_version' => '2020-08-27'
                  ]);
                $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $formData['amount'],
                'currency' => $formData['currency'],
                'customer' => $customer->id,
                'metadata' => [
                'order_id' => $order_id,
                'req_type' => $req_type,
                'user_id' => $user_id,
                'env' => \Config::get('services.stripe.env'),
                ],
                'payment_method_types' => ['card']]);

                // return $response->withJson([
                // 'paymentIntent' => $paymentIntent->client_secret,
                // 'ephemeralKey' => $ephemeralKey->secret,
                // 'customer' => $customer->id ,
                // 'publishableKey' => env('STRIPE_KEY')
                // ])->withStatus(200);
                
                return array('httpcode'=>'200','status'=>'success','paymentIntent'=>$paymentIntent->client_secret,'ephemeralKey'=>$ephemeralKey->secret,'customer'=>$customer->id,'publishableKey'=>\Config::get('services.stripe.key'));
                
        }else{ return invalidToken(); }
    }
   
}
