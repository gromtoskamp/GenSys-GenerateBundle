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
        $methodCalls = [];
        foreach ($this->methodService->getPropertyCalls($reflectionMethod) as $propertyCall) {
            $methodCalls[] = new MethodCall($propertyCall->var->name, $propertyCall->name->name);
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
