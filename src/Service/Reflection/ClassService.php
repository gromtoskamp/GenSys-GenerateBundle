<?php

namespace GenSys\GenerateBundle\Service\Reflection;

use ReflectionClass;
use ReflectionMethod;

class ClassService
{
    /** @var MethodService */
    private $methodService;

    public function __construct(
        MethodService $methodService
    ) {
        $this->methodService = $methodService;
    }

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

    /**
     * @param ReflectionClass $reflectionClass
     * @return array
     */
    public function getConstructorMap(ReflectionClass $reflectionClass): array
    {
        $constructor = $reflectionClass->getConstructor();
        if (null === $constructor) {
            return [];
        }
        $parameters = $constructor->getParameters();

        $propertyAssignments = $this->methodService->getPropertyAssignments($constructor);

        $constructorMap = [];
        foreach ($parameters as $key => $parameter) {
            if (null === $parameter->getClass()) {
                continue;
            }

            foreach ($propertyAssignments as $propertyAssignment) {
                if ($propertyAssignment->expr->name === $parameter->getName()) {
                    $constructorMap[$parameter->getClass()->getShortName()] = $propertyAssignment;
                }
            }
        }

        return $constructorMap;
    }
}
