<?php

namespace GenSys\GenerateBundle\Model\Scanner;

use GenSys\GenerateBundle\Service\ReflectionService;
use ReflectionMethod;
use ReflectionParameter;

class MethodScanner extends AbstractScanner
{
    private const REGEX_PROPERTY_REFERENCE = '/\$this->(\w*)->\w*\(/';
    private const REGEX_INTERNAL_CALL = '/\$this->(\w*)\(([\$,\w\n\s]*)\)/';
    private const REGEX_VARIABLE_CALL = '/\$(\w*)->(\w*)\(/';
    private const REGEX_PROPERTY_CALL = '/\$this->(\w*)->(\w*)\(/';

    /** @var ReflectionMethod */
    private $reflectionMethod;

    /** @var string */
    private $reflectionMethodBody;

    /**
     * MethodScanner constructor.
     * @param ReflectionMethod $reflectionMethod
     * @param string $reflectionMethodBody
     */
    public function __construct(
        ReflectionMethod $reflectionMethod,
        string $reflectionMethodBody
    ) {
        $this->reflectionMethod = $reflectionMethod;
        $this->reflectionMethodBody = $reflectionMethodBody;
    }

    /**
     * @return array
     */
    public function getPropertyReferences(): array
    {
        return $this->match(
            $this->getReflectionMethodBody(),
            self::REGEX_PROPERTY_REFERENCE
        );
    }

    /**
     * @return array
     */
    public function getInternalCalls(): array
    {
        $combinedMatch = $this->combinedMatch(
            $this->getReflectionMethodBody(),
            self::REGEX_INTERNAL_CALL
        );

        $internalCalls = [];
        foreach ($combinedMatch as $methodName => $matches) {
            foreach ($matches as $match) {
                $parameters = explode(', ', $match);
                foreach ($parameters as $parameter) {
                    $internalCalls[$methodName][] = substr($parameter, 1);
                }
            }
        }

        return $internalCalls;
    }

    /**
     * @return array
     */
    public function getPropertyCalls(): array
    {
        return $this->combinedMatch(
            $this->getReflectionMethodBody(),
            self::REGEX_PROPERTY_CALL
        );
    }

    /**
     * @return array
     */
    public function getVariableCalls(): array
    {
        return $this->combinedMatch(
            $this->reflectionMethodBody,
            self::REGEX_VARIABLE_CALL
        );
    }

    /**
     * @return array
     */
    public function getParameterCalls(): array
    {
        $parameterMapping = $this->mapParameters($this->reflectionMethod->getParameters());

        $parameterCalls = [];
        foreach ($this->getVariableCalls() as $variable => $calls) {

            foreach($parameterMapping as $item) {
                if ($item['name'] === $variable) {
                    $parameterCalls[$item['type']] = $calls;
                }
            }

        }

        $calledMethods = [];
        foreach ($this->getInternalCalls() as $methodName => $methodParameters) {
            foreach ($methodParameters as $methodParameter) {
                if (in_array($methodParameter, $parameterMapping, true)) {
                    $calledMethods[$methodName] = $methodParameter;
                }
            }
        }

        $internalCalls = $this->getInternalCalls();
        foreach ($internalCalls as $calledMethodName => $usedParameters) {
            $calledReflectionMethod = $this->reflectionMethod->getDeclaringClass()->getMethod($calledMethodName);

            $calledParameterMapping = $this->mapParameters($calledReflectionMethod->getParameters());

            $linkMapping = [];
            foreach ($parameterMapping as $parameter) {
                if (in_array($parameter['name'], $usedParameters)) {
                    $key = array_search($parameter['name'], $usedParameters);

                    $linkMapping[] = [
                        'source' => $parameter,
                        'target' => $calledParameterMapping[$key]
                    ];
                }
            }


            $reflectionService = new ReflectionService();
            $calledReflectionMethodScanner = new MethodScanner($calledReflectionMethod, $reflectionService->getMethodBody($calledReflectionMethod));

            foreach ($calledReflectionMethodScanner->getParameterCalls() as $calledParameter => $calledMethods) {
                foreach ($linkMapping as $linkMap) {
                    if ($linkMap['target']['type'] === $calledParameter) {
                        $parameterCalls[$linkMap['source']['type']] = $calledMethods;
                    }
                }
            }
        }


        return $parameterCalls;
    }

    /**
     * @param ReflectionParameter[] $reflectionParameters
     * @return array
     */
    private function mapParameters(array $reflectionParameters)
    {
        $parameterMapping = [];
        foreach ($reflectionParameters as $parameter) {
            if ($class = $parameter->getClass()) {
                $parameterMapping[] = [
                    'type' => $class->getShortName(),
                    'name' => $parameter->getName()
                ];
            } else {
                $parameterMapping[] = [
                    'type' => $parameter->getType()->getName(),
                    'name' => $parameter->getName()
                ];
            }
        }

        return $parameterMapping;
    }

    /**
     * @return string
     */
    private function getReflectionMethodBody(): string
    {
        return $this->reflectionMethodBody;
    }
}
