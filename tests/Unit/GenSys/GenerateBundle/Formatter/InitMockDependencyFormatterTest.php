<?php

namespace Tests\Unit\GenSys\GenerateBundle\Formatter;

use GenSys\GenerateBundle\Model\MockDependency;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use GenSys\GenerateBundle\Formatter\InitMockDependencyFormatter;

class InitMockDependencyFormatterTest extends TestCase
{

    /** @var MockObject */
    private $mockDependency;

    public function setUp(): void
    {
        $this->mockDependency = $this->getMockBuilder(MockDependency::class)->disableOriginalConstructor()->getMock();
    }

    public function testFormat(): void
    {
        $propertyName = 'testPropertyName';
        $className = 'TestClassName';

        $this->mockDependency->method('getPropertyName')->willReturn($propertyName);
        $this->mockDependency->method('getClassName')->willReturn($className);

        $fixture = new InitMockDependencyFormatter();
        $result = $fixture->format([$this->mockDependency, $this->mockDependency]);

        $this->assertSame(
            "\$this->$propertyName = \$this->getMockBuilder($className::class)->disableOriginalConstructor()->getMock();" . PHP_EOL . '        ' . "\$this->$propertyName = \$this->getMockBuilder($className::class)->disableOriginalConstructor()->getMock();" . PHP_EOL,
            $result
        );
    }

}
