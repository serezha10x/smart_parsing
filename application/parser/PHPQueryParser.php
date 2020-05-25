<?php


namespace application\parser;

require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");


class PHPQueryParser
{
    private $filter;
    private $selector;
    private $global_url;
    private $word;

    /**
     * PHPQueryParser constructor.
     * @param string $filter
     * @param string $global_url
     * @param string $word
     */

    public function __construct(string $filter, string $selector,  string $global_url, string $word)
    {
        $this->filter = $filter;
        $this->global_url = $global_url;
        $this->word = $word;
        $this->selector = $selector;
    }


    public function ParseSynonym(int $limit) : array {
        try {
            $url = $this->global_url . $this->word;
            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HEADER, true);

            $html = curl_exec($ch);

            curl_close($ch);

            \phpQuery::newDocument($html);

            $synonyms = pq($this->filter)->find($this->selector)->text();

            $arr_synonyms = preg_split("@\n@u", $synonyms, $limit, PREG_SPLIT_NO_EMPTY);

            var_dump($arr_synonyms);

            \phpQuery::unloadDocuments();

            return $arr_synonyms;

        } catch (\Exception $ex) {
            echo $ex->getMessage();
            return array('');
        }
    }
}