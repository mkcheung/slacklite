<?php
namespace AppBundle\Service;

use AppBundle\Entity\Channel;
use Doctrine\Common\Collections\ArrayCollection;
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
class ChannelService
{

    protected $em;
    protected $channelRepo;
    protected $userRepo;

    public function __construct(
        EntityManager $entityManager,
        EntityRepository $channelRepository,
        EntityRepository $userRepository
    ) {
        $this->em       = $entityManager;
        $this->channelRepo = $channelRepository;
        $this->userRepo = $userRepository;
    }

    public function getChannels()
    {
        return $this->channelRepo->findAll();
    }

    public function createChannel(Request $request)
    {

        $channelData = json_decode($request->getContent(), true);

        $channel = new Channel();
        $channel->setChannelName($channelData['channelName']);

        $usersToAdd = $this->userRepo->findBy(['user_id' => $channelData['messageUsers']]);

        foreach($usersToAdd as $user){

        	$channel->addMessageUser($user);
        }
        
        $this->em->persist($channel);
        $this->em->flush();
        return $channel;
    }
}