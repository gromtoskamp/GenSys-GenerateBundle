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
    public function formatFixture(TestMethod $testMethod): string
    {
        $fixture = $testMethod->getFixture();
        return $this->fixtureFormatter->format($fixture) . PHP_EOL;
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
        $methodParameters = $testMethod->getFixture()->getMethodParameters();

        return sprintf($formatted . '$fixture->%s(%s);', $originalName, $methodParameters) . PHP_EOL;
    }
}
