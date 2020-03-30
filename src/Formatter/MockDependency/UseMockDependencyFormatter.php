<?php

namespace GenSys\GenerateBundle\Formatter\MockDependency;

use GenSys\GenerateBundle\Formatter\MockDependencyFormatter;
use GenSys\GenerateBundle\Model\MockDependency;

class UseMockDependencyFormatter implements MockDependencyFormatter
{
    /**
     * @param MockDependency $mockDependency
     * @return string
     */
    public function format(MockDependency $mockDependency): string
    {
        return 'use ' . $mockDependency->getFullyQualifiedClassName() . ';';
    }
}
