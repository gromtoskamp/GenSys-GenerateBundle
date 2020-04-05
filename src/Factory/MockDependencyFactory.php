<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Model\MockDependency;
use ReflectionParameter;

class MockDependencyFactory
{
    /**
     * @param ReflectionParameter $reflectionParameter
     * @return MockDependency
     */
    public function create(ReflectionParameter $reflectionParameter): MockDependency
    {
        $shortName = $reflectionParameter->getClass()->getShortName();
        $propertyName = lcfirst($shortName);
        $fullyQualifiedClassName = $reflectionParameter->getClass()->getName();

        return new MockDependency(
            $shortName,
            $propertyName,
            $fullyQualifiedClassName
        );
    }
}
