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
            if (!$this->isDeclaredInClass($reflectionMethod, $reflectionClass)) {
                continue;
            }

            if (strpos($reflectionMethod->getName(), '__') !== 0) {
                $publicNonMagicMethods[] = $reflectionMethod;
            }
        }

        return $publicNonMagicMethods;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @param ReflectionClass $reflectionClass
     * @return bool
     */
    private function isDeclaredInClass(ReflectionMethod $reflectionMethod, ReflectionClass $reflectionClass): bool
    {
        return $reflectionMethod->getDeclaringClass()->getName() === $reflectionClass->getName();
    }
}
