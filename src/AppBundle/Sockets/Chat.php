<?php

// Change the namespace according to your bundle, and that's all !
namespace AppBundle\Sockets;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use React\EventLoop;
use React\EventLoop\StreamSelectLoop;


class Chat implements WampServerInterface {

    protected $accessedChannels = [];

    /**
     * @var \React\EventLoop\LoopInterface
     */
    private $loop;

    /**
     * @var array List of connected clients
     */
    private $clients;

    /**
     * Pass in the react event loop here
     */
    public function __construct(StreamSelectLoop $loop)
    {
        $this->loop = $loop;
    }

    public function onSubscribe(ConnectionInterface $conn, $subscription) {
        
        echo 'inside onsubscribe';

        // This is the JSON passed in from your JavaScript
        // Obviously you need to validate it's JSON and expected data etc...
        $data = json_decode($subscription)->getId();

        // Validate the users id and token together against the db values

        // Now, let's subscribe this user only
        // 5 = the interval, in seconds
        $timer = $this->loop->addPeriodicTimer(5, function() use ($subscription) {
            $data = "whatever data you want to broadcast";
            return $subscription->broadcast(json_encode($data));
        });

        // Store the timer against that user's connection resource Id
        $this->clients[$conn->resourceId]['timer'] = $timer;
    }
    public function onUnSubscribe(ConnectionInterface $conn, $topic) {
    }
    public function onOpen(ConnectionInterface $conn) {
        echo "New connection! ({$conn->resourceId})\n";
        $this->clients[$conn->resourceId]['conn'] = $conn;
        print_r(array_keys($this->clients)); // TODO: debugging statement 
    }
    public function onClose(ConnectionInterface $conn) {
    }
    public function onCall(ConnectionInterface $conn, $id, $topic, array $params) {
        // In this application if clients send data it's because the user hacked around in console
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }
    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible) {
        // In this application if clients send data it's because the user hacked around in console
        $conn->close();
    }
    public function onError(ConnectionInterface $conn, \Exception $e) {
    }

    /**
     * @param string JSON'ified string we'll receive from ZeroMQ
     */
    public function onChannelAccess($entry) {
        echo 'IN ON CHANNEL ACCESS';
        // $entryData = json_decode($entry, true);

        // // If the lookup topic object isn't set there is no one to publish to
        // if (!array_key_exists($entryData['category'], $this->accessedChannels)) {
        //     return;
        // }

        // $topic = $this->accessedChannels[$entryData['category']];

        // // re-send the data to all the clients subscribed to that category
        // $topic->broadcast($entryData);
    }

    // protected $clients;
    // protected $container;


    // public function __construct(ContainerInterface $container) {
    //     $this->container = $container;
    //     $this->clients = new \SplObjectStorage;
    // }

    // public function onOpen(ConnectionInterface $conn) {
    //     // Store the new connection to send messages to later
    //     $this->clients->attach($conn);

    //     echo "New connection! ({$conn->resourceId})\n";
    // }

    // public function onMessage(ConnectionInterface $from, $msg) {

    //     $incomingPackage = json_decode($msg,true);
        
    //     $jwt_manager = $this->container->get('lexik_jwt_authentication.jwt_manager');
    //     $userRepo = $this->container->get('repository.user');
    //     $messageService = $this->container->get('app.message_service');

    //     $token = new JWTUserToken();
    //     $token->setRawToken($incomingPackage['authToken']);
    //     $userInfo = $jwt_manager->decode($token);

    //     $user = $userRepo->findOneBy(['user_id' => $userInfo['user_id']]);
    //     if(!$user) {
    //         $from->close();
    //     }
    //     $request = new Request(
    //         $_GET,
    //         $_POST,
    //         [],
    //         $_COOKIE,
    //         $_FILES,
    //         $_SERVER
    //     );
    //     $request->request->set('channel_id', $incomingPackage['channelId']);
    //     $request->request->set('user_id', $user->getId());
    //     $request->request->set('message', $incomingPackage['message']);

    //     $message = $messageService->createMessage($request);

    //     $numRecv = count($this->clients) - 1;
    //     echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
    //         , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

    //     foreach ($this->clients as $client) {
    //         // if ($from !== $client) {
    //         // if ($from === $client) {
    //             $client->send($message);
    //         // }
    //     }
    //     return $message;
    // }

    // public function onClose(ConnectionInterface $conn) {
    //     // The connection is closed, remove it, as we can no longer send it messages
    //     $this->clients->detach($conn);

    //     echo "Connection {$conn->resourceId} has disconnected\n";
    // }

    // public function onError(ConnectionInterface $conn, \Exception $e) {
    //     echo "An error has occurred: {$e->getMessage()}\n";

    //     $conn->close();
    // }
}