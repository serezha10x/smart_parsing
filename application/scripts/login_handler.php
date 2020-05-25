<?php

if (isset($_POST['submit'])) {
    if (isset($_POST['login']) && isset($_POST['password'])) {
        setcookie('login', $_POST['login'], time() + (86400 * 30), "/");
        setcookie('password', password_hash($_POST['password'], PASSWORD_DEFAULT), time() + (86400 * 30), "/");
        header('Location: http://nirs.com');
        exit;
    }
}
