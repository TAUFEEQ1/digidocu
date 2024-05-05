<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Subscription;
use App\GovPayApi;
use Illuminate\Support\Facades\Log;

class PaymentProcessor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $subscription;
    /**
     * Create a new job instance.
     */
    public function __construct(Subscription $subscription)
    {
        //
        $this->subscription = $subscription;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Simulate the payment callback after 60 seconds

        $api = new GovPayApi([
            "mobile_network" => $this->subscription->sub_payment_mobile_network,
            "amount" => $this->subscription->sub_amount,
            "phone_no" => $this->subscription->sub_payment_mobile_no,
            "name" => $this->subscription->createdBy->name
        ]);
        $reference = $api->initialize();
        $this->subscription->sub_payment_ref= $reference;
        $this->subscription->save();
    }
}
