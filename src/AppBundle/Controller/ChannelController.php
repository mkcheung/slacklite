<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ChannelController extends Controller
{

    public function indexAction()
    {
    	$channelService = $this->get('app.channel_service');
    	$allChannels = $channelService->getChannels();

    	if(empty($allChannels)){
    		throw new NotFoundHttpException('No channels found');
    	}

    	foreach($allChannels as $channel) {
    		$data['channels'][] = [
    			'id' => $channel->getId(),
    			'channelName' => $channel->getChannelName()
    		];
    	}

    	return new JsonResponse($data, 200);
    }

    public function createAction(Request $request){

        $channelService = $this->get('app.channel_service');
        $channel = $channelService->createChannel($request);

        return new Response('Channel '.$channel->getChannelName().' created!', 201);
    }

    public function getChannelAction(Request $request){

        $channelService = $this->get('app.channel_service');
        $channelData = $channelService->getChannel($request);

        return new JsonResponse($channelData, 200);
    }

}
