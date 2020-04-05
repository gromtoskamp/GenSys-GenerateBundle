<?php

namespace GenSys\GenerateBundle\PhpParser\Body;

use GenSys\GenerateBundle\Service\FileService;
use ReflectionClass;

class ClassBodyFetcher
{
    /** @var FileService */
    private $fileService;

    public function __construct(
        FileService $fileService
    ) {
        $this->fileService = $fileService;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @return string
     */
    public function getBody(ReflectionClass $reflectionClass): string
    {
        $fileName = $reflectionClass->getFileName();
        $startLine = $reflectionClass->getStartLine();
        $endLine = $reflectionClass->getEndLine();

        return $this->fileService->getContents(
            $fileName,
            $startLine,
            $endLine
        );
    }
}
