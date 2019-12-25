<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Model\Structure\MethodCall;
use GenSys\GenerateBundle\Service\Reflection\MethodService;
use ReflectionException;
use ReflectionMethod;

class MethodCallFactory
{
    /** @var MethodService */
    private $methodService;

    /**
     * MethodCallFactory constructor.
     * @param MethodService $methodService
     */
    public function __construct(
        MethodService $methodService
    ) {
        $this->methodService = $methodService;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return array
     * @throws ReflectionException
     */
    public function createFromReflectionMethod(ReflectionMethod $reflectionMethod): array
    {
        $propertyCalls = $this->methodService->getPropertyCalls($reflectionMethod);
        $constructorMap = $this->getConstructorMap($reflectionMethod);

        $methodCalls = [];
        foreach ($propertyCalls as $propertyCall) {
            foreach ($constructorMap as $className => $propertyAssignment) {
                if ($propertyCall->var->name->name === $propertyAssignment->var->name->name) {
                    $methodCalls[] = new MethodCall(lcfirst($className), $propertyCall->name->name);
                }
            }
        }

        $parameterCalls = $this->methodService->getParameterCalls($reflectionMethod);
        foreach ($parameterCalls as $parameterCall) {
            $reflectionParameters = $reflectionMethod->getParameters();
            foreach ($reflectionParameters as $reflectionParameter) {
                if ($reflectionParameter->getName() === $parameterCall->var->name) {
                    $methodCalls[] = new MethodCall(lcfirst($reflectionParameter->getClass()->getShortName()), $parameterCall->name->name);
                }
            }
        }

        return $methodCalls;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return array
     */
    private function getConstructorMap(ReflectionMethod $reflectionMethod): array
    {
        $constructor = $reflectionMethod->getDeclaringClass()->getConstructor();
        if (null === $constructor) {
            return [];
        }
        return $this->methodService->getMethodMap($constructor);
    }

}
