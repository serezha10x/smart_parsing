<?php

namespace application\core;

use PDO;

abstract class Model {

    protected $pdo;
	protected static $USERNANE = 'root';
	protected static $PASSWORD = '';
	protected static $HOST = '127.0.0.1';
	protected static $DBNAME = 'ParseProject';
	
	public function __construct() {
        try {
            $this->pdo = new PDO('mysql:host='.self::$HOST.';dbname='.self::$DBNAME, self::$USERNANE, self::$PASSWORD);
        } catch (\Exception $ex) {
            View::errorCode(500);
        }
    }

    public function __destruct() {
        $this->pdo = NULL;
    }
}