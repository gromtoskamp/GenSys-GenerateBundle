<?php

namespace GenSys\GenerateBundle\Model;

class PropertyClass
{
    /** @var string */
    private $className;
    /** @var string */
    private $propertyName;

    /**
     * PropertyClass constructor.
     * @param string $propertyName
     * @param string $className
     */
    public function __construct(
        string $propertyName,
        string $className
    ) {
        $this->className = $className;
        $this->propertyName = $propertyName;
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
    public function getClassName(): string
    {
        return $this->className;
    }
}