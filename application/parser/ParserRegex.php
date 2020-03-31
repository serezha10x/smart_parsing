<?php


namespace application\parser;


class ParserRegex
{
    private $text;
    private $ukr_alph = array('с', 'а', 'в', 'к', 'о', 'в', 'е');
    private $rus_alph = array('с', 'а', 'в', 'к', 'о', 'в', 'е');


    public function __construct(&$text) {
        $this->text = $text;
    }

    private function GetSourceFromForm(): string
    {
        return $_SERVER['DOCUMENT_ROOT']."/docs/mydoc.docx";
    }

    public function run()
    {
        $patterns = require __DIR__ . "/regex_patterns.php";

        echo $this->text;

        foreach ($patterns as $pattern) {
            preg_match_all($pattern, $this->text, $matches);
            $matches[0] = array_unique($matches[0]);
            echo "<pre>";
            var_dump($matches[0]);
        }
    }
}