<?php

namespace GenSys\GenerateBundle\Model;

class PropertyType
{
    /** @var string */
    private $propertyName;
    /** @var string */
    private $typeName;

    /**
     * PropertyType constructor.
     * @param string $propertyName
     * @param string $typeName
     */
    public function __construct(
        string $propertyName,
        string $typeName
    ) {
        $this->propertyName = $propertyName;
        $this->typeName = $typeName;
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
    public function getTypeName(): string
    {
        return $this->typeName;
    }

}