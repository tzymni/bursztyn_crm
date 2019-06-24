<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReservationsRepository")
 */
class Reservations
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="date")
     */
    private $date_from;

    /**
     * @ORM\Column(type="date")
     */
    private $date_to;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_active;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cottages")
     */
    private $cottage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tourist_name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tourist_num;

    public function getId()
    {
        return $this->id;
    }



    public function getDateFrom(): \DateTimeInterface
    {
        return $this->date_from;
    }

    public function setDateFrom(\DateTimeInterface $date_from): self
    {
        $this->date_from = $date_from;

        return $this;
    }

    public function getDateTo(): DateTimeInterface
    {
        return $this->date_to;
    }

    public function setDateTo(\DateTimeInterface $date_to): self
    {
        $this->date_to = $date_to;

        return $this;
    }

    public function getIsActive(): bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): self
    {
        $this->is_active = $is_active;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCottage(): Cottages
    {
        return $this->cottage;
    }

    public function setCottage(Cottages $cottage): self
    {
        $this->cottage = $cottage;

        return $this;
    }

    public function getTouristName(): string
    {
        return $this->tourist_name;
    }

    public function setTouristName(string $tourist_name): self
    {
        $this->tourist_name = $tourist_name;

        return $this;
    }

    public function getTouristNum(): int
    {
        return $this->tourist_num;
    }

    public function setTouristNum(int $tourist_num): self
    {
        $this->tourist_num = $tourist_num;

        return $this;
    }
}
