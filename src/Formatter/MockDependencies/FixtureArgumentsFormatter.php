<?php

namespace GenSys\GenerateBundle\Formatter\MockDependencies;

use GenSys\GenerateBundle\Formatter\MockDependenciesFormatter;
use GenSys\GenerateBundle\Formatter\MockDependency\FixtureArgumentFormatter;
use GenSys\GenerateBundle\Model\MockDependency;

class FixtureArgumentsFormatter implements MockDependenciesFormatter
{
    /** @var FixtureArgumentFormatter */
    private $fixtureArgumentFormatter;

    public function __construct(
        FixtureArgumentFormatter $fixtureArgumentFormatter
    ) {
        $this->fixtureArgumentFormatter = $fixtureArgumentFormatter;
    }

    /**
     * @param MockDependency[] $mockDependencies
     * @return string
     */
    public function format(iterable $mockDependencies): string
    {
        $formatted = [];
        foreach ($mockDependencies as $mockDependency) {
            $formatted[] = $this->fixtureArgumentFormatter->format($mockDependency);
        }

        return implode(', ', $formatted);
    }
}
