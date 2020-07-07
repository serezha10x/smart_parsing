<?php


namespace application\reader;


use application\core\View;
use application\reader\IReader;
use application\util\FileVerification;
use Exception;


class ReaderCreator
{
    public function factory($file_ext): IReader
    {
        try {
            if (isset($filename) && !file_exists($filename)) {
                throw new Exception('File is not exists...');
            }

            if (!FileVerification::CheckFormat($file_ext)) {
                throw new Exception('This format ('.$file_ext.') is not supported...');
            }

            $reader_class = 'application\reader\\' . ucfirst($file_ext) . 'Reader';
            if (class_exists($reader_class)) {
                return new $reader_class;
            } else {
                throw new Exception("There are no such a class...");
            }
        } catch(Exception $e) {
            exit($e->getMessage());
        }
    }
}