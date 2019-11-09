<?php

namespace GenSys\GenerateBundle\Resources\Dummy\Object;

abstract class AbstractDummy
{
    /** @var string */
    private $value;

    public function __construct(
        string $value
    ) {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
