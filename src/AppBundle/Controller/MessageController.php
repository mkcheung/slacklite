<?php
/**
 * Created by PhpStorm.
 * User: marscheung
 * Date: 4/11/17
 * Time: 8:21 PM
 */

namespace AppBundle\Controller;

use AppBundle\Forms\Users\UserType;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;


class MessageController extends Controller
{

    public function indexAction()
    {
        $messageServices = $this->get('app.message_service');
        $messages = $messageServices->getMessages();

        if(empty($messages)){
            throw $this->createNotFoundException('No messages available');
        }


        foreach($messages as $message){
            $data['messages'][] = [
                'message' => $message->getMessage(),
                'createdAt' => $message->getCreatedAt(),
                'modifiedAt' => $message->getModifiedAt()
            ];
        }

        $response = new JsonResponse($data, 200);
        
        return $response;
    }

    public function getMessagesInChannelAction(Request $request)
    {
        $messageServices = $this->get('app.message_service');
        $response = $messageServices->getChannelMessages($request);

        if(empty($response)){
            throw $this->createNotFoundException('No messages in channel.');
        }

        return $response;
    }
    public function createAction(Request $request)
    {

        $messageServices = $this->get('app.message_service');
        $message = $messageServices->createMessage($request);

        return new Response('Message created!', 201);
    }

}