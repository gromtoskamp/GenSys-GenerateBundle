<?php

namespace GenSys\GenerateBundle\Model;

class PropertyMethodCall
{
    /** @var string */
    private $propertyName;
    /** @var string */
    private $methodName;

    public function __construct(
        string $propertyName,
        string $methodName
    ) {
        $this->propertyName = $propertyName;
        $this->methodName = $methodName;
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
    public function getMethodName(): string
    {
        return $this->methodName;
    }

}