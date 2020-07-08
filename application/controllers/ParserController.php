<?php


namespace application\controllers;

use application\core\Controller;
use application\parser\ParserDictionary;
use application\parser\ParserRegex;
use application\parser\ParserTextAnalysis;
use application\parser\ParserWordsCase;
use application\parser\SemanticParser;
use application\reader\ReaderCreator;
use application\semantic\WikiSemantic;
use application\semantic\WordNetSemantic;


require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");


class ParserController extends Controller
{
    public function parseAction() {
        $vars = ['login' => $_COOKIE['login']];

        if (isset($_COOKIE['upload_file'])) {
            // получаем название загружаемого файла
            $file_path = $_COOKIE['upload_file'];
            setcookie("upload_file", false, -1, "/");

            $parsers = [];

            $file_ext = pathinfo($file_path)['extension'];
            $reader_creator = new ReaderCreator;
            $reader = $reader_creator->factory($file_ext);
            // читаем текст
            $text = $reader->read($file_path);

            $parsers['parse_regex']      = new ParserRegex($text);
            //$parsers['parse_words_case'] = new ParserWordsCase($text);
            $parsers['dict_parse']     = new ParserDictionary($text);
            $parsers['php_analysis']     = new ParserTextAnalysis($text);
            $parsers['semantic_wordnet'] = new SemanticParser($text, 'IT', new WikiSemantic());
            // Для WordNet
            //$parsers['semantic_wordnet'] = new SemanticParser($text, 'IT', new WordNetSemantic());

            foreach ($parsers as $key => $val) {
                $vars += [$key => $val->parse()];
            }

            $vars += ['text' => $text];
            $this->view->render('Парсер', $vars);

        } else {
            $this->view->render('Парсер', $vars);
        }
    }
}