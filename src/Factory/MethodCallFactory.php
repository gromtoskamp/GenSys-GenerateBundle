<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Mapper\MethodMapper;
use GenSys\GenerateBundle\Model\MethodCall;
use GenSys\GenerateBundle\Model\PropertyType;
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
            foreach ($constructorMap as $propertyType) {
                if ($propertyCall->var->name->name === $propertyType->getPropertyName()) {
                    $calledReflectionMethod = new ReflectionMethod($propertyType->getFullyQualifiedName(), $propertyCall->name->name);
                    $returnType = $calledReflectionMethod->getReturnType();
                    $returnTypeName = $returnType ? $returnType->getName() : 'void';
                    $methodCalls[] = new MethodCall(
                        lcfirst($propertyType->getTypeName()),
                        $propertyCall->name->name,
                        $returnTypeName
                    );
                }
            }
        }

        $parameterCalls = $this->methodService->getParameterCalls($reflectionMethod);
        foreach ($parameterCalls as $parameterCall) {
            $reflectionParameters = $reflectionMethod->getParameters();
            foreach ($reflectionParameters as $reflectionParameter) {
                if ($reflectionParameter->getName() === $parameterCall->var->name) {
                    $calledReflectionMethod = new ReflectionMethod($reflectionParameter->getClass()->getName(), $parameterCall->name->name);
                    $returnType = $calledReflectionMethod->getReturnType();
                    $returnTypeName = $returnType ? $returnType->getName() : 'void';
                    $methodCalls[] = new MethodCall(
                        lcfirst($reflectionParameter->getClass()->getShortName()),
                        $parameterCall->name->name,
                        $returnTypeName
                    );
                }
            }
        }

        return $methodCalls;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return PropertyType[]
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
