<?php

namespace GenSys\GenerateBundle\Formatter;

use GenSys\GenerateBundle\Model\MockDependency;

class UseMockDependenciesFormatter
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
