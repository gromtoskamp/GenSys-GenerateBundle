<?php

namespace GenSys\GenerateBundle\Formatter\MockDependencies;

use GenSys\GenerateBundle\Formatter\MockDependenciesFormatter;
use GenSys\GenerateBundle\Formatter\MockDependency\PropertyMockDependencyFormatter;
use GenSys\GenerateBundle\Model\MockDependency;

class PropertyMockDependenciesFormatter implements MockDependenciesFormatter
{
    /** @var PropertyMockDependencyFormatter */
    private $propertyMockDependencyFormatter;

    /**
     * PropertyMockDependenciesFormatter constructor.
     * @param PropertyMockDependencyFormatter $propertyMockDependencyFormatter
     */
    public function __construct(
        PropertyMockDependencyFormatter $propertyMockDependencyFormatter
    ) {
        $this->propertyMockDependencyFormatter = $propertyMockDependencyFormatter;
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
            $formatted[] = $this->propertyMockDependencyFormatter->format($mockDependency);
        }

        return implode(PHP_EOL, $formatted);
    }
}
