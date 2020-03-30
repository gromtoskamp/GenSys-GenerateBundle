<?php

namespace GenSys\GenerateBundle\Formatter\PropertyType;

use GenSys\GenerateBundle\Formatter\PropertyTypeFormatter;
use GenSys\GenerateBundle\Model\PropertyType;

class InitArgumentFormatter implements PropertyTypeFormatter
{
    /**
     * @param PropertyType $propertyType
     * @return string
     */
    public function format(PropertyType $propertyType): string
    {
        $same = '$' . $propertyType->getPropertyName() . ' = ';

        if (in_array($propertyType->getTypeName(), ['int', 'string', 'bool'])) {
            return $same . '(' . $propertyType->getTypeName() . ') \'\';';
        }

        return $same . 'new ' . $propertyType->getTypeName() . '();';
    }
}
