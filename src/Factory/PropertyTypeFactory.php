<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Model\PropertyType;

class PropertyTypeFactory
{
    /**
     * @param string $propertyName
     * @param string $typeName
     * @return PropertyType
     */
    public function create(string $propertyName, string $typeName): PropertyType
    {
        return new PropertyType($propertyName, $typeName);
    }
}
