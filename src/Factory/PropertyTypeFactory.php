<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Model\PropertyType;

class PropertyTypeFactory
{
    /**
     * @param string $propertyName
     * @param string $typeName
     * @param string $fullyQualifiedName
     * @return PropertyType
     */
    public function create(string $propertyName, string $typeName, string $fullyQualifiedName): PropertyType
    {
        return new PropertyType($propertyName, $typeName, $fullyQualifiedName);
    }
}
