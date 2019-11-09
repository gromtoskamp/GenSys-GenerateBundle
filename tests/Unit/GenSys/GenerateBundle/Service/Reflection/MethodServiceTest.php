<?php

namespace Tests\Unit\GenSys\GenerateBundle\Service\Reflection;

use GenSys\GenerateBundle\Resources\Dummy\Service\DummyServiceWithDependency;
use GenSys\GenerateBundle\Resources\Dummy\Service\TestCaseMethods;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use GenSys\GenerateBundle\Service\Reflection\MethodService;
use PHPUnit\Util\Json;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class MethodServiceTest extends TestCase
{
    /** @var ReflectionMethod|MockObject */
    public $reflectionMethod;
    /** @var Json */
    private $bodyResults;
    /** @var Json */
    private $internalCalls;
    /** @var array */
    private $propertyCalls;
    /** @var array */
    private $variableCalls;
    /** @var array */
    private $parameterCalls;
    /** @var array */
    private $propertyAssignments;

    public function setUp(): void
    {
        $this->bodyResults = $this->getJsonAsset('getBody');
        $this->propertyCalls = $this->getJsonAsset('getPropertyCalls');
    }

    /**
     * @dataProvider getDummyServiceWithDependencyMethods
     * @param ReflectionMethod $reflectionMethod
     */
    public function testGetInternalCalls(ReflectionMethod $reflectionMethod): void
    {
        $this->internalCalls = $this->getJsonAsset('getInternalCalls');
        $fixture = new MethodService();
        $result = $fixture->getInternalCalls($reflectionMethod);

        $this->assertSame(
            json_encode($this->internalCalls[$reflectionMethod->getName()]),
            json_encode($result)
        );
    }

    /**
     * @dataProvider getDummyServiceWithDependencyMethods
     * @param ReflectionMethod $reflectionMethod
     */
    public function testGetPropertyCalls(ReflectionMethod $reflectionMethod): void
    {
        $fixture = new MethodService();
        try {
            $result = $fixture->getPropertyCalls($reflectionMethod);
        } catch (ReflectionException $e) {
            $this->fail($e->getMessage());
        }

        $this->assertArrayHasKey($reflectionMethod->getName(), $this->propertyCalls);
        $this->assertSame(
            json_encode($this->propertyCalls[$reflectionMethod->getName()]),
            json_encode($result)
        );
    }

    /**
     * @throws ReflectionException
     */
    public function testFetchPrivateProperty(): void
    {
        $fixture = new MethodService();
        $reflectionClass = new ReflectionClass(DummyServiceWithDependency::class);
        $this->assertTrue($reflectionClass->hasMethod('addToProperty'));
        $method = $reflectionClass->getMethod('addToProperty');

        $result = $fixture->getPropertyCalls($method);
        $this->assertSame('dummyObjectA', $result[0]->var->name->name);
        $this->assertSame('getDummyValue', $result[0]->name->name);
    }

    /**
     * @dataProvider getDummyServiceWithDependencyMethods
     * @param ReflectionMethod $reflectionMethod
     */
    public function testGetVariableCalls(ReflectionMethod $reflectionMethod): void
    {
        $this->variableCalls = $this->getJsonAsset('getVariableCalls');
        $fixture = new MethodService();
        $result = $fixture->getVariableCalls($reflectionMethod);

        $this->assertSame(
            json_encode($this->variableCalls[$reflectionMethod->getName()]),
            json_encode($result)
        );
    }

    /**
     * @dataProvider getDummyServiceWithDependencyMethods
     * @param ReflectionMethod $reflectionMethod
     * @throws ReflectionException
     */
    public function testGetParameterCalls(ReflectionMethod $reflectionMethod): void
    {
        $this->parameterCalls = $this->getJsonAsset('getParameterCalls');
        $fixture = new MethodService();
        $result = $fixture->getParameterCalls($reflectionMethod);

        $this->assertSame(
            json_encode($this->parameterCalls[$reflectionMethod->getName()]),
            json_encode($result)
        );
    }

    /**
     * @dataProvider getDummyServiceWithDependencyMethods
     * @param ReflectionMethod $reflectionMethod
     */
    public function testGetBody(ReflectionMethod $reflectionMethod): void
    {
        $fixture = new MethodService();
        $result = $fixture->getBody($reflectionMethod);

        $name = $reflectionMethod->getName();
        $this->assertNotNull(
            $bodyResult = $this->bodyResults[$name]
        );

        $this->assertSame(
            $this->stripWhitespace($result),
            $this->stripWhitespace($bodyResult)
        );
    }

    /**
     * @dataProvider getDummyServiceWithDependencyMethods
     * @param ReflectionMethod $reflectionMethod
     */
    public function testGetPropertyAssignments(ReflectionMethod $reflectionMethod): void
    {
        $this->propertyAssignments = $this->getJsonAsset('getPropertyAssignments');
        $fixture = new MethodService();
        $result = $fixture->getPropertyAssignments($reflectionMethod);

        $name = $reflectionMethod->getName();
        $this->assertNotNull(
            $bodyResult = $this->propertyAssignments[$name]
        );

        $this->assertSame(
            json_encode($result),
            json_encode($bodyResult)
        );
    }

    /**
     * @throws ReflectionException
     */
    public function testBracketsDontMessAnythingUp()
    {
        $reflectionClass = new ReflectionClass(TestCaseMethods::class);

        $this->assertTrue($reflectionClass->hasMethod('getByReflectionMethod'));

        $method = $reflectionClass->getMethod('getByReflectionMethod');
        $fixture = new MethodService();

        $result = $fixture->getParameterCalls($method);
        $this->assertSame(
            'getName',
            $result[0]->name->name
        );
    }

    /**
     * @return ReflectionMethod[]
     * @throws ReflectionException
     */
    public function getDummyServiceWithDependencyMethods(): array
    {
        $reflectionClass = new ReflectionClass(DummyServiceWithDependency::class);
        $methods = [];
        foreach ($reflectionClass->getMethods() as $method) {
            $methods[] = [$method];
        }

        return $methods;
    }

    /**
     * @param $string
     * @return string
     */
    private function stripWhitespace($string): string
    {
        return preg_replace('/\s/', '', $string);
    }

    /**
     * @param $name
     * @return array
     */
    private function getJsonAsset($name): array
    {
        $contents = file_get_contents(__DIR__ . '/assets/' . $name . '.json');
        return json_decode($contents, true);
    }
}
