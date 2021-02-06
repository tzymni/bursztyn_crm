<?php


namespace App\Lib;


interface EventParser
{
    public function parseData($data): array;
}