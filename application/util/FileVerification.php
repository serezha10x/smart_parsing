<?php

namespace application\util;

class FileVerification
{
    static function CheckFormat($file_extension) : bool {
        $permit_format = require $_SERVER['DOCUMENT_ROOT'].'/application/config/formats.php';
        return in_array($file_extension, $permit_format);
    }
}