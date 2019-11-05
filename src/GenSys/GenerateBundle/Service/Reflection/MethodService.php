<?php

namespace GenSys\GenerateBundle\Service\Reflection;

use GenSys\GenerateBundle\Factory\ParameterFactory;
use GenSys\GenerateBundle\Service\RegexMatcher;
use ReflectionMethod;

class MethodService
{
    private const REGEX_PROPERTY_REFERENCE = '/\$this->(\w*)->\w*\(/';
    private const REGEX_INTERNAL_CALL = '/\$this->(\w*)\(([\$,\w\n\s]*)\)/';
    private const REGEX_VARIABLE_CALL = '/\$(\w*)->(\w*)\(/';
    private const REGEX_PROPERTY_CALL = '/\$this->(\w*)->(\w*)\(/';

    /** @var RegexMatcher */
    private $regexMatcher;
    /** @var ParameterFactory */
    private $parameterFactory;

    public function __construct(
        RegexMatcher $regexMatcher,
        ParameterFactory $parameterFactory
    ) {
        $this->regexMatcher = $regexMatcher;
        $this->parameterFactory = $parameterFactory;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return array
     */
    public function getPropertyReferences(ReflectionMethod $reflectionMethod): array
    {
        return $this->regexMatcher->match(
            $this->getBody($reflectionMethod),
            self::REGEX_PROPERTY_REFERENCE
        );
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return array
     */
    public function getInternalCalls(ReflectionMethod $reflectionMethod): array
    {
        $combinedMatch = $this->regexMatcher->combinedMatch(
            $this->getBody($reflectionMethod),
            self::REGEX_INTERNAL_CALL
        );

        $internalCalls = [];
        foreach ($combinedMatch as $methodName => $matches) {
            foreach ($matches as $match) {
                $parameters = explode(',', $match);
                foreach ($parameters as $parameter) {
                    $internalCalls[$methodName][] = substr($parameter, 1);
                }
            }
        }

        return $internalCalls;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return array
     */
    public function getPropertyCalls(ReflectionMethod $reflectionMethod): array
    {
        return $this->regexMatcher->combinedMatch(
            $this->getBody($reflectionMethod),
            self::REGEX_PROPERTY_CALL
        );
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return array
     */
    public function getVariableCalls(ReflectionMethod $reflectionMethod): array
    {
        return $this->regexMatcher->combinedMatch(
            $this->getBody($reflectionMethod),
            self::REGEX_VARIABLE_CALL
        );
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return array
     */
    public function getParameterCalls(ReflectionMethod $reflectionMethod): array
    {
        $parameterMapping = $this->getParameters($reflectionMethod);

        $parameterCalls = [];
        foreach ($this->getVariableCalls($reflectionMethod) as $variable => $calls) {

            foreach($parameterMapping as $parameter) {
                if ($parameter->getName() === $variable) {
                    $parameterCalls[$parameter->getType()] = $calls;
                }
            }

        }

        $calledMethods = [];
        foreach ($this->getInternalCalls($reflectionMethod) as $methodName => $methodParameters) {
            foreach ($methodParameters as $methodParameter) {
                if (in_array($methodParameter, $parameterMapping, true)) {
                    $calledMethods[$methodName] = $methodParameter;
                }
            }
        }

        $internalCalls = $this->getInternalCalls($reflectionMethod);
        foreach ($internalCalls as $calledMethodName => $usedParameters) {
            $calledReflectionMethod = $reflectionMethod->getDeclaringClass()->getMethod($calledMethodName);

            $calledParameterMapping = $this->getParameters($calledReflectionMethod);

            $linkMapping = [];
            foreach ($parameterMapping as $parameter) {

                if (in_array($parameter->getName(), $usedParameters)) {
                    $key = array_search($parameter->getName(), $usedParameters);

                    $linkMapping[] = [
                        'source' => $parameter,
                        'target' => $calledParameterMapping[$key]
                    ];
                }
            }

            foreach ($this->getParameterCalls($calledReflectionMethod) as $calledParameter => $calledMethods) {
                foreach ($linkMapping as $linkMap) {
                    if ($linkMap['target']->getType() === $calledParameter) {
                        $parameterCalls[$linkMap['source']->getType()] = $calledMethods;
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
    private function getParameters(ReflectionMethod $reflectionMethod)
    {
        return $this->parameterFactory->createFromReflectionMethod($reflectionMethod);
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return string
     */
    public function getBody(Reflectionmethod $reflectionMethod): string
    {
        $filename = $reflectionMethod->getFileName();
        $startLine = $reflectionMethod->getStartLine() + 1;
        $endLine = $reflectionMethod->getEndLine() - 1;
        $length = $endLine - $startLine;

        $source = file($filename);
        $implode = implode('', array_slice($source, $startLine, $length));

        return (preg_replace('/\s*/', '', $implode));
    }
}