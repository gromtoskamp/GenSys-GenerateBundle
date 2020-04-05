<?php /** @noinspection PhpExpressionResultUnusedInspection */

namespace GenSys\GenerateBundle\Resources\Dummy\Service;

use GenSys\GenerateBundle\Resources\Dummy\Object\DummyObject;
use GenSys\GenerateBundle\Resources\Dummy\Object\DummyObjectA;
use GenSys\GenerateBundle\Resources\Dummy\Object\DummyObjectB;

class DummyServiceWithDependency
{
    /** @var DummyObjectA */
    private $dummyObjectA;
    /** @var DummyObjectB */
    private $dummyObjectC;

    public function __construct(
        DummyObjectA $dummyObjectA,
        DummyObjectB $dummyObjectB
    ) {
        $this->dummyObjectA = $dummyObjectA;
        $this->dummyObjectC = $dummyObjectB;
    }

    public function addToDummyValue(DummyObjectB $dummyObjectC, int $addTo): int
    {
        return $this->privateAddToDummyValue($dummyObjectC, $addTo);
    }

    public function addToDummyValueDirect(DummyObject $dummyObject, int $addTo): int
    {
        return $dummyObject->getDummyValue() + $addTo;
    }

    public function addToDummyValueProperty(int $addTo): int
    {
        $this->dummyObjectA->getDummyValue() + $addTo;
        $this->dummyObjectC->getDummyValue() + $addTo;
        return 1;
    }

    public function addToMultipleDummyValues(DummyObjectA $dummyObjectA, DummyObjectB $dummyObjectB, int $addTo): int
    {
        return $this->privateAddToDummyValue($dummyObjectA, $addTo) +
            $this->privateAddToDummyValue2($dummyObjectB, $addTo);
    }

    public function addToProperty(int $addTo): int
    {
        return $this->privateAddToProperty($addTo);
    }

    public function getDummyObjectDummyObject()
    {
        return $this->dummyObjectA->getDummyObject();
    }

    public function getString()
    {
        return $this->dummyObjectA->getDummyString();
    }

    public function getBool()
    {
        return $this->dummyObjectA->isDummyBool();
    }

    private function privateAddToDummyValue(DummyObject $dummyObjectE, int $addTo): int
    {
        return $this->privateAddToDummyValue2($dummyObjectE, $addTo);
    }

    private function privateAddToDummyValue2(DummyObject $dummyObjectF, int $addTo): int
    {
        return $dummyObjectF->getDummyValue() + $addTo;
    }

    private function privateAddToProperty(int $addTo): int
    {
        return $this->dummyObjectA->getDummyValue() + $addTo;
    }
}
