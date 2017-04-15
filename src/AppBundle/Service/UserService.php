<?php
namespace AppBundle\Service;

use AppBundle\Entity\MessageUser;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
    protected $roleRepo;

    public function __construct(
        EntityManager $entityManager,
        EntityRepository $userRepository,
        EntityRepository $roleRepository
    ) {
        $this->em       = $entityManager;
        $this->userRepo = $userRepository;
        $this->roleRepo = $roleRepository;
    }

    public function getUsers()
    {
        return $this->userRepo->findAll();
    }

    public function createUser(Request $request)
    {
        //TODO: Place some validation here to verify everything needed
        $requestParameters = $request->request->all();

        $role = $this->roleRepo->findOneBy(['role_id' => $requestParameters['role']]);

        $user              = new MessageUser($requestParameters['username'],
            password_hash($requestParameters['password'], PASSWORD_DEFAULT), $role);

        $this->em->persist($user);
        $this->em->flush();
    }
}