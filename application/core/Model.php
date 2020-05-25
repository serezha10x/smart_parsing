<?php

namespace application\core;

use PDO;

abstract class Model {

    protected $pdo;
	protected static $USERNAME = 'root';
	protected static $PASSWORD = '';
	protected static $HOST = '127.0.0.1';
	protected static $DBNAME = 'ParseProject';

    const USERS_TABLE = 'users';
    const PASSWORD_FIELD = 'password';
    const NAME_FIELD = 'name';
    const IS_ADMIN_FIELD = 'is_admin';


	public function __construct() {
        try {
            $this->pdo = new PDO('mysql:host='.self::$HOST.';dbname='.self::$DBNAME, self::$USERNAME, self::$PASSWORD);
        } catch (\Exception $ex) {
            View::errorCode(500);
        }
    }

    public function check_user($login, $password) : bool {
        try {
            if (empty($login) or empty($password)) {
                throw new Exception();
            }
            $sql = 'SELECT * FROM `' . self::$USERS_TABLE . '` WHERE ' . self::$NAME_FIELD . ' = :login';
            $stm = $this->pdo->prepare($sql, array(PDO::PARAM_STR));
            $stm->bindParam(':login', $login, PDO::PARAM_STR);
            $stm->execute();

            if ($stm->rowCount() == 0) {
                throw new Exception();
            } else {
                $row = $stm->fetch(PDO::FETCH_ASSOC);
                if (password_verify($password, $row[self::$PASSWORD_FIELD])) {
                    if ($row[self::$IS_ADMIN_FIELD] != 0) {
                        setcookie("isAdmin", "1", time() + 86400, "/");
                    } else {
                        setcookie("isAdmin", "0", time() + 86400, "/");
                    }
                    return true;
                } else {
                    throw new Exception("There is no account with these login and password...");
                }
            }

        } catch (Exception $ex) {
            View::errorCode(500);
            return false;
        }
        return false;
    }

    public function __destruct() {
        $this->pdo = NULL;
    }
}