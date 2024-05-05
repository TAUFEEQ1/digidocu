<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Advert;
use App\GovPayApi;
use Illuminate\Support\Facades\Log;

class AdvertPayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $advert;
    /**
     * Create a new job instance.
     */
    public function __construct(Advert $advert)
    {
        //
        $this->advert = $advert;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {

            $api = new GovPayApi(
                [
                    "mobile_network" => $this->advert->ad_payment_mobile_network,
                    "amount" => $this->advert->ad_amount,
                    "phone_no" => $this->advert->ad_payment_mobile_no,
                    "name" => $this->advert->createdBy->name
                ]
            );
            
            // Update the advert status and payment time
            $this->advert->update([
                "status" => config("constants.ADVERT_STATES.PAID"),
                "ad_paid_at" => $current_date
            ]);
            
            // Log success message
            Log::info('Advert status updated successfully.', ['advert_id' => $this->advert->id]);
        } catch (\Exception $e) {
            // Log any errors that occurred
            Log::error('Error updating advert status: ' . $e->getMessage(), ['advert_id' => $this->advert->id]);
        }
    }
    
}
