<?php

namespace application\wiki;


class WikipediaApi
{
    public function WikiClient($title)
    {
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'content' => $reqdata = http_build_query(array(
                    'action' => 'query',
                    'list' => 'search',
                    'srsearch' => $title,
                    'format' => 'json'
                )),
                'header' => implode("\r\n", array(
                    "Content-Length: " . strlen($reqdata),
                    "User-Agent: MyCuteBot/0.1",
                    "Connection: Close",
                    ""
                ))
            ))
        );

        if (false === $response = file_get_contents("https://ru.wikipedia.org/w/api.php", false, $context)) {
            return false;
        }
        //парсим строку
        $json = json_decode($response, JSON_UNESCAPED_UNICODE);
        //echo "<pre>";var_dump($json); exit();
        return $json;
    }
}
