<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/application/config/Constants.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/application/core/Model.php';

use application\config\Constants;
use application\core\Model;


const logInTime = Constants::TIME_LIVE_COOKIES;
const logOutTime = -1;


function authentication(int $time) {
    setcookie('login', $_POST['login'], time() + $time, "/");
    setcookie('password', $_POST['password'], time() + $time, "/");
    header('Location: http://nirs.com');
    exit;
}


if (isset($_POST['submit'])) {
    if (isset($_POST['login']) and isset($_POST['password'])) {
        authentication(logInTime);
    }
}
else if (isset($_POST['logout'])) {
    if (isset($_COOKIE[Model::IS_ADMIN_FIELD])) {
        setcookie(Model::IS_ADMIN_FIELD, $_COOKIE[Model::IS_ADMIN_FIELD], time() + logOutTime, "/");
    }
    authentication(logOutTime);
}
