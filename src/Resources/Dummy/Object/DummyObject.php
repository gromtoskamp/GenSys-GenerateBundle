<?php

namespace GenSys\GenerateBundle\Resources\Dummy\Object;

class DummyObject
{
    /** @var int */
    private $dummyValue;
    /** @var DummyObject */
    private $dummyObject;
    /** @var string */
    private $dummyString;
    /** @var bool */
    private $dummyBool;

    /**
     * DummyObject constructor.
     * @param int $dummyValue
     * @param DummyObject|null $dummyObject
     */
    public function __construct(
        int $dummyValue,
        string $dummyString,
        bool $dummyBool,
        DummyObject $dummyObject = null
    ) {
        $this->dummyValue = $dummyValue;
        $this->dummyObject = $dummyObject;
        $this->dummyString = $dummyString;
        $this->dummyBool = $dummyBool;
    }

    public function getDummyValue(): int
    {
        return $this->dummyValue;
    }

    public function setDummyValue(int $dummyValue): void
    {
        $this->dummyValue = $dummyValue;
    }

    /**
     * @return DummyObject
     */
    public function getDummyObject(): DummyObject
    {
        return $this->dummyObject;
    }

    /**
     * @return string
     */
    public function getDummyString(): string
    {
        return $this->dummyString;
    }

    /**
     * @return bool
     */
    public function isDummyBool(): bool
    {
        return $this->dummyBool;
    }
}
