<?php

namespace GenSys\GenerateBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class GenSysGenerateBundle extends Bundle
{
    //TODO: when returning a mocked object, also check methodcalls on this object.
        //if getClass !== null,
        //getMethodCallsForVariable(returnedMockVariable)
    //TODO: void methods should not result in a ->willReturn statement
    //TODO: check getDeclaringClass->getConstructor from static methods
    //TODO: check getDeclaringClass->getConstructor from methods declared in parent
    //TODO: fix bug DummyServiceWithDependency::addToDummyValueProperty ->dummyObjectC does not show up in test
}
