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
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{

    public function indexAction()
    {
        $roleServices = $this->get('app.role_service');
        $roles = $roleServices->getRoles();
        return $this->render('AppBundle:Role:role_index.html.twig', array(
            'title' => 'Roles',
            'roles' => $roles
        ));
    }

    public function createAction(Request $request)
    {

        $roleServices = $this->get('app.role_service');
        $roleServices->createRole($request);

        return new Response('Role created!');
    }

}