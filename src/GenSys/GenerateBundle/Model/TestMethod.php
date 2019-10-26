<?php

namespace GenSys\GenerateBundle\Model;

class TestMethod
{
    /** @var string */
    private $name;

    /** @var array */
    private $body;

    public function __construct(
        $name,
        array $body
    ) {
        $this->name = $name;
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getBody(): array
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function getBodyAsString(): string
    {
        return implode("\n", $this->getBody());
    }
}
