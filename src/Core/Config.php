<?php
namespace CarRental\Core;

use CarRental\Utils\Singleton;
use CarRental\Exceptions\NotFoundException;

class Config extends Singleton {
    // Config data is stored here
    private $data;

    protected function __construct() {
        // Load config file and decode
        $json = file_get_contents(__DIR__ . "/../../config/app.json");
        $this->data = json_decode($json, true);
    }

    public function get($key) {
        // Check if key exists in config, if not, throw error
        if (!isset($this->data[$key])) {
            throw (new NotFoundException("Key $key was not found in config."));
        }
        
        return $this->data[$key];
    }
}
