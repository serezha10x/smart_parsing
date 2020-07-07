<?php

namespace application\config;

use phpMorphy;

require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

class MorphyConfig
{
    static public function setMorphy(string $lang) {
        $dir = $_SERVER['DOCUMENT_ROOT'] . "/vendor/cijic/phpmorphy/libs/phpmorphy/dicts";
        $lang = 'ru_RU';
        return new phpMorphy($dir, $lang);
    }
}