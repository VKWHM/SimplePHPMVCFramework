<?php
require_once '../vendor/autoload.php';
use app\core\Application;
use app\controllers\SiteController;
$app = Application::getInstance();
$app->router->get('/', "home");
$app->router->get('/contact', "contact");
$app->router->post('/contact', array(SiteController::class, 'contact'));
$app->run();
 ?>
