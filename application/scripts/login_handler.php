<?php

require_once $_SERVER['DOCUMENT_ROOT']."/application/core/View.php";

use application\core\View;


if (isset($_POST['login']) && isset($_POST['password'])) {
    $_SESSION['authorize']['password'] = $_POST['password'];
    $_SESSION['authorize']['login'] = $_POST['login'];
    View::redirect('http://nirs.com');
}
