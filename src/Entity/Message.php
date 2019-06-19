<?php

namespace App\Entity;

use App\Helper\AuthorInterface;
use App\Helper\AuthorTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 * @ORM\Table(name="messages")
 */
class Message implements AuthorInterface
{
    use AuthorTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"show", "list"})
     */
    private $id;

    /**
     * @var Place
     * @ORM\ManyToOne(targetEntity="Place", inversedBy="messages")
     */
    private $place;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Groups({"show", "list"})
     */
    private $text;

    /**
     * @ORM\Column(type="integer")
     *
     * @Groups({"show", "list"})
     */
    private $createdAt;

    /**
     *
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     *
     * @Groups({"show", "list"})
     */
    private $author;

    public function __construct()
    {
        $this->createdAt = time();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Place
     */
    public function getPlace(): Place
    {
        return $this->place;
    }

    /**
     * @param Place $place
     */
    public function setPlace(Place $place): void
    {
        $this->place = $place;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getCreatedAt(): ?int
    {
        return $this->createdAt;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor(User $author)
    {
        $this->author = $author;
    }
}
