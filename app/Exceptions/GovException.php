<?php
namespace App\Exceptions;

use Exception;
use Throwable;

class GovException extends Exception{



    public function __construct($message="Unknown Error") {
        // Ensure everything is assigned properly
        parent::__construct($message, 0, null);
    }

    // Custom string representation of object
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
