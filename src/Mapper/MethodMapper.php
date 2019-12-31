<?php

namespace GenSys\GenerateBundle\Mapper;

use GenSys\GenerateBundle\Factory\PropertyClassFactory;
use GenSys\GenerateBundle\Model\PropertyClass;
use GenSys\GenerateBundle\Service\Reflection\MethodService;
use ReflectionMethod;

class MethodMapper
{
    /** @var PropertyClassFactory */
    private $propertyClassFactory;
    /** @var MethodService */
    private $methodService;

    public function __construct(
        PropertyClassFactory $propertyClassFactory,
        MethodService $methodService
    ) {
        $this->propertyClassFactory = $propertyClassFactory;
        $this->methodService = $methodService;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return PropertyClass[]
     */
    public function map(ReflectionMethod $reflectionMethod): array
    {
        $reflectionParameters = $reflectionMethod->getParameters();
        $propertyAssignments = $this->methodService->getPropertyAssignments($reflectionMethod);

        $propertyClasses = [];
        foreach ($reflectionParameters as $key => $parameter) {
            if (null === $parameter->getClass()) {
                continue;
            }

            foreach ($propertyAssignments as $propertyAssignment) {
                $propertyName = $propertyAssignment->expr->name;
                if ($propertyName === $parameter->getName()) {
                    $shortName = $parameter->getClass()->getShortName();
                    $propertyClasses[] = $this->propertyClassFactory->create($propertyName, $shortName);
                }
            }
        }

        return $propertyClasses;
    }
}