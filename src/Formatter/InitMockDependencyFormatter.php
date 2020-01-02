<?php

namespace GenSys\GenerateBundle\Formatter;

use GenSys\GenerateBundle\Model\MockDependency;

class InitMockDependencyFormatter
{
    /**
     * @param MockDependency[] $mockDependencies
     * @return string
     */
    public function format(iterable $mockDependencies): string
    {
        $formatted = [];
        foreach ($mockDependencies as $mockDependency) {
            $propertyName = $mockDependency->getPropertyName();
            $className = $mockDependency->getClassName();
            $formatted[] = "\$this->$propertyName = \$this->getMockBuilder($className::class)->disableOriginalConstructor()->getMock();";
        }

        return implode(PHP_EOL . '        ', $formatted);
    }
}
