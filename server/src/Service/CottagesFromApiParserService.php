<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

/**
 *
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 **/
class CottagesFromApiParserService
{

    public $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;

    }

    public function parseCottagesToSystemFormat(array $cottagesFromApi)
    {
        echo "COTTAGES FROM API";

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