<?php


namespace application\parser;


class ParserWordsCase extends Parser
{
    protected $text;


    public function __construct(&$text) {
        parent::__construct($text);
    }


    public function parse()
    {
        $tokens = tokenize($this->text, \TextAnalysis\Tokenizers\GeneralTokenizer::class);

        $theme = '';
        $isPrevUpper = false;
        $isCurUpper = false;

        foreach ($tokens as $token) {
            if ($this->my_ctype_upper($token)) {
                $isCurUpper = true;
            } else {
                if ($isPrevUpper) {
                    break;
                } else {
                    continue;
                }
            }
            if ($isPrevUpper && $isCurUpper) {
                $theme .= ($token . ' ');
            }
            $isPrevUpper = $isCurUpper;
        }

        return 'Тема: ' . $theme;
    }


    private function my_ctype_upper(string $str) : bool {
        if ($) {

        }
        if ($str === strtoupper($str)) return true;
        else return false;
    }
}