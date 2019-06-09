<?php

namespace App\Entity;

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

    public function getExtraInfo(): string
    {
        return $this->extra_info;
    }

    public function setExtraInfo(string $extra_info): self
    {
        $this->extra_info = $extra_info;

        return $this;
    }
}
