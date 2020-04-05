<?php

namespace application\models;

use application\core\Model;
use application\core\View;
use Exception;
use PDO;


class AccountModel extends Model
{
    private static $USERS_TABLE = 'users';
    private static $PASSWORD_FIELD = 'password';
    private static $NAME_FIELD = 'name';
    private static $IS_ADMIN_FIELD = 'is_admin';


    public function __construct() {
        parent::__construct();
    }

    public function Login($login, $password) : bool {
        try {
            $sql = 'SELECT * FROM `' . self::$USERS_TABLE . '` WHERE ' . self::$NAME_FIELD . ' = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->bindValue(1, $login, PDO::PARAM_STR);
            $stm->execute();

            if ($stm->rowCount() == 0) {
                //throw new Exception();
            } else {
                $row = $stm->fetch(PDO::FETCH_ASSOC);
                if (password_verify($password, $row[self::$PASSWORD_FIELD])) {
                    $_SESSION['authorize']['login'] = $login;
                    $_SESSION['authorize']['verify'] = 1;

                    if ($row[self::$IS_ADMIN_FIELD] != 0) {
                        $_SESSION['authorize']['admin'] = 1;
                    } else {
                        $_SESSION['authorize']['admin'] = 0;
                    }
                } else {
                    throw new Exception();
                }
            }

        } catch (Exception $ex) {
           // View::errorCode(500);
            return false;
        }

        return true;
    }


    public function __destruct() {
        parent::__destruct();
    }
}