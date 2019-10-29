<?php

namespace GenSys\GenerateBundle\Model;

use ReflectionParameter;

class MockDependency
{
    /** @var ReflectionParameter */
    private $parameter;

    public function __construct(
        ReflectionParameter $parameter
    ) {
        $this->parameter = $parameter;
    }

    public function getClassName(): string
    {
        return $this->parameter->getClass()->getShortName();
    }

    public function getVariableName(): string
    {
        return '$' . $this->getPropertyName();
    }
    
    public function getPropertyName(): string
    {
        return lcfirst($this->getClassName());
    }

    public function getPropertyCall(): string
    {
        return '$this->' . $this->getPropertyName();
    }

    public function getFullyQualifiedClassName(): string
    {
        return '\\' . $this->parameter->getClass()->getName();
    }

    public function getBody(): string
    {
        return '$this->' . $this->getPropertyName() . ' = $this->getMockBuilder(' . $this->parameter->getClass()->getShortName() . '::class)->disableOriginalConstructor()->getMock();';
    }

    public function getDocBlock(): string
    {
        return '@var ' . $this->getClassName() . '|MockObject';
    }
}
