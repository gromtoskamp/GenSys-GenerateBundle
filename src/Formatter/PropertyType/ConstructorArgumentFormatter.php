<?php

namespace GenSys\GenerateBundle\Formatter\PropertyType;

use GenSys\GenerateBundle\Formatter\PropertyTypeFormatter;
use GenSys\GenerateBundle\Model\PropertyType;

class ConstructorArgumentFormatter implements PropertyTypeFormatter
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
