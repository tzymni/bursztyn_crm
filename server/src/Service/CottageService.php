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

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * CottageService constructor.
     * @param EntityManagerInterface $em
     */
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
        $isActive = isset($data['is_active']) ? $data['is_active'] : true;

        if (!empty($color) && !$this->validHexColor($color)) {
            return 'Invalid hex color!';
        }

        $cottage = new Cottages();

        $cottage->setName($name);
        $cottage->setColor($color);
        $cottage->setExtraInfo($extra_info);
        $cottage->setIsActive($isActive);
        $cottage->setMaxGuestsNumber($maxGuestsNumber);

        try {
            $this->em->persist($cottage);

            $this->em->flush();

            return $cottage;
        } catch (\Exception $ex) {
            return "Can't create cottage!" . $ex->getMessage();
        }
    }

    public function getCottageById($id)
    {
        $cottage = $this->em->getRepository('App:Cottages')
            ->findOneBy(['id' => $id]);

        if ($cottage) {
            return $cottage;
        } else {
            return "Cant't find cottage!";
        }
    }

    /**
     * Find active cottage by id.
     *
     * @param $id
     * @return object|string
     */
    public function getActiveCottageById($id)
    {
        $cottage = $this->em->getRepository('App:Cottages')->findBy(
            array("is_active" => true, "id" => $id),
            array(),
            array(1)
        );

        if (isset($cottage) && isset($cottage[0])) {
            return $cottage[0];
        } else {
            return sprintf("Can't find cottage!");
        }
    }

    public function updateCottage(Cottages $cottage, array $data)
    {
        try {
            if (!empty($data['name'])) {
                $cottage->setName($data['name']);
            }

            if (!empty($data['color'])) {
                $cottage->setColor($data['color']);
            }

            if (!empty($data['extra_info'])) {
                $cottage->setExtraInfo($data['extra_info']);
            }
            if (!empty($data['max_guests_number'])) {
                $cottage->setMaxGuestsNumber($data['max_guests_number']);
            }

            $this->em->persist($cottage);
            $this->em->flush();

            return $cottage;
        } catch (\Exception $ex) {
            return "Unable to update cottage " . $ex->getMessage();
        }
    }

    /**
     * Deactivate all related objects by set is_active = false.
     *
     * @param Cottages $cottages
     */
    protected function deactivateRelatedObjects(Cottages $cottages)
    {
        $reservations = $cottages->getReservations();

        foreach ($reservations as $reservation) {
            $reservation->setIsActive(false);
            $event = $reservation->getEvent();
            $event->setIsActive(false);
        }
    }

    /**
     * Soft delete user (change is_active = false).
     *
     * @param Cottages $cottages
     * @return Cottagesr|string
     */
    public function deleteCottage(Cottages $cottages)
    {
        $cottages->setIsActive(false);

        try {
            $this->deactivateRelatedObjects($cottages);
            $this->em->persist($cottages);
            $this->em->flush();
            return $cottages;
        } catch (Exception $ex) {
            return "Cant remove cottage!";
        }
    }

}
