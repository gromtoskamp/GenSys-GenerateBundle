<?php

namespace GenSys\GenerateBundle\Service\Reflection;

use ReflectionParameter;

class ParameterService
{
    public function getType(ReflectionParameter $reflectionParameter)
    {
        if (null !== $reflectionParameter->getClass()) {
            return $reflectionParameter->getClass()->getShortName();
        }

        return $reflectionParameter->getType()->getName();
    }
}
