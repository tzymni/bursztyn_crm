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
     * @ORM\ManyToOne(targetEntity="App\Entity\Cottages", inversedBy="reservations")
     */
    private $cottage_id;

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

    public function getId()
    {
        return $this->id;
    }

    public function getCottageId(): Cottages
    {
        return $this->cottage_id;
    }

    public function setCottageId(Cottages $cottage_id): self
    {
        $this->cottage_id = $cottage_id;

        return $this;
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
}
