<?php

namespace CarRental\Controllers;

use CarRental\Core\Request;
use CarRental\Utils\DependencyInjector;

abstract class AbstractController
{
    protected $view;
    protected $di;
    protected $request;
    protected $db;
    protected $config;

    public function __construct(DependencyInjector $di, Request $request)
    {
        $this->request = $request;
        $this->di = $di;
        $this->db = $di->get("PDO");
        $this->view = $di->get("Twig_Environment");
        $this->config = $di->get('Utils\Config');
    }

    protected function render(string $template, array $params): string
    {
        return $this->view->render($template, $params);
    }
}
