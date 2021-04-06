<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Service to parse data about cottages downloaded from API to format used in the system.
 *
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 **/
class CottagesFromApiParserService
{

    /**
     * @var EntityManagerInterface
     */
    public $em;

    /**
     * CottagesFromApiParserService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;

    }

    /**
     * Parse cottages data from API to format required to save data in the database.
     *
     * @param array $cottagesFromApi
     * @return array
     */
    public function parseCottagesToSystemFormat(array $cottagesFromApi): array
    {
        $parsedCottages = array();

        foreach ($cottagesFromApi['result']['objects'] as $cottagesType) {

            $capacity = $cottagesType['capacity'];

            foreach ($cottagesType['items'] as $cottage) {

                $parsedCottage = array();
                $parsedCottage['name'] = $cottage['name'];
                $parsedCottage['external_id'] = $cottage['id'];
                $parsedCottage['max_guests_number'] = $capacity;
                $parsedCottages[] = $parsedCottage;
            }

        }

        return $parsedCottages;
    }

}