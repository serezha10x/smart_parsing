<?php


namespace application\parser;


final class ParserRegex extends Parser
{
    protected $text;


    public function __construct(&$text) {
        parent::__construct($text);
    }


    public function parse() {
        $patterns = require __DIR__ . "/parser_config/regex_patterns.php";
        $parse_text = "<br>";
        $info = array();

        foreach ($patterns as $pattern_name => $pattern) {
            $parse_text .= "<br>" . $pattern_name . ": ";
            preg_match_all($pattern, $this->text, $matches);
            $info[$pattern_name] = array_unique($matches[0]);
            $size = count($info[$pattern_name]);
            for ($i = 0; $i < $size; $i++) {
                if ($info[$pattern_name][$i] != '' and strlen($info[$pattern_name][$i]) > 1) {
                    if ($i == $size - 1) {
                        $parse_text .= $info[$pattern_name][$i] . ".<br>";
                    } else {
                        $parse_text .= $info[$pattern_name][$i] . ", ";
                    }
                }
            }
        }

        return $parse_text;
    }
}