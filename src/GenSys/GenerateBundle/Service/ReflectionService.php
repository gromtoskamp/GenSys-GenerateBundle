<?php

namespace GenSys\GenerateBundle\Service;

use ReflectionMethod;

class ReflectionService
{
    /**
     * @param ReflectionMethod $reflectionMethod
     * @return string
     */
    public function getMethodBody(Reflectionmethod $reflectionMethod): string
    {
        $filename = $reflectionMethod->getFileName();
        $startLine = $reflectionMethod->getStartLine() + 1;
        $endLine = $reflectionMethod->getEndLine() - 1;
        $length = $endLine - $startLine;

        $source = file($filename);
        return implode('', array_slice($source, $startLine, $length));
    }
}
