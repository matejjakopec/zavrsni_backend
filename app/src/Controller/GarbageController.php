<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Offer;
use App\Entity\PostGarbage;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class GarbageController extends BaseController
{
    /**
     * @Route("api/garbage/list", methods={"GET"})
     */
    public function getGarbageList(Request $request, ManagerRegistry $doctrine):Response
    {
        $listController = new ListController(PostGarbage::class, $doctrine);
        $page = $request->query->get('page') == null ? 1 : $request->query->get('page');
        $perPage = $request->query->get('perPage') == null ? 30 : $request->query->get('perPage');
        $orderBy = $request->query->get('orderBy') == null ? 'published' : $request->query->get('orderBy');
        $sortOrder = $request->query->get('sortOrder') == null ? 'ASC' : $request->query->get('sortOrder');
        $data = $listController->getData($page, $perPage, $orderBy, $sortOrder);
        return $this->respondWithSuccess(Mapper::getGarbageList($data));
    }

    /**
     * @Route("api/garbage/{id}", methods={"GET"})
     */
    public function getGarbagePost(int $id, ManagerRegistry $doctrine): Response{
        $garbagePost = $doctrine->getRepository(PostGarbage::class)->findBy(['id' => $id]);
        return $this->respondWithSuccess(Mapper::getGarbagePost($garbagePost[0]));
    }

    /**
     * @Route("api/garbage/create", methods={"POST"})
     */
    public function createGarbagePost(Request $request, ManagerRegistry $doctrine, Security $security):Response{
        if(!$security->getUser()){
            return $this->respondWithFailure('You must be logged in to perform this action', 400);
        }

        $title = $request->get('title');
        $content = $request->get('content');
        $location = $request->get('location');
        $images = $request->get('images');

        if(!$title || !$content || !$location){
            return $this->respondWithFailure('Insuficient data', 400);
        }

        $garbagePost = new PostGarbage();
        $garbagePost->setTitle($title)
                    ->setContent($content)
                    ->setLocation($location)
                    ->setPublished(new \DateTime())
                    ->setAuthor($security->getUser());

        if($images){
            foreach ($images as $id){
                $image = $doctrine->getRepository(Image::class)->findBy(['id' => $id]);
                $garbagePost->addImage($image[0]);
            }
        }

        $entityManager = $doctrine->getManager();
        $entityManager->persist($garbagePost);

        $entityManager->flush();

        return $this->respondWithSuccess(Mapper::getGarbagePost($garbagePost));
    }

    /**
     * @Route("api/garbage/{id}/offers", methods={"GET"})
     */

    public function getOffers(int $id, ManagerRegistry $doctrine, Security $security, Request $request){
        $user = $security->getUser();
        $post = $doctrine->getRepository(PostGarbage::class)->findOneBy(['id' => $id]);

        if($user != $post->getAuthor()){
            return $this->respondWithFailure("Don't have permission to access this data", 400);
        }

        $listController = new ListController(Offer::class, $doctrine);
        $page = $request->query->get('page') == null ? 1 : $request->query->get('page');
        $perPage = $request->query->get('perPage') == null ? 10 : $request->query->get('perPage');
        $orderBy = $request->query->get('orderBy') == null ? 'published' : $request->query->get('orderBy');
        $sortOrder = $request->query->get('sortOrder') == null ? 'ASC' : $request->query->get('sortOrder');
        $data = $listController->getOfferByPost($page, $perPage, $orderBy, $sortOrder, $post);

        return $this->respondWithSuccess(Mapper::getOfferList($data));
    }
}