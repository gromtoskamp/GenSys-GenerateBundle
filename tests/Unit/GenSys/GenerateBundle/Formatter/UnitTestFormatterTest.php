<?php

namespace Tests\Unit\GenSys\GenerateBundle\Formatter;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use GenSys\GenerateBundle\Formatter\UnitTestFormatter;
use GenSys\GenerateBundle\Formatter\UseMockDependenciesFormatter;
use GenSys\GenerateBundle\Formatter\PropertyMockDependenciesFormatter;
use GenSys\GenerateBundle\Formatter\InitMockDependenciesFormatter;
use GenSys\GenerateBundle\Model\UnitTest;

class UnitTestFormatterTest extends TestCase
{
    /** @var UseMockDependenciesFormatter|MockObject */
    public $useMockDependenciesFormatter;
    /** @var PropertyMockDependenciesFormatter|MockObject */
    public $propertyMockDependenciesFormatter;
    /** @var InitMockDependenciesFormatter|MockObject */
    public $initMockDependenciesFormatter;
    /** @var UnitTest|MockObject */
    public $unitTest;

    public function setUp(): void
    {
        $this->useMockDependenciesFormatter = $this->getMockBuilder(UseMockDependenciesFormatter::class)->disableOriginalConstructor()->getMock();
        $this->propertyMockDependenciesFormatter = $this->getMockBuilder(PropertyMockDependenciesFormatter::class)->disableOriginalConstructor()->getMock();
        $this->initMockDependenciesFormatter = $this->getMockBuilder(InitMockDependenciesFormatter::class)->disableOriginalConstructor()->getMock();
        $this->unitTest = $this->getMockBuilder(UnitTest::class)->disableOriginalConstructor()->getMock();
    }

    public function testFormatUseMockDependencies(): void
    {
        $this->useMockDependenciesFormatter->method('format')->willReturn('formatted');
        $this->unitTest->method('getMockDependencies')->willReturn([]);
        $fixture = new UnitTestFormatter($this->useMockDependenciesFormatter, $this->propertyMockDependenciesFormatter, $this->initMockDependenciesFormatter);
        $result = $fixture->formatUseMockDependencies($this->unitTest);

        $this->assertSame(
            'formatted' . PHP_EOL,
            $result
        );

        $this->tearDown();
    }

    public function testFormatPropertyMockDependencies(): void
    {
        $this->propertyMockDependenciesFormatter->method('format')->willReturn('formatted');
        $this->unitTest->method('getMockDependencies')->willReturn([]);
        $fixture = new UnitTestFormatter($this->useMockDependenciesFormatter, $this->propertyMockDependenciesFormatter, $this->initMockDependenciesFormatter);
        $result = $fixture->formatPropertyMockDependencies($this->unitTest);

        $this->assertSame(
            'formatted' . PHP_EOL,
            $result
        );
    }

    public function testFormatInitMockDependencies(): void
    {
        $this->initMockDependenciesFormatter->method('format')->willReturn('formatted');
        $this->unitTest->method('getMockDependencies')->willReturn([]);
        $fixture = new UnitTestFormatter($this->useMockDependenciesFormatter, $this->propertyMockDependenciesFormatter, $this->initMockDependenciesFormatter);
        $result = $fixture->formatInitMockDependencies($this->unitTest);

        $this->assertSame(
            'formatted' . PHP_EOL,
            $result
        );
    }

}
