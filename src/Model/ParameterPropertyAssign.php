<?php

namespace GenSys\GenerateBundle\Model;

class ParameterPropertyAssign
{
    /** @var string */
    private $className;
    /** @var string */
    private $propertyName;

    /**
     * Meh constructor.
     * @param $className
     * @param $propertyName
     */
    public function __construct(
        string $className,
        string $propertyName
    ) {
        $this->className = $className;
        $this->propertyName = $propertyName;
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
}