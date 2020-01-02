<?php

namespace GenSys\GenerateBundle\Formatter;

use GenSys\GenerateBundle\Model\MethodCall;

class TestMethodCallsFormatter
{
    /** @var TestMethodCallFormatter */
    private $testMethodCallFormatter;

    /**
     * TestMethodCallsFormatter constructor.
     * @param TestMethodCallFormatter $testMethodCallFormatter
     */
    public function __construct(
        TestMethodCallFormatter $testMethodCallFormatter
    ) {
        $this->testMethodCallFormatter = $testMethodCallFormatter;
    }

    /**
     * @param MethodCall[] $methodCalls
     * @return string
     */
    public function format(iterable $methodCalls): string
    {
        $formatted = [];
        foreach ($methodCalls as $methodCall) {
            $formatted[] = $this->testMethodCallFormatter->format($methodCall);
        }

        $indent = str_repeat(' ', 8);
        return implode(PHP_EOL . $indent, $formatted);
    }
}
