<?php

namespace Tests\Dev;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Container;

class AppTest extends KernelTestCase
{
    /** @var Container */
    private $container;

    public function setUp()
    {
        parent::setUp();
        self::bootKernel();
        $this->container = self::$kernel->getContainer();
    }

    public function testApp()
    {
    }
}