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
    /** @var string */
    private $methodParameters;

    /**
     * TestMethod constructor.
     * @param string $name
     * @param string $originalName
     * @param bool $returnsVoid
     * @param iterable $methodCalls
     * @param string $methodParameters
     */
    public function __construct(
        string $name,
        string $originalName,
        bool $returnsVoid,
        iterable $methodCalls,
        string $methodParameters
    ) {
        $this->name = $name;
        $this->originalName = $originalName;
        $this->returnsVoid = $returnsVoid;
        $this->methodCalls = $methodCalls;
        $this->methodParameters = $methodParameters;
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
     * @return string
     */
    public function getMethodParameters(): string
    {
        return $this->methodParameters;
    }
}
