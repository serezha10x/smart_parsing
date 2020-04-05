<?php


namespace application\parser;
require_once($_SERVER['DOCUMENT_ROOT'] . '/application/frameworks/phpmorphy-0.3.7/src/common.php');

use phpMorphy;
use phpMorphy_Exception;


class ParserDictionary
{
    private $key_words;
    private $text;
    private $morphy;

    public function __construct(&$text) {
        $this->key_words = array();
        $this->text = $text;
    }

    public function run()
    {
        $dir = $_SERVER['DOCUMENT_ROOT'] . "/vendor/cijic/phpmorphy/libs/phpmorphy/dicts";

        $lang = 'ru_RU';

        $opts = array(
            'storage' => PHPMORPHY_STORAGE_FILE
        );

        try {
            $this->morphy = new phpMorphy($dir, $lang, $opts);
        } catch(phpMorphy_Exception $e) {
            die('Error occured while creating phpMorphy instance: ' . $e->getMessage());
        }

        // убираем все, кроме букв
        $str_freq  = preg_replace('@([^А-Яа-яA-Za-z\s\-])@u', '', $this->text);
        // убираем все лишние пробелы
        $str_freq  = preg_replace('@\s{2,}@u', ' ', $str_freq);
        // разбиваем строку по пробелам
        $arr_words = preg_split("@ @u", $str_freq);

        $count = count($arr_words);
        // получаем части речи, необходимые для парсинга
        $need_words = require __DIR__ . "/need_words.php";

        $array_defis = array();
        for ($i = 0; $i < $count; $i++) {
            if (mb_strlen($arr_words[$i]) <= 3) {
                unset($arr_words[$i]);
                continue;
            }
            $arr_words[$i] = mb_strtoupper($arr_words[$i]);

            if ($part = $this->morphy->getPartOfSpeech($arr_words[$i])) {
                // проверка на часть речи
                $isNeedPart = false;
                foreach ($need_words as $nw) {
                    if (in_array($nw, $part)) {
                        $isNeedPart = true;
                    }
                }
                if (!$isNeedPart) {
                    unset($arr_words[$i]);
                    continue;
                }

                $result = $this->morphy->findWord($arr_words[$i], phpMorphy::IGNORE_PREDICT);
                if ($result === false) {

                    // проверка на -
                    if (mb_strpos($arr_words[$i], "-")) {
                        $tmp = preg_split("@-@", $arr_words[$i]);
                        foreach ($tmp as $s) {
                            // добавляем слова по отдельности
                            array_push($arr_words, $s);
                        }
                        // проверка на идентичность слов
                        $isUnique = true;
                        foreach ($array_defis as $arr_d) {
                            if (!$this->isUnique($tmp, $arr_d)) {
                                $isUnique = false;
                                break;
                            }
                        }
                        if ($isUnique) {
                            $temp_array = array();
                            foreach ($tmp as $t) {
                                array_push($temp_array, $this->morphy->lemmatize($t)[0]);
                            }
                            $array_defis[] = $temp_array;
                        }
                        unset($arr_words[$i]);

                        $count += count($tmp);
                        continue;

                    // проверка на Ё
                    } else if (mb_substr_count($arr_words[$i], 'Е') > 0) {
                        if ($change_words = $this->str_replace_once('Е', 'Ё', $arr_words[$i])) {
                            $isExist = false;
                            foreach ($change_words as $change_word) {
                                $result = $this->morphy->findWord($change_word, phpMorphy::IGNORE_PREDICT);
                                if ($result !== false) {
                                    $isExist = true;
                                    break;
                                }
                            }
                            if ($isExist) {
                                continue;
                            } else {
                                unset($arr_words[$i]);
                            }
                        }
                    } else {
                        unset($arr_words[$i]);
                    }
                }
            }
        }

        $num_max = 3;
        $arr_freq  = array_count_values($arr_words);
        $maxes = $this->getMaxes($arr_freq, $num_max);

        $temp_array = array();

        foreach ($arr_freq as $key => $value) {
            for ($i = 0; $i < $num_max; $i++) {
                if ($value == $maxes[$i]) {
                    $key_word = $this->morphy->lemmatize($key)[0];
                    $this->key_words[] = $key_word;
                }
            }
        }

        foreach ($array_defis as $arr_d) {
            $str_defis = "";
            foreach ($arr_d as $str) {
                $str_defis .= ($str . "-");
            }
            $str_defis = trim($str_defis, "-");
            if (!empty($str_defis)) {
                $this->key_words[] = $str_defis;
            }
        }

        array_unique($this->key_words);
        var_dump($this->key_words);
    }


    private function isUnique($arr_1, $arr_2) : bool {
        if (count($arr_1) != count($arr_2)) {
            return true;
        } else {
            $k = 0;
            $size = count($arr_1);
            for($i = 0; $i < $size; $i++) {
                if ($arr_1[$i] === $arr_2[$i]) {
                    $k++;
                }
            }
            if ($k == $size) {
                return false;
            } else {
                return true;
            }
        }
    }


    private function getMaxes($arr_freq, $num_max) {
        $maxes = array();
        for ($i = 0; $i < $num_max; $i++) {
            $temp_max = -1;
            foreach ($arr_freq as $key => $value) {
                if ($i == 0) {
                    if ($value > $temp_max) {
                        $temp_max = $value;
                    }
                    continue;
                }
                else {
                    $isWas = false;
                    if ($value > $temp_max) {
                        for ($j = 0; $j < $i; $j++) {
                            if ($value == $maxes[$j]) {
                                $isWas = true;
                            }
                        }
                        if (!$isWas) {
                            $temp_max = $value;
                        }
                    }
                }
            }
            $maxes[$i] = $temp_max;
        }
        return $maxes;
    }


    private function str_replace_once($search, $replace, $subject) {
        $count = mb_substr_count($subject, 'Е');
        if ($count === 0) {
            return false;
        }

        $pos = 0;
        $change_words = array();
        for($i = 0; $i < $count; $i++) {
            $pos = mb_strpos($subject, $search, $pos);
            $change_words[] = $this->mb_substr_replace($subject, $replace, $pos);
        }
        return $change_words;
    }

    function mb_substr_replace($original, $replacement, $position)
    {
        $startString = mb_substr($original, 0, $position, "UTF-8");
        $endString = mb_substr($original, $position + 1, mb_strlen($original), "UTF-8");

        $out = $startString . $replacement . $endString;

        return $out;
    }
}