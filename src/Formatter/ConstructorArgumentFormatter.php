<?php

namespace GenSys\GenerateBundle\Formatter;

use GenSys\GenerateBundle\Model\PropertyType;

class ConstructorArgumentFormatter
{
    /**
     * @param PropertyType $propertyType
     * @return string
     */
    public function format(PropertyType $propertyType): string
    {
        return '$' . $propertyType->getPropertyName();
    }
}