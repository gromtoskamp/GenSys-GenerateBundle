<?php /** @noinspection PhpInconsistentReturnPointsInspection */

namespace GenSys\GenerateBundle\Resources\Dummy\Service;

use GenSys\GenerateBundle\Resources\Dummy\Object\DummyObject;
use GenSys\GenerateBundle\Resources\Dummy\Object\DummyObjectA;
use GenSys\GenerateBundle\Resources\Dummy\Object\DummyObjectB;

class DummyServiceWithDependency
{
    /** @var DummyObjectA */
    private $dummyObjectA;
    /** @var DummyObjectB */
    private $dummyObjectB;

    public function __construct(
        DummyObjectA $dummyObjectA,
        DummyObjectB $dummyObjectB
    ) {
        $this->dummyObjectA = $dummyObjectA;
        $this->dummyObjectB = $dummyObjectB;
    }

    public function addToDummyValueDirect(DummyObject $dummyObject, int $addTo): int
    {
        return $dummyObject->getDummyValue() + $addTo;
    }

    public function addToDummyValueProperty(int $addTo): int
    {
        $this->dummyObjectA->getDummyValue() + $addTo;
        $this->dummyObjectB->getDummyValue() + $addTo;
        return 1;
    }

    public function addToDummyValue(DummyObjectB $dummyObjectB, int $addTo): int
    {
        return $this->privateAddToDummyValue($dummyObjectB, $addTo);
    }

    public function addToMultipleDummyValues(DummyObjectA $dummyObjectA, DummyObjectB $dummyObjectB, int $addTo): int
    {
        $this->privateAddToDummyValue($dummyObjectA, $addTo);
        $this->privateAddToDummyValue2($dummyObjectB, $addTo);
    }

    private function privateAddToDummyValue(DummyObject $dummyObject, int $addTo): int
    {
        return $dummyObject->getDummyValue() + $addTo;
    }

    private function privateAddToDummyValue2(DummyObject $dummyObject, int $addTo): int
    {
        return $dummyObject->getDummyValue() + $addTo;
    }
}
