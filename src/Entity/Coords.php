<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CoordsRepository")
 */
class Coords
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     *
     * @Groups({"show", "list"})
     */
    private $ltd;

    /**
     * @ORM\Column(type="float")
     *
     * @Groups({"show", "list"})
     */
    private $lng;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLtd(): ?float
    {
        return $this->ltd;
    }

    public function setLtd(float $ltd): self
    {
        $this->ltd = $ltd;

        return $this;
    }

    public function getLng(): ?float
    {
        return $this->lng;
    }

    public function setLng(float $lng): self
    {
        $this->lng = $lng;

        return $this;
    }
}
