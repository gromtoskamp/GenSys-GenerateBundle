<?php

namespace GenSys\GenerateBundle\Service\Scanner;

use GenSys\GenerateBundle\Service\ReflectionService;
use ReflectionClass;
use ReflectionMethod;

class MethodScanner extends AbstractScanner
{
    private const REGEX_PROPERTY_REFERENCE = '/\$this->(\w*)->\w*\(/';
    private const REGEX_INTERNAL_CALL = '/\$this->(\w*)\((.*)\)/';
    private const REGEX_VARIABLE_CALL = '/\$(\w*)->(\w*)\(/';
    private const REGEX_PROPERTY_CALL = '/\$this->(\w*)->(\w*)\(/';

    /** @var ReflectionMethod */
    private $reflectionMethod;

    /** @var string */
    private $reflectionMethodBody;
    /** @var ReflectionService */
    private $reflectionService;

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
        return $this->combinedMatch(
            $this->getReflectionMethodBody(),
            self::REGEX_INTERNAL_CALL
        );
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
        /** @var ReflectionClass[] $parameters */
        $parameters = [];
        foreach ($this->reflectionMethod->getParameters() as $parameter) {
            if ($class = $parameter->getClass()) {
                $parameters[] = lcfirst($class->getShortName());
            }
        }

        $parameterCalls = [];
        foreach ($this->getVariableCalls() as $variable => $calls) {
            if (in_array($variable, $parameters, true)) {
                $parameterCalls[$variable] = $calls;
            }
        }

        $internalCalls = $this->getInternalCalls();



        return $parameterCalls;
    }

    /**
     * @return string
     */
    private function getReflectionMethodBody(): string
    {
        return $this->reflectionMethodBody;
    }
}
