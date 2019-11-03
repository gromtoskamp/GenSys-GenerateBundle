<?php

namespace GenSys\GenerateBundle\Model;

class MethodCall
{
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