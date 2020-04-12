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
class CottageService
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Check if color is hex.
     *
     * @param $color
     * @return bool
     */
    public function validHexColor($color)
    {
        if (preg_match('/^#[a-f0-9]{6}$/i', $color)) //hex color is valid
        {
            return true;
        }

        return false;
    }

    /**
     * @param $data
     *    $data = [
     *      'name' => (string) User name. Required.
     *      'password' => (string) User (plain) password. Required.
     *    ]
     * @return User|string User entity or string in case of error
     */
    public function createCottage($data)
    {
        $name = empty($data['name']) ? null : $data['name'];
        $color = empty($data['color']) ? null : $data['color'];
        $extra_info = empty($data['extra_info']) ? null : $data['extra_info'];
        $maxGuestsNumber = empty($data['max_guests_number']) ? null : $data['max_guests_number'];

        if (!empty($color) && !$this->validHexColor($color)) {
            return 'Invalid hex color!';
        }

        $cottage = new Cottages();

        $cottage->setName($name);
        $cottage->setColor($color);
        $cottage->setExtraInfo($extra_info);
        $cottage->setIsActive(true);
        $cottage->setMaxGuestsNumber($maxGuestsNumber);

        try {
            $this->em->persist($cottage);

            $this->em->flush();

            return $cottage;
        } catch (\Exception $ex) {
            return "Can't create cottage!" . $ex->getMessage();
        }
    }

    public function getCottage($id)
    {
        $cottage = $this->em->getRepository('App:Cottages')
            ->findOneBy(['id' => $id]);

        if ($cottage) {
            return $cottage;
        } else {
            return "Cant't find cottage!";
        }
    }

    public function updateCottage(Cottages $cottage, array $data)
    {
        try {
            if (isset($data['name'])) {
                $cottage->setName($data['name']);
            }

            if (isset($data['color'])) {
                $cottage->setColor($data['color']);
            }

            if (isset($data['extra_info'])) {
                $cottage->setExtraInfo($data['extra_info']);
            }

            $this->em->persist($cottage);
            $this->em->flush();

            return $cottage;
        } catch (\Exception $ex) {
            return "Unable to update cottage " . $ex->getMessage();
        }
    }

}
