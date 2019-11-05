<?php

namespace GenSys\GenerateBundle\Model\Structure;

class Parameter
{
    /** @var string */
    private $name;
    /** @var string */
    private $type;

    public function __construct(
        string $name,
        string $type
    ) {
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
