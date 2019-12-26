<?php

namespace GenSys\GenerateBundle\Service\Reflection;

use Exception;
use GenSys\GenerateBundle\PhpParser\Filter\MethodCall\InternalCallFilter;
use GenSys\GenerateBundle\PhpParser\Filter\MethodCall\PropertyFetchFilter;
use GenSys\GenerateBundle\PhpParser\Filter\MethodCall\VariableCallFilter;
use GenSys\GenerateBundle\PhpParser\Filter\Node\MethodCallFilter;
use GenSys\GenerateBundle\PhpParser\Filter\Node\PropertyAssignmentFilter;
use GenSys\GenerateBundle\Service\FileService;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use ReflectionException;
use ReflectionMethod;

class MethodService
{
    /** @var Parser */
    private $parser;
    /** @var FileService */
    private $fileService;
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

    public function __construct(
        FileService $fileService,
        MethodCallFilter $methodCallFilter,
        PropertyAssignmentFilter $propertyAssignmentFilter,
        InternalCallFilter $internalCallFilter,
        PropertyFetchFilter $propertyFetchFilter,
        VariableCallFilter $variableCallFilter
    ) {
        $this->parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        $this->fileService = $fileService;
        $this->methodCallFilter = $methodCallFilter;
        $this->propertyAssignmentFilter = $propertyAssignmentFilter;
        $this->internalCallFilter = $internalCallFilter;
        $this->propertyFetchFilter = $propertyFetchFilter;
        $this->variableCallFilter = $variableCallFilter;
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
        $nodes = $this->parse($reflectionMethod);
        return $this->propertyAssignmentFilter->filter($nodes);
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return string
     */
    public function getBody(Reflectionmethod $reflectionMethod): string
    {
        $filename = $reflectionMethod->getFileName();
        $startLine = $reflectionMethod->getStartLine();
        $endLine = $reflectionMethod->getEndLine();

        return $this->fileService->getContents(
            $filename,
            $startLine,
            $endLine
        );
    }

    /**
     * @param ReflectionMethod $constructor
     * @return array
     */
    public function getMethodMap(ReflectionMethod $constructor): array
    {
        $parameters = $constructor->getParameters();
        $propertyAssignments = $this->getPropertyAssignments($constructor);

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

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return array
     * @throws ReflectionException
     */
    private function getInternalCallReflectionMethods(ReflectionMethod $reflectionMethod): array
    {
        $internalCalls = $this->getInternalCalls($reflectionMethod);

        $calledReflectionMethods = [];
        foreach ($internalCalls as $internalCall) {
            $calledReflectionMethods[] = $reflectionMethod->getDeclaringClass()->getMethod($internalCall->name->name);
        }

        return $calledReflectionMethods;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return Node[]
     */
    private function getMethodCalls(ReflectionMethod $reflectionMethod): array
    {
        $nodes = $this->parse($reflectionMethod);
        return $this->methodCallFilter->filter($nodes);
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return array
     */
    private function parse(ReflectionMethod $reflectionMethod): ?array
    {
        $body = $this->getBody($reflectionMethod);
        try {
            $nodes = $this->parser->parse('<?php ' . $body);
            return $nodes ?? [];
        } catch (Exception $e) {
            //well this sure wont bite me in the ass.
            return [];
        }
    }
}