<?php

namespace App\Http\Controllers\Admin;
namespace App\Http\Controllers\Api\Customer;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Cashier\Events\WebhookReceived;

class StripeWebhookController extends CashierController
{
    /**
     * Handle customer subscription updated.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
	 
	 
    protected function handleCheckoutSessionCompleted(array $payload)
    {
        $customer = $payload['data']['object']['customer'];

        echo "<pre>";
        print_r($customer);
        dd("Checkout");

        return new Response('Webhook Handled', 200);
    }
    
      protected function handlePayment_intentSucceeded(array $payload)
    {
        $customer = $payload['data']['object']['customer'];

        echo "<pre>";
        print_r($customer);
        dd("PAYMENT");

        return new Response('Webhook Handled', 200);
    }

    /**
 * Handle a Stripe webhook call.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Symfony\Component\HttpFoundation\Response
 */

public function handleWebhook(Request $request)
{
    $payload = json_decode($request->getContent(), true);
    $method = 'handle'.Str::studly(str_replace('.', '_', $payload['type']));

    WebhookReceived::dispatch($payload);

    if (method_exists($this, $method)) {
        $response = $this->{$method}($payload);

        WebhookHandled::dispatch($payload);

        return $response;
    }

    return $this->missingMethod($payload);
}

}