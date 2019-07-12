<?php

namespace App\Entity;

use App\Helper\PlaceStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlaceRepository")
 * @ORM\Table(name="places")
 */
class Place
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"show", "list"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"show", "list", "update"})
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Groups({"show", "list", "update"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Groups({"show", "list"})
     */
    private $images;

    /**
     * @var ArrayCollection|Message[]
     * @ORM\OrderBy({"createdAt" = "DESC"})
     *
     * @ORM\OneToMany(targetEntity="Message", mappedBy="place")
     *
     * @Groups({"show", "list"})
     */
    private $messages;

    /**
     * @var Coords|null
     * @Groups({"show", "list"})
     * @ORM\OneToOne(targetEntity="Coords", cascade={"persist"})
     *
     * @Groups({"show", "list"})
     */
    private $coords;

    /**
     * @ORM\Column(type="integer")
     *
     * @Groups({"show", "list", "update"})
     */
    private $status = PlaceStatus::NEW;

    /**
     * @SerializedName("lastTime")
     * @Groups({"show", "list"})
     */
    public function getLastMessageTime()
    {
        $lastTime = null;
        if ($this->messages->count() > 0) {
            $lastTime = $this->messages->first()->getCreatedAt();
        }
        return $lastTime;
    }

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->status = PlaceStatus::NEW;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImages(): ?string
    {
        return $this->images;
    }

    public function setImages(string $images): self
    {
        $this->images = $images;

        return $this;
    }

    /**
     * @return Message[]|ArrayCollection
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param Message[]|ArrayCollection $messages
     */
    public function setMessages($messages): void
    {
        $this->messages = $messages;
    }

    /**
     * @param Message $message
     */
    public function addMessage(Message $message): void
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
        }
    }

    /**
     * @param Message $message
     */
    public function removeMessage(Message $message): void
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
        }
    }

    /**
     * @return Coords
     */
    public function getCoords()
    {
        return $this->coords;
    }

    /**
     * @param Coords $coords
     */
    public function setCoords(Coords $coords)
    {
        $this->coords = $coords;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }


}
