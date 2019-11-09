<?php

namespace GenSys\GenerateBundle\Resources\Dummy\Service;

use GenSys\GenerateBundle\Model\MockDependency;
use ReflectionMethod;

class TestCaseMethods
{
    /**
     * @param ReflectionMethod $reflectionMethod
     * @return MockDependency[]
     */
    public function getByReflectionMethod(ReflectionMethod $reflectionMethod): array
    {
        $reflectionMethodName = $reflectionMethod->getName();
        if (!isset($this->$reflectionMethodName)) {
            return [];
        }
        return $this->$reflectionMethodName;
    }
}