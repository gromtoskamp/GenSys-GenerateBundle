<?php

namespace GenSys\GenerateBundle\Mapper;

use GenSys\GenerateBundle\Factory\PropertyTypeFactory;
use GenSys\GenerateBundle\Model\PropertyType;
use GenSys\GenerateBundle\Service\Reflection\MethodService;
use ReflectionMethod;
use RuntimeException;

class FullMethodMapper
{
    /** @var PropertyTypeFactory */
    private $propertyTypeFactory;
    /** @var MethodService */
    private $methodService;

    /**
     * FullMethodMapper constructor.
     * @param PropertyTypeFactory $propertyTypeFactory
     * @param MethodService $methodService
     */
    public function __construct(
        PropertyTypeFactory $propertyTypeFactory,
        MethodService $methodService
    ) {
        $this->propertyTypeFactory = $propertyTypeFactory;
        $this->methodService = $methodService;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return PropertyType[]
     */
    public function map(ReflectionMethod $reflectionMethod): array
    {
        $reflectionParameters = $reflectionMethod->getParameters();
        $propertyAssignments = $this->methodService->getPropertyAssignments($reflectionMethod);

        $propertyTypes = [];
        foreach ($reflectionParameters as $key => $parameter) {
            foreach ($propertyAssignments as $propertyAssignment) {
                $propertyName = $propertyAssignment->expr->name;
                if ($propertyName === $parameter->getName()) {
                    if (null === $parameter->getClass()) {
                        $type = $parameter->getType();
                        if (null === $type) {
                            throw new RuntimeException('No class nor type found');
                        }
                        $propertyTypes[] = $this->propertyTypeFactory->create($propertyName, $type->getName());
                    } else {
                        $shortName = $parameter->getClass()->getShortName();
                        $propertyTypes[] = $this->propertyTypeFactory->create($propertyName, $shortName);
                    }
                }
            }
        }

        return $propertyTypes;
    }
}
