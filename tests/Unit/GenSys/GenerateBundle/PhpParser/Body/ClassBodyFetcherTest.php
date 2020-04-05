<?php

namespace Tests\Unit\GenSys\GenerateBundle\PhpParser\Body;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use GenSys\GenerateBundle\PhpParser\Body\ClassBodyFetcher;
use GenSys\GenerateBundle\Service\FileService;
use ReflectionClass;

class ClassBodyFetcherTest extends TestCase
{
    /** @var FileService|MockObject */
    public $fileService;
    /** @var ReflectionClass|MockObject */
    public $reflectionClass;

    /** @var ClassBodyFetcher $fixture */
    private $fixture;

    public function setUp(): void
    {
        $this->fileService = $this->createMock(FileService::class);
        $this->reflectionClass = $this->createMock(ReflectionClass::class);

        $this->fixture = new ClassBodyFetcher($this->fileService);    }

    public function testGetBody(): void
    {
        $this->fileService->method('getContents')->willReturn('contents');
        $this->reflectionClass->method('getFileName')->willReturn('fileName');
        $this->reflectionClass->method('getStartLine')->willReturn(0);
        $this->reflectionClass->method('getEndLine')->willReturn(1);

        $result = $this->fixture->getBody($this->reflectionClass);

        $this->assertSame(
            'contents',
            $result
        );
    }

}
