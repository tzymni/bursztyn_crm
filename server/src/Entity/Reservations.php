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
     * @ORM\OneToOne(targetEntity="App\Entity\Events", inversedBy="reservations", cascade={"persist", "remove"})
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cottages", inversedBy="reservations")
     */
    private $cottage;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $guest_first_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $guest_last_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $guest_phone_number;

    /**
     * @ORM\Column(type="integer")
     */
    private $guests_number;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $advance_payment;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $extra_info;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_active;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_add;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $advance;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $external_id;

    public function getId()
    {
        return $this->id;
    }

    public function getEvent(): ?Events
    {
        return $this->event;
    }

    public function setEvent(?Events $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getCottage(): ?Cottages
    {
        return $this->cottage;
    }

    public function setCottage(?Cottages $cottage): self
    {
        $this->cottage = $cottage;

        return $this;
    }

    public function getGuestFirstName(): ?string
    {
        return $this->guest_first_name;
    }

    public function setGuestFirstName(string $guest_first_name): self
    {
        $this->guest_first_name = $guest_first_name;

        return $this;
    }

    public function getGuestLastName(): ?string
    {
        return $this->guest_last_name;
    }

    public function setGuestLastName(?string $guest_last_name): self
    {
        $this->guest_last_name = $guest_last_name;

        return $this;
    }

    public function getGuestPhoneNumber(): ?string
    {
        return $this->guest_phone_number;
    }

    public function setGuestPhoneNumber(?string $guest_phone_number): self
    {
        $this->guest_phone_number = $guest_phone_number;

        return $this;
    }

    public function getGuestsNumber(): ?int
    {
        return $this->guests_number;
    }

    public function setGuestsNumber(int $guests_number): self
    {
        $this->guests_number = $guests_number;

        return $this;
    }

    public function getAdvancePayment(): ?bool
    {
        return $this->advance_payment;
    }

    public function setAdvancePayment(?bool $advance_payment): self
    {
        $this->advance_payment = $advance_payment;

        return $this;
    }

    public function getExtraInfo(): ?string
    {
        return $this->extra_info;
    }

    public function setExtraInfo(?string $extra_info): self
    {
        $this->extra_info = $extra_info;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(?bool $is_active): self
    {
        $this->is_active = $is_active;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDateAdd(): ?\DateTimeInterface
    {
        return $this->date_add;
    }

    public function setDateAdd(\DateTimeInterface $date_add): self
    {
        $this->date_add = $date_add;

        return $this;
    }

    public function getAdvance(): ?float
    {
        return $this->advance;
    }

    public function setAdvance(?float $advance): self
    {
        $this->advance = $advance;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

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
