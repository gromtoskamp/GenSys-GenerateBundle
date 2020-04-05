<?php

namespace GenSys\GenerateBundle\Model;

class MockDependency
{
    /** @var string */
    private $className;
    /** @var string */
    private $propertyName;
    /** @var string */
    private $fullyQualifiedClassName;

    /**
     * MockDependency constructor.
     * @param string $className
     * @param string $propertyName
     * @param string $fullyQualifiedClassName
     */
    public function __construct(
        string $className,
        string $propertyName,
        string $fullyQualifiedClassName
    ) {
        $this->className = $className;
        $this->propertyName = $propertyName;
        $this->fullyQualifiedClassName = $fullyQualifiedClassName;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @return string
     */
    public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    /**
     * @return string
     */
    public function getFullyQualifiedClassName(): string
    {
        return $this->fullyQualifiedClassName;
    }
}
