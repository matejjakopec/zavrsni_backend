<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\PostRemovalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PostRemovalRepository::class)]

class PostRemoval implements AuthoredEntityInterface, PublishedDateInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["get-with-images"])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["post", "get-with-images"])]
    private $title;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["post", "get-with-images"])]
    private $content;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["post", "get-with-images"])]
    private $location;

    #[ORM\Column(type: 'datetime')]
    #[Groups(["get-with-images"])]
    private $published;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'postsRemovals')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["get-with-images"])]
    private $author;

    #[ORM\ManyToMany(targetEntity: "App\Entity\Image")]
    #[ORM\JoinTable]
    #[ApiSubresource]
    #[Groups(["post", "get-with-images"])]
    private $images;



    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getPublished(): ?\DateTimeInterface
    {
        return $this->published;
    }

    public function setPublished(\DateTimeInterface $published): PublishedDateInterface
    {
        $this->published = $published;

        return $this;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @param User $author
     * @return PostRemoval
     */
    public function setAuthor(UserInterface $author): AuthoredEntityInterface
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image)
    {
        $this->images->add($image);
    }

    public function removeImage(Image $image)
    {
        $this->images->removeElement($image);
    }
}
