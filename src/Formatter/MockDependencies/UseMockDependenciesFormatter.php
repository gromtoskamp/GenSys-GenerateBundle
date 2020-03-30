<?php

namespace GenSys\GenerateBundle\Formatter\MockDependencies;

use GenSys\GenerateBundle\Formatter\MockDependenciesFormatter;
use GenSys\GenerateBundle\Formatter\MockDependency\UseMockDependencyFormatter;
use GenSys\GenerateBundle\Model\MockDependency;

class UseMockDependenciesFormatter implements MockDependenciesFormatter
{
    /** @var UseMockDependencyFormatter */
    private $useMockDependencyFormatter;

    public function __construct(
        UseMockDependencyFormatter $useMockDependencyFormatter
    ) {
        $this->useMockDependencyFormatter = $useMockDependencyFormatter;
    }

    /**
     * @param MockDependency[] $mockDependencies
     * @return string
     */
    public function format(iterable $mockDependencies): string
    {
        if (empty($mockDependencies)) {
            return '';
        }

        $formatted = [];
        foreach ($mockDependencies as $mockDependency) {
            $formatted[] = $this->useMockDependencyFormatter->format($mockDependency);
        }

        return implode(PHP_EOL, $formatted);
    }
}
