<?php

namespace application\controllers;

use application\core\Controller;
use application\core\Router;
use application\models\AccountModel;

class AccountController extends Controller {

    public function __construct($route) {
        parent::__construct($route);
    }

	public function loginAction() {
		$this->view->render('Вход');
	}

	public function registerAction() {
		$this->view->render('Регистрация');
	}

	public static function exitAction() {
        if (isset($_COOKIE['login']) || isset($_COOKIE['password'])) {
            setcookie('login', false, -1, '/');
            setcookie('password', false, -1, '/');
            Router::redirect("../");
        }
    }
}