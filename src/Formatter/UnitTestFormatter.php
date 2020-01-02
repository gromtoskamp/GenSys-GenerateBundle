<?php

namespace GenSys\GenerateBundle\Formatter;

use GenSys\GenerateBundle\Model\UnitTest;

class UnitTestFormatter
{
    /** @var UseMockDependenciesFormatter */
    private $useMockDependenciesFormatter;
    /** @var PropertyMockDependencyFormatter */
    private $propertyMockDependencyFormatter;
    /** @var InitMockDependencyFormatter */
    private $initMockDependencyFormatter;

    /**
     * UnitTestFormatter constructor.
     * @param UseMockDependenciesFormatter $useMockDependenciesFormatter
     * @param PropertyMockDependencyFormatter $propertyMockDependencyFormatter
     * @param InitMockDependencyFormatter $initMockDependencyFormatter
     */
    public function __construct(
        UseMockDependenciesFormatter $useMockDependenciesFormatter,
        PropertyMockDependencyFormatter $propertyMockDependencyFormatter,
        InitMockDependencyFormatter $initMockDependencyFormatter
    ) {
        $this->useMockDependenciesFormatter = $useMockDependenciesFormatter;
        $this->propertyMockDependencyFormatter = $propertyMockDependencyFormatter;
        $this->initMockDependencyFormatter = $initMockDependencyFormatter;
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
        return $this->propertyMockDependencyFormatter->format($mockDependencies) . PHP_EOL;
    }

    /**
     * @param UnitTest $unitTest
     * @return string
     */
    public function formatInitMockDependencies(UnitTest $unitTest): string
    {
        $mockDependencies = $unitTest->getMockDependencies();
        return $this->initMockDependencyFormatter->format($mockDependencies) . PHP_EOL;
    }
}
