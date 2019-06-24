<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Service;

use App\Entity\Reservations;
use App\Repository\ReservationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityManager;

/**
 * Description of ReservationService
 *
 * @author tzymni
 */
class ReservationService {

    public function prepareEventsData($events_active) {
        $events = [];

        $x = 0;

        foreach ($events_active as $event) {

            $events[$x]['title'] = "Rezerwacja " . $event['name']." ".$event['tourist_name']." (".$event['tourist_num']." osób/osoby)";
            $events[$x]['start_date'] = $event['date_from']->format('Y-m-d');
            $events[$x]['end_date'] = $event['date_to']->format('Y-m-d');
            $events[$x]['color'] = $event['color'];
            $events[$x]['id'] = $event['reservation_id'];
            $x++;
        }

        return $events;
    }

}
