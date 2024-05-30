<?php

namespace App\Jobs;

use App\Exceptions\GovException;
use App\GovPayApi;
use App\Payments\PublicationPayment as PaymentsPublicationPayment;
use App\PublicationBuyer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PublicationPayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $publication_buyer;
    /**
     * Create a new job instance.
     */
    public function __construct(PublicationBuyer $pub_buyer)
    {
        //
        $this->publication_buyer = $pub_buyer;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        $api = new GovPayApi(
            [
                "mobile_network" => $this->publication_buyer->mobile_network,
                "amount" => $this->publication_buyer->publication->pub_fees,
                "phone_no" => $this->publication_buyer->mobile_no,
                "name" => $this->publication_buyer->buyer->name
            ]
        );
        try{
            $reference = $api->initialize();
            $this->publication_buyer->payment_ref= $reference;
            $this->publication_buyer->save();
        }catch(GovException $e){
            $tx = ["status"=>"FAILED","notes"=>$e->getMessage()];
            $payment = new PaymentsPublicationPayment();
            $payment->setDocument($this->publication_buyer);
            $payment->setStatus($tx);
        }
    }
}
