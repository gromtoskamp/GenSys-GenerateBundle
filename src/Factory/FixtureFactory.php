<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Model\Collection\MockDependencyCollection;
use GenSys\GenerateBundle\Model\Fixture;
use ReflectionClass;

class FixtureFactory
{
    /**
     * @param ReflectionClass $reflectionClass
     * @param MockDependencyCollection $mockDependencyCollection
     * @return Fixture
     */
    public function create(ReflectionClass $reflectionClass, MockDependencyCollection $mockDependencyCollection): Fixture
    {
        $constructor = $reflectionClass->getConstructor();
        $mockDependencies = null !== $constructor ? $mockDependencyCollection->getByReflectionMethod($constructor) : [];

        return new Fixture(
            $reflectionClass->getNamespaceName(),
            $reflectionClass->getShortName(),
            $mockDependencies
        );
    }
}
