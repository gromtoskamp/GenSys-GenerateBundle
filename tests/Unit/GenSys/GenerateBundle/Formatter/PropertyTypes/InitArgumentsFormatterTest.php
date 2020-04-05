<?php

namespace Tests\Unit\GenSys\GenerateBundle\Formatter\PropertyTypes;

use GenSys\GenerateBundle\Formatter\PropertyType\InitArgumentFormatter;
use GenSys\GenerateBundle\Model\PropertyType;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use GenSys\GenerateBundle\Formatter\PropertyTypes\InitArgumentsFormatter;

class InitArgumentsFormatterTest extends TestCase
{

    /** @var InitArgumentFormatter|MockObject */
    private $initArgumentFormatter;

    public function setUp(): void
    {
        $this->initArgumentFormatter = $this->getMockBuilder(InitArgumentFormatter::class)->getMock();
    }

    public function testFormat(): void
    {
        $fixture = new InitArgumentsFormatter(
            $this->initArgumentFormatter
        );

        $properties = [
            new PropertyType('', '', ''),
            new PropertyType('', '', ''),
            new PropertyType('', '', '')
        ];

        $result = $fixture->format($properties);

        $this->assertSame(
            "\n        \n        ",
            $result
        );
    }

}
