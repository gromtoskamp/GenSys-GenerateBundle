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

    public function getPropertyName(): string
    {
        return lcfirst($this->getClassName());
    }

    public function getFullyQualifiedClassName(): string
    {
        return $this->parameter->getClass()->getName();
    }
}
