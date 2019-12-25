<?php

namespace GenSys\GenerateBundle\Model\Decorator;

use Iterator;

abstract class IterableDecorator implements Iterator
{
    /** @var int  */
    private $index = 0;

    /** @var iterable */
    private $items;

    /**
     * @param iterable $items
     * @return iterable
     */
    abstract public function decorate(iterable $items): iterable;

    /**
     * @return mixed
     */
    public function current()
    {
        return $this->items[$this->index];
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
        return isset($this->items[$this->index]);
    }

    /**
     *
     */
    public function rewind(): void
    {
        $this->index = 0;
    }
}