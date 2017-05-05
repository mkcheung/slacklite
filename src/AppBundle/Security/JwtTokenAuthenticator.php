<?php

namespace AppBundle\Security;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Lexik\Bundle\JWTAuthenticationExceptionBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

class JwtTokenAuthenticator extends AbstractGuardAuthenticator
{

	/**
	* @var JWTEncoderInterface
	*/
	private $jwtEncoder;

	/**
	* @var EntityManager
	*/
	private $entityManager;

	public function __construct(JWTEncoderInterface $jwtEncoder, EntityManager $em){
		$this->jwtEncoder = $jwtEncoder;
		$this->entityManager = $em;
	}

    public function getCredentials(Request $request){

    	$extractor = new AuthorizationHeaderTokenExtractor(
    		'',
    		'Authorization'
    	);

    	$token = $extractor->extract($request);
    	if(empty($token)){
    		return null;
    	}
    }


    public function getUser($credentials, UserProviderInterface $userProvider){
    	try {
    		$data = $this->jwtEncoder->decode($credentials);
    	} catch (JWTDecodeFailureException $e) {
    		throw new CustomUserMessageAuthenticationException('Invalid Token');
    	}

    	$user = $this->em->getRepository('AppBundle:MessageUser')->findOneBy(['username' => $data['username']]);
    	return $user;
    }


    public function checkCredentials($credentials, UserInterface $user){
    	return true;
    }

    public function createAuthenticatedToken(UserInterface $user, $providerKey){

    }


    public function onAuthenticationFailure(Request $request, AuthenticationException $exception){

    }


    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey){

    }


    public function supportsRememberMe(){
    	return false;
    }

    public function start(Request $request, AuthenticationException $authException = null){
    	return new JsonResponse([
    		'Error! ' . empty($authException) ? $authException->getMessage().': '.$authException->getMessageKey() : 'Authorization Required.'
    	],401);
    }

}