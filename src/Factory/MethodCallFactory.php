<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Model\Structure\MethodCall;
use GenSys\GenerateBundle\Service\Reflection\ClassService;
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

        $constructor = $reflectionMethod->getDeclaringClass()->getConstructor();
        $constructorMap = $this->methodService->getMethodMap($constructor);

        $methodCalls = [];
        foreach ($propertyCalls as $propertyCall) {
            foreach ($constructorMap as $className => $propertyAssignment) {
                if ($propertyCall->var->name->name === $propertyAssignment->var->name->name) {
                    $methodCalls[] = new MethodCall(lcfirst($className), $propertyCall->name->name);
                }
            }
        }

        foreach ($this->methodService->getParameterCalls($reflectionMethod) as $parameterCall) {
            foreach ($reflectionMethod->getParameters() as $reflectionParameter) {
                if ($reflectionParameter->getName() === $parameterCall->var->name) {
                    $methodCalls[] = new MethodCall(lcfirst($reflectionParameter->getClass()->getShortName()), $parameterCall->name->name);
                }
            }
        }

        return $methodCalls;
    }
}
