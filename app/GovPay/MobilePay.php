<?php
namespace App\GovPay;
use App\Exceptions\GovException;
use Illuminate\Support\Facades\Http;

class MobilePay extends GovBase{
    private array $details;

    public function setDetails(array $details)
    {
        $this->details["amount"] = (int) $details["amount"];
        // prepare mobile phone
        $this->details["phone_no"] = "256".substr($details["phone_no"],1);
        // prepare mobile network
        $networks = config("govnet.NETWORKS");
        $this->details["mobile_network"] = $networks[$details["mobile_network"]];  
        $this->details["name"] = $details["name"];
    }
    public function getDetails()
    {
        return $this->details;   
    }

    public function getPayload()
    {
        $details = $this->getDetails();
        $data = [
            "merchant_reference" => "auto", 
            "transaction_method" => "MOBILE_MONEY", 
            "currency" => "UGX", 
            "amount" => $details["amount"], 
            "provider_code" => $details["mobile_network"], 
            "msisdn" => $details["phone_no"], 
            "customer_name" => $details["name"], 
            "description" => "MM Collection", 
            "charge_customer" => config("govnet.CHARGE_CUSTOMER")
        ];
        return $data;
    }

    public function initialize()
    {
        $response = Http::withHeaders($this->getHeaders())->post($this->base_url."/initialize",$this->getPayload());
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
    public function confirm(string $reference){
        $data = [
            "internal_reference"=>$reference
        ];
        Http::withHeaders($this->getHeaders())->post($this->base_url."/confirm",$data);
    }
}