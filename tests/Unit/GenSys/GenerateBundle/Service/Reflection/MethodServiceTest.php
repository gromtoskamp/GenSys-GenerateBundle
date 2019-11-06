<?php

namespace Tests\Unit\GenSys\GenerateBundle\Service\Reflection;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use GenSys\GenerateBundle\Service\Reflection\MethodService;
use GenSys\GenerateBundle\Service\RegexMatcher;
use GenSys\GenerateBundle\Factory\ParameterFactory;
use ReflectionMethod;

class MethodServiceTest extends TestCase
{
    /** @var RegexMatcher|MockObject */
    public $regexMatcher;

    /** @var ParameterFactory|MockObject */
    public $parameterFactory;

    /** @var ReflectionMethod|MockObject */
    public $reflectionMethod;


    public function setUp(): void
    {
        $this->regexMatcher = $this->getMockBuilder(RegexMatcher::class)->disableOriginalConstructor()->getMock();
        $this->parameterFactory = $this->getMockBuilder(ParameterFactory::class)->disableOriginalConstructor()->getMock();
        $this->reflectionMethod = $this->getMockBuilder(ReflectionMethod::class)->disableOriginalConstructor()->getMock();
    }

    public function testGetPropertyReferences(): void
    {
        $this->regexMatcher->method('match')->willReturn(null);
        $this->reflectionMethod->method('getFileName')->willReturn(null);
        $this->reflectionMethod->method('getStartLine')->willReturn(null);
        $this->reflectionMethod->method('getEndLine')->willReturn(null);
        $fixture = new MethodService($this->regexMatcher, $this->parameterFactory);
        $result = $fixture->getPropertyReferences($this->reflectionMethod);

        //TODO: Write assertion.

        $this->tearDown();
    }

    public function testGetInternalCalls(): void
    {
        $this->regexMatcher->method('combinedMatch')->willReturn(null);
        $this->reflectionMethod->method('getFileName')->willReturn(null);
        $this->reflectionMethod->method('getStartLine')->willReturn(null);
        $this->reflectionMethod->method('getEndLine')->willReturn(null);
        $fixture = new MethodService($this->regexMatcher, $this->parameterFactory);
        $result = $fixture->getInternalCalls($this->reflectionMethod);

        //TODO: Write assertion.

        $this->tearDown();
    }

    public function testGetPropertyCalls(): void
    {
        $this->regexMatcher->method('combinedMatch')->willReturn(null);
        $this->reflectionMethod->method('getFileName')->willReturn(null);
        $this->reflectionMethod->method('getStartLine')->willReturn(null);
        $this->reflectionMethod->method('getEndLine')->willReturn(null);
        $fixture = new MethodService($this->regexMatcher, $this->parameterFactory);
        $result = $fixture->getPropertyCalls($this->reflectionMethod);

        //TODO: Write assertion.

        $this->tearDown();
    }

    public function testGetVariableCalls(): void
    {
        $this->regexMatcher->method('combinedMatch')->willReturn(null);
        $this->reflectionMethod->method('getFileName')->willReturn(null);
        $this->reflectionMethod->method('getStartLine')->willReturn(null);
        $this->reflectionMethod->method('getEndLine')->willReturn(null);
        $fixture = new MethodService($this->regexMatcher, $this->parameterFactory);
        $result = $fixture->getVariableCalls($this->reflectionMethod);

        //TODO: Write assertion.

        $this->tearDown();
    }

    public function testGetParameterCalls(): void
    {
        $this->reflectionMethod->method('getFileName')->willReturn(null);
        $this->reflectionMethod->method('getStartLine')->willReturn(null);
        $this->reflectionMethod->method('getEndLine')->willReturn(null);
        $fixture = new MethodService($this->regexMatcher, $this->parameterFactory);
        $result = $fixture->getParameterCalls($this->reflectionMethod);

        //TODO: Write assertion.

        $this->tearDown();
    }

    public function testGetBody(): void
    {
        $this->reflectionMethod->method('getFileName')->willReturn(null);
        $this->reflectionMethod->method('getStartLine')->willReturn(null);
        $this->reflectionMethod->method('getEndLine')->willReturn(null);
        $fixture = new MethodService($this->regexMatcher, $this->parameterFactory);
        $result = $fixture->getBody($this->reflectionMethod);

        //TODO: Write assertion.

        $this->tearDown();
    }

    public function tearDown(): void
    {
        unset($this->regexMatcher);
        unset($this->parameterFactory);
        unset($this->reflectionMethod);
    }
}
