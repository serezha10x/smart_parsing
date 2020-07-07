<?php


namespace application\semantic;

interface ISemanticParsable
{
    public function getTermsByWords(string $word) : string;
}