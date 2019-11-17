<?php

namespace GenSys\GenerateBundle\Model;

use GenSys\GenerateBundle\Model\Structure\MethodCall;

class TestMethod
{
    /** @var string */
    private $name;
    /** @var string */
    private $originalName;
    /** @var MethodCall[] */
    private $methodCalls;
    /** @var Fixture */
    private $fixture;

    public function __construct(
        $name,
        string $originalName,
        iterable $methodCalls,
        Fixture $fixture
    ) {
        $this->name = $name;
        $this->originalName = $originalName;
        $this->methodCalls = $methodCalls;
        $this->fixture = $fixture;
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
    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    /**
     * @return MethodCall[]
     */
    public function getMethodCalls(): iterable
    {
        return $this->methodCalls;
    }

    /**
     * @return Fixture
     */
    public function getFixture(): Fixture
    {
        return $this->fixture;
    }
}
