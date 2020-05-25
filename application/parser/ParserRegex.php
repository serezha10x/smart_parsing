<?php


namespace application\parser;


class ParserRegex
{
    private $text;
    private $info;
    private $ukr_alph = array('с', 'а', 'в', 'к', 'о', 'в', 'е');
    private $rus_alph = array('с', 'а', 'в', 'к', 'о', 'в', 'е');


    public function __construct(&$text) {
        $this->text = $text;
        $this->info = array();
    }


    public function run() {
        $patterns = require __DIR__ . "/regex_patterns.php";
        $parse_text = "<br>";

        foreach ($patterns as $pattern_name => $pattern) {
            $parse_text .= "<br>" . $pattern_name . ": ";
            preg_match_all($pattern, $this->text, $matches);
            $this->info[$pattern_name] = array_unique($matches[0]);
            $size = count($this->info[$pattern_name]);
            for ($i = 0; $i < $size; $i++) {
                if ($i == $size - 1) $parse_text .= $this->info[$pattern_name][$i] . ".<br>";
                else $parse_text .= $this->info[$pattern_name][$i] . ", ";
            }
        }

        return $parse_text;
    }
}