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


use Thruway\Peer\Router;
use Thruway\Transport\RatchetTransportProvider;

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


        // $router = new Router();
        // $transportProvider = new RatchetTransportProvider("127.0.0.1", 8090);
        // $router->addTransportProvider($transportProvider);
        // $router->start();
        $router = new Router();
        $realm = "realm1";

        $router->addInternalClient(new Chat($realm, $router->getLoop()));
        $router->addTransportProvider(new RatchetTransportProvider("127.0.0.1", 8090));
        $router->start();
    }
}