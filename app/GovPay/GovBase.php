<?php

namespace App\GovPay;


abstract class GovBase{
    private array $details;
    public string $base_url;

    abstract public function setDetails(array $details);
    abstract public function getDetails();
    abstract public function getPayload();

    abstract public function initialize();

    public function __construct() {
        $this->base_url = config("govnet.BASE_URL");
    }

    public function getHeaders(){
        $headers = [
            "public-key" => env("GOV_PUBLIC_KEY"),
            "x-api-version" => '1'
        ];
        return $headers;       
    }
    
}