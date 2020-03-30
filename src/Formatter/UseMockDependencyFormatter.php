<?php

namespace GenSys\GenerateBundle\Formatter;

use GenSys\GenerateBundle\Model\MockDependency;

class UseMockDependencyFormatter
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
