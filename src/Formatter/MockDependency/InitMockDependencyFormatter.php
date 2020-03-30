<?php

namespace GenSys\GenerateBundle\Formatter\MockDependency;

use GenSys\GenerateBundle\Formatter\MockDependencyFormatter;
use GenSys\GenerateBundle\Model\MockDependency;

class InitMockDependencyFormatter implements MockDependencyFormatter
{
    /**
     * @param MockDependency $mockDependency
     * @return string
     */
    public function format(MockDependency $mockDependency): string
    {
        $propertyName = $mockDependency->getPropertyName();
        $className = $mockDependency->getClassName();
        return "\$this->$propertyName = \$this->createMock($className::class);";
    }
}
