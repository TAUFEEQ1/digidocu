<?php
namespace App\GovPay;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\GovException;

class CardPay extends GovBase{
    private array $details;
    private string $payment_url;
    public function setDetails(array $details)
    {
        $this->details['amount'] = (int) $details["amount"];
        $this->details['email'] = $details["email"];
        $this->details['name'] = $details["name"];
        $this->details['redirect_url'] = $details["redirect_url"];
    }

    public function getPaymentUrl(){
        return $this->payment_url;
    }

    public function getDetails()
    {
        return $this->details;
    }

    public function getPayload()
    {
        $details = $this->getDetails();
        $data = [
            "charge_customer" => true, 
            "merchant_reference" => "auto", 
            "transaction_method" => "CARD", 
            "currency" => "UGX", 
            "amount" => $details["amount"], 
            "provider_code" => "card_ug", 
            "customer_email" => $details["email"], 
            "customer_name" => $details["name"], 
            "description" => "Card Collection", 
            "redirect_url" => $details["redirect_url"], 
            "require_confirmation" => false 
        ];
        return $data;
    }

    public function initialize()
    {
        $response = Http::withHeaders($this->getHeaders())->post($this->base_url."/initialize",$this->getPayload());
        $jsonData = $response->json();  
        // use a validator.
        $validator = Validator::make($jsonData,[
            "payload"=>"required",
            "payload.internal_reference"=>"required",
        ]);
        if($validator->passes()){
            $this->payment_url =  $jsonData["payload"]["payment_url"];
            return $jsonData["payload"]["internal_reference"];
        }
        if(array_key_exists('message',$jsonData)){
            throw new GovException($jsonData['message']);
        }
        return null;
    }
}