<?php

namespace Tests\Dev;

use GenSys\GenerateBundle\Factory\TestMethodFactory;
use GenSys\GenerateBundle\Resources\Dummy\Service\DummyServiceWithDependency;
use GenSys\GenerateBundle\Service\Reflection\MethodService;
use PhpParser\Error;
use PhpParser\NodeDumper;
use PhpParser\ParserFactory;
use ReflectionClass;
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
        /** @var TestMethodFactory $testMethodFactory */
        $testMethodFactory = $this->container->get(TestMethodFactory::class);
        $reflectionClass = new ReflectionClass(DummyServiceWithDependency::class);

        $methodService = $this->container->get(MethodService::class);


        $result = $methodService->getInternalCalls($reflectionClass->getMethod('privateAddToDummyValue'));
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        try {
            $ast = $parser->parse("<?php\n" . ltrim($methodService->getBody($reflectionClass->getMethod('privateAddToDummyValue'))));
        } catch (Error $error) {
            echo "Parse error: {$error->getMessage()}\n";
            return;
        }

        $dumper = new NodeDumper;
        echo $dumper->dump($ast) . "\n";
        exit;

        $this->assertTrue(true);
    }
}