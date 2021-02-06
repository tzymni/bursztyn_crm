<?php

namespace App\Lib;

class CleaningParser implements EventParser
{

    public function parseData($data): array
    {
        $data['title'] = 'CLEANING';
        return $data;
        // TODO: Implement create() method.
    }
}