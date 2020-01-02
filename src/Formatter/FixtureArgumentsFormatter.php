<?php

namespace GenSys\GenerateBundle\Formatter;

use GenSys\GenerateBundle\Model\MockDependency;

class FixtureArgumentsFormatter
{
    /**
     * @param MockDependency[] $mockDependencies
     * @return string
     */
    public function format(iterable $mockDependencies): string
    {
        $formatted = [];
        foreach ($mockDependencies as $mockDependency) {
            $formatted[] = '$this->' . $mockDependency->getPropertyName();
        }

        return implode(', ', $formatted);
    }
}
