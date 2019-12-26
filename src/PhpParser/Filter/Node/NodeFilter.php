<?php


namespace GenSys\GenerateBundle\PhpParser\Filter\Node;

use PhpParser\Node;

interface NodeFilter
{
    /**
     * @param Node[] $nodes
     * @return Node[] $nodes
     */
    public function filter(iterable $nodes): iterable;
}