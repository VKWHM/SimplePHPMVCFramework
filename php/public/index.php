<?php
require_once '../vendor/autoload.php';
use app\core\Application;
use app\controllers\SiteController;
use app\controllers\AuthController;
$app = Application::getInstance();
$app->router->get('/', "home");

$app->router->get('/contact', "contact");
$app->router->post('/contact', array(Controller::class, 'contact'));

$app->router->get('/login', array(AuthController::class, 'login'));
$app->router->post('/login', array(AuthController::class, 'login'));

$app->router->get('/register', array(AuthController::class, 'register'));
$app->router->post('/register', array(AuthController::class, 'register'));

$app->run();
 ?>
