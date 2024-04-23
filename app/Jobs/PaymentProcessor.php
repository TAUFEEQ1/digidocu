<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Subscription;
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
        //
        sleep(60);
        $current_date = now();
        $this->subscription->update([
            "status"=>config("constants.SUB_STATUSES.ACTIVE"),
            "sub_payment_status"=>config("constants.SUB_PAY_STATES.COMPLETED"),
            "sub_start_date"=>$current_date,
            "sub_end_date"=>$current_date->addYear(1)
        ]);
        
    }
}
