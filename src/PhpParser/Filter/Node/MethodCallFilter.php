<?php

namespace GenSys\GenerateBundle\PhpParser\Filter\Node;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;

class MethodCallFilter extends AbstractNodeFilter
{
    /**
     * @param Node[] $nodes
     * @return MethodCall[] $nodes
     */
    public function filter(iterable $nodes): array
    {
        return $this->nodeFinder->find($nodes, static function (Node $node) {
            return $node instanceof MethodCall;
        });
    }
}
