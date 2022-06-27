<?php

namespace App\Entity;


use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\UploadImageAction;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[Uploadable]
#[ApiResource(
    collectionOperations: [
        "get"=>[
            "normalization_context"=>["groups"=>["get-with-images"]]
        ],
        "post"=>[
            "method" =>"POST",
            "path"=>"/images",
            "controller"=>UploadImageAction::class,
            "defaults"=>["_api_receive"=>false]
        ],
        "api_post_removals_images_get_subresource"=>[
            "normalization_context"=>["groups"=>["get-with-images"]]
        ]
    ]
)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

   #[UploadableField(mapping: "images", fileNameProperty: "url")]
   #[Assert\NotNull]
   private $file;

   #[ORM\Column(nullable: true)]
   #[Groups(["get-with-images"])]
   private $url;


    public function getId()
    {
        return $this->id;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file): void
    {
        $this->file = $file;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url): void
    {
        $this->url = $url;
    }



}