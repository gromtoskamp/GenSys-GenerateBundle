<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Model\Collection\MockDependencyCollection;
use GenSys\GenerateBundle\Model\Fixture;
use ReflectionMethod;

class FixtureFactory
{
    /**
     * @param ReflectionMethod $reflectionMethod
     * @param MockDependencyCollection $mockDependencyCollection
     * @return Fixture
     */
    public function create(ReflectionMethod $reflectionMethod, MockDependencyCollection $mockDependencyCollection): Fixture
    {
        $reflectionClass = $reflectionMethod->getDeclaringClass();
        $parameters = $this->getParameters($reflectionMethod);

        $constructor = $reflectionClass->getConstructor();
        $mockDependencies = null !== $constructor ? $mockDependencyCollection->getByReflectionMethod($constructor) : [];

        return new Fixture(
            $reflectionClass->getNamespaceName(),
            $reflectionClass->getShortName(),
            $reflectionMethod->getName(),
            $mockDependencies,
            implode(',', $parameters)
        );
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return array
     */
    private function getParameters(ReflectionMethod $reflectionMethod): array
    {
        $parameters = [];
        foreach ($reflectionMethod->getParameters() as $parameter) {
            if ($parameter->getClass()) {
                $parameters[] = '$this->' . lcfirst($parameter->getClass()->getShortName());
            } else {
                $parameters[] = 'null';
            }
        }
        return $parameters;
    }
}
