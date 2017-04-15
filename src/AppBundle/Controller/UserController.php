<?php
/**
 * Created by PhpStorm.
 * User: marscheung
 * Date: 4/11/17
 * Time: 8:21 PM
 */

namespace AppBundle\Controller;

use AppBundle\Forms\Users\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{

    public function indexAction()
    {
        $userServices = $this->get('app.user_service');
        $users = $userServices->getUsers();
        return $this->render('AppBundle:User:user_index.html.twig', [
            'title' => 'Users',
            'users' => $users,
        ]);
    }

    public function createAction(Request $request)
    {

        $roleServices = $this->get('app.user_service');
        $roleServices->createUser($request);

        return new Response('User created!');
    }

}