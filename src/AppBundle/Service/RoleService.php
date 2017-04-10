<?php
namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
/**
 * Created by PhpStorm.
 * User: marscheung
 * Date: 4/9/17
 * Time: 3:27 PM
 */
class RoleService
{

    protected $em;
    protected $roleRepo;

    public function __construct(
        EntityManager $entityManager,
        EntityRepository $entityRepository
    ) {
        $this->em = $entityManager;
        $this->roleRepo = $entityRepository;
    }

    public function getRoles()
    {
       return $this->roleRepo->findAll();
    }

    public function createRole(Request $request)
    {


    }
}