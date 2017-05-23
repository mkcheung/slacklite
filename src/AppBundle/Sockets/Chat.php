<?php

// Change the namespace according to your bundle, and that's all !
namespace AppBundle\Sockets;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class Chat implements MessageComponentInterface {

    protected $clients;
    protected $container;


    public function __construct(ContainerInterface $container) {
        $this->container = $container;
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    // public function onMessage(ConnectionInterface $from, $msg) {
    //     $numRecv = count($this->clients) - 1;
    //     echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
    //         , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

    //     foreach ($this->clients as $client) {
    //         if ($from !== $client) {
    //             // The sender is not the receiver, send to each client connected
    //             $client->send($msg);
    //         }
    //     }
    // }

    public function onMessage(ConnectionInterface $from, $msg) {

        $incomingPackage = json_decode($msg,true);
        
        $jwt_manager = $this->container->get('lexik_jwt_authentication.jwt_manager');
        $userRepo = $this->container->get('repository.user');
        $messageService = $this->container->get('app.message_service');

        $token = new JWTUserToken();
        $token->setRawToken($incomingPackage['authToken']);
        $userInfo = $jwt_manager->decode($token);

        $user = $userRepo->findOneBy(['user_id' => $userInfo['user_id']]);
        if(!$user) {
            $from->close();
        }
        $request = new Request(
            $_GET,
            $_POST,
            [],
            $_COOKIE,
            $_FILES,
            $_SERVER
        );
        $request->request->set('channel_id', $incomingPackage['channelId']);
        $request->request->set('user_id', $user->getId());
        $request->request->set('message', $incomingPackage['message']);

        $message = $messageService->createMessage($request);

        $all_connections = $this->connections;
        foreach($all_connections as $key => $conn){
            if($conn === $from){
                $this->connections[$index_connection] = $from;
                $from->send('..:: Connected as '.$index_connection.'  ::..');
                unset($this->connections[$key]);
                break;
            } else {
                continue;
            }

        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}