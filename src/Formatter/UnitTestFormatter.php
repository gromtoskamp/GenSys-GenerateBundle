<?php

namespace GenSys\GenerateBundle\Formatter;

use GenSys\GenerateBundle\Model\UnitTest;

class UnitTestFormatter
{
    /** @var UseMockDependenciesFormatter */
    private $useMockDependenciesFormatter;
    /** @var PropertyMockDependenciesFormatter */
    private $propertyMockDependenciesFormatter;
    /** @var InitMockDependenciesFormatter */
    private $initMockDependenciesFormatter;

    /**
     * UnitTestFormatter constructor.
     * @param UseMockDependenciesFormatter $useMockDependenciesFormatter
     * @param PropertyMockDependenciesFormatter $propertyMockDependenciesFormatter
     * @param InitMockDependenciesFormatter $initMockDependenciesFormatter
     */
    public function __construct(
        UseMockDependenciesFormatter $useMockDependenciesFormatter,
        PropertyMockDependenciesFormatter $propertyMockDependenciesFormatter,
        InitMockDependenciesFormatter $initMockDependenciesFormatter
    ) {
        $this->useMockDependenciesFormatter = $useMockDependenciesFormatter;
        $this->propertyMockDependenciesFormatter = $propertyMockDependenciesFormatter;
        $this->initMockDependenciesFormatter = $initMockDependenciesFormatter;
    }

    /**
     * @param UnitTest $unitTest
     * @return string
     */
    public function formatUseMockDependencies(UnitTest $unitTest): string
    {
        $mockDependencies = $unitTest->getMockDependencies();
        return $this->useMockDependenciesFormatter->format($mockDependencies) . PHP_EOL;
    }

    /**
     * @param UnitTest $unitTest
     * @return string
     */
    public function formatPropertyMockDependencies(UnitTest $unitTest): string
    {
        $mockDependencies = $unitTest->getMockDependencies();
        return $this->propertyMockDependenciesFormatter->format($mockDependencies) . PHP_EOL;
    }

    /**
     * @param UnitTest $unitTest
     * @return string
     */
    public function formatInitMockDependencies(UnitTest $unitTest): string
    {
        $mockDependencies = $unitTest->getMockDependencies();
        return $this->initMockDependenciesFormatter->format($mockDependencies) . PHP_EOL;
    }
}
