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


// use Thruway\Event\ConnectionCloseEvent;
// use Thruway\Event\ConnectionOpenEvent;
// use Thruway\Event\RouterStartEvent;
// use Thruway\Event\RouterStopEvent;
// use Thruway\Exception\DeserializationException;
// use Thruway\Logging\Logger;
// use Thruway\Message\HelloMessage;
// use Thruway\Serializer\JsonSerializer;

use \Thruway\Transport\TransportInterface;
use \Thruway\Message\Message;
use React\EventLoop\Factory;
use React\ZMQ\Context;
use React\Socket\Server as SocketServer;
use ZMQ;

use Thruway\Peer\Client as Client;
class Chat extends Client
{
    protected $sessions = [];

    public function onSessionStart($session, $transport)
    {

        // 1) subscribe to a topic
        $onevent = function ($args) {
            echo "Event {$args[0]}\n";
        };
        $session->subscribe('com.singular.channel', $onevent);
        // 2) publish an event
        $session->publish('com.singular.channel', ['Hello, world from PHP!!!'], [], ["acknowledge" => true])->then(
            function () {
                echo "Publish Acknowledged!\n";
            },
            function ($error) {
                // publish failed
                echo "Publish Error {$error}\n";
            }
        );
    }


    public function onMessage(TransportInterface $transport, Message $msg) {
        var_dump($msg);
        if ($msg instanceof PublishMessage) {
            if ($msg->getTopicName() == "mytopic") {
                // mangle the message in here
            }
        }

        parent::onMessage($transport, $msg);
    }
}

//     /**
//      * @param string JSON'ified string we'll receive from ZeroMQ
//      */
//     public function onBlogEntry($entry)
//     {
// echo '----------------------------ONBLOGENTRY$entry--------------------------------';
//         $entryData = json_decode($entry, true);

//         if (!isset($entryData['category'])) {
//             return;
//         }

//         $this->getSession()->publish($entryData['category'], [$entryData]);

//     }

// }
