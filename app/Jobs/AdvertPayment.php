<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Advert;
use App\Exceptions\GovException;
use App\GovPayApi;
use Illuminate\Support\Facades\Log;
use App\Payments\AdvertPayment as AdPayment;
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

            $api = new GovPayApi(
                [
                    "mobile_network" => $this->advert->ad_payment_mobile_network,
                    "amount" => $this->advert->ad_amount,
                    "phone_no" => $this->advert->ad_payment_mobile_no,
                    "name" => $this->advert->createdBy->name
                ]
            );
            try{
                $reference = $api->initialize();
                $this->advert->ad_payment_ref= $reference;
                $this->advert->save();
            }catch(GovException $e){
                $tx = ["status"=>"FAILED","notes"=>$e->getMessage()];
                $payment = new AdPayment();
                $payment->setDocument($this->advert);
                $payment->setStatus($tx);
            }
            
    }
    
}
