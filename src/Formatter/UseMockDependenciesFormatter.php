<?php

namespace GenSys\GenerateBundle\Formatter;

use GenSys\GenerateBundle\Model\MockDependency;

class UseMockDependenciesFormatter
{
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
            $formatted[] = 'use ' . $mockDependency->getFullyQualifiedClassName() . ';';
        }

        return implode(PHP_EOL, $formatted) . PHP_EOL;
    }
}
