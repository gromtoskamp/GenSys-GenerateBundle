<?php

namespace GenSys\GenerateBundle\Model\Decorator;

use GenSys\GenerateBundle\Model\Structure\MethodCall;
use Iterator;

class SortedMethodCalls implements Iterator
{
    /** @var int  */
    private $index = 0;
    /** @var MethodCall[] $methodCalls */
    private $methodCalls;

    public function __construct(iterable $methodCalls)
    {
        $this->methodCalls = $this->sortMethodCalls($methodCalls);
    }

    /**
     * Return the current element
     * @link https://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return $this->methodCalls[$this->index];
    }

    /**
     * Move forward to next element
     * @link https://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        $this->index++;
    }

    /**
     * Return the key of the current element
     * @link https://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * Checks if current position is valid
     * @link https://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return isset($this->methodCalls[$this->index]);
    }

    /**
     * Rewind the Iterator to the first element
     * @link https://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->index = 0;
    }

    /**
     * @param MethodCall[] $methodCalls
     * @return MethodCall[]
     */
    private function groupMethodCalls(iterable $methodCalls): iterable
    {
        $groupedMethodCalls = [];
        foreach ($methodCalls as $methodCall) {
            $groupedMethodCalls[$methodCall->getSubject()][] = $methodCall;
        }

        return $groupedMethodCalls;
    }

    /**
     * @param MethodCall[] $methodCalls
     * @return array
     */
    private function sortMethodCalls(iterable $methodCalls): iterable
    {
        $sortedMethodCalls = [];
        foreach ($this->groupMethodCalls($methodCalls) as $subject => $group) {
            foreach ($group as $methodCall) {
                $sortedMethodCalls[] = $methodCall;
            }
        }

        return $sortedMethodCalls;
    }
}