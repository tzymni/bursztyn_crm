<?php

namespace App\Service;

use App\Entity\Cottages;
use App\Repository\CottagesRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class CottageService to manage cottages (adding, removing, updating, etc).
 *
 * @package App\Service
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 */
class CottageService
{
    /**
     * Available list of cottages colors.
     *
     * @var string[]
     */
    public $colorList = array(
        "#4D4D4D",
        "#999999",
        '#FFFFFF',
        '#FE9200',
        '#FCDC00',
        '#DBDF00',
        '#A4DD00',
        '#68CCCA',
        '#73D8FF',
        '#AEA1FF',
        '#FDA1FF',
        '#333333',
        '#808080',
        '#CCCCCC',
        '#D33115',
        '#E27300',
        '#FCC400',
        '#B0BC00',
        '#68BC00',
        '#16A5A5',
        '#009CE0',
        '#7B64FF',
        '#FA28FF',
        '#000000',
        '#666666',
        '#B3B3B3',
        '#9F0500',
        '#C45100',
        '#FB9E00',
        '#808900',
        '#194D33',
        '#0C797D',
        '#0062B1',
        '#653294',
        '#AB149E'
    );

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
     * Check if color is in hexadecimal format.
     *
     * @param $color
     * @return bool
     */
    public function validHexColor($color): bool
    {
        if (preg_match('/^#[a-f0-9]{6}$/i', $color)) {
            return true;
        }
        return false;
    }

    /**
     * Add cottage to the database.
     *
     * @param $data
     *    $data = [
     *      'name' => (string) Users name. Required.
     *      'password' => (string) Users (plain) password. Required.
     *    ]
     * @return Users|string Users entity or string in case of error
     */
    public function createCottage($data)
    {
        $name = empty($data['name']) ? null : $data['name'];
        $color = empty($data['color']) ? null : $data['color'];
        $extra_info = empty($data['extra_info']) ? null : $data['extra_info'];
        $maxGuestsNumber = empty($data['max_guests_number']) ? null : $data['max_guests_number'];
        $isActive = isset($data['is_active']) ? $data['is_active'] : true;
        $externalId = empty($data['external_id']) ? null : $data['external_id'];

        if (!empty($color) && !$this->validHexColor($color)) {
            return 'Invalid hex color!';
        }

        $cottage = new Cottages();

        $cottage->setName($name);
        $cottage->setColor($color);
        $cottage->setExtraInfo($extra_info);
        $cottage->setIsActive($isActive);
        $cottage->setMaxGuestsNumber($maxGuestsNumber);
        $cottage->setExternalId($externalId);

        try {
            $this->em->persist($cottage);
            $this->em->flush();
            return $cottage;
        } catch (\Exception $ex) {
            return "Can't create cottage!" . $ex->getMessage();
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
        $cottageRepository = $this->em->getRepository(Cottages::class);

        if ($cottageRepository instanceof CottagesRepository) {
            $cottage = $cottageRepository->findActiveById($id);
        }

        if (empty($cottage)) {
            return sprintf("Can't find cottage!");
        } else {
            return $cottage;
        }
    }

    /**
     * Get all active cottages.
     *
     * @return object[]|string
     */
    public function getActiveCottages()
    {

        $cottageRepository = $this->em->getRepository(Cottages::class);

        if ($cottageRepository instanceof CottagesRepository) {
            $cottages = $cottageRepository->findAllActive();
        }

        if (isset($cottages) && isset($cottages[0])) {
            return $cottages;
        } else {
            return sprintf("Can't find active cottages!");
        }
    }

    /**
     * Update cottage.
     *
     * @param Cottages $cottage
     * @param array $data
     * @return Cottages|string
     */
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

    /**
     * Get color that are not used in database.
     *
     * @return string
     */
    public function getUnusedColor(): string
    {

        $cottagesRepository = $this->em->getRepository(Cottages::class);

        if ($cottagesRepository instanceof CottagesRepository) {
            $colorsInDB = $cottagesRepository->findAllColorsOfActiveCottages();
        } else {
            $colorsInDB = null;
        }

        $usedColors = array();
        foreach ($colorsInDB as $color) {
            $usedColors[] = $color['color'];
        }

        $colorList = $this->colorList;
        foreach ($usedColors as $usedColor) {
            if (($key = array_search($usedColor, $colorList)) !== false) {
                unset($colorList[$key]);
            }
        }

        if (empty($colorList)) {
            $unusedColor = "#FFFFFF";
        } else {
            $ranKey = array_rand($colorList, 1);
            $unusedColor = $colorList[$ranKey];
        }

        return $unusedColor;
    }

    /**
     * Get all ids of external cottages.
     *
     * @return array
     */
    public function getAllIdsOfExternalCottages(): array
    {
        $cottageRepository = $this->em->getRepository(Cottages::class);
        $externalCottages = null;
        if ($cottageRepository instanceof CottagesRepository) {
            $externalCottages = $cottageRepository->findAllExternalActiveCottages();
        };

        $ids = array();
        foreach ($externalCottages as $externalCottage) {
            $ids[] = $externalCottage->getId();
        }

        return $ids;

    }

}
