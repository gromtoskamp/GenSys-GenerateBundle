<?php

namespace GenSys\GenerateBundle\Resources\Dummy\Object;

class MultipleArgDummyObject
{
    /** @var int */
    private $int;
    /** @var string */
    private $string;
    /** @var bool */
    private $bool;

    /**
     * MultipleArgDummyObject constructor.
     * @param int $int
     * @param string $string
     * @param bool $bool
     */
    public function __construct(
        int $int,
        string $string,
        bool $bool
    ) {
        $this->int = $int;
        $this->string = $string;
        $this->bool = $bool;
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
