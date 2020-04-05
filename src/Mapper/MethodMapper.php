<?php

namespace GenSys\GenerateBundle\Mapper;

use GenSys\GenerateBundle\Factory\PropertyTypeFactory;
use GenSys\GenerateBundle\Model\PropertyType;
use GenSys\GenerateBundle\Service\Reflection\MethodService;
use ReflectionMethod;

class MethodMapper
{
    /** @var PropertyTypeFactory */
    private $propertyTypeFactory;
    /** @var MethodService */
    private $methodService;

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
            if (null === $parameter->getClass()) {
                continue;
            }

            foreach ($propertyAssignments as $propertyAssignment) {
                $propertyName = $propertyAssignment->expr->name;
                if ($propertyName === $parameter->getName()) {
                    $shortName = $parameter->getClass()->getShortName();
                    $fullyQualifiedName = $parameter->getClass()->getName();
                    $propertyTypes[] = $this->propertyTypeFactory->create($propertyName, $shortName, $fullyQualifiedName);
                }
            }
        }

        return $propertyTypes;
    }
}
