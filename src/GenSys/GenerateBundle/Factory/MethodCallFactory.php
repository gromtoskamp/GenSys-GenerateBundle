<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Model\Structure\MethodCall;
use GenSys\GenerateBundle\Service\Reflection\MethodService;
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
     */
    public function createFromReflectionMethod(ReflectionMethod $reflectionMethod): array
    {
        $methodCalls = [];
        foreach ($this->methodService->getPropertyCalls($reflectionMethod) as $property => $propertyCalls) {
            foreach ($propertyCalls as $propertyCall) {
                $methodCalls[] = new MethodCall($property, $propertyCall);
            }
        }

        foreach ($this->methodService->getParameterCalls($reflectionMethod) as $parameter => $parameterCalls) {
            foreach ($parameterCalls as $parameterCall) {
                $methodCalls[] = new MethodCall(lcfirst($parameter), $parameterCall);
            }
        }
        return $methodCalls;
    }
}
