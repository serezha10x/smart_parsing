<?php

namespace application\controllers;

use application\core\Controller;

class MainController extends Controller {

	public function indexAction() {
	    $vars = ['login' => $_COOKIE['login']];
		$this->view->render('Главная страница', $vars);
	}
}