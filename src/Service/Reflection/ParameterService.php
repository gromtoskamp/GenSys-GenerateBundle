<?php

namespace GenSys\GenerateBundle\Service\Reflection;

use ReflectionParameter;
use RuntimeException;

class ParameterService
{
    /**
     * @param ReflectionParameter $reflectionParameter
     * @return string
     */
    public function getType(ReflectionParameter $reflectionParameter): string
    {
        if (null !== $reflectionParameter->getClass()) {
            return $reflectionParameter->getClass()->getShortName();
        }

        if (null !== $reflectionParameter->getType()) {
            return $reflectionParameter->getType()->getName();
        }

        throw new RuntimeException('Unknown type for reflectionParameter ' . $reflectionParameter->getName());
    }
}
