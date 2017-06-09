<?php
// myapplication/src/AppBundle/Command/SocketCommand.php
// Change the namespace according to your bundle

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


//include React and ZMQ modules
use React\EventLoop\Factory;
use React\ZMQ\Context;
use React\Socket\Server as SocketServer;
use ZMQ;

// Include ratchet libs
use Ratchet\Server\IoServer as RatchetIoServer;
use Ratchet\Http\HttpServer as RatchetHttpServer;
use Ratchet\WebSocket\WsServer as RatchetWsServer;
use Ratchet\Wamp\WampServer as RatchetWampServer;

// Change the namespace according to your bundle
use AppBundle\Sockets\Chat;

class SocketCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('sockets:start-chat')
            // the short description shown while running "php bin/console list"
            ->setHelp("Starts the chat socket demo")
            // the full command description shown when running the command with
            ->setDescription('Starts the chat socket demo')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $loop   = Factory::create();
        $pusher = new Chat($loop);

        // Listen for the web server to make a ZeroMQ push after an ajax request
        $context = new Context($loop);
        $pull = $context->getSocket(ZMQ::SOCKET_PULL);
        $pull->bind('tcp://127.0.0.1:5555'); // Binding to 127.0.0.1 means the only client that can connect is itself
        $pull->on('message', array($pusher, 'onChannelAccess'));

        $output->writeln([
            'Chat socket',// A line
            '============',// Another line
            'Starting chat, open your browser.',// Empty line
        ]);


        // Set up our WebSocket server for clients wanting real-time updates
        $webSock = new SocketServer($loop);
        $webSock->listen(8090, '0.0.0.0'); // Binding to 0.0.0.0 means remotes can connect
        $webServer = new RatchetIoServer(
            new RatchetHttpServer(
                new RatchetWsServer(
                    new RatchetWampServer(
                        $pusher
                    )
                )
            ),
            $webSock
        );

        $loop->run();
    }
}