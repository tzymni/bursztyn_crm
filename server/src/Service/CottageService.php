<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Service;

use App\Entity\Cottages;
use App\Repository\CottagesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityManager;

/**
 * Description of CottageService
 *
 * @author tzymni
 */
class CottageService {

    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    /**
     * @param $data
     *    $data = [
     *      'name' => (string) User name. Required.
     *      'password' => (string) User (plain) password. Required.
     *    ]
     * @return User|string User entity or string in case of error
     */
    public function createCottage($data) {



        $name = $data['name'];
        $color = $data['color'];
        $extra_info = $data['extra_info'];

        $cottage = new Cottages();
        $cottage->setName($name);
        $cottage->setColor($color);
        $cottage->setExtraInfo($extra_info);
        $cottage->setIsActive(1);

        try {
            $this->em->persist($cottage);
            
            $this->em->flush();


            return $cottage;
        }  catch (\Exception $ex) {
            
            return "Nie udało się stworzyć domku ".$ex->getMessage();
        }
    }
    
    
        public function getCottage($id) {
        $cottage = $this->em->getRepository('App:Cottages')
                ->findOneBy(['id' => $id]);

        if ($cottage) {
            return $cottage;
        } else {
            return "Nie ma takiego domku";
        }
    }

}
