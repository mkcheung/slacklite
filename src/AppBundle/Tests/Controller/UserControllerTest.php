<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;
use AppBundle\Controller;
use AppBundle\Service;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Mockery;

class UserControllerTest extends WebTestCase
{
    public function testIndex()
    {
    	$mockUserController = new Controller\UserController(); 
    	$mockContainer = Mockery::mock(Container::class);

    }
}
