<?php

namespace AppBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
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

		$isValidUser = true;

		if(!$isValidUser){
			throw new BadCredentialsException('Incorrect password. Please try again.');
		}

		$userToken = $this->get('lexik_jwt_authentication.encoder')
			->encode(
				[
					'user_id' => $user->getId(),
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