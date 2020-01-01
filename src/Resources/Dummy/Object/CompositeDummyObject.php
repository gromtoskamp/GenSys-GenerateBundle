<?php

namespace GenSys\GenerateBundle\Resources\Dummy\Object;

class CompositeDummyObject
{
    /** @var DummyObject */
    private $dummyObject;
    /** @var int */
    private $int;
    /** @var string */
    private $string;
    /** @var bool */
    private $bool;

    public function __construct(
        DummyObject $dummyObject,
        int $int,
        string $string,
        bool $bool
    ) {
        $this->dummyObject = $dummyObject;
        $this->int = $int;
        $this->string = $string;
        $this->bool = $bool;
    }

    /**
     * @return DummyObject
     */
    public function getDummyObject(): DummyObject
    {
        return $this->dummyObject;
    }

    /**
     * @return int
     */
    public function getInt(): int
    {
        return $this->int;
    }

    /**
     * @return string
     */
    public function getString(): string
    {
        return $this->string;
    }

    /**
     * @return bool
     */
    public function isBool(): bool
    {
        return $this->bool;
    }
}
