<?php

namespace App;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Exceptions\GovException;

class GovPayApi{

    private array $details;
    private string $base_url;

    public function __construct($details)
    {
        $this->details = $details;
        $this->details["formatted"] = FALSE;
        $this->base_url = config("govnet.BASE_URL");
    }


    private function fmt_details(){
        //  prepare amount
        $this->details["amount"] = (int) $this->details["amount"];
        // prepare mobile phone
        $this->details["phone_no"] = "256".substr($this->details["phone_no"],1);
        // prepare mobile network
        $networks = config("govnet.NETWORKS");
        $this->details["mobile_network"] = $networks[$this->details["mobile_network"]];
        // 

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
          "transaction_method" => "MOBILE_MONEY", 
          "currency" => "UGX", 
          "amount" => $details["amount"]*0.01, 
          "provider_code" => $details["mobile_network"], 
          "msisdn" => $details["phone_no"], 
          "customer_name" => $this->details["name"], 
          "description" => "MM Collection", 
          "charge_customer" => config("govnet.CHARGE_CUSTOMER")
       ]; 
        
        
        $response = Http::withHeaders($this->getHeaders())->post($this->base_url."/initialize",$data);
        $jsonData = $response->json();
        Log::info($jsonData);
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

    public function confirm(string $reference){

        $data = [
            "internal_reference"=>$reference
        ];
        Http::withHeaders($this->getHeaders())->post($this->base_url."/confirm",$data);
    }
}