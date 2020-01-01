<?php

namespace GenSys\GenerateBundle\PhpParser\Filter\Node;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;

class PropertyAssignmentFilter extends AbstractNodeFilter
{
    /**
     * @param Node[] $nodes
     * @return Node[]
     */
    public function filter(iterable $nodes): iterable
    {
        return $this->nodeFinder->find($nodes, static function (Node $node) {
            return $node instanceof Assign
                && $node->var instanceof PropertyFetch
                && $node->expr instanceof Variable;
        });
    }
}
