<?php
namespace AppBundle\Service;

use AppBundle\Entity\Message;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    protected $userRepo;
    protected $channelRepo;

    public function __construct(
        EntityManager $entityManager,
        EntityRepository $messageRepository,
        EntityRepository $userRepository,
        EntityRepository $channelRepository
    ) {
        $this->em       = $entityManager;
        $this->messageRepo = $messageRepository;
        $this->userRepo = $userRepository;
        $this->channelRepo = $channelRepository;
    }

    public function getMessages()
    {
        return $this->messageRepo->findAll();
    }

    public function getChannelMessages(Request $request)
    {
        $channelData = json_decode($request->getContent(), true);

        $channelMessages = $this->messageRepo->findBy(['channel' => $channelData['channel_id']], ['createdAt' => 'ASC']);

        foreach($channelMessages as $message){
            $sender = $message->getUser();
            $data['messages'][] = [
               'sender' => $sender->getFirstName().' '.$sender->getLastName(),
               'message' => $message->getMessage(),
               'created_at' => $message->getCreatedAt()
            ];
        }

        $response = new JsonResponse($data, 200);

        return $response;
    }

    public function createMessage(Request $request)
    {

        $messageData = json_decode($request->getContent(), true);

        $message = new Message();
        $user = $this->userRepo->findOneBy(['user_id' => $messageData['user_id']]);

        if(empty($user)){
            throw new NotFoundHttpException('Sending User does not exist.');
        }

        $channel = $this->channelRepo->findOneBy(['channel_id' => $messageData['channel_id']]);

        if(empty($channel)){
            throw new NotFoundHttpException('Message channel does not exist.');
        }

        $message->setMessage($messageData['message']);
        $message->setCreatedAt(new \DateTime());
        $message->setUser($user);
        $message->setChannel($channel);
        $this->em->persist($message);
        $this->em->flush();
        return $message;
    }
}