<?php

namespace GenSys\GenerateBundle\Formatter;

use GenSys\GenerateBundle\Model\TestMethod;

class TestMethodFormatter
{
    /** @var TestMethodCallsFormatter */
    private $testMethodCallsFormatter;
    /** @var FixtureFormatter */
    private $fixtureFormatter;

    /**
     * TestMethodFormatter constructor.
     * @param TestMethodCallsFormatter $testMethodCallsFormatter
     * @param FixtureFormatter $fixtureFormatter
     */
    public function __construct(
        TestMethodCallsFormatter $testMethodCallsFormatter,
        FixtureFormatter $fixtureFormatter
    ) {
        $this->testMethodCallsFormatter = $testMethodCallsFormatter;
        $this->fixtureFormatter = $fixtureFormatter;
    }

    /**
     * @param TestMethod $testMethod
     * @return string
     */
    public function formatTestMethodCalls(TestMethod $testMethod): string
    {
        $methodCalls = $testMethod->getMethodCalls();
        return $this->testMethodCallsFormatter->format($methodCalls) . PHP_EOL;
    }

    /**
     * @param TestMethod $testMethod
     * @return string
     */
    public function formatResult(TestMethod $testMethod): string
    {
        $formatted = '';
        if (!$testMethod->isReturnsVoid()) {
            $formatted .= '$result = ';
        }

        $originalName = $testMethod->getOriginalName();
        $methodParameters = $testMethod->getMethodParameters();

        return sprintf($formatted . '$this->fixture->%s(%s);', $originalName, $methodParameters) . PHP_EOL;
    }
}
