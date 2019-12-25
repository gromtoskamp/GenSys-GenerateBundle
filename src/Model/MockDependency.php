<?php

namespace GenSys\GenerateBundle\Model;

use ReflectionParameter;

class MockDependency
{
    /** @var ReflectionParameter */
    private $parameter;

    /**
     * MockDependency constructor.
     * @param ReflectionParameter $parameter
     */
    public function __construct(
        ReflectionParameter $parameter
    ) {
        $this->parameter = $parameter;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->parameter->getClass()->getShortName();
    }

    /**
     * @return string
     */
    public function getPropertyName(): string
    {
        return lcfirst($this->getClassName());
    }

    /**
     * @return string
     */
    public function getFullyQualifiedClassName(): string
    {
        return $this->parameter->getClass()->getName();
    }
}
