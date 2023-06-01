<?php
namespace app\core;
class Application {
    private static $root_directory;
    private static $instance;
    public $router;
    public $request;
    public $response;
    protected $controller;
    public function getController() {
        return $this->controller ?? false;
    }
    public function setController($instance) {
        $this->controller = $instance;
    }
    public function __construct() {
        $this->response = new Response();
        $this->request = new Request();
        $this->router = new Router($this->request, $this->response);
    }
    public function run() {
        echo $this->router->resolve();
    }
    public static function getInstance() {
        if (!self::$instance) {
            self::$root_directory = dirname(__DIR__);
            self::$instance = new self();
        }
        return self::$instance;
    }
    public static function getRootDirectory() {
        return self::$root_directory;
    }
}
 ?>
