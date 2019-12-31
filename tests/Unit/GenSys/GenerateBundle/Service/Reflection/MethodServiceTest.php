<?php

namespace Tests\Unit\GenSys\GenerateBundle\Service\Reflection;

use GenSys\GenerateBundle\PhpParser\Filter\MethodCall\InternalCallFilter;
use GenSys\GenerateBundle\PhpParser\Filter\MethodCall\PropertyFetchFilter;
use GenSys\GenerateBundle\PhpParser\Filter\MethodCall\VariableCallFilter;
use GenSys\GenerateBundle\PhpParser\Filter\Node\MethodCallFilter;
use GenSys\GenerateBundle\PhpParser\Filter\Node\PropertyAssignmentFilter;
use GenSys\GenerateBundle\PhpParser\Parse\MethodParser;
use GenSys\GenerateBundle\Resources\Dummy\Service\TestCaseMethods;
use GenSys\GenerateBundle\Service\FileService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use GenSys\GenerateBundle\Service\Reflection\MethodService;
use PHPUnit\Util\Json;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Tests\Providers\DummyServiceWithDependencyProvider;

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
    /** @var FileService */
    private $fileService;
    /** @var MethodCallFilter  */
    private $methodCallFilter;
    /** @var PropertyAssignmentFilter  */
    private $propertyAssignmentFilter;
    /** @var InternalCallFilter  */
    private $internalCallFilter;
    /** @var PropertyFetchFilter  */
    private $propertyFetchFilter;
    /** @var VariableCallFilter  */
    private $variableCallFilter;
    /** @var MethodService */
    private $fixture;
    /** @var MethodParser */
    private $methodParser;
    private $dummyServiceWithDependencyProvider;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->bodyResults = $this->getJsonAsset('getBody');
        $this->propertyCalls = $this->getJsonAsset('getPropertyCalls');
        $this->internalCalls = $this->getJsonAsset('getInternalCalls');
        $this->variableCalls = $this->getJsonAsset('getVariableCalls');
        $this->parameterCalls = $this->getJsonAsset('getParameterCalls');
        $this->propertyAssignments = $this->getJsonAsset('getPropertyAssignments');

        $this->fileService = new FileService();
        $this->methodCallFilter = new MethodCallFilter();
        $this->propertyAssignmentFilter = new PropertyAssignmentFilter();
        $this->internalCallFilter = new InternalCallFilter();
        $this->propertyFetchFilter = new PropertyFetchFilter();
        $this->variableCallFilter = new VariableCallFilter();
        $this->methodParser = new MethodParser(new FileService());

        $this->dummyServiceWithDependencyProvider = new DummyServiceWithDependencyProvider();

        $this->fixture = new MethodService(
            $this->methodCallFilter,
            $this->propertyAssignmentFilter,
            $this->internalCallFilter,
            $this->propertyFetchFilter,
            $this->variableCallFilter,
            $this->methodParser
        );

        parent::__construct($name, $data, $dataName);
    }

    /**
     * @dataProvider getDummyServiceWithDependencyMethods
     * @param ReflectionMethod $reflectionMethod
     */
    public function testGetInternalCalls(ReflectionMethod $reflectionMethod): void
    {
        $result = $this->fixture->getInternalCalls($reflectionMethod);

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
        try {
            $result = $this->fixture->getPropertyCalls($reflectionMethod);
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
        $addToProperty = $this->dummyServiceWithDependencyProvider->getAddToProperty();

        $result = $this->fixture->getPropertyCalls($addToProperty);
        $this->assertSame('dummyObjectA', $result[0]->var->name->name);
        $this->assertSame('getDummyValue', $result[0]->name->name);
    }

    /**
     * @dataProvider getDummyServiceWithDependencyMethods
     * @param ReflectionMethod $reflectionMethod
     */
    public function testGetVariableCalls(ReflectionMethod $reflectionMethod): void
    {
        $result = $this->fixture->getVariableCalls($reflectionMethod);

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
        $result = $this->fixture->getParameterCalls($reflectionMethod);

        $this->assertSame(
            json_encode($this->parameterCalls[$reflectionMethod->getName()]),
            json_encode($result)
        );
    }

    /**
     * @dataProvider getDummyServiceWithDependencyMethods
     * @param ReflectionMethod $reflectionMethod
     */
    public function testGetPropertyAssignments(ReflectionMethod $reflectionMethod): void
    {
        $result = $this->fixture->getPropertyAssignments($reflectionMethod);

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
    public function testBracketsDontMessAnythingUp(): void
    {
        $reflectionClass = new ReflectionClass(TestCaseMethods::class);

        $this->assertTrue($reflectionClass->hasMethod('getByReflectionMethod'));

        $method = $reflectionClass->getMethod('getByReflectionMethod');

        $result = $this->fixture->getParameterCalls($method);
        $this->assertSame(
            'getName',
            $result[0]->name->name
        );
    }

    /**
     * @return ReflectionMethod[]
     */
    public function getDummyServiceWithDependencyMethods(): array
    {
        return $this->dummyServiceWithDependencyProvider->getAllMethods();
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
