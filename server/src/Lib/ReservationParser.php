<?php

namespace App\Lib;

use App\Entity\Cottages;
use App\Entity\Reservations;
use App\Repository\CottagesRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Parse data for reservation event.
 *
 * @package App\Lib
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 */
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
     * Create title for reservation event.
     *
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

        $cottageRepository = $this->em->getRepository(Cottages::class);
        $cottageResponse = null;
        if ($cottageRepository instanceof CottagesRepository) {
            $cottageResponse = $cottageRepository->findActiveById($data['cottage_id']);
        }

        if (!$cottageResponse instanceof Cottages) {
            throw new \Exception($cottageResponse);
        }

        return sprintf("%s: Rezerwacja dla %s %s (%s - %s)", $cottageResponse->getName(), $firstName, $lastName,
            $dateFrom, $dateTo);
    }

    /**
     * Parse reservation data.
     *
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