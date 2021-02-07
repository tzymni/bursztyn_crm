<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CottagesCleaningEventsRepository")
 */
class CottagesCleaningEvents
{
    const EVENT_TYPE = 'CLEANING';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cottages", inversedBy="cottagesCleaningEvents")
     */
    private $cottage;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Events", inversedBy="cottagesCleaningEvents")
     */
    private $event;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEvent(): ?Events
    {
        return $this->event;
    }

    public function setEvent(?Events $event): self
    {
        $this->event = $event;

        return $this;
    }
}
