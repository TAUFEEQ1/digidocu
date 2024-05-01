<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Subscription;
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

        $this->subscription->update([
        ]);
    }
}
