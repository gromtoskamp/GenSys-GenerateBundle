<?php

namespace GenSys\GenerateBundle\Formatter;

use GenSys\GenerateBundle\Model\PropertyType;

interface PropertyTypeFormatter
{
    /**
     * @param PropertyType $propertyType
     * @return string
     */
    public function format(PropertyType $propertyType): string;
}