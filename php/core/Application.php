<?php
namespace app\core;
class Application {
    private static $ROOT_DIR;
    public $router;
    public $request;
    public $response;
    public function __construct($root_directory) {
        self::$ROOT_DIR = $root_directory;
        $this->response = new Response();
        $this->request = new Request();
        $this->router = new Router($this->request, $this->response);
    }
    public function run() {
        echo $this->router->resolve();
    }
    public static function getRootDirectory() {
        return self::$ROOT_DIR;
    }
}
 ?>
