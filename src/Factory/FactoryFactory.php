<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Mapper\FullMethodMapper;
use GenSys\GenerateBundle\Model\Factory;
use ReflectionClass;

class FactoryFactory
{
    /** @var FullMethodMapper */
    private $fullMethodMapper;

    /**
     * FactoryFactory constructor.
     * @param FullMethodMapper $fullMethodMapper
     */
    public function __construct(
        FullMethodMapper $fullMethodMapper
    ) {
        $this->fullMethodMapper = $fullMethodMapper;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @return Factory
     */
    public function create(ReflectionClass $reflectionClass): Factory
    {
        $constructor = $reflectionClass->getConstructor();
        $propertyTypes = $this->fullMethodMapper->map($constructor);

        $shortName = $reflectionClass->getShortName();
        $name = $reflectionClass->getName();
        return new Factory(
            'GenSys\\GenerateBundle\\Factory',
            $shortName . 'Factory',
            $name,
            $shortName,
            $propertyTypes
        );
    }
}
