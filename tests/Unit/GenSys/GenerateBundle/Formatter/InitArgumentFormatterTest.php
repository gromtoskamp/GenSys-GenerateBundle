<?php

namespace Tests\Unit\GenSys\GenerateBundle\Formatter;

use PHPUnit\Framework\TestCase;
use GenSys\GenerateBundle\Formatter\InitArgumentFormatter;
use GenSys\GenerateBundle\Model\PropertyType;

class InitArgumentFormatterTest extends TestCase
{
    public function testFormatInt(): void
    {
        $fixture = new InitArgumentFormatter();
        
        $propertyType = new PropertyType('testInt', 'int');
        $result = $fixture->format($propertyType);

        $this->assertSame(
            '$testInt = (int) \'\';',
            $result
        );
    }

    public function testFormatString(): void
    {
        $fixture = new InitArgumentFormatter();

        $propertyType = new PropertyType('testString', 'string');
        $result = $fixture->format($propertyType);

        $this->assertSame(
            '$testString = (string) \'\';',
            $result
        );
    }

    public function testFormatBool(): void
    {
        $fixture = new InitArgumentFormatter();

        $propertyType = new PropertyType('testBool', 'bool');
        $result = $fixture->format($propertyType);

        $this->assertSame(
            '$testBool = (bool) \'\';',
            $result
        );
    }

    public function testFormatClass(): void
    {
        $fixture = new InitArgumentFormatter();

        $propertyType = new PropertyType('testClass', 'ClassName');
        $result = $fixture->format($propertyType);

        $this->assertSame(
            '$testClass = new ClassName();',
            $result
        );
    }

}
