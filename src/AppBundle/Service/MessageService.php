<?php
namespace AppBundle\Service;

use AppBundle\Entity\Message;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Created by PhpStorm.
 * User: marscheung
 * Date: 4/11/17
 * Time: 8:29 PM
 */
class MessageService
{

    protected $em;
    protected $messageRepo;

    public function __construct(
        EntityManager $entityManager,
        EntityRepository $messageRepository,
        EntityRepository $userRepository
    ) {
        $this->em       = $entityManager;
        $this->messageRepo = $messageRepository;
        $this->userRepo = $userRepository;
    }

    public function getMessages()
    {
        return $this->messageRepo->findAll();
    }

    public function createMessage(Request $request)
    {

        $messageData = json_decode($request->getContent(), true);

        $message = new Message();
        $createdByUser = $this->userRepo->findOneBy(['id' => $messageData['created_by_id']]);

        if(empty($createdByUser)){
            throw new NotFoundHttpException('Sending User does not exist.');
        }

        $sentTo = $this->userRepo->findOneBy(['id' => $messageData['user_id']]);

        if(empty($sentTo)){
            throw new NotFoundHttpException('Recipient User does not exist.');
        }

        $message->setMessage($messageData['message']);
        $message->setCreatedBy($createdByUser);
        $message->setUser($sentTo);
        $this->em->persist($message);
        $this->em->flush();
        return $message;
    }
}