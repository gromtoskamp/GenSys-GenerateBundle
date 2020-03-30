<?php

namespace Tests\Unit\GenSys\GenerateBundle\Formatter\MockDependencies;

use GenSys\GenerateBundle\Model\MockDependency;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use GenSys\GenerateBundle\Formatter\MockDependencies\UseMockDependenciesFormatter;
use GenSys\GenerateBundle\Formatter\MockDependency\UseMockDependencyFormatter;

class UseMockDependenciesFormatterTest extends TestCase
{
    /** @var UseMockDependencyFormatter|MockObject */
    public $useMockDependencyFormatter;
    /** @var MockObject|MockDependency */
    private $mockDependency;

    public function setUp(): void
    {
        $this->useMockDependencyFormatter = $this->createMock(UseMockDependencyFormatter::class);
        $this->mockDependency = $this->createMock(MockDependency::class);
    }

    public function testFormat(): void
    {
        $this->useMockDependencyFormatter->method('format')->willReturn('formatted');
        $fixture = new UseMockDependenciesFormatter($this->useMockDependencyFormatter);
        $result = $fixture->format([$this->mockDependency, $this->mockDependency]);

        $this->assertSame(
            'formatted' . PHP_EOL . 'formatted',
            $result
        );
    }

}
