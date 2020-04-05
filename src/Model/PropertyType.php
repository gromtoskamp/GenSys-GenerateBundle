<?php

namespace GenSys\GenerateBundle\Model;

class PropertyType
{
    /** @var string */
    private $propertyName;
    /** @var string */
    private $typeName;
    /** @var string */
    private $fullyQualifiedName;

    /**
     * PropertyType constructor.
     * @param string $propertyName
     * @param string $typeName
     * @param string $fullyQualifiedName
     */
    public function __construct(
        string $propertyName,
        string $typeName,
        string $fullyQualifiedName
    ) {
        $this->propertyName = $propertyName;
        $this->typeName = $typeName;
        $this->fullyQualifiedName = $fullyQualifiedName;
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

    /**
     * @return string
     */
    public function getFullyQualifiedName(): string
    {
        return $this->fullyQualifiedName;
    }
}
