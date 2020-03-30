<?php

namespace GenSys\GenerateBundle\Formatter;

use GenSys\GenerateBundle\Model\MockDependency;

class InitMockDependenciesFormatter
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
