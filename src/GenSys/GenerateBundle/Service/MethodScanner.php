<?php

namespace GenSys\GenerateBundle\Service;

use ReflectionMethod;

class MethodScanner
{
    private const REGEX_PROPERTY_REFERENCE = '/\$this->(\w*)->\w*\(/';
    private const REGEX_PROPERTY_CALL = '/\$this->(\w*)->(\w*)\(/';
    private const REGEX_INTERNAL_CALL = '/\$this->(\w*)\(/';

    /** @var FileService */
    private $fileService;

    public function __construct(
        FileService $fileService
    ) {
        $this->fileService = $fileService;
    }

    public function getPropertyCalls(ReflectionMethod $reflectionMethod): array
    {
        $reflectionMethodBody = $this->fileService->getReflectionMethodBody($reflectionMethod);
        $matches = [];
        preg_match_all(self::REGEX_PROPERTY_REFERENCE, $reflectionMethodBody, $matches);
        return array_unique($matches[1]);
    }

    public function getInternalMethodCalls(ReflectionMethod $reflectionMethod): array
    {
        $reflectionMethodBody = $this->fileService->getReflectionMethodBody($reflectionMethod);
        $matches = [];
        preg_match_all(self::REGEX_INTERNAL_CALL, $reflectionMethodBody, $matches);
        return array_unique($matches[1]);
    }

    public function getPropertyMethodCalls(ReflectionMethod $reflectionMethod): array
    {
        $reflectionMethodBody = $this->fileService->getReflectionMethodBody($reflectionMethod);
        $matches = [];
        preg_match_all(self::REGEX_PROPERTY_CALL, $reflectionMethodBody, $matches);

        $combinedMatches = [];
        foreach ($matches[1] as $key => $match) {
            $combinedMatches[$match][] = $matches[2][$key];
        }

        return $combinedMatches;
    }
}
