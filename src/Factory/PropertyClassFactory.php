<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Model\PropertyClass;

class PropertyClassFactory
{
    /**
     * @param string $propertyName
     * @param string $className
     * @return PropertyClass
     */
    public function create(string $propertyName, string $className): PropertyClass
    {
        return new PropertyClass($propertyName, $className);
    }
}