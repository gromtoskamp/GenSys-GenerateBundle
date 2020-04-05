<?php

namespace GenSys\GenerateBundle\PhpParser\Filter\MethodCall;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;

class PropertyFetchFilter implements MethodCallFilter
{

    /**
     * @param MethodCall[] $methodCalls
     * @return MethodCall[]
     */
    public function filter(iterable $methodCalls): array
    {
        $propertyFetches = [];
        foreach ($methodCalls as $methodCall) {
            if ($methodCall->var instanceof PropertyFetch) {
                $propertyFetches[] = $methodCall;
            }
        }

        return $propertyFetches;
    }
}
