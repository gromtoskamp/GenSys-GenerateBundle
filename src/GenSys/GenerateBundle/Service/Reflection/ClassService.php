<?php

namespace GenSys\GenerateBundle\Service\Reflection;

use ReflectionClass;
use ReflectionMethod;

class ClassService
{
    /**
     * @param ReflectionClass $reflectionClass
     * @return ReflectionMethod[]
     */
    public function getPublicMethods(ReflectionClass $reflectionClass): array
    {
        return $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @return ReflectionMethod[]
     */
    public function getPublicNonMagicMethods(ReflectionClass $reflectionClass): array
    {
        $publicNonMagicMethods = [];
        foreach ($this->getPublicMethods($reflectionClass) as $reflectionMethod) {
            if (strpos($reflectionMethod->getName(), '__') !== 0) {
                $publicNonMagicMethods[] = $reflectionMethod;
            }
        }

        return $publicNonMagicMethods;
    }
}
