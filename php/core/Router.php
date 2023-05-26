<?php
namespace app\core;

class Router {
    protected array $routes = [];
    protected $request;
    public function __construct($request) {
        $this->request = $request;
    }
    public function get(string $path, $callable) {
        $this->routes['get'][$path] = $callable;
    }
    public function resolve() {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $callable = $this->routes[$method][$path] ?? false;
        if (!$callable) {
            http_response_code(404);
            die("Not Found");
        }
        echo $callable();

    }

}

 ?>
