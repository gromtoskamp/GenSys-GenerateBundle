<?php

namespace GenSys\GenerateBundle\Service\Reflection;

use GenSys\GenerateBundle\PhpParser\Filter\MethodCall\InternalCallFilter;
use GenSys\GenerateBundle\PhpParser\Filter\MethodCall\PropertyFetchFilter;
use GenSys\GenerateBundle\PhpParser\Filter\MethodCall\VariableCallFilter;
use GenSys\GenerateBundle\PhpParser\Filter\Node\MethodCallFilter;
use GenSys\GenerateBundle\PhpParser\Filter\Node\PropertyAssignmentFilter;
use GenSys\GenerateBundle\PhpParser\Parse\MethodParser;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use ReflectionException;
use ReflectionMethod;

class MethodService
{
    /** @var MethodCallFilter */
    private $methodCallFilter;
    /** @var PropertyAssignmentFilter */
    private $propertyAssignmentFilter;
    /** @var InternalCallFilter */
    private $internalCallFilter;
    /** @var PropertyFetchFilter */
    private $propertyFetchFilter;
    /** @var VariableCallFilter */
    private $variableCallFilter;
    /** @var MethodParser */
    private $methodParser;

    public function __construct(
        MethodCallFilter $methodCallFilter,
        PropertyAssignmentFilter $propertyAssignmentFilter,
        InternalCallFilter $internalCallFilter,
        PropertyFetchFilter $propertyFetchFilter,
        VariableCallFilter $variableCallFilter,
        MethodParser $methodParser
    ) {

        $this->methodCallFilter = $methodCallFilter;
        $this->propertyAssignmentFilter = $propertyAssignmentFilter;
        $this->internalCallFilter = $internalCallFilter;
        $this->propertyFetchFilter = $propertyFetchFilter;
        $this->variableCallFilter = $variableCallFilter;
        $this->methodParser = $methodParser;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return MethodCall[]
     */
    public function getInternalCalls(ReflectionMethod $reflectionMethod): array
    {
        $methodCalls = $this->getMethodCalls($reflectionMethod);
        return $this->internalCallFilter->filter($methodCalls);
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return array
     * @throws ReflectionException
     */
    public function getPropertyCalls(ReflectionMethod $reflectionMethod): array
    {
        $methodCalls = $this->getMethodCalls($reflectionMethod);
        $propertyCalls = $this->propertyFetchFilter->filter($methodCalls);

        $calledReflectionMethods = $this->getInternalCallReflectionMethods($reflectionMethod);
        foreach ($calledReflectionMethods as $calledReflectionMethod) {
            $calledPropertyCalls = $this->getPropertyCalls($calledReflectionMethod);
            foreach ($calledPropertyCalls as $call) {
                $propertyCalls[] = $call;
            }
        }

        return $propertyCalls;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return array
     */
    public function getVariableCalls(ReflectionMethod $reflectionMethod): array
    {
        $methodCalls = $this->getMethodCalls($reflectionMethod);
        return $this->variableCallFilter->filter($methodCalls);
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return array
     * @throws ReflectionException
     */
    public function getParameterCalls(ReflectionMethod $reflectionMethod): array
    {

        $parameterCalls = [];
        $parameters = $reflectionMethod->getParameters();
        foreach ($this->getVariableCalls($reflectionMethod) as $variableCall) {
            foreach($parameters as $parameter) {
                if ($parameter->getName() === $variableCall->var->name) {
                    $parameterCalls[] = $variableCall;
                }
            }
        }

        $internalCalls = $this->getInternalCalls($reflectionMethod);
        foreach ($internalCalls as $internalCall) {
            $calledReflectionMethod = $reflectionMethod->getDeclaringClass()->getMethod($internalCall->name->name);
            $calledParameterCalls = $this->getParameterCalls($calledReflectionMethod);
            $calledParameters = $calledReflectionMethod->getParameters();

            foreach ($calledParameterCalls as $calledParameterCall) {
                foreach ($calledParameters as $key => $calledParameter) {
                    if ($calledParameterCall->var->name === $calledParameter->getName()) {
                        $calledParameterCall->var->name = $internalCall->args[$key]->value->name;
                        $parameterCalls[] = $calledParameterCall;
                    }
                }
            }
        }


        return $parameterCalls;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return array
     */
    public function getPropertyAssignments(ReflectionMethod $reflectionMethod): array
    {
        $nodes = $this->methodParser->parse($reflectionMethod);
        return $this->propertyAssignmentFilter->filter($nodes);
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return array
     * @throws ReflectionException
     */
    private function getInternalCallReflectionMethods(ReflectionMethod $reflectionMethod): array
    {
        $internalCalls = $this->getInternalCalls($reflectionMethod);

        $calledReflectionMethods = [];
        $reflectionClass = $reflectionMethod->getDeclaringClass();
        foreach ($internalCalls as $internalCall) {
            $calledReflectionMethods[] = $reflectionClass->getMethod($internalCall->name->name);
        }

        return $calledReflectionMethods;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return Node[]
     */
    private function getMethodCalls(ReflectionMethod $reflectionMethod): array
    {
        $nodes = $this->methodParser->parse($reflectionMethod);
        return $this->methodCallFilter->filter($nodes);
    }
}
