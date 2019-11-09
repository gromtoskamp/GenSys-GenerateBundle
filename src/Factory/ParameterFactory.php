<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Model\Structure\Parameter;
use ReflectionMethod;
use ReflectionParameter;

class ParameterFactory
{
    /**
     * @param ReflectionMethod $reflectionMethod
     * @return array
     */
    public function createFromReflectionMethod(ReflectionMethod $reflectionMethod): array
    {
        $parameters = [];
        foreach ($reflectionMethod->getParameters() as $reflectionParameter) {
            $parameters[] = $this->createFromReflectionParameter($reflectionParameter);
        }

        return $parameters;
    }

    /**
     * @param ReflectionParameter $reflectionParameter
     * @return Parameter
     */
    private function createFromReflectionParameter(ReflectionParameter $reflectionParameter): Parameter
    {
        if ($class = $reflectionParameter->getClass()) {
            return new Parameter(
                $reflectionParameter->getName(),
                $class->getShortName()
            );
        }

        return new Parameter(
            $reflectionParameter->getName(),
            $reflectionParameter->getType()->getName()
        );
    }
}