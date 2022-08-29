<?php

namespace App\Controller;


use App\Entity\PostGarbage;
use App\Entity\PostRemoval;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends BaseController
{
    private $security;

    private $passwordHasher;

    public function __construct(Security $security, UserPasswordHasherInterface $passwordHasher)
    {
        $this->security = $security;
        $this->passwordHasher = $passwordHasher;
    }
    /**
     * @Route("api/users/{id}", methods={"GET"})
     */
    public function getUserInfo(int $id, ManagerRegistry $doctrine):Response{
        $user = $doctrine->getRepository(User::class)->findBy(['id' => $id]);

        if(!$user){
            return $this->respondWithFailure('Not Found', 404);
        }

        return $this->respondWithSuccess(Mapper::getUser($user[0]));
    }

    /**
     * @Route("api/users/create", methods={"POST"})
     */
    public function createUser(Request $request, ManagerRegistry $doctrine, ValidatorInterface $validator):Response{
        $username = $request->get('username');
        $password = $request->get('password');
        $retypedPassword = $request->get('retypedPassword');
        $phoneNumber = $request->get('phoneNumber');
        $email = $request->get('email');

        if(!$username || !$password || !$retypedPassword || !$phoneNumber || !$email){
            return $this->respondWithFailure("Insufficient data", 400);
        }

        if(!$this->validateEmail($email, $validator)){
            return $this->respondWithFailure("Email is not proper", 400);
        }

        if($password != $retypedPassword){
            return $this->respondWithFailure('Passwords do not match', 400);
        }

        if($this->emailExists($email, $doctrine)){
            return $this->respondWithFailure("Email already exists", 400);
        }
        if($this->usernameExists($username, $doctrine)){
            return $this->respondWithFailure("Username already exists", 400);
        }


        $user = new User();
        $user->setUsername($username)
            ->setPhoneNumber($phoneNumber)
            ->setEmail($email)
            ->setPassword($this->passwordHasher->hashPassword($user, $password));


        $entityManager = $doctrine->getManager();
        $entityManager->persist($user);

        $entityManager->flush();

        return $this->respondWithSuccess(Mapper::getUser($user));

    }

    public function validateEmail($email,ValidatorInterface $validator){
        $constraints = array(
            new Email(),
            new NotBlank()
        );

        $error = $validator->validate($email, $constraints);

        if (count($error) > 0) {
            return false;
        }
        return true;
    }

    public function emailExists($email, $doctrine){
        $user = $doctrine->getRepository(User::class)->findBy(['email' => $email]);
        if($user){
            return true;
        }
        return false;
    }

    public function usernameExists($username, $doctrine){
        $user = $doctrine->getRepository(User::class)->findBy(['username' => $username]);
        if($user){
            return true;
        }
        return false;
    }

    /**
     * @Route("api/users/{id}/removal", methods={"GET"})
     */

    public function getUserRemovalPosts(int $id, Request $request, ManagerRegistry $doctrine){
        $listController = new ListController(PostRemoval::class, $doctrine);
        $user = $doctrine->getRepository(User::class)->findOneBy(['id' => $id]);

        $page = $request->query->get('page') == null ? 1 : $request->query->get('page');
        $perPage = $request->query->get('perPage') == null ? 5 : $request->query->get('perPage');
        $orderBy = $request->query->get('orderBy') == null ? 'published' : $request->query->get('orderBy');
        $sortOrder = $request->query->get('sortOrder') == null ? 'ASC' : $request->query->get('sortOrder');
        $data = $listController->getPostsByAuthor($page, $perPage, $orderBy, $sortOrder, $user);
        return $this->respondWithSuccess(Mapper::getGarbageList($data));
    }

    /**
     * @Route("api/users/{id}/garbage", methods={"GET"})
     */

    public function getUserGarbagePosts(int $id, Request $request, ManagerRegistry $doctrine){
        $listController = new ListController(PostGarbage::class, $doctrine);
        $user = $doctrine->getRepository(User::class)->findOneBy(['id' => $id]);

        $page = $request->query->get('page') == null ? 1 : $request->query->get('page');
        $perPage = $request->query->get('perPage') == null ? 5 : $request->query->get('perPage');
        $orderBy = $request->query->get('orderBy') == null ? 'published' : $request->query->get('orderBy');
        $sortOrder = $request->query->get('sortOrder') == null ? 'ASC' : $request->query->get('sortOrder');
        $data = $listController->getPostsByAuthor($page, $perPage, $orderBy, $sortOrder, $user);
        return $this->respondWithSuccess(Mapper::getGarbageList($data));
    }
}