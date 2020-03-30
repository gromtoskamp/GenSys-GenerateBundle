<?php

namespace Tests\Unit\GenSys\GenerateBundle\Formatter\MockDependencies;

use GenSys\GenerateBundle\Model\MockDependency;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use GenSys\GenerateBundle\Formatter\MockDependencies\InitMockDependenciesFormatter;
use GenSys\GenerateBundle\Formatter\MockDependency\InitMockDependencyFormatter;

class InitMockDependenciesFormatterTest extends TestCase
{
    /** @var InitMockDependencyFormatter|MockObject */
    public $initMockDependencyFormatter;
    /** @var MockObject|MockDependency */
    private $mockDependency;

    public function setUp(): void
    {
        $this->initMockDependencyFormatter = $this->createMock(InitMockDependencyFormatter::class);
        $this->mockDependency = $this->createMock(MockDependency::class);
    }

    public function testFormat(): void
    {
        $this->initMockDependencyFormatter->method('format')->willReturn('formatted');
        $fixture = new InitMockDependenciesFormatter($this->initMockDependencyFormatter);
        $result = $fixture->format([$this->mockDependency, $this->mockDependency]);

        $this->assertSame(
            'formatted' . PHP_EOL . '        formatted',
            $result
        );

        $this->tearDown();
    }

}
