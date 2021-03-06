<?php

namespace GenSys\GenerateBundle\PhpParser\Filter\Node;

use PhpParser\Node;
use PhpParser\Node\Stmt\Use_;

class UseFilter extends AbstractNodeFilter
{
    /**
     * @param Node[] $nodes
     * @return Node[] $nodes
     */
    public function filter(iterable $nodes): iterable
    {
        return $this->nodeFinder->find($nodes, static function (Node $node) {
            return $node instanceof Use_;
        });
    }
}
