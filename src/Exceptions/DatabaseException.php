<?php
namespace CarRental\Exceptions;

use \Exception;

class DatabaseException extends Exception {
    public function __construct($message, $code = 403) {
        if ($code !== 0) http_response_code($code);

        parent::__construct($message, $code);
    }
}
