<?php
namespace app\core;

class Router {
    protected array $routes = [];
    protected $request;
    protected $response;
    public function __construct($request, $response) {
        $this->request = $request;
        $this->response = $response;
    }
    public function get(string $path, $callable) {
        $this->routes['get'][$path] = $callable;
    }
    public function post(string $path, $callable) {
        $this->routes['post'][$path] = $callable;
    }
    public function resolve() {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $callable = $this->routes[$method][$path] ?? false;
        if (!$callable) {
            $this->response->statusCode(404);
            return $this->renderView('404');
        }
        if (is_string($callable)) {
            return $this->renderView($callable);
        } else {
            return $callable();
        }
    }
    public function renderView($view) {
        $layoutContent = $this->layoutContent();
        $viewContent = $this->renderViewOnly($view);
        return str_replace("{{put_content}}", $viewContent, $layoutContent);
    }
    public function renderViewOnly($view) {
        ob_start();
        include_once Application::getRootDirectory() . "/views/$view.php";
        return ob_get_clean();
    }
    public function layoutContent() {
        ob_start();
        include_once Application::getRootDirectory() . "/views/layouts/base.php";
        return ob_get_clean();
    }

}

 ?>
