<?php


namespace application\reader;
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

class PdfReader implements IReader
{

    public function read(string $filename): string
    {
        $parser = new \Smalot\PdfParser\Parser();
        $pdf    = $parser->parseFile($filename);

        $text = $pdf->getText();
        return $text;
        return pdf2text($filename);
    }
}