<?php

namespace GenSys\GenerateBundle\Factory;

use GenSys\GenerateBundle\Model\MethodCall;
use GenSys\GenerateBundle\Model\Scanner\MethodScanner;

class MethodCallFactory
{
    public function createFromMethodScanner(MethodScanner $methodScanner): array
    {
        $methodCalls = [];
        foreach ($methodScanner->getPropertyCalls() as $property => $propertyCalls) {
            foreach ($propertyCalls as $propertyCall) {
                $methodCalls[] = new MethodCall($property, $propertyCall);
            }
        }

        foreach ($methodScanner->getParameterCalls() as $parameter => $parameterCalls) {
            foreach ($parameterCalls as $parameterCall) {
                $methodCalls[] = new MethodCall($parameter, $parameterCall);
            }
        }

        return $methodCalls;
    }
}
