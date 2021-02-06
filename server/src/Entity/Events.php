<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventsRepository")
 */
class Events
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $date_from_unix_utc;

    /**
     * @ORM\Column(type="integer")
     */
    private $date_to_unix_utc;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_active;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="events", cascade={"persist", "remove"})
     *
     */
    private $created_by;

    /**
     * @ORM\Column(type="string", length=255, columnDefinition="ENUM('RESERVATION', 'CLEANING')")
     */
    private $type;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Reservations", mappedBy="event", cascade={"persist", "remove"})
     */
    private $reservations;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $date_from;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $date_to;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CottagesCleaningEvents", mappedBy="event")
     */
    private $cottagesCleaningEvents;

    public function __construct()
    {
        $this->cottagesCleaningEvents = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateFromUnixUtc(): ?int
    {
        return $this->date_from_unix_utc;
    }

    public function setDateFromUnixUtc(int $date_from_unix_utc): self
    {
        $this->date_from_unix_utc = $date_from_unix_utc;

        return $this;
    }

    public function getDateToUnixUtc(): ?int
    {
        return $this->date_to_unix_utc;
    }

    public function setDateToUnixUtc(int $date_to_unix_utc): self
    {
        $this->date_to_unix_utc = $date_to_unix_utc;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

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

    public function getCreatedBy(): ?User
    {
        return $this->created_by;
    }

    public function setCreatedBy(?User $created_by): self
    {
        $this->created_by = $created_by;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getReservations(): ?Reservations
    {
        return $this->reservations;
    }

    public function setReservations(?Reservations $reservations): self
    {
        $this->reservations = $reservations;

        // set (or unset) the owning side of the relation if necessary
        $newEvent = null === $reservations ? null : $this;
        if ($reservations->getEvent() !== $newEvent) {
            $reservations->setEvent($newEvent);
        }

        return $this;
    }

    public function getDateFrom(): ?string
    {
        return $this->date_from;
    }

    public function setDateFrom(?string $date_from): self
    {
        $this->date_from = $date_from;

        return $this;
    }

    public function getDateTo(): ?string
    {
        return $this->date_to;
    }

    public function setDateTo(?string $date_to): self
    {
        $this->date_to = $date_to;

        return $this;
    }

    /**
     * @return Collection|CottagesCleaningEvents[]
     */
    public function getCottagesCleaningEvents(): Collection
    {
        return $this->cottagesCleaningEvents;
    }

    public function addCottagesCleaningEvent(CottagesCleaningEvents $cottagesCleaningEvent): self
    {
        if (!$this->cottagesCleaningEvents->contains($cottagesCleaningEvent)) {
            $this->cottagesCleaningEvents[] = $cottagesCleaningEvent;
            $cottagesCleaningEvent->setEvent($this);
        }

        return $this;
    }

    public function removeCottagesCleaningEvent(CottagesCleaningEvents $cottagesCleaningEvent): self
    {
        if ($this->cottagesCleaningEvents->contains($cottagesCleaningEvent)) {
            $this->cottagesCleaningEvents->removeElement($cottagesCleaningEvent);
            // set the owning side to null (unless already changed)
            if ($cottagesCleaningEvent->getEvent() === $this) {
                $cottagesCleaningEvent->setEvent(null);
            }
        }

        return $this;
    }

}
