<?php


namespace application\controllers;

use application\core\Controller;
use application\parser\ParserRegex;

class ParserController extends Controller
{
    public function parseAction() {
        $parserRegex = new ParserRegex();
        $parserRegex->run();
    }
}