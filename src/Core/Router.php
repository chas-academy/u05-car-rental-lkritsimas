<?php
namespace CarRental\Core;

use \Exception;
use CarRental\Exceptions\HTTPException;
use CarRental\Utils\DependencyInjector;

class Router {
    
    private $di;
    private $routes;

    public function __construct(DependencyInjector $di) {
        $this->di = $di;
        $json = file_get_contents(__DIR__ . "/../../config/routes.json");
        $this->routes = json_decode($json, true);
    }

    public function route(Request $request) {
        $path = $request->getPath();
        $method = $request->getMethod();

        try {
            if (array_key_exists($path, $this->routes)) {
                $values = array_values($this->routes[$path]);
                return $this->callAction($request, ...$values);
            } else {
                foreach($this->routes as $route => $data) {
                    // $pattern = preg_replace("#\(/\)#", "/?", $route);
                    $pattern = "@^" . preg_replace("/{([a-zA-Z0-9\_\-]+)}/", "(?<$1>[a-zA-Z0-9\_\-]+)", $route) . "$@D";

                    preg_match($pattern, $path, $matches);
                    // Remove full match
                    array_shift($matches);    

                    // if ($this->match($route, $path, $params, &$map)) {
                    if ($matches) {
                        return $this->callAction($request, $data["controller"], $data["method"], $matches);
                    }
                }
            }

            // Route does not exist, throw 404
            throw new HTTPException("Page not found", 404);
        } catch (HTTPException $e) {
            return $this->di->get("Twig_Environment")->render("Error.html.twig", [
                "code" => $e->getCode(),
                "message" => $e->getMessage()
            ]);
        }
    }

    public function callAction($request, $controller, $action, $params = []) {
        try {
            $controller = "CarRental\\Controllers\\{$controller}";
            $controller = new $controller($this->di, $request);

            if (!method_exists($controller, $action)) {
                throw new Exception("{$controller} does not have the {$action} action");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        
        return $controller->$action($params);
    }
}
