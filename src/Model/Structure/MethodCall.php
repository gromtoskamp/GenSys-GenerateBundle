<?php

namespace GenSys\GenerateBundle\Model\Structure;

class MethodCall
{
    public const THIS = 'this';

    /** @var string */
    private $subject;
    /** @var string */
    private $methodName;

    public function __construct(
        string $subject,
        string $methodName
    ) {
        $this->subject = $subject;
        $this->methodName = $methodName;
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

}