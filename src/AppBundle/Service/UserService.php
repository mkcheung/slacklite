<?php
namespace AppBundle\Service;

use AppBundle\Entity\MessageUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Created by PhpStorm.
 * User: marscheung
 * Date: 4/11/17
 * Time: 8:29 PM
 */
class UserService
{

    protected $em;
    protected $userRepo;

    public function __construct(
        EntityManager $entityManager,
        EntityRepository $userRepository
    ) {
        $this->em       = $entityManager;
        $this->userRepo = $userRepository;
    }

    public function getUsers()
    {
        return $this->userRepo->findAll();
    }

    public function createUser(Request $request)
    {

        $userData = json_decode($request->getContent(), true);

        $user = new MessageUser();

        $user->setUsername($userData['username']);
        $user->setFirstName($userData['firstName']);
        $user->setEmail($userData['email']);
        $user->setLastName($userData['lastName']);
        $user->setPassword(password_hash($userData['password'], PASSWORD_DEFAULT));
        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }
}