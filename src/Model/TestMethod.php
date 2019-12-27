<?php

namespace GenSys\GenerateBundle\Model;

class TestMethod
{
    /** @var string */
    private $name;
    /** @var string */
    private $originalName;
    /** @var bool */
    private $returnsVoid;
    /** @var MethodCall[] */
    private $methodCalls;
    /** @var Fixture */
    private $fixture;

    /**
     * TestMethod constructor.
     * @param $name
     * @param string $originalName
     * @param bool $returnsVoid
     * @param iterable $methodCalls
     * @param Fixture $fixture
     */
    public function __construct(
        string $name,
        string $originalName,
        bool $returnsVoid,
        iterable $methodCalls,
        Fixture $fixture
    ) {
        $this->name = $name;
        $this->originalName = $originalName;
        $this->returnsVoid = $returnsVoid;
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
     * @return bool
     */
    public function isReturnsVoid(): bool
    {
        return $this->returnsVoid;
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
