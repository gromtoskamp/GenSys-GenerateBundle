<?php

namespace Tests\Unit\GenSys\GenerateBundle\Service\Reflection;

use GenSys\GenerateBundle\PhpParser\Filter\MethodCall\InternalCallFilter;
use GenSys\GenerateBundle\PhpParser\Filter\MethodCall\PropertyFetchFilter;
use GenSys\GenerateBundle\PhpParser\Filter\MethodCall\VariableCallFilter;
use GenSys\GenerateBundle\PhpParser\Filter\Node\MethodCallFilter;
use GenSys\GenerateBundle\PhpParser\Filter\Node\PropertyAssignmentFilter;
use GenSys\GenerateBundle\PhpParser\Parse\MethodParser;
use GenSys\GenerateBundle\Service\Reflection\MethodService;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Tests\Providers\DummyServiceWithDependencyProvider;

class MethodServiceTest extends TestCase
{
    /** @var ReflectionMethod|MockObject */
    public $reflectionMethod;
    /** @var MethodCallFilter|MockObject  */
    private $methodCallFilter;
    /** @var PropertyAssignmentFilter|MockObject */
    private $propertyAssignmentFilter;
    /** @var InternalCallFilter|MockObject  */
    private $internalCallFilter;
    /** @var PropertyFetchFilter|MockObject  */
    private $propertyFetchFilter;
    /** @var VariableCallFilter|MockObject  */
    private $variableCallFilter;
    /** @var MethodParser|MockObject */
    private $methodParser;
    /** @var MockObject|ReflectionClass */
    private $reflectionClass;
    /** @var MockObject|MethodCall */
    private $methodCall;
    /** @var MockObject|Identifier */
    private $identifier;

    /** @var MethodService */
    private $fixture;

    /** @var DummyServiceWithDependencyProvider */

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->reflectionMethod = $this->createMock(ReflectionMethod::class);
        $this->reflectionClass = $this->createMock(ReflectionClass::class);
        $this->methodCallFilter = $this->createMock(MethodCallFilter::class);
        $this->propertyAssignmentFilter = $this->createMock(PropertyAssignmentFilter::class);
        $this->internalCallFilter = $this->createMock(InternalCallFilter::class);
        $this->propertyFetchFilter = $this->createMock(PropertyFetchFilter::class);
        $this->variableCallFilter = $this->createMock(VariableCallFilter::class);
        $this->methodParser = $this->createMock(MethodParser::class);
        $this->methodCall = $this->createMock(MethodCall::class);
        $this->identifier = $this->createMock(Identifier::class);
        $this->identifier->name = 'name';
        $this->methodCall->name = $this->identifier;

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

    public function testGetInternalCalls(): void
    {
        $this->internalCallFilter->expects($this->once())->method('filter');
        $this->fixture->getInternalCalls($this->reflectionMethod);
    }

    public function testGetPropertyCalls_hasNoRecursion_getsCalledOnce(): void
    {
        $this->internalCallFilter->method('filter')->willReturn([]);
        $this->propertyFetchFilter->expects($this->once())->method('filter');
        try {
            $this->fixture->getPropertyCalls($this->reflectionMethod);
        } catch (ReflectionException $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testGetPropertyCalls_hasSingleRecusion_getsCalledTwice(): void
    {
        $this->reflectionMethod->method('getDeclaringClass')->willReturn($this->reflectionClass);
        $this->reflectionClass->method('getMethod')->willReturn($this->reflectionMethod);

        $this->internalCallFilter->expects($this->at(0))->method('filter')->willReturn([$this->methodCall]);
        $this->propertyFetchFilter->expects($this->exactly(2))->method('filter');

        try {
            $this->fixture->getPropertyCalls($this->reflectionMethod);
        } catch (ReflectionException $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testGetVariableCalls(): void
    {
        $this->variableCallFilter->expects($this->once())->method('filter');
        $this->fixture->getVariableCalls($this->reflectionMethod);
    }

    public function testGetParameterCalls(): void
    {
        $this->assertTrue(true);
    }

    public function testGetPropertyAssignments(): void
    {
        $this->propertyAssignmentFilter->expects($this->once())->method('filter');
        $this->fixture->getPropertyAssignments($this->reflectionMethod);
    }
}
