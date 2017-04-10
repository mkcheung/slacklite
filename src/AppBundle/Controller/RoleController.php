<?php
/**
 * Created by PhpStorm.
 * User: marscheung
 * Date: 4/9/17
 * Time: 3:39 PM
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RoleController extends Controller
{

    /**
     * @Route("/roles")
     */
    public function indexAction()
    {
        return $this->render('role/index.html.twig', array(
            'title' => 'Roles'
        ));
    }

    /**
     * @Route("/roles/create")
     */
    public function createRoleAction(Request $request)
    {

        $roleServices = $this->get('app.role_service');
//
//        $roles = $roleServices->getRoles();
        return $this->render('role/create_user.html.twig', array(
            'title' => 'Create Roles'
        ));
    }

}