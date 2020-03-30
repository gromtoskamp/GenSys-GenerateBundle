<?php

namespace GenSys\GenerateBundle\Formatter;

use GenSys\GenerateBundle\Model\MockDependency;

interface MockDependenciesFormatter
{
    /**
     * @param MockDependency[] $mockDependencies
     * @return string
     */
    public function format(iterable $mockDependencies): string;
}