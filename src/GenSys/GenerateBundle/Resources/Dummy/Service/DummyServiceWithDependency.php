<?php

namespace GenSys\GenerateBundle\Resources\Dummy\Service;

use GenSys\GenerateBundle\Resources\Dummy\Object\DummyObject;

class DummyServiceWithDependency
{
    /** @var DummyObject */
    private $dummyObject;

    public function __construct(DummyObject $dummyObject)
    {
        $this->dummyObject = $dummyObject;
    }

    public function addToDummyValueProperty(int $addTo): int
    {
        $this->dummyObject->setDummyValue($this->dummyObject->getDummyValue() + 2);
        return $this->dummyObject->getDummyValue() + $addTo;
    }

    public function addToDummyValue(DummyObject $dummyObject, int $addTo): int
    {
        return $this->privateAddToDummyValue($dummyObject, $addTo);
    }

    private function privateAddToDummyValue(DummyObject $dummyObject, int $addTo): int
    {
        return $dummyObject->getDummyValue() + $addTo;
    }
}
