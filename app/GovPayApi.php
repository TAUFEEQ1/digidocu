<?php

namespace App;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Exceptions\GovException;
use App\GovPay\CardPay;
use App\GovPay\GovBase;
use App\GovPay\MobilePay;
use BadMethodCallException;

class GovPayApi{

    private MobilePay|CardPay $payment;

    public string $payment_type;

    public function __construct($details)
    {
        if(array_key_exists("PAY_TYPE",$details) && $details["PAY_TYPE"]=="CARD"){
            $this->payment_type = "CARD";
            $this->payment = new CardPay;
        }else{
            $this->payment_type = "MOBILE";
            $this->payment = new MobilePay;
            if(array_key_exists("mobile_network",$details)){
                $this->payment->setDetails($details);
            }
        }
    }

    public function initialize(){
        return $this->payment->initialize();
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->payment, $name)) {
            return call_user_func_array([$this->payment, $name], $arguments);
        } else {
            throw new BadMethodCallException("Method $name does not exist");
        }
    }

}