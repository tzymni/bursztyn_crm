<?php

namespace App\Lib;

use App\Entity\Cottages;
use App\Entity\Reservations;
use App\Service\CottageService;
use Doctrine\ORM\EntityManagerInterface;

class ReservationParser implements EventParser
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ReservationParser constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param $data
     * @return string
     * @throws \Exception
     */
    public function generateTitle($data): string
    {
        $firstName = $data['guest_first_name'];
        $lastName = $data['guest_last_name'];
        $dateFrom = $data['date_from'];
        $dateTo = $data['date_to'];

        $cottageService = new CottageService($this->em);
        $cottageResponse = $cottageService->getActiveCottageById($data['cottage_id']);

        if (!$cottageResponse instanceof Cottages) {
            throw new \Exception($cottageResponse);
        }

        return sprintf("%s: Reservation for %s %s (%s - %s)", $cottageResponse->getName(), $firstName, $lastName,
            $dateFrom, $dateTo);
    }


    /**
     * @param $data
     * @return array
     * @throws \Exception
     */
    public function parseData($data): array
    {

        $data['type'] = Reservations::EVENT_TYPE;
        $data['title'] = $this->generateTitle($data);
        $data['date_to'] = strtotime($data['date_to'] . ' UTC');
        $data['date_from'] = strtotime($data['date_from'] . ' UTC');


        return $data;
    }
}