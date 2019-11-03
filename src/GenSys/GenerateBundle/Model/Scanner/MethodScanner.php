<?php

namespace GenSys\GenerateBundle\Model\Scanner;

use ReflectionMethod;

class MethodScanner
{
    private const REGEX_PROPERTY_REFERENCE = '/\$this->(\w*)->\w*\(/';
    private const REGEX_INTERNAL_CALL = '/\$this->(\w*)\(/';
    private const REGEX_VARIABLE_CALL = '/\$(\w*)->(\w*)\(/';
    private const REGEX_PROPERTY_CALL = '/\$this->(\w*)->(\w*)\(/';

    /** @var ReflectionMethod */
    private $reflectionMethod;

    /** @var string */
    private $reflectionMethodBody;

    /**
     * MethodScanner constructor.
     * @param ReflectionMethod $reflectionMethod
     */
    public function __construct(
        ReflectionMethod $reflectionMethod
    ) {
        $this->reflectionMethod = $reflectionMethod;
        $this->reflectionMethodBody = $this->getReflectionMethodBody($reflectionMethod);
    }

    /**
     * @return array
     */
    public function getPropertyReferences(): array
    {
        return $this->match(self::REGEX_PROPERTY_REFERENCE);
    }

    /**
     * @return array
     */
    public function getInternalCalls(): array
    {
        return $this->match(self::REGEX_INTERNAL_CALL);
    }

    /**
     * @return array
     */
    public function getPropertyCalls(): array
    {
        return $this->combinedMatch(self::REGEX_PROPERTY_CALL);
    }

    /**
     * @return array
     */
    public function getParameterCalls(): array
    {
        /** @var \ReflectionClass[] $parameters */
        $parameterCalls = [];
        foreach ($this->reflectionMethod->getParameters() as $parameter) {
            if ($class = $parameter->getClass()) {
                $parameters[] = lcfirst($class->getShortName());
            }
        }

        $variableCalls = $this->combinedMatch(self::REGEX_VARIABLE_CALL);
        foreach ($variableCalls as $variable => $calls) {
            if (in_array($variable, $parameters, true)) {
                $parameterCalls[$variable] = $calls;
            }
        }

        return $parameterCalls;
    }

    /**
     * @return array
     */
    public function getVariableCalls(): array
    {
        return $this->combinedMatch(self::REGEX_VARIABLE_CALL);
    }

    /**
     * @param $regex
     * @return array
     */
    private function match(string $regex): array
    {
        $matches = [];
        preg_match_all($regex, $this->reflectionMethodBody, $matches);
        return array_unique($matches[1]);
    }

    /**
     * @param string $regex
     * @return array
     */
    private function combinedMatch(string $regex): array
    {
        $matches = [];
        preg_match_all($regex, $this->reflectionMethodBody, $matches);
        $combinedMatches = [];
        foreach ($matches[1] as $key => $match) {
            $combinedMatches[$match][] = $matches[2][$key];
        }

        return $combinedMatches;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return string
     */
    private function getReflectionMethodBody(ReflectionMethod $reflectionMethod): string
    {
        $filename = $reflectionMethod->getFileName();
        $startLine = $reflectionMethod->getStartLine() + 1;
        $endLine = $reflectionMethod->getEndLine() - 1;
        $length = $endLine - $startLine;

        $source = file($filename);
        return implode('', array_slice($source, $startLine, $length));
    }
}
