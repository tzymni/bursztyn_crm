<?php


namespace App\Lib;

/**
 * Interface EventParser
 * @package App\Lib
 */
interface EventParser
{
    public function parseData($data): array;
}