<?php


namespace application\parser;


abstract class Parser
{
    protected $text;

    public function __construct(&$text)
    {
        $this->text = $text;
    }

    abstract public function parse();
}