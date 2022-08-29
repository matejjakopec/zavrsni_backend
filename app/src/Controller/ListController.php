<?php

namespace App\Controller;

use App\Entity\PostGarbage;
use App\Entity\User;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

class ListController
{
    private string $class;

    private ManagerRegistry $doctrine;

    public function __construct(string $class, ManagerRegistry $doctrine)
    {
        $this->class = $class;
        $this->doctrine = $doctrine;
    }

    public function getData(int $page = 1,int $perPage = 30, $sortBy = 'published', $sortOrder = 'ASC')
    {
        $results = $this->doctrine->getRepository($this->class);
        $query = $results->createQueryBuilder('u')
            ->orderBy('u.'.$sortBy, $sortOrder)
            ->getQuery();

        $paginator = new Paginator($query);
        $paginator
            ->getQuery()
            ->setFirstResult($perPage * ($page-1))
            ->setMaxResults($perPage);

        return $paginator;
    }

    public function getOfferByPost(int $page = 1,int $perPage = 30, $sortBy = 'published', $sortOrder = 'ASC', PostGarbage $post){
        $results = $this->doctrine->getRepository($this->class);
        $query = $results->createQueryBuilder('u')
            ->where("u.post = :post")
            ->orderBy('u.'.$sortBy, $sortOrder)
            ->setParameter("post", $post)
            ->getQuery();

        $paginator = new Paginator($query);
        $paginator
            ->getQuery()
            ->setFirstResult($perPage * ($page-1))
            ->setMaxResults($perPage);

        return $paginator;
    }

    public function getPostsByAuthor(int $page = 1,int $perPage = 30, $sortBy = 'published', $sortOrder = 'ASC', UserInterface $author){
        $results = $this->doctrine->getRepository($this->class);
        $query = $results->createQueryBuilder('u')
            ->where("u.author = :author")
            ->orderBy('u.'.$sortBy, $sortOrder)
            ->setParameter("author", $author)
            ->getQuery();

        $paginator = new Paginator($query);
        $paginator
            ->getQuery()
            ->setFirstResult($perPage * ($page-1))
            ->setMaxResults($perPage);

        return $paginator;
    }

}