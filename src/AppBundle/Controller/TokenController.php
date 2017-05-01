<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class TokenController extends Controller {

	public function createAction(Request $request){

		$data = json_decode($request->getContent(), true);

		$user = $this->getDoctrine()->getRepository('AppBundle:MessageUser')->findOneBy(
				[
					'username'=>$data['username']
				]
			);

		if(empty($user)){
			throw new NotFoundHttpException('User not found.');
		}

		$isValidUser = $this->get('security.password_encoder')->isPasswordValid($user, $data['password']);

		if(!$isValidUser){
			throw new BadCredentialsException('Incorrect password. Please try again.');
		}

		$userToken = $this->get('lexik_jwt_authentication.encoder')
			->encode(
				[
					'username' => $data['username'],
					'roles' => $user->getRoles()
				]
			);

		return new JsonResponse(
			[
				'token' =>$userToken
			]
		);
	}
}