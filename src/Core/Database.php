<?php

namespace CarRental\Core;

use \PDO;
use \PDOException;

use CarRental\Core\Config;
use CarRental\Utils\Singleton;

class Database
{
  public $handler;

  public function __construct($config)
  {
    try {
      $this->handler = new PDO(
        $config['type'] . ':' .
          'host=' . $config['host'] .
          ';dbname=' . $config['database'],
        $config['user'],
        $config['password']
      );

      $pdoOptions = [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
      ];

      $this->handler->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return $e->getMessage();
    }
  }
}
