<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\StripePayments;

use Spatie\WebhookClient\Models\WebhookCall;

class Payment_intentSucceeded implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   /** @var \Spatie\WebhookClient\Models\WebhookCall */
    public $webhookCall;

    public function __construct(WebhookCall $webhookCall)
    {
        $this->webhookCall = $webhookCall;
    }

    public function handle()
    {
        // do your work here


        $customer = $payload['data']['object']['customer'];
        
     
        $stripe_p = [];
        $stripe_p['sale_id'] = 10;
        $stripe_p['response'] = json_encode($payload['data']);
        
        
        $updates=StripePayments::create($stripe_p);
                
        // you can access the payload of the webhook call with `$this->webhookCall->payload`
    }
}
