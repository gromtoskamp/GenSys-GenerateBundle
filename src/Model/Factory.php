<?php

namespace GenSys\GenerateBundle\Model;

class Factory
{
    /** @var string */
    private $namespace;
    /** @var string */
    private $className;
    /** @var string */
    private $entityClassName;
    /** @var string */
    private $entityShortName;
    /** @var iterable */
    private $propertyTypes;

    /**
     * Factory constructor.
     * @param string $namespace
     * @param string $className
     * @param string $entityClassName
     * @param string $entityShortName
     * @param PropertyType[] $propertyTypes
     */
    public function __construct(
        string $namespace,
        string $className,
        string $entityClassName,
        string $entityShortName,
        iterable $propertyTypes
    ) {
        $this->namespace = $namespace;
        $this->className = $className;
        $this->entityClassName = $entityClassName;
        $this->entityShortName = $entityShortName;
        $this->propertyTypes = $propertyTypes;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
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
    public function getEntityClassName(): string
    {
        return $this->entityClassName;
    }

    /**
     * @return string
     */
    public function getFullyQualifiedName(): string
    {
        return $this->namespace . '\\' . $this->className;
    }

    /**
     * @return string
     */
    public function getEntityShortName(): string
    {
        return $this->entityShortName;
    }

    /**
     * @return PropertyType[]
     */
    public function getPropertyTypes(): iterable
    {
        return $this->propertyTypes;
    }

    /**
     * @return iterable
     */
    public function getPropertyNames(): iterable
    {
        $propertyNames = [];
        foreach ($this->getPropertyTypes() as $propertyType) {
            $propertyNames[] = $propertyType->getTypeName();
        }

        return $propertyNames;
    }
}
