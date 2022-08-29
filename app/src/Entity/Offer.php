<?php

namespace App\Entity;

use ApiPlatform\Core\Action\NotFoundAction;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]


class Offer implements AuthoredEntityInterface, PublishedDateInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["get-with-offers"])]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'offers')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["get-with-offers"])]
    private $author;

    #[ORM\ManyToOne(targetEntity: PostGarbage::class, inversedBy: 'offers')]
    #[Groups(["post", "get-with-offers"])]
    private $post;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["post", "get-with-offers"])]
    private $message;

    #[ORM\Column(type: 'decimal', length: 255)]
    #[Groups(["post", "get-with-offers"])]
    private $price;

    #[ORM\Column(type: 'datetime')]
    #[Groups(["get-with-offers"])]
    private $published;


    public function getId()
    {
        return $this->id;
    }


    public function getPost()
    {
        return $this->post;
    }


    public function setPost($post): self
    {
        $this->post = $post;
        return $this;
    }


    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message): self
    {
        $this->message = $message;
        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price): self
    {
        $this->price = $price;
        return $this;
    }

    public function setAuthor(UserInterface $author): AuthoredEntityInterface
    {
        $this->author = $author;
        return $this;
    }

    public function getAuthor()
    {
        return $this->author;
    }


    public function setPublished(\DateTimeInterface $published): PublishedDateInterface
    {
        $this->published = $published;

        return $this;
    }

    public function getPublished()
    {
        return $this->published;
    }
}