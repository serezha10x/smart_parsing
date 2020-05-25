<?php

namespace application\parser;

use application\util\FileVerification;

class Reader {
    private $filename;

    public function __construct($filePath) {
        $this->filename = $filePath;
    }


    private function read_doc() {
        $fileHandle = fopen($this->filename, "r");
        $line = @fread($fileHandle, filesize($this->filename));
        $lines = explode(chr(0x0D),$line);
        $outtext = "";
        foreach($lines as $thisline)
        {
            $pos = strpos($thisline, chr(0x00));
            if (($pos !== FALSE)||(strlen($thisline)==0))
            {
            } else {
                $outtext .= $thisline." ";
            }
        }
        $outtext = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/","",$outtext);
        return $outtext;
    }

    private function read_docx(){
        $striped_content = '';
        $content = '';

        $zip = zip_open($this->filename);
        if (!$zip || is_numeric($zip)) return false;

        while ($zip_entry = zip_read($zip)) {
            if (zip_entry_open($zip, $zip_entry) == FALSE) continue;
            if (zip_entry_name($zip_entry) != "word/document.xml") continue;
            $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
            zip_entry_close($zip_entry);
        }

        zip_close($zip);

        $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
        $content = str_replace('</w:r></w:p>', "\r\n", $content);

        $striped_content = strip_tags($content);
        return $striped_content;
    }

    /************************excel sheet************************************/

    function xlsx_to_text($input_file){
        $xml_filename = "xl/sharedStrings.xml"; //content file name
        $zip_handle = new ZipArchive;
        $output_text = "";
        if(true === $zip_handle->open($input_file)){
            if(($xml_index = $zip_handle->locateName($xml_filename)) !== false){
                $xml_datas = $zip_handle->getFromIndex($xml_index);
                $xml_handle = DOMDocument::loadXML($xml_datas, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
                $output_text = strip_tags($xml_handle->saveXML());
            }else{
                $output_text .="";
            }
            $zip_handle->close();
        }else{
            $output_text .="";
        }
        return $output_text;
    }

    /*************************power point files*****************************/
    function pptx_to_text($input_file){
        $zip_handle = new ZipArchive;
        $output_text = "";
        if(true === $zip_handle->open($input_file)){
            $slide_number = 1; //loop through slide files
            while(($xml_index = $zip_handle->locateName("ppt/slides/slide".$slide_number.".xml")) !== false){
                $xml_datas = $zip_handle->getFromIndex($xml_index);
                $xml_handle = DOMDocument::loadXML($xml_datas, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
                $output_text .= strip_tags($xml_handle->saveXML());
                $slide_number++;
            }
            if($slide_number == 1){
                $output_text .="";
            }
            $zip_handle->close();
        }else{
            $output_text .="";
        }
        return $output_text;
    }


    public function convertToText() {
        try {
            if (isset($this->filename) && !file_exists($this->filename)) {
                throw new \Exception('File Not exists...');
            }

            $fileArray = pathinfo($this->filename);
            $file_ext = $fileArray['extension'];

            if (!FileVerification::CheckFormat($file_ext)) {
                throw new \Exception('This format is not supported');
            }

            switch ($file_ext) {
                case 'doc':
                    $d = new doc();
                    $d->read($this->filename);
                    $text = $d->parse();
                    $text = strip_tags($text);
                    return mb_convert_encoding($text, "UTF-8", "auto");
                    return $text;
                case 'docx':
                    return $this->read_docx();
                case 'xlsx':
                    return $this->read_xlsx();
                case 'pptx':
                    return $this->read_pptx();
                default:
                    throw new \Exception('This format is not supported');
            }
        }
        catch (\Exception $ex)
        {
            die($ex->getMessage());
        }
    }

}