<?php

namespace GenSys\GenerateBundle\Formatter;

use GenSys\GenerateBundle\Model\PropertyType;

interface PropertyTypesFormatter
{
    /**
     * @param PropertyType[] $propertyTypes
     * @return string
     */
    public function format(iterable $propertyTypes): string;
}