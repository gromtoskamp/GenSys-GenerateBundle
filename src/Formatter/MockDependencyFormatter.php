<?php

namespace GenSys\GenerateBundle\Formatter;

use GenSys\GenerateBundle\Model\MockDependency;

interface MockDependencyFormatter
{
    public function format(MockDependency $mockDependency): string;
}