<?php


namespace application\semantic;

use application\parser\PHPQueryParser;
use application\wiki\WikipediaApi;
use application\wordnet\WordNetApi;

require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");


class WikiSemantic implements ISemanticParsable
{
    const TERMS_TAG = 'div.mw-parser-output ol li';

    private $parser;
    private $wikiApi;


    public function __construct()
    {
        $this->parser  = new PHPQueryParser();
        $this->wikiApi = new WikipediaApi();
    }


    public function getTermsByWords(string $word): string
    {
        return $this->parser->ParseText($this->wikiApi->GetWikiPage($word), self::TERMS_TAG);
    }
}