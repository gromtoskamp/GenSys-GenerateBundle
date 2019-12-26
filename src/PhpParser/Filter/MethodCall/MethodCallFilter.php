<?php

namespace GenSys\GenerateBundle\PhpParser\Filter\MethodCall;

use PhpParser\Node\Expr\MethodCall;

interface MethodCallFilter
{
    /**
     * @param MethodCall[] $methodCalls
     * @return MethodCall[]
     */
    public function filter(iterable $methodCalls): iterable;
}