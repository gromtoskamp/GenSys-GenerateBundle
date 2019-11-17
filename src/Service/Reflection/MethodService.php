<?php

namespace GenSys\GenerateBundle\Service\Reflection;

use Exception;
use GenSys\GenerateBundle\Service\FileService;
use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\NodeFinder;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use ReflectionException;
use ReflectionMethod;

class MethodService
{
    /** @var Parser */
    private $parser;
    /** @var NodeFinder */
    private $nodeFinder;
    /** @var FileService */
    private $fileService;

    public function __construct(
        FileService $fileService
    ) {
        $this->parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        $this->nodeFinder = new NodeFinder();
        $this->fileService = $fileService;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return array
     */
    public function getInternalCalls(ReflectionMethod $reflectionMethod): array
    {
        $methodCalls = $this->getMethodCalls($reflectionMethod);

        $internalCalls = [];
        foreach ($methodCalls as $methodCall) {
            if ($methodCall->var->name === 'this') {
                $internalCalls[] = $methodCall;
            }
        }

        return $internalCalls;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return array
     * @throws ReflectionException
     */
    public function getPropertyCalls(ReflectionMethod $reflectionMethod): array
    {
        $methodCalls = $this->getMethodCalls($reflectionMethod);

        $propertyCalls = [];
        foreach ($methodCalls as $methodCall) {
            if ($methodCall->var instanceof PropertyFetch) {
                $propertyCalls[] = $methodCall;
            }
        }

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

        $variableCalls = [];
        foreach ($methodCalls as $methodCall) {
            if (!$methodCall->var instanceof PropertyFetch && $methodCall->var->name !== 'this') {
                $variableCalls[] = $methodCall;
            }
        }

        return $variableCalls;
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
        return $this->nodeFinder->find($nodes, static function (Node $node) {
            return $node instanceof Assign && $node->var instanceof PropertyFetch;
        });
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
     * @return array
     */
    private function getMethodCalls(ReflectionMethod $reflectionMethod): array
    {
        $nodes = $this->parse($reflectionMethod);
        return $this->nodeFinder->find($nodes, static function (Node $node) {
            return $node instanceof MethodCall;
        });
    }

    private function parse(ReflectionMethod $reflectionMethod): ?array
    {
        $body = $this->getBody($reflectionMethod);
        try {
            return $this->parser->parse('<?php ' . $body);
        } catch (Exception $e) {
            //well this sure wont bite me in the ass.
            return [];
        }
    }
}