<?php


namespace application\controllers;

use application\core\Controller;
use application\parser\ParserDictionary;
use application\parser\ParserRegex;
use application\parser\Reader;

class ParserController extends Controller
{
    public function parseAction() {
        $reader = new Reader($_SERVER['DOCUMENT_ROOT']."/docs/mydoc.docx");
        $text = $reader->convertToText();

        $parserRegex = new ParserRegex($text);
        $parserRegex->run();

        $parserDictionaty = new ParserDictionary($text);
        $parserDictionaty->run();
    }
}