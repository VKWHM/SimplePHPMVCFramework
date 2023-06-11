<?php
require_once '../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

use app\core\Application;
use app\controllers\SiteController;
use app\controllers\AuthController;

$config = array(
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD']
    ]
);

$app = Application::getInstance(dirname(__DIR__), $config);
$app->router->get('/', "home");

$app->router->get('/contact', "contact");
$app->router->post('/contact', array(Controller::class, 'contact'));

$app->router->get('/login', array(AuthController::class, 'login'));
$app->router->post('/login', array(AuthController::class, 'login'));

$app->router->get('/register', array(AuthController::class, 'register'));
$app->router->post('/register', array(AuthController::class, 'register'));

$app->run();
 ?>
