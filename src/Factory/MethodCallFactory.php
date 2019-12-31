<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Mapper\MethodMapper;
use GenSys\GenerateBundle\Model\MethodCall;
use GenSys\GenerateBundle\Model\ParameterPropertyAssign;
use GenSys\GenerateBundle\Service\Reflection\MethodService;
use ReflectionException;
use ReflectionMethod;

class MethodCallFactory
{
    /** @var MethodService */
    private $methodService;
    /** @var MethodMapper */
    private $methodMapper;

    /**
     * MethodCallFactory constructor.
     * @param MethodService $methodService
     * @param MethodMapper $methodMapper
     */
    public function __construct(
        MethodService $methodService,
        MethodMapper $methodMapper
    ) {
        $this->methodService = $methodService;
        $this->methodMapper = $methodMapper;
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
            foreach ($constructorMap as $parameterPropertyAssign) {
                if ($propertyCall->var->name->name === $parameterPropertyAssign->getPropertyName()) {
                    $methodCalls[] = new MethodCall(
                        lcfirst($parameterPropertyAssign->getClassName()),
                        $propertyCall->name->name
                    );
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
     * @return ParameterPropertyAssign[]
     */
    private function getConstructorMap(ReflectionMethod $reflectionMethod): array
    {
        $constructor = $reflectionMethod->getDeclaringClass()->getConstructor();
        if (null === $constructor) {
            return [];
        }
        return $this->methodMapper->map($constructor);
    }

}
