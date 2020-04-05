<?php

namespace GenSys\GenerateBundle\Formatter;

use ReflectionClass;
use ReflectionException;

class ReturnTypeFormatter
{
    private $returnTypeMap = [
        'string' => '\'string\'',
        'int' => 1,
        'bool' => 'true',
        'void' => 'null'
    ];

    public function format(string $returnType)
    {
        if (!isset ($this->returnTypeMap[$returnType])) {
            return $this->formatClassReturnType($returnType);
        }
        return $this->returnTypeMap[$returnType];
    }

    /**
     * @param string $returnType
     * @return string
     */
    private function formatClassReturnType(string $returnType): string
    {
        if (!class_exists($returnType)) {
            return 'null';
        }

        try {
            $reflectionClass = new ReflectionClass($returnType);
        } catch (ReflectionException $e) {
            return 'null';
        }

        return sprintf('$this->%s', lcfirst($reflectionClass->getShortName()));
    }
}
