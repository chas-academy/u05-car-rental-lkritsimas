<?php

namespace CarRental\Core;

class Request
{
    private $path;
    private $method;
    private $data;

    public function __construct()
    {
        // $pathArray = explode(
        //     "?", 
        //     filter_var($_SERVER["REQUEST_URI"], FILTER_SANITIZE_URL)
        // );
        $pathArray = explode("?", $_SERVER["REQUEST_URI"]);
        $this->path = substr($pathArray[0], 1);
        $this->method = $_SERVER["REQUEST_METHOD"];
        $this->data = array_merge($_POST, $_GET);
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getData()
    {
        return $this->data;
    }
}
