<?php

namespace Tests\Unit\GenSys\GenerateBundle\PhpParser\Filter\Node;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Use_;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use GenSys\GenerateBundle\PhpParser\Filter\Node\UseFilter;


class UseFilterTest extends TestCase
{
    /** @var UseFilter $fixture */
    private $fixture;
    /** @var MockObject|Use_ */
    private $use_;
    /** @var MockObject|Class_ */
    private $class_;

    public function setUp(): void
    {
        $this->fixture = new UseFilter();
        $this->use_ = $this->createMock(Use_::class);
        $this->class_ = $this->createMock(Class_::class);
    }

    public function testFilter_returnsUse(): void
    {
        $result = $this->fixture->filter([$this->use_, $this->class_]);

        $this->assertSame(
            [$this->use_],
            $result
        );
    }

    public function testFilter_returnsEmpty(): void
    {
        $result = $this->fixture->filter([$this->class_]);

        $this->assertSame(
            [],
            $result
        );
    }

}
