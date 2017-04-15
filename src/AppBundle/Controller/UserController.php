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

        if ($request->isMethod('POST')) {
            $userServices = $this->get('app.user_service');
            $userServices->createUser($request);

            $this->redirect('/users');
        }

        $roleServices = $this->get('app.role_service');
        $roles        = $roleServices->getRoles();

        $form = $this->createForm(UserType::class, $roles, [
            'action' => $this->generateUrl('user_create'),
            'method' => 'POST',
        ])->createView();

        return $this->render('AppBundle:User:create_user.html.twig', [
            'title' => 'Create User',
            'form'  => $form,
        ]);
    }

}