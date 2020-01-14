<?php
require_once __DIR__ . '/vendor/autoload.php';

use CarRental\Core\Config;
use CarRental\Core\Database;
use CarRental\Core\Router;
use CarRental\Core\Request;
use CarRental\Utils\DependencyInjector;

$config = new Config();
$dbConfig = $config->get('database');

$db = new Database($dbConfig);
$db = $db->handler;

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/src/Views');
$view = new \Twig\Environment($loader);

$di = new DependencyInjector();
$di->set('PDO', $db);
$di->set('Utils\Config', $config);
$di->set('Twig_Environment', $view);

$router = new Router($di);
$response = $router->route(new Request());
echo $response;
