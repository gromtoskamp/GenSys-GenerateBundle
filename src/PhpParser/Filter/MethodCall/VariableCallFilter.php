<?php

namespace GenSys\GenerateBundle\PhpParser\Filter\MethodCall;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;

class VariableCallFilter implements MethodCallFilter
{

    /**
     * @param MethodCall[] $methodCalls
     * @return MethodCall[]
     */
    public function filter(iterable $methodCalls): iterable
    {
        $variableCalls = [];
        foreach ($methodCalls as $methodCall) {
            /** @var Expr\Variable $var */
            $var = $methodCall->var;
            $name = $var->name;
            if (!$var instanceof PropertyFetch && $name !== 'this') {
                $variableCalls[] = $methodCall;
            }
        }

        return $variableCalls;
    }
}
