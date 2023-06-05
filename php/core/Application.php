<?php
namespace app\core;
class Application {
    private static $root_directory;
    private static $instance;
    public $router;
    public $request;
    public $response;
    public $db;
    protected $controller;
    public function getController() {
        return $this->controller ?? false;
    }
    public function setController($instance) {
        $this->controller = $instance;
    }
    public function __construct(array $config) {
        $this->response = new Response();
        $this->request = new Request();
        $this->router = new Router($this->request, $this->response);
        $this->db = new Database($config['db']);
    }
    public function run() {
        echo $this->router->resolve();
    }
    public static function getInstance(...$params) {
        if (!self::$instance) {
            self::$root_directory = dirname(__DIR__);
            self::$instance = new self(...$params);
        }
        return self::$instance;
    }
    public static function getRootDirectory() {
        return self::$root_directory;
    }
}
 ?>
