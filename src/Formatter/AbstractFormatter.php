<?php

namespace GenSys\GenerateBundle\Formatter;

abstract class AbstractFormatter
{
    /** @var string  */
    private const INDENT = '    ';

    /**
     * @param iterable $properties
     * @return string
     */
    abstract public function format(iterable $properties): string;

    /**
     * @param int $amount
     * @return string
     */
    protected function indent(int $amount): string
    {
        return str_repeat(self::INDENT, $amount);
    }

    /**
     * @param iterable $constructorArguments
     * @return iterable
     */
    protected function prefix(iterable $constructorArguments)
    {
        foreach ($constructorArguments as &$constructorArgument) {
            $constructorArgument = '$' . $constructorArgument;
        }
        return $constructorArguments;
    }
}
