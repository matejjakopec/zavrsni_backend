<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Entity\PostGarbage;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class OfferController extends BaseController
{

    /**
     * @Route("api/offer/create", methods={"POST"})
     */
    public function createOffer(Request $request, ManagerRegistry $doctrine, Security $security):Response{
        $postId = $request->get("post");
        $price = $request->get("price");
        $message = $request->get("message");

        if(!$postId || !$price || !$message){
            return $this->respondWithFailure("Insufficient data", 400);
        }

        $post = $doctrine->getRepository(PostGarbage::class)->findBy(['id' => $postId]);

        $offer = new Offer();

        $offer->setPost($post[0])
            ->setPrice($price)
            ->setMessage($message)
            ->setPublished(new \DateTime())
            ->setAuthor($security->getUser());

        $entityManager = $doctrine->getManager();
        $entityManager->persist($offer);

        $entityManager->flush();

        return $this->respondWithSuccess(Mapper::getOffer($offer));
    }

    /**
     * @Route("api/offer/{id}", methods={"GET"})
     */

    public function getOffer(int $id, ManagerRegistry $doctrine, Security $security){
        $user = $security->getUser();
        $offer = $doctrine->getRepository(Offer::class)->findOneBy(['id' => $id]);

        if($user != $offer->getAuthor()){
            return $this->respondWithFailure("Don't have permission to access this data", 400);
        }

        return $this->respondWithSuccess(Mapper::getOffer($offer));
    }

}