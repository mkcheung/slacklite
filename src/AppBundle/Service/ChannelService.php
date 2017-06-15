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

    public function getChannel(Request $request)
    {
        $channelData = $request->query->all();

        $channelUsers = $this->userRepo->findBy(['user_id' => explode(',',$channelData['message_user_ids']) ]);
        $queryText  = "SELECT c FROM AppBundle:Channel c ";
        $queryText .= "WHERE c.singular = :singularOrNot ";

        foreach($channelUsers as $channelUser){
            $i = 0;
            $queryText .= "AND :members$i MEMBER OF c.messageUsers ";
            $i++;
        }

        $query = $this->em->createQuery($queryText);

        for($j=0; $j < $i ; $j++){
            $query->setParameter("members$j", $channelUsers[$j]);
        }
        $query->setParameter("singularOrNot", $channelData['singular']);
        
        $channel = $query->getResult();

        $channelUsers = $channel[0]->getMessageUsers();

        foreach($channelUsers as $channelUser){
            $userIds[] = $channelUser->getId();
        } 

        $channelTopic = str_replace(' ', '.', 'com.'.strtolower($channel[0]->getChannelName()));

        $data['channel'][$channel[0]->getId()] = [
            'channelName' => $channel[0]->getChannelName(),
            'channelTopic' => $channelTopic,
            'singular' => $channel[0]->getSingular(),
            'channelUserIds' => $userIds
        ];

        return $data;
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