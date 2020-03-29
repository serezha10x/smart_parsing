<?php

require 'vendor/autoload.php';


class ReaderFactory
{
    public function GetReader($format)
    {
        $objReader = null;
        switch ($format)
        {
            case 'docx':
                $objReader = \PhpOffice\PhpWord\IOFactory::createReader('Word2007');
                break;
        }

        return $objReader;
    }
}