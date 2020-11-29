<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CottagesRepository")
 */
class Cottages
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=8, nullable=true)
     */
    private $color;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_active;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $extra_info;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $max_guests_number;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reservations", mappedBy="cottage")
     */
    private $reservations;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $external_id;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

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

    public function getExtraInfo()
    {
        return $this->extra_info;
    }

    public function setExtraInfo($extra_info): self
    {
        $this->extra_info = $extra_info;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMaxGuestsNumber()
    {
        return $this->max_guests_number;
    }

    /**
     * @param mixed $max_guests_number
     */
    public function setMaxGuestsNumber($max_guests_number): void
    {
        $this->max_guests_number = $max_guests_number;
    }

    /**
     * @return Collection|Reservations[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservations $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setCottage($this);
        }

        return $this;
    }

    public function removeReservation(Reservations $reservation): self
    {
        if ($this->reservations->contains($reservation)) {
            $this->reservations->removeElement($reservation);
            // set the owning side to null (unless already changed)
            if ($reservation->getCottage() === $this) {
                $reservation->setCottage(null);
            }
        }

        return $this;
    }

    public function getExternalId(): ?int
    {
        return $this->external_id;
    }

    public function setExternalId(?int $external_id): self
    {
        $this->external_id = $external_id;

        return $this;
    }

}
