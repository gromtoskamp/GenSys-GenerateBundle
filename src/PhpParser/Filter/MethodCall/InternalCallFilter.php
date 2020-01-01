<?php

namespace GenSys\GenerateBundle\PhpParser\Filter\MethodCall;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;

class InternalCallFilter implements MethodCallFilter
{
    private const INTERNAL_CALL_VAR_NAME = 'this';

    /**
     * @param MethodCall[] $methodCalls
     * @return MethodCall[]
     */
    public function filter(iterable $methodCalls): iterable
    {
        $internalCalls = [];

        /** @var MethodCall $methodCall */
        foreach ($methodCalls as $methodCall) {
            /** @var Variable $variable */
            $variable = $methodCall->var;
            if ($variable->name === self::INTERNAL_CALL_VAR_NAME) {
                $internalCalls[] = $methodCall;
            }
        }

        return $internalCalls;
    }
}
