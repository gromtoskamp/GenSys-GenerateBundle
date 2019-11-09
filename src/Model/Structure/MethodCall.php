<?php

namespace GenSys\GenerateBundle\Model\Structure;

class MethodCall
{
    public const THIS = 'this';

    /** @var string */
    private $subject;
    /** @var string */
    private $methodName;
    /** @var array */
    private $parameters;

    public function __construct(
        string $subject,
        string $methodName,
        array $parameters = []
    ) {
        $this->subject = $subject;
        $this->methodName = $methodName;
        $this->parameters = $parameters;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return $this->methodName;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

}