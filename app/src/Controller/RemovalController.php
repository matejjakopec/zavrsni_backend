<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\PostGarbage;
use App\Entity\PostRemoval;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class RemovalController extends BaseController
{
    /**
     * @Route("api/removal/list", methods={"GET"})
     */
    public function getGarbageList(Request $request, ManagerRegistry $doctrine):Response
    {
        $listController = new ListController(PostRemoval::class, $doctrine);
        $page = $request->query->get('page') == null ? 1 : $request->query->get('page');
        $perPage = $request->query->get('perPage') == null ? 30 : $request->query->get('perPage');
        $orderBy = $request->query->get('orderBy') == null ? 'published' : $request->query->get('orderBy');
        $sortOrder = $request->query->get('sortOrder') == null ? 'ASC' : $request->query->get('sortOrder');
        $data = $listController->getData($page, $perPage, $orderBy, $sortOrder);
        return $this->respondWithSuccess(Mapper::getGarbageList($data));
    }

    /**
     * @Route("api/removal/{id}", methods={"GET"})
     */
    public function getGarbagePost(int $id, ManagerRegistry $doctrine): Response{
        $garbagePost = $doctrine->getRepository(PostRemoval::class)->findBy(['id' => $id]);
        return $this->respondWithSuccess(Mapper::getGarbagePost($garbagePost[0]));
    }

    /**
     * @Route("api/removal/create", methods={"POST"})
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

        $garbagePost = new PostRemoval();
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
}