<?php

use application\core\Router;
use application\models\AccountModel;

spl_autoload_register(function($class) {
    $path = str_replace('\\', '/', $class.'.php');
    if (file_exists($path)) {
        require $path;
    }
});

session_start();

if (!isset($_SESSION['authorize']['login']) && !isset($_SESSION['authorize']['password'])) {
    $model = new AccountModel();
    if ($model->Login($_SESSION['authorize']['login'], $_SESSION['authorize']['password'])) {

    }
}

$router = new Router();
$router->run();