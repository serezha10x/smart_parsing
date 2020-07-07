<?php

namespace application\models;

use application\config\Constants;
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
        $is_auth = $this->check_user($login, $password);
        if ($is_auth) {
            setcookie('isAuthorized', '1', time() + Constants::TIME_LIVE_COOKIES);
            return true;
        } else {
            unset($_COOKIE['login']);
            setcookie('login', null, -1, "/");
            unset($_COOKIE['password']);
            setcookie('password', null, -1, "/");
            setcookie('isAuthorized', '0', time() + Constants::TIME_LIVE_COOKIES);
            return false;
        }
    }


    public function __destruct() {
        parent::__destruct();
    }
}