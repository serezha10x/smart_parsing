<?php

namespace application\controllers;

use application\core\Controller;
use application\models\AccountModel;

class AccountController extends Controller {

    public function __construct($route) {
        parent::__construct($route);
    }

    public function signIn($login, $password) {
        $this->model->Login($login, $password);
    }

	public function loginAction() {
		$this->view->render('Вход');
	}

	public function registerAction() {
		$this->view->render('Регистрация');
	}
}