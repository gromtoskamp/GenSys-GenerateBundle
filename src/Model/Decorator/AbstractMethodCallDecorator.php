<?php

namespace GenSys\GenerateBundle\Model\Decorator;

use GenSys\GenerateBundle\Model\Structure\MethodCall;
use Iterator;

abstract class AbstractMethodCallDecorator implements Iterator
{
    /** @var int  */
    private $index = 0;

    /** @var iterable */
    private $methodCalls;

    /**
     * AbstractMethodCallDecorator constructor.
     * @param iterable $methodCalls
     */
    public function __construct(iterable $methodCalls)
    {
        $this->methodCalls = $this->decorate($methodCalls);
    }

    /**
     * @param iterable $methodCalls
     * @return iterable
     */
    abstract protected function decorate(iterable $methodCalls): iterable;

    /**
     * @return mixed
     */
    public function current(): MethodCall
    {
        return $this->methodCalls[$this->index];
    }

    /**
     *
     */
    public function next(): void
    {
        $this->index++;
    }

    /**
     * @return int
     */
    public function key(): int
    {
        return $this->index;
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return isset($this->methodCalls[$this->index]);
    }

    /**
     *
     */
    public function rewind(): void
    {
        $this->index = 0;
    }
}