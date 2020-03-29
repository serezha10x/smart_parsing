<?php


namespace application\parser;


class ParserRegex
{
    private $source_format;
    private $permit_formats;
    private $source;
    private $ukr_alph = array('с', 'а', 'в', 'к', 'о', 'в', 'е');
    private $rus_alph = array('с', 'а', 'в', 'к', 'о', 'в', 'е');


    private function GetSourceFromForm(): string
    {
        return $_SERVER['DOCUMENT_ROOT']."/docs/mydoc.docx";
    }

    public function run()
    {
        $reader = new Reader($this->GetSourceFromForm());
        $text = $reader->convertToText();
        $patterns = require __DIR__ . "/regex_patterns.php";

        echo $text;

        foreach ($patterns as $pattern) {
            preg_match_all($pattern, $text, $matches);
            $matches[0] = array_unique($matches[0]);
            echo "<pre>";
            var_dump($matches[0]);
        }
    }
}