<?php

namespace GenSys\GenerateBundle\Formatter\MockDependencies;

use GenSys\GenerateBundle\Formatter\MockDependenciesFormatter;
use GenSys\GenerateBundle\Formatter\MockDependency\InitMockDependencyFormatter;
use GenSys\GenerateBundle\Model\MockDependency;

class InitMockDependenciesFormatter implements MockDependenciesFormatter
{
    /** @var InitMockDependencyFormatter */
    private $initMockDependencyFormatter;

    /**
     * InitMockDependenciesFormatter constructor.
     * @param InitMockDependencyFormatter $initMockDependencyFormatter
     */
    public function __construct(
        InitMockDependencyFormatter $initMockDependencyFormatter
    ) {
        $this->initMockDependencyFormatter = $initMockDependencyFormatter;
    }

    /**
     * @param MockDependency[] $mockDependencies
     * @return string
     */
    public function format(iterable $mockDependencies): string
    {
        $formatted = [];
        foreach ($mockDependencies as $mockDependency) {
            $formatted[] = $this->initMockDependencyFormatter->format($mockDependency);
        }

        return implode(PHP_EOL . '        ', $formatted);
    }
}
