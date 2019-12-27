<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Model\ParameterPropertyAssign;

class ParameterPropertyAssignFactory
{
    /**
     * @param $className
     * @param $propertyName
     * @return ParameterPropertyAssign
     */
    public function create($className, $propertyName): ParameterPropertyAssign
    {
        return new ParameterPropertyAssign($className, $propertyName);
    }
}