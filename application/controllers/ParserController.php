<?php


namespace application\controllers;

use application\core\Controller;
use application\parser\ParserDictionary;
use application\parser\ParserRegex;
use application\parser\PHPQueryParser;
use application\parser\Reader;
use application\wiki\WikipediaApi;


class ParserController extends Controller
{
    public function parseAction() {
/*
        echo '<pre>';

        $wikiApi = new WikipediaApi();
        var_dump($wikiApi->WikiClient("mysql"));
        exit();

        $phpquery = new PHPQueryParser('table', 'a', 'https://jeck.ru/tools/SynonymsDictionary/', 'корабль');
        $phpquery->ParseSynonym(5);
        exit();
*/

        $tmp = exec ("python text.py");
        echo "temp: $tmp";
        exit();
        function PY()
        {
            $p = func_get_args();
            $code = array_pop($p);
            if (count($p) % 2 == 1) return false;
            $precode = '';
            for ($i = 0; $i < count($p); $i += 2) $precode .= $p[$i] . " = json.loads('" . json_encode($p[$i + 1]) . "')\n";
            $pyt = tempnam('/tmp', 'pyt');
            file_put_contents($pyt, "import json\n" . $precode . $code);
            system("python {$pyt}");
            unlink($pyt);
        } //begin echo "This is PHP code\n"; $r=array('hovinko','ruka',6); $s=6; PY('r',$r,'s',$s,<<<ENDPYTHON print('This is python 3.4 code. Looks like included in PHP :)'); s=s+42 print(r,' : ',s) ENDPYTHON ); echo "This is PHP code again\n";


        $vars = ['login' => $_COOKIE['login']];

        if (isset($_COOKIE['upload_file'])) {
            $file_path = $_COOKIE['upload_file'];
            setcookie("upload_file", false, -1, "/");

            $reader = new Reader($file_path);
            $text = $reader->convertToText();

            $parserRegex = new ParserRegex($text);
            $parse_regex_text = $parserRegex->run();

            $parserDictionary = new ParserDictionary($text);
            $dict_parse_text = $parserDictionary->run();


            $vars += ['parse_regex_text' => $parse_regex_text];
            $vars += ['dict_parse_text' => $dict_parse_text];
            $vars += ['text' => $text];
            $this->view->render('Парсер', $vars);

        } else {
            $this->view->render('Парсер', $vars);
        }
    }
}