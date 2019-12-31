<?php

namespace GenSys\GenerateBundle\PhpParser\Parse;

use Exception;
use GenSys\GenerateBundle\Service\FileService;
use PhpParser\ParserFactory;
use ReflectionMethod;
use PhpParser\Parser;

class MethodParser
{
    /** @var Parser */
    private $parser;
    /** @var FileService */
    private $fileService;

    /**
     * MethodParser constructor.
     * @param FileService $fileService
     */
    public function __construct(
        FileService $fileService
    ) {
        $this->parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        $this->fileService = $fileService;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return array|null
     */
    public function parse(ReflectionMethod $reflectionMethod): ?array
    {
        $body = $this->getBody($reflectionMethod);
        try {
            $nodes = $this->parser->parse('<?php ' . $body);
            return $nodes ?? [];
        } catch (Exception $e) {
            //well this sure wont bite me in the ass.
            return [];
        }
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return string
     */
    private function getBody(ReflectionMethod $reflectionMethod): string
    {
        $filename = $reflectionMethod->getFileName();
        $startLine = $reflectionMethod->getStartLine();
        $endLine = $reflectionMethod->getEndLine();

        return $this->fileService->getContents(
            $filename,
            $startLine,
            $endLine
        );
    }
}