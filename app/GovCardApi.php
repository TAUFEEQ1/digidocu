<?php

namespace App;

use App\Exceptions\GovException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GovCardApi{

    private array $details;
    private string $base_url;

    public function __construct($details)
    {
        $this->details = $details;
        $this->details["formatted"] = FALSE;
        $this->base_url = config("govnet.BASE_URL");
    }

    public function fmt_details(){
        $this->details["amount"] = (int) $this->details["amount"];
        $this->details["merchant_reference"] = 'auto';

    }

    public function getDetails(){
        if(!$this->details["formatted"]){
            $this->fmt_details();
        }
        return $this->details;
    }
    private function getHeaders(){
        $headers = [
            "public-key" => env("GOV_PUBLIC_KEY"),
            "x-api-version" => '1'
        ];
        return $headers;       
    }
    public function initialize(){

        $details = $this->getDetails();
        $data = [
          "merchant_reference" => "auto", 
          "transaction_method" => "CARD", 
          "currency" => "UGX", 
          "amount" => $details["amount"], 
          "provider_code" => "card_ug", 
          "customer_name" => $this->details["name"], 
          "description" => "Card Collection", 
          "charge_customer" => config("govnet.CHARGE_CUSTOMER")
       ]; 
        
        
        $response = Http::withHeaders($this->getHeaders())->post($this->base_url."/initialize",$data);
        $jsonData = $response->json();
        if(array_key_exists('internal_reference',$jsonData["data"])){
            $reference = $jsonData['data']['internal_reference'];
            return $reference;
        }else{
            
            if(array_key_exists('message',$jsonData)){
                throw new GovException($jsonData['message']);
            }
            throw new GovException();
        }
    }

}