<?php


namespace application\reader;


interface IReader
{
    public function read(string $filename): string;
}