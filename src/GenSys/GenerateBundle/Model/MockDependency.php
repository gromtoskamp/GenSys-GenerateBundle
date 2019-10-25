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

    public function getClassName()
    {
        return $this->parameter->getClass()->getShortName();
    }

    public function getVariableName()
    {
        return '$' . $this->getPropertyName();
    }
    
    public function getPropertyName()
    {
        return lcfirst($this->getClassName());
    }

    public function getPropertyCall()
    {
        return '$this->' . $this->getPropertyName();
    }

    public function getFullyQualifiedClassName()
    {
        return '\\' . $this->parameter->getClass()->getName();
    }

    public function getBody()
    {
        return '$this->' . $this->getPropertyName() . ' = $this->getMockBuilder(' . $this->parameter->getClass()->getShortName() . '::class)->disableOriginalConstructor()->getMock();';
    }

    public function getDocBlock()
    {
        return '@var ' . $this->getClassName() . '|MockObject';
    }
}
