<?php

namespace GenSys\GenerateBundle\Formatter\MockDependency;

use GenSys\GenerateBundle\Formatter\MockDependencyFormatter;
use GenSys\GenerateBundle\Model\MockDependency;

class PropertyMockDependencyFormatter implements MockDependencyFormatter
{
    /**
     * @param MockDependency $mockDependency
     * @return string
     */
    public function format(MockDependency $mockDependency): string
    {
        $className = $mockDependency->getClassName();
        $propertyName = $mockDependency->getPropertyName();

        $rows[] = "    /** @var $className|MockObject */";
        $rows[] = "    public $$propertyName;";
        return implode(PHP_EOL, $rows);
    }
}
