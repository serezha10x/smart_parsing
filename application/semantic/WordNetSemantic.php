<?php


namespace application\semantic;


use application\wordnet\WordNetApi;


class WordNetSemantic implements ISemanticParsable
{
    private $wordnet_api;

    public function __construct()
    {
        $this->wordnet_api = new WordNetApi();
    }

    public function getTermsByWords(string $word): string
    {
        return $this->wordnet_api->getSynsetsGloss($word);
    }
}