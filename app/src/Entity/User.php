<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\GetUserFromTokenController;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    collectionOperations: [
        "get"=>[
            'method' => 'GET',
            'path' => '/users',
            'controller' => GetUserFromTokenController::class,
            "normalization_context"=>["groups" => ["get", "get-with-images","get-with-offers"],
                "enable_max_depth"=>true]],
        "post"=>[
            "denormalization_context"=>["groups"=>["post"]]],
        "put"=>["normalization_context"=>["groups" => ["get"]]]],
    itemOperations: [
        "get"=>["access_control"=>"is_granted('IS_AUTHENTICATED_FULLY')",
            "normalization_context"=>["groups" => ["get", "get-with-images", "get-with-offers"],
                "enable_max_depth"=>true]],
        "put"=>["access_control"=>"is_granted('IS_AUTHENTICATED_FULLY')
            and object == user",
            "denormalization_context"=>["groups"=>["put"]]]],
    normalizationContext: ["groups"=>["get", "get-with-offers"]])]
#[UniqueEntity("email")]
#[UniqueEntity("username")]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["get", "get-with-images", "get-with-offers"])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["get", "post", "get-with-images", "get-with-offers"])]
    #[Assert\NotBlank]
    #[Assert\Length(min: 6, max: 30)]
    private $username;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 8, max: 30)]
    #[Groups(["put", "post"])]
    private $password;

    #[Assert\NotBlank]
    #[Assert\Expression("this.getPassword() === this.getRetypedPassword()",
    message: "Passwords do not match")]
    #[Groups(["put", "post"])]
    private $retypedPassword;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Groups(["get", "post"])]
    private $email;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: PostRemoval::class)]
    #[Groups(["get"])]
    #[MaxDepth(1)]
    private Collection $postsRemovals;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: PostGarbage::class)]
    #[Groups(["get"])]
    #[MaxDepth(1)]
    private Collection $postsGarbages;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Offer::class)]
    #[Groups(["get"])]
    #[MaxDepth(1)]
    private $offers;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    #[Groups(["get-with-images", "post", "get"])]
    private $phoneNumber;


    public function __construct()
    {
        $this->postsRemovals = new ArrayCollection([]);
        $this->postsGarbages = new ArrayCollection([]);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRetypedPassword()
    {
        return $this->retypedPassword;
    }

    /**
     * @param mixed $retypedPassword
     */
    public function setRetypedPassword($retypedPassword): void
    {
        $this->retypedPassword = $retypedPassword;
    }


    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getPostsRemovals(): Collection
    {
        return $this->postsRemovals;
    }

    /**
     * @return Collection
     */
    public function getPostsGarbages(): Collection
    {
        return $this->postsGarbages;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials()
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->getUsername();
    }

    public function getOffers()
    {
        return $this->offers;
    }

    public function setOffers($offers): self
    {
        $this->offers = $offers;
        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber($phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }
}
