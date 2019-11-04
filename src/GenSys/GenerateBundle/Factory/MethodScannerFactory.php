<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Service\ReflectionService;
use GenSys\GenerateBundle\Service\Scanner\MethodScanner;
use ReflectionMethod;

class MethodScannerFactory
{
    /** @var ReflectionService */
    private $reflectionService;

    public function __construct(
        ReflectionService $reflectionService
    ) {
        $this->reflectionService = $reflectionService;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return MethodScanner
     */
    public function createFromReflectionMethod(ReflectionMethod $reflectionMethod): MethodScanner
    {
        $body = $this->reflectionService->getMethodBody($reflectionMethod);
        return new MethodScanner($reflectionMethod, $body);
    }
}
