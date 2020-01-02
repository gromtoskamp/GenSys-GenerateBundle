<?php

namespace GenSys\GenerateBundle\Formatter;

use GenSys\GenerateBundle\Model\MethodCall;

class TestMethodCallFormatter
{
    /**
     * @param MethodCall $methodCall
     * @return string
     */
    public function format(MethodCall $methodCall): string
    {
        $subject = $methodCall->getSubject();
        $methodName = $methodCall->getMethodName();

        return sprintf('$this->%s->method(\'%s\')->willReturn(null);', $subject, $methodName);
    }
}
