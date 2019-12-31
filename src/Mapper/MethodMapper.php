<?php

namespace GenSys\GenerateBundle\Mapper;

use GenSys\GenerateBundle\Factory\ParameterPropertyAssignFactory;
use GenSys\GenerateBundle\Model\ParameterPropertyAssign;
use GenSys\GenerateBundle\Service\Reflection\MethodService;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use ReflectionMethod;

class MethodMapper
{
    /** @var ParameterPropertyAssignFactory */
    private $parameterPropertyAssignFactory;
    /** @var MethodService */
    private $methodService;

    public function __construct(
        ParameterPropertyAssignFactory $parameterPropertyAssignFactory,
        MethodService $methodService
    ) {
        $this->parameterPropertyAssignFactory = $parameterPropertyAssignFactory;
        $this->methodService = $methodService;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return ParameterPropertyAssign[]
     */
    public function map(ReflectionMethod $reflectionMethod): array
    {
        $reflectionParameters = $reflectionMethod->getParameters();
        $propertyAssignments = $this->methodService->getPropertyAssignments($reflectionMethod);

        $propertyAssignmentMap = [];
        foreach ($reflectionParameters as $key => $parameter) {
            if (null === $parameter->getClass()) {
                continue;
            }

            foreach ($propertyAssignments as $propertyAssignment) {
                if (!$propertyAssignment instanceof Assign) {
                    continue;
                }

                if (!$propertyAssignment->expr instanceof Variable) {
                    continue;
                }

                $exprName = $propertyAssignment->expr->name;
                if ($exprName === $parameter->getName()) {
                    $shortName = $parameter->getClass()->getShortName();
                    $propertyAssignmentMap[] = $this->parameterPropertyAssignFactory->create($shortName, $exprName);
                }
            }
        }

        return $propertyAssignmentMap;
    }
}