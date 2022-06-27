<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;

class GetOfferController extends AbstractController
{

    private $security;

    public function __construct(Security $security)
    {
        // Avoid calling getUser() in the constructor: auth may not
        // be complete yet. Instead, store the entire Security object.
        $this->security = $security;
    }

    public function __invoke(ManagerRegistry $doctrine): Offer
    {
        $user = $this->security->getUser();
        // usually you'll want to make sure the user is authenticated first,
        // see "Authorization" below
        $offers = $doctrine->getRepository(Offer::class)->findBy(['author' => $user]);
        // returns your User object, or null if the user is not authenticated
        // use inline documentation to tell your editor your exact User class
        // Call whatever methods you've added to your User class
        // For example, if you added a getFirstName() method, you can use that.
        return $offers;
    }

}