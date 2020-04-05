<?php

namespace GenSys\GenerateBundle\Formatter;

use GenSys\GenerateBundle\Model\MethodCall;

class TestMethodCallFormatter
{
    /** @var ReturnTypeFormatter */
    private $returnTypeFormatter;

    public function __construct(
        ReturnTypeFormatter $returnTypeFormatter
    ) {
        $this->returnTypeFormatter = $returnTypeFormatter;
    }

    /**
     * @param MethodCall $methodCall
     * @return string
     */
    public function format(MethodCall $methodCall): string
    {
        $subject = $methodCall->getSubject();
        $methodName = $methodCall->getMethodName();
        $formattedReturnType = $this->returnTypeFormatter->format($methodCall->getReturnType());

        return sprintf('$this->%s->method(\'%s\')->willReturn(%s);', $subject, $methodName, $formattedReturnType);
    }
}
