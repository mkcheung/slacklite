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

        $userData = json_decode($request->getContent(), true);
        $role = $this->roleRepo->findOneBy(['role_id' => $userData['role']]);

        $role = new MessageUser($userData['username'], password_hash($userData['password'], PASSWORD_DEFAULT), $role);
        $this->em->persist($role);
        $this->em->flush();
    }
}