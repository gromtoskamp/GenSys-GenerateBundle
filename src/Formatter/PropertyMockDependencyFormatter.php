<?php

namespace GenSys\GenerateBundle\Formatter;

use GenSys\GenerateBundle\Model\MockDependency;

class PropertyMockDependencyFormatter
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
            $className = $mockDependency->getClassName();
            $propertyName = $mockDependency->getPropertyName();
            $rows = [];
            $rows[] = "    /** @var $className|MockObject */";
            $rows[] = "    public $$propertyName;";
            $formatted[] = implode(PHP_EOL, $rows);
        }

        return implode(PHP_EOL . PHP_EOL, $formatted) . PHP_EOL;
    }
}
