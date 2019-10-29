<?php

namespace GenSys\GenerateBundle\Model\Scanner;

use ReflectionMethod;

class MethodScanner
{
    private const REGEX_PROPERTY_REFERENCE = '/\$this->(\w*)->\w*\(/';
    private const REGEX_PROPERTY_CALL = '/\$this->(\w*)->(\w*)\(/';
    private const REGEX_INTERNAL_CALL = '/\$this->(\w*)\(/';
    private const REGEX_VARIABLE_CALL = '/\$(\w*)->\(/';

    /** @var ReflectionMethod */
    private $reflectionMethod;

    /** @var string */
    private $reflectionMethodBody;

    public function __construct(
        ReflectionMethod $reflectionMethod
    ) {
        $this->reflectionMethod = $reflectionMethod;
        $this->reflectionMethodBody = $this->getReflectionMethodBody($reflectionMethod);
    }

    public function getPropertyReferences(): array
    {
        $matches = [];
        preg_match_all(self::REGEX_PROPERTY_REFERENCE, $this->reflectionMethodBody, $matches);
        return array_unique($matches[1]);
    }

    public function getInternalMethodCalls(): array
    {
        $matches = [];
        preg_match_all(self::REGEX_INTERNAL_CALL, $this->reflectionMethodBody, $matches);
        return array_unique($matches[1]);
    }

    public function getPropertyMethodCalls(): array
    {
        $matches = [];
        preg_match_all(self::REGEX_PROPERTY_CALL, $this->reflectionMethodBody, $matches);

        $combinedMatches = [];
        foreach ($matches[1] as $key => $match) {
            $combinedMatches[$match][] = $matches[2][$key];
        }

        return $combinedMatches;
    }

    public function getArgumentCalls(): array
    {
        $parameters = [];
        foreach ($this->reflectionMethod->getParameters() as $reflectionParameter) {
            if ($class = $reflectionParameter->getClass()) {
                $parameters[] = $reflectionParameter;
            }
        }

        return $parameters;
    }

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
