<?php

namespace GenSys\GenerateBundle\Service\Generator;

use PHPUnit\Framework\MockObject\MockObject;
use function str_replace;

use GenSys\GenerateBundle\Model\BluePrint;
use GenSys\GenerateBundle\Model\BluePrint\TestMethod;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use ReflectionMethod;
use GenSys\GenerateBundle\Factory\MockDependencyFactory;
use ReflectionClass;
use ReflectionException;
use Nette\PhpGenerator\PhpNamespace;
use Symfony\Component\Filesystem\Filesystem;

class NetteGenerator implements GeneratorStrategy
{
    const TEST_FOLDER_PATH = __DIR__ . '/../../Tests/Unit/';
    const EXTEND_TESTCASE = 'PHPUnit\Framework\TestCase';

    /** @var MockDependencyFactory */
    private $mockDependencyFactory;

    /**
     * NetteGenerator constructor.
     */
    public function __construct()
    {
        $this->mockDependencyFactory = new MockDependencyFactory();
    }

    /**
     * @param string $originalClass
     * @throws ReflectionException
     */
    public function createTest(string $originalClass)
    {
        $bluePrint = new BluePrint($originalClass);
        $bluePrint->addUse(TestCase::class);
        $bluePrint->addUse(MockObject::class);
        $bluePrint->setExtend(TestCase::class);

        $reflectionClass = new ReflectionClass($originalClass);
        $mockDependencies = $this->mockDependencyFactory->createFromReflectionClass($reflectionClass);
        $bluePrint->addMockDependencies($mockDependencies);

        $this->addTestMethods($reflectionClass, $bluePrint);

        $this->write($bluePrint->getPhpNamespace());
    }

    private function addTestMethods(ReflectionClass $reflectionClass, BluePrint $bluePrint)
    {
        foreach($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $reflectionMethod) {
            if (strpos($reflectionMethod->getName(), '__') !== false) {
                continue;
            }

            $testMethod = new TestMethod($reflectionMethod, $bluePrint);
            $bluePrint->addTestMethod($testMethod);
        }
    }

    /**
     * @param PhpNamespace $phpNamespace
     */
    private function write(PhpNamespace $phpNamespace)
    {
        $namespace = str_replace('\\', '/', $phpNamespace->getName() . '/');
        $testFolder = self::TEST_FOLDER_PATH . $namespace;

        if (!file_exists($testFolder)) {
            $fileSystem = new Filesystem();
            $fileSystem->mkdir($testFolder);
        }

        foreach ($phpNamespace->getClasses() as $classType) {
            file_put_contents($testFolder . $classType->getName() . '.php', "<?php\n\n" . (string) $phpNamespace);
        }
    }
}