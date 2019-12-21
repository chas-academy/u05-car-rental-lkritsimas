<?php
namespace CarRental\Exceptions;

use \Exception;

class HTTPException extends Exception {
    public function __construct($message, $code = 0) {
        if ($code !== 0) http_response_code($code);

        parent::__construct($message, $code);
    }
}
