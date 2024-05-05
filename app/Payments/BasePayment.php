<?php
namespace App\Payments;
use App\Document;

abstract class BasePayment {

    private Document $document;
    public function setDocument(Document $document){
        $this->document = $document;
    }
    
    public function setStatus(array $tx){

    }
}