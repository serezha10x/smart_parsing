<?php

namespace application\core;

use application\core\View;


abstract class Controller {

    public $route;
    public $view;
    public $acl;
    protected $model;


    public function __construct($route) {
        $this->route = $route;
        if (!$this->checkAcl()) {
            View::errorCode(403);
        }
        $this->model = $this->loadModel($route['controller']);
        if ($this->model != null) {
            $this->model->check_user($_COOKIE['login'], $_COOKIE['password']);
        }
        $this->view = new View($route);
    }

    public function loadModel($name) {
        $path = 'application\models\\'.ucfirst($name);
        if (class_exists($path)) {
            return new $path;
        }
    }

    public function checkAcl() {
        $this->acl = require 'application/acl/'.$this->route['controller'].'.php';
        if ($this->isAcl('all')) {
            return true;
        }
        elseif (isset($_COOKIE['login']) and isset($_COOKIE['isAuthorized']) and $this->isAcl('authorize')) {
            return true;
        }
        elseif (!isset($_COOKIE['login']) and !isset($_COOKIE['isAuthorized']) and $this->isAcl('guest')) {
            return true;
        }
        elseif (isset($_COOKIE['login']) and isset($_COOKIE['isAuthorized']) and $_COOKIE['isAdmin'] != 0 and $this->isAcl('admin')) {
            return true;
        }
        return false;
    }

    public function isAcl($key) {
        return in_array($this->route['action'], $this->acl[$key]);
    }
}