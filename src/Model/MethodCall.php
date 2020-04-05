<?php

namespace GenSys\GenerateBundle\Model;

class MethodCall
{
    public const THIS = 'this';

    /** @var string */
    private $subject;
    /** @var string */
    private $methodName;
    /** @var string */
    private $returnType;

    /**
     * MethodCall constructor.
     * @param string $subject
     * @param string $methodName
     * @param string $returnType
     */
    public function __construct(
        string $subject,
        string $methodName,
        string $returnType
    ) {
        $this->subject = $subject;
        $this->methodName = $methodName;
        $this->returnType = $returnType;
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
     * @return string
     */
    public function getReturnType(): string
    {
        return $this->returnType;
    }
}
