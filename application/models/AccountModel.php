<?php

namespace application\models;

use application\core\Model;
use application\core\View;
use Exception;
use PDO;


class AccountModel extends Model
{
    public function __construct() {
        parent::__construct();
    }

    public function Login($login, $password) : bool {
        if($this->check_user($login, $password)) {
            setcookie('isAuthorized', '1', time() + 86400 * 30);
            return true;
        } else {
            unset($_COOKIE['login']);
            setcookie('login', null, -1, "/");
            unset($_COOKIE['password']);
            setcookie('password', null, -1, "/");
            setcookie('isAuthorized', '0', time() + 86400 * 30);
            return false;
        }
    }


    public function __destruct() {
        parent::__destruct();
    }
}