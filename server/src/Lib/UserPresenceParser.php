<?php

namespace App\Lib;

use App\Entity\UserPresences;

class UserPresenceParser implements EventParser
{

    public function parseData($data): array
    {

        $data['type'] = UserPresences::EVENT_TYPE;
        $data['title'] = $this->generateTitle($data);
        $data['date_to'] = strtotime($data['date_to'] . ' UTC');
        $data['date_from'] = strtotime($data['date_from'] . ' UTC');
        $data['is_active'] = true;

        return $data;
    }

    public function generateTitle($data): string
    {
        return "TEST";
        // TODO: Implement generateTitle() method.
    }
}